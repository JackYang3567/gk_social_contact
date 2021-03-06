<?php

//cors同源设置(除了第一项，其他都是解决前端框架因为头的问题报错)
header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods:OPTIONS, GET, POST');
// header('Access-Control-Allow-Headers:x-requested-with');
// header('Access-Control-Max-Age:86400');
// header('Access-Control-Allow-Credentials:true');
header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Access-Control-Allow-Headers:Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With');


/** 获得显示的头像 */
function getShowPhoto($user_state_obj,$sex,$user_id,$size){
  if($user_state_obj && $user_state_obj->photo){
    $photo_path = 'user/' . $user_id . '/' . $size . '.jpg';
  }else{
    if($sex){
      $photo_path = 'default_woman/' . $size . '.jpg';
    }else{
      $photo_path = 'default_man/' . $size . '.jpg';
    }
  }
  return $photo_path;
}
