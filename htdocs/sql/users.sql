SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `phone` varchar(20) NOT NULL COMMENT '手机号码',
  `pwd` varchar(255) NOT NULL COMMENT '用户密码',
  `regtime` int(11) NOT NULL COMMENT '注册时间时间戳',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
