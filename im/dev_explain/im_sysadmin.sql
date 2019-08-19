DROP TABLE IF EXISTS `txzh_sys_superuser`;
DROP TABLE IF EXISTS `txzh_sys_public_config`;
DROP TABLE IF EXISTS `txzh_sys_admin`;
DROP TABLE IF EXISTS `txzh_sys_role`;
DROP TABLE IF EXISTS `txzh_sys_permission`;
DROP TABLE IF EXISTS `txzh_sys_rolePermissionRelation`;
DROP TABLE IF EXISTS `txzh_sys_group`;
DROP TABLE IF EXISTS `txzh_sys_groupRoleRelation`;
DROP TABLE IF EXISTS `txzh_sys_groupPermissionRelation`;
DROP TABLE IF EXISTS `txzh_sys_adminPermissionRelation`;
DROP TABLE IF EXISTS `txzh_sys_adminRoleRelation`;
DROP TABLE IF EXISTS `txzh_sys_adminGroupRelation`;
DROP TABLE IF EXISTS `txzh_sys_organizaton`;
DROP TABLE IF EXISTS `txzh_sys_log`;
DROP TABLE IF EXISTS `txzh_sys_super_log`;

-- ======== 创建社交系统 后台（管理平台）数据表 ========
DROP TABLE IF EXISTS `txzh_sys_superuser`;
CREATE TABLE `txzh_sys_superuser` (
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
INSERT INTO  `txzh_sys_superuser` (`login_name`,`password`,`user_name`,`mobile`,`email`) VALUES ('admin','e10adc3949ba59abbe56e057f20f883e','超级管理员','13808013567','13808013567@163.com');


-- 系统公共配置表
DROP TABLE IF EXISTS `txzh_sys_public_config`;
CREATE TABLE `txzh_sys_public_config` (
  `conf_id` bigint NOT NULL AUTO_INCREMENT  COMMENT '主键conf_id',
  `conf_type` varchar(256) NOT NULL DEFAULT '' COMMENT '配置类型',
  `conf_field_key` varchar(256) NOT NULL DEFAULT '' COMMENT '配置字段名',
  `conf_field_title` varchar(256) DEFAULT NULL COMMENT '配置功能描述',
  `conf_field_val` varchar(256) DEFAULT NULL COMMENT '配置字段值',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`conf_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='系统公共配置表';

-- =======================系统管理员 相关======================================================
-- 系统管理员表
DROP TABLE IF EXISTS `txzh_sys_admin`;
CREATE TABLE `txzh_sys_admin` (
  `a_id` bigint NOT NULL AUTO_INCREMENT COMMENT'主键a_id',
  `o_id` bigint NOT NULL  COMMENT'所属组织id',
  `login_name` varchar(64) NOT NULL DEFAULT '' COMMENT '登录帐号',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员密码',
  `user_name` varchar(64) NOT NULL DEFAULT '' COMMENT '管理员姓名',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `email` varchar(64) DEFAULT NULL COMMENT '电子邮箱',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `sigin_at` timestamp NOT NULL  COMMENT '登录时间',
  `last_login_time` timestamp NOT NULL  COMMENT '最近登录时间',
  `count` bigint NOT NULL DEFAULT '0' COMMENT '登录次数',
  PRIMARY KEY (`a_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='系统管理员表';

-- 角色表
DROP TABLE IF EXISTS `txzh_sys_role`;
CREATE TABLE `txzh_sys_role` (
  `r_id` bigint NOT NULL AUTO_INCREMENT COMMENT'角色主键 r_id',
  `pr_id` bigint NOT NULL  COMMENT'父级角色ID',
  `role_name` varchar(64) NOT NULL DEFAULT '' COMMENT '角色名称',  
  `description` varchar(256) NOT NULL DEFAULT '' COMMENT '角色描述',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`r_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='角色表';


-- 权限表
DROP TABLE IF EXISTS `txzh_sys_permission`;
CREATE TABLE `txzh_sys_permission` (
  `p_id` bigint NOT NULL AUTO_INCREMENT COMMENT'权限主键 p_id',
  `pp_id` bigint NOT NULL  COMMENT'父级权限ID',
  `permission_name` varchar(64) NOT NULL DEFAULT '' COMMENT '权限名称',
  `description` varchar(256) NOT NULL DEFAULT '' COMMENT '权限描述',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`p_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='权限表';

-- 角色与权限关系表
DROP TABLE IF EXISTS `txzh_sys_rolePermissionRelation`;
CREATE TABLE `txzh_sys_rolePermissionRelation` (
  `rpr_id` bigint NOT NULL AUTO_INCREMENT COMMENT'角色权限主键 rpr_id',
  `r_id` bigint NOT NULL  COMMENT'角色ID',
  `p_id` bigint NOT NULL  COMMENT'权限ID', 
  `p_type` int(11) NOT NULL DEFAULT 0 COMMENT '权限类型 0:可访问，1:可授权',
  PRIMARY KEY (`rpr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='角色与权限关系表';

-- ===========================================================
-- 组表
DROP TABLE IF EXISTS `txzh_sys_group`;
CREATE TABLE `txzh_sys_group` (
  `g_id` bigint NOT NULL AUTO_INCREMENT COMMENT'组主键 g_id',
  `pg_id` bigint NOT NULL  COMMENT'父组ID',
  `group_name` varchar(64) NOT NULL DEFAULT '' COMMENT '角色名称',  
  `description` varchar(256) NOT NULL DEFAULT '' COMMENT '组描述',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`g_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='组表';

-- 组与角色关系表
DROP TABLE IF EXISTS `txzh_sys_groupRoleRelation`;
CREATE TABLE `txzh_sys_groupRoleRelation` (
  `grr_id` bigint NOT NULL AUTO_INCREMENT COMMENT'组与角色关系主键 grr_id',
  `g_id` bigint NOT NULL  COMMENT'组ID',
  `r_id` bigint NOT NULL  COMMENT'角色ID',  
  PRIMARY KEY (`grr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='组与角色关系表';

-- 组与权限关系表
DROP TABLE IF EXISTS `txzh_sys_groupPermissionRelation`;
CREATE TABLE `txzh_sys_groupPermissionRelation` (
  `gpr_id` bigint NOT NULL AUTO_INCREMENT COMMENT'组与权限关系主键 gpr_id',
  `g_id` bigint NOT NULL  COMMENT'组ID',
  `p_id` bigint NOT NULL  COMMENT'权限ID', 
  `p_type` int(11) NOT NULL DEFAULT 0 COMMENT '权限类型 0:可访问，1:可授权', 
  PRIMARY KEY (`gpr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='组与权限关系表';

-- ============================================
-- 管理员权限表
DROP TABLE IF EXISTS `txzh_sys_adminPermissionRelation`;
CREATE TABLE `txzh_sys_adminPermissionRelation` (
  `apr_id` bigint NOT NULL AUTO_INCREMENT COMMENT'管理员权限表主键 apr_id',
  `a_id` bigint NOT NULL  COMMENT'管理员ID',
  `p_id` bigint NOT NULL  COMMENT'权限ID', 
  `p_type` int(11) NOT NULL DEFAULT 0 COMMENT '权限类型 0:可访问，1:可授权', 
  PRIMARY KEY (`apr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员权限表';

-- 管理员角色表
DROP TABLE IF EXISTS `txzh_sys_adminRoleRelation`;
CREATE TABLE `txzh_sys_adminRoleRelation` (
  `arr_id` bigint NOT NULL AUTO_INCREMENT COMMENT'管理员角色表主键 arr_id',
  `a_id` bigint NOT NULL  COMMENT'管理员ID',
  `r_id` bigint NOT NULL  COMMENT'角色ID', 
  PRIMARY KEY (`arr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员角色表';


-- 管理员组表
DROP TABLE IF EXISTS `txzh_sys_adminGroupRelation`;
CREATE TABLE `txzh_sys_adminGroupRelation` (
  `agr_id` bigint NOT NULL AUTO_INCREMENT COMMENT'管理员组表主键 agr_id',
  `a_id` bigint NOT NULL  COMMENT'管理员ID',
  `g_id` bigint NOT NULL  COMMENT'组ID', 
  PRIMARY KEY (`agr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员组表';

-- ==============================
-- 组织表
DROP TABLE IF EXISTS `txzh_sys_organizaton`;
CREATE TABLE `txzh_sys_organizaton` (
  `o_id` bigint NOT NULL AUTO_INCREMENT COMMENT'组织表 o_id',
  `po_id` bigint NOT NULL  COMMENT'父组织ID',
  `org_name` varchar(64) NOT NULL DEFAULT '' COMMENT '组织名称',  
  `description` varchar(256) NOT NULL DEFAULT '' COMMENT '组织描述',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`o_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='组织表';


-- ================================
-- 管理员操作日志表

DROP TABLE IF EXISTS `txzh_sys_log`;
CREATE TABLE `txzh_sys_log` (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT'管理员操作日志表 id',
  `opt_type` int(11) NOT NULL  COMMENT '操作类型',
  `content` varchar(256) NOT NULL DEFAULT '' COMMENT '组织名称',  
  `a_id` bigint NOT NULL  COMMENT '管理员ID',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员操作日志表';


-- 超级用户操作日志表

DROP TABLE IF EXISTS `txzh_sys_super_log`;
CREATE TABLE `txzh_sys_super_log` (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT'超级用户操作日志表 id',
  `opt_type` int(11) NOT NULL  COMMENT '操作类型',
  `content` varchar(256) NOT NULL DEFAULT '' COMMENT '组织名称',  
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='超级用户操作日志表';


