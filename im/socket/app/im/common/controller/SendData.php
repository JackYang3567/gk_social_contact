<?php
namespace common\controller;

use \GatewayWorker\Lib\Gateway;

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
     * 发送给指定client_id
     * @param int    $client  客户端id
     * @param string $name    接口
     * @param array  $data    发送的数据
     */
    public static function sendToClient($user_id, $name, $data)
    {
        Gateway::sendToClient($user_id, self::formatData($name, $data));
    }

    /**
     * 发送给指定uid
     * @param int    $user_id 用户id
     * @param string $name    接口
     * @param array  $data    发送的数据
     */
    public static function sendToUid($user_id, $name, $data)
    {
        Gateway::sendToUid($user_id, self::formatData($name, $data));
    }

    /**
     * 发送给所有
     * @param string $name                    接口
     * @param array  $data                    发送的数据
     * @param array  $exclude_client_id array 排除的客户端连接id
     */
    public static function sendToAll($name, $data, $exclude_client_id = [])
    {
        Gateway::sendToAll(self::formatData($name, $data), [
            $exclude_client_id => $exclude_client_id
        ]);
    }
}
