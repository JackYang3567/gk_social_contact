<?php
namespace app\im\model\mongo;
use think\Model;

class Chat extends Model
{
	/** 设置数据库配置 */
	protected $connection = 'mongo';
	/** 自动完成 */
	protected $auto = [];
  protected $insert = [];
  protected $update = [];
	/** 设置json类型字段 */
	protected $json = [
		'content',
	];
	/** 类型转换 */
	protected $type = [
		'user_id' => 'integer',
		'content_type' => 'integer',
		'msg_type' => 'integer',
		'time' => 'integer',
	];

	protected static function init()
	{

	}

}
