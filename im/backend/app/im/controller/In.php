<?php
namespace app\im\controller;

use \Request;
use \app\im\model\mysql\User;
use \app\common\controller\Jwt;
use \app\common\model\mysql\System;
use \app\im\model\mongo\Chat;
use \app\im\model\mongo\ChatList;
use \app\im\model\mongo\Friend;
use \app\im\model\mongo\ChatMember;

class In
{
	public function login()
	{
		$post_data = Request::post();
		$return_data = [
			'err' => 1,
			'msg' => 'fail',
		];
		if(!isset($post_data['username']) || !isset($post_data['password']) || $post_data['username'] == '' || $post_data['password'] == ''){
			$return_data['msg'] = '登陆数据有误';
			return json($return_data);
		}
		$user = User::field('id,password,status')->where('username', $post_data['username'])->find();

		if(!$user || $user->password !== md5($post_data['password'])){
			$return_data['msg'] = '用户名或者密码错误';
			return json($return_data);
		}

		if($user->status > 0){
			$return_data['msg'] = '用户已被' . [ '锁定','冻结' ][$user->status];
			return json($return_data);
		}

		$return_data['err'] = 0;
		$return_data['msg'] = '登陆成功';
		$return_data['data'] = [
			'token' => self::createToken($user->id),
		];
		return json($return_data);
	}

	public function reg()
	{
		$post_data = Request::post();
		$return_data = [
			'err' => 1,
			'msg' => 'fail'
		];
		if(!isset($post_data['username']) || !isset($post_data['password']) || $post_data['username'] == '' || $post_data['password'] == ''){
			$return_data['msg'] = '注册数据有误';
			return json($return_data);
		}

		if (!preg_match("/^\d{1,20}$/", $post_data['agent_id'])) {
			$return_data['msg'] = '社群号只能包括下划线、数字、字母,并且不能超过20个';
			return json($return_data);
		}
		if (!preg_match("/^\w{1,20}$/", $post_data['username'])) {
			$return_data['msg'] = '社群号只能包括下划线、数字、字母,并且不能超过20个';
			return json($return_data);
		}

		if (!preg_match("/^\w{1,20}$/", $post_data['password'])) {
			$return_data['msg'] = '密码只能包括下划线、数字、字母,长度6-20位';
			return json($return_data);
		}

		$db_data = User::where('username', $post_data['username'])->find();
		if($db_data){
			$return_data['msg'] = '这个用户名已经存在了';
			return json($return_data);
		}
		if($user = User::create([
			'agent_id' => $post_data['agent_id'],
			'is_customer_service' => $post_data['is_customer_service'],
			'username' => $post_data['username'],
			'password' => $post_data['password'],
			'nickname' => $post_data['username'],
		])){
			/** 这里注册成功后，自动添加客服为好友 */

			/** 客服id */
			$friend_id = 7;
			$user->id *= 1;
			if($friend_id && !Friend::field('id')->where([
				'user_id' =>  $user->id,
				'friend_id' => $friend_id,
			])
			->find()){
				$chat_user_ids = [ $user->id,$friend_id ];
				sort($chat_user_ids);
				$chat_user_ids = json_encode($chat_user_ids);
				$list_id = md5(uniqid('JWT',true) . rand(1, 100000));

				/** 增加会话列表 */
				ChatList::create([
					'user_id' => $user->id,
					'list_id' => $list_id,
					'user_ids' => $chat_user_ids,
					'type' => 0,
					'top' => 1,
					'top_time' => time(),
				]);
				ChatList::create([
					'user_id' => $friend_id,
					'list_id' => $list_id,
					'user_ids' => $chat_user_ids,
					'type' => 0,
					'top' => 1,
					'top_time' => time(),
				]);

				/** 增加到成员表 */
				ChatMember::create([
					'list_id' => $list_id,
					'user_id' => $user->id,
				]);
				ChatMember::create([
					'list_id' => $list_id,
					'user_id' => $friend_id,
				]);

				/** 增加到好友表 */
				Friend::create([
					'user_id' => $user->id,
					'friend_id' => $friend_id,
					'from' => 4,
				]);
				Friend::create([
					'user_id' => $friend_id,
					'friend_id' => $user->id,
					'from' => 4,
				]);

				/** 增加到对话表 */
				Chat::create([
					'list_id' => $list_id,
					'user_id' => $friend_id,
					'content_type' => 0,
					'msg_type' => 0,
					'content' => [
						'text' => '欢迎来到微微社群,有什么问题您都可以联系我!',
					],
					'time' => time(),
				]);
			}

			$return_data['err'] = 0;
			$return_data['msg'] = '注册成功';
			$return_data['data'] = [
				'token' => self::createToken($user->id),
			];
		}
		return json($return_data);
	}

	private static function createToken($user_id)
	{
		$jwt = new Jwt;
		$db_data = System::where('key','JWT')->select()->toArray();
		Jwt::$key = $db_data[0]['value']['key']['value'];
		Jwt::$timeNum = $db_data[0]['value']['time']['value'];
		$payload = [
			'user_id' => $user_id,
		];
    return $jwt->getToken($payload);
	}

}
