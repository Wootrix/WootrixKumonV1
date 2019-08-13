/*
 * Created By - DJ
 * purpose - All database SQLs 
 * Created ON - 25 June 2013
 * */

CREATE TABLE IF NOT EXISTS `tbl_album_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album_id` int(11) DEFAULT NULL,
  `video_id` varchar(255) DEFAULT NULL,
  `status` enum('0','1') DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `album_id_idx` (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `tbl_favourites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `video_id` varchar(255) DEFAULT NULL,
  `is_favourite` enum('0','1') DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_type` int(11) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `facebookid` varchar(255) DEFAULT NULL,
  `twitterId` varchar(255) DEFAULT NULL,
  `registration_type` enum('app','facebook','twitter') DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tbl_user_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `album_name` varchar(255) DEFAULT NULL,
  `status` enum('0','1') DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `tbl_user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `lat` varchar(20) DEFAULT NULL,
  `long` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `device_type` varchar(100) DEFAULT NULL,
  `device_model` varchar(255) DEFAULT NULL,
  `device_token` text,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_on` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `tbl_user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


ALTER TABLE `tbl_album_videos`
  ADD CONSTRAINT `album_id` FOREIGN KEY (`album_id`) REFERENCES `tbl_user_albums` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

  
ALTER TABLE `tbl_user_profile`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

  
CREATE TABLE IF NOT EXISTS `tbl_user_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `video_name` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_userid_users_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table will stror users profile data like name dob' AUTO_INCREMENT=1 ;	


//added by DJ on 26June2013
ALTER TABLE  `tbl_user_profile` DROP  `email`;

ALTER TABLE  `tbl_users` ADD  `email` VARCHAR( 100 ) NOT NULL AFTER  `id`;

ALTER TABLE `tbl_users` ADD `website` VARCHAR( 255 ) NOT NULL AFTER `registration_type`;

ALTER TABLE `tbl_users` ADD `description` VARCHAR( 255 ) NOT NULL AFTER `website`; 

ALTER TABLE `tbl_users` DROP `profile_photo`;

ALTER TABLE `tbl_users` DROP `website`;

ALTER TABLE `tbl_users` DROP `description`;
 
ALTER TABLE `tbl_user_profile` ADD `website` VARCHAR( 255 ) NOT NULL AFTER `image`;

ALTER TABLE `tbl_user_profile` ADD `description` VARCHAR( 255 ) NOT NULL AFTER `website`; 

ALTER TABLE  `tbl_favourites` CHANGE  `is_favourite`  `is_favourite` TINYINT( 4 ) NOT NULL;

CREATE TABLE IF NOT EXISTS `tbl_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `video_id` varchar(255) DEFAULT NULL,
  `is_liked` tinyint(4) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


//added by KV on 27June2013
ALTER TABLE `tbl_album_videos` CHANGE `video_id` `video_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `tbl_favourites` CHANGE `video_id` `video_id` INT( 11 ) NULL DEFAULT NULL;

ALTER TABLE  `tbl_likes` CHANGE  `video_id`  `video_id` INT( 255 ) NULL DEFAULT NULL;