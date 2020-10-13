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
  `user_id` INT NOT NULL,
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
  `party_id` INT NOT NULL,
  `winner` VARCHAR(255),
  `funds` INT,
  `points` INT,
  PRIMARY KEY (`battle_id`)
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