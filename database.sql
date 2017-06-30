DROP DATABASE IF EXISTS vuex_cms;
CREATE DATABASE vuex_cms;
USE vuex_cms;

/** USER ACCOUNT STUFF **/
CREATE TABLE `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL,
  `passwordHash` VARCHAR(255) NOT NULL,
  `firstname` VARCHAR(32) NOT NULL,
  `lastname` VARCHAR(32) NOT NULL,
  `changed` TIMESTAMP, -- DEFAULT '1970-01-01 00:00:01',
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

/** USER PERMISSION STUFF **/
CREATE TABLE IF NOT EXISTS `permission` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `categoryID` INT(11) NOT NULL,
  `name` VARCHAR(256) NOT NULL,
  `description` TEXT,
  `defaultValue` INT(11) DEFAULT '1',
  PRIMARY KEY(`id`)
);

CREATE TABLE IF NOT EXISTS `permission_value` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `groupID` INT(11) NOT NULL,
  `permissionID` INT(11) NOT NULL,
  `value` INT(11) NOT NULL,
  PRIMARY KEY(`id`, `groupID`, `permissionID`)
);

CREATE TABLE IF NOT EXISTS `permission_category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(256) NOT NULL,
  `description` TEXT,
  PRIMARY KEY(`id`)
);

/** PLUGIN SYSTEM STUFF **/
CREATE TABLE `plugin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `active` TINYINT(11) NOT NULL DEFAULT 0,
  `namespace` VARCHAR(32) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `label` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255),
  `version` VARCHAR(32),
  `author` VARCHAR(255),
  `email` VARCHAR(255),
  `website` VARCHAR(255),
  `changed` TIMESTAMP, -- DEFAULT '1970-01-01 00:00:01',
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `name` (`name`)
);

CREATE TABLE `plugin_dependency` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `pluginID` INT(11) NOT NULL,
  `name` VARCHAR(32) NOT NULL,
  `version` VARCHAR(32) NOT NULL
);

/** SITE SYSTEM STUFF **/
CREATE TABLE `site` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `active` TINYINT(1) DEFAULT 1,
  `label` VARCHAR(255) NOT NULL,
  `host` VARCHAR(255) NOT NULL,
  `hosts` TEXT NOT NULL,
  `secure` TINYINT(1) DEFAULT 0,
  `changed` TIMESTAMP, -- DEFAULT '1970-01-01 00:00:01',
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `site_item` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `siteID` INT(11) NOT NULL,
  `parentID` INT(11),
  `label` VARCHAR(255) NOT NULL,
  `type` INT(11) NOT NULL,
  `changed` TIMESTAMP, -- DEFAULT '1970-01-01 00:00:01',
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);