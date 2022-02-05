<?php
//----ALTER TABLE `users` ADD `mobile` VARCHAR(15) NULL DEFAULT NULL AFTER `email`;

/*
--ALTER TABLE `donarinfos` ADD `approvedStatus` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1=Pending, 2=Approved,
3=Decline' AFTER `donationAmount`, ADD `approvedInfo` TEXT NULL DEFAULT NULL AFTER `approvedStatus`;
ALTER TABLE `donarinfos` CHANGE `approvedInfo` `processInfo` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;


--ALTER TABLE `users` ADD `mobileBankBkash` VARCHAR(15) NULL DEFAULT NULL AFTER `mobile`;
