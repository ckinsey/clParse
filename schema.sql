CREATE SCHEMA `clParse` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

CREATE  TABLE `clParse`.`entries` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(128) NULL ,
  `body` TEXT NULL ,
  `date` DATETIME NOT NULL ,
  `about` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `date` (`date` ASC) );

ALTER TABLE `clParse`.`entries` ADD COLUMN `is_read` TINYINT(1)  NULL DEFAULT 0  AFTER `about` ;
ALTER TABLE `clParse`.`entries` ADD COLUMN `is_flagged` TINYINT(1) NOT NULL DEFAULT 0  AFTER `is_read` , CHANGE COLUMN `is_read` `is_read` TINYINT(1) NOT NULL DEFAULT '0'  ;
