<?php
//----ALTER TABLE `users` ADD `mobile` VARCHAR(15) NULL DEFAULT NULL AFTER `email`;

/*
--ALTER TABLE `donarinfos` ADD `approvedStatus` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1=Pending, 2=Approved,
3=Decline' AFTER `donationAmount`, ADD `approvedInfo` TEXT NULL DEFAULT NULL AFTER `approvedStatus`;
ALTER TABLE `donarinfos` CHANGE `approvedInfo` `processInfo` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;


--ALTER TABLE `users` ADD `mobileBankBkash` VARCHAR(15) NULL DEFAULT NULL AFTER `mobile`;

--ALTER TABLE `donarinfos` ADD `TransactionMobileNumber` VARCHAR(15) NULL DEFAULT NULL AFTER `TransactionID`;



----ALTER TABLE `users` CHANGE `user_type` `user_type` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=Super Admin
2=admin,3=found Collector,4=Operator,5=Donar';

----ALTER TABLE `users` CHANGE `mobile` `mobile` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT
NULL, CHANGE `coordinator` `coordinator` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `emine` `emine` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `created_ip` `created_ip` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `updated_ip` `updated_ip` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;




CREATE TABLE `sms_history` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `mobile_number` varchar(30) NOT NULL,
  `msg` text NOT NULL,
  `send_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=pending, 2=send comple',
  `success_status` tinyint(1) DEFAULT NULL COMMENT '1=success,=fail',
  `ins_date` datetime NOT NULL,
  `is_resend_sms` tinyint(1) DEFAULT NULL,
  `is_resend_sms_date` datetime DEFAULT NULL,
  `ins_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `sms_history`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sms_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110848;

ALTER TABLE `sms_history` CHANGE `member_id` `donar_id` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT 'table # users';

ALTER TABLE `sms_history` ADD `updated_at` DATETIME NULL DEFAULT NULL AFTER `ins_by`;
ALTER TABLE `sms_history` CHANGE `ins_date` `created_at` DATETIME NOT NULL;
