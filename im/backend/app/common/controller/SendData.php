<?php
namespace app\common\controller;

use GatewayClient\Gateway;

/**
 * 发送数据的封装类
 */
class SendData
{
    /**
     * 组合发送数据
     */
    private static function formatData($name,$data)
    {
        return json_encode([
            'action' => $name,
            'data' => $data
        ]);
    }

    /**
     * 发送给指定uid
     * @param int    $user_id 用户id
     * @param string $name    接口
     * @param array  $data    发送的数据
     */
    public static function sendToUid($user_ids, $name, $data = [])
    {
        if(!is_array($user_ids)){
          $user_ids = [ $user_ids ];
        }
        foreach ($user_ids as $value){
          Gateway::sendToUid($value, self::formatData($name, $data));
        }
    }

    /**
     * 发送给所有
     * @param string $name                    接口
     * @param array  $data                    发送的数据
     * @param array  $exclude_user_ids array 排除的user_id
     */
    public static function sendToAll($name, $data, $exclude_user_ids = [])
    {
        $exclude_client_ids = [];
        foreach ($exclude_user_ids as $value) {
          $exclude_client_ids[] += Gateway::getClientIdByUid() ?? [];
        }
        Gateway::sendToAll(self::formatData($name, $data), $exclude_client_ids);
    }
}
