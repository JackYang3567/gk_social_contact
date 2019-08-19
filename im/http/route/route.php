<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;


Route::get('yzmimg','super/login/yzmimg');//验证码图片

Route::get('super_login','super/login/loginPage');//后台超级用户登录页面
Route::get('super_signout','super/login/signout');//后台超级用户退出页面

Route::get('super_changepass','super/login/changepass');//后台超级用户修改密码页面
Route::post('super_updatepass','super/login/updatepass');//后台超级用户修改密码页面

Route::post('superloginAction','super/login/loginAction');//后台超级用户登录操作
Route::get('superManageAdmin','super/index/index');//后台超级用户首页
Route::get('welcomeSuper','super/index/welcome');//欢迎页面
Route::get('super_memberList','super/member/memberList');//注册会员列表
Route::get('super_memberShow','super/member/memberShow');//展示单个会员信息



Route::get('super_groupList','super/group/index');//群列表

Route::post('changeUserService','super/member/changeUserService');//更改会员为客服人员
Route::post('super_changeUserStatus','super/member/changeUserStatus');//更改会员状态





Route::get('super_configList','super/baseconf/index');//基础配置列表

Route::get('super_findList','super/find/index');//发现管理
Route::post('super_addGame','super/find/addGame');//添加发现中的游戏
Route::post('super_imgupload','super/find/imgupload');//添加发现中的游戏上传图片
Route::post('super_changeGameStatus','super/find/changeGameStatus');//修改记录状态
Route::post('super_gameDel','super/find/gameDel');//删除记录状态
Route::get('super_gameShow','super/find/show');//编辑记录状态
Route::post('super_updateGame','super/find/updateGame');//更新记录




Route::get('super_friendlist','super/friendlist/index');//通讯录管理

Route::get('super_chatList','super/chatlist/index');//会话管理
Route::get('super_MemberChatList','super/chatlist/memberChatList');

Route::get('super_memberlogList','super/memberlog/index');//日志管理




return [

];
