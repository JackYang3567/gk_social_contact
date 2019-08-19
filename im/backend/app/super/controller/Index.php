<?php

namespace app\super\controller;

use think\Controller;

class Index extends Controller
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
            $this->error('====抱歉，您还没有登录，请先登录===');
        }
        */
        $logined = session('logined');
        if(!$logined)
        {
            $this->error('==+++==抱歉，您还没有登录，请先登录===');
        }
    }
    public function index()
    {
      
       
       // return view();
       return $this->fetch();
    }

    

    public function welcome()
    {
        
        //return view();
        return $this->fetch();
    }

    public function adminIndex()
    {
        $this->assign('admin_id',session('admin_id'));  
        $this->assign('admin_name', session('admin_name'));
       // return view();
       return $this->fetch();
    }

    public function adminWelcome()
    {
        
        //return view();
        return $this->fetch();
    }
    
}