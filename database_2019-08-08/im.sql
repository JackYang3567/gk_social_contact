-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2019-08-07 09:39:43
-- 服务器版本： 5.7.26-log
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `im`
--

-- --------------------------------------------------------

--
-- 表的结构 `txzh_admin`
--

CREATE TABLE `txzh_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '登陆名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '登陆密码',
  `sex` int(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `phone` varchar(11) DEFAULT NULL COMMENT '手机号',
  `email` varchar(20) DEFAULT NULL COMMENT '邮箱',
  `group_id` int(2) DEFAULT NULL COMMENT '权限角色ID',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员表';

--
-- 转存表中的数据 `txzh_admin`
--

INSERT INTO `txzh_admin` (`id`, `username`, `password`, `sex`, `phone`, `email`, `group_id`, `status`, `create_time`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 0, '17608035998', '1975931238@qq.com', 1, 0, 0),
(8, 'user', '$2y$10$eA5sl1MzHc5H3aZHkEWur.DDiKDKJw6YAC/C8SyXmh4868rzZketu', 0, '13800000000', '792532971@qq.com', 8, 0, 1548671342),
(9, 'agent', '$2y$10$b5bRaCojDtS4j6moFTSUv.t/bESHmsreyK7IDEBud.q9k1XvbfMQm', 0, '13800000000', '792532977@qq.com', 1, 0, 1548671411);

-- --------------------------------------------------------

--
-- 表的结构 `txzh_auth_group`
--

CREATE TABLE `txzh_auth_group` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户组ID',
  `title` char(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态：0=开启，1=关闭',
  `rules` char(80) NOT NULL DEFAULT '' COMMENT '规则（所对应的是规则表的id）'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `txzh_auth_group`
--

INSERT INTO `txzh_auth_group` (`id`, `title`, `status`, `rules`) VALUES
(1, 'asdas', 0, ''),
(8, 'asdasdas', 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `txzh_auth_group_access`
--

CREATE TABLE `txzh_auth_group_access` (
  `uid` mediumint(8) UNSIGNED NOT NULL COMMENT '用户ID',
  `group_id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户组ID'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `txzh_auth_rule`
--

CREATE TABLE `txzh_auth_rule` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT '规则表ID',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '路径（控制器/方法）',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则名称例如:管理员添加,修改,删除',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0=开启，1=关闭',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  `pid` int(10) UNSIGNED DEFAULT '0',
  `show` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `txzh_bsys_config`
--

CREATE TABLE `txzh_bsys_config` (
  `id` bigint(20) NOT NULL COMMENT '主键id',
  `type` varchar(256) NOT NULL DEFAULT '' COMMENT '配置类型',
  `table_name` varchar(64) NOT NULL DEFAULT '' COMMENT '配置表名(不带前缀)',
  `field_key` varchar(32) NOT NULL DEFAULT '' COMMENT '配置字段名',
  `field_title` varchar(64) NOT NULL DEFAULT '' COMMENT '配置功能描述',
  `field_val` text NOT NULL COMMENT '配置字段值',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统公共配置表';

--
-- 转存表中的数据 `txzh_bsys_config`
--

INSERT INTO `txzh_bsys_config` (`id`, `type`, `table_name`, `field_key`, `field_title`, `field_val`, `created_at`) VALUES
(1, '会员', 'user', 'status', '禁用会员，field_val字段中记录被禁用会员的id', ',27', '2019-07-02 02:08:46'),
(2, '会员', 'user', 'is_servives', '客服人员设置，field_val字段中记录客服人员的id', ',7,27,28', '2019-07-02 02:08:47');

-- --------------------------------------------------------

--
-- 表的结构 `txzh_bsys_superuser`
--

CREATE TABLE `txzh_bsys_superuser` (
  `id` int(11) NOT NULL COMMENT '主键id',
  `login_name` varchar(64) NOT NULL DEFAULT '' COMMENT '登录帐号',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员密码',
  `user_name` varchar(64) NOT NULL DEFAULT '' COMMENT '管理员姓名',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `email` varchar(64) DEFAULT NULL COMMENT '电子邮箱',
  `count` bigint(20) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `sigin_at` timestamp NOT NULL COMMENT '登录时间',
  `last_login_time` timestamp NOT NULL COMMENT '最近登录时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='超级管理员表';

--
-- 转存表中的数据 `txzh_bsys_superuser`
--

INSERT INTO `txzh_bsys_superuser` (`id`, `login_name`, `password`, `user_name`, `mobile`, `email`, `count`, `created_at`, `sigin_at`, `last_login_time`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '超级管理员', '13808013567', '13808013567@163.com', 0, '2019-07-02 02:08:46', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `txzh_bsys_super_log`
--

CREATE TABLE `txzh_bsys_super_log` (
  `id` bigint(20) NOT NULL COMMENT '超级用户操作日志表 id',
  `opt_type` int(11) NOT NULL COMMENT '操作类型',
  `content` varchar(256) NOT NULL DEFAULT '' COMMENT '组织名称',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='超级用户操作日志表';

-- --------------------------------------------------------

--
-- 表的结构 `txzh_capital_log`
--

CREATE TABLE `txzh_capital_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '用户余额',
  `explain` varchar(255) DEFAULT NULL COMMENT '说明',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资金明细表';

-- --------------------------------------------------------

--
-- 表的结构 `txzh_login_log`
--

CREATE TABLE `txzh_login_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `ip` varchar(15) DEFAULT NULL COMMENT '登陆ip',
  `details` varchar(255) DEFAULT NULL COMMENT '登陆详情',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '登陆时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='登陆日志表';

--
-- 转存表中的数据 `txzh_login_log`
--

INSERT INTO `txzh_login_log` (`id`, `user_id`, `ip`, `details`, `create_time`) VALUES
(13, 1, '192.168.4.109', '登录', 1548138924),
(14, 1, '192.168.4.109', '登录', 1548138974),
(15, 1, '192.168.4.109', '登录', 1548140184),
(16, 1, '192.168.4.109', '登录', 1548140415),
(17, 1, '192.168.4.109', '登录', 1548140473),
(18, 1, '192.168.4.109', '登录', 1548141292),
(20, 1, '192.168.4.109', '登录', 1548224435),
(21, 1, '192.168.4.109', '登录', 1548660659);

-- --------------------------------------------------------

--
-- 表的结构 `txzh_system`
--

CREATE TABLE `txzh_system` (
  `id` int(11) NOT NULL,
  `key` varchar(15) NOT NULL DEFAULT '' COMMENT '设置名',
  `value` varchar(200) DEFAULT NULL COMMENT '设置的值',
  `explain` varchar(100) NOT NULL DEFAULT '' COMMENT '设置说明'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统设置表';

--
-- 转存表中的数据 `txzh_system`
--

INSERT INTO `txzh_system` (`id`, `key`, `value`, `explain`) VALUES
(4, 'JWT', '{\"key\":{\"value\":\"12345678\",\"explain\":\"JWT密钥设置\"},\"time\":{\"value\":100000000,\"explain\":\"JWT有效时间设置(从用户未操作时间开始算起)\"}}', 'JWT设置');

-- --------------------------------------------------------

--
-- 表的结构 `txzh_user`
--

CREATE TABLE `txzh_user` (
  `id` int(11) NOT NULL,
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
  `is_customer_service` int(1) NOT NULL DEFAULT '0' COMMENT '1:是客服，0:不是客服'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `txzh_user`
--

INSERT INTO `txzh_user` (`id`, `username`, `nickname`, `doodling`, `email`, `phone`, `sex`, `password`, `money`, `point`, `type`, `status`, `create_time`, `circli_img`, `is_customer_service`) VALUES
(7, 'abc', '众筹在线客服', '在线客服', NULL, NULL, 1, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 1556443358, NULL, 0),
(27, '6666', '6666', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '4297f44b13955235245b2497399d7a93', '0.00', 0, 1, 0, 0, NULL, 0),
(17, '58963', '58963', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '4297f44b13955235245b2497399d7a93', '0.00', 0, 1, 0, 0, NULL, 0),
(26, '8888', 'boss', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '4297f44b13955235245b2497399d7a93', '0.00', 0, 1, 0, 0, NULL, 0),
(28, 'llcc', 'llcc', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(29, '7890', '7890', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(13, 'test123', '用户156091283242386', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(14, 'jack123', '用户156092209044819', 'Jack\n', NULL, NULL, 1, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(15, '13808013567', 'IT作ggggiiii', '最个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(16, '8866', '8866', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '4297f44b13955235245b2497399d7a93', '0.00', 0, 1, 0, 0, NULL, 0),
(30, '13330753002', '昝智骞', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '6590f73ecdf351c38de00befd2ecf17b', '0.00', 0, 1, 0, 0, NULL, 0),
(31, '13699004480', '123', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(32, '15928827226', '抱个仙人球', '亲亲亲亲亲亲亲亲', NULL, NULL, 0, '2cd2e5817c8c789721b46b3c82e42697', '0.00', 0, 1, 0, 0, NULL, 0),
(33, '13330753003', '13330753003', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'fcea920f7412b5da7be0cf42b8c93759', '0.00', 0, 1, 0, 0, NULL, 0),
(34, '17602818941', '始终', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '3a300c48b47b714d9821dc473304aa84', '0.00', 0, 1, 0, 0, NULL, 0),
(35, '00001', '乘风破浪', '不忘初心方得始终。', NULL, NULL, 0, 'c00e4592a1b87114dd54b2c4b1990414', '0.00', 0, 1, 0, 0, NULL, 0),
(36, '881688', '881688', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '4297f44b13955235245b2497399d7a93', '0.00', 0, 1, 0, 0, NULL, 0),
(37, '13003973975', '佳佳', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 1, '54fb1dcfd462867c45eec98a58968b9f', '0.00', 0, 1, 0, 0, NULL, 0),
(38, '13458141031', '三里屯、屯主', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '9172cce36e2cb0735986b99ce92b505a', '0.00', 0, 1, 0, 0, NULL, 0),
(39, 'z123456', '思思妹呀', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 1, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(40, 'x123456', '陈冠希同款', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(41, '13566355222', '13566355222', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '2a698a1aa007f6f1023a02c92e6f227d', '0.00', 0, 1, 0, 0, NULL, 0),
(42, '13158698390', '萧语晴', '暂时没想好！', NULL, NULL, 1, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(43, '18608380031', '罗湘瑜', '我的世界你走不进来', NULL, NULL, 1, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(44, '00002', '00002', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 1, '9b94a3d6cf34d7dfb932db5d61b2ae4d', '0.00', 0, 1, 0, 0, NULL, 0),
(45, '1314520', '1314520', '脏话固然混浊 但比谎话干净', NULL, NULL, 0, 'b206e95a4384298962649e58dc7b39d4', '0.00', 0, 1, 0, 0, NULL, 0),
(46, '5201314', '5201314', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 1, '200820e3227815ed1756a6b531e7e0d2', '0.00', 0, 1, 0, 0, NULL, 0),
(47, '13002320102', '我自闭了', '随时进入自闭状态', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(48, '1238', '1238', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 1, '200820e3227815ed1756a6b531e7e0d2', '0.00', 0, 1, 0, 0, NULL, 0),
(49, 'superlan', 'superlan', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 1, '200820e3227815ed1756a6b531e7e0d2', '0.00', 0, 1, 0, 0, NULL, 0),
(50, '666666', 'T-mac', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '824a67f29e97b8798a9df7f00189f3e1', '0.00', 0, 1, 0, 0, NULL, 0),
(51, '10086', '10086', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '4297f44b13955235245b2497399d7a93', '0.00', 0, 1, 0, 0, NULL, 0),
(52, '18508389601', '阿喵～', '好的坏的都是风景。', NULL, NULL, 1, 'cd6c8deb69c4a608ff28f04bcb5b8d7f', '0.00', 0, 1, 0, 0, NULL, 0),
(53, '13198283810', '七分可爱^0^', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 1, '70f4a3aef9b43932361a233715e138c9', '0.00', 0, 1, 0, 0, NULL, 0),
(54, '13092866103', '晓琳', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'b81c3e4ad23e364605f8ce96b5ce42e5', '0.00', 0, 1, 0, 0, NULL, 0),
(55, '18760241853', '18760241853', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'a3f11457f0354cc242d5e855e007c056', '0.00', 0, 1, 0, 0, NULL, 0),
(56, '16888', '16888', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '4297f44b13955235245b2497399d7a93', '0.00', 0, 1, 0, 0, NULL, 0),
(57, 'www8899', 'www8899', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'c5200ce690fb082d1d3fe74759665583', '0.00', 0, 1, 0, 0, NULL, 0),
(58, '123567', '123567', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '4297f44b13955235245b2497399d7a93', '0.00', 0, 1, 0, 0, NULL, 0),
(59, '123456', '123456', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(60, '17628048754', '金牌导师-轩轩', '你选择我，我就必须带你走向人生的巅峰', NULL, NULL, 1, '447c28b0cfa7ad4ba1cd3e587bb855bd', '0.00', 0, 1, 0, 0, NULL, 0),
(61, '13219862539', '众筹计划员-有事找群管理', '我是计划专员，有事情可以找自己导师哦', NULL, NULL, 1, '447c28b0cfa7ad4ba1cd3e587bb855bd', '0.00', 0, 1, 0, 0, NULL, 0),
(62, 'qaq123', 'qaq123', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '70eb6a3452a6501890e1fc3f6ed35438', '0.00', 0, 1, 0, 0, NULL, 0),
(63, '3300671255', '3300671255', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(64, 'oicqw', 'oicqw', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '80d526adc4423e14ebc290982dea5a13', '0.00', 0, 1, 0, 0, NULL, 0),
(65, '88888888', '', '衣服都赋予选股方法有效预防想法太需要', NULL, NULL, 0, '7c497868c9e6d3e4cf2e87396372cd3b', '0.00', 0, 1, 0, 0, NULL, 0),
(66, '13063095523', '13063095523', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '10cf21aa3dbe4b06b6a7ca09b6ca395b', '0.00', 0, 1, 0, 0, NULL, 0),
(67, '13063095523', '13063095523', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '10cf21aa3dbe4b06b6a7ca09b6ca395b', '0.00', 0, 1, 0, 0, NULL, 0),
(68, '13508013567', '13508013567', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(69, '1111', '1111', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(70, '13708013567', '我没有昵称', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(71, '13908013567', '13908013567', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(72, '000001', '000001', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '200820e3227815ed1756a6b531e7e0d2', '0.00', 0, 1, 0, 0, NULL, 0),
(73, '13888888888', '13888888888', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(74, '18206091630', '18206091630', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '36476992d4f50dbe2bc7728f4b9fe299', '0.00', 0, 1, 0, 0, NULL, 0),
(75, '12345612345', '12345612345', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(76, '13500582314', '13500582314', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '2a698a1aa007f6f1023a02c92e6f227d', '0.00', 0, 1, 0, 0, NULL, 0),
(77, 'aa123', 'aa123', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(78, 'aa123456', 'aa123456', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(79, '66666', '66666', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '3a300c48b47b714d9821dc473304aa84', '0.00', 0, 1, 0, 0, NULL, 0),
(80, '666888', '666888', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '3a300c48b47b714d9821dc473304aa84', '0.00', 0, 1, 0, 0, NULL, 0),
(81, 'qaq5722', 'qaq5722', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'ea3749be391fffa892e563f6a0355e5c', '0.00', 0, 1, 0, 0, NULL, 0),
(82, 'qaq5721', 'qaq5721', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'ea3749be391fffa892e563f6a0355e5c', '0.00', 0, 1, 0, 0, NULL, 0),
(83, '15723125145', '15723125145', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(84, '13551148087', '13551148087', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '5d93ceb70e2bf5daa84ec3d0cd2c731a', '0.00', 0, 1, 0, 0, NULL, 0),
(85, '17602824713', '17602824713', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '5d93ceb70e2bf5daa84ec3d0cd2c731a', '0.00', 0, 1, 0, 0, NULL, 0),
(86, '17602824713', '17602824713', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '5d93ceb70e2bf5daa84ec3d0cd2c731a', '0.00', 0, 1, 0, 0, NULL, 0),
(87, '147258369', '147258369', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e807f1fcf82d132f9bb018ca6738a19f', '0.00', 0, 1, 0, 0, NULL, 0),
(88, '257258259', '257258259', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e807f1fcf82d132f9bb018ca6738a19f', '0.00', 0, 1, 0, 0, NULL, 0),
(89, '1', '1', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(90, 'awq', 'awq', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(91, 'awf', 'awf', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(92, 'aw7', 'aw7', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(93, '333', '333', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(94, 'ttt', 'ttt', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(95, 'mytesr', 'mytesr', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(96, '454345', '454345', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(97, 'zxcvbn', 'zxcvbn', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(98, 'test1234', 'test1234', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(99, '13007271335', '13007271335', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '0b9a75d6a88721a4bcf94f0fd668c56b', '0.00', 0, 1, 0, 0, NULL, 0),
(100, '17683081972', '17683081972', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, 'e10adc3949ba59abbe56e057f20f883e', '0.00', 0, 1, 0, 0, NULL, 0),
(101, '13808083567', '13808083567', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(102, 'xxxxxx', 'xxxxxx', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '96e79218965eb72c92a549dd5a330112', '0.00', 0, 1, 0, 0, NULL, 0),
(103, 'yuyu211333', 'yuyu211333', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '4297f44b13955235245b2497399d7a93', '0.00', 0, 1, 0, 0, NULL, 0),
(104, 'qaq123321', 'qaq123321', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '4297f44b13955235245b2497399d7a93', '0.00', 0, 1, 0, 0, NULL, 0),
(105, 'ldh123456', 'ldh123456', '本宝宝暂时还没有想到个性的签名', NULL, NULL, 0, '9608875e17c5fccd0746d836d181b902', '0.00', 0, 1, 0, 0, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `txzh_admin`
--
ALTER TABLE `txzh_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `txzh_auth_group`
--
ALTER TABLE `txzh_auth_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `txzh_auth_group_access`
--
ALTER TABLE `txzh_auth_group_access`
  ADD UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `txzh_auth_rule`
--
ALTER TABLE `txzh_auth_rule`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `txzh_bsys_config`
--
ALTER TABLE `txzh_bsys_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `txzh_bsys_superuser`
--
ALTER TABLE `txzh_bsys_superuser`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `txzh_bsys_super_log`
--
ALTER TABLE `txzh_bsys_super_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `txzh_capital_log`
--
ALTER TABLE `txzh_capital_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `txzh_login_log`
--
ALTER TABLE `txzh_login_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `txzh_system`
--
ALTER TABLE `txzh_system`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `txzh_user`
--
ALTER TABLE `txzh_user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `txzh_admin`
--
ALTER TABLE `txzh_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `txzh_auth_group`
--
ALTER TABLE `txzh_auth_group`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户组ID', AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `txzh_auth_rule`
--
ALTER TABLE `txzh_auth_rule`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '规则表ID', AUTO_INCREMENT=58;

--
-- 使用表AUTO_INCREMENT `txzh_bsys_config`
--
ALTER TABLE `txzh_bsys_config`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `txzh_bsys_superuser`
--
ALTER TABLE `txzh_bsys_superuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `txzh_bsys_super_log`
--
ALTER TABLE `txzh_bsys_super_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '超级用户操作日志表 id';

--
-- 使用表AUTO_INCREMENT `txzh_capital_log`
--
ALTER TABLE `txzh_capital_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `txzh_login_log`
--
ALTER TABLE `txzh_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- 使用表AUTO_INCREMENT `txzh_system`
--
ALTER TABLE `txzh_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `txzh_user`
--
ALTER TABLE `txzh_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;
COMMIT;

alter table `txzh_user` add `agent_id` int(11) default 0 comment'客户标识';
alter table `txzh_login_log` add `agent_id` int(11) default 0 comment'客户标识';
alter table `txzh_admin` add `agent_id` int(11) default 0 comment'客户标识';
alter table `txzh_admin` add `agent_name` varchar(255) NOT NULL DEFAULT '' COMMENT '客户(商家)名称';

alter table `txzh_user` add `is_customer_service` int(11) default 0 comment'客户标识';



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
