# Host: localhost  (Version: 5.5.53)
# Date: 2019-06-29 09:12:54
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='用户表';

#
# Data for table "txzh_user"
#

/*!40000 ALTER TABLE `txzh_user` DISABLE KEYS */;
INSERT INTO `txzh_user` VALUES (7,'abc','向上','本宝宝暂时还没有想到个性的签名了',NULL,NULL,0,'c33367701511b4f6020ec61ded352059',0.00,0,1,0,1556443358,NULL,0),(8,'abcde','用户345','本宝宝暂时还没有想到个性的签名',NULL,NULL,0,'e10adc3949ba59abbe56e057f20f883e',0.00,0,1,0,1556443403,NULL,0),(9,'abcd','用户678','本宝宝暂时还没有想到个性的签名',NULL,NULL,0,'e10adc3949ba59abbe56e057f20f883e',0.00,0,1,1,1556444015,NULL,0),(10,'张飞','用户9','本宝宝暂时还没有想到个性的签名',NULL,NULL,0,'e10adc3949ba59abbe56e057f20f883e',0.00,0,1,0,1557740464,NULL,0),(11,'赵云','78','本宝宝暂时还没有想到个性的签名',NULL,NULL,0,'e10adc3949ba59abbe56e057f20f883e',0.00,0,1,0,1557830764,NULL,0),(12,'马超','用户a77defddf03a3eaf94125e8cc893a5e3用户a77defddf03a3e','本宝宝暂时还没有想到个性的签名',NULL,NULL,0,'e10adc3949ba59abbe56e057f20f883e',0.00,0,1,0,1557830915,NULL,0),(13,'test123','用户156091283242386','本宝宝暂时还没有想到个性的签名',NULL,NULL,0,'96e79218965eb72c92a549dd5a330112',0.00,0,1,0,0,NULL,0),(14,'jack123','用户156092209044819','Jack\n',NULL,NULL,1,'96e79218965eb72c92a549dd5a330112',0.00,0,1,0,0,NULL,0);
/*!40000 ALTER TABLE `txzh_user` ENABLE KEYS */;
