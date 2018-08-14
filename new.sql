INSERT INTO unbeleadsablev1.`users` (`user_id`,`main_user`,`username`,`password`,`email`,`name`,`user_country`,`level`,`last_login`,`changed_password`,`status`) VALUES (176,NULL,'NANDHINI','a6235d809e3f4888bc228c7239f47105','nandhini@unbeleadsable.com','Nandhini','CA',100,'2018-08-14 09:34:13',1,1);

CREATE TABLE unbeleadsablev1.`agent_leads` (
  `lead_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `internal_id` varchar(20) NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lead_name` varchar(150) NOT NULL,
  `lead_email` varchar(50) NOT NULL,
  `lead_phone` varchar(20) NOT NULL,
  `lead_areas` text NOT NULL,
  `lead_agency` varchar(100) NOT NULL,
  `lead_lang` varchar(10) NOT NULL,
  `lead_license` varchar(100) DEFAULT NULL,
  `lead_board` varchar(100) DEFAULT NULL,
  `lead_ref` varchar(25) DEFAULT NULL,
  `lead_country` varchar(50) NOT NULL,
  `lead_type` varchar(50) NOT NULL,
  `lead_status` tinyint(3) NOT NULL DEFAULT '1',
  `lead_comments` text,
  `lead_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`lead_id`)
) ENGINE=MyISAM AUTO_INCREMENT=300 DEFAULT CHARSET=latin1
