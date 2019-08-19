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

/*
Route::get('manageAdmin','admin/index/index');//后台首页
Route::get('manageLogin','admin/login/loginPage');//后台登录页面
Route::post('loginAction','admin/login/loginAction');//后台登录操作
Route::get('welcome','admin/index/welcome');//欢迎页面
Route::get('memberList','admin/member/memberList');//注册会员列表
Route::get('memberShow','admin/member/memberShow');//展示单个会员信息
Route::post('changeUserStatus','admin/member/changeUserStatus');//更改会员状态
*/

Route::get('yzmimg','super/login/yzmimg');//验证码图片

Route::get('super_login','super/login/loginPage');//后台超级用户登录页面
Route::post('superloginAction','super/login/loginAction');//后台超级用户登录操作
Route::get('super_signout','super/login/signout');//后台超级用户退出页面


Route::get('admin_login','super/login/signin');//管理员登录页面
Route::post('adminloginAction','super/login/signinAction');//管理员登录操作
Route::get('admin_signout','super/admin/signout');//管理员退出页面
Route::get('adminManageAdmin','super/index/adminIndex');//管理员首页
Route::post('admin_updatepass','super/admin/updatepass');//管理员修改密码页面

Route::get('super_adminList','super/admin/index');//后台管理员列表
Route::post('super_adminSave','super/admin/adminSave');//保存添加后台管理员
Route::post('super_checkAdminUsername','super/admin/checkAdminUsername');//管理员帐号唯一性检查
Route::post('super_adminDel','super/admin/adminDel');//删除管理员帐号
Route::get('super_editInfo','super/admin/editInfo'); //完善管理员资料
Route::get('super_adminShow','super/admin/show'); 

Route::post('super_adminUpdate','super/admin/adminUpdate'); //完善管理员资料

Route::get('super_adminChangPass','super/admin/changepass'); //管理员修改密码页面
Route::post('super_changeAdminStatus','super/admin/changeAdminStatus');//更改管理员状态
Route::get('super_changepass','super/login/changepass');//后台超级用户修改密码页面
Route::post('super_updatepass','super/login/updatepass');//后台超级用户修改密码页面


Route::get('superManageAdmin','super/index/index');//后台超级用户首页

Route::get('welcomeSuper','super/index/welcome');//欢迎页面
Route::get('welcomeAdmin','super/index/adminWelcome');//欢迎页面
Route::get('super_memberList','super/member/memberList');//注册会员列表
Route::get('super_memberShow','super/member/memberShow');//展示单个会员信息
Route::get('super_getMemberByagent','super/member/memberByagent');//通过agent_id获取会员列表



Route::get('super_groupList','super/group/index');//群列表

Route::post('changeUserService','super/member/changeUserService');//更改会员为客服人员
Route::post('super_changeUserStatus','super/member/changeUserStatus');//更改会员状态





Route::get('super_configList','super/baseconf/index');//基础配置列表

Route::get('super_findList','super/find/index');//发现管理
Route::post('super_checkAgentId','super/find/checkAgentId');//检查客户标识
Route::get('super_getAgentList','super/find/getAgentList');//获取客户(商家)列表
Route::post('super_updateService','super/find/updateService');

Route::post('super_addGame','super/find/addGame');//添加发现中的游戏
Route::post('super_imgupload','super/find/imgupload');//添加发现中的游戏上传图片
Route::post('super_changeGameStatus','super/find/changeGameStatus');//修改记录状态
Route::post('super_gameDel','super/find/gameDel');//删除记录状态
Route::get('super_gameShow','super/find/show');//编辑记录状态
Route::post('super_updateGame','super/find/updateGame');//更新记录




Route::get('super_friendlist','super/friendlist/index');//通讯录管理
Route::get('super_membermaillist','super/friendlist/membermaillist');//会员通讯录

Route::get('super_chatList','super/chatlist/index');//会话管理
Route::get('super_MemberChatList','super/chatlist/memberChatList');

Route::get('super_memberlogList','super/memberlog/index');//日志管理




return [

];
