-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2026 at 10:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finnserv`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `mailing_name` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) NOT NULL DEFAULT 'India',
  `pincode` varchar(10) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `gstin` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `mailing_name`, `address`, `state`, `country`, `pincode`, `phone`, `email`, `gstin`, `created_at`, `updated_at`) VALUES
(1, 'FinnServ Demo Company', 'FinnServ Accounting & Business', 'Navi Mumbai, Maharashtra, India', 'Maharashtra', 'India', '400000', NULL, NULL, NULL, '2026-08-13 06:00:48', '2026-08-17 14:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `financial_years`
--

CREATE TABLE `financial_years` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `financial_years`
--

INSERT INTO `financial_years` (`id`, `company_id`, `name`, `start_date`, `end_date`, `is_active`, `created_at`) VALUES
(1, 1, '2026-27', '2026-04-01', '2027-03-31', 1, '2026-08-13 06:01:11'),
(2, 1, '2027-28', '2027-04-01', '2028-03-31', 0, '2026-08-15 08:19:16');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `nature` enum('Assets','Liabilities','Income','Expenses') NOT NULL,
  `affects_gross_profit` tinyint(1) NOT NULL DEFAULT 0,
  `behaves_like_subledger` tinyint(1) NOT NULL DEFAULT 0,
  `net_debit_credit_reporting` tinyint(1) NOT NULL DEFAULT 0,
  `used_for_calculation` tinyint(1) NOT NULL DEFAULT 0,
  `allocation_method` enum('Not Applicable','Appropriate by Qty','Appropriate by Value') NOT NULL DEFAULT 'Not Applicable',
  `hsn_sac_details_mode` enum('As per Company/Group','Specify Details Here','Use GST Classification') NOT NULL DEFAULT 'As per Company/Group',
  `hsn_sac` varchar(20) DEFAULT NULL,
  `hsn_sac_description` varchar(255) DEFAULT NULL,
  `gst_rate_details_mode` enum('As per Company/Group','Specify Details Here','Specify Slab-Based Rates','Use GST Classification') NOT NULL DEFAULT 'As per Company/Group',
  `taxability_type` varchar(50) DEFAULT NULL,
  `gst_rate` decimal(5,2) DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `company_id`, `name`, `alias`, `parent_id`, `nature`, `affects_gross_profit`, `behaves_like_subledger`, `net_debit_credit_reporting`, `used_for_calculation`, `allocation_method`, `hsn_sac_details_mode`, `hsn_sac`, `hsn_sac_description`, `gst_rate_details_mode`, `taxability_type`, `gst_rate`, `is_system`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Capital Account', NULL, NULL, 'Liabilities', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:31:01'),
(2, 1, 'Current Assets', NULL, NULL, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:31:01'),
(3, 1, 'Current Liabilities', NULL, NULL, 'Liabilities', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:31:01'),
(4, 1, 'Fixed Assets', NULL, NULL, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:31:01'),
(5, 1, 'Investments', NULL, NULL, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:31:01'),
(7, 1, 'Income (Direct)', 'Income (Direct)', NULL, 'Income', 1, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:26:16'),
(8, 1, 'Income (Indirect)', 'Income (Indirect)', NULL, 'Income', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:26:16'),
(9, 1, 'Direct Expenses', NULL, NULL, 'Expenses', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:31:01'),
(10, 1, 'Indirect Expenses', NULL, NULL, 'Expenses', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:31:01'),
(11, 1, 'Purchase Accounts', NULL, NULL, 'Expenses', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:31:01'),
(12, 1, 'Sales Accounts', NULL, NULL, 'Income', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:31:01'),
(13, 1, 'Duties & Taxes', NULL, 3, 'Liabilities', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:47:59'),
(14, 1, 'Sundry Debtors', NULL, 2, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:47:59'),
(15, 1, 'Sundry Creditors', NULL, 3, 'Liabilities', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:47:59'),
(16, 1, 'Cash-in-Hand', NULL, 2, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:47:59'),
(17, 1, 'Bank Accounts', NULL, 2, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 0, '2026-08-13 06:01:55', '2026-08-19 13:47:59'),
(18, 1, 'Branch / Divisions', 'Branch / Divisions', NULL, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 10, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(19, 1, 'Direct Incomes', 'Income (Direct)', NULL, 'Income', 1, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 60, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(20, 1, 'Indirect Incomes', 'Income (Indirect)', NULL, 'Income', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 90, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(21, 1, 'Loans (Liability)', 'Loans (Liability)', NULL, 'Liabilities', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 110, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(22, 1, 'Misc. Expenses (ASSET)', 'Misc. Expenses (ASSET)', NULL, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 120, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(23, 1, 'Suspense A/c', 'Suspense A/c', NULL, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 150, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(25, 1, 'Deposits (Asset)', 'Deposits (Asset)', 2, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 230, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(26, 1, 'Loans & Advances (Asset)', 'Loans & Advances (Asset)', 2, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 240, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(27, 1, 'Stock-in-Hand', 'Stock-in-Hand', 2, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 250, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(28, 1, 'Provisions', 'Provisions', 3, 'Liabilities', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 320, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(29, 1, 'Reserves & Surplus', 'Reserves & Surplus', 1, 'Liabilities', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 410, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(30, 1, 'Retained Earnings', 'Retained Earnings', 1, 'Liabilities', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 420, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(32, 1, 'Bank OCC A/c', 'Bank OCC A/c', 21, 'Liabilities', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 510, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(33, 1, 'Bank OD A/c', 'Bank OD A/c', 21, 'Liabilities', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 520, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(34, 1, 'Secured Loans', 'Secured Loans', 21, 'Liabilities', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 530, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(35, 1, 'Unsecured Loans', 'Unsecured Loans', 21, 'Liabilities', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 540, '2026-08-19 13:22:50', '2026-08-19 13:22:50'),
(39, 1, 'Expenses (Direct)', 'Expenses (Direct)', NULL, 'Expenses', 1, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 170, '2026-08-19 13:26:16', '2026-08-19 13:26:16'),
(40, 1, 'Expenses (Indirect)', 'Expenses (Indirect)', NULL, 'Expenses', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 1, 180, '2026-08-19 13:26:16', '2026-08-19 13:26:16'),
(43, 1, 'Test Group', 'TG-FINAL', NULL, 'Assets', 0, 0, 0, 0, 'Not Applicable', 'As per Company/Group', NULL, NULL, 'As per Company/Group', NULL, NULL, 0, 9999, '2026-08-19 14:12:59', '2026-08-19 14:15:51');

-- --------------------------------------------------------

--
-- Table structure for table `ledgers`
--

CREATE TABLE `ledgers` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `opening_balance_type` enum('Debit','Credit') NOT NULL DEFAULT 'Debit',
  `mailing_name` varchar(150) DEFAULT NULL,
  `mailing_address` text DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) NOT NULL DEFAULT 'India',
  `pincode` varchar(10) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `gstin` varchar(15) DEFAULT NULL,
  `provide_bank_details` tinyint(1) NOT NULL DEFAULT 0,
  `bank_name` varchar(150) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `branch_name` varchar(150) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ledgers`
