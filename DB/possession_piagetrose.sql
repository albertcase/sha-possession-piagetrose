DROP TABLE IF EXISTS `lounge_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lounge_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `useid` varchar(40) NOT NULL,
  `title` varchar(40) NOT NULL,
  `firstname` varchar(90) NOT NULL,
  `lastname` varchar(90) NOT NULL,
  `mobile` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `city` varchar(40) NOT NULL,
  `getmsg` enum('0','1') DEFAULT '0',
  `submittime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `useid` (`useid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
