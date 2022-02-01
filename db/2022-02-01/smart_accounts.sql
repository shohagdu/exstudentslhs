-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2022 at 04:00 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_accounts`
--

-- --------------------------------------------------------

--
-- Table structure for table `acc_customer_ledger`
--

CREATE TABLE `acc_customer_ledger` (
  `id` int(11) UNSIGNED NOT NULL,
  `customer_id` int(11) UNSIGNED NOT NULL,
  `loan_info_id` int(11) DEFAULT NULL,
  `credit_amount` float NOT NULL DEFAULT 0,
  `debit_amount` float NOT NULL DEFAULT 0,
  `transaction_type` enum('loan_receive','loan_repayment','deposit','withdrawal') NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `acc_general_ledger`
--

CREATE TABLE `acc_general_ledger` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `transaction_parent_id` bigint(20) DEFAULT NULL,
  `debit_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Debit COA ID',
  `credit_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Credit COA ID',
  `amount` float UNSIGNED NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `transaction_type` enum('customer_deposit','capital_investment','revenue','expense') DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_by_ip` varchar(15) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_by_ip` varchar(15) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `acc_general_ledger`
--

INSERT INTO `acc_general_ledger` (`id`, `transaction_id`, `transaction_parent_id`, `debit_id`, `credit_id`, `amount`, `comment`, `transaction_type`, `status`, `created_at`, `created_by`, `created_by_ip`, `updated_at`, `updated_by`, `updated_by_ip`, `deleted_at`, `deleted_by`) VALUES
(1, 'TR#1220201060552', NULL, NULL, NULL, 5000, 'asdfsa', 'capital_investment', 'inactive', '2022-02-01 06:05:52', 1, '::1', '2022-02-01 14:15:24', 1, '::1', '2022-02-01 08:15:24', 1),
(2, NULL, 1, 18, NULL, 5000, NULL, 'capital_investment', 'inactive', '2022-02-01 06:05:52', 1, '::1', '2022-02-01 14:15:24', 1, '::1', '2022-02-01 08:15:24', 1),
(3, NULL, 1, NULL, 1, 5000, NULL, 'capital_investment', 'inactive', '2022-02-01 06:05:52', 1, '::1', '2022-02-01 14:15:24', 1, '::1', '2022-02-01 08:15:24', 1),
(4, 'TR#1220201060610', NULL, NULL, NULL, 6000, 'dfs', 'capital_investment', 'active', '2022-02-01 06:06:10', 1, '::1', '2022-02-01 06:06:10', 1, '::1', NULL, NULL),
(5, NULL, 4, 3, NULL, 6000, NULL, 'capital_investment', 'active', '2022-02-01 06:06:10', 1, '::1', '2022-02-01 06:06:10', 1, '::1', NULL, NULL),
(6, NULL, 4, NULL, 1, 6000, NULL, 'capital_investment', 'active', '2022-02-01 06:06:10', 1, '::1', '2022-02-01 06:06:10', 1, '::1', NULL, NULL),
(7, 'TR#1220201072043', NULL, NULL, NULL, 6000, 'sdfs', 'capital_investment', 'active', '2022-02-01 07:20:43', 1, '::1', '2022-02-01 07:20:43', 1, '::1', NULL, NULL),
(8, NULL, 7, 3, NULL, 6000, NULL, 'capital_investment', 'active', '2022-02-01 07:20:43', 1, '::1', '2022-02-01 07:20:43', 1, '::1', NULL, NULL),
(9, NULL, 7, NULL, 1, 6000, NULL, 'capital_investment', 'active', '2022-02-01 07:20:43', 1, '::1', '2022-02-01 07:20:43', 1, '::1', NULL, NULL),
(10, 'TR#1220201072103', NULL, NULL, NULL, 100, 'sdf', 'capital_investment', 'active', '2022-02-01 07:21:03', 1, '::1', '2022-02-01 07:21:03', 1, '::1', NULL, NULL),
(11, NULL, 10, 18, NULL, 100, NULL, 'capital_investment', 'active', '2022-02-01 07:21:03', 1, '::1', '2022-02-01 07:21:03', 1, '::1', NULL, NULL),
(12, NULL, 10, NULL, 1, 100, NULL, 'capital_investment', 'active', '2022-02-01 07:21:03', 1, '::1', '2022-02-01 07:21:03', 1, '::1', NULL, NULL),
(13, 'TR#1220201073811', NULL, NULL, NULL, 5000, 'asdfssa sdfasfsd sadfff', 'capital_investment', 'active', '2022-02-01 07:38:11', 1, '::1', '2022-02-01 07:38:11', 1, '::1', NULL, NULL),
(14, NULL, 13, 3, NULL, 5000, NULL, 'capital_investment', 'active', '2022-02-01 07:38:11', 1, '::1', '2022-02-01 07:38:11', 1, '::1', NULL, NULL),
(15, NULL, 13, NULL, 1, 5000, NULL, 'capital_investment', 'active', '2022-02-01 07:38:11', 1, '::1', '2022-02-01 07:38:11', 1, '::1', NULL, NULL),
(16, 'TR#1220201080221', NULL, NULL, NULL, 500, 'sdfa', 'capital_investment', 'active', '2022-02-01 08:02:21', 1, '::1', '2022-02-01 08:02:21', 1, '::1', NULL, NULL),
(17, NULL, 16, 3, NULL, 500, NULL, 'capital_investment', 'active', '2022-02-01 08:02:21', 1, '::1', '2022-02-01 08:02:21', 1, '::1', NULL, NULL),
(18, NULL, 16, NULL, 1, 500, NULL, 'capital_investment', 'active', '2022-02-01 08:02:21', 1, '::1', '2022-02-01 08:02:21', 1, '::1', NULL, NULL),
(19, 'TR#1220201080246', NULL, NULL, NULL, 6000, 'sdfsd sdfa', 'capital_investment', 'active', '2022-02-01 08:02:46', 1, '::1', '2022-02-01 08:02:46', 1, '::1', NULL, NULL),
(20, NULL, 19, 18, NULL, 6000, NULL, 'capital_investment', 'active', '2022-02-01 08:02:46', 1, '::1', '2022-02-01 08:02:46', 1, '::1', NULL, NULL),
(21, NULL, 19, NULL, 1, 6000, NULL, 'capital_investment', 'active', '2022-02-01 08:02:46', 1, '::1', '2022-02-01 08:02:46', 1, '::1', NULL, NULL),
(22, 'TR#1220201080342', NULL, NULL, NULL, 600, 'sdfa', 'capital_investment', 'active', '2022-02-01 08:03:42', 1, '::1', '2022-02-01 08:03:42', 1, '::1', NULL, NULL),
(23, NULL, 22, 3, NULL, 600, NULL, 'capital_investment', 'active', '2022-02-01 08:03:42', 1, '::1', '2022-02-01 08:03:42', 1, '::1', NULL, NULL),
(24, NULL, 22, NULL, 1, 600, NULL, 'capital_investment', 'active', '2022-02-01 08:03:42', 1, '::1', '2022-02-01 08:03:42', 1, '::1', NULL, NULL),
(25, 'TR#1220201080709', NULL, NULL, NULL, 5000, 'gf', 'capital_investment', 'active', '2022-02-01 08:07:09', 1, '::1', '2022-02-01 08:07:09', 1, '::1', NULL, NULL),
(26, NULL, 25, 3, NULL, 5000, NULL, 'capital_investment', 'active', '2022-02-01 08:07:09', 1, '::1', '2022-02-01 08:07:09', 1, '::1', NULL, NULL),
(27, NULL, 25, NULL, 1, 5000, NULL, 'capital_investment', 'active', '2022-02-01 08:07:09', 1, '::1', '2022-02-01 08:07:09', 1, '::1', NULL, NULL),
(28, 'TR#1220201082025', NULL, NULL, NULL, 90000, 'sdf sdf ds sdfsf', 'capital_investment', 'active', '2022-02-01 08:20:25', 1, '::1', '2022-02-01 08:20:25', 1, '::1', NULL, NULL),
(29, NULL, 28, 18, NULL, 90000, NULL, 'capital_investment', 'active', '2022-02-01 08:20:25', 1, '::1', '2022-02-01 08:20:25', 1, '::1', NULL, NULL),
(30, NULL, 28, NULL, 1, 90000, NULL, 'capital_investment', 'active', '2022-02-01 08:20:25', 1, '::1', '2022-02-01 08:20:25', 1, '::1', NULL, NULL),
(31, 'TR#1220201083558', NULL, NULL, NULL, 108, 'dssf sdf sd', 'capital_investment', 'active', '2022-02-01 08:35:58', 1, '::1', '2022-02-01 08:35:58', 1, '::1', NULL, NULL),
(32, NULL, 31, 3, NULL, 108, NULL, 'capital_investment', 'active', '2022-02-01 08:35:58', 1, '::1', '2022-02-01 08:35:58', 1, '::1', NULL, NULL),
(33, NULL, 31, NULL, 1, 108, NULL, 'capital_investment', 'active', '2022-02-01 08:35:58', 1, '::1', '2022-02-01 08:35:58', 1, '::1', NULL, NULL),
(34, 'TR#1220201111036', NULL, NULL, NULL, 50000, 'asdfa', 'capital_investment', 'active', '2022-02-01 11:10:36', 1, '::1', '2022-02-01 11:10:36', 1, '::1', NULL, NULL),
(35, NULL, 34, 3, NULL, 50000, NULL, 'capital_investment', 'active', '2022-02-01 11:10:36', 1, '::1', '2022-02-01 11:10:36', 1, '::1', NULL, NULL),
(36, NULL, 34, NULL, 1, 50000, NULL, 'capital_investment', 'active', '2022-02-01 11:10:36', 1, '::1', '2022-02-01 11:10:36', 1, '::1', NULL, NULL),
(37, 'TR#1220201111442', NULL, NULL, NULL, 50000, '85411', 'capital_investment', 'active', '2022-02-01 11:14:42', 1, '::1', '2022-02-01 11:14:42', 1, '::1', NULL, NULL),
(38, NULL, 37, 3, NULL, 50000, NULL, 'capital_investment', 'active', '2022-02-01 11:14:42', 1, '::1', '2022-02-01 11:14:42', 1, '::1', NULL, NULL),
(39, NULL, 37, NULL, 1, 50000, NULL, 'capital_investment', 'active', '2022-02-01 11:14:42', 1, '::1', '2022-02-01 11:14:42', 1, '::1', NULL, NULL),
(40, 'TR#1220201112944', NULL, NULL, NULL, 9000, 'asdf', 'capital_investment', 'inactive', '2022-02-01 11:29:44', 1, '::1', '2022-02-01 14:49:19', 1, '::1', '2022-02-01 08:49:19', 1),
(41, NULL, 40, 3, NULL, 9000, NULL, 'capital_investment', 'inactive', '2022-02-01 11:29:44', 1, '::1', '2022-02-01 14:49:19', 1, '::1', '2022-02-01 08:49:19', 1),
(42, NULL, 40, NULL, 1, 9000, NULL, 'capital_investment', 'inactive', '2022-02-01 11:29:44', 1, '::1', '2022-02-01 14:49:19', 1, '::1', '2022-02-01 08:49:19', 1),
(43, 'TR#1220201121629', NULL, NULL, NULL, 300, 'sdfgs', 'capital_investment', 'inactive', '2022-02-01 12:16:29', 1, '::1', '2022-02-01 14:48:30', 1, '::1', '2022-02-01 08:48:30', 1),
(44, NULL, 43, 3, NULL, 300, NULL, 'capital_investment', 'inactive', '2022-02-01 12:16:29', 1, '::1', '2022-02-01 14:48:30', 1, '::1', '2022-02-01 08:48:30', 1),
(45, NULL, 43, NULL, 1, 300, NULL, 'capital_investment', 'inactive', '2022-02-01 12:16:29', 1, '::1', '2022-02-01 14:48:30', 1, '::1', '2022-02-01 08:48:30', 1),
(46, 'TR#1220201121716', NULL, NULL, NULL, 4000, 'sdfs', 'capital_investment', 'inactive', '2022-02-01 12:17:16', 1, '::1', '2022-02-01 14:48:51', 1, '::1', '2022-02-01 08:48:51', 1),
(47, NULL, 46, 18, NULL, 4000, NULL, 'capital_investment', 'inactive', '2022-02-01 12:17:16', 1, '::1', '2022-02-01 14:48:51', 1, '::1', '2022-02-01 08:48:51', 1),
(48, NULL, 46, NULL, 1, 4000, NULL, 'capital_investment', 'inactive', '2022-02-01 12:17:16', 1, '::1', '2022-02-01 14:48:51', 1, '::1', '2022-02-01 08:48:51', 1),
(49, 'TR#1220201141524', NULL, NULL, NULL, 5000, 'asdfsa', 'capital_investment', 'inactive', '2022-02-01 14:15:24', 1, '::1', '2022-02-01 14:16:01', 1, '::1', '2022-02-01 08:16:01', 1),
(50, NULL, 49, 18, NULL, 5000, NULL, 'capital_investment', 'inactive', '2022-02-01 14:15:24', 1, '::1', '2022-02-01 14:16:01', 1, '::1', '2022-02-01 08:16:01', 1),
(51, NULL, 49, NULL, 1, 5000, NULL, 'capital_investment', 'inactive', '2022-02-01 14:15:24', 1, '::1', '2022-02-01 14:16:01', 1, '::1', '2022-02-01 08:16:01', 1),
(52, 'TR#1220201141601', NULL, NULL, NULL, 10, 'asdfsa', 'capital_investment', 'inactive', '2022-02-01 14:16:01', 1, '::1', '2022-02-01 14:23:31', 1, '::1', '2022-02-01 08:23:31', 1),
(53, NULL, 52, 18, NULL, 10, NULL, 'capital_investment', 'inactive', '2022-02-01 14:16:01', 1, '::1', '2022-02-01 14:23:31', 1, '::1', '2022-02-01 08:23:31', 1),
(54, NULL, 52, NULL, 1, 10, NULL, 'capital_investment', 'inactive', '2022-02-01 14:16:01', 1, '::1', '2022-02-01 14:23:31', 1, '::1', '2022-02-01 08:23:31', 1),
(55, 'TR#1220201142331', NULL, NULL, NULL, 20, 'asdfsa dsaf adsfa dfadf', 'capital_investment', 'inactive', '2022-02-01 14:16:01', 1, '::1', '2022-02-01 14:48:26', 1, '::1', '2022-02-01 08:48:26', 1),
(56, NULL, 55, 18, NULL, 20, NULL, 'capital_investment', 'inactive', '2022-02-01 14:16:01', 1, '::1', '2022-02-01 14:48:26', 1, '::1', '2022-02-01 08:48:26', 1),
(57, NULL, 55, NULL, 1, 20, NULL, 'capital_investment', 'inactive', '2022-02-01 14:16:01', 1, '::1', '2022-02-01 14:48:26', 1, '::1', '2022-02-01 08:48:26', 1),
(58, 'TR#1220201142450', NULL, NULL, NULL, 8000, 'sdfas', 'capital_investment', 'inactive', '2022-02-01 14:24:50', 1, '::1', '2022-02-01 14:24:58', 1, '::1', '2022-02-01 08:24:58', 1),
(59, NULL, 58, 18, NULL, 8000, NULL, 'capital_investment', 'inactive', '2022-02-01 14:24:50', 1, '::1', '2022-02-01 14:24:58', 1, '::1', '2022-02-01 08:24:58', 1),
(60, NULL, 58, NULL, 1, 8000, NULL, 'capital_investment', 'inactive', '2022-02-01 14:24:50', 1, '::1', '2022-02-01 14:24:58', 1, '::1', '2022-02-01 08:24:58', 1),
(61, 'TR#1220201142458', NULL, NULL, NULL, 8000, 'sdfas asdfasd', 'capital_investment', 'inactive', '2022-02-01 14:24:50', 1, '::1', '2022-02-01 14:49:49', 1, '::1', '2022-02-01 08:49:49', 1),
(62, NULL, 61, 18, NULL, 8000, NULL, 'capital_investment', 'inactive', '2022-02-01 14:24:50', 1, '::1', '2022-02-01 14:49:49', 1, '::1', '2022-02-01 08:49:49', 1),
(63, NULL, 61, NULL, 1, 8000, NULL, 'capital_investment', 'inactive', '2022-02-01 14:24:50', 1, '::1', '2022-02-01 14:49:49', 1, '::1', '2022-02-01 08:49:49', 1),
(64, 'TR#1220201144843', NULL, NULL, NULL, 500000, 'asdfasf', 'capital_investment', 'inactive', '2022-02-01 14:48:43', 1, '::1', '2022-02-01 14:48:47', 1, '::1', '2022-02-01 08:48:47', 1),
(65, NULL, 64, 3, NULL, 500000, NULL, 'capital_investment', 'inactive', '2022-02-01 14:48:43', 1, '::1', '2022-02-01 14:48:47', 1, '::1', '2022-02-01 08:48:47', 1),
(66, NULL, 64, NULL, 1, 500000, NULL, 'capital_investment', 'inactive', '2022-02-01 14:48:43', 1, '::1', '2022-02-01 14:48:47', 1, '::1', '2022-02-01 08:48:47', 1),
(67, 'TR#1220201144847', NULL, NULL, NULL, 500000, 'asdfasf', 'capital_investment', 'inactive', '2022-02-01 14:48:43', 1, '::1', '2022-02-01 14:49:47', 1, '::1', '2022-02-01 08:49:47', 1),
(68, NULL, 67, 3, NULL, 500000, NULL, 'capital_investment', 'inactive', '2022-02-01 14:48:43', 1, '::1', '2022-02-01 14:49:47', 1, '::1', '2022-02-01 08:49:47', 1),
(69, NULL, 67, NULL, 1, 500000, NULL, 'capital_investment', 'inactive', '2022-02-01 14:48:43', 1, '::1', '2022-02-01 14:49:47', 1, '::1', '2022-02-01 08:49:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `acc_transactional_coa`
--

CREATE TABLE `acc_transactional_coa` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `economic_code` int(7) UNSIGNED NOT NULL,
  `group_name` enum('asset','revenue','expense','liability','capital','drawing') NOT NULL,
  `sub_group_name` enum('fixed_asset','current_asset','direct_income','indirect_income','operating_expense','capital_expense','loan_payable','tax_payable','owner_drawing','owner_invest','donation','customer_deposit_payable','unexcepted_expense','asset_depreciation') DEFAULT NULL,
  `is_default` enum('yes','no') NOT NULL DEFAULT 'no',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_by_ip` varchar(15) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) UNSIGNED DEFAULT NULL,
  `updated_by_ip` varchar(15) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `acc_transactional_coa`
--

INSERT INTO `acc_transactional_coa` (`id`, `name`, `economic_code`, `group_name`, `sub_group_name`, `is_default`, `created_at`, `created_by`, `created_by_ip`, `updated_at`, `updated_by`, `updated_by_ip`, `deleted_at`, `deleted_by`) VALUES
(1, 'Investment', 6100001, 'capital', 'owner_invest', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(2, 'Donation', 6200001, 'capital', 'donation', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(3, 'Cash Account', 1210001, 'asset', 'current_asset', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(4, 'Customer Loan Principal Receivable', 1230001, 'asset', 'current_asset', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(5, 'Customer Loan Interest Receivable', 1230002, 'asset', 'current_asset', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(6, 'Computer Laptop', 1100001, 'asset', 'fixed_asset', 'no', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(7, 'Interest on customer loan', 2200001, 'revenue', 'indirect_income', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(8, 'Customer Form Fee', 2100001, 'revenue', 'direct_income', 'no', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(9, 'Utility Bill', 3100001, 'expense', 'operating_expense', 'no', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(11, 'Computer Purchase', 3200001, 'expense', 'capital_expense', 'no', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(12, 'Customer Deposit Payable', 4100001, 'liability', 'customer_deposit_payable', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(13, 'Loan Payable', 4200001, 'liability', 'loan_payable', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(14, 'Tax Payable', 4300001, 'liability', 'tax_payable', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(15, 'Owner Drawing', 5000001, 'drawing', 'owner_drawing', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(16, 'Unexcepted Expense', 3300001, 'expense', 'unexcepted_expense', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(17, 'Asset Depreciation', 3400001, 'expense', 'asset_depreciation', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(18, 'Islami Bank Bangladesh LTD', 1220001, 'asset', 'current_asset', 'no', NULL, 0, '', NULL, NULL, '', NULL, NULL),
(19, 'Customer Due Receivable', 1230004, 'asset', 'current_asset', 'yes', NULL, 0, '', NULL, NULL, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_info`
--

CREATE TABLE `customer_info` (
  `id` int(11) UNSIGNED NOT NULL,
  `account_no` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `present_address` varchar(255) NOT NULL,
  `permanent_address` varchar(255) NOT NULL,
  `national_id` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer_info`
--

INSERT INTO `customer_info` (`id`, `account_no`, `name`, `mobile`, `email`, `present_address`, `permanent_address`, `national_id`) VALUES
(1, 1000001, 'Md Omar Faruk', '01840239203', 'omarlemua@gmail.com', '109/6,east rayer bazar,dhaka-1209', '109/6,east rayer bazar,dhaka-1209', '19943011495000254');

-- --------------------------------------------------------

--
-- Table structure for table `customer_loan_info`
--

CREATE TABLE `customer_loan_info` (
  `id` int(11) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `sanction_number` varchar(255) NOT NULL,
  `principal_amount` int(11) NOT NULL,
  `interest_amount` int(11) NOT NULL,
  `interest_percentage` int(11) NOT NULL,
  `loan_type` enum('product','money') NOT NULL DEFAULT 'money',
  `installment` int(11) NOT NULL,
  `installment_type` enum('monthly','weekly') NOT NULL DEFAULT 'monthly',
  `total_receivable` float NOT NULL DEFAULT 0,
  `paid_installment` int(11) NOT NULL DEFAULT 0,
  `paid_amount` float NOT NULL DEFAULT 0,
  `loan_sanction_date` date DEFAULT NULL,
  `installment_start_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `gender` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `dob` datetime DEFAULT NULL,
  `city` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `country` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `address`, `gender`, `image`, `phone`, `dob`, `city`, `country`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'sd', '1', '/uploads/profile/1605888701_1_avatar.jpg', '01840239203', '2020-11-20 22:07:55', 'Dhaka', 'Bangladesh', '2020-11-20 16:07:55', '2020-11-20 16:11:41', NULL),
(2, 85, NULL, NULL, '/uploads/profile/avatar.jpg', NULL, NULL, NULL, NULL, '2020-12-05 06:27:19', '2020-12-05 06:27:19', NULL),
(3, 86, NULL, NULL, '/uploads/profile/avatar.jpg', NULL, NULL, NULL, NULL, '2020-12-14 02:57:03', '2020-12-14 02:57:03', NULL),
(4, 87, NULL, NULL, '/uploads/profile/avatar.jpg', NULL, NULL, NULL, NULL, '2020-12-14 03:03:17', '2020-12-14 03:03:17', NULL),
(5, 88, NULL, NULL, '/uploads/profile/avatar.jpg', NULL, NULL, NULL, NULL, '2021-02-03 20:29:50', '2021-02-03 20:29:50', NULL),
(6, 89, NULL, NULL, '/uploads/profile/avatar.jpg', NULL, NULL, NULL, NULL, '2021-02-03 20:43:03', '2021-02-03 20:43:03', NULL),
(7, 90, NULL, NULL, '/uploads/profile/avatar.jpg', NULL, NULL, NULL, NULL, '2021-02-15 16:16:33', '2021-02-15 16:16:33', NULL),
(8, 91, NULL, NULL, '/uploads/profile/avatar.jpg', NULL, NULL, NULL, NULL, '2021-04-27 15:46:11', '2021-04-27 15:46:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_info`
--

CREATE TABLE `supplier_info` (
  `id` int(11) UNSIGNED NOT NULL,
  `account_no` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `present_address` varchar(255) NOT NULL,
  `permanent_address` varchar(255) NOT NULL,
  `national_id` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier_info`
--

INSERT INTO `supplier_info` (`id`, `account_no`, `name`, `mobile`, `email`, `present_address`, `permanent_address`, `national_id`) VALUES
(1, 1000001, 'Md Omar Faruk', '01840239203', 'omarlemua@gmail.com', '109/6,east rayer bazar,dhaka-1209', '109/6,east rayer bazar,dhaka-1209', '19943011495000254');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `email` varchar(128) CHARACTER SET utf8 NOT NULL,
  `email_verified_at` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `image` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `user_type` tinyint(1) UNSIGNED NOT NULL COMMENT '1=coordinator,2=emine,3=regular',
  `is_admin` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1=super_admin,2=system_admin',
  `project_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `access_all_project` int(11) NOT NULL DEFAULT 0,
  `coordinator` varchar(128) DEFAULT NULL,
  `emine` varchar(128) DEFAULT NULL,
  `permission` text CHARACTER SET utf8 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) UNSIGNED DEFAULT NULL,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `deleted_by` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `status`, `image`, `last_login`, `user_type`, `is_admin`, `project_id`, `access_all_project`, `coordinator`, `emine`, `permission`, `created_at`, `updated_at`, `deleted_at`, `updated_by`, `created_by`, `deleted_by`) VALUES
(1, 'admin', 'admin@anchal.com', NULL, '$2y$10$or3WhPjGdyYeOKdAlkC/AOI8H5BNpScAhTFFgzkgrbYjxzX3Jzsa6', 1, NULL, '2022-02-01 06:17:59', 0, 1, 1, 0, NULL, NULL, NULL, '2020-11-20 16:05:38', '2022-02-01 06:17:59', NULL, NULL, 1, NULL),
(2, 'Enamul Bhasa', 'enamul@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, '2021-04-26 15:03:11', 1, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:22:59', '2021-04-26 15:03:11', NULL, 1, 1, NULL),
(3, 'Fazlur Rahman', 'dfdf@dfd,com', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 1, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:22:59', '2020-12-25 21:08:11', '2020-12-25 21:08:11', NULL, 1, 1),
(4, 'Aminur Rahman', 'sds@fdf.op', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 1, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:22:59', '2020-12-25 21:08:19', '2020-12-25 21:08:19', NULL, 1, 1),
(5, 'Naym Uddin Roby bhasa', 'roby@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, '2020-12-25 21:40:14', 2, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:22:59', '2020-12-25 21:40:14', NULL, 1, 1, NULL),
(6, 'Amirul Islam', 'amirul@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:22:59', NULL, NULL, NULL, 1, NULL),
(7, 'Juel Molla', 'juel@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1110373,1110111,1110112,1110113,1110114,1110161,1110162,1110163,1110164,1110165,1110221,1110222,1110223,1110224,1110241,1110242,1110243,1110261,1110262,1110263,1110281,1110282,1110283,1110284,1110285,1110286,1110291,1110292,1110293,1110294,1110331,1110332,1110351,1110352,1110361,1110362,1110363,1110371,1110372,1110373,1110381,1110382,1110383,1110391,1110392,1110393', '2020-11-20 19:22:59', '2021-02-03 20:45:37', NULL, 1, 1, NULL),
(8, 'Rasel Mia', 'rasel@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1110574,1110794,1110511,1110512,1110513,1110514,1110515,1110516,1110541,1110542,1110551,1110552,1110561,1110562,1110563,1110792,1110564,1110571,1110572,1110573,1110581,1110582,1110583,1110584,1110585,1110591,1110592,1110593,1110594,1110711,1110712,1110721,1110722,1110723,1110724,1110725,1110741,1110742,1110743,1110744,1110751,1110752,1110781,1110782,1110783,1110784,1110785,1110791,1110792,1110793', '2020-11-20 19:22:59', '2021-02-03 20:45:29', NULL, 1, 1, NULL),
(9, 'Khademul Malek', 'khademul@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1110181,1110182,1110183,1110191,1110192,1110193,1110411,1110412,1110413,1110414,1110415,1110441,1110442,1110443,1110444,1110445,1110446,1110451,1110452,1110453,1110454,1110461,1110462,1110463,1110464,1110471,1110481,1110482,1110483,1110484,1110485,1110486,1110491,1110492,1110493,1110495,1110631,1110632,1110641,1110642,1110651,1110661', '2020-11-20 19:22:59', '2021-02-03 20:45:15', NULL, 1, 1, NULL),
(10, 'Abdus Salim', 'abdussalim@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1210421,1210422,1210423,1210441,1210442,1210481,1210482,1210483,1210484,1210491,1210492,1210493,1210494,1211221,1211222,1211223,1211231,1211232,1211233,1211234,1211235,1211236,1211237,1211238,1211291,1211292,1211293', '2020-11-20 19:22:59', '2021-02-03 20:46:12', NULL, 1, 1, NULL),
(11, 'Jenis Akter Suma', 'suma@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1211121,1211122,1211123,1211124,1211125,1211131,1211132,1211133,1211141,1211142,1211151,1211152,1211161,1211162,1211163,1211164,1211171,1211181,1211182,1210731,1210732,1210741,1210742,1210743,1210761,1210762,1210763,1210781,1210782', '2020-11-20 19:22:59', '2021-02-03 20:45:56', NULL, 1, 1, NULL),
(12, 'Khyrun Nahar', 'khyrun@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1210911,1210912,1210921,1210922,1210923,1210924,1210925,1210931,1210932,1210933,1210941,1210942,1210943,1210961,1210981,1210982,1210983,1210984,1210991,1210992,1210993,1210994,1210995,1210131,1210132,1210133,1210134,1210135,1210136,1210141,1210142,1210143,1210144,1210145,1210181,1210182,1210183,1210184,1210191,1210192,1210193,1210194,1210195', '2020-11-20 19:22:59', '2021-02-03 20:45:00', NULL, 1, 1, NULL),
(13, 'Nazmun Nahar', 'nazmun@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1210611,1210612,1210613,1210614,1210615,1210621,1210622,1210623,1210624,1210625,1210631,1210632,1210633,1210634,1210635,1210641,1210642,1210643,1210651,1210652,1210653,1210661,1210662,1210663,1210664,1210665,1210671,1210672,1210673,1210674,1210675', '2020-11-20 19:22:59', '2021-02-03 20:47:54', NULL, 1, 1, NULL),
(14, 'Khalid Saifullah kha', 'Khalid@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1210511,1210512,1210531,1210541,1210561,1210562,1210563,1210564,1210571,1210572,1210573,1210581,1210582,1210583,1210591,1210592,1210593,1210311,1210312,1210313,1210321,1210322,1210323,1210331,1210332,1210341,1210342,1210343,1210361,1210371,1210391,1210392', '2020-11-20 19:22:59', '2021-02-03 20:47:37', NULL, 1, 1, NULL),
(15, 'Mohammad Mohsin', 'mohsin@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1210811,1210812,1210813,1210814,1210815,1210821,1210822,1210831,1210832,1210833,1210834,1210841,1210842,1210843,1210844,1210845,1210851,1210852,1210853,1210854,1210861,1210862,1210863,1210864,1210865,1210871,1210872,1210873', '2020-11-20 19:22:59', '2021-02-03 20:47:06', NULL, 1, 1, NULL),
(16, 'Md. Nizam Uddin', 'nizam@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1210211,1210212,1210221,1210222,1210223,1210231,1210232,1210241,1210242,1210243,1210251,1210252,1210253,1210254,1210271,1210272,1210273,1210273,1210281,1210282,1210291,1210292,1210293,1210294,1211041,1211042,1211043,1211044,1211045,1211051,1211052,1211053,1211054,1211081,1211082,1211083,1211091,1211092,1211093,1211094,1211095', '2020-11-20 19:23:00', '2021-02-03 20:44:38', NULL, 1, 1, NULL),
(17, 'Md. Salim mia', 'salimac@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:00', NULL, NULL, NULL, 1, NULL),
(18, 'Rupok Saha', 'rupak@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1120211,1120212,1120213,1120221,1120222,1120223,1120231,1120232,1120233,1120241,1120242,1120243,1120261,1120262,1120291,1120292,1120521,1120522,1120531,1120532,1120533,1120534,1120551,1120552,1120553,1120581,1120582,1120583,1120591,1120592,1120593', '2020-11-20 19:23:00', '2021-02-03 20:46:48', NULL, 1, 1, NULL),
(19, 'Zehad Hossain', 'zehad@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1120731,1120732,1120733,1120734,1120735,1120741,1120761,1120762,1120763,1120771,1120773,1120772,1120781,1120782,1120791,1120792,1120793,1120621,1120623,1120621,1120623,1120631,1120632,1120633,1120651,1120652,1120653,1120661,1120662,1120671,1120681,1120682,1120691,1120692,1120693', '2020-11-20 19:23:00', '2021-02-03 20:44:29', NULL, 1, 1, NULL),
(20, 'Masuda Akter Poly', 'masuda@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1120865,1120863,1120121,1120113,1120114,1120124,1120131,1120132,1120133,1120141,1120143,1120151,1120152,1120153,1120171,1120811,1120812,1120813,1120821,1120822,1120831,1120832,1120833,1120851,1120852,1120853,1120854,1120855,1120856,1120861,1120862,1120863,1120864,1120866,1120881', '2020-11-20 19:23:00', '2021-02-03 20:44:17', NULL, 1, 1, NULL),
(21, 'Omor faruk', 'faruk@anchalbhasa.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 1, 0, '2,89', '5,22', '1120321,1120322,1120323,1120341,1120351,1120352,1120353,1120361,1120362,1120371,1120372,1120374,1120376,1120391,1120392,1120421,1120422,1120423,1120431,1120432,1120433,1120441,1120442,1120451,1120453,1120461,1120462,1120472,1120474,1120475', '2020-11-20 19:23:00', '2021-02-03 20:46:28', NULL, 1, 1, NULL),
(22, 'Angshuman Sarker Bhasa', 'angshuman@anchalbhasa.org', NULL, '$2y$10$D7E7CJcT2Km8y6p6.Zu44Ob2.1b28r0.huQDfbeU4lctSdQFY4Q7C', 1, NULL, '2021-02-04 15:05:29', 2, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:00', '2021-02-04 15:05:29', NULL, 22, 1, NULL),
(23, 'Angshuman Sarker', 'angshuman@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 2, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:00', '2020-12-25 21:06:47', '2020-12-25 21:06:47', NULL, 1, 1),
(24, '', '', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:00', '2020-12-25 21:05:42', '2020-12-25 21:05:42', NULL, 1, 1),
(25, '', '', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:00', '2020-12-25 21:05:52', '2020-12-25 21:05:52', NULL, 1, 1),
(26, '', '', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:00', '2020-12-25 21:06:01', '2020-12-25 21:06:01', NULL, 1, 1),
(27, '', '', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:00', '2020-12-25 21:07:15', '2020-12-25 21:07:15', NULL, 1, 1),
(28, 'Md Rasel Mia', 'rasel@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:00', '2020-12-25 21:06:32', '2020-12-25 21:06:32', NULL, 1, 1),
(29, 'Nazmun Nahar', 'Nazmunnahar@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:00', '2020-12-25 21:06:12', '2020-12-25 21:06:12', NULL, 1, 1),
(30, '', 'khyrun@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:00', '2020-12-25 21:08:03', '2020-12-25 21:08:03', NULL, 1, 1),
(31, '', 'khyrun@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:01', '2020-12-25 21:05:30', '2020-12-25 21:05:30', NULL, 1, 1),
(32, '', 'khyrun@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:01', '2020-12-25 21:04:36', '2020-12-25 21:04:36', NULL, 1, 1),
(33, '', 'khyrun@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:01', '2020-12-25 21:04:46', '2020-12-25 21:04:46', NULL, 1, 1),
(34, '', 'khyrun@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:01', '2020-12-25 21:04:56', '2020-12-25 21:04:56', NULL, 1, 1),
(35, '', '', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:01', '2020-12-25 21:05:04', '2020-12-25 21:05:04', NULL, 1, 1),
(36, '', 'khyrun@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 1, 0, NULL, NULL, NULL, '2020-11-20 19:23:01', '2020-12-25 21:05:12', '2020-12-25 21:05:12', NULL, 1, 1),
(37, 'Roby', 'roby@anchalukam.org', NULL, '$2y$10$mMKIf56OH75xqHQClfe68O2kvhzcg5Z2sCrtQE/YpXTPXs5z6TxU2', 1, NULL, '2021-01-18 20:15:59', 2, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:48', '2021-01-18 20:15:59', NULL, 37, 1, NULL),
(38, 'Enamul', 'enamul@anchalukam.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, '2021-04-26 15:34:02', 1, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:48', '2021-04-26 15:34:02', NULL, 1, 1, NULL),
(39, 'karim', 'karim@gmail.com', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 3, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:48', '2020-12-25 19:36:27', '2020-12-25 19:36:27', NULL, 1, 1),
(40, 'Md.Mijanur Rahaman', 'mijanur@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, '2020-11-21 07:04:30', 1, 0, 2, 0, NULL, NULL, '2211193', '2020-11-20 19:24:48', '2020-12-25 19:30:04', NULL, 1, 1, NULL),
(41, 'Amirul Islam', 'amirul@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 2, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:48', '2020-12-25 19:31:39', NULL, NULL, 1, NULL),
(42, 'Imran Nazir', 'imran@anchalukam.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, '2020-12-25 05:53:40', 3, 0, 2, 0, '38,88', '37,45,91', '2211111,2211112,2211113,2211071,2211072,2211073,2211021,2211371,2211372,2211373,2211191,2211192,2211193,2211194,2211031,2211032,2211033,2211034,2211061,2211062,2211063,2211022,2211023,2211024,2211012,2211013,2211014,2211352,2211353,2211374,2211362,2211011,2211351,2211361,2211195', '2020-11-20 19:24:48', '2021-04-27 15:53:26', NULL, 1, 1, NULL),
(43, 'Motaher Hossain  ', 'motaher@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 2, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:48', '2020-12-25 19:31:38', NULL, NULL, 1, NULL),
(44, 'Symon hasan', 'symon@anchalukam.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 2, 0, '38,88', '37,45,91', '2110683,2110421,2110422,2110431,2110521,2110621,2110681,2110682,2110731,2110771,2110432,2110433,2110434,2110522,2110532,2110533,2110534,2110531,2110611,2110671,2110691,2110692,2110693,2110732,2110762,2110761,2110531,2110532,2110533,2110534,2110611,2110432,2110433,2110434,2110522,2110671,2110691,2110692,2110693,2110732,2110761,2110762,2110522,2110671,2110691,2110692,2110693,2110732,2110761,2110762', '2020-11-20 19:24:48', '2021-04-27 15:51:28', NULL, 1, 1, NULL),
(45, 'Angshuman Saker', 'angshuman@anchalukam.org', NULL, '$2y$10$pVuQPVhyliDhXpw5bvqqKeLM/yibW8f0DVNBgOOnDKkBL5cy6Sglq', 1, NULL, '2021-02-04 15:42:24', 2, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:48', '2021-02-04 15:42:24', NULL, 45, 1, NULL),
(46, 'Shahinur Rahman', 'shahin@anchalukam.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 2, 0, '38,88', '37,45,91', '2210466,2210451,2210452,2210453,2210454,2210431,2210432,2210433,2210471,2210472,2211391,2211392,2210411,2210412,2210413,2210455,2210456,2210461,2210462,2210463,2210464,2210434,2210473,2211381,2210951,2210952,2210971,2211393,2210414,2210435,2211394,2210972,2210973,2210465,2210953', '2020-11-20 19:24:48', '2021-04-27 15:51:43', NULL, 1, 1, NULL),
(47, 'Khuku mani', 'khuku@anchalukam.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 2, 0, '38,88', '37,45,91', '2120563,2120573,2120843,2120891,2120253,2120271,2120161,2120181,2120182,2120191,2120511,2120573,2120561,2120571,2120841,2120871,2120891,2120251,2120271,2120192,2120512,2120541,2120562,2120572,2120842,2120872,2120892,2120252,2120272,2120513,2120563,2120573,2120563,2120573,2120843,2120253,2120891,2120271,2120281,2120282,2120542', '2020-11-20 19:24:48', '2022-01-10 17:09:15', NULL, 1, 1, NULL),
(48, 'Alomgir Hasan', 'alomgir@anchalukam.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 2, 0, '38,88', '37,45,91', '2110172,2110121,2110131,2110132,2110133,2110151,2110171,2110211,2110231,2110232,2110271,2110311,2110341,2110342,2110141,2110142,2110251,2110252,2110253,2110272,2110273,2110274,2110312,2110321,2110141,2110142,2110152,2110251,2110252,2110253,2110272,2110273,2110274,2110312,2110321', '2020-11-20 19:24:49', '2021-04-27 15:53:08', NULL, 1, 1, NULL),
(49, 'Tarikul Hassan', 'tarik@anchalukam.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 2, 0, '38,88', '37,45,91', '2210771,2210772,2210773,2210774,2210791,2210792,2210793,2211431,2211441,2210381,2210712,2210713,2210722,2210723,2210752,2210753,2210754,2211422,2211423,2211432,2211433,2211442,2211443,2210352,2210353,2210382,2210383,2210384,2210794,2210711,2210721,2210751,2211421,2210775,2210776,2210777,2210382,2210383,2210384,2210352,2210353,2210795,2210351,2211321,2211331,2211332,2211341,2211342,2211343,2211344', '2020-11-20 19:24:49', '2021-04-27 15:51:07', NULL, 1, 1, NULL),
(50, 'Abu layes md. Jubair', 'jubair@anchalukam.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 2, 0, '38,88', '37,45,91', '2211274,2211271,2211272,2211261,2211262,2211263,2211264,2211265,2211281,2210881,2210891,2211273,2211241,2211242,2211243,2211244,2211245,2211246,2211282,2211283,2211284,2211212,2211213,2211214,2211215,2211216,2211211,2211251,2211252,2211253,2210882,2210883,2210892,2210893', '2020-11-20 19:24:49', '2021-04-27 15:52:11', NULL, 1, 1, NULL),
(51, 'Mukta rani', 'mukta@anchalukam.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 2, 0, '38,88', '37,45,91', '2210152,2210682,2210171,2210172,2210173,2210161,2210151,2210691,2210692,2210681,2210121,2210122,2210123,2210162,2210163,2210164,2210111,2210693,2210694,2210695,2210696,2210683,2210684,2210685,2210686,2211491,2211492,2210153,2210154,2211493,2210174,2210165,2210166', '2020-11-20 19:24:49', '2021-04-27 15:52:50', NULL, 1, 1, NULL),
(52, 'rehena parvin ani', 'ani@anchalukam.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 2, 0, '38,88', '37,45,91', '2120311,2120381,2120331,2120711,2120721,2120751,2120411,2120481,2120491,2120611,2120641,2120312,2120382,2120332,2120712,2120722,2120752,2120412,2120492,2120612,2120642,2120313,2120413,2120493,2120643,2120723', '2020-11-20 19:24:49', '2021-04-27 15:52:26', NULL, 1, 1, NULL),
(53, 'Mahbur Alam', 'mahbur@anchalukam.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 1, NULL, NULL, 3, 0, 2, 0, '38,88', '37,45,91', '2210552,2210521,2211461,2211441,2210211,2210551,2210261,2211411,2211421,2211431,2211451,2210522,2211462,2211442,2210212,2210551,2210262,2211432,2211452,2210523,2211463,2211454,2210213,2210553,2210263,2210524,2210214,2211453,2211471,2211472,2211481,2211473,2211482,2211483', '2020-11-20 19:24:49', '2021-04-27 15:50:51', NULL, 1, 1, NULL),
(54, 'Rehana Parveen', 'rehana.parveen@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:49', '2020-12-25 19:31:36', NULL, NULL, 1, NULL),
(55, 'Aminur Rahman', 'aminur@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 1, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:49', '2020-12-25 19:31:32', NULL, NULL, 1, NULL),
(56, 'Fazlur Rahman', 'dfd@fd.nm', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 1, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:49', '2020-12-25 19:31:30', NULL, NULL, 1, NULL),
(57, 'Amirul Islam', 'amirul@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:49', '2020-12-25 19:31:27', NULL, NULL, 1, NULL),
(58, 'Angshuman Sarker', 'sarker@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:49', '2020-12-25 19:32:37', '2020-12-25 19:32:37', NULL, 1, 1),
(59, 'Angshuman Sarker', 'sarker@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:49', '2020-12-25 19:32:53', '2020-12-25 19:32:53', NULL, 1, 1),
(60, 'Jenis Akter Suma ', 'suma@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:49', '2020-12-25 19:33:08', '2020-12-25 19:33:08', NULL, 1, 1),
(61, 'Jenis Akter Suma ', 'suma@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:49', '2020-12-25 19:33:23', '2020-12-25 19:33:23', NULL, 1, 1),
(62, 'Abdus Salim', 'salims@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:49', '2020-12-25 19:34:53', '2020-12-25 19:34:53', NULL, 1, 1),
(63, 'Jenis Akter Suma ', 'suma@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:49', '2020-12-25 19:33:31', '2020-12-25 19:33:31', NULL, 1, 1),
(64, 'Jenis Akter Suma ', 'suma@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:33:47', '2020-12-25 19:33:47', NULL, 1, 1),
(65, 'Jenis Akter Suma ', 'suma@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:33:36', '2020-12-25 19:33:36', NULL, 1, 1),
(66, 'Jenis Akter Suma ', 'suma@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:33:43', '2020-12-25 19:33:43', NULL, 1, 1),
(67, 'Abdus Salim', 'salims@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:35:02', '2020-12-25 19:35:02', NULL, 1, 1),
(68, 'Jenis akter suma', 'suma@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:33:54', '2020-12-25 19:33:54', NULL, 1, 1),
(69, 'Jenis akter suma', 'suma@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:34:02', '2020-12-25 19:34:02', NULL, 1, 1),
(70, 'Jenis akter suma', 'suma@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:34:08', '2020-12-25 19:34:08', NULL, 1, 1),
(71, 'Jenis Akter Suma ', 'suma@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:34:17', '2020-12-25 19:34:17', NULL, 1, 1),
(72, 'Abdus Salim ', 'Salim1@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:34:23', '2020-12-25 19:34:23', NULL, 1, 1),
(73, 'Jenis Akter Suma ', 'suma@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:34:27', '2020-12-25 19:34:27', NULL, 1, 1),
(74, 'zehad hossain', 'zehad@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:34:35', '2020-12-25 19:34:35', NULL, 1, 1),
(75, 'rupak saha', 'rupak@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:34:41', '2020-12-25 19:34:41', NULL, 1, 1),
(76, 'omorfaruk', 'faruk@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:34:46', '2020-12-25 19:34:46', NULL, 1, 1),
(77, 'masuda', 'masuda@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:34:58', '2020-12-25 19:34:58', NULL, 1, 1),
(78, 'Khyrun Nahar', 'khyrun@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:50', '2020-12-25 19:36:16', '2020-12-25 19:36:16', NULL, 1, 1),
(79, 'Nazmun Nahar', 'nazmun@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:51', '2020-12-25 19:36:05', '2020-12-25 19:36:05', NULL, 1, 1),
(80, 'Khalid Saifullah kha', 'Khalid@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:51', '2020-12-25 19:35:09', '2020-12-25 19:35:09', NULL, 1, 1),
(81, 'Mohammad Mohsin', 'mohsin@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:51', '2020-12-25 19:35:31', '2020-12-25 19:35:31', NULL, 1, 1),
(82, 'Md. Nizam Uddin', 'nizam@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:51', '2020-12-25 19:36:01', '2020-12-25 19:36:01', NULL, 1, 1),
(83, 'Md. Salim mia', 'salimac@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:51', '2020-12-25 19:36:09', '2020-12-25 19:36:09', NULL, 1, 1),
(84, 'Zehad Hossain ', 'zehad@ciprb.org', NULL, '$2y$10$XMlDRdlGzMakvYoFU7xKIeuOztRyziJBkXQDG1/2yH5nhPwGsbLNe', 0, NULL, NULL, 0, 0, 2, 0, NULL, NULL, NULL, '2020-11-20 19:24:51', '2020-12-25 19:35:49', '2020-12-25 19:35:49', NULL, 1, 1),
(85, 'test', 'test@anchal.com', NULL, '$2y$10$oxUEeK.zoqFZBmgx5F8IjuRJ6s4yfuxyYu9kqz9AC4sER1Ha8.oVm', 0, NULL, '2020-12-10 19:02:08', 2, 0, 1, 0, NULL, NULL, '1210923,1210442', '2020-12-05 06:27:19', '2020-12-26 04:30:06', '2020-12-26 04:30:06', 1, 1, 1),
(86, 'test1', 'test1@app.com', NULL, '$2y$10$8Hxk5j75GNwufJmqV9rfEuqWlTbkQl/p6uyfJPLZNssRjyUastcAi', 0, NULL, '2020-12-14 03:31:08', 2, 0, 1, 0, NULL, NULL, NULL, '2020-12-14 02:57:03', '2020-12-26 04:30:13', '2020-12-26 04:30:13', 1, 1, 1),
(87, 'test2', 'test2@app.com', NULL, '$2y$10$FG7JlVM79AC88HZOUcQJceF7V3hLij9tNrTwMzBjJKILa50Et/C2W', 0, NULL, '2020-12-14 03:49:19', 3, 0, 1, 0, NULL, NULL, NULL, '2020-12-14 03:03:17', '2020-12-26 04:30:20', '2020-12-26 04:30:20', 1, 1, 1),
(88, 'Farukh Ahmed', 'farukh@anchalukam.org', NULL, '$2y$10$HuchvJhg8igOTPUTmz2RV.zsaYd5YOE3Rcz55mTqTcZBF8Xts/uW6', 1, NULL, '2021-02-03 20:31:13', 1, 0, 2, 0, NULL, NULL, NULL, '2021-02-03 20:29:50', '2021-02-03 20:31:13', NULL, 1, 1, NULL),
(89, 'Farukh Ahmed', 'farukh@anchalbhasa.org', NULL, '$2y$10$MZUcoI7fYPUsLPqkQwEQyOo.e4bCX0iJ8u.aOh.ffdNE8pD/27sv6', 1, NULL, '2021-02-03 20:49:02', 1, 0, 1, 0, NULL, NULL, NULL, '2021-02-03 20:43:03', '2021-02-03 20:49:02', NULL, NULL, 1, NULL),
(90, 'Al Amin', 'al-amin@ciprb.org', NULL, '$2y$10$zNfJi9pssNpHKRpKqVJUIeU5T5T331DvD1iSHzEM0Q3Hu799FfwMu', 1, NULL, '2021-02-15 21:10:43', 2, 0, 3, 0, NULL, NULL, NULL, '2021-02-15 16:16:33', '2021-02-15 21:10:43', NULL, 1, 1, NULL),
(91, 'Md. Abul Borkat', 'borkat@anchalukam.org', NULL, '$2y$10$xKxSNbTJyhmvMZ4z2JuhPu.taAn06ywirW7GxWBhTJz0T3/2I8IPi', 1, NULL, '2021-06-02 17:07:17', 2, 0, 2, 0, NULL, NULL, NULL, '2021-04-27 15:46:11', '2021-06-02 17:07:17', NULL, 91, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acc_customer_ledger`
--
ALTER TABLE `acc_customer_ledger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_general_ledger`
--
ALTER TABLE `acc_general_ledger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_transactional_coa`
--
ALTER TABLE `acc_transactional_coa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_info`
--
ALTER TABLE `customer_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_loan_info`
--
ALTER TABLE `customer_loan_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `phone` (`phone`);

--
-- Indexes for table `supplier_info`
--
ALTER TABLE `supplier_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `password` (`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acc_customer_ledger`
--
ALTER TABLE `acc_customer_ledger`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_general_ledger`
--
ALTER TABLE `acc_general_ledger`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `acc_transactional_coa`
--
ALTER TABLE `acc_transactional_coa`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `customer_info`
--
ALTER TABLE `customer_info`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_loan_info`
--
ALTER TABLE `customer_loan_info`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `supplier_info`
--
ALTER TABLE `supplier_info`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
