UPDATE `trophies` SET `name` = 'Natural Hat Trick', `action` = 'natural-hat-trick' WHERE `action` = 'hat-trick';
UPDATE `trophies` SET `name` = 'Natural Quad Trick', `action` = 'natural-quad-trick' WHERE `action` = 'quad-trick';
ALTER TABLE `trophies` ADD `soundfile` VARCHAR(255) NOT NULL DEFAULT 'default.mp3';
ALTER TABLE `trophies_players` ADD `created` TIMESTAMP NULL AFTER `trophy_id`;
