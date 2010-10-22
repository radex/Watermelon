-- 
-- Structure for table `wm_benchmark`
-- 

DROP TABLE IF EXISTS `wm_benchmark`;
CREATE TABLE IF NOT EXISTS `wm_benchmark` (
  `benchmark_name` varchar(256) NOT NULL,
  `benchmark_value` int(11) NOT NULL,
  KEY `name` (`benchmark_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_blogposts`
-- 

DROP TABLE IF EXISTS `wm_blogposts`;
CREATE TABLE IF NOT EXISTS `wm_blogposts` (
  `blogpost_id` int(11) NOT NULL AUTO_INCREMENT,
  `blogpost_author` int(11) NOT NULL,
  `blogpost_created` int(10) NOT NULL,
  `blogpost_title` varchar(256) NOT NULL,
  `blogpost_content` text NOT NULL,
  `blogpost_beginning` text,
  PRIMARY KEY (`blogpost_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_comments`
-- 

DROP TABLE IF EXISTS `wm_comments`;
CREATE TABLE IF NOT EXISTS `wm_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_authorID` int(11) DEFAULT NULL,
  `comment_authorName` varchar(40) DEFAULT NULL,
  `comment_authorEmail` varchar(64) DEFAULT NULL,
  `comment_authorWebsite` varchar(40) DEFAULT NULL,
  `comment_created` int(10) NOT NULL,
  `comment_text` text NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_comments_records`
-- 

DROP TABLE IF EXISTS `wm_comments_records`;
CREATE TABLE IF NOT EXISTS `wm_comments_records` (
  `commrecord_record` int(11) NOT NULL,
  `commrecord_comment` int(11) NOT NULL,
  `commrecord_type` varchar(40) NOT NULL,
  KEY `blogpostcomm_post` (`commrecord_record`),
  KEY `commrecord_type` (`commrecord_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_pages`
-- 

DROP TABLE IF EXISTS `wm_pages`;
CREATE TABLE IF NOT EXISTS `wm_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_author` int(11) NOT NULL,
  `page_created` int(10) NOT NULL,
  `page_name` varchar(256) NOT NULL COMMENT 'Part of URI',
  `page_title` varchar(256) NOT NULL,
  `page_content` text NOT NULL,
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `page_name` (`page_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_privileges`
-- 

DROP TABLE IF EXISTS `wm_privileges`;
CREATE TABLE IF NOT EXISTS `wm_privileges` (
  `privilege_user` int(11) NOT NULL,
  `privilege_privilege` varchar(64) NOT NULL,
  KEY `privileges_user` (`privilege_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_registry`
-- 

DROP TABLE IF EXISTS `wm_registry`;
CREATE TABLE IF NOT EXISTS `wm_registry` (
  `registry_name` varchar(256) CHARACTER SET latin1 NOT NULL,
  `registry_value` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`registry_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_users`
-- 

DROP TABLE IF EXISTS `wm_users`;
CREATE TABLE IF NOT EXISTS `wm_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(40) NOT NULL COMMENT 'name used when logging in',
  `user_password` varchar(40) NOT NULL,
  `user_salt` varchar(16) NOT NULL,
  `user_nick` varchar(40) NOT NULL COMMENT 'displayed user name',
  `user_email` varchar(64) NOT NULL,
  `user_lastseen` int(10) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_login` (`user_login`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

