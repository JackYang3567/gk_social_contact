# Host: 127.0.0.1  (Version: 5.5.53)
# Date: 2019-06-13 16:19:47
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "txzh_admin"
#

DROP TABLE IF EXISTS `txzh_admin`;
CREATE TABLE `txzh_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '登陆名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '登陆密码',
  `sex` int(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `phone` varchar(11) DEFAULT NULL COMMENT '手机号',
  `email` varchar(20) DEFAULT NULL COMMENT '邮箱',
  `group_id` int(2) DEFAULT NULL COMMENT '权限角色ID',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='管理员表';

#
# Data for table "txzh_admin"
#

/*!40000 ALTER TABLE `txzh_admin` DISABLE KEYS */;
INSERT INTO `txzh_admin` VALUES (1,'admin','e10adc3949ba59abbe56e057f20f883e',0,'17608035998','1975931238@qq.com',1,0,0),(8,'user','$2y$10$eA5sl1MzHc5H3aZHkEWur.DDiKDKJw6YAC/C8SyXmh4868rzZketu',0,'13800000000','792532971@qq.com',8,0,1548671342),(9,'agent','$2y$10$b5bRaCojDtS4j6moFTSUv.t/bESHmsreyK7IDEBud.q9k1XvbfMQm',0,'13800000000','792532977@qq.com',1,0,1548671411);
/*!40000 ALTER TABLE `txzh_admin` ENABLE KEYS */;

#
# Structure for table "txzh_auth_group"
#

DROP TABLE IF EXISTS `txzh_auth_group`;
CREATE TABLE `txzh_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组ID',
  `title` char(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0=开启，1=关闭',
  `rules` char(80) NOT NULL DEFAULT '' COMMENT '规则（所对应的是规则表的id）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

#
# Data for table "txzh_auth_group"
#

/*!40000 ALTER TABLE `txzh_auth_group` DISABLE KEYS */;
INSERT INTO `txzh_auth_group` VALUES (1,'asdas',0,''),(8,'asdasdas',0,'');
/*!40000 ALTER TABLE `txzh_auth_group` ENABLE KEYS */;

#
# Structure for table "txzh_auth_group_access"
#

DROP TABLE IF EXISTS `txzh_auth_group_access`;
CREATE TABLE `txzh_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL COMMENT '用户ID',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组ID',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "txzh_auth_group_access"
#

/*!40000 ALTER TABLE `txzh_auth_group_access` DISABLE KEYS */;
/*!40000 ALTER TABLE `txzh_auth_group_access` ENABLE KEYS */;

#
# Structure for table "txzh_auth_rule"
#

DROP TABLE IF EXISTS `txzh_auth_rule`;
CREATE TABLE `txzh_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则表ID',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '路径（控制器/方法）',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则名称例如:管理员添加,修改,删除',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0=开启，1=关闭',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  `pid` int(10) unsigned DEFAULT '0',
  `show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

#
# Data for table "txzh_auth_rule"
#

/*!40000 ALTER TABLE `txzh_auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `txzh_auth_rule` ENABLE KEYS */;

#
# Structure for table "txzh_capital_log"
#

DROP TABLE IF EXISTS `txzh_capital_log`;
CREATE TABLE `txzh_capital_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '用户余额',
  `explain` varchar(255) DEFAULT NULL COMMENT '说明',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资金明细表';

#
# Data for table "txzh_capital_log"
#

/*!40000 ALTER TABLE `txzh_capital_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `txzh_capital_log` ENABLE KEYS */;

#
# Structure for table "txzh_login_log"
#

DROP TABLE IF EXISTS `txzh_login_log`;
CREATE TABLE `txzh_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `ip` varchar(15) DEFAULT NULL COMMENT '登陆ip',
  `details` varchar(255) DEFAULT NULL COMMENT '登陆详情',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '登陆时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='登陆日志表';

#
# Data for table "txzh_login_log"
#

/*!40000 ALTER TABLE `txzh_login_log` DISABLE KEYS */;
INSERT INTO `txzh_login_log` VALUES (13,1,'192.168.4.109','登录',1548138924),(14,1,'192.168.4.109','登录',1548138974),(15,1,'192.168.4.109','登录',1548140184),(16,1,'192.168.4.109','登录',1548140415),(17,1,'192.168.4.109','登录',1548140473),(18,1,'192.168.4.109','登录',1548141292),(20,1,'192.168.4.109','登录',1548224435),(21,1,'192.168.4.109','登录',1548660659);
/*!40000 ALTER TABLE `txzh_login_log` ENABLE KEYS */;

#
# Structure for table "txzh_system"
#

DROP TABLE IF EXISTS `txzh_system`;
CREATE TABLE `txzh_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(15) NOT NULL DEFAULT '' COMMENT '设置名',
  `value` varchar(200) DEFAULT NULL COMMENT '设置的值',
  `explain` varchar(100) NOT NULL DEFAULT '' COMMENT '设置说明',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='系统设置表';

#
# Data for table "txzh_system"
#

/*!40000 ALTER TABLE `txzh_system` DISABLE KEYS */;
INSERT INTO `txzh_system` VALUES (4,'JWT','{\"key\":{\"value\":\"123456\",\"explain\":\"JWT密钥设置\"},\"time\":{\"value\":100000000,\"explain\":\"JWT有效时间设置(从用户未操作时间开始算起)\"}}','JWT设置');
/*!40000 ALTER TABLE `txzh_system` ENABLE KEYS */;

#
# Structure for table "txzh_user"
#

DROP TABLE IF EXISTS `txzh_user`;
CREATE TABLE `txzh_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `doodling` varchar(50) DEFAULT '本宝宝暂时还没有想到个性的签名' COMMENT '个性签名',
  `email` varchar(20) DEFAULT NULL COMMENT '邮箱',
  `phone` varchar(11) DEFAULT NULL COMMENT '手机号',
  `sex` int(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `point` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '类型',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `circli_img` varchar(50) DEFAULT NULL COMMENT '朋友圈背景图片',
  `is_customer_service` int(1) NOT NULL DEFAULT '0' COMMENT '1:是客服，0:不是客服',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='用户表';

#
# Data for table "txzh_user"
#

/*!40000 ALTER TABLE `txzh_user` DISABLE KEYS */;
INSERT INTO `txzh_user` VALUES (7,'abc','向上','本宝宝暂时还没有想到个性的签名了',NULL,NULL,0,'c33367701511b4f6020ec61ded352059',0.00,0,1,0,1556443358,NULL),(8,'abcde','用户345','本宝宝暂时还没有想到个性的签名',NULL,NULL,0,'e10adc3949ba59abbe56e057f20f883e',0.00,0,1,0,1556443403,NULL),(9,'abcd','用户678','本宝宝暂时还没有想到个性的签名',NULL,NULL,0,'e10adc3949ba59abbe56e057f20f883e',0.00,0,1,0,1556444015,NULL),(10,'张飞','用户9','本宝宝暂时还没有想到个性的签名',NULL,NULL,0,'e10adc3949ba59abbe56e057f20f883e',0.00,0,1,0,1557740464,NULL),(11,'赵云','78','本宝宝暂时还没有想到个性的签名',NULL,NULL,0,'e10adc3949ba59abbe56e057f20f883e',0.00,0,1,0,1557830764,NULL),(12,'马超','用户a77defddf03a3eaf94125e8cc893a5e3用户a77defddf03a3e','本宝宝暂时还没有想到个性的签名',NULL,NULL,0,'e10adc3949ba59abbe56e057f20f883e',0.00,0,1,0,1557830915,NULL);
/*!40000 ALTER TABLE `txzh_user` ENABLE KEYS */;