--

INSERT INTO `ledgers` (`id`, `company_id`, `group_id`, `name`, `alias`, `opening_balance`, `opening_balance_type`, `mailing_name`, `mailing_address`, `state`, `country`, `pincode`, `phone`, `email`, `gstin`, `provide_bank_details`, `bank_name`, `account_number`, `ifsc_code`, `branch_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 16, 'Cash', NULL, 0.00, 'Debit', NULL, NULL, NULL, 'India', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, '2026-08-13 06:02:46', '2026-08-13 06:02:46'),
(2, 1, 17, 'Bank Account', NULL, 0.00, 'Debit', NULL, NULL, NULL, 'India', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, '2026-08-13 06:02:46', '2026-08-13 06:02:46'),
(3, 1, 12, 'Sales', NULL, 0.00, 'Credit', NULL, NULL, NULL, 'India', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, '2026-08-13 06:02:46', '2026-08-13 06:02:46'),
(4, 1, 11, 'Purchase', NULL, 0.00, 'Debit', NULL, NULL, NULL, 'India', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, '2026-08-13 06:02:46', '2026-08-13 06:02:46'),
(5, 1, 13, 'CGST', NULL, 0.00, 'Credit', NULL, NULL, NULL, 'India', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, '2026-08-13 06:02:46', '2026-08-13 06:02:46'),
(6, 1, 13, 'SGST', NULL, 0.00, 'Credit', NULL, NULL, NULL, 'India', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, '2026-08-13 06:02:46', '2026-08-13 06:02:46'),
(7, 1, 13, 'IGST', NULL, 0.00, 'Credit', NULL, NULL, NULL, 'India', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, '2026-08-13 06:02:46', '2026-08-13 06:02:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Manager','Accountant','Viewer') NOT NULL DEFAULT 'Viewer',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `company_id`, `name`, `username`, `password`, `role`, `is_active`, `created_at`) VALUES
(1, 1, 'System Administrator', 'admin', '$2y$10$f1tSzV99ILNZw/x6tS2CFOYb0/aUnKZlLLnvzjhDMYRXWieEGhjQ.', 'Admin', 1, '2026-08-13 06:01:31');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `voucher_type_id` int(10) UNSIGNED NOT NULL,
  `voucher_number` varchar(50) NOT NULL,
  `voucher_date` date NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `narration` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_entries`
--

CREATE TABLE `voucher_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `ledger_id` int(10) UNSIGNED NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `narration` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_types`
--

CREATE TABLE `voucher_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `abbreviation` varchar(10) NOT NULL,
  `numbering_method` enum('Automatic','Manual') NOT NULL DEFAULT 'Automatic',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voucher_types`
--

INSERT INTO `voucher_types` (`id`, `company_id`, `name`, `abbreviation`, `numbering_method`, `created_at`) VALUES
(1, 1, 'Payment', 'PAY', 'Automatic', '2026-08-13 06:02:25'),
(2, 1, 'Receipt', 'REC', 'Automatic', '2026-08-13 06:02:25'),
(3, 1, 'Contra', 'CON', 'Automatic', '2026-08-13 06:02:25'),
(4, 1, 'Journal', 'JRN', 'Automatic', '2026-08-13 06:02:25'),
(5, 1, 'Sales', 'SAL', 'Automatic', '2026-08-13 06:02:25'),
(6, 1, 'Purchase', 'PUR', 'Automatic', '2026-08-13 06:02:25'),
(7, 1, 'Sales Return', 'SR', 'Automatic', '2026-08-13 06:02:25'),
(8, 1, 'Purchase Return', 'PR', 'Automatic', '2026-08-13 06:02:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `financial_years`
--
ALTER TABLE `financial_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_company_financial_year` (`company_id`,`name`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_company_group` (`company_id`,`name`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `ledgers`
--
ALTER TABLE `ledgers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_company_ledger` (`company_id`,`name`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_company_username` (`company_id`,`username`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_voucher_number` (`company_id`,`voucher_type_id`,`voucher_number`),
  ADD KEY `voucher_type_id` (`voucher_type_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `voucher_entries`
--
ALTER TABLE `voucher_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_id` (`voucher_id`),
  ADD KEY `ledger_id` (`ledger_id`);

--
-- Indexes for table `voucher_types`
--
ALTER TABLE `voucher_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_company_voucher_type` (`company_id`,`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `financial_years`
--
ALTER TABLE `financial_years`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `ledgers`
--
ALTER TABLE `ledgers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher_entries`
--
ALTER TABLE `voucher_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher_types`
--
ALTER TABLE `voucher_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `financial_years`
--
ALTER TABLE `financial_years`
  ADD CONSTRAINT `financial_years_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `groups_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `groups` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ledgers`
--
ALTER TABLE `ledgers`
  ADD CONSTRAINT `ledgers_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ledgers_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `vouchers_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vouchers_ibfk_2` FOREIGN KEY (`voucher_type_id`) REFERENCES `voucher_types` (`id`),
  ADD CONSTRAINT `vouchers_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `voucher_entries`
--
ALTER TABLE `voucher_entries`
  ADD CONSTRAINT `voucher_entries_ibfk_1` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_entries_ibfk_2` FOREIGN KEY (`ledger_id`) REFERENCES `ledgers` (`id`);

--
-- Constraints for table `voucher_types`
--
ALTER TABLE `voucher_types`
  ADD CONSTRAINT `voucher_types_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
