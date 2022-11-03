SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `buys`
-- ----------------------------
DROP TABLE IF EXISTS `buys`;
CREATE TABLE `buys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `cid` int(11) NOT NULL COMMENT '挑战id',
  `time` varchar(255) DEFAULT NULL COMMENT '购买时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `categorys`
-- ----------------------------
DROP TABLE IF EXISTS `categorys`;
CREATE TABLE `categorys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '分类标题',
  `time` varchar(255) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `challenges`
-- ----------------------------
DROP TABLE IF EXISTS `challenges`;
CREATE TABLE `challenges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '挑战标题',
  `state` int(1) NOT NULL DEFAULT '0' COMMENT '挑战标题状态 0 隐藏 1 显示',
  `content` longtext COMMENT '挑战描述',
  `hint` varchar(255) DEFAULT NULL COMMENT '挑战提示',
  `flag` varchar(255) NOT NULL COMMENT '挑战答案',
  `download` text COMMENT '挑战附件地址',
  `money` varchar(255) NOT NULL COMMENT '挑战需要的金币',
  `score` varchar(255) NOT NULL COMMENT '挑战分数',
  `solve` varchar(255) NOT NULL DEFAULT '0' COMMENT '解题成功人数',
  `tags` text COMMENT '挑战标签',
  `category` varchar(255) NOT NULL COMMENT '挑战分类',
  `time` varchar(255) NOT NULL COMMENT '挑战添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `money`
-- ----------------------------
DROP TABLE IF EXISTS `money`;
CREATE TABLE `money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `money` int(11) NOT NULL COMMENT '金币数量',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '增减类型 0 买附件减 1 解题加 2 签到加 3 系统奖励',
  `time` varchar(255) NOT NULL COMMENT '金币增减时间',
  `first` int(11) NOT NULL,
  `last` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `notify`
-- ----------------------------
DROP TABLE IF EXISTS `notify`;
CREATE TABLE `notify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `setting`
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT '站点标题',
  `desc` varchar(255) NOT NULL COMMENT '站点描述',
  `keywords` varchar(255) DEFAULT NULL,
  `state` int(11) NOT NULL COMMENT '是否开启注册',
  `verify` int(11) NOT NULL COMMENT '是否开启邮箱验证',
  `stats` varchar(255) NOT NULL COMMENT '统计ID',
  `smtp` varchar(255) NOT NULL COMMENT '邮箱服务器',
  `smtp_name` varchar(255) NOT NULL COMMENT '邮箱账号',
  `smtp_pass` varchar(255) NOT NULL COMMENT '邮箱密码',
  `money` int(11) NOT NULL DEFAULT '10' COMMENT '用户签到金币初始值',
  `conin` int(11) NOT NULL DEFAULT '100' COMMENT '用户注册赠送的金币',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of setting
-- ----------------------------
INSERT INTO `setting` VALUES ('1', 'MoeCTF', 'A Simple, Free CTF Platform', 'MoeCTF,CTF,WEB,CRYPTO,REVERSE,PWN,MISC,网络安全,CTF训练平台,CTFer,Hacker,黑客,信息安全', '0', '0', '', 'smtp.domain.com', '', '', '10', '100');

-- ----------------------------
-- Table structure for `solves`
-- ----------------------------
DROP TABLE IF EXISTS `solves`;
CREATE TABLE `solves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` varchar(255) NOT NULL COMMENT '挑战id',
  `uid` varchar(255) NOT NULL COMMENT '用户id',
  `score` varchar(255) NOT NULL COMMENT '挑战分数',
  `time` varchar(255) NOT NULL COMMENT '解题时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `avatar` text NOT NULL COMMENT '用户头像',
  `scores` varchar(255) DEFAULT NULL COMMENT '用户分数',
  `password` varchar(255) NOT NULL COMMENT '用户密码',
  `money` int(11) NOT NULL COMMENT '用户金币',
  `website` varchar(255) DEFAULT NULL COMMENT '用户网站',
  `email` varchar(255) NOT NULL COMMENT '用户邮箱',
  `content` text COMMENT '用户签名',
  `type` varchar(255) NOT NULL DEFAULT '0' COMMENT '用户类型 0 普通用户 1 管理员',
  `sign_days` varchar(255) DEFAULT NULL COMMENT '连续签到天数',
  `sign_time` varchar(255) DEFAULT NULL COMMENT '上次签到时间',
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '用户显示还是隐藏',
  `verify` int(11) NOT NULL DEFAULT '0' COMMENT '用户邮箱是否验证',
  `token` varchar(255) NOT NULL DEFAULT '' COMMENT '验证token',
  `time` varchar(255) NOT NULL COMMENT '用户注册时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', '', '', '###0918d318d77a701b5c3ad7aee135265c', '', '', '', '', '1', '2', '', '', '0', '', '1665621944');