<?php
namespace app\im\controller;

use \Request;
use \app\im\model\mongo\FriendApply;
use \app\im\model\mongo\ChatMember;
use \app\im\model\mongo\ChatList;
use \app\im\model\mongo\Chat;
use \app\im\model\mongo\Friend;
use \app\common\controller\SendData;
use \app\im\model\mongo\UserState;
use \app\im\model\mongo\ChatGroup;

class Action
{
    /** 添加好友申请 */
    public function friendAdd()
    {
      $post_data = Request::post();
      $return_data = [
        'err' => 1,
        'msg' => 'error'
      ];
      if(!isset($post_data['user_id']) || !isset($post_data['is_type']) || !isset($post_data['content'])){
        $return_data['msg'] = '参数错误';
        return json($return_data);
      }

      if(USER_ID == $post_data['user_id']){
        $return_data['msg'] = '你不能添加自己为好友';
        return json($return_data);
      }

      $friend_data = Friend::where([
        'user_id' => USER_ID,
        'friend_id' => $post_data['user_id'],
      ])
      ->select()
      ->toArray();
      if(count($friend_data)){
          $return_data['msg'] = '对方已经是你的好友了';
          return json($return_data);
      }

      $return_data['err'] = 0;

      $friend_data = Friend::where([
        'user_id' => $post_data['user_id'],
        'friend_id' => USER_ID,
      ])
      ->select()
      ->toArray();
      /** 这里是把对方删除了，对方没有删除自己，直接添加到自己通讯录 */
      if(count($friend_data)){
          Friend::create([
            'user_id' => USER_ID,
            'friend_id' =>  $post_data['user_id'],
            'from' => $friend_data[0]['from'],
          ]);
          $return_data['msg'] = '添加成功';
          return json($return_data);
      }

      /** 这里如果已经申请了，并且对方没有处理，更新原有的数据 */
      $is_apply = FriendApply::where([
        'apply_user_id' => USER_ID,
        'friend_user_id' => ($post_data['user_id'] * 1),
        'action' => 0,
      ])
      ->find();
      if($is_apply){
        FriendApply::update([
          'id' => $is_apply->id,
          'content' => $post_data['content'],
          'from' => $post_data['is_type'],
        ]);
      }else{
        FriendApply::create([
          'apply_user_id' => USER_ID,
          'friend_user_id' => $post_data['user_id'],
          'content' => $post_data['content'],
          'from' => $post_data['is_type'],
        ]);
      }
      $return_data['msg'] = '已申请添加';
      /** 通知对方处理 */
      SendData::sendToUid($post_data['user_id'], 'newFriend', [
        'num' => 1
      ]);
      return json($return_data);
    }

