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
  `blogpost_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `blogpost_title` varchar(256) NOT NULL,
  `blogpost_content` text NOT NULL,
  `blogpost_beginning` text,
  PRIMARY KEY (`blogpost_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_blogposts`
-- 

INSERT INTO `wm_blogposts` (`blogpost_id`, `blogpost_author`, `blogpost_created`, `blogpost_title`, `blogpost_content`, `blogpost_beginning`) VALUES
  ('1', '0', '2010-10-11 21:33:52', 'Testowy tytuł (1)', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną,', NULL),
  ('2', '0', '2010-10-11 21:34:43', 'Testowy tytuł (2)', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną, Że znowu do piersi kryje, odsłaniając ramiona i palestra, i całował. Zaczęła się spory w dalekim mieści kończył tak pan Rejent na niej z nim padnie. Dalej Jasiński, młodzian piękny chart z mnóstwem gości nie ma albo o tańcach, nawet wozy, w rozmowę lecz nie nalewa szklanki, i raptem paniczyki młode z drzew raz zawitała moda i stryjaszkiem jedno i stajennym i chołodziec litewski milcząc żwawo jedli. , choć młodzież teraźniejsza, Że znowu je w wielkiej peruce, którą do kraju. Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.', 'Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.'),
  ('4', '0', '2010-10-11 21:33:52', 'Testowy tytuł (1)', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną,', NULL),
  ('5', '0', '2010-10-11 21:34:43', 'Testowy tytuł (2)', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną, Że znowu do piersi kryje, odsłaniając ramiona i palestra, i całował. Zaczęła się spory w dalekim mieści kończył tak pan Rejent na niej z nim padnie. Dalej Jasiński, młodzian piękny chart z mnóstwem gości nie ma albo o tańcach, nawet wozy, w rozmowę lecz nie nalewa szklanki, i raptem paniczyki młode z drzew raz zawitała moda i stryjaszkiem jedno i stajennym i chołodziec litewski milcząc żwawo jedli. , choć młodzież teraźniejsza, Że znowu je w wielkiej peruce, którą do kraju. Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.', 'Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.'),
  ('6', '0', '2010-10-11 21:33:52', 'Testowy tytuł (1)', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną,', NULL),
  ('7', '0', '2010-10-11 21:34:43', 'Testowy tytuł (2)', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną, Że znowu do piersi kryje, odsłaniając ramiona i palestra, i całował. Zaczęła się spory w dalekim mieści kończył tak pan Rejent na niej z nim padnie. Dalej Jasiński, młodzian piękny chart z mnóstwem gości nie ma albo o tańcach, nawet wozy, w rozmowę lecz nie nalewa szklanki, i raptem paniczyki młode z drzew raz zawitała moda i stryjaszkiem jedno i stajennym i chołodziec litewski milcząc żwawo jedli. , choć młodzież teraźniejsza, Że znowu je w wielkiej peruce, którą do kraju. Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.', 'Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.'),
  ('8', '0', '2010-10-11 21:33:52', 'Testowy tytuł (1)', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną,', NULL),
  ('9', '0', '2010-10-11 21:34:43', 'Testowy tytuł (2)', 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Ile cię stracił. Dziś człowieka nie chciałby do Polski jak zdrowe oblicz gospodarza, w gościnę zaprasza. Właśnie dwukonną bryką wjechał młody ja sam na dachu. Wtem zapadło do łona a młodzież lepsza, ale powiedzieć nie chciałby do włosów, włosy pozwijane w granatowym kontuszu stał w palcach i pan Wojski towarzystwa nam starym serca rosną, Że znowu do piersi kryje, odsłaniając ramiona i palestra, i całował. Zaczęła się spory w dalekim mieści kończył tak pan Rejent na niej z nim padnie. Dalej Jasiński, młodzian piękny chart z mnóstwem gości nie ma albo o tańcach, nawet wozy, w rozmowę lecz nie nalewa szklanki, i raptem paniczyki młode z drzew raz zawitała moda i stryjaszkiem jedno i stajennym i chołodziec litewski milcząc żwawo jedli. , choć młodzież teraźniejsza, Że znowu je w wielkiej peruce, którą do kraju. Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.', 'Mowy starca krążyły we zboże i w naukach mniej zgorszenia. Ach, ja pamiętam czasy, kiedy do folwarku nie miał głos zabierać. Umilkli wszyscy poszli za zającami nie pyta bo tak Suwarów w Piramidy, w miechu. Starzy na naród bo.');

-- 
-- Structure for table `wm_pms`
-- 

DROP TABLE IF EXISTS `wm_pms`;
CREATE TABLE IF NOT EXISTS `wm_pms` (
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

DROP TABLE IF EXISTS `wm_registry`;
CREATE TABLE IF NOT EXISTS `wm_registry` (
  `registry_name` varchar(256) CHARACTER SET latin1 NOT NULL,
  `registry_value` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`registry_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_registry`
-- 

INSERT INTO `wm_registry` (`registry_name`, `registry_value`) VALUES
  ('wmelon', 'O:8:\"stdClass\":13:{s:11:\"modulesList\";O:8:\"stdClass\":5:{s:11:\"controllers\";a:4:{s:4:\"blog\";a:2:{i:0;s:4:\"blog\";i:1;b:0;}s:4:\"test\";a:2:{i:0;s:4:\"test\";i:1;b:0;}s:6:\"cnthnd\";a:2:{i:0;s:4:\"test\";i:1;b:1;}s:4:\"e404\";a:2:{i:0;s:10:\"watermelon\";i:1;b:0;}}s:6:\"models\";a:3:{s:4:\"blog\";a:2:{i:0;s:4:\"blog\";i:1;b:0;}s:9:\"testmodel\";a:2:{i:0;s:4:\"test\";i:1;b:0;}s:10:\"testmodel2\";a:2:{i:0;s:4:\"test\";i:1;b:1;}}s:9:\"blocksets\";a:2:{s:4:\"test\";a:2:{i:0;s:4:\"test\";i:1;b:0;}s:5:\"test2\";a:2:{i:0;s:4:\"test\";i:1;b:1;}}s:10:\"extensions\";a:3:{s:4:\"test\";a:2:{i:0;s:4:\"test\";i:1;b:0;}s:5:\"test2\";a:2:{i:0;s:4:\"test\";i:1;b:1;}s:4:\"auth\";a:2:{i:0;s:10:\"watermelon\";i:1;b:0;}}s:5:\"skins\";a:1:{i:0;s:7:\"wcmslay\";}}s:8:\"autoload\";a:3:{i:0;s:4:\"test\";i:1;s:5:\"test2\";i:2;s:4:\"auth\";}s:17:\"controllerHandler\";N;s:17:\"defaultController\";s:4:\"test\";s:7:\"siteURL\";s:29:\"http://localhost/w/index.php/\";s:9:\"systemURL\";s:26:\"http://localhost/w/wmelon/\";s:4:\"skin\";s:7:\"wcmslay\";s:4:\"lang\";s:2:\"pl\";s:8:\"siteName\";s:12:\"Nazwa strony\";s:10:\"siteSlogan\";s:13:\"Slogan strony\";s:6:\"footer\";s:29:\"Testowanie <em>stopki</em>â€¦\";s:10:\"blockMenus\";a:1:{i:0;a:4:{i:0;a:4:{i:0;s:9:\"Test::foo\";i:1;s:4:\"test\";i:2;s:3:\"foo\";i:3;a:0:{}}i:1;a:4:{i:0;s:9:\"Test::bar\";i:1;s:4:\"test\";i:2;s:3:\"bar\";i:3;a:2:{i:0;s:3:\"foo\";i:1;s:3:\"bar\";}}i:2;a:4:{i:0;s:11:\"Test2::foo2\";i:1;s:5:\"test2\";i:2;s:4:\"foo2\";i:3;a:0:{}}i:3;a:4:{i:0;s:11:\"Test2::bar2\";i:1;s:5:\"test2\";i:2;s:4:\"bar2\";i:3;a:2:{i:0;s:4:\"foo2\";i:1;s:4:\"bar2\";}}}}s:9:\"textMenus\";a:1:{i:0;a:2:{i:0;a:3:{i:0;s:4:\"Blog\";i:1;s:33:\"http://localhost/w/index.php/blog\";i:2;s:9:\"Blooog!!!\";}i:1;a:3:{i:0;s:5:\"Testy\";i:1;s:33:\"http://localhost/w/index.php/test\";i:2;N;}}}}');

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
  `user_lastseen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_login` (`user_login`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- 
-- Data for table `wm_users`
-- 

INSERT INTO `wm_users` (`user_id`, `user_login`, `user_password`, `user_salt`, `user_nick`, `user_email`, `user_lastseen`) VALUES
  ('1', 'radex', 'ac2039247f211bd4e69667aade2e25eac3beecaf', '1234567890123456', '', '', '2010-10-12 20:19:44');

