SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

SHOW WARNINGS;

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
-- Table `pbc_template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pbc_template` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `pbc_template` (
  `pbc_template_id` INT NOT NULL AUTO_INCREMENT ,
  `pbc_template_name` VARCHAR(45) NOT NULL ,
  `pbc_template_desc` VARCHAR(45) NULL ,
  PRIMARY KEY (`pbc_template_id`) )
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `pbc_user_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pbc_user_role` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `pbc_user_role` (
  `pbc_user_role_id` INT NOT NULL AUTO_INCREMENT ,
  `pbc_role_name` VARCHAR(45) NOT NULL ,
  `pbc_role_desc` VARCHAR(45) NULL ,
  PRIMARY KEY (`pbc_user_role_id`) )
ENGINE = InnoDB;

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
  `user_active` INT NOT NULL DEFAULT 1 ,
  `user_pbc_template_id` INT NOT NULL ,
  `user_pbc_role_id` INT NOT NULL ,
  PRIMARY KEY (`user_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;
CREATE INDEX `FK_department` ON `user` (`user_depart_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_pbc_template` ON `user` (`user_pbc_template_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_user_role` ON `user` (`user_pbc_role_id` ASC) ;

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
-- Table `project`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `project` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `project` (
  `project_id` INT NOT NULL AUTO_INCREMENT ,
  `project_name` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `project_no` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `project_creator_id` INT NOT NULL ,
  `project_create_date` INT NOT NULL ,
  PRIMARY KEY (`project_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;
CREATE INDEX `FK_project_user` ON `project` (`project_creator_id` ASC) ;

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
  PRIMARY KEY (`trip_id`) )
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
CREATE INDEX `FK_trip_project` ON `trip` (`trip_project_id` ASC) ;

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
  PRIMARY KEY (`tplan_id`) )
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
  PRIMARY KEY (`training_id`) )
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
  PRIMARY KEY (`p_training_id`) )
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
  PRIMARY KEY (`firewall_id`) )
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
  PRIMARY KEY (`f_rule_id`) )
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
  PRIMARY KEY (`standard_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

SHOW WARNINGS;
CREATE INDEX `FK_std_doc` ON `standard` (`s_doc_id` ASC) ;

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
  PRIMARY KEY (`info_id`) )
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
-- Table `pbc_biz_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pbc_biz_type` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `pbc_biz_type` (
  `pbc_biz_type_id` INT NOT NULL AUTO_INCREMENT ,
  `pbc_biz_type_name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`pbc_biz_type_id`) )
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `pbc`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pbc` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `pbc` (
  `pbc_id` INT NOT NULL AUTO_INCREMENT ,
  `pbc_user_id` INT NOT NULL ,
  `pbc_time` INT NOT NULL ,
  `pbc_reward` INT NULL ,
  `pbc_grade` INT NULL ,
  `pbc_status` VARCHAR(45) NOT NULL ,
  `pbc_change_time` INT NOT NULL ,
  `pbc_change_by` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`pbc_id`) )
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `FK_pbc_user` ON `pbc` (`pbc_user_id` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `pbc_data`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pbc_data` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `pbc_data` (
  `pbc_data_id` INT NOT NULL AUTO_INCREMENT ,
  `pbc_biz_type_id` INT NOT NULL ,
  `pbc_active_type` VARCHAR(45) NULL ,
  `pbc_active` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL ,
  `pbc_end_tag` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL ,
  `pbc_planned_end_date` INT NULL ,
  `pbc_refer_task` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL ,
  `pbc_weights` INT NULL ,
  `pbc_evaluator` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL ,
  `pbc_rule` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL ,
  `pbc_grade_self` INT NULL ,
  `pbc_grade` INT NULL ,
  `pbc_comment` VARCHAR(45) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL ,
  `pbc_id` INT NOT NULL ,
  PRIMARY KEY (`pbc_data_id`) )
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `FK_pbc_biz_type` ON `pbc_data` (`pbc_biz_type_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_pbc_data_pbc` ON `pbc_data` (`pbc_id` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `pbc_active_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pbc_active_type` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `pbc_active_type` (
  `pbc_active_type_id` INT NOT NULL AUTO_INCREMENT ,
  `pbc_active_name` VARCHAR(45) NOT NULL ,
  `pbc_biz_type_id` INT NOT NULL ,
  PRIMARY KEY (`pbc_active_type_id`) )
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `FK_active_type_biz_type` ON `pbc_active_type` (`pbc_biz_type_id` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `pbc_temp_biz`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pbc_temp_biz` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `pbc_temp_biz` (
  `pbc_temp_biz_id` INT NOT NULL AUTO_INCREMENT ,
  `pbc_biz_type_id` INT NOT NULL ,
  `pbc_template_id` INT NOT NULL ,
  PRIMARY KEY (`pbc_temp_biz_id`) )
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `FK_pbc_template` ON `pbc_temp_biz` (`pbc_template_id` ASC) ;

SHOW WARNINGS;
CREATE INDEX `FK_pbc_biz_type` ON `pbc_temp_biz` (`pbc_biz_type_id` ASC) ;

SHOW WARNINGS;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

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

-- -----------------------------------------------------
-- Data for table `pbc_template`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `pbc_template` (`pbc_template_id`, `pbc_template_name`, `pbc_template_desc`) VALUES (1, '技术人员', '技术人员的模板，大多数人的模板');
INSERT INTO `pbc_template` (`pbc_template_id`, `pbc_template_name`, `pbc_template_desc`) VALUES (2, '部门主任', '部门主任的模板');
INSERT INTO `pbc_template` (`pbc_template_id`, `pbc_template_name`, `pbc_template_desc`) VALUES (3, '计划及行政助理', '计划及行政助理');

COMMIT;

-- -----------------------------------------------------
-- Data for table `pbc_user_role`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `pbc_user_role` (`pbc_user_role_id`, `pbc_role_name`, `pbc_role_desc`) VALUES (1, '管理人员', '通常为办公室的用户');
INSERT INTO `pbc_user_role` (`pbc_user_role_id`, `pbc_role_name`, `pbc_role_desc`) VALUES (2, '组长', '每个组通常会有两个组长，组长负责组员');
INSERT INTO `pbc_user_role` (`pbc_user_role_id`, `pbc_role_name`, `pbc_role_desc`) VALUES (3, '组员', '一般用户');
INSERT INTO `pbc_user_role` (`pbc_user_role_id`, `pbc_role_name`, `pbc_role_desc`) VALUES (4, '超级管理员', '主任用户，不需填写pbc，但有权限为其他人打分');

COMMIT;

-- -----------------------------------------------------
-- Data for table `trip_type`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `trip_type` (`trip_type_id`, `trip_type_name`) VALUES (1, '会议');
INSERT INTO `trip_type` (`trip_type_id`, `trip_type_name`) VALUES (2, '外场');
INSERT INTO `trip_type` (`trip_type_id`, `trip_type_name`) VALUES (3, '调研');
INSERT INTO `trip_type` (`trip_type_id`, `trip_type_name`) VALUES (4, '协调');
INSERT INTO `trip_type` (`trip_type_id`, `trip_type_name`) VALUES (5, '售后服务');
INSERT INTO `trip_type` (`trip_type_id`, `trip_type_name`) VALUES (6, '培训');
INSERT INTO `trip_type` (`trip_type_id`, `trip_type_name`) VALUES (7, '其它');

COMMIT;

-- -----------------------------------------------------
-- Data for table `firewall_content_type`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `firewall_content_type` (`f_c_type_id`, `f_c_type_name`) VALUES (1, '计划');
INSERT INTO `firewall_content_type` (`f_c_type_id`, `f_c_type_name`) VALUES (2, '质量');
INSERT INTO `firewall_content_type` (`f_c_type_id`, `f_c_type_name`) VALUES (3, '保密');
INSERT INTO `firewall_content_type` (`f_c_type_id`, `f_c_type_name`) VALUES (4, '安全');
INSERT INTO `firewall_content_type` (`f_c_type_id`, `f_c_type_name`) VALUES (5, '组织纪律');
INSERT INTO `firewall_content_type` (`f_c_type_id`, `f_c_type_name`) VALUES (6, '6S');
INSERT INTO `firewall_content_type` (`f_c_type_id`, `f_c_type_name`) VALUES (7, '其它');

COMMIT;

-- -----------------------------------------------------
-- Data for table `pbc_biz_type`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (1, '项目开发任务');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (2, '联试保障任务');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (3, '文档编制、整理');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (4, '评审');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (5, '学习和培训');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (6, '其它');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (7, '防火墙');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (8, '计划管理');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (9, '综合管理及决策支持');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (10, '资源管理');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (11, '成本管理');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (12, '所办公会及各委员会要求');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (13, '管理项目');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (14, '目标制定与任务分解');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (15, '监督与管理');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (16, '组织与流程建设');
INSERT INTO `pbc_biz_type` (`pbc_biz_type_id`, `pbc_biz_type_name`) VALUES (17, '团队建设');

COMMIT;

-- -----------------------------------------------------
-- Data for table `pbc_active_type`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (1, '方案论证阶段', 1);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (2, '详细设计阶段', 1);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (3, '测试、测试移交阶段', 1);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (4, '计划制定', 8);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (5, '计划监控与预警', 8);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (6, '计划变更控制', 8);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (7, '外部计划协调', 9);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (8, '计划考核沟通', 9);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (9, '资源协调、统筹', 10);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (10, '项目排序', 10);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (11, '经验数据库', 10);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (12, '预算', 11);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (13, '所级（机制改革）', 13);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (14, '部门', 13);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (15, '预算制定', 15);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (16, '成本控制', 15);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (17, '经营分析及预警监控', 15);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (18, '资源配置', 15);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (19, '风险管理', 15);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (20, '岗位分析及组织建设', 16);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (21, '流程、制度制定及文化', 16);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (22, '企业文化建设', 16);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (23, '人才招聘', 17);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (24, '人才培养', 17);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (25, '绩效管理', 17);
INSERT INTO `pbc_active_type` (`pbc_active_type_id`, `pbc_active_name`, `pbc_biz_type_id`) VALUES (26, '任职资格管理', 17);

COMMIT;

-- -----------------------------------------------------
-- Data for table `pbc_temp_biz`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (1, 1, 1);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (2, 2, 1);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (3, 3, 1);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (4, 4, 1);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (5, 5, 1);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (6, 6, 1);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (7, 7, 1);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (8, 12, 2);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (9, 13, 2);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (10, 14, 2);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (11, 15, 2);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (12, 16, 2);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (13, 17, 2);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (14, 7, 2);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (15, 8, 3);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (16, 9, 3);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (17, 10, 3);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (18, 11, 3);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (19, 6, 3);
INSERT INTO `pbc_temp_biz` (`pbc_temp_biz_id`, `pbc_biz_type_id`, `pbc_template_id`) VALUES (20, 7, 3);

COMMIT;