    /** 添加好友操作 */
    public function friendAddAction()
    {
      $post_data = Request::post();
      $return_data = [
        'err' => 1,
        'msg' => 'error',
      ];
      if(!isset($post_data['apply_id'])){
        return json($return_data);
      }

      $db_data = FriendApply::field('apply_user_id,content,action,from,time')
      ->where([
        'id' => $post_data['apply_id']
      ])
      ->select()
      ->toArray();
      if(count($db_data) == 0){
        $return_data['msg'] = '没有这条申请数据';
        return json($return_data);
      }
      if($db_data[0]['action']){
        $return_data['msg'] = '已经操作过这条数据了';
        return json($return_data);
      }

      $friend_data = Friend::where([
        'user_id' => USER_ID,
        'friend_id' => $db_data[0]['apply_user_id'],
      ])
      ->select()
      ->toArray();
      if(count($friend_data)){
          $return_data['msg'] = '对方已经是你的好友了';
          return json($return_data);
      }

      if($db_data[0]['apply_user_id'] == USER_ID){
          $return_data['msg'] = '你不能添加自己为好友';
          return json($return_data);
      }

      FriendApply::update([
        'id' => $post_data['apply_id'],
        'action' => 1,
      ]);

      $chat_user_ids = [ USER_ID,$db_data[0]['apply_user_id'] ];
      sort($chat_user_ids);
      $chat_user_ids = json_encode($chat_user_ids);
      $list_id = md5(uniqid('JWT',true) . rand(1, 100000));
      /** 增加会话列表 */
      ChatList::create([
        'user_id' => USER_ID,
        'list_id' => $list_id,
        'user_ids' => $chat_user_ids,
        'type' => 0,
      ]);
      ChatList::create([
        'user_id' => $db_data[0]['apply_user_id'],
        'list_id' => $list_id,
        'user_ids' => $chat_user_ids,
        'type' => 0,
      ]);
      /** 增加到成员表 */
      ChatMember::create([
        'list_id' => $list_id,
        'user_id' => $db_data[0]['apply_user_id'],
      ]);
      ChatMember::create([
        'list_id' => $list_id,
        'user_id' => USER_ID,
      ]);
      /** 增加到好友表 */
      Friend::create([
        'user_id' => USER_ID,
        'friend_id' => $db_data[0]['apply_user_id'],
        'from' => $db_data[0]['from'],
      ]);
      Friend::create([
        'user_id' => $db_data[0]['apply_user_id'],
        'friend_id' => USER_ID,
        'from' => $db_data[0]['from'],
      ]);
      /** 增加到对话表 */
      Chat::create([
        'list_id' => $list_id,
        'user_id' => $db_data[0]['apply_user_id'],
        'content_type' => 0,
        'msg_type' => 0,
        'content' => [
            'text' => $db_data[0]['content'],
        ],
        'time' => $db_data[0]['time'],
      ]);
      Chat::create([
        'list_id' => $list_id,
        'user_id' => USER_ID,
        'content_type' => 0,
        'msg_type' => 0,
        'content' => [
          'text' => '我通过了你的朋友验证请求,现在我们可以开始聊天了',
        ],
        'time' => time(),
      ]);

      /** 通知双方重新获取列表数据 */
      SendData::sendToUid(USER_ID, 'getChatList');
      SendData::sendToUid($db_data[0]['apply_user_id'],'getChatList');

      $return_data['err'] = 0;
      $return_data['msg'] = 'success';

      return json($return_data);
    }

    /** 更新头像状态 */
    public function upPhoto()
    {
      $return_data = [
        'err' => 1,
        'msg' => 'fail',
      ];

      $obj = self::userState(USER_ID);
      $obj->photo = 1;
      $obj->save();

      $return_data['err'] = 0;
      $return_data['msg'] = 'success';
      return json($return_data);
    }

    /** 更新头朋友圈背景图片 */
    public function upCircleImg()
    {
      $return_data = [
        'err' => 1,
        'msg' => 'fail',
      ];

      $obj = self::userState(USER_ID);
      $obj->circle_img = 1;
      $obj->save();

      $return_data['err'] = 0;
      $return_data['msg'] = 'success';
      return json($return_data);
    }

    /** 查看群聊状态 */
    public function groupState()
    {
      $post_data = Request::post();
      $return_data = [
        'err' => 1,
        'msg' => 'error',
        'data' => 0,
      ];
      if(!isset($post_data['list_id'])){
        $return_data['msg'] = 'list_id null';
        return json($return_data);
      }
      $return_data['err'] = 0;
      $return_data['msg'] = 'success';

      $chat_group = ChatGroup::field('main_id,is_msg')->where('list_id',$post_data['list_id'])->find();
      $chat_member_data = ChatMember::field('is_admin,is_msg')
      ->where([
        'list_id' => $post_data['list_id'],
        'user_id' => USER_ID,
      ])
      ->find();
      $is_msg = 0;
      $is_action = 0;
      /** 如果禁言了，自己不是群主和管理员的话，就不能发言 */
      if($chat_member_data->is_msg || ($chat_group->is_msg && $chat_group->main_id != USER_ID && $chat_member_data->is_admin == 0)){
        $is_msg = 1;
      }
      if($chat_group->main_id == USER_ID || $chat_member_data->is_admin){
        $is_action = 1;
      }
      $return_data['data'] = [
        'is_msg' => $is_msg,
        'is_action' => $is_action,
      ];
      return json($return_data);
    }

    /** 用户状态实列，如果有就返回有的实列，没有创建再返回实列 */
    private static function userState($user_id)
    {
      $obj = UserState::where('user_id',($user_id * 1))->find();
      if(!$obj){
        $obj = UserState::create([ 'user_id'=> $user_id ]);
      }
      return $obj;
    }

}
