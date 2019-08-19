DROP TABLE IF EXISTS `txzh_bsys_superuser`;
DROP TABLE IF EXISTS `txzh_bsys_config`;
DROP TABLE IF EXISTS `txzh_bsys_superlog`;

-- ======== 创建社交系统 后台（管理平台）数据表 ========
DROP TABLE IF EXISTS `txzh_bsys_superuser`;
CREATE TABLE `txzh_bsys_superuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT  COMMENT '主键id',
  `login_name` varchar(64) NOT NULL DEFAULT '' COMMENT '登录帐号',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员密码',
  `user_name` varchar(64) NOT NULL DEFAULT '' COMMENT '管理员姓名',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `email` varchar(64) DEFAULT NULL COMMENT '电子邮箱',
  `count` bigint NOT NULL DEFAULT '0' COMMENT '登录次数',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `sigin_at` timestamp NOT NULL  COMMENT '登录时间',
  `last_login_time` timestamp NOT NULL COMMENT '最近登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='超级管理员表';
INSERT INTO  `txzh_bsys_superuser` (`login_name`,`password`,`user_name`,`mobile`,`email`) VALUES ('admin','e10adc3949ba59abbe56e057f20f883e','超级管理员','13808013567','13808013567@163.com');


-- 系统公共配置表
DROP TABLE IF EXISTS `txzh_bsys_config`;
CREATE TABLE `txzh_bsys_config` (
  `id` bigint NOT NULL AUTO_INCREMENT  COMMENT '主键id',
  `type` varchar(256) NOT NULL DEFAULT '' COMMENT '配置类型',
  `table_name` varchar(64) NOT NULL DEFAULT '' COMMENT '配置表名(不带前缀)',
  `field_key` varchar(32) NOT NULL DEFAULT '' COMMENT '配置字段名',
  `field_title` varchar(64) NOT NULL DEFAULT '' COMMENT '配置功能描述',
  `field_val` text NOT NULL DEFAULT '' COMMENT '配置字段值',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='系统公共配置表';
INSERT INTO  `txzh_bsys_config` (`type`,`table_name`,`field_key`,`field_title`,`field_val`) VALUES ('会员 - 禁用会员','user','status','禁用会员，配置字段值列中记录被禁用会员的id','');
INSERT INTO  `txzh_bsys_config` (`type`,`table_name`,`field_key`,`field_title`,`field_val`) VALUES ('会员 - 客服人员','user','is_servives','客服人员设置，配置字段值列中记录客服人员的id','');
-- INSERT INTO  `txzh_bsys_config` (`type`,`table_name`,`field_key`,`field_title`,`field_val`) VALUES ('会员 - 置顶会员','user','is_top','置顶，配置字段值列中记录置顶会员的id','');
-- INSERT INTO  `txzh_bsys_config` (`type`,`table_name`,`field_key`,`field_title`,`field_val`) VALUES ('JWT - 密钥','system','secret_key','JWT - 密钥设置','');
-- INSERT INTO  `txzh_bsys_config` (`type`,`table_name`,`field_key`,`field_title`,`field_val`) VALUES ('JWT - 有效时间','system','effective_time','JWT有效时间设置(从用户未操作时间开始算起','');



-- 超级用户操作日志表

DROP TABLE IF EXISTS `txzh_bsys_super_log`;
CREATE TABLE `txzh_bsys_super_log` (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT'超级用户操作日志表 id',
  `opt_type` int(11) NOT NULL  COMMENT '操作类型',
  `content` varchar(256) NOT NULL DEFAULT '' COMMENT '组织名称',  
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='超级用户操作日志表';


