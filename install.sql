CREATE DATABASE `rpg`;

USE `rpg`;

CREATE TABLE `user` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255),
  `password` VARCHAR(255),
  PRIMARY KEY (`user_id`)
);

CREATE TABLE `party` (
  `party_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `name` VARCHAR(255),
  PRIMARY KEY (`party_id`)
);

CREATE TABLE `unit` (
  `unit_id` INT NOT NULL AUTO_INCREMENT,
  `party_id` INT NOT NULL,
  `job_id` INT NOT NULL,
  `name` VARCHAR(255),
  `hp` SMALLINT,
  `mp` SMALLINT,
  `str` SMALLINT,
  `agl` SMALLINT,
  `sta` SMALLINT,
  `mag` SMALLINT,
  PRIMARY KEY (`unit_id`)
);

CREATE TABLE `job` (
  `job_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `sprite` VARCHAR(255) NOT NULL,
  `move_cost` INT(255) NOT NULL,
  PRIMARY KEY (`job_id`)
);

CREATE TABLE `action` (
  `action_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `range` DECIMAL(5, 2) NOT NULL,
  `spread` DECIMAL(5, 2) NOT NULL,
  `action_cost` INT(255) NOT NULL,
  PRIMARY KEY (`action_id`)
);

CREATE TABLE `job_action` (
  `job_action_id` INT NOT NULL AUTO_INCREMENT,
  `job_id` INT NOT NULL,
  `action_id` INT NOT NULL,
  PRIMARY KEY (`job_action_id`)
);

CREATE TABLE `battle` (
  `battle_id` INT NOT NULL AUTO_INCREMENT,
  `battle_code` VARCHAR(255) NOT NULL,
  `home_id` INT NOT NULL,
  `away_id` INT NOT NULL,
  `winner_id` INT,
  `funds` INT DEFAULT 0,
  `points` INT DEFAULT 0,
  PRIMARY KEY (`battle_id`)
);

CREATE TABLE `spoils` (
  `spoils_id` INT NOT NULL AUTO_INCREMENT,
  `battle_id` INT NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  `user_id` INT NOT NULL,
  `party_id` INT NOT NULL,
  `unit_id` INT NOT NULL,
  `value` INT,
  `item_id` INT NULL,
  `applied` BOOLEAN,
  PRIMARY KEY (`spoils_id`)
);

CREATE TABLE `item` (
  `item_id` INT NOT NULL AUTO_INCREMENT,
  `slot_id` INT NULL,
  `type` VARCHAR(255) NOT NULL,
  `subtype` VARCHAR(255) NOT NULL,
  `job_id` INT NULL,
  `pwr` SMALLINT,
  `def` SMALLINT,
  `hp` SMALLINT,
  `mp` SMALLINT,
  `str` SMALLINT,
  `agl` SMALLINT,
  `sta` SMALLINT,
  `mag` SMALLINT,
  `cost` INT DEFAULT 1,
  PRIMARY KEY (`item_id`)
);

CREATE TABLE `slot` (
  `slot_id` INT NOT NULL,
  `name` VARCHAR(255)
);

CREATE TABLE `item_job` (
  `item_job_id` INT NOT NULL AUTO_INCREMENT,
  `item_id` INT NOT NULL,
  `job_id` INT NOT NULL,
  PRIMARY KEY (`item_job_id`)
);

CREATE TABLE `item_party` (
  `item_party_id` INT NOT NULL AUTO_INCREMENT,
  `item_id` INT NOT NULL,
  `party_id` INT NOT NULL,
  PRIMARY KEY (`item_party_id`),
  UNIQUE KEY `in_inventory` (`item_id`)
);

CREATE TABLE `item_unit_slot` (
  `item_party_id` INT NOT NULL AUTO_INCREMENT,
  `item_id` INT NOT NULL,
  `unit_id` INT NOT NULL,
  `slot_id` INT NOT NULL,
  PRIMARY KEY (`item_party_id`),
  UNIQUE KEY `slotted_item` (`item_id`, `unit_id`, `slot_id`)
);

INSERT INTO `job` (`job_id`, `name`, `sprite`, `move_cost`)
VALUES
(1, 'Fighter', 'F', 20),
(2, 'Archer', 'A', 30),
(3, 'Wizard', 'W', 40);

INSERT INTO `action` (`action_id`, `name`, `range`, `spread`, `action_cost`)
VALUES
(1, 'melee', 1.5, 0, 50),
(2, 'arrow', 5, 0, 50),
(3, 'fire', 3, 1.5, 50);

INSERT INTO `job_action` (`job_id`, `action_id`)
VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO `slot` (`slot_id`, `name`)
VALUES
(1, 'right'),
(2, 'left'),
(3, 'head'),
(4, 'body'),
(5, 'accessory');