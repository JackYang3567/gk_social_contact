<?php
namespace app\im\controller;

use \Request;
/** mongo表 */
use \app\im\model\mongo\Circle as dbCircle;
use \app\im\model\mongo\CircleComments;
use \app\common\controller\SendData;
use \app\im\model\mongo\Friend;
use \app\im\model\mongo\UserState;

class Circle
{
  /** 添加朋友圈消息 */
  public function sendMsg()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'fail',
    ];
    if(!isset($post_data['content'])){
      $return_data['msg'] = '参数错误';
      return json($return_data);
    }

    $post_data['content'] = json_decode($post_data['content'],true);
    if(!$circle_obj = dbCircle::create([
      'user_id' => USER_ID,
      'type' => 0,
      'content' => $post_data['content'],
    ])){
      return json($return_data);
    }

    /** 这里通知其他好友(我没屏蔽对方，对方也没有屏蔽我的) */
    $db_friend_data = Friend::field('friend_id,remarks')->where([
      'user_id' => USER_ID,
      'show_circle_he' => 0,
    ])->select()->toArray();
    $friend_ids = [];
    if(count($db_friend_data)){
      foreach ($db_friend_data as $value) {
        if(Friend::field('show_circle_my')->where([
          'user_id' => $value['friend_id'],
          'friend_id' => USER_ID,
          'show_circle_my' => 0,
        ])->find()){
          $friend_ids[] = $value['friend_id'];
        }
      }
    }

    /** 提示好友，朋友圈有动态了 */
    if(count($friend_ids)){
      foreach ($friend_ids as $user_id) {
        $obj = self::userState($user_id);
        $obj->reader_circle = 1;
        $obj->save();
        SendData::sendToUid($user_id, 'circleTips', []);
      }
    }

    $return_data['err'] = 0;
    $return_data['msg'] = 'success';
    $return_data['data'] = [
      'circle_id' => $circle_obj->id,
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

  /** 赞操作 */
  public function likeAction()
  {
      $post_data = Request::post();
      $return_data = [
        'err' => 1,
        'msg' => 'fail',
      ];
      if(!isset($post_data['is_like']) || !isset($post_data['id'])){
        $return_data['msg'] = '参数错误';
        return json($return_data);
      }
      $circle_obj = dbCircle::get($post_data['id']);
      if(!$circle_obj){
        $return_data['msg'] = '没有这条数据';
        return json($return_data);
      }
      if($post_data['is_like'] && !array_search(USER_ID,$circle_obj->like)){
        $is_likes = $circle_obj->like;
        $is_likes[] = USER_ID;
        $circle_obj->like = $is_likes;
        $action = 1;
      }else{
        $is_likes = $circle_obj->like;
        $key = array_search(USER_ID,$is_likes);
        array_splice($is_likes,$key,1);
        $circle_obj->like = $is_likes;
        $action = 0;
      }
      $circle_obj->save();
      $return_data['err'] = 0;
      $return_data['msg'] = 'success';
      $return_data['data'] = [
        'action' => $action,
      ];
      return json($return_data);
  }

  /** 评论 */
  public function comment()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'fail',
    ];
    if(!isset($post_data['message']) || !isset($post_data['id']) || !isset($post_data['chat_user_id'])){
      $return_data['msg'] = '参数错误';
      return json($return_data);
    }
    $circle = dbCircle::get($post_data['id']);
    if(!$circle){
      $return_data['msg'] = '这条朋友圈不存在';
      return json($return_data);
    }
    if($circle_comments = CircleComments::create([
      'circle_id' => $post_data['id'],
      'user_id' => USER_ID,
      'chat_user_id' => $post_data['chat_user_id'],
      'content' => $post_data['message'],
    ])){
      if(USER_ID != $circle->user_id){
        /** 这里通知被评论的人 */
        $obj = self::userState($post_data['chat_user_id']);
        $reader_circle_ids = $obj->reader_circle_ids;
        if(isset($reader_circle_ids[$post_data['id']])){
          $reader_circle_ids[$post_data['id']] += 1;
        }else{
          $reader_circle_ids[$post_data['id']] = 1;
        }
        $obj->reader_circle_ids = $reader_circle_ids;
        $obj->save();
        SendData::sendToUid($post_data['chat_user_id'], 'cricleChatTips', []);
      }
      $return_data['err'] = 0;
      $return_data['msg'] = 'success';
    }
    return json($return_data);
  }

}
