=============================================================================================================================
Make the database Empty 

set foreign_key_checks=0;
truncate coe_absent_entry;
truncate coe_additional_credits;
truncate coe_bat_deg_reg;
truncate coe_batch;
truncate coe_degree;
truncate coe_exam_timetable;
truncate coe_hall_allocate;
truncate coe_hall_master;
truncate coe_login_details;
truncate coe_mark_entry;
truncate coe_mark_entry_master;
truncate coe_nominal;
truncate coe_programme;
truncate coe_regulation;
truncate coe_stu_address;
truncate coe_stu_guardian;
truncate coe_student;
truncate coe_student_mapping;
truncate coe_subjects;
truncate coe_subjects_mapping;
truncate coe_dummy_number;
truncate coe_revaluation;
truncate coe_store_dummy_mapping;
truncate coe_student_category_details;
truncate coe_mandatory_subjects;
truncate coe_elective_waiver;
truncate coe_mandatory_stu_marks;
truncate coe_mandatory_subcat_subjects;
truncate coe_practical_entry;

update coe_configuration set updated_at="0000-00-00 00:00:00";
set foreign_key_checks=1;

select * from coe_absent_entry;
select * from  coe_bat_deg_reg;
select * from  coe_batch;
select * from  coe_degree;
select * from  coe_exam_timetable;
select * from  coe_hall_allocate;
select * from  coe_hall_master;
select * from  coe_login_details;
select * from  coe_mark_entry;
select * from  coe_mark_entry_master;
select * from  coe_nominal;
select * from  coe_programme;
select * from  coe_regulation;
select * from  coe_stu_address;
select * from  coe_stu_guardian;
select * from  coe_student;
select * from  coe_student_mapping;
select * from  coe_subjects;
select * from  coe_subjects_mapping;



DATABASE COE;

Tables for Users & Roles
-------------------
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




=============================================================================================================================
Table for Database Cache
---------

