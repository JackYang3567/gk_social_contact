<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/28
 * Time: 10:11
 */
namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
use Yzm\ValidateCode;
use app\admin\model\Admin;

class Login extends Controller
{
    public function loginPage()
    {
        return view();
    }

    public function yzmimg()
    {
        $yzm = new ValidateCode();
        $yzm -> doimg();
        session('yzm_save',$yzm->getCode());
    }
  
    public function loginAction()
    {
        $post = Request::param();
        if(!$post['username'])
        {
            return ['status'=>false,'msg'=>'管理员账号不能为空'];
        }
        if(!$post['password'])
        {
            return ['status'=>false,'msg'=>'管理员密码不能为空'];
        }
        if(!$post['yzm'])
        {
            return ['status'=>false,'msg'=>'验证码不能为空'];
        }
        $check_yzm = session('yzm_save');
        if($check_yzm != $post['yzm'])
        {
            return ['status'=>false,'msg'=>'您输入的验证码不正确'];
        }
        $admin = new Admin();
        $adminInfo = $admin->where('username',$post['username'])->find();
        if($adminInfo)
        {
            if($adminInfo['password'] == md5($post['password']))
            {
                session('admin_id',$adminInfo['id']);
                session('admin_name',$adminInfo['username']);
                session('group_id',$adminInfo['group_id']);
                return ['status'=>true,'msg'=>'登录成功'];
            }
            else
            {
                return ['status'=>false,'msg'=>'您的密码不正确'];
            }
        }
        else
        {
            return ['status'=>false,'msg'=>'管理员不存在，请核对账号'];
        }
    }
}