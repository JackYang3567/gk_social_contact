<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/28
 * Time: 10:52
 */
namespace app\admin\controller;

use think\Controller;
use app\im\model\mysql\User;
use think\facade\Request;


class Member extends Controller
{
    public function initialize()
    {
        $admin_id = session('admin_id');
        if($admin_id)
        {

        }
        else
        {
            $this->error('请先登录');
        }
    }

    public function memberList()
    {
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
            if($key['key'])
            {
                $where1[] = ['username','like','%'.$key['key'].'%'];
                $where1[] = ['nickname','like','%'.$key['key'].'%'];
            }
        }
        //获取注册会员
        $user = User::where(function ($q)use($where){
                    $q->where($where);
                })->where(function ($q1)use($where1){
                    $q1->whereOr($where1);
                })
            ->field('*,username as yuan_name')
            ->paginate('15',false,[
                    'query'=>Request::param()
                ])->each(function ($v)use($key){
                    if(isset($key['key']) && $key['key'])
                    {
                        if(preg_match('/'.$key['key'].'/',$v['username']))
                        {
                            $v['username'] = preg_replace('/'.$key['key'].'/','<span style="color: red">'.$key['key'].'</span>',$v['username']);
                        }
                        if(preg_match('/'.$key['key'].'/',$v['nickname']))
                        {
                            $v['nickname'] = preg_replace('/'.$key['key'].'/','<span style="color: red">'.$key['key'].'</span>',$v['nickname']);
                        }
                    }
                    return $v;
                });
        $this->assign('user',$user);
        $this->assign('key',$key);
        return view();
    }

    public function memberShow()
    {
        $user_id = Request::param('user_id');
        $user = User::where('id',$user_id)->find();
        $this->assign('user',$user);
        return view();
    }

    public function changeUserStatus()
    {
        $post = Request::param();
        $change = User::changeStatus($post['id'],$post['act']);
        return $change;
    }

    public function changeUserToCustomerService()
    {
        $post = Request::param();
        $change = User::changeUserToCustomerService($post['id'],$post['act']);
        return $change;
    }
}