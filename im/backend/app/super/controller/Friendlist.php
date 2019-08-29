<?php

namespace app\super\controller;

use think\Controller;
use app\im\model\mongo\Friend;
use app\im\model\mysql\User;
use think\facade\Request;

const PAGE_RECORDS = 15;

class Friendlist extends Controller
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
        $post_data = Request::post();
        $key = Request::param();
        $where = [];
        $where1 = [];
        if(isset($key['act']) && $key['act'] == 'check')
        {
            if($key['start_time'] && !$key['end_time'])
            {
                $where[] = ['create_time','>=',strtotime($key['start_time'].' 00:00:00')];
            }
            else if(!$key['start_time'] && $key['end_time'])
            {
                $where[] = ['create_time','<=',strtotime($key['end_time'].' 23:59:59')];
            }
            else if($key['start_time'] && $key['end_time'])
            {
                if(strtotime($key['start_time'].' 00:00:00') < strtotime($key['end_time'].' 00:00:00'))
                {
                    $where[] = ['create_time','>=',strtotime($key['start_time'].' 00:00:00')];
                    $where[] = ['create_time','<=',strtotime($key['end_time'].' 23:59:59')];
                }
                else
                {
                    $where[] = ['create_time','>=',strtotime($key['end_time'].' 00:00:00')];
                    $where[] = ['create_time','<=',strtotime($key['start_time'].' 23:59:59')];
                }
            }
           
            $userids = User::getUserIdByNickname($key['key']);
           
            if($key['key'])
            {
                $where[] =  ['user_id','>',0];
                $where1[] =  ['user_id','=',$userids];
            }
        }else{
            $where[] =  ['user_id','>',0];
        }
       
       // $friendArr = Friend::select();
        $friendArr =  Friend::where($where)
                     ->whereOr($where1)->paginate(PAGE_RECORDS);
                      
        $list = array();
         
        foreach($friendArr as $key => $val){
            $user = User::getUserByUserId($val->user_id);
            $friend = User::getUserByUserId($val->friend_id);
            $val->nickname = $user->nickname ;
            $val->friend_nickname = $friend->nickname ;
            array_push($list,$val);
        }
        $this->assign('friendlist',  $friendArr);
        $this->assign('list',  $list);
        $this->assign('key',$key);
        return $this->fetch();
    }

    public function show()
    {
        $conf_id = Request::param('conf_id');
        $conf = User::where('id',$conf_id)->find();
        $this->assign('conf',$conf);
        return $this->fetch();
    }

    public function membermaillist(){
        $user_id = (int)Request::param('user_id');
        $key = Request::param();
      
              
        $where[] =  ['user_id','=',$user_id];
        $friendArr =  Friend::where($where)->paginate(PAGE_RECORDS);
                      
        $list = array();
         
        foreach($friendArr as $key => $val){
            $user = User::getUserByUserId($val->user_id);
            $friend = User::getUserByUserId($val->friend_id);
            $val->nickname = $user->nickname ;
            $val->friend_nickname = $friend->nickname ;
            array_push($list,$val);
        }
        $this->assign('friendlist',  $friendArr);
        $this->assign('list',  $list);
        $this->assign('key',$key);
        return $this->fetch();
    }

}