SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `department`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `department` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `department` (
  `depart_id` INT NOT NULL AUTO_INCREMENT ,
  `depart_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`depart_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `user` (
  `user_id` INT NOT NULL AUTO_INCREMENT ,
  `user_login` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `user_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `user_pwd` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `user_depart_id` INT NOT NULL ,
  PRIMARY KEY (`user_id`) ,
  CONSTRAINT `FK_department`
    FOREIGN KEY (`user_depart_id` )
    REFERENCES `ad_imp`.`department` (`depart_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;
CREATE INDEX `FK_department` ON `user` (`user_depart_id` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `trip_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trip_type` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `trip_type` (
  `trip_type_id` INT NOT NULL AUTO_INCREMENT ,
  `trip_type_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`trip_type_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `upfiles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upfiles` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `upfiles` (
  `upfile_id` INT NOT NULL AUTO_INCREMENT ,
  `upfile_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `upfile_sysname` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `upfile_time` INT NOT NULL ,
  `upfile_user_id` INT NOT NULL ,
  `upfile_ip` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `upfile_ext` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`upfile_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `trip`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `trip` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `trip` (
  `trip_id` INT NOT NULL AUTO_INCREMENT ,
  `trip_user_id` INT NOT NULL ,
  `trip_project_id` INT NOT NULL ,
  `trip_type_id` INT NOT NULL ,
  `trip_leaving_date` INT NOT NULL ,
  `trip_back_date` INT NOT NULL ,
  `trip_day_off` INT NOT NULL ,
  `trip_location` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `trip_sender_depart` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `trip_sender` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `trip_fee` INT NOT NULL ,
  `trip_result` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `trip_contact` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL ,
  `trip_phone` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL ,
  `trip_report_doc_id` INT NOT NULL ,
  PRIMARY KEY (`trip_id`) ,
  CONSTRAINT `FK_trip_type`
    FOREIGN KEY (`trip_type_id` )
    REFERENCES `ad_imp`.`trip_type` (`trip_type_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_trip_doc`
    FOREIGN KEY (`trip_report_doc_id` )
    REFERENCES `ad_imp`.`upfiles` (`upfile_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_trip_user`
    FOREIGN KEY (`trip_user_id` )
    REFERENCES `ad_imp`.`user` (`user_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci
COMMENT = '出差信息表';

SHOW WARNINGS;
CREATE INDEX `FK_trip_type` ON `trip` (`trip_type_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_trip_doc` ON `trip` (`trip_report_doc_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_trip_user` ON `trip` (`trip_user_id` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `project`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `project` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `project` (
  `project_id` INT NOT NULL AUTO_INCREMENT ,
  `project_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `project_no` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`project_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `training_plan`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `training_plan` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `training_plan` (
  `tplan_id` INT NOT NULL AUTO_INCREMENT ,
  `tplan_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `tplan_doc_id` INT NOT NULL ,
  `tplan_user_id` INT NOT NULL ,
  `tplan_modify_date` INT NOT NULL ,
  PRIMARY KEY (`tplan_id`) ,
  CONSTRAINT `FK_tplan_doc`
    FOREIGN KEY (`tplan_doc_id` )
    REFERENCES `ad_imp`.`upfiles` (`upfile_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_tplan_user`
    FOREIGN KEY (`tplan_user_id` )
    REFERENCES `ad_imp`.`user` (`user_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;
CREATE INDEX `FK_tplan_doc` ON `training_plan` (`tplan_doc_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_tplan_user` ON `training_plan` (`tplan_user_id` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `training_content_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `training_content_type` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `training_content_type` (
  `t_content_type_id` INT NOT NULL AUTO_INCREMENT ,
  `t_content_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`t_content_type_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `training_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `training_type` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `training_type` (
  `ttype_id` INT NOT NULL AUTO_INCREMENT ,
  `ttype_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`ttype_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `training`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `training` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `training` (
  `training_id` INT NOT NULL AUTO_INCREMENT ,
  `t_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `t_type_id` INT NOT NULL ,
  `t_presenter` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `t_date` INT NOT NULL ,
  `t_location` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `t_content_type_id` INT NOT NULL ,
  `t_grade` INT NOT NULL ,
  `t_period` INT NOT NULL ,
  `t_doc_id` INT NOT NULL ,
  PRIMARY KEY (`training_id`) ,
  CONSTRAINT `FK_training_content_type`
    FOREIGN KEY (`t_content_type_id` )
    REFERENCES `ad_imp`.`training_content_type` (`t_content_type_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_training_type`
    FOREIGN KEY (`t_type_id` )
    REFERENCES `ad_imp`.`training_type` (`ttype_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_training_doc`
    FOREIGN KEY (`t_doc_id` )
    REFERENCES `ad_imp`.`upfiles` (`upfile_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;
CREATE INDEX `FK_training_content_type` ON `training` (`t_content_type_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_training_type` ON `training` (`t_type_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_training_doc` ON `training` (`t_doc_id` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `personal_training`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `personal_training` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `personal_training` (
  `p_training_id` INT NOT NULL AUTO_INCREMENT ,
  `p_training_user_id` INT NOT NULL ,
  `p_training_presenter` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `p_training_date` INT NOT NULL ,
  `p_training_location` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `p_training_type_id` INT NOT NULL ,
  `p_training_fee` INT NOT NULL ,
  `p_register_doc_id` INT NOT NULL ,
  `p_training_wish` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`p_training_id`) ,
  CONSTRAINT `FK_p_training_doc`
    FOREIGN KEY (`p_register_doc_id` )
    REFERENCES `ad_imp`.`upfiles` (`upfile_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_o_training_user`
    FOREIGN KEY (`p_training_user_id` )
    REFERENCES `ad_imp`.`user` (`user_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;
CREATE INDEX `FK_p_training_doc` ON `personal_training` (`p_register_doc_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_o_training_user` ON `personal_training` (`p_training_user_id` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `firewall_content_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `firewall_content_type` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `firewall_content_type` (
  `f_c_type_id` INT NOT NULL AUTO_INCREMENT ,
  `f_c_type_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`f_c_type_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `firewall`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `firewall` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `firewall` (
  `firewall_id` INT NOT NULL AUTO_INCREMENT ,
  `f_user_id` INT NOT NULL ,
  `f_type_id` INT NOT NULL ,
  `f_content` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `f_date` INT NOT NULL ,
  `f_refer_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `f_rules` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`firewall_id`) ,
  CONSTRAINT `FK_firewall_content_type`
    FOREIGN KEY (`f_type_id` )
    REFERENCES `ad_imp`.`firewall_content_type` (`f_c_type_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_firewall_user`
    FOREIGN KEY (`f_user_id` )
    REFERENCES `ad_imp`.`user` (`user_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;
CREATE INDEX `FK_firewall_content_type` ON `firewall` (`f_type_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_firewall_user` ON `firewall` (`f_user_id` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `firewall_rule`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `firewall_rule` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `firewall_rule` (
  `f_rule_id` INT NOT NULL AUTO_INCREMENT ,
  `rule_doc_id` INT NOT NULL ,
  PRIMARY KEY (`f_rule_id`) ,
  CONSTRAINT `FK_firewall_rule_doc`
    FOREIGN KEY (`rule_doc_id` )
    REFERENCES `ad_imp`.`upfiles` (`upfile_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;
CREATE INDEX `FK_firewall_rule_doc` ON `firewall_rule` (`rule_doc_id` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `standard`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `standard` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `standard` (
  `standard_id` INT NOT NULL AUTO_INCREMENT ,
  `s_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `s_target` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `s_creator` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `s_auditor` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `s_approver` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `s_audit_date` INT NOT NULL ,
  `s_publish_date` INT NOT NULL ,
  `s_doc_version` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `s_doc_id` INT NOT NULL ,
  PRIMARY KEY (`standard_id`) ,
  CONSTRAINT `FK_std_doc`
    FOREIGN KEY (`standard_id` )
    REFERENCES `ad_imp`.`upfiles` (`upfile_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;
CREATE INDEX `FK_std_doc` ON `standard` (`standard_id` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `info_source`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `info_source` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `info_source` (
  `info_source_id` INT NOT NULL AUTO_INCREMENT ,
  `info_s_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`info_source_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `info_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `info_type` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `info_type` (
  `info_type_id` INT NOT NULL AUTO_INCREMENT ,
  `info_type_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  PRIMARY KEY (`info_type_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `infomation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `infomation` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `infomation` (
  `info_id` INT NOT NULL AUTO_INCREMENT ,
  `info_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `info_source_id` INT NOT NULL ,
  `info_type_id` INT NOT NULL ,
  `info_user_id` INT NOT NULL ,
  `info_date` INT NOT NULL ,
  `info_abstract` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `info_keywords` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `info_doc_id` INT NOT NULL ,
  PRIMARY KEY (`info_id`) ,
  CONSTRAINT `FK_info_source`
    FOREIGN KEY (`info_source_id` )
    REFERENCES `ad_imp`.`info_source` (`info_source_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_info_type`
    FOREIGN KEY (`info_type_id` )
    REFERENCES `ad_imp`.`info_type` (`info_type_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_info_user`
    FOREIGN KEY (`info_user_id` )
    REFERENCES `ad_imp`.`user` (`user_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_info_doc`
    FOREIGN KEY (`info_doc_id` )
    REFERENCES `ad_imp`.`upfiles` (`upfile_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;
CREATE INDEX `FK_info_source` ON `infomation` (`info_source_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_info_type` ON `infomation` (`info_type_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_info_user` ON `infomation` (`info_user_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_info_doc` ON `infomation` (`info_doc_id` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Placeholder table for view `firewall_rule_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `firewall_rule_view` (`id` INT);
SHOW WARNINGS;

-- -----------------------------------------------------
-- View `firewall_rule_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `firewall_rule_view` ;
SHOW WARNINGS;
DROP TABLE IF EXISTS `firewall_rule_view`;
SHOW WARNINGS;
CREATE  OR REPLACE VIEW `ad_imp`.`firewall_rule_view` AS
select upfiles.upfile_name, upfiles.upfile_sysname,firewall_rule.f_rule_id 
from firewall_rule, upfiles
where firewall_rule.rule_doc_id= upfiles.upfile_id;
SHOW WARNINGS;

-- -----------------------------------------------------
-- Data for table `department`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `department` (`depart_id`, `depart_name`) VALUES (1, '办公室');
INSERT INTO `department` (`depart_id`, `depart_name`) VALUES (2, '方案论证组');
INSERT INTO `department` (`depart_id`, `depart_name`) VALUES (3, '航天及地面组');
INSERT INTO `department` (`depart_id`, `depart_name`) VALUES (4, '航空组');
INSERT INTO `department` (`depart_id`, `depart_name`) VALUES (5, '测试组');

COMMIT;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
