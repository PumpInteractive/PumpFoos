-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema pumpfoos
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `pumpfoos` ;

-- -----------------------------------------------------
-- Schema pumpfoos
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `pumpfoos` DEFAULT CHARACTER SET utf8 ;
USE `pumpfoos` ;

-- -----------------------------------------------------
-- Table `pumpfoos`.`players`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pumpfoos`.`players` ;

CREATE TABLE IF NOT EXISTS `pumpfoos`.`players` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slack_user_id` VARCHAR(255) NOT NULL,
  `slack_user_name` VARCHAR(255) NOT NULL,
  `slack_profile_pic_url` VARCHAR(255) NOT NULL,
  `games_played` INT(10) UNSIGNED NOT NULL,
  `wins` INT(10) UNSIGNED NOT NULL,
  `losses` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `players_slack_user_id_idx` (`slack_user_id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pumpfoos`.`game_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pumpfoos`.`game_types` ;

CREATE TABLE IF NOT EXISTS `pumpfoos`.`game_types` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `number_of_players` ENUM('2', '4') NOT NULL DEFAULT '4',
  `score_to_win` TINYINT NOT NULL DEFAULT 5,
  `description` TEXT NULL,
  `default` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pumpfoos`.`games`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pumpfoos`.`games` ;

CREATE TABLE IF NOT EXISTS `pumpfoos`.`games` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `game_type_id` INT UNSIGNED NOT NULL,
  `start` DATETIME NOT NULL,
  `end` DATETIME NULL,
  `duration` INT UNSIGNED NULL,
  `team_1_final_score` TINYINT UNSIGNED NULL,
  `team_2_final_score` TINYINT UNSIGNED NULL,
  `winning_team` ENUM('1', '2') NULL,
  `losing_team` ENUM('1', '2') NULL,
  PRIMARY KEY (`id`),
  INDEX `games_game_type_id_idx` (`game_type_id` ASC),
  CONSTRAINT `games_game_type_id`
    FOREIGN KEY (`game_type_id`)
    REFERENCES `pumpfoos`.`game_types` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pumpfoos`.`men`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pumpfoos`.`men` ;

CREATE TABLE IF NOT EXISTS `pumpfoos`.`men` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `team` ENUM('1', '2') NOT NULL,
  `bar` ENUM('3-bar-goalie', '2-bar', '5-bar', '3-bar-attack') NOT NULL,
  `position` ENUM('near', 'near-middle', 'middle', 'far-middle', 'far') NOT NULL,
  `player_position` ENUM('attack', 'defence') NOT NULL,
  `scoring_shortcut_key` CHAR(1) NOT NULL,
  `scoring_key_code` TINYINT NOT NULL,
  `display_order` TINYINT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pumpfoos`.`goals`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pumpfoos`.`goals` ;

CREATE TABLE IF NOT EXISTS `pumpfoos`.`goals` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `game_id` INT UNSIGNED NOT NULL,
  `scoring_player_id` INT UNSIGNED NOT NULL,
  `scoring_man_id` INT UNSIGNED NOT NULL,
  `defending_player_id` INT UNSIGNED NOT NULL,
  `bar` ENUM('3-bar-goalie', '2-bar', '5-bar', '3-bar-attack') NOT NULL,
  `position` ENUM('near', 'near-middle', 'middle', 'far-middle', 'far') NOT NULL,
  `player_position` ENUM('attack', 'defence') NOT NULL,
  `team` ENUM('1', '2') NOT NULL,
  `time_of_goal` INT UNSIGNED NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `goals_game_id_idx` (`game_id` ASC),
  INDEX `goals_scoring_player_id_idx` (`scoring_player_id` ASC),
  INDEX `goals_scoring_man_id_idx` (`scoring_man_id` ASC),
  INDEX `goals_defending_player_id_idx` (`defending_player_id` ASC),
  CONSTRAINT `goals_game_id`
    FOREIGN KEY (`game_id`)
    REFERENCES `pumpfoos`.`games` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `goals_scoring_player_id`
    FOREIGN KEY (`scoring_player_id`)
    REFERENCES `pumpfoos`.`players` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `goals_scoring_man_id`
    FOREIGN KEY (`scoring_man_id`)
    REFERENCES `pumpfoos`.`men` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `goals_defending_player_id`
    FOREIGN KEY (`defending_player_id`)
    REFERENCES `pumpfoos`.`players` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pumpfoos`.`games_players`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pumpfoos`.`games_players` ;

CREATE TABLE IF NOT EXISTS `pumpfoos`.`games_players` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `game_id` INT UNSIGNED NOT NULL,
  `player_id` INT UNSIGNED NOT NULL,
  `team` ENUM('1', '2') NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `games_players_game_id_idx` (`game_id` ASC),
  INDEX `games_players_player_id_idx` (`player_id` ASC),
  CONSTRAINT `games_players_game_id`
    FOREIGN KEY (`game_id`)
    REFERENCES `pumpfoos`.`games` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `games_players_player_id`
    FOREIGN KEY (`player_id`)
    REFERENCES `pumpfoos`.`players` (`id`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `pumpfoos`.`players`
-- -----------------------------------------------------
START TRANSACTION;
USE `pumpfoos`;
INSERT INTO `pumpfoos`.`players` (`id`, `slack_user_id`, `slack_user_name`, `slack_profile_pic_url`, `games_played`, `wins`, `losses`) VALUES (DEFAULT, 'U052ZM7H9', 'sean', 'https://secure.gravatar.com/avatar/c89360d2945a74bf81d5903a5db98fd9.jpg?s=192&d=https%3A%2F%2Fa.slack-edge.com%2F7fa9%2Fimg%2Favatars%2Fava_0012-192.png', 45, 21, 24);
INSERT INTO `pumpfoos`.`players` (`id`, `slack_user_id`, `slack_user_name`, `slack_profile_pic_url`, `games_played`, `wins`, `losses`) VALUES (DEFAULT, 'U052BJ7S2', 'troy', 'https://secure.gravatar.com/avatar/3fcc295c42638dbd75cbe8a2d4340b5c.jpg?s=192&d=https%3A%2F%2Fa.slack-edge.com%2F7fa9%2Fimg%2Favatars%2Fava_0020-192.png', 64, 39, 25);
INSERT INTO `pumpfoos`.`players` (`id`, `slack_user_id`, `slack_user_name`, `slack_profile_pic_url`, `games_played`, `wins`, `losses`) VALUES (DEFAULT, 'U052Z95B5', 'scott', 'https://secure.gravatar.com/avatar/2e7cd69e5868bb22437c70a44ea7f509.jpg?s=192&d=https%3A%2F%2Fa.slack-edge.com%2F7fa9%2Fimg%2Favatars%2Fava_0015-192.png', 52, 25, 27);
INSERT INTO `pumpfoos`.`players` (`id`, `slack_user_id`, `slack_user_name`, `slack_profile_pic_url`, `games_played`, `wins`, `losses`) VALUES (DEFAULT, 'U08JRNPR7', 'brendan', 'https://avatars.slack-edge.com/2016-03-01/23831393376_cb6c805992a5b4b1cd2e_192.jpg', 70, 37, 33);
INSERT INTO `pumpfoos`.`players` (`id`, `slack_user_id`, `slack_user_name`, `slack_profile_pic_url`, `games_played`, `wins`, `losses`) VALUES (DEFAULT, 'U0532LGKV', 'jake', 'https://avatars.slack-edge.com/2016-01-22/19221767252_6eaf8a24d34a528f00d8_192.jpg', 64, 36, 28);

COMMIT;


-- -----------------------------------------------------
-- Data for table `pumpfoos`.`game_types`
-- -----------------------------------------------------
START TRANSACTION;
USE `pumpfoos`;
INSERT INTO `pumpfoos`.`game_types` (`id`, `name`, `number_of_players`, `score_to_win`, `description`, `default`) VALUES (DEFAULT, 'Doubles', '4', 5, '2 v 2 play', 1);
INSERT INTO `pumpfoos`.`game_types` (`id`, `name`, `number_of_players`, `score_to_win`, `description`, `default`) VALUES (DEFAULT, 'Singles', '2', 3, '1 v 1 play', 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `pumpfoos`.`men`
-- -----------------------------------------------------
START TRANSACTION;
USE `pumpfoos`;
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (1, '1', '3-bar-goalie', 'near', 'defence', 'q', 81, 1);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (2, '1', '3-bar-goalie', 'middle', 'defence', 'w', 87, 2);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (3, '1', '3-bar-goalie', 'far', 'defence', 'e', 69, 3);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (4, '1', '2-bar', 'near', 'defence', 'r', 82, 1);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (5, '1', '2-bar', 'far', 'defence', 't', 84, 2);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (6, '1', '5-bar', 'near', 'attack', 'y', 89, 1);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (7, '1', '5-bar', 'near-middle', 'attack', 'u', 85, 2);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (8, '1', '5-bar', 'middle', 'attack', 'i', 73, 3);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (9, '1', '5-bar', 'far-middle', 'attack', 'o', 79, 4);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (10, '1', '5-bar', 'far', 'attack', 'p', 80, 5);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (11, '1', '3-bar-attack', 'near', 'attack', 'a', 65, 1);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (12, '1', '3-bar-attack', 'middle', 'attack', 's', 83, 2);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (13, '1', '3-bar-attack', 'far', 'attack', 'd', 68, 3);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (14, '2', '3-bar-goalie', 'near', 'defence', 'f', 70, 3);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (15, '2', '3-bar-goalie', 'middle', 'defence', 'g', 71, 2);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (16, '2', '3-bar-goalie', 'far', 'defence', 'h', 72, 1);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (17, '2', '2-bar', 'near', 'defence', 'j', 74, 2);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (18, '2', '2-bar', 'far', 'defence', 'k', 75, 1);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (19, '2', '5-bar', 'near', 'attack', 'l', 76, 5);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (20, '2', '5-bar', 'near-middle', 'attack', 'z', 90, 4);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (21, '2', '5-bar', 'middle', 'attack', 'x', 88, 3);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (22, '2', '5-bar', 'far-middle', 'attack', 'c', 67, 2);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (23, '2', '5-bar', 'far', 'attack', 'v', 86, 1);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (24, '2', '3-bar-attack', 'near', 'attack', 'b', 66, 3);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (25, '2', '3-bar-attack', 'middle', 'attack', 'n', 78, 2);
INSERT INTO `pumpfoos`.`men` (`id`, `team`, `bar`, `position`, `player_position`, `scoring_shortcut_key`, `scoring_key_code`, `display_order`) VALUES (26, '2', '3-bar-attack', 'far', 'attack', 'm', 77, 1);

COMMIT;

