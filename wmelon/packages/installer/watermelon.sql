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
-- Structure for table `wm_registry`
-- 

DROP TABLE IF EXISTS `wm_registry`;
CREATE TABLE IF NOT EXISTS `wm_registry` (
  `registry_name` varchar(256) CHARACTER SET latin1 NOT NULL,
  `registry_value` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`registry_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;