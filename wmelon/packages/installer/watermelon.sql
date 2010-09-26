-- 
-- Structure for table `wm_benchmark`
-- 

CREATE TABLE `wm_benchmark` (
  `benchmark_name` varchar(256) NOT NULL,
  `benchmark_value` int(11) NOT NULL,
  KEY `name` (`benchmark_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_pms`
-- 

CREATE TABLE `wm_pms` (
  `pm_id` int(11) NOT NULL AUTO_INCREMENT,
  `pm_sender` int(11) NOT NULL,
  `pm_recipient` int(11) NOT NULL,
  `pm_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pm_subject` varchar(100) NOT NULL,
  `pm_content` text NOT NULL,
  `pm_recipientRead` tinyint(1) NOT NULL,
  `pm_recipientDeleted` tinyint(1) NOT NULL,
  `pm_senderDeleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`pm_id`),
  KEY `pm_sender` (`pm_sender`),
  KEY `pm_recipient` (`pm_recipient`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_registry`
-- 

CREATE TABLE `wm_registry` (
  `registry_name` varchar(256) CHARACTER SET latin1 NOT NULL,
  `registry_value` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`registry_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Structure for table `wm_users`
-- 

CREATE TABLE `wm_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(40) NOT NULL COMMENT 'name used when logging in',
  `user_password` varchar(40) NOT NULL,
  `user_salt` varchar(16) NOT NULL,
  `user_nick` varchar(40) NOT NULL COMMENT 'displayed user name',
  `user_email` varchar(64) NOT NULL,
  `user_lastseen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_login` (`user_login`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
