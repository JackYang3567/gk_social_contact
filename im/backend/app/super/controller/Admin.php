<?php

namespace app\super\controller;

use think\Controller;
use app\im\model\mysql\User;
use app\super\model\Admin as Model_Admin;
use think\facade\Request;

const PAGE_RECORDS = 15;

class Admin extends Controller
{
    public function initialize()
    {
        /*
        $admin_id = session('admin_id');
        if(!$admin_id)
        {
            $this->error('请先登录');
        }

        $admin_id = session('admin_id');
        if(!$admin_id)
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
        $key = Request::param();
        $where = [];
        $where1 = [];
        if(isset($key['act']) && $key['act'] == 'check')
        {
            /*
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
            }*/
            if($key['key'])
            {
                $val = (String) $key['key'];
                $where[] = ['username','=',$val];
                $where1[] = ['phone','=',$val];
            }
        }
       
        $adminArr =  Model_Admin::where($where)
         ->whereOr($where1)->order('id', 'desc')->paginate(PAGE_RECORDS);
       
        $list = array();
        foreach($adminArr as $key => $val){
          
            array_push($list,$val);
        }
  
        $this->assign('adminlist',  $adminArr);
        $this->assign('list',  $list);
        $this->assign('key',$key);
        return $this->fetch();       
    }

    public function checkAdminUsername(){
        $post_data = Request::post();
        $username =  $post_data['username'];
      
        $return_data = [
          'err' => 1,
          'msg' => 'fail',
          
        ];

        $where =  ['username' => $username ];
        $admin_obj = Model_Admin::where($where)->find();
        if($admin_obj){
          $return_data['err'] = 0;
          $return_data['msg'] = '获取数据成功！';
          $return_data['data'] = $admin_obj;
        }
       

        return $return_data;    
    }

    public function adminSave(){
        $post_data = Request::post();  

        $return_data = [
            'err' => 1,
            'msg' => 'error',
          ];
         // $find_id = md5(uniqid('JWT',true) . rand(1, 100000));
          $admin_obj = Model_Admin::create([
            'agent_id'  => $post_data['agent_id'],
            'agent_name' => $post_data['agent_name'],
            'username' => $post_data['username'],
            'password' => md5($post_data['password']),
            'status' => 0,
            'phone'=>'',
             'email' => '',
            'create_time' => time(),
          ]);

          if( $admin_obj){
               $return_data['err'] = 0;
               $return_data['msg'] = '数据修改成功！';
          }
        return $return_data;
    }

    public function adminDel(){
        $post_data = Request::post();
        $return_data = [
          'err' => 1,
          'msg' => 'fail',
        ];
        $where =  ['id' => $post_data['id'] ];
        $admin_obj = Model_Admin::where($where)->delete();
        if($admin_obj ){
          $return_data['err'] = 0;
          $return_data['msg'] = '数据删除成功！';
          $return_data['data'] = $admin_obj;
       }

        return $return_data; 
    }

    public function editInfo(){
       $this->assign('admin_id',session('admin_id'));  
       $this->assign('admin_name', session('admin_name'));
       $this->assign('admin_phone',session('admin_phone'));
       $this->assign('admin_email', session('admin_email'));
       return $this->fetch(); 
    }

    public function show()
    {
        $id = Request::param('id');
        $where =  ['id' => $id ];
        $admin_obj = Model_Admin::where($where)->find();
        if( $admin_obj){
            $return_data['err'] = 0;
            $return_data['msg'] = '获取数据成功！';
            $return_data['data'] = $admin_obj;
        }
        return $return_data;    
    }

    public function adminUpdate(){
        $post_data = Request::post();
        
        $update = [];
        $return_data = [
           'err' => 1,
           'msg' => 'fail',
          
         ];
         if (!preg_match("/^[1][3,4,5,7,8][0-9]{9}$/", $post_data['admin_phone'])) {
			$return_data['msg'] = '手机号码格式不正确';
			return $return_data;
        }
        if (!preg_match(" /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/", $post_data['admin_email'])) {
			$return_data['msg'] = '邮箱格式不正确';
			return $return_data;
        }

        $update = ['phone' => $post_data['admin_phone'],
                   'email' => $post_data['admin_email'],];
       
      
        if(Model_Admin::where('id',$post_data['admin_id'])->update( $update )){
           $return_data['err'] = 0;
           $return_data['msg'] = '数据修改成功！';
        }
         return   $return_data; 
    }

    public function changepass(){
        $this->assign('admin_id',session('admin_id'));  
        $this->assign('admin_name', session('admin_name'));  
        return $this->fetch(); 
     }
    
     public function updatepass(){
        $post_data = Request::post();
        
        $update = [];
        $return_data = [
           'err' => 1,
           'msg' => 'fail',
          
         ];
         if (!preg_match("/^\w{1,20}$/", $post_data['password'])) {
			$return_data['msg'] = '密码只能包括下划线、数字、字母,长度6-20位';
			return $return_data;
        }
        if($post_data['password']!==$post_data['repassword']){
            $return_data['msg'] = '密码只能包括下划线、数字、字母,长度6-20位';
			return $return_data;
        }

        $update = ['password' => md5($post_data['password'])];
       
      
        if(Model_Admin::where('id',$post_data['admin_id'])->update( $update )){
           $return_data['err'] = 0;
           $return_data['msg'] = '数据修改成功！';
        }
         return   $return_data;
    }


    public function changeAdminStatus(){
        $post_data = Request::post();
        
        $return_data = [
            'err' => 1,
            'msg' => 'fail',
           ];

        $where =  ['id' => $post_data['id'] ];
           $admin_obj = Model_Admin::where($where)->find();
          
           $num = 0;
           if((int) $post_data['act']){
              $num = 1;
           }
    
           $update = [];
          if( $admin_obj){
              $update = ['status' => $num];
              if( Model_Admin::where($where)->update( $update )){
                 $return_data['err'] = 0;
                 $return_data['msg'] = '数据修改成功！';
              }             
          }
        return $return_data;
    }


     /**
     * 管理员退出页面
     *
     * @return void
     */
    public function signout(){
        // 清除会话数据wipe out session data
        session_unset();
        // 销毁会话destroy session
       // 删除（当前作用域）
       session('admin_id',null);
       session('admin_name',null);
       header('Location: /admin_login');
        exit();
   }

}