<?php
namespace app\im\controller;

use \Request;

class App
{
  /** app升级检测 */
  public function update()
  {
    $post_data = Request::post();
    $return_data = [
      'err' => 1,
      'msg' => 'error',
      'data' => [],
    ];
    if(!isset($post_data['appid']) || !isset($post_data['version'])){
      return json($return_data);
    }

    $status = 0;
    $note = "更新提示\n\n";
    $update_url = [];
    $version = '3.0.8';

    /** 这里判断版本是否需要升级 */
    if ($post_data['appid'] === '__UNI__48FC257' && $post_data['version'] !== $version) { //校验appid和检查版本号
      $client_version = explode('.',$post_data['version']);
      $server_version = explode('.',$version);

      if($client_version[0] == $server_version[0] && $client_version[1] == $server_version[1]){
        /** 小版本资源更新 */
        if($client_version[2] != $server_version[2]){
          $note .= '修复BUG';
          $update_url['ios'] = 'http://119.23.109.165:8787/static/__UNI__48FC257.wgt';
          $update_url['android'] = 'http://119.23.109.165:8787/static/__UNI__48FC257.wgt';
          $status = 2;
        }
      }
      /** 大版本整包更新 */
      else{
        $note .= "修复BUG";
        $update_url['ios'] = 'http://119.23.109.165:8787/app.apk';
        $update_url['android'] = 'http://119.23.109.165:8787/app.apk';
        $status = 1;
      }
    }

    $return_data['err'] = 0;
    $return_data['msg'] = 'success';
    $return_data['data'] = [
      /** 0没有更新，1大版本整包更新， 2小版本资源更新 */
      'status' => $status,
      'note' => $note,
      'update_url' => $update_url,
    ];
    return json($return_data);
  }
}
