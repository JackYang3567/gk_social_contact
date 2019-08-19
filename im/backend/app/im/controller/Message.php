<?php
namespace app\im\controller;

use \Request;
use \app\im\common\controller\NameFirstChar;
/** mongo表 */
use \app\im\model\mongo\Chat;
use \app\im\model\mongo\ChatList;
use \app\im\model\mongo\Friend;
use \app\im\model\mongo\ChatMember;
use \app\common\controller\SendData;
use \app\im\model\mongo\UserState;
use \app\im\model\mongo\ChatGroup;
use \app\im\model\mongo\Group;
/** mysql表 */
use \app\im\model\mysql\User;

class Message
{
  public function textMsg()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'error',
    ];
    if(!isset($post_data['list_id']) || !isset($post_data['content_type']) || !isset($post_data['content'])){
      $return_data['msg'] = 'msg data error';
      return json($return_data);
    }
    $post_data['content'] = json_decode($post_data['content']);

    if(!$chat_list = ChatList::field('id,type,status')->where('list_id',$post_data['list_id'])->find()){
        $return_data['msg'] = '没有这条会话,发送消息失败!';
        return json($return_data);
    }

    /** 如果是群聊禁言中不能发送消息 */
    if($chat_list->type == 1){
      $chat_group = ChatGroup::field('is_msg,main_id')->where('list_id',$post_data['list_id'])->find();
      $chat_member_data = ChatMember::field('is_admin,is_msg')
      ->where([
        'list_id' => $post_data['list_id'],
        'user_id' => USER_ID,
      ])
      ->find();
      /** 被禁言了或者群状态为禁言中，群主和管理员不被禁言 */
      if($chat_member_data->is_msg || ($chat_group->is_msg && $chat_group->main_id != USER_ID && $chat_member_data->is_admin == 0)){
        $return_data['err'] = '2';
        $return_data['msg'] = '禁言了...';
        return json($return_data);
      }
    }

    if($chat_list->status){
      $chat_list->status = 0;
      $chat_list->save();
    }

    $chat_obj = Chat::create([
      'list_id' => $post_data['list_id'],
      'user_id' => USER_ID,
      'content_type' => $post_data['content_type'],
      'msg_type' => 0,
      'content' => $post_data['content'],
      'time' => time(),
    ]);

    $member = ChatMember::field('user_id')
    ->where('list_id',$post_data['list_id'])
    ->select()
    ->toArray();

    /** 这里通知其他对象 */
    foreach ($member as $value) {
      if(USER_ID != $value['user_id']){
        ChatList::where([
          'list_id' => $post_data['list_id'],
          'user_id' => ($value['user_id'] * 1),
        ])
        ->setInc('no_reader_num',1);
      }

      $sex = User::field('sex')->get(USER_ID)->sex;

			$user_state = UserState::field('photo')->where('user_id',USER_ID)->find();

      $face = getShowPhoto($user_state,$sex,USER_ID,70);

      /** 发送通知 */
      SendData::sendToUid($value['user_id'], 'chatData', [
        'list_id' => $post_data['list_id'],
        'data' => [
          'type' => 0,
          'msg' => [
            'id' => $chat_obj->id,
            'type' => $post_data['content_type'],
            'time' => time(),
            'user_info' => [
              'uid' => USER_ID,
              'name' => '',
              'face' => $face,
            ],
            'content' => $post_data['content'],
          ],
        ]
      ]);
    }
    $return_data['err'] = 0;
    $return_data['msg'] = 'success';
    return json($return_data);
  }

  /** 获得与对方的会话id */
  public function getListId()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'fail',
    ];
    if(!isset($post_data['user_id'])){
      $return_data['msg'] = 'user_id data error';
      return json($return_data);
    }
    $chat_user_ids = [ USER_ID,($post_data['user_id'] * 1) ];
    sort($chat_user_ids);
    $chat_user_ids = json_encode($chat_user_ids);
    $chat_list_data = ChatList::field('list_id')
    ->where([
      'user_id' => USER_ID,
      'user_ids' => $chat_user_ids,
    ])
    ->find();
    /** 如果没有对话（说明与对方对方对话数据已经升级为群了）新建会话数据 */
    if($chat_list_data){
      $list_id = $chat_list_data->list_id;
    }else{
      $chat_user_ids = [ USER_ID,$post_data['user_id'] ];
      sort($chat_user_ids);
      $chat_user_ids = json_encode($chat_user_ids);
      $list_id = md5(uniqid('JWT',true) . rand(1, 100000));
      /** 增加会话列表 */
      ChatList::create([
        'user_id' => USER_ID,
        'list_id' => $list_id,
        'user_ids' => $chat_user_ids,
        'status' => 1,
        'type' => 0,
      ]);
      ChatList::create([
        'user_id' => $post_data['user_id'],
        'list_id' => $list_id,
        'user_ids' => $chat_user_ids,
        'status' => 1,
        'type' => 0,
      ]);
      /** 增加到成员表 */
      ChatMember::create([
        'list_id' => $list_id,
        'user_id' => $post_data['user_id'],
      ]);
      ChatMember::create([
        'list_id' => $list_id,
        'user_id' => USER_ID,
      ]);
    }
    $return_data['err'] = 0;
    $return_data['msg'] = 'success';
    $return_data['data'] = [
      'list_id' => $list_id,
    ];
    return json($return_data);
  }

  public function updataNoReader()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'error',
    ];
    if(!isset($post_data['list_id'])){
      $return_data['msg'] = 'list_id data error';
      return json($return_data);
    }
    ChatList::where([
      'list_id' => $post_data['list_id'],
      'user_id' => USER_ID,
    ])
    ->update([
      'no_reader_num' => 0,
    ]);
    $return_data['err'] = 0;
    $return_data['msg'] = 'success';
    return json($return_data);
  }

  /** 获得会话详情 */
  public function getChatDetails()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'error',
    ];
    if(!isset($post_data['list_id'])){
      $return_data['msg'] = 'list_id data error';
      return json($return_data);
    }
    $member = ChatMember::field('user_id,is_admin')
    ->where([
      [ 'list_id','=',$post_data['list_id'] ],
    ])
    ->order('time','ASC')
    ->select()
    ->toArray();
    $data = [];
    $is_action = 0;
    if(count($member)){
      foreach ($member as $key => $value) {
        /** 如果是自己好友，显示自己的备注 */
        $db_user = User::get($value['user_id']);
        if(($friend = Friend::where([
          'user_id' => USER_ID,
          'friend_id' => $value['user_id'],
        ])->find()) && $friend->remarks){
          $show_name = $friend->remarks;
        }else{
          $show_name = $db_user->nickname;
        }
        $user_state_obj = UserState::field('photo')->where('user_id',$value['user_id'])->find();
        $face = getShowPhoto($user_state_obj,$db_user->sex,$value['user_id'],90);
        $data[] = [
          'user_id' => $value['user_id'],
          'show_name' => $show_name,
          'photo' => $face,
        ];
        /** 如果自己是管理员（获得权限） */
        if($value['user_id'] == USER_ID && $value['is_admin']){
          $is_action = 1;
        }
      }
    }
    $return_data['err'] = 0;
    $return_data['msg'] = 'success';

    $type = ChatList::where([
      'user_id' => USER_ID,
      'list_id' => $post_data['list_id'],
    ])
    ->find()
    ->type;
    switch ($type) {
      case 1:
        $group = ChatGroup::where('list_id',$post_data['list_id'])
        ->find()
        ->toArray();
        if(isset($group['is_photo']) && $group['is_photo']){
          $group['is_photo'] = '/group_photo/' . $post_data['list_id'] . '/90.jpg';
        }else{
          $group['is_photo'] = 'default_group_photo/90.jpg';
        }
        $group['is_photo'] .= '?_=' . rand(1, 100);
        /** 如果自己是群主也可以操作 */
        if($group['main_id'] == USER_ID){
          $is_action = 1;
        }
        break;
      default:
        $group = [];
        break;
    }
    $return_data['data'] = [
      'member' => $data,
      'type' => $type,
      'group' => $group,
      'is_action' => $is_action,
    ];
    return json($return_data);
  }

  /** 添加会话成员（从会话变成群聊） */
  public function addChat()
  {
      $post_data = Request::post();
      $return_data = [
        'err' => 1,
        'msg' => 'fail',
      ];
      if(!isset($post_data['list_id']) || !isset($post_data['users'])){
        $return_data['msg'] = 'data error';
        return json($return_data);
      }

      if(!($db_chat_list = ChatList::field('type,user_ids')
      ->where([
        'user_id' => USER_ID,
        'list_id' => $post_data['list_id']
      ])
      ->find())){
          $return_data['msg'] = '没有这条对话';
          return json($return_data);
      }

      $user_ids = json_decode($db_chat_list['user_ids'],true);
      $db_chat_list['user_ids'] = $user_ids;
      $post_data['users'] = json_decode($post_data['users'],true);
      foreach ($post_data['users'] as $user_id) {
        $user_id *= 1;
        /** 如果用户存在这个会话中，则跳过这个用户 */
        if(in_array($user_id,$user_ids)){
          continue;
        }
        $user_ids[] = $user_id;
        /** 增加到会话列表 */
        ChatList::create([
          'user_id' => $user_id,
          'list_id' => $post_data['list_id'],
          'user_ids' => '',
          'type' => 1,
        ]);
        /** 增加到成员 */
        ChatMember::create([
          'list_id' => $post_data['list_id'],
          'user_id' => $user_id,
        ]);
        $content = [
            'text' => User::get(USER_ID)->nickname . '邀请了' . User::get($user_id)->nickname . '进入会话',
        ];
        /** 增加一条系统消息 */
        $chat_obj = Chat::create([
          'list_id' => $post_data['list_id'],
          'user_id' => 0,
          'content_type' => 0,
          'msg_type' => 1,
          'content' => $content,
          'time' => time(),
        ]);
        /** 通知新加入的成员重新获取会话列表 */
        SendData::sendToUid($user_id, 'getChatList');
        /** 给其他原先用户发送加入的消息 */
        foreach ($db_chat_list['user_ids'] as $is_user_id) {
          SendData::sendToUid($is_user_id, 'chatData', [
            'list_id' => $post_data['list_id'],
            'data' => [
              'type' => 1,
              'msg' => [
                'user_info' => [
                  'uid' => 0,
                ],
                'id' => $chat_obj->id,
                'type' => 0,
                'content' => $content,
              ],
            ]
          ]);
        }
      }
      /** 如果还没有升级为群聊，添加群表 */
      if($db_chat_list['type'] == 0){
        ChatGroup::create([
          'list_id' => $post_data['list_id'],
          /** 默认第一个发起会话的人就是群主 */
          'main_id' => (Chat::where('list_id',$post_data['list_id'])->order('time','ASC')->find())->user_id,
        	'name' => ('群聊' . time() . rand(1, 100000)),
        ]);
      }
      sort($user_ids);
      /** 更新这条会话类型为群聊，和更新成员字段 */
      ChatList::where('list_id',$post_data['list_id'])->update([ 'type'=>1,'user_ids'=>json_encode($user_ids) ]);
      $return_data['err'] = 0;
      $return_data['msg'] = '已邀请' .count($post_data['users']) . '位好友';
      return json($return_data);
  }

  /** 获得群会话数据 */
  public function getGroupData()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'fail',
      'data' => '',
    ];
    if(!isset($post_data['list_id']) || !isset($post_data['type'])){
      $return_data['msg'] = 'data error';
      return json($return_data);
    }
    $db_group = ChatGroup::field($post_data['type'])->where('list_id',$post_data['list_id'])->find();
    if(!$db_group){
      $return_data['msg'] = 'group data error';
      return json($return_data);
    }
    $return_data['err'] = 0;
    $return_data['msg'] = 'success';
    $return_data['data'] = $db_group->{$post_data['type']};
    return json($return_data);
  }

  /** 设置群会话数据 */
  public function setGroupData()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'fail',
    ];
    if(!isset($post_data['list_id']) || !isset($post_data['type']) || !isset($post_data['content'])){
      $return_data['msg'] = 'data error';
      return json($return_data);
    }
    $db_group = ChatGroup::field($post_data['type'])->where('list_id',$post_data['list_id'])->find();
    if(!$db_group){
      $return_data['msg'] = 'group data error';
      return json($return_data);
    }
    ChatGroup::where('list_id',$post_data['list_id'])->update([
      $post_data['type'] => $post_data['content']
    ]);
    $return_data['err'] = 0;
    $return_data['msg'] = 'success';
    return json($return_data);
  }

	/** 设置群的禁言 */
	public function groupIsMsg()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'fail',
    ];
    if(!isset($post_data['list_id']) || !isset($post_data['value'])){
      $return_data['msg'] = 'data error';
      return json($return_data);
    }
    ChatGroup::where('list_id',$post_data['list_id'])->update([
      'is_msg' => $post_data['value'],
    ]);
    $return_data['err'] = 0;
    $return_data['msg'] = 'success';
    return json($return_data);
  }

  /** 获得群成员 */
  public function groupMember()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'fail',
      'data' => [],
    ];
    if(!isset($post_data['list_id'])){
      $return_data['msg'] = 'data error';
      return json($return_data);
    }

    $group_data = ChatGroup::field('main_id')->where('list_id',$post_data['list_id'])->find();

    /** 如果是群主自己，获得除自己所有成员数据，如果是管理员，获得除去自己，除去管理员的数据 */
    if($group_data->main_id == USER_ID){
      $where = [
        [ 'list_id','=',$post_data['list_id'] ],
        [ 'user_id','<>',USER_ID ],
      ];
    }else{
      $where = [
        [ 'list_id','=',$post_data['list_id'] ],
        [ 'user_id','<>',USER_ID ],
        [ 'user_id','<>',$group_data->main_id ],
        [ 'is_admin','=',0 ],
      ];
    }
    $member_data = ChatMember::field('user_id,nickname')
    ->where($where)
    ->order('time','ASC')
    ->select()
    ->toArray();
    foreach ($member_data as $key => &$value) {
      $state_data = UserState::field('photo')
      ->where('user_id',$value['user_id'])
      ->find();
      $user_data = User::field('sex,nickname')->get($value['user_id']);
      $friend_data = Friend::field('remarks')->where([
        'user_id' => USER_ID,
        'friend_id' => ($value['user_id'] * 1),
      ])
      ->find();
      if($friend_data && $friend_data->remarks){
        $value['nickname'] = $friend_data->remarks;
      }else{
        if(!$value['nickname']){
          $value['nickname'] = $user_data->nickname;
        }
      }
      $value['photo'] = getShowPhoto($state_data,$user_data->sex,$value['user_id'],90);
    }
    $return_data['err'] = 0;
    $return_data['msg'] = 'success';
    $return_data['data'] = $member_data;
    return json($return_data);
  }

  /** 移除群成员 */
  public function removeChat()
  {
      $post_data = Request::post();
      $return_data = [
        'err' => 1,
        'msg' => 'fail',
      ];
      if(!isset($post_data['list_id']) || !isset($post_data['users'])){
        $return_data['msg'] = 'data error';
        return json($return_data);
      }

      $group_data = ChatGroup::field('main_id')
      ->where('list_id',$post_data['list_id'])
      ->find();

      if(!$group_data){
        $return_data['msg'] = '群聊数据有误';
        return json($return_data);
      }

      $chat_member_data = ChatMember::field('is_admin')
      ->where([
        'list_id' => $post_data['list_id'],
        'user_id' => USER_ID,
      ])
      ->find();
      if($group_data->main_id != USER_ID && $chat_member_data->is_admin == 0){
        $return_data['msg'] = '你没有权限,操作已取消';
        return json($return_data);
      }

      $db_chat_list = ChatList::field('type,user_ids')
      ->where([
        'user_id' => USER_ID,
        'list_id' => $post_data['list_id']
      ])
      ->find();

      if(!$db_chat_list){
        $return_data['msg'] = '没有这条对话';
        return json($return_data);
      }

      $user_ids = json_decode($db_chat_list['user_ids'],true);
      $post_data['users'] = json_decode($post_data['users'],true);
      $last_user_ids = array_diff($user_ids,$post_data['users']);

      foreach ($post_data['users'] as $user_id) {
        $user_id *= 1;
        /** 删除会话列表 */
        ChatList::where([
          'user_id' => $user_id,
          'list_id' => $post_data['list_id'],
        ])
        ->delete();
        /** 删除成员列表 */
        ChatMember::where([
          'list_id' => $post_data['list_id'],
          'user_id' => $user_id,
        ])
        ->delete();
        $content = [
            'text' => User::get($user_id)->nickname . '被' . User::get(USER_ID)->nickname . '移除了群聊',
        ];
        /** 增加一条系统消息 */
        $chat_obj = Chat::create([
          'list_id' => $post_data['list_id'],
          'user_id' => 0,
          'content_type' => 0,
          'msg_type' => 1,
          'content' => $content,
          'time' => time(),
        ]);
        /** 通知被移除的成员重新获取会话列表 */
        SendData::sendToUid($user_id, 'getChatList');
        /** 通知还在群里的成员 */
        foreach ($last_user_ids as $is_user_id) {
          SendData::sendToUid($is_user_id, 'chatData', [
            'list_id' => $post_data['list_id'],
            'data' => [
              'type' => 1,
              'msg' => [
                'user_info' => [
                  'uid' => 0,
                ],
                'id' => $chat_obj->id,
                'type' => 0,
                'content' => $content,
              ],
            ]
          ]);
        }
      }
      sort($last_user_ids);
      /** 更新这条会话成员 */
      ChatList::where('list_id',$post_data['list_id'])->update([ 'user_ids'=>json_encode($last_user_ids) ]);
      $return_data['err'] = 0;
      $return_data['msg'] = '已移除' .count($post_data['users']) . '位成员';
      return json($return_data);
  }

  /** 获得群头像 */
  public function getGroupPhoto()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'fail',
    ];
    if(!isset($post_data['list_id'])){
      $return_data['msg'] = 'data error';
      return json($return_data);
    }
    $group = ChatGroup::where('list_id',$post_data['list_id'])->find()->toArray();
    if(isset($group['is_photo']) && $group['is_photo']){
      $photo = '/group_photo/' . $post_data['list_id'] . '/300.jpg';
    }else{
      $photo = 'default_group_photo/300.jpg';
    }
    $return_data['err'] = 0;
    $return_data['msg'] = 'success';
    $return_data['data'] = $photo;
    return json($return_data);
  }

  /** 更新群头像状态 */
  public function upGroupPhoto()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'fail',
    ];

    if(!isset($post_data['list_id'])){
      $return_data['msg'] = 'data error';
      return json($return_data);
    }

    $chat_group = ChatGroup::field('is_photo')->where('list_id',$post_data['list_id'])->find();

    if(!$chat_group){
      $return_data['msg'] = '群聊数据错误';
      return json($return_data);
    }

    $chat_group->is_photo = 1;
    $chat_group->save();

    $return_data['err'] = 0;
    $return_data['msg'] = 'success';
    return json($return_data);
  }

  /** 获得群管理员表 */
  public function getGroupAdmin()
  {
    $post_data = Request::post();
    if(!isset($post_data['list_id']) || !isset($post_data['type'])){
      $return_data['msg'] = 'data error';
      return json($return_data);
    }
    $group_data = ChatGroup::field('main_id')->where('list_id',$post_data['list_id'])->find();
    if(!$group_data){
      $return_data['msg'] = '没有这条群聊消息';
      return json($return_data);
    }

    $chat_member_data = ChatMember::field('is_admin')
    ->where([
      'list_id' => $post_data['list_id'],
      'user_id' => USER_ID,
    ])
    ->find();

    if($group_data->main_id != USER_ID && $chat_member_data->is_admin == 0){
      $return_data['msg'] = '你没有权限,操作已取消';
      return json($return_data);
    }

    /** 如果是群主自己，获得除自己所有成员数据，如果是管理员，获得除去自己，除去管理员的数据 */
    if($group_data->main_id == USER_ID){
      $where = [
        [ 'list_id','=',$post_data['list_id'] ],
        [ 'user_id','<>',USER_ID ],
      ];
    }else{
      $where = [
        [ 'list_id','=',$post_data['list_id'] ],
        [ 'user_id','<>',USER_ID ],
        [ 'user_id','<>',$group_data->main_id ],
        [ 'is_admin','=',0 ],
      ];
    }

    $db_data =  ChatMember::field('user_id,nickname,is_admin,is_msg')
    ->where($where)
    ->select()
    ->toArray();
    $char_array = [];
    $data = [];
    $user_ids = [];
    if(count($db_data)){
      foreach ($db_data as $value) {
        $User = User::field('nickname,sex')->get($value['user_id']);
        if($value['nickname']){
          $name = $value['nickname'];
        }else{
          $name = $User->nickname;
        }
        $user_state_obj = UserState::field('photo')->where('user_id',$value['user_id'])->find();
        $face = getShowPhoto($user_state_obj,$User->sex,$value['user_id'],90);
        $char = NameFirstChar::get($name);

        $is_admin = false;
        switch ($post_data['type']) {
          case 1:
            if($value['is_admin']){
              $is_admin = true;
              $user_ids[] = $value['user_id'] . '';
            }
            break;
          case 2:
            if($value['is_msg']){
              $is_admin = true;
              $user_ids[] = $value['user_id'] . '';
            }
            break;
          default:
            $return_data['msg'] = '未知类型';
            return json($return_data);
            break;
        }
        $char_array[$char][] = [
          'photo' => $face,
          'user_id' => $value['user_id'],
          'name' => $name,
          'is_admin' => $is_admin,
        ];
      }
      foreach ($char_array as $key => $value) {
        $data[] = [
          'letter' => $key,
          'data' => $value,
        ];
      }
    }
    $is_field = array_column($data,'letter');
    array_multisort($is_field,SORT_ASC,$data);

    $return_data = [
      'err' => 0,
      'data' => [
          'list' => $data,
          'user_ids' => $user_ids,
      ],
    ];
    return json($return_data);
  }

  /** 设置管理员 */
  public function setGroupAdmin()
  {
      $post_data = Request::post();
      $return_data = [
        'err' => 1,
        'msg' => 'fail',
      ];
      if(!isset($post_data['list_id']) || !isset($post_data['users']) || !isset($post_data['type'])){
        $return_data['msg'] = 'data error';
        return json($return_data);
      }

      $group_data = ChatGroup::field('main_id')->where('list_id',$post_data['list_id'])->find();
      if(!$group_data){
        $return_data['msg'] = '没有这条群聊消息';
        return json($return_data);
      }

      $chat_member_data = ChatMember::field('is_admin')
      ->where([
        'list_id' => $post_data['list_id'],
        'user_id' => USER_ID,
      ])
      ->find();

      if($group_data->main_id != USER_ID && $chat_member_data->is_admin == 0){
        $return_data['msg'] = '你没有权限,操作已取消';
        return json($return_data);
      }

      switch ($post_data['type']) {
        case 1:
          ChatMember::where([
             [ 'list_id','=',$post_data['list_id'] ],
             [ 'user_id','<>',USER_ID ],
          ])
          ->update([
            'is_admin' => 0,
          ]);
          $post_data['users'] = json_decode($post_data['users'],true);
          foreach ($post_data['users'] as &$value) {
            $value *= 1;
          }
          ChatMember::where([
            [ 'list_id','=',$post_data['list_id'] ],
            [ 'user_id','in',$post_data['users'] ],
          ])
          ->update([
            'is_admin' => 1,
          ]);
          break;
        case 2:
          /** 群主设置所有 */
          if($group_data->main_id == USER_ID){
            $where = [
               [ 'list_id','=',$post_data['list_id'] ],
               [ 'user_id','<>',USER_ID ],
            ];
          }
          /** 管理员只能设置普通会员 */
          else{
            $where = [
               [ 'list_id','=',$post_data['list_id'] ],
               [ 'user_id','<>',USER_ID ],
               [ 'is_admin','=',0 ],
            ];
          }
          ChatMember::where($where)
          ->update([
            'is_msg' => 0,
          ]);
          $post_data['users'] = json_decode($post_data['users'],true);
          foreach ($post_data['users'] as &$value) {
            $value *= 1;
          }
          ChatMember::where([
            [ 'list_id','=',$post_data['list_id'] ],
            [ 'user_id','in',$post_data['users'] ],
          ])
          ->update([
            'is_msg' => 1,
          ]);
          break;
        default:
          $return_data['msg'] = '未知类型';
          return json($return_data);
          break;
      }
      $return_data['err'] = 0;
      $return_data['msg'] = '已设置';
      return json($return_data);
  }

  /** 撤回消息 */
  public function withdraw()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'fail',
    ];
    if(!isset($post_data['list_id']) || !isset($post_data['type']) || !isset($post_data['msg_id'])){
      $return_data['msg'] = 'data error';
      return json($return_data);
    }

    $chat_data = Chat::field('time,user_id')
    ->where([
      'list_id' => $post_data['list_id'],
      'id' => $post_data['msg_id'],
    ])
    ->find();

    if(!$chat_data){
      $return_data['msg'] = '没有这条消息';
      return json($return_data);
    }

    switch ($post_data['type']) {
      /** 这里是对话，撤回消息，时间不能超过 2 分钟 */
      case 0:
        if($chat_data->user_id != USER_ID){
          $return_data['msg'] = '不能撤回其他人的消息';
          return json($return_data);
        }
        if(intval(time() - $chat_data->time / 60) > 2){
          $return_data['msg'] = '只能撤回2分钟之内的消息';
          return json($return_data);
        }
        break;
      case 1:
        $member_data = ChatMember::field('is_admin')
        ->where([
          'list_id' => $post_data['list_id'],
          'user_id' => USER_ID,
        ])
        ->find();

        if(!$member_data){
          $return_data['msg'] = '成员消息错误';
          return json($return_data);
        }

        $group_data = ChatGroup::field('main_id')
        ->where('list_id',$post_data['list_id'])
        ->find();

        if(!$group_data){
          $return_data['msg'] = '群聊数据错误';
          return json($return_data);
        }

        /** 如果自己是群主，可以撤回任何时间的消息 */
        if(USER_ID != $group_data->main_id){
          if($chat_data->user_id == $group_data->main_id){
            $return_data['msg'] = '不能撤回群主的消息';
            return json($return_data);
          }
          /** 如果自己是管理员，只能撤回自己的和普通会员的消息 */
          if($member_data->is_admin){
            $member_obj = ChatMember::field('is_admin')
            ->where([
              'list_id' => $post_data['list_id'],
              'user_id' => $chat_data->user_id,
            ])
            ->find();
            if($member_obj->is_admin && $chat_data->user_id != USER_ID){
              $return_data['msg'] = '不能撤回其他管理员的消息';
              return json($return_data);
            }
          }
          /** 如果自己不是管理员，不是群主，只能撤回自己2分钟之内的消息 */
          else {
              if($chat_data->user_id != USER_ID){
                $return_data['msg'] = '不能撤回其他人的消息';
                return json($return_data);
              }
              if(intval(time() - $chat_data->time / 60) > 2){
                $return_data['msg'] = '只能撤回2分钟之内的消息';
                return json($return_data);
              }
          }
        }
        break;
      default:
        $return_data['msg'] = '未知错误';
        return json($return_data);
        break;
    }

    $chat_member_data = ChatMember::field('user_id')
    ->where('list_id',$post_data['list_id'])
    ->select()
    ->toArray();

    Chat::where([
      'list_id' => $post_data['list_id'],
      'id' => $post_data['msg_id'],
    ])
    ->delete();

    /** 通知所有成员 */
    foreach ($chat_member_data as $value) {
      SendData::sendToUid($value['user_id'], 'deleteChat', [
        'list_id' => $post_data['list_id'],
        'msg_id' => $post_data['msg_id'],
      ]);
    }

    $return_data['err'] = 0;
    $return_data['msg'] = '已撤回';
    return json($return_data);
  }

  /** 发起群聊 */
  public function addGroup()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'fail',
    ];
    if(!isset($post_data['list_id']) || !isset($post_data['users'])){
      $return_data['msg'] = 'data error';
      return json($return_data);
    }
    $post_data['users'] = json_decode($post_data['users'],true);
    if(count($post_data['users']) < 1){
      $return_data['msg'] = '请选择群聊成员';
      return json($return_data);
    }

    $post_data['users'][] = USER_ID;
    sort($post_data['users']);
    $chat_user_ids = json_encode($post_data['users']);
    $list_id = md5(uniqid('JWT',true) . rand(1, 100000));

    /** 增加到群表 */
    ChatGroup::create([
      'list_id' => $list_id,
      'main_id' => USER_ID,
      'name' => ('群聊' . time() . rand(1, 100000)),
    ]);

    $user_names = '';

    foreach ($post_data['users'] as $user_id) {
      /** 增加会话列表 */
      ChatList::create([
        'user_id' => $user_id,
        'list_id' => $list_id,
        'user_ids' => $chat_user_ids,
        'type' => 1,
      ]);
      /** 增加到成员表 */
      ChatMember::create([
        'list_id' => $list_id,
        'user_id' => $user_id,
      ]);
      $user_names .= ($user_names ? ',' : '') . User::field('nickname')->get($user_id)->nickname;
    }

    /** 增加一条系统消息 */
    $chat_obj = Chat::create([
      'list_id' => $list_id,
      'user_id' => 0,
      'content_type' => 0,
      'msg_type' => 1,
      'content' => [
        'text' => $user_names . '加入群聊',
      ],
      'time' => time(),
    ]);

    foreach ($post_data['users'] as $user_id) {
      /** 通知双方重新获取列表数据 */
      SendData::sendToUid($user_id, 'getChatList');
    }

    $return_data['err'] = 0;
    $return_data['msg'] = '已成功创建';
    return json($return_data);
  }

}
