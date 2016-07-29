CREATE TABLE `trophies` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`description` VARCHAR(255) NOT NULL,
	`action` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`)
) AUTO_INCREMENT=3;

CREATE TABLE `trophies_players` (
	`player_id` INT(11) UNSIGNED NOT NULL,
	`trophy_id` INT(11) UNSIGNED NOT NULL,
	INDEX `trophies_players_player_id` (`player_id`),
	INDEX `trophies_players_trophy_id` (`trophy_id`),
	CONSTRAINT `trophies_players_player_id` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`),
	CONSTRAINT `trophies_players_trophy_id` FOREIGN KEY (`trophy_id`) REFERENCES `trophies` (`id`)
);


INSERT INTO `trophies` (`id`, `name`, `description`, `action`) VALUES (1, 'Hat Trick', '{player_name} has scored 3 goals in a row with the same player.', 'hat-trick');
INSERT INTO `trophies` (`id`, `name`, `description`, `action`) VALUES (2, 'Quad Trick', '{player_name} has scored 4 goals in a row with the same player.', 'quad-trick');
INSERT INTO `trophies` (`id`, `name`, `description`, `action`) VALUES (3, 'Goalie Goal', '{player_name} has scored a goal with their goalie. Amazing!', 'goalie-goal');
