<?php

namespace app\super\controller;

use think\Controller;
use app\im\model\mysql\User;
use app\super\model\BsysConfig;
use think\facade\Request;

const PAGE_RECORDS = 15;

class Baseconf extends Controller
{
    public function initialize()
    {
        /*
        $admin_id = session('admin_id');
        if(!$admin_id)
        {
            $this->error('请先登录');
        }

        
        $super_id = session('super_id');
        if(!$super_id)
        {
            $this->error('请先登录');
        }
         */
        $logined = session('logined');
        if(!$logined)
        {
            $this->error('====抱歉，您还没有登录，请先登录===');
        }
    }

    public function index()
    {
        $list =  BsysConfig::where('id','>',0)->paginate(PAGE_RECORDS);
        $this->assign('list',  $list);
        return $this->fetch();
    }

    public function show()
    {
        $conf_id = Request::param('conf_id');
        $conf = User::where('id',$conf_id)->find();
        $this->assign('conf',$conf);
        return view();
    }

}