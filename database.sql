DROP DATABASE IF EXISTS vuex_cms;
CREATE DATABASE vuex_cms;
USE vuex_cms;

CREATE TABLE `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL,
  `passwordHash` VARCHAR(255) NOT NULL,
  `firstname` VARCHAR(32) NOT NULL,
  `lastname` VARCHAR(32) NOT NULL,
  `changed` TIMESTAMP DEFAULT '1970-01-01 00:00:01',
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `groupID` INT(11) NOT NULL
);

INSERT INTO user (id, email, passwordHash, firstname, lastname, changed, created, groupID) VALUES
  (1, 'demo@demo.com', '', 'Administrator', '', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 1);

CREATE TABLE `user_group` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `label` VARCHAR(32) NOT NULL
);

INSERT INTO `user_group` (id, label) VALUES
  (1, 'Master');

CREATE TABLE `user_session` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userID` INT(11) NOT NULL,
  `hash` VARCHAR(32) NOT NULL,
  `expires` DATETIME
);