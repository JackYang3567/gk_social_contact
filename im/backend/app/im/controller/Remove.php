<?php
namespace app\im\controller;

/** mongo表 */
use \app\im\model\mongo\Chat;
use \app\im\model\mongo\ChatList;
use \app\im\model\mongo\Friend;
use \app\im\model\mongo\ChatMember;
use \app\im\model\mongo\ChatGroup;
use \app\im\model\mongo\UserState;
use \app\im\model\mongo\Circle;
use \app\im\model\mongo\CircleComments;
use \app\im\model\mongo\FriendApply;

class Remove
{
	/** 删除所有数据 */
	public function deleteAllData()
	{
		(new Chat)::where('id','neq','1')->delete();
		(new ChatList)::where('id','neq','1')->delete();
		(new Friend)::where('id','neq','1')->delete();
		(new ChatMember)::where('id','neq','1')->delete();
		(new ChatGroup)::where('id','neq','1')->delete();
		(new UserState)::where('id','neq','1')->delete();
		(new Circle)::where('id','neq','1')->delete();
		(new CircleComments)::where('id','neq','1')->delete();
		(new FriendApply)::where('id','neq','1')->delete();
		self::deleteDir(__DIR__ . '/../../../public/static/chat');
		self::deleteDir(__DIR__ . '/../../../public/static/circle');
		self::deleteDir(__DIR__ . '/../../../public/static/photo/user');
		self::deleteDir(__DIR__ . '/../../../public/static/photo/circle');
		self::deleteDir(__DIR__ . '/../../../public/static/photo/group_photo');
		echo '删除所有数据成功';
	}

	private static function deleteDir($dir)
	{
		if (!$handle = @opendir($dir)) {
			return false;
		}
		while (false !== ($file = readdir($handle))) {
			if ($file !== "." && $file !== "..") { //排除当前目录与父级目录
					$file = $dir . '/' . $file;
					if (is_dir($file)) {
							self::deleteDir($file);
					} else {
							@unlink($file);
					}
			}
		}
		@rmdir($dir);
		return true;
	}

}
