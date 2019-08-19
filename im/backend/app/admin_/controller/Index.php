<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/28
 * Time: 10:08
 */
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    public function initialize()
    {
        $admin_id = session('admin_id');
        if($admin_id)
        {

        }
        else
        {
            $this->error('抱歉，您还没有登录，请先登录~');
        }
    }
    public function index()
    {
        return view();
    }

    public function welcome()
    {
        return view();
    }
}