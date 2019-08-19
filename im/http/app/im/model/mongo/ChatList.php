<?php
namespace app\im\model\mongo;
use think\Model;

class ChatList extends Model
{
	/** 设置数据库配置 */
	protected $connection = 'mongo';
	/** 自动完成 */
	protected $auto = [];
  protected $insert = [
		'top' => 0,
		'top_time' => 0,
		'no_reader_num' => 1,
		'ignore' => 0,
		'status' => 0,
	];
  protected $update = [];
	/** 类型转换 */
	protected $type = [
		'no_reader_num' => 'integer',
		'user_id' => 'integer',
		'type' => 'integer',
		'top' => 'integer',
		'top_time' => 'integer',
		'ignore' => 'integer',
		'status' => 'integer'
	];
	/** 设置json类型字段 */
	protected $json = [
	];

	protected static function init()
	{

	}

}