CREATE TABLE `cache` (
  `id` char(128) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

=============================================================================================================================
Table for Degree

CREATE TABLE `coe_degree` (
  `degree_id` INT NOT NULL AUTO_INCREMENT,
  `degree_code` VARCHAR(50) NOT NULL,
  `degree_name` VARCHAR(255) NOT NULL,
  `degree_type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`degree_id`),
  UNIQUE INDEX `unique` (`degree_code` ASC, `degree_name` ASC, `degree_type` ASC));

=============================================================================================================================

Table Design for Configuration

CREATE TABLE `coe_configuration` (
  `config_id` INT NOT NULL AUTO_INCREMENT,
  `config_name` VARCHAR(255) NOT NULL,
  `config_value` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` VARCHAR(45) NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  PRIMARY KEY (`config_id`),
  INDEX `fk_coe_configuration_1_idx` (`created_by` ASC),
  INDEX `unique_name` (`config_name` ASC, `config_value` ASC),
  INDEX `fk_coe_configuration_2_idx` (`updated_by` ASC),
  CONSTRAINT `fk_coe_configuration_1`
    FOREIGN KEY (`created_by`)
    REFERENCES `coe_test_int`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_configuration_2`
    FOREIGN KEY (`updated_by`)
    REFERENCES `coe_test_int`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

=============================================================================================================================
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.batch.locking.start', '01-06', 'Batch Locking Start', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.batch.locking.end', '01-07', 'Batch Locking End', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.absent.name', 'Absentee', 'Absentee', '1', '1');
UPDATE `coe_configuration` SET `config_value`='01-06 TO 01-07' WHERE `coe_config_id`='11';
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.absent.locking.period', '15', 'Absent Locking', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.fees.locking.period', '20', 'Fees Locking', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_at`, `created_by`, `updated_by`) VALUES ('coe.additional.absent.locking.period', '15', 'Additional Absent Locking', '', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.galley.name', 'Galley', 'Galley', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.additional.name', 'Additional', 'Additional Credits', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_at`, `created_by`, `updated_by`) VALUES ('coe.additional.absent.locking.period', '15', 'Additional Absentee Locking', '', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.report.name', 'Reports', 'Reports', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_at`, `created_by`, `updated_by`) VALUES ('coe.nominal.name', 'Nominal', 'Students Nominal', '', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.nominal.enable.status', '0', 'Nominal Status', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.exam.name', 'Exam', 'Exam', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.exam.bisem.name', 'Bisem', 'Bisem', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.exam.trisem.name', 'Trisem', 'Trisem', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.exam.session.name', 'Session', 'Session', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.exam.term.name', 'Term', 'Term', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.galley.hall.type', 'Hall Type', 'Hall Type', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('42', 'coe.absent.type.name', 'Absent Types', 'Absent Types', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_by`, `updated_by`) VALUES ('coe.absent.status', 'Absent Status', 'Absent Status', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES ('coe.galley.hall.column.size', '6', 'Galley Column Size', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '1', '1');
INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES ('coe.dummy.number.name', 'Dummy Numbers', 'Dummy Numbers', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '1', '1');


=============================================================================================================================

Table Design for Student

CREATE TABLE `coe_student` (
  `coe_student_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `register_number` VARCHAR(45) NOT NULL,
  `gender` VARCHAR(45) NOT NULL,
  `dob` DATE NOT NULL,
  `religion` VARCHAR(45) NOT NULL,
  `nationality` VARCHAR(45) NOT NULL,
  `caste` VARCHAR(45) NOT NULL,
  `sub_caste` VARCHAR(45) NULL,
  `bloodgroup` VARCHAR(45) NOT NULL,
  `email_id` VARCHAR(255) NULL,
  `admission_year` YEAR NOT NULL,
  `admission_date` DATE NOT NULL,
  `mobile_no` VARCHAR(45) NULL,
  `status` VARCHAR(45) NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`coe_student_id`),
  UNIQUE INDEX `register_number_UNIQUE` (`register_number` ASC));

=============================================================================================================================

Table Design for Address

CREATE TABLE `coe_stu_address` (
  `coe_stu_address_id` INT NOT NULL AUTO_INCREMENT,
  `stu_address_id` INT NOT NULL,
  `current_address` TEXT NOT NULL,
  `current_city` VARCHAR(45) NULL,
  `current_state` VARCHAR(45) NULL,
  `current_country` VARCHAR(45) NOT NULL,
  `current_pincode` VARCHAR(6) NULL,
  `permanant_address` TEXT NULL,
  `permanant_state` VARCHAR(45) NULL,
  `permanant_country` VARCHAR(45) NULL,
  `permanant_pincode` VARCHAR(45) NULL,
  PRIMARY KEY (`coe_stu_address_id`),
  INDEX `fk_coe_stu_address_1_idx` (`stu_address_id` ASC),
  CONSTRAINT `fk_coe_stu_address_1`
    FOREIGN KEY (`stu_address_id`)
    REFERENCES `coe_student` (`coe_student_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

=============================================================================================================================

Table Design for Guardian

CREATE TABLE `coe_guardian` (
  `coe_guardian_id` INT NOT NULL AUTO_INCREMENT,
  `stu_guardian_id` INT NOT NULL,
  `guardian_name` VARCHAR(45) NOT NULL,
  `guardian_relation` VARCHAR(45) NOT NULL,
  `guardian_mobile_no` VARCHAR(45) NOT NULL,
  `guardian_address` VARCHAR(45) NOT NULL,
  `guardian_email` VARCHAR(45) NULL,
  `guardian_occupation` VARCHAR(45) NULL,
  PRIMARY KEY (`coe_guardian_id`),
  INDEX `fk_guardian_1_idx` (`stu_guardian_id` ASC),
  CONSTRAINT `fk_guardian_1`
    FOREIGN KEY (`stu_guardian_id`)
    REFERENCES `coe_student` (`coe_student_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

=============================================================================================================================

Table Design for Student Mapping

CREATE TABLE `coe_student_mapping` (
  `coe_student_mapping_id` INT NOT NULL AUTO_INCREMENT,
  `student_rel_id` INT NOT NULL,
  `course_batch_mapping_id` INT NOT NULL,
  `address_id` INT NOT NULL,
  `section_name` VARCHAR(45) NOT NULL,
  `status_category_type_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  PRIMARY KEY (`coe_student_mapping_id`),
  INDEX `fk_coe_student_mapping_1_idx` (`created_by` ASC),
  INDEX `fk_coe_student_mapping_2_idx` (`updated_by` ASC),
  INDEX `fk_coe_student_mapping_3_idx` (`student_rel_id` ASC),
  INDEX `fk_coe_student_mapping_4_idx` (`course_batch_mapping_id` ASC),
  INDEX `fk_coe_student_mapping_5_idx` (`address_id` ASC),
  INDEX `fk_coe_student_mapping_6_idx` (`status_category_type_id` ASC),
  CONSTRAINT `fk_coe_student_mapping_1`
    FOREIGN KEY (`created_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_student_mapping_2`
    FOREIGN KEY (`updated_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_student_mapping_3`
    FOREIGN KEY (`student_rel_id`)
    REFERENCES `coe_student` (`coe_student_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_student_mapping_4`
    FOREIGN KEY (`course_batch_mapping_id`)
    REFERENCES `coe_bat_deg_reg` (`coe_mapping_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_student_mapping_5`
    FOREIGN KEY (`address_id`)
    REFERENCES `coe_stu_address` (`coe_stu_address_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_student_mapping_6`
    FOREIGN KEY (`status_category_type_id`)
    REFERENCES `coe_category_type` (`coe_category_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

=============================================================================================================================

Table Design for Hall Master


CREATE TABLE `coe_hall_master` (
  `coe_hall_master_id` INT NOT NULL AUTO_INCREMENT,
  `hall_name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(45) NOT NULL,
  `hall_type_id` INT NOT NULL,
  `created_by` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_by` INT NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`coe_hall_master_id`),
  INDEX `fk_coe_hall_master_1_idx` (`hall_type_id` ASC),
  INDEX `fk_coe_hall_master_2_idx` (`created_by` ASC),
  INDEX `fk_coe_hall_master_3_idx` (`updated_by` ASC),
  CONSTRAINT `fk_coe_hall_master_1`
    FOREIGN KEY (`hall_type_id`)
    REFERENCES `coe_category_type` (`coe_category_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_hall_master_2`
    FOREIGN KEY (`created_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_hall_master_3`
    FOREIGN KEY (`updated_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

ALTER TABLE `coe_hall_master` 
ADD UNIQUE INDEX `uq_coe_hall_master_4_idx` (`hall_name` ASC, `description` ASC);

=============================================================================================================================

Table Design for Hall Master Allocate

CREATE TABLE `coe_hall_allocate` (
  `coe_hall_allocate_id` INT NOT NULL AUTO_INCREMENT,
  `hall_master_id` INT NOT NULL,
  `exam_timetable_id` INT NOT NULL,
  `year` INT NOT NULL,
  `month` VARCHAR(45) NOT NULL,
  `register_number` VARCHAR(45) NOT NULL,
  `row` INT NOT NULL,
  `row_column` INT NOT NULL,
  `seat_no` INT NOT NULL,
  `created_by` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_by` INT NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`coe_hall_allocate_id`),
  INDEX `fk_coe_hall_allocate_1_idx` (`hall_master_id` ASC),
  INDEX `fk_coe_hall_allocate_2_idx` (`exam_timetable_id` ASC),
  INDEX `fk_coe_hall_allocate_3_idx` (`created_by` ASC),
  INDEX `fk_coe_hall_allocate_4_idx` (`updated_by` ASC),
  UNIQUE INDEX `index6` (`register_number` ASC, `exam_timetable_id` ASC),
  CONSTRAINT `fk_coe_hall_allocate_1`
    FOREIGN KEY (`hall_master_id`)
    REFERENCES `coe_hall_master` (`coe_hall_master_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_hall_allocate_2`
    FOREIGN KEY (`exam_timetable_id`)
    REFERENCES `coe_exam_timetable` (`coe_exam_timetable_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_hall_allocate_3`
    FOREIGN KEY (`created_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_hall_allocate_4`
    FOREIGN KEY (`updated_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

=============================================================================================================================

Table Design for Exam Time Table

 CREATE TABLE `coe_exam_timetable` (
  `coe_exam_timetable_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_mapping_id` int(11) NOT NULL,
  `exam_year` int(11) NOT NULL,
  `exam_month` int(11) NOT NULL,
  `exam_type` int(11) NOT NULL,
  `exam_term` int(11) NOT NULL,
  `exam_date` date NOT NULL,
  `exam_session` int(11) NOT NULL,
  `qp_code` varchar(45) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`coe_exam_timetable_id`),
  UNIQUE KEY `index3` (`exam_date`,`exam_session`,`qp_code`,`subject_mapping_id`),
  KEY `fk_coe_exam_timetable_1_idx` (`subject_mapping_id`),
  KEY `fk_coe_exam_timetable_2_idx` (`exam_type`),
  KEY `fk_coe_exam_timetable_2_idx1` (`exam_month`),
  KEY `fk_coe_exam_timetable_4_idx` (`exam_term`),
  KEY `fk_coe_exam_timetable_5_idx` (`exam_session`),
  CONSTRAINT `fk_coe_exam_timetable_3` FOREIGN KEY (`exam_type`) REFERENCES `coe_category_type` (`coe_category_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_exam_timetable_4` FOREIGN KEY (`exam_term`) REFERENCES `coe_category_type` (`coe_category_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_exam_timetable_5` FOREIGN KEY (`exam_session`) REFERENCES `coe_category_type` (`coe_category_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_exam_timetable_1` FOREIGN KEY (`subject_mapping_id`) REFERENCES `coe_subjects_mapping` (`coe_subjects_mapping_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_exam_timetable_2` FOREIGN KEY (`exam_month`) REFERENCES `coe_category_type` (`coe_category_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);


=============================================================================================================================

Table Design for Absent Entry

CREATE TABLE `coe_absent_entry` (
  `coe_absent_entry_id` INT NOT NULL AUTO_INCREMENT,
  `absent_student_reg` INT NOT NULL,
  `exam_type` INT NOT NULL,
  `exam_date` VARCHAR(45) NULL,
  `exam_session` VARCHAR(45) NULL,
  `exam_subject_id` INT NOT NULL,
  `exam_absent_status` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `created_by` INT NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `updated_by` INT NOT NULL,
  PRIMARY KEY (`coe_absent_entry_id`),
  INDEX `fk_coe_absent_entry_1_idx` (`absent_student_reg` ASC),
  INDEX `fk_coe_absent_entry_2_idx` (`exam_type` ASC),
  INDEX `fk_coe_absent_entry_3_idx` (`exam_subject_id` ASC),
  INDEX `fk_coe_absent_entry_4_idx` (`exam_absent_status` ASC),
  INDEX `fk_coe_absent_entry_5_idx` (`created_by` ASC),
  INDEX `fk_coe_absent_entry_6_idx` (`updated_by` ASC),
  CONSTRAINT `fk_coe_absent_entry_1`
    FOREIGN KEY (`absent_student_reg`)
    REFERENCES `coe_student_mapping` (`coe_student_mapping_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_absent_entry_2`
    FOREIGN KEY (`exam_type`)
    REFERENCES `coe_category_type` (`coe_category_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_absent_entry_3`
    FOREIGN KEY (`exam_subject_id`)
    REFERENCES `coe_subjects_mapping` (`coe_subjects_mapping_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_absent_entry_4`
    FOREIGN KEY (`exam_absent_status`)
    REFERENCES `coe_category_type` (`coe_category_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_absent_entry_5`
    FOREIGN KEY (`created_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_absent_entry_6`
    FOREIGN KEY (`updated_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

ALTER TABLE `coe_absent_entry` 
ADD COLUMN `absent_term` INT NOT NULL AFTER `exam_type`,
ADD INDEX `fk_coe_absent_entry_7_idx` (`absent_term` ASC);
ALTER TABLE `coe_absent_entry` 
ADD CONSTRAINT `fk_coe_absent_entry_7`
  FOREIGN KEY (`absent_term`)
  REFERENCES `coe_category_type` (`coe_category_type_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `coe_absent_entry` 
ADD UNIQUE INDEX `unique_ab_1` (`absent_student_reg` ASC, `exam_type` ASC, `exam_subject_id` ASC, `absent_term` ASC);

=============================================================================================================================

Table Design for INTernal Mark Entry

CREATE TABLE `coe_mark_entry` (
  `coe_mark_entry_id` INT NOT NULL AUTO_INCREMENT,
  `student_map_id` INT NOT NULL,
  `subject_map_id` INT NOT NULL,
  `category_type_id` INT NOT NULL,
  `category_type_id_marks` INT NOT NULL,
  `year` INT NULL,
  `month` INT NULL,
  `term` INT NULL,
  `mark_type` INT NOT NULL,
  `status_id` INT NOT NULL,
  `created_by` INT NOT NULL,
  `created_at` DATETIME NULL,
  `updated_by` INT NOT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`coe_mark_entry_id`),
  INDEX `fk_coe_mark_entry_1_idx` (`student_map_id` ASC),
  INDEX `fk_coe_mark_entry_2_idx` (`subject_map_id` ASC),
  INDEX `fk_coe_mark_entry_3_idx` (`category_type_id` ASC, `mark_type` ASC),
  UNIQUE INDEX `index5` (`student_map_id` ASC, `subject_map_id` ASC, `category_type_id` ASC),
  INDEX `fk_coe_mark_entry_4_idx` (`created_by` ASC),
  INDEX `fk_coe_mark_entry_5_idx` (`updated_by` ASC),
  CONSTRAINT `fk_coe_mark_entry_1`
    FOREIGN KEY (`student_map_id`)
    REFERENCES `coe_student_mapping` (`coe_student_mapping_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_mark_entry_2`
    FOREIGN KEY (`subject_map_id`)
    REFERENCES `coe_subjects_mapping` (`coe_subjects_mapping_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_mark_entry_3`
    FOREIGN KEY (`category_type_id` , `mark_type`)
    REFERENCES `coe_category_type` (`coe_category_type_id` , `coe_category_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_mark_entry_4`
    FOREIGN KEY (`created_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_mark_entry_5`
    FOREIGN KEY (`updated_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);



CREATE TABLE `login_details` (
  `login_detail_id` INT NOT NULL AUTO_INCREMENT,
  `login_user_id` INT NOT NULL,
  `login_at` DATETIME NOT NULL,
  `login_out` DATETIME NOT NULL,
  `login_ip_address` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`login_detail_id`),
  INDEX `fk_login_details_1_idx` (`login_user_id` ASC),
  CONSTRAINT `fk_login_details_1`
    FOREIGN KEY (`login_user_id`)
    REFERENCES `COLLEGEINSTALL_TEST`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

ALTER TABLE `coe_mark_entry_master` 
ADD COLUMN `withheld_remarks` VARCHAR(45) NULL AFTER `withheld`;

ALTER TABLE `coe_mark_entry_master` 
CHANGE COLUMN `withheld_remarks` `withheld_remarks` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE COLUMN `updated_at` `updated_at` VARCHAR(45) NOT NULL ;


CREATE TABLE `coe_additional_credits` (
  `coe_additional_credits_id` INT NOT NULL AUTO_INCREMENT,
  `exam_year` INT NOT NULL,
  `exam_month` INT NOT NULL,
  `student_map_id` INT NOT NULL,
  `subject_code` VARCHAR(45) NOT NULL,
  `subject_name` VARCHAR(45) NOT NULL,
  `credits` INT NOT NULL,
  `grade` VARCHAR(45) NOT NULL,
  `created_at` VARCHAR(45) NOT NULL,
  `created_by` INT NOT NULL,
  `updated_at` VARCHAR(45) NOT NULL,
  `updated_by` INT NOT NULL,
  PRIMARY KEY (`coe_additional_credits_id`),
  INDEX `fk_coe_additional_credits_1_idx` (`student_map_id` ASC),
  INDEX `fk_coe_additional_credits_2_idx` (`exam_month` ASC),
  INDEX `fk_coe_additional_credits_3_idx` (`created_by` ASC),
  INDEX `fk_coe_additional_credits_4_idx` (`updated_by` ASC),
  UNIQUE INDEX `additional_index6` (`student_map_id` ASC, `subject_code` ASC),
  CONSTRAINT `fk_coe_additional_credits_1`
    FOREIGN KEY (`student_map_id`)
    REFERENCES `coe_student_mapping` (`coe_student_mapping_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_additional_credits_2`
    FOREIGN KEY (`exam_month`)
    REFERENCES `coe_category_type` (`coe_category_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_additional_credits_3`
    FOREIGN KEY (`created_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coe_additional_credits_4`
    FOREIGN KEY (`updated_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

ALTER TABLE `coe_category_type` 
DROP INDEX `category_type` ,
ADD UNIQUE INDEX `category_type` (`category_type` ASC, `category_id` ASC);


CREATE TABLE coe_mandatory_subjects (
  coe_mandatory_subjects_id int(11) NOT NULL AUTO_INCREMENT,
  subject_code varchar(255) NOT NULL,
  subject_name varchar(255) NOT NULL,
  CIA_min int(11) NOT NULL,
  CIA_max int(11) NOT NULL,
  ESE_min int(11) NOT NULL,
  ESE_max int(11) NOT NULL,
  total_minimum_pass int(11) NOT NULL,
  credit_points int(11) NOT NULL,
  end_semester_exam_value_mark int(11) NOT NULL,
  created_by int(11) NOT NULL,
  created_at datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  updated_by int(11) NOT NULL,
  updated_at datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (coe_mandatory_subjects_id)
)
ENGINE = INNODB,
AUTO_INCREMENT = 4,
AVG_ROW_LENGTH = 5461,
CHARACTER SET latin1,
COLLATE latin1_swedish_ci,
COMMENT = 'This table is created only for Mandatory Courses';

ALTER TABLE coe_mandatory_subjects
ADD UNIQUE INDEX index9 (subject_code);

ALTER TABLE coe_mandatory_subjects
ADD INDEX user_created_idx (created_by);

ALTER TABLE coe_mandatory_subjects
ADD INDEX user_updated_idx (updated_by);

ALTER TABLE coe_mandatory_subjects
ADD UNIQUE INDEX UK_coe_mandatory_subjects_subj (subject_name);

ALTER TABLE coe_mandatory_subjects
ADD CONSTRAINT user_created FOREIGN KEY (created_by)
REFERENCES user (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE coe_mandatory_subjects
ADD CONSTRAINT user_updated FOREIGN KEY (updated_by)
REFERENCES user (id) ON DELETE NO ACTION ON UPDATE NO ACTION;


CREATE TABLE `coe_mandatory_subcat_subjects` (
  `coe_mandatory_subcat_subjects_id` INT NOT NULL AUTO_INCREMENT,
  `man_subject_id` INT NOT NULL,
  `coe_batch_id` INT NOT NULL,
  `batch_map_id` INT NOT NULL,
  `sub_cat_code` VARCHAR(45) NOT NULL,
  `sub_cat_name` VARCHAR(255) NOT NULL,
  `course_type_id` INT NOT NULL,
  `paper_type_id` INT NOT NULL,
  `subject_type_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `created_by` INT NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `updated_by` INT NOT NULL,
  PRIMARY KEY (`coe_mandatory_subcat_subjects_id`),
  INDEX `fk_1_idx` (`coe_batch_id` ASC),
  INDEX `fkcoe_batch_idMan_2_idx` (`man_subject_id` ASC),
  INDEX `fkcoe_batch_idMan_3_idx` (`batch_map_id` ASC),
  INDEX `fkcoe_batch_idMan_4_idx` (`course_type_id` ASC),
  INDEX `fkcoe_batch_idMan_5_idx` (`paper_type_id` ASC),
  INDEX `fkcoe_batch_idMan_6_idx` (`subject_type_id` ASC),
  UNIQUE INDEX `Man_unique_set` (`coe_batch_id` ASC, `batch_map_id` ASC, `man_subject_id` ASC, `sub_cat_code` ASC),
  CONSTRAINT `fkcoe_batch_idMan_1`
    FOREIGN KEY (`coe_batch_id`)
    REFERENCES `coe_batch` (`coe_batch_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkcoe_batch_idMan_2`
    FOREIGN KEY (`man_subject_id`)
    REFERENCES `coe_mandatory_subjects` (`coe_mandatory_subjects_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkcoe_batch_idMan_3`
    FOREIGN KEY (`batch_map_id`)
    REFERENCES `coe_bat_deg_reg` (`coe_bat_deg_reg_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkcoe_batch_idMan_4`
    FOREIGN KEY (`course_type_id`)
    REFERENCES `coe_category_type` (`coe_category_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkcoe_batch_idMan_5`
    FOREIGN KEY (`paper_type_id`)
    REFERENCES `coe_category_type` (`coe_category_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fkcoe_batch_idMan_6`
    FOREIGN KEY (`subject_type_id`)
    REFERENCES `coe_category_type` (`coe_category_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);



CREATE TABLE coe_mandatory_stu_marks (
  coe_mandatory_stu_marks_id int(11) NOT NULL AUTO_INCREMENT,
  student_map_id int(11) NOT NULL,
  subject_map_id int(11) NOT NULL,
  CIA int(11) NOT NULL,
  ESE int(11) NOT NULL,
  total int(11) NOT NULL,
  result varchar(255) NOT NULL,
  grade_point float NOT NULL,
  grade_name varchar(10) NOT NULL,
  year int(11) NOT NULL,
  month int(11) NOT NULL,
  term int(11) NOT NULL,
  mark_type int(11) NOT NULL,
  status_id int(11) NOT NULL,
  year_of_passing varchar(255) NOT NULL,
  attempt varchar(255) NOT NULL DEFAULT 1,
  withheld varchar(255) DEFAULT NULL,
  withheld_remarks varchar(255) DEFAULT NULL,
  withdraw varchar(255) DEFAULT NULL,
  fees_paid varchar(255) DEFAULT NULL,
  created_by int(11) NOT NULL,
  created_at datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  updated_by int(11) NOT NULL,
  updated_at datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (coe_mandatory_stu_marks_id)
)
ENGINE = INNODB,
COMMENT = 'Mandatory Course Marks';

ALTER TABLE coe_mandatory_stu_marks
ADD UNIQUE INDEX UK_coe_mandatory_stu_marks (student_map_id, subject_map_id, year, month, mark_type, term);

ALTER TABLE coe_mandatory_stu_marks
ADD CONSTRAINT FK_coe_mandatory_stu_marks_stu FOREIGN KEY (student_map_id)
REFERENCES coe_student_mapping (coe_student_mapping_id) ON DELETE NO ACTION;

ALTER TABLE coe_mandatory_stu_marks
ADD CONSTRAINT FK_coe_mandatory_stu_marks_sub FOREIGN KEY (subject_map_id)
REFERENCES coe_subjects_mapping (coe_subjects_mapping_id) ON DELETE NO ACTION;

ALTER TABLE coe_mandatory_stu_marks
ADD CONSTRAINT FK_coe_mandatory_stu_marks_mon FOREIGN KEY (month)
REFERENCES coe_category_type (coe_category_type_id) ON DELETE NO ACTION;

ALTER TABLE coe_mandatory_stu_marks
ADD CONSTRAINT FK_coe_mandatory_stu_marks_ter FOREIGN KEY (term)
REFERENCES coe_category_type (coe_category_type_id) ON DELETE NO ACTION;

ALTER TABLE coe_mandatory_stu_marks
ADD CONSTRAINT FK_coe_mandatory_stu_marks_cre FOREIGN KEY (created_by)
REFERENCES user (id) ON DELETE NO ACTION;

ALTER TABLE coe_mandatory_stu_marks
ADD CONSTRAINT FK_coe_mandatory_stu_marks_upd FOREIGN KEY (updated_by)
REFERENCES user (id) ON DELETE NO ACTION;

ALTER TABLE `coe_mandatory_stu_marks` 
DROP FOREIGN KEY `FK_coe_mandatory_stu_marks_sub`;
ALTER TABLE `coe_mandatory_stu_marks` 
DROP INDEX `FK_coe_mandatory_stu_marks_sub` ,
ADD INDEX `FK_coe_mandatory_stu_marks_sub_idx` (`subject_map_id` ASC);
ALTER TABLE `coe_mandatory_stu_marks` 
ADD CONSTRAINT `FK_coe_mandatory_stu_marks_sub`
  FOREIGN KEY (`subject_map_id`)
  REFERENCES `coe_mandatory_subcat_subjects` (`coe_mandatory_subcat_subjects_id`)
  ON DELETE NO ACTION;

ALTER TABLE coe_mandatory_stu_marks CHANGE COLUMN grade_point grade_point FLOAT DEFAULT NULL;

ALTER TABLE coe_mandatory_subcat_subjects 
  ADD COLUMN is_additional VARCHAR(255) DEFAULT 'NO';

ALTER TABLE coe_mandatory_subcat_subjects 
 MODIFY is_additional VARCHAR(255) DEFAULT 'NO' AFTER sub_cat_name;

 ALTER TABLE coe_mandatory_stu_marks 
  CHANGE COLUMN grade_point grade_point FLOAT NOT NULL;

  ALTER TABLE coe_mandatory_subjects
  DROP INDEX UK_coe_mandatory_subjects_subj,
  ADD COLUMN man_batch_id INT NOT NULL AFTER coe_mandatory_subjects_id;

ALTER TABLE coe_mandatory_subjects
  ADD UNIQUE INDEX batch_sub_name_uniq (man_batch_id, subject_name);

ALTER TABLE coe_mandatory_subjects
  ADD CONSTRAINT FK_coe_batch_coe_batch_id FOREIGN KEY (man_batch_id)
    REFERENCES coe_batch(coe_batch_id) ON DELETE RESTRICT ON UPDATE RESTRICT;

// Change the Regulation VALUES

ALTER TABLE `coe_bat_deg_reg` 
ADD COLUMN `regulation_year` INT NOT NULL DEFAULT 2016 AFTER `coe_batch_id`;

ALTER TABLE `coe_mandatory_stu_marks` 
ADD COLUMN `semester` INT NULL AFTER `status_id`;

ALTER TABLE `coe_mandatory_stu_marks` 
CHANGE COLUMN `semester` `semester` INT(11) NOT NULL ;

ALTER TABLE `coe_mandatory_subcat_subjects` 
ADD COLUMN `paper_no` INT NULL AFTER `subject_type_id`;

ALTER TABLE `coe_mandatory_subcat_subjects` 
ADD COLUMN `paper_no` INT NULL AFTER `subject_type_id`;

ALTER TABLE `coe_subjects_mapping` 
ADD COLUMN `paper_no` VARCHAR(45) NOT NULL AFTER `migration_status`;

ALTER TABLE `coe_subjects_mapping` 
CHANGE COLUMN `paper_no` `paper_no` INT NOT NULL DEFAULT 1 ;

update coe_subjects_mapping as A JOIN coe_subjects as B ON B.coe_subjects_id=A.subject_id set A.paper_no=B.paper_no where B.coe_subjects_id=A.subject_id

ALTER TABLE `coe_subjects` DROP COLUMN `paper_no`;

ALTER TABLE `coe_stu_guardian` 
CHANGE COLUMN `guardian_mobile_no` `guardian_mobile_no` VARCHAR(45) NOT NULL ;

ALTER TABLE `coe_stu_guardian` 
CHANGE COLUMN `guardian_income` `guardian_income` INT(11) NOT NULL ;

ALTER TABLE `coe_student` 
CHANGE COLUMN `aadhar_number` `aadhar_number` VARCHAR(20) NOT NULL ;

ALTER TABLE `coe_student` 
CHANGE COLUMN `aadhar_number` `aadhar_number` INT(12) NOT NULL ;

ALTER TABLE `coe_stu_guardian` 
CHANGE COLUMN `guardian_mobile_no` `guardian_mobile_no` INT(10) NOT NULL ;

=============================================================================
24-10-2018
=============================================================================

CREATE TABLE coe_practical_entry (
  coe_practical_entry_id int(11) NOT NULL AUTO_INCREMENT,
  student_map_id int(11) NOT NULL,
  subject_map_id int(11) NOT NULL,
  ESE int(11) NOT NULL,
  year int(11) NOT NULL,
  month int(11) NOT NULL,
  term int(11) NOT NULL,
  mark_type int(11) NOT NULL,
  created_at datetime NOT NULL,
  created_by int(11) NOT NULL,
  updated_at datetime NOT NULL,
  updated_by int(11) NOT NULL,
  PRIMARY KEY (coe_practical_entry_id)
)
ENGINE = INNODB,
COMMENT = 'This table is using only for Practical Entry';

ALTER TABLE coe_practical_entry
ADD CONSTRAINT FK_coe_practical_entry_student FOREIGN KEY (student_map_id)
REFERENCES coe_student_mapping (coe_student_mapping_id) ON DELETE NO ACTION;

ALTER TABLE coe_practical_entry
ADD CONSTRAINT FK_coe_practical_entry_subject FOREIGN KEY (subject_map_id)
REFERENCES coe_subjects_mapping (coe_subjects_mapping_id) ON DELETE NO ACTION;

ALTER TABLE coe_practical_entry
ADD CONSTRAINT FK_coe_practical_entry_month FOREIGN KEY (month)
REFERENCES coe_category_type (coe_category_type_id) ON DELETE NO ACTION;

ALTER TABLE coe_practical_entry
ADD CONSTRAINT FK_coe_practical_entry_mark_ty FOREIGN KEY (mark_type)
REFERENCES coe_category_type (coe_category_type_id) ON DELETE NO ACTION;

ALTER TABLE coe_practical_entry
ADD CONSTRAINT FK_coe_practical_entry_term FOREIGN KEY (term)
REFERENCES coe_category_type (coe_category_type_id) ON DELETE NO ACTION;

ALTER TABLE coe_practical_entry
ADD CONSTRAINT FK_coe_practical_entry_created FOREIGN KEY (created_by)
REFERENCES user (id) ON DELETE NO ACTION;

ALTER TABLE coe_practical_entry
ADD CONSTRAINT FK_coe_practical_entry_updated FOREIGN KEY (updated_by)
REFERENCES user (id) ON DELETE NO ACTION;


ALTER TABLE coe_practical_entry 
  ADD COLUMN out_of_100 INT(11) NOT NULL;

ALTER TABLE coe_practical_entry 
 MODIFY out_of_100 INT(11) NOT NULL AFTER subject_map_id;

ALTER TABLE coe_practical_entry 
  ADD UNIQUE INDEX UK_coe_practical_entry(student_map_id, subject_map_id, year, month, mark_type);

ALTER TABLE coe_practical_entry 
  ADD COLUMN approve_status VARCHAR(255) NOT NULL DEFAULT 'NO';

ALTER TABLE `coe_practical_entry` 
ADD COLUMN `examiner_name` VARCHAR(255) NOT NULL AFTER `mark_type`;

===============================================
27-10-2018
===============================================

ALTER TABLE `coe_subjects` CHANGE COLUMN `credit_points` `credit_points` FLOAT NOT NULL;
ALTER TABLE `coe_mark_entry_master` 
ADD COLUMN `is_updated` VARCHAR(45) NOT NULL DEFAULT 'NO' AFTER `withdraw`;

ALTER TABLE `coe_mark_entry` 
ADD COLUMN `is_updated` VARCHAR(45) NOT NULL DEFAULT 'NO' AFTER `attendance_remarks`;

==============================================
28-10-2018
==============================================

ALTER TABLE `coe_mandatory_subjects` 
ADD COLUMN `batch_mapping_id` INT NOT NULL AFTER `man_batch_id`,
DROP INDEX `batch_sub_name_uniq` ,
ADD UNIQUE INDEX `batch_sub_name_uniq` (`man_batch_id` ASC, `subject_name` ASC, `batch_mapping_id` ASC),
DROP INDEX `index9` ;

ALTER TABLE `coe_mandatory_subjects` 
ADD COLUMN `semester` INT NOT NULL AFTER `batch_mapping_id`,
DROP INDEX `batch_sub_name_uniq` ,
ADD UNIQUE INDEX `batch_sub_name_uniq` (`man_batch_id` ASC, `batch_mapping_id` ASC, `subject_code` ASC);

ALTER TABLE `coe_mandatory_subcat_subjects` 
ADD UNIQUE INDEX `index9` (`sub_cat_name` ASC, `batch_map_id` ASC, `coe_batch_id` ASC);


==============================================
02-10-2018
==============================================

ALTER TABLE `coe_practical_entry` 
CHANGE COLUMN `approve_status` `approve_status` VARCHAR(255) NOT NULL DEFAULT 'NO' AFTER `examiner_name`,
ADD COLUMN `chief_exam_name` VARCHAR(255) NOT NULL AFTER `approve_status`;

INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES ('coe.elective.waiver.count', '3', 'Elective Waiver', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '1', '1');

CREATE TABLE `coe_elective_waiver` (
  `coe_elective_waiver_id` int(10) NOT NULL AUTO_INCREMENT,
  `student_map_id` int(10) NOT NULL,
  `waiver_reason` varchar(1000) NOT NULL,
  `total_studied` int(11) NOT NULL COMMENT 'Match the count from configuration',
  `subject_codes` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `created_by` int(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(50) NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`coe_elective_waiver_id`),
  UNIQUE KEY `index6` (`student_map_id`),
  KEY `coe_elective_waiver_ibfk_1` (`created_by`),
  KEY `coe_elective_waiver_ibfk_2` (`updated_by`),
  KEY `coe_elective_waiver_ibfk_3` (`student_map_id`),
  KEY `coe_elective_waiver_ibfk_4_idx` (`month`),
  CONSTRAINT `coe_elective_waiver_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `coe_elective_waiver_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`),
  CONSTRAINT `coe_elective_waiver_ibfk_3` FOREIGN KEY (`student_map_id`) REFERENCES `coe_student_mapping` (`coe_student_mapping_id`),
  CONSTRAINT `coe_elective_waiver_ibfk_4` FOREIGN KEY (`month`) REFERENCES `coe_category_type` (`coe_category_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=399001 DEFAULT CHARSET=latin1;

ALTER TABLE `coe_elective_waiver` 
ADD COLUMN `removed_sub_map_id` INT NOT NULL AFTER `student_map_id`;

ALTER TABLE `coe_elective_waiver` 
ADD INDEX `coe_elective_waiver_ibfk_4_idx1` (`removed_sub_map_id` ASC);

ALTER TABLE `coe_elective_waiver` 
ADD CONSTRAINT `coe_elective_waiver_ibfk_5`
  FOREIGN KEY (`removed_sub_map_id`)
  REFERENCES `coe_subjects_mapping` (`coe_subjects_mapping_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

///////////////// SKCT UPDATE QUEIRES ///////////////////

INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('', '3', '24', '3', '15MC001', 'MANDATORY COURSE: BUSINESS COMMUNICATION', '0', '100', '0', '0', '50', '1', '100', '1', '0000-00-00 00:00:00', '11', '0000-00-00 00:00:00');
UPDATE `coe_mandatory_subjects` SET `batch_mapping_id`='23', `semester`='3' WHERE `coe_mandatory_subjects_id`='1';
INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('', '3', '25', '3', '15MC001', 'MANDATORY COURSE: BUSINESS COMMUNICATION', '0', '100', '0', '0', '50', '1', '100', '1', '0000-00-00 00:00:00', '11', '0000-00-00 00:00:00');
INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('', '3', '26', '3', '15MC001', 'MANDATORY COURSE: BUSINESS COMMUNICATION', '0', '100', '0', '0', '50', '1', '100', '1', '0000-00-00 00:00:00', '11', '0000-00-00 00:00:00');
INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('', '3', '27', '3', '15MC001', 'MANDATORY COURSE: BUSINESS COMMUNICATION', '0', '100', '0', '0', '50', '1', '100', '1', '0000-00-00 00:00:00', '11', '0000-00-00 00:00:00');
INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('', '3', '28', '3', '15MC001', 'MANDATORY COURSE: BUSINESS COMMUNICATION', '0', '100', '0', '0', '50', '1', '100', '1', '0000-00-00 00:00:00', '11', '0000-00-00 00:00:00');
INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('', '3', '29', '3', '15MC001', 'MANDATORY COURSE: BUSINESS COMMUNICATION', '0', '100', '0', '0', '50', '1', '100', '1', '0000-00-00 00:00:00', '11', '0000-00-00 00:00:00');

UPDATE `coe_mandatory_subcat_subjects` SET `man_subject_id`='13' WHERE `coe_mandatory_subcat_subjects_id`='2';
UPDATE `coe_mandatory_subcat_subjects` SET `man_subject_id`='14' WHERE `coe_mandatory_subcat_subjects_id`='3';
UPDATE `coe_mandatory_subcat_subjects` SET `man_subject_id`='15' WHERE `coe_mandatory_subcat_subjects_id`='4';
UPDATE `coe_mandatory_subcat_subjects` SET `man_subject_id`='16' WHERE `coe_mandatory_subcat_subjects_id`='5';
UPDATE `coe_mandatory_subcat_subjects` SET `man_subject_id`='17' WHERE `coe_mandatory_subcat_subjects_id`='6';
UPDATE `coe_mandatory_subcat_subjects` SET `man_subject_id`='18' WHERE `coe_mandatory_subcat_subjects_id`='7';
INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES (NULL, '2', '15', '3', '16MC001', 'MANDATORY COURSE: BUSINESS COMMUNICATION', '0', '100', '0', '0', '50', '1', '100', '11', '2018-10-15 15:45:16', '11', '2018-10-15 15:45:16');
INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES (NULL, '2', '16', '3', '16MC001', 'MANDATORY COURSE: BUSINESS COMMUNICATION', '0', '100', '0', '0', '50', '1', '100', '11', '2018-10-15 15:45:16', '11', '2018-10-15 15:45:16');

UPDATE `coe_mandatory_subcat_subjects` SET `man_subject_id`='19' WHERE `coe_mandatory_subcat_subjects_id`='106';
UPDATE `coe_mandatory_subcat_subjects` SET `man_subject_id`='20' WHERE `coe_mandatory_subcat_subjects_id`='107';

INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('', '2', '21', '4', '16MC001', 'MANDATORY COURSE: BUSINESS COMMUNICATION', '0', '100', '0', '0', '50', '1', '100', '11', '2018-10-15 15:45:16', '11', '2018-10-15 15:45:16');

UPDATE `coe_mandatory_subcat_subjects` SET `man_subject_id`='21' WHERE `coe_mandatory_subcat_subjects_id`='112';
INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('', '2', '19', '3', '16MC002', 'MANDATORY COURSE: LIFE SKILLS', '0', '100', '0', '0', '50', '1', '100', '11', '2018-10-15 15:45:40', '11', '2018-10-15 15:45:40');

UPDATE `coe_mandatory_subcat_subjects` SET `man_subject_id`='22' WHERE `coe_mandatory_subcat_subjects_id`='119';
INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES (NULL, '2', '21', '3', '16MC002', 'MANDATORY COURSE: LIFE SKILLS', '0', '100', '0', '0', '50', '1', '100', '11', '2018-10-15 15:45:40', '11', '2018-10-15 15:45:40');

UPDATE `coe_mandatory_subcat_subjects` SET `man_subject_id`='23' WHERE `coe_mandatory_subcat_subjects_id`='121';
INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('', '2', '16', '4', '16MC002', 'MANDATORY COURSE: LIFE SKILLS', '0', '100', '0', '0', '50', '1', '100', '11', '2018-10-15 15:45:40', '11', '2018-10-15 15:45:40');
UPDATE `coe_mandatory_subcat_subjects` SET `man_subject_id`='24' WHERE `coe_mandatory_subcat_subjects_id`='125';

INSERT INTO `coe_mandatory_subjects` (`coe_mandatory_subjects_id`, `man_batch_id`, `batch_mapping_id`, `semester`, `subject_code`, `subject_name`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('', '2', '15', '4', '16MC003', 'MANDATORY COURSE: LANGUAGE', '0', '100', '0', '0', '50', '1', '100', '11', '2018-10-15 15:46:09', '11', '2018-10-15 15:46:09');
delete FROM coe_mandatory_subcat_subjects where coe_mandatory_subcat_subjects_id NOT IN(SELECT distinct subject_map_id FROM coe_mandatory_stu_marks );
  delete FROM coe_mandatory_subjects where coe_mandatory_subjects_id NOT IN (select man_subject_id from coe_mandatory_subcat_subjects);

/////////////////////////////////////////////////////////////////////////

ALTER TABLE `coe_mandatory_subcat_subjects` 
DROP INDEX `index9` ,
ADD UNIQUE INDEX `index9` (`sub_cat_name` ASC, `batch_map_id` ASC, `coe_batch_id` ASC, `is_additional` ASC);


//////////////////// 12-11-2018 //////////////

ALTER TABLE `coe_hall_master` CHANGE `hall_name` `hall_name` VARCHAR(245) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `coe_hall_master` CHANGE `description` `description` VARCHAR(245) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `coe_regulation` 
CHANGE COLUMN `grade_point` `grade_point` FLOAT NULL DEFAULT NULL ;

UPDATE `coe_regulation` SET `grade_name`='A+' WHERE `coe_regulation_id`='5';
UPDATE `coe_regulation` SET `grade_name`='D+' WHERE `coe_regulation_id`='7';

////////////15-11-2018////////////////

INSERT INTO `coe_subjects_mapping` (`coe_subjects_mapping_id`, `batch_mapping_id`, `subject_id`, `semester`, `paper_type_id`, `subject_type_id`, `course_type_id`, `migration_status`, `paper_no`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('4148', '24', '2209', '6', '8', '15', '21', 'NO', '0', '1', '2018-10-16 09:44:04', '1', '2018-10-16 09:44:04');
INSERT INTO `coe_subjects_mapping` (`coe_subjects_mapping_id`, `batch_mapping_id`, `subject_id`, `semester`, `paper_type_id`, `subject_type_id`, `course_type_id`, `migration_status`, `paper_no`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('3943', '26', '2207', '6', '8', '15', '79', 'NO', '0', '1', '2018-10-15 09:22:09', '1', '2018-10-15 09:22:09');
INSERT INTO `coe_subjects_mapping` (`coe_subjects_mapping_id`, `batch_mapping_id`, `subject_id`, `semester`, `paper_type_id`, `subject_type_id`, `course_type_id`, `migration_status`, `paper_no`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('3945', '29', '2209', '6', '8', '15', '21', 'NO', '0', '1', '2018-10-15 13:35:03', '1', '2018-10-15 13:35:03');
INSERT INTO `coe_subjects_mapping` (`coe_subjects_mapping_id`, `batch_mapping_id`, `subject_id`, `semester`, `paper_type_id`, `subject_type_id`, `course_type_id`, `migration_status`, `paper_no`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('3944', '27', '2208', '6', '8', '15', '79', 'NO', '0', '1', '2018-10-15 11:06:03', '1', '2018-10-15 11:06:03');
INSERT INTO `coe_subjects_mapping` (`coe_subjects_mapping_id`, `batch_mapping_id`, `subject_id`, `semester`, `paper_type_id`, `subject_type_id`, `course_type_id`, `migration_status`, `paper_no`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('3914', '24', '2186', '7', '8', '13', '20', 'NO', '45', '11', '2018-10-03 15:58:24', '11', '2018-10-03 15:58:24');
INSERT INTO `coe_subjects_mapping` (`coe_subjects_mapping_id`, `batch_mapping_id`, `subject_id`, `semester`, `paper_type_id`, `subject_type_id`, `course_type_id`, `migration_status`, `paper_no`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('3919', '24', '2189', '7', '8', '13', '20', 'NO', '45', '11', '2018-10-03 15:58:24', '11', '2018-10-03 15:58:24');

INSERT INTO `coe_subjects_mapping` (`coe_subjects_mapping_id`, `batch_mapping_id`, `subject_id`, `semester`, `paper_type_id`, `subject_type_id`, `course_type_id`, `migration_status`, `paper_no`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('3992', '26', '2207', '7', '8', '15', '20', 'NO', '1', '11', '2018-10-15 17:10:52', '11', '2018-10-15 17:10:52');
DELETE FROM `coe_exam_timetable` WHERE `coe_exam_timetable_id`='3502';
DELETE FROM `coe_exam_timetable` WHERE `coe_exam_timetable_id`='797';
DELETE FROM `coe_exam_timetable` WHERE `coe_exam_timetable_id`='792';


DELETE FROM `coe_exam_timetable` WHERE `coe_exam_timetable`.`coe_exam_timetable_id` = 774;
DELETE FROM `coe_exam_timetable` WHERE `coe_exam_timetable`.`coe_exam_timetable_id` = 792;
DELETE FROM `coe_exam_timetable` WHERE `coe_exam_timetable`.`coe_exam_timetable_id` = 797;
DELETE FROM `coe_exam_timetable` WHERE `coe_exam_timetable`.`coe_exam_timetable_id` = 3502;


DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18425;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18430;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18435;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18451;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18456;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18461;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18471;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18486;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18491;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18501;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18511;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18516;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18521;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18531;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18536;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18541;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18551;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18557;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18562;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18567;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18572;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18577;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18582;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18592;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18608;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18668;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18663;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18658;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18653;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18648;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18638;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18633;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18623;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18618;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18613;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18662;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18652;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18647;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18642;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18627;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18622;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18596;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18581;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18576;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18566;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18561;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18545;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18535;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18520;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18515;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18510;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18505;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18500;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18485;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18480;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18475;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18465;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18460;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 18439;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 17076;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 17037;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 17034;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 17028;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 17025;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 17022;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 17013;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 17010;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 17007;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 17004;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16995;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16986;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16977;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16974;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16971;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16965;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16962;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16959;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16944;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16932;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16926;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16917;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16914;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16905;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16902;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16893;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16890;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16881;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16878;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16872;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16869;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16847;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16844;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16841;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16838;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16835;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16829;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16808;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16799;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16781;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16775;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16769;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16760;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16739;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16727;
DELETE FROM `coe_nominal` WHERE `coe_nominal`.`coe_nominal_id` = 16709;

DELETE FROM `coe_hall_allocate` WHERE `coe_hall_allocate`.`coe_hall_allocate_id` = 63923;
DELETE FROM `coe_hall_allocate` WHERE `coe_hall_allocate`.`coe_hall_allocate_id` = 63051;
DELETE FROM `coe_hall_allocate` WHERE `coe_hall_allocate`.`coe_hall_allocate_id` = 59303;
DELETE FROM `coe_hall_allocate` WHERE `coe_hall_allocate`.`coe_hall_allocate_id` = 58264;
DELETE FROM `coe_hall_allocate` WHERE `coe_hall_allocate`.`coe_hall_allocate_id` = 56184;
DELETE FROM `coe_hall_allocate` WHERE `coe_hall_allocate`.`coe_hall_allocate_id` = 53061;
DELETE FROM `coe_hall_allocate` WHERE `coe_hall_allocate`.`coe_hall_allocate_id` = 52010;
DELETE FROM `coe_hall_allocate` WHERE `coe_hall_allocate`.`coe_hall_allocate_id` = 49849;
DELETE FROM `coe_hall_allocate` WHERE `coe_hall_allocate`.`coe_hall_allocate_id` = 48805;


//////// 22-NOV-2018 QUERY UPDATE ////////

ALTER TABLE `coe_student` ADD `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `student_status`, ADD `created_by` INT NOT NULL AFTER `created_at`, ADD `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_by`, ADD `updated_by` INT NOT NULL AFTER `updated_at`;

ALTER TABLE `coe_student` CHANGE `created_by` `created_by` INT(11) NOT NULL DEFAULT '1';
ALTER TABLE `coe_student` CHANGE `created_by` `created_by` INT(11) NULL DEFAULT '1';
ALTER TABLE `coe_student` CHANGE `updated_by` `updated_by` INT(11) NULL DEFAULT '1';

Changes in SKCT //

DELETE FROM `coe_category_type` WHERE `coe_category_type_id`='91';
DELETE FROM `coe_category_type` WHERE `coe_category_type_id`='92';
DELETE FROM `coe_categories` WHERE `coe_category_id`='18';
INSERT INTO `coe_categories` (`coe_category_id`, `category_name`, `description`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('18', 'Transparency Fee', 'Transparency Fee', '10', '2018-10-03 22:49:56', '10', '2018-10-03 22:49:56');
INSERT INTO `coe_categories` (`coe_category_id`, `category_name`, `description`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('19', 'Revaluation Fees ', 'Revaluation Fees ', '10', '2018-10-03 22:51:07', '10', '2018-10-03 22:51:07');
INSERT INTO `coe_category_type` (`coe_category_type_id`, `category_id`, `category_type`, `description`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES (NULL, '18', '400', '400', '10', '2018-10-03 22:49:56', '10', '2018-10-03 22:49:56');
INSERT INTO `coe_category_type` (`coe_category_type_id`, `category_id`, `category_type`, `description`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES (NULL, '19', '400', '400', '10', '2018-10-03 22:51:07', '10', '2018-10-03 22:51:07');

UPDATE `coe_category_type` SET `category_type`='Detain/Debar', `description`='Detain/Debar' WHERE `coe_category_type_id`='4';

================= 25-NOV-2018=================
ALTER TABLE `coe_absent_entry` ADD `exam_year` INT NULL DEFAULT NULL AFTER `exam_date`;
update coe_absent_entry set exam_year=YEAR(exam_date);

DELETE FROM `coe_mandatory_subcat_subjects` WHERE `coe_mandatory_subcat_subjects_id`='143';
DELETE FROM `coe_mandatory_subcat_subjects` WHERE `coe_mandatory_subcat_subjects_id`='162';
DELETE FROM `coe_mandatory_subcat_subjects` WHERE `coe_mandatory_subcat_subjects_id`='163';
DELETE FROM `coe_mandatory_subcat_subjects` WHERE `coe_mandatory_subcat_subjects_id`='164';
DELETE FROM `coe_mandatory_subcat_subjects` WHERE `coe_mandatory_subcat_subjects_id`='166';
DELETE FROM `coe_mandatory_subcat_subjects` WHERE `coe_mandatory_subcat_subjects_id`='169';
DELETE FROM `coe_mandatory_subcat_subjects` WHERE `coe_mandatory_subcat_subjects_id`='170';

===================== 15-12-2018 ===============
CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `stu_info` AS
    (SELECT 
        `a`.`coe_student_mapping_id` AS `stu_map_id`,
        `b`.`register_number` AS `reg_num`,
        `b`.`coe_student_id` AS `stu_id`,
        `a`.`course_batch_mapping_id` AS `batch_map_id`
    FROM
        (`coe_student_mapping` `a`
        JOIN `coe_student` `b`)
    WHERE
        (`a`.`student_rel_id` = `b`.`coe_student_id`))

SELECT * FROM  stu_info WHERE stu_id='3089';

CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `sub_info` AS
    (SELECT 
        `a`.`coe_subjects_mapping_id` AS `sub_map_id`,
        `b`.`coe_subjects_id` AS `sub_id`,
        `b`.`subject_code` AS `sub_code`,
        `a`.`batch_mapping_id` AS `sub_batch_id`
    FROM
        (`coe_subjects_mapping` `a`
        JOIN `coe_subjects` `b`)
    WHERE
        (`b`.`coe_subjects_id` = `a`.`subject_id`))
SELECT * FROM  sub_info WHERE sub_code='16PN851';

ALTER TABLE `coe_mark_entry_master` 
ADD UNIQUE INDEX `index6` (`student_map_id` ASC, `subject_map_id` ASC, `year` ASC, `month` ASC, `term` ASC, `mark_type` ASC);

ALTER TABLE `coe_student` CHANGE `aadhar_number` `aadhar_number` VARCHAR(12) NOT NULL;
ALTER TABLE `coe_stu_guardian` CHANGE `guardian_mobile_no` `guardian_mobile_no` VARCHAR(10) NOT NULL;

================= 27-12-2018 ===================

ALTER TABLE `coe_mandatory_subjects` 
ADD COLUMN `part_no` INT NOT NULL DEFAULT 3 AFTER `end_semester_exam_value_mark`;

CREATE TABLE `coe_classifications` (
  `coe_classifications_id` INT NOT NULL AUTO_INCREMENT,
  `regulation_year` INT(4) NOT NULL,
  `percentage_from` FLOAT NOT NULL,
  `percentage_to` FLOAT NOT NULL,
  `grade_name` VARCHAR(5) NOT NULL,
  `classification_text` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT NOT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` INT NOT NULL,
  PRIMARY KEY (`coe_classifications_id`),
  UNIQUE INDEX `index2` (`regulation_year` ASC, `percentage_from` ASC, `percentage_to` ASC),
  INDEX `user_created_idx` (`created_by` ASC),
  INDEX `user_updated_idx` (`updated_by` ASC),
  CONSTRAINT `user_created_fore_id`
    FOREIGN KEY (`created_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_updated_fore_id`
    FOREIGN KEY (`updated_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


ALTER TABLE `coe_mandatory_subcat_subjects` 
ADD COLUMN `credit_point` INT(10) NOT NULL DEFAULT 1 AFTER `subject_type_id`;

ALTER TABLE `coe_mandatory_subcat_subjects` 
CHANGE COLUMN `credit_point` `credit_points` INT(10) NOT NULL DEFAULT '1' ;

ALTER TABLE `coe_mandatory_subcat_subjects` 
CHANGE COLUMN `paper_no` `paper_no` INT(11) NOT NULL AFTER `credit_point`;

ALTER TABLE `coe_mandatory_subjects` 
DROP COLUMN `credit_points`;

USE `live_skct`;

DELIMITER $$

DROP TRIGGER IF EXISTS live_skct.coe_mandatory_subcat_subjects_BEFORE_INSERT$$
USE `live_skct`$$
CREATE DEFINER=`root`@`localhost` TRIGGER `coe_mandatory_subcat_subjects_BEFORE_INSERT` BEFORE INSERT ON `coe_mandatory_subcat_subjects` FOR EACH ROW
BEGIN
SET @sum = @sum + NEW.credit_points;
END$$
DELIMITER ;


================ 08-01-2018 ==============

UPDATE `coe_category_type` SET `category_type`='Transparency Fee' WHERE `coe_category_type_id`='98';
UPDATE `coe_category_type` SET `category_type`='Revaluation Fees' WHERE `coe_category_type_id`='99';

============= 09-01-2019 ARTS COLLEGE CHANGES ============

UPDATE `coe_categories` SET `category_name`='Transparency Fees', `description`='Transparency Fees' WHERE `coe_category_id`='18';
INSERT INTO `coe_categories` (`coe_category_id`, `category_name`, `description`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('', 'Revaluation Fees', 'Revaluation  Fees', '1', '2018-09-17 15:59:19', '1', '2018-09-17 15:59:19');
UPDATE `coe_category_type` SET `category_id`='19' WHERE `coe_category_type_id`='92';
UPDATE `coe_categories` SET `description`='Revaluation Fees' WHERE `coe_category_id`='19';

============= 23-01-2018 CHANGES IN ALL COLLEGES ==========

INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES ('coe.max.reval.subjects', '5', 'Max Revaluation', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '1', '1');

============= 18-02-2018 CHANGES IN ALL COLLEGES ==========

INSERT INTO `coe_configuration` (`config_name`, `config_value`, `config_desc`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES ('coe.max.exam.condution', '7', 'Exclude Batch Exams', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '1', '1');

ALTER TABLE `live_skcet`.`coe_absent_entry` DROP INDEX `index9`, ADD UNIQUE `index9` (`absent_student_reg`, `exam_type`, `absent_term`, `exam_month`, `exam_subject_id`, `exam_year`) USING BTREE;

============ 05-03-2019 CHANGES IN ALL COLLEGES ===========
ALTER TABLE `coe_absent_entry` 
ADD UNIQUE INDEX `index_GROUP` (`exam_type` ASC, `absent_student_reg` ASC, `exam_year` ASC, `absent_term` ASC, `exam_month` ASC, `exam_subject_id` ASC, `exam_session` ASC) ,
DROP INDEX `index9` ;

CREATE TABLE `coe_practical_exam_timetable` (   `coe_practical_exam_timetable_id` INT NOT NULL AUTO_INCREMENT,   `batch_mapping_id` INT NOT NULL,   `student_map_id` INT NOT NULL,   `subject_map_id` INT NOT NULL,   `exam_year` INT NOT NULL,   `exam_month` INT NOT NULL,   `mark_type` INT NOT NULL,   `term` INT NOT NULL,   `exam_date` DATE NOT NULL,   `exam_session` TIME NOT NULL,   `out_of_100` INT NULL,   `ESE` INT NULL,   `internal_examiner_name` VARCHAR(145) NOT NULL,   `external_examiner_name` VARCHAR(245) NULL,   `approve_status` VARCHAR(45) NULL DEFAULT 'NO',   `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,   `created_by` INT NULL,   `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,   `updated_by` INT NULL,   PRIMARY KEY (`coe_practical_exam_timetable_id`),   UNIQUE INDEX `index2` (`batch_mapping_id` ASC, `student_map_id` ASC, `subject_map_id` ASC, `exam_year` ASC, `exam_month` ASC, `mark_type` ASC, `term` ASC, `exam_date` ASC, `exam_session` ASC) ,   INDEX `fk1_idx` (`subject_map_id` ASC) ,   INDEX `fk_2_idx` (`student_map_id` ASC) ,   INDEX `fk_4_idx` (`mark_type` ASC) ,   INDEX `fk_5_idx` (`term` ASC) ,   INDEX `fk_6_idx` (`created_by` ASC) ,   INDEX `fk_7_idx` (`updated_by` ASC) ,   CONSTRAINT `fk_sub_map`     FOREIGN KEY (`subject_map_id`)     REFERENCES `coe_subjects_mapping` (`coe_subjects_mapping_id`)     ON DELETE NO ACTION     ON UPDATE NO ACTION,   CONSTRAINT `fk_stu_map_2`     FOREIGN KEY (`student_map_id`)     REFERENCES `coe_student_mapping` (`coe_student_mapping_id`)     ON DELETE NO ACTION     ON UPDATE NO ACTION,   CONSTRAINT `fk_batch_map_3`     FOREIGN KEY (`batch_mapping_id`)     REFERENCES `coe_bat_deg_reg` (`coe_bat_deg_reg_id`)     ON DELETE NO ACTION     ON UPDATE NO ACTION,   CONSTRAINT `fk_cat_id_4`     FOREIGN KEY (`mark_type`)     REFERENCES `coe_category_type` (`coe_category_type_id`)     ON DELETE NO ACTION     ON UPDATE NO ACTION,   CONSTRAINT `fk_5`     FOREIGN KEY (`term`)     REFERENCES `coe_category_type` (`coe_category_type_id`)     ON DELETE NO ACTION     ON UPDATE NO ACTION,   CONSTRAINT `fk_6`     FOREIGN KEY (`created_by`)     REFERENCES `user` (`id`)     ON DELETE NO ACTION     ON UPDATE NO ACTION,   CONSTRAINT `fk_7`     FOREIGN KEY (`updated_by`)     REFERENCES `user` (`id`)     ON DELETE NO ACTION     ON UPDATE NO ACTION) COMMENT = 'This table is using to track the data for Internal Examiner'  


INSERT INTO `skcet`.`coe_subjects` (`coe_subjects_id`, `subject_code`, `subject_name`, `subject_fee`, `CIA_min`, `CIA_max`, `ESE_min`, `ESE_max`, `total_minimum_pass`, `credit_points`, `part_no`, `end_semester_exam_value_mark`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('', '11UAK704.', 'TOTAL QUALITY MANAGEMENT ', '200', '0', '40', '30', '60', '50', '3', '3', '100', '1', '2018-03-17 17:33:57', '1', '2018-03-17 17:33:57');


=============== 15-03-2019 changes in all colleges ================

ALTER TABLE `coe_subjects` CHANGE `coe_subjects_id` `coe_subjects_id` BIGINT(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `coe_absent_entry` CHANGE `coe_absent_entry_id` `coe_absent_entry_id` BIGINT(10) NOT NULL AUTO_INCREMENT;



===================== ARTS COLLEGE BAR CODE TABLE 27-03-2019 ======================

CREATE TABLE `coe_bar_code_quest_marks` (
  `coe_bar_code_quest_marks_id` BIGINT(10) NOT NULL AUTO_INCREMENT,
  `student_map_id` INT(10) NOT NULL,
  `subject_map_id` INT(10) NOT NULL,
  `dummy_number` INT(10) NOT NULL,
  `year` INT(10) NOT NULL,
  `month` INT(10) NOT NULL,
  `question_no` INT(10) NOT NULL,
  `question_no_marks` INT(10) NOT NULL,
  `mark_type` INT(10) NOT NULL,
  `term` INT(10) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT(10) NOT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` INT(10) NOT NULL,
  PRIMARY KEY (`coe_bar_code_quest_marks_id`),
  INDEX `stu_map_ref_idx` (`student_map_id` ASC) ,
  INDEX `sub_map_ref_idx` (`subject_map_id` ASC) ,
  INDEX `user_map_ref_idx` (`created_by` ASC) ,
  INDEX `user_map_ref_update_idx` (`updated_by` ASC) ,
  INDEX `dummy_map_ref_idx` (`dummy_number` ASC) ,
  UNIQUE INDEX `do_not_repeat_duplicat` (`student_map_id` ASC, `subject_map_id` ASC, `year` ASC, `month` ASC, `question_no` ASC, `mark_type` ASC, `term` ASC) ,
  INDEX `mark_type_ref_idx` (`mark_type` ASC) ,
  INDEX `mark_term_ref_idx` (`term` ASC) ,
  CONSTRAINT `stu_map_ref`
    FOREIGN KEY (`student_map_id`)
    REFERENCES `coe_student_mapping` (`coe_student_mapping_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `sub_map_ref`
    FOREIGN KEY (`subject_map_id`)
    REFERENCES `coe_subjects_mapping` (`coe_subjects_mapping_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_map_ref`
    FOREIGN KEY (`created_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_map_ref_update`
    FOREIGN KEY (`updated_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `dummy_map_ref`
    FOREIGN KEY (`dummy_number`)
    REFERENCES `coe_dummy_number` (`coe_dummy_number_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `mark_type_ref`
    FOREIGN KEY (`mark_type`)
    REFERENCES `coe_category_type` (`coe_category_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `mark_term_ref`
    FOREIGN KEY (`term`)
    REFERENCES `coe_category_type` (`coe_category_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
COMMENT = 'This Table contains the Question Wise Marks for the students';


============================= 09-04-2019 =================== 

ALTER TABLE `coe_student_category_details` ADD `mark_type` INT(10) NOT NULL DEFAULT '27' AFTER `month`, ADD `term` INT(10) NOT NULL DEFAULT '34' AFTER `mark_type`;

========================== 04-06-2019 ======================

ALTER TABLE `coe_additional_credits` 
ADD COLUMN `out_of_maximum` INT NULL DEFAULT 0 AFTER `credits`;

ALTER TABLE `coe_additional_credits` 
ADD COLUMN `CIA` INT NULL AFTER `out_of_maximum`,
ADD COLUMN `ESE` INT NULL AFTER `CIA`,
ADD COLUMN `cia_maximum` INT NULL AFTER `grade_name`,
ADD COLUMN `cia_minimum` INT NULL AFTER `cia_maximum`,
ADD COLUMN `ese_minimum` INT NULL AFTER `cia_minimum`,
ADD COLUMN `ese_maximum` INT NULL AFTER `ese_minimum`;

ALTER TABLE `coe_additional_credits` 
ADD COLUMN `total_minimum_pass` INT NULL AFTER `ese_minimum`;

ALTER TABLE `coe_additional_credits` ADD `semester` INT NOT NULL AFTER `credits`;
ALTER TABLE `coe_additional_credits` ADD `part_no` INT NOT NULL DEFAULT '3' AFTER `credits`;


Changes for Arts colleges
06-02-2020

CREATE TABLE `coe_update_tracker` (
  `coe_update_tracker_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_map_id` int(11) DEFAULT NULL,
  `subject_map_id` int(11) DEFAULT NULL,
  `exam_year` int(11) DEFAULT NULL,
  `exam_month` int(11) DEFAULT NULL,
  `updated_ip_address` varchar(1000) DEFAULT NULL,
  `prev_subject_code` varchar(1000) DEFAULT NULL,
  `prev_subject_name` varchar(1000) DEFAULT NULL,
  `prev_internal_marks` int(11) DEFAULT NULL,
  `new_internal_marks` int(11) DEFAULT NULL,
  `prev_ese_marks` int(11) DEFAULT NULL,
  `new_ese_marks` int(11) DEFAULT NULL,
  `new_subject_code` varchar(1000) DEFAULT NULL,
  `new_subject_name` varchar(1000) DEFAULT NULL,
  `prev_result` varchar(1000) DEFAULT NULL,
  `new_result` varchar(1000) DEFAULT NULL,
  `prev_total` int(11) DEFAULT NULL,
  `new_total` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`coe_update_tracker_id`),
  KEY `1_idx` (`student_map_id`),
  KEY `2_idx` (`subject_map_id`),
  KEY `3_idx` (`updated_by`),
  KEY `4_idx` (`exam_month`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

ALTER TABLE `coe_update_tracker` 
DROP COLUMN `new_total`,
DROP COLUMN `prev_total`,
DROP COLUMN `new_result`,
DROP COLUMN `prev_result`,
DROP COLUMN `new_subject_name`,
DROP COLUMN `new_subject_code`,
DROP COLUMN `new_ese_marks`,
DROP COLUMN `prev_ese_marks`,
DROP COLUMN `new_internal_marks`,
DROP COLUMN `prev_internal_marks`,
CHANGE COLUMN `updated_ip_address` `updated_ip_address` VARCHAR(255) NULL DEFAULT NULL ,
CHANGE COLUMN `prev_subject_code` `updated_link_from` VARCHAR(1000) NULL DEFAULT NULL ,
CHANGE COLUMN `prev_subject_name` `data_updated` VARCHAR(1000) NULL DEFAULT NULL ;
ALTER TABLE `coe_update_tracker` 
ADD CONSTRAINT `update_update_user`
  FOREIGN KEY (`updated_by`)
  REFERENCES `user` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `coe_update_tracker` 
CHANGE COLUMN `data_updated` `data_updated` TEXT NULL DEFAULT NULL ;

  CREATE TABLE `coe_fees_paid` (
  `coe_fees_paid_id` INT NOT NULL AUTO_INCREMENT,
  `student_map_id` INT NOT NULL,
  `subject_map_id` INT NOT NULL,
  `year` INT NOT NULL,
  `month` INT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT NOT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` INT NOT NULL,
  PRIMARY KEY (`coe_fees_paid_id`),
  INDEX `stu_fees_id_idx` (`student_map_id` ASC) ,
  INDEX `sub_fees_id_idx` (`subject_map_id` ASC) ,
  INDEX `user_fee_id_idx` (`created_by` ASC) ,
  INDEX `user_fees_up_id_idx` (`updated_by` ASC) ,
  CONSTRAINT `stu_fees_id`
    FOREIGN KEY (`student_map_id`)
    REFERENCES `coe_student_mapping` (`coe_student_mapping_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `sub_fees_id`
    FOREIGN KEY (`subject_map_id`)
    REFERENCES `coe_subjects_mapping` (`coe_subjects_mapping_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_fee_id`
    FOREIGN KEY (`created_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_fees_up_id`
    FOREIGN KEY (`updated_by`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

ALTER TABLE `coe_fees_paid` ADD COLUMN `status` VARCHAR(45) NULL AFTER `month`;



select * from coe_mark_entry_master where CIA+ESE!=total;
update coe_mark_entry_master set total=(CIA+ESE) where CIA+ESE!=total;
select * from coe_mark_entry_master where result!='Pass' and grade_point!=0;
update coe_mark_entry_master set grade_point=0 where result!='Pass' and grade_point!=0;
SELECT * FROM coe_mark_entry_master where grade_point between 5 and 5.9 and grade_name!='B' and result='Pass'
update coe_mark_entry_master set grade_name='B' where grade_point between 5 and 5.9 and grade_name!='B' and result='Pass'
SELECT * FROM coe_mark_entry_master where grade_point!=0 and result='Fail';
update coe_mark_entry_master set grade_point=0 where grade_point!=0 and result='Fail';
SELECT * FROM coe_mark_entry_master where grade_point!=0 and result='Absent';
update coe_mark_entry_master set grade_point=0 where grade_point!=0 and result='Absent';

ALTER TABLE `coe_fees_paid` ADD COLUMN `is_imported` VARCHAR(45) NOT NULL DEFAULT 'NO' AFTER `status`;


========================== 22-07-2021 ======================part no update in arts colleage

CREATE 
     OR REPLACE ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `sub_info` AS
    (SELECT 
        `a`.`coe_subjects_mapping_id` AS `sub_map_id`,
        `b`.`coe_subjects_id` AS `sub_id`,
        `b`.`subject_code` AS `sub_code`,
        `a`.`semester` AS `sem`,
        `a`.`batch_mapping_id` AS `sub_batch_id`,
         `b`.`part_no` AS `part_no`
        
    FROM
        (`coe_subjects_mapping` `a`
        JOIN `coe_subjects` `b`)
    WHERE
        (`b`.`coe_subjects_id` = `a`.`subject_id`));
--------value added course----------
CREATE TABLE `coe_value_subjects` (
  `coe_val_sub_id` int(10) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_fee` int(10) DEFAULT NULL,
  `CIA_min` int(10) NOT NULL,
  `CIA_max` int(10) NOT NULL,
  `ESE_min` int(10) NOT NULL,
  `ESE_max` int(10) NOT NULL,
  `total_minimum_pass` int(10) NOT NULL,
  `credit_points` float NOT NULL,
  `part_no` int(11) NOT NULL DEFAULT '3',
  `end_semester_exam_value_mark` int(10) NOT NULL,
  `created_by` int(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(50) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=144 DEFAULT CHARSET=latin1;

ALTER TABLE `coe_value_subjects`
  ADD PRIMARY KEY (`coe_val_sub_id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`,`subject_name`),
  ADD KEY `subjects_ibfk_1` (`created_by`),
  ADD KEY `subjects_ibfk_2` (`updated_by`);


   AUTO_INCREMENT for table `coe_value_subjects`
--
ALTER TABLE `coe_value_subjects`
  MODIFY `coe_val_sub_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2647;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coe_subjects`
--
ALTER TABLE `coe_value_subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`);
COMMIT;

--------------------------------------------------
CREATE TABLE `coe_sub_mapping` (
  `coe_sub_mapping_id` int(10) NOT NULL,
  `batch_mapping_id` int(10) NOT NULL,
  `val_subject_id` int(10) NOT NULL,
  `semester` int(11) NOT NULL,
  `paper_type_id` int(10) NOT NULL,
  `subject_type_id` int(10) NOT NULL,
  `course_type_id` int(10) NOT NULL,
  `migration_status` varchar(45) DEFAULT 'NO',
  `paper_no` int(11) NOT NULL DEFAULT '1',
  `created_by` int(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(50) NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=82 DEFAULT CHARSET=latin1;


CREATE TABLE `coe_value_nominal` (
  `coe_nominal_val_id` int(11) NOT NULL,
  `course_batch_mapping_id` int(11) NOT NULL,
  `coe_student_id` int(11) NOT NULL,
  `coe_subjects_id` int(11) NOT NULL,
  `section_name` varchar(4) NOT NULL,
  `semester` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=136 DEFAULT CHARSET=latin1;
COMMIT;



CREATE TABLE `coe_value_mark_entry` (
  `coe_value_mark_entry_id` int(10) NOT NULL,
  `student_map_id` int(10) NOT NULL,
  `subject_map_id` int(10) NOT NULL,
  `CIA` int(10) NOT NULL,
  `ESE` int(10) NOT NULL,
  `total` int(10) NOT NULL,
  `result` varchar(50) NOT NULL,
  `grade_point` float NOT NULL,
  `grade_name` varchar(10) NOT NULL,
  `year` int(10) NOT NULL,
  `month` int(10) NOT NULL,
  `term` int(10) NOT NULL,
  `mark_type` int(10) NOT NULL,
  `status_id` int(10) DEFAULT NULL,
  `year_of_passing` varchar(30) DEFAULT NULL,
  `attempt` int(10) DEFAULT NULL,
  `withheld` varchar(45) DEFAULT NULL,
  `withheld_remarks` varchar(100) DEFAULT NULL,
  `withdraw` varchar(45) DEFAULT NULL,
  `is_updated` varchar(45) NOT NULL DEFAULT 'NO',
  `fees_paid` varchar(45) DEFAULT NULL,
  `result_published_date` date NOT NULL,
  `created_by` int(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(50) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=113 DEFAULT CHARSET=latin1;