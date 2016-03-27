-- 2016-02-26 Update

ALTER TABLE `user_stats`
ADD `slack_user_name` varchar(255) COLLATE 'latin1_swedish_ci' NOT NULL AFTER `slack_user_id`,
ADD `slack_profile_pic_url` varchar(255) COLLATE 'latin1_swedish_ci' NOT NULL AFTER `slack_user_name`;