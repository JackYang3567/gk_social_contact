<?php
namespace app\im\model\mysql;
use think\Model;

class User extends Model
{
	/** 设置数据库配置 */
	protected $connection = 'mysql';
	/** 自动完成 */
	protected $auto = [];
  protected $insert = [];
  protected $update = [];

	protected static function init()
	{

	}

	public function setPasswordAttr($value)
  {
      return md5($value);
  }

}
