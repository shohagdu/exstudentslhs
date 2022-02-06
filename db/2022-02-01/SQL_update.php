<?php
//----ALTER TABLE `users` ADD `mobile` VARCHAR(15) NULL DEFAULT NULL AFTER `email`;

/*
--ALTER TABLE `donarinfos` ADD `approvedStatus` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1=Pending, 2=Approved,
3=Decline' AFTER `donationAmount`, ADD `approvedInfo` TEXT NULL DEFAULT NULL AFTER `approvedStatus`;
ALTER TABLE `donarinfos` CHANGE `approvedInfo` `processInfo` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;


--ALTER TABLE `users` ADD `mobileBankBkash` VARCHAR(15) NULL DEFAULT NULL AFTER `mobile`;

--ALTER TABLE `donarinfos` ADD `TransactionMobileNumber` VARCHAR(15) NULL DEFAULT NULL AFTER `TransactionID`;



ALTER TABLE `users` CHANGE `user_type` `user_type` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=Super Admin 2=admin,3=found Collector,4=Operator,5=Donar';

ALTER TABLE `users` CHANGE `mobile` `mobile` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `coordinator` `coordinator` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `emine` `emine` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `created_ip` `created_ip` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `updated_ip` `updated_ip` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
