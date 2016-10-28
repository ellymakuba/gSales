-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 20, 2016 at 11:26 AM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `webafriqdigitalpartners`
--

-- --------------------------------------------------------

--
-- Table structure for table `custmarried`
--

CREATE TABLE IF NOT EXISTS `custmarried` (
  `custmarried_id` int(11) NOT NULL AUTO_INCREMENT,
  `custmarried_status` varchar(15) NOT NULL,
  PRIMARY KEY (`custmarried_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `custmarried`
--

INSERT INTO `custmarried` (`custmarried_id`, `custmarried_status`) VALUES
(0, 'N/A'),
(1, 'Single'),
(2, 'Married'),
(3, 'Widowed'),
(4, 'Divorced');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_no` varchar(50) DEFAULT NULL,
  `cust_name` varchar(100) DEFAULT NULL,
  `cust_dob` int(11) DEFAULT NULL,
  `custsex_id` int(11) DEFAULT NULL,
  `cust_address` varchar(100) DEFAULT NULL,
  `cust_phone` varchar(50) DEFAULT NULL,
  `cust_email` varchar(100) DEFAULT NULL,
  `cust_occup` varchar(50) DEFAULT NULL,
  `custmarried_id` int(11) DEFAULT NULL,
  `cust_heir` varchar(100) DEFAULT NULL,
  `cust_heirrel` varchar(50) DEFAULT NULL,
  `cust_lengthres` int(11) DEFAULT NULL,
  `cust_since` int(11) DEFAULT NULL,
  `custsick_id` int(11) DEFAULT NULL,
  `cust_lastsub` int(11) DEFAULT NULL,
  `cust_active` int(1) NOT NULL DEFAULT '0',
  `cust_lastupd` int(11) DEFAULT NULL,
  `cust_pic` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`cust_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=105 ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cust_id`, `cust_no`, `cust_name`, `cust_dob`, `custsex_id`, `cust_address`, `cust_phone`, `cust_email`, `cust_occup`, `custmarried_id`, `cust_heir`, `cust_heirrel`, `cust_lengthres`, `cust_since`, `custsick_id`, `cust_lastsub`, `cust_active`, `cust_lastupd`, `cust_pic`, `user_id`) VALUES
(13, '013/2006', 'Melania Mitchem  ', 158364000, 1, 'Kalere', '0782-380513', '', 'Clergy', 1, '', '', NULL, 1158796800, 0, 1413902400, 0, 1420070400, NULL, 1),
(16, '016/2007', 'Lulu Obando  ', -440906400, 1, 'Sempa Parish ', '0782-096008', '', 'Clergy Man', 2, '', '', NULL, 1167782400, 0, 1436424400, 1, 1420070400, NULL, 1),
(20, '020/2006', 'Berry Steve  ', -256525200, 1, 'Bombo', '0782-453477', '', 'Clergy Man', 2, '', '', NULL, 1157932800, 0, 1439950400, 1, 1427241600, NULL, 2),
(57, '057/2006', 'Ferne Munson  ', -7200, 1, 'Bweyeyo', NULL, '', 'Lay-Reader', 2, '', '', NULL, 1159826400, 0, 1471918400, 0, 1427241600, NULL, 3),
(78, '078/2007', 'Lyndia Kump  ', -872301600, 2, 'C/O DCA Kampala', NULL, '', 'Nurse', 1, '', '', NULL, 1170633600, 0, 1490062400, 1, 1420070400, NULL, 1),
(82, '082/2007', 'Shellie Bromley  ', -24544800, 1, 'Kangulumira- Mpologoma ', NULL, '', 'Teacher', 2, '', '', NULL, 1188259200, 0, 1493518400, 0, 1420070400, NULL, 1),
(84, '084/2007', 'Jean Piehl  ', 135727200, 1, 'Wobulenzi-Kigulu', NULL, '', '', 2, '', '', NULL, 1174867200, 0, 1495246400, 1, 1420070400, NULL, 1),
(101, '101', 'elly makuba', 1140912000, 1, 'eldoret', '0702970522', 'ellymkuba@gmail.com', 'programmer', 2, '', 'wife', NULL, 1458086400, 0, 1458086400, 1, 1458159719, 'uploads/photos/customers/cust101_146x190.jpg', 1),
(103, '103', 'wyclif kwauha', 1141689600, 1, 'vision', '662', '', 'engineer', 1, '', '', NULL, 1458345600, 0, 1458345600, 1, 1458390457, 'uploads/photos/customers/cust103_146x190.jpg', 1),
(104, '104', 'dancan wechuli', 1173052800, 1, 'kisumu', '14', '', 'doctor', 0, 'own', 'brother', NULL, 1458345600, 0, 1458345600, 1, 1458390810, 'uploads/photos/customers/cust104_146x190.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `custsex`
--

CREATE TABLE IF NOT EXISTS `custsex` (
  `custsex_id` int(11) NOT NULL AUTO_INCREMENT,
  `custsex_name` varchar(50) NOT NULL,
  PRIMARY KEY (`custsex_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `custsex`
--

INSERT INTO `custsex` (`custsex_id`, `custsex_name`) VALUES
(1, 'Male'),
(2, 'Female'),
(3, 'Couple'),
(4, 'Family'),
(5, 'Group'),
(6, 'Institution'),
(7, 'Business');

-- --------------------------------------------------------

--
-- Table structure for table `custsick`
--

CREATE TABLE IF NOT EXISTS `custsick` (
  `custsick_id` int(11) NOT NULL AUTO_INCREMENT,
  `custsick_name` varchar(50) NOT NULL,
  `custsick_risk` int(11) NOT NULL,
  PRIMARY KEY (`custsick_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `custsick`
--

INSERT INTO `custsick` (`custsick_id`, `custsick_name`, `custsick_risk`) VALUES
(0, 'None', 0),
(1, 'Heart Attack', 1),
(2, 'Stroke', 1),
(3, 'Cancer', 3),
(4, 'HIV/AIDS', 3),
(5, 'Ulcer', 1);

-- --------------------------------------------------------

--
-- Table structure for table `emplmarried`
--

CREATE TABLE IF NOT EXISTS `emplmarried` (
  `emplmarried_id` int(11) NOT NULL AUTO_INCREMENT,
  `emplmarried_status` varchar(15) NOT NULL,
  PRIMARY KEY (`emplmarried_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `emplmarried`
--

INSERT INTO `emplmarried` (`emplmarried_id`, `emplmarried_status`) VALUES
(1, 'Single'),
(2, 'Married'),
(3, 'Widowed'),
(4, 'Divorced');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `empl_id` int(11) NOT NULL AUTO_INCREMENT,
  `empl_no` varchar(50) DEFAULT NULL,
  `empl_name` varchar(100) DEFAULT NULL,
  `empl_dob` int(11) DEFAULT NULL,
  `emplsex_id` int(11) NOT NULL DEFAULT '1',
  `emplmarried_id` int(11) NOT NULL,
  `empl_salary` int(11) NOT NULL,
  `empl_address` varchar(100) DEFAULT NULL,
  `empl_phone` varchar(50) DEFAULT NULL,
  `empl_email` varchar(100) DEFAULT NULL,
  `empl_in` int(11) DEFAULT NULL,
  `empl_out` int(11) DEFAULT NULL,
  `empl_lastupd` int(11) NOT NULL,
  `empl_active` int(1) NOT NULL DEFAULT '0',
  `empl_pic` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`empl_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`empl_id`, `empl_no`, `empl_name`, `empl_dob`, `emplsex_id`, `emplmarried_id`, `empl_salary`, `empl_address`, `empl_phone`, `empl_email`, `empl_in`, `empl_out`, `empl_lastupd`, `empl_active`, `empl_pic`, `user_id`) VALUES
(3, '003', 'James, Son of Zebedee', -3600, 1, 2, 0, 'Capharnaum', '01234567', '', -3600, NULL, 1457528117, 0, 'uploads/photos/employees/empl3_146x190.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `emplsex`
--

CREATE TABLE IF NOT EXISTS `emplsex` (
  `emplsex_id` int(11) NOT NULL AUTO_INCREMENT,
  `emplsex_name` varchar(20) NOT NULL,
  PRIMARY KEY (`emplsex_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `emplsex`
--

INSERT INTO `emplsex` (`emplsex_id`, `emplsex_name`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE IF NOT EXISTS `expenses` (
  `exp_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` int(6) DEFAULT NULL,
  `exptype_id` int(11) NOT NULL,
  `exp_amount` int(11) NOT NULL,
  `exp_date` int(15) DEFAULT NULL,
  `exp_text` varchar(100) DEFAULT NULL,
  `exp_recipient` varchar(75) DEFAULT NULL,
  `exp_receipt` int(11) DEFAULT NULL,
  `exp_voucher` int(11) DEFAULT NULL,
  `exp_created` int(11) DEFAULT NULL,
  `user_id` int(6) DEFAULT NULL,
  PRIMARY KEY (`exp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`exp_id`, `cust_id`, `exptype_id`, `exp_amount`, `exp_date`, `exp_text`, `exp_recipient`, `exp_receipt`, `exp_voucher`, `exp_created`, `user_id`) VALUES
(1, NULL, 1, 15000, 1453158000, 'Airtime for Manager', 'Airtel', 0, 201, 1453207875, 2),
(2, NULL, 6, 60000, 1453676400, '2GB data bundle', 'MTN', 70812, 562, 1453213126, 1),
(3, NULL, 4, 50000, 1454281200, 'Power Bill for January', 'UMEME', 21511494, 156, 1454318269, 1),
(4, NULL, 18, 51, 1451520000, 'Distributed Dividend for 2015', NULL, NULL, NULL, 1458384272, 1);

-- --------------------------------------------------------

--
-- Table structure for table `exptype`
--

CREATE TABLE IF NOT EXISTS `exptype` (
  `exptype_id` int(11) NOT NULL AUTO_INCREMENT,
  `exptype_type` varchar(50) NOT NULL,
  `exptype_short` varchar(8) NOT NULL,
  PRIMARY KEY (`exptype_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `exptype`
--

INSERT INTO `exptype` (`exptype_id`, `exptype_type`, `exptype_short`) VALUES
(1, 'Airtime', 'EXP_AIT'),
(2, 'Bank Charges', 'EXP_BCH'),
(3, 'Committee Welfare', 'EXP_COW'),
(4, 'Electricity', 'EXP_ELC'),
(5, 'Gifts', 'EXP_GFT'),
(6, 'Internet', 'EXP_ITN'),
(7, 'IT', 'EXP_ITC'),
(8, 'Motorcycle', 'EXP_MOT'),
(9, 'Office Space', 'EXP_OFF'),
(10, 'Petty Cash', 'EXP_PCA'),
(11, 'Rent', 'EXP_RNT'),
(12, 'Staff Facilitation', 'EXP_SFC'),
(13, 'Staff Welfare', 'EXP_SWF'),
(14, 'Stationary', 'EXP_STN'),
(15, 'Tax', 'EXP_TAX'),
(16, 'Transport', 'EXP_TRN'),
(17, 'Insurance', 'EXP_INS'),
(18, 'Annual Share Dividend', 'EXP_SHD'),
(19, 'Annual Savings Interest', 'EXP_INT');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE IF NOT EXISTS `fees` (
  `fee_id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_name` varchar(50) NOT NULL,
  `fee_short` varchar(8) NOT NULL,
  `fee_value` float NOT NULL,
  PRIMARY KEY (`fee_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`fee_id`, `fee_name`, `fee_short`, `fee_value`) VALUES
(1, 'Entry Fee', 'FEE_ENT', 10000),
(2, 'Withdrawal Fee', 'FEE_WDL', 1000),
(3, 'Stationary Sales', 'FEE_STS', 2000),
(4, 'Anual Subscription', 'FEE_ASB', 5000),
(5, 'Loan Fee', 'FEE_LOF', 1),
(6, 'Loan Application Fee', 'FEE_LAP', 10000),
(7, 'Loan Default Fine', 'FEE_LDF', 15000),
(8, 'Loan Interest Rate', 'FEE_LIR', 3);

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE IF NOT EXISTS `incomes` (
  `inc_id` int(11) NOT NULL AUTO_INCREMENT,
  `inctype_id` int(11) NOT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `loan_id` int(11) DEFAULT NULL,
  `ltrans_id` int(11) DEFAULT NULL,
  `sav_id` int(11) DEFAULT NULL,
  `inc_amount` int(11) NOT NULL,
  `inc_date` int(15) NOT NULL,
  `inc_receipt` int(11) NOT NULL,
  `inc_text` varchar(200) NOT NULL,
  `inc_created` int(11) NOT NULL,
  `user_id` int(6) NOT NULL,
  PRIMARY KEY (`inc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`inc_id`, `inctype_id`, `cust_id`, `loan_id`, `ltrans_id`, `sav_id`, `inc_amount`, `inc_date`, `inc_receipt`, `inc_text`, `inc_created`, `user_id`) VALUES
(1, 7, 1, NULL, NULL, NULL, 10000, 1452812400, 1483, '', 1453118784, 1),
(2, 3, 1, NULL, NULL, NULL, 6000, 1454108400, 1484, '', 1453118805, 1),
(3, 2, 1, NULL, NULL, NULL, 1000, 1453158000, 1281, '', 1453207255, 2),
(4, 9, 90, NULL, NULL, NULL, 18000, 1453244400, 180, '', 1453208404, 1),
(5, 2, 100, NULL, NULL, NULL, 1000, 1454281200, 5678, '', 1454329440, 1),
(6, 2, 4, NULL, NULL, NULL, 1000, 1423436400, 548, '', 1455024777, 1),
(7, 2, 5, NULL, NULL, NULL, 1000, 1448924400, 659, '', 1455025157, 1),
(8, 2, 12, NULL, NULL, NULL, 1000, 1435615200, 884, '', 1455025453, 1),
(9, 7, 5, NULL, NULL, NULL, 10000, 1454194800, 8501, '', 1456487835, 1),
(10, 7, 20, 5, NULL, NULL, 10000, 1454540400, 18, '', 1456491502, 1),
(11, 3, 5, 4, NULL, NULL, 8000, 1456268400, 1712, '', 1456491634, 1),
(12, 2, 4, NULL, NULL, 429, 1000, 1456527600, 151, '', 1456576375, 1),
(13, 2, 1, NULL, NULL, 447, 1000, 1456959600, 1236, '', 1457081678, 1),
(14, 2, 15, NULL, NULL, 450, 1000, 1457046000, 1563, '', 1457081766, 1),
(15, 1, 101, NULL, NULL, NULL, 10000, 1458086400, 44, '', 1458159719, 1),
(16, 6, 101, NULL, NULL, NULL, 2000, 1458086400, 44, '', 1458159719, 1),
(17, 1, 102, NULL, NULL, NULL, 10000, 1434672000, 0, '', 1458378713, 1),
(18, 6, 102, NULL, NULL, NULL, 2000, 1434672000, 0, '', 1458378713, 1),
(19, 2, 102, NULL, NULL, 455, 1000, 1458345600, 20132, '', 1458380398, 1),
(20, 7, 102, 6, NULL, NULL, 10000, 1458345600, 14, '', 1458382428, 1),
(21, 7, 102, 7, NULL, NULL, 10000, 1458345600, 142, '', 1458383278, 1),
(22, 3, 102, 7, NULL, NULL, 100, 1458345600, 2421, '', 1458383355, 1),
(23, 4, 102, NULL, 33, NULL, 300, 1458345600, 88, '', 1458383572, 1),
(24, 4, 102, NULL, 34, NULL, 300, 1458345600, 24, '', 1458383609, 1),
(26, 4, 102, NULL, 36, NULL, 300, 1458345600, 100, '', 1458383674, 1),
(27, 4, 102, NULL, 37, NULL, 300, 1458345600, 784, '', 1458383769, 1),
(28, 4, 102, NULL, 38, NULL, 300, 1458345600, 63, '', 1458383794, 1),
(29, 1, 103, NULL, NULL, NULL, 10000, 1458345600, 1200, '', 1458390457, 1),
(30, 6, 103, NULL, NULL, NULL, 2000, 1458345600, 1200, '', 1458390457, 1),
(31, 1, 104, NULL, NULL, NULL, 10000, 1458345600, 100, '', 1458390810, 1),
(32, 6, 104, NULL, NULL, NULL, 2000, 1458345600, 100, '', 1458390810, 1);

-- --------------------------------------------------------

--
-- Table structure for table `inctype`
--

CREATE TABLE IF NOT EXISTS `inctype` (
  `inctype_id` int(11) NOT NULL AUTO_INCREMENT,
  `inctype_type` varchar(50) NOT NULL,
  `inctype_short` varchar(8) NOT NULL,
  PRIMARY KEY (`inctype_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `inctype`
--

INSERT INTO `inctype` (`inctype_id`, `inctype_type`, `inctype_short`) VALUES
(1, 'Entrance Fee', 'INC_ENF'),
(2, 'Withdrawal Fee', 'INC_WDF'),
(3, 'Loan Fee', 'INC_LOF'),
(4, 'Loan Interest', 'INC_INT'),
(5, 'Loan Default Fine', 'INC_LDF'),
(6, 'Stationary Sales', 'INC_STS'),
(7, 'Loan Application Fee', 'INC_LAF'),
(8, 'Subscription Fee', 'INC_SUF'),
(9, 'Other', 'INC_OTH'),
(10, 'Insurance', 'INC_INS');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE IF NOT EXISTS `loans` (
  `loan_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` int(11) NOT NULL,
  `loanstatus_id` int(11) NOT NULL,
  `loan_no` varchar(20) NOT NULL,
  `loan_date` int(15) NOT NULL,
  `loan_dateout` int(11) NOT NULL,
  `loan_issued` int(2) NOT NULL,
  `loan_principal` int(11) NOT NULL,
  `loan_interest` float NOT NULL,
  `loan_appfee_receipt` int(11) NOT NULL,
  `loan_fee` int(11) NOT NULL,
  `loan_fee_receipt` int(11) NOT NULL,
  `loan_rate` decimal(11,0) NOT NULL,
  `loan_period` int(11) NOT NULL,
  `loan_repaytotal` int(11) NOT NULL,
  `loan_repaystart` int(11) NOT NULL,
  `loan_purpose` varchar(250) NOT NULL,
  `loan_sec1` varchar(250) NOT NULL,
  `loan_sec2` varchar(250) NOT NULL,
  `loan_guarant1` int(11) NOT NULL,
  `loan_guarant2` int(11) NOT NULL,
  `loan_guarant3` int(11) NOT NULL,
  `loan_feepaid` int(1) NOT NULL DEFAULT '0',
  `loan_created` int(15) DEFAULT NULL,
  `user_id` int(6) NOT NULL,
  PRIMARY KEY (`loan_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`loan_id`, `cust_id`, `loanstatus_id`, `loan_no`, `loan_date`, `loan_dateout`, `loan_issued`, `loan_principal`, `loan_interest`, `loan_appfee_receipt`, `loan_fee`, `loan_fee_receipt`, `loan_rate`, `loan_period`, `loan_repaytotal`, `loan_repaystart`, `loan_purpose`, `loan_sec1`, `loan_sec2`, `loan_guarant1`, `loan_guarant2`, `loan_guarant3`, `loan_feepaid`, `loan_created`, `user_id`) VALUES
(1, 100, 2, 'L-100-2', 1439935200, 1439935200, 1, 850000, 2.5, 1234, 8500, 87874, '162917', 6, 977500, 0, 'test', 'Cow', '', 1, 2, 3, 0, 1439993579, 1),
(2, 1, 2, 'L-1-1', 1452812400, 1454108400, 1, 600000, 2.5, 1483, 6000, 1484, '65000', 12, 780000, 0, 'Printing Cost', 'Historic Bible Edition', 'Herd of pigs', 3, 4, 200, 0, 1453118784, 1),
(4, 5, 2, 'L-5-1', 1454194800, 1456268400, 1, 800000, 3, 8501, 8000, 1712, '104000', 10, 1040000, 0, 'Aquisition of a plot', 'House', '', 2, 4, 26, 0, 1456487835, 1),
(5, 20, 1, 'L-20-1', 1454540400, 0, 0, 900000, 2, 18, 9000, 0, '168000', 6, 1008000, 0, 'Business Boost', 'Motorcycle', 'Land Title', 63, 120, 11, 0, 1456491502, 1),
(6, 102, 1, 'L-102-1', 1458345600, 0, 0, 0, 3, 14, 0, 0, '0', 2, 0, 0, '', '', '', 16, 19, 19, 0, 1458382428, 1),
(7, 102, 2, 'L-102-2', 1458345600, 1458345600, 1, 10000, 3, 142, 100, 2421, '2300', 6, 11800, 0, 'development', 'salary', '', 16, 18, 20, 0, 1458383278, 1);

-- --------------------------------------------------------

--
-- Table structure for table `loanstatus`
--

CREATE TABLE IF NOT EXISTS `loanstatus` (
  `loanstatus_id` int(11) NOT NULL AUTO_INCREMENT,
  `loanstatus_status` varchar(50) NOT NULL,
  `loanstatus_short` varchar(5) NOT NULL,
  PRIMARY KEY (`loanstatus_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `loanstatus`
--

INSERT INTO `loanstatus` (`loanstatus_id`, `loanstatus_status`, `loanstatus_short`) VALUES
(1, 'Pending', 'PEN'),
(2, 'Approved', 'APP'),
(3, 'Refused', 'REF'),
(4, 'Abandoned', 'ABN'),
(5, 'Cleared', 'CLR');

-- --------------------------------------------------------

--
-- Table structure for table `logrec`
--

CREATE TABLE IF NOT EXISTS `logrec` (
  `logrec_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `logrec_start` int(11) DEFAULT NULL,
  `logrec_end` int(11) DEFAULT NULL,
  `logrec_logout` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`logrec_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `logrec`
--

INSERT INTO `logrec` (`logrec_id`, `user_id`, `logrec_start`, `logrec_end`, `logrec_logout`) VALUES
(29, 5, 1458472609, 1458472620, 1),
(30, 5, 1458472648, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ltrans`
--

CREATE TABLE IF NOT EXISTS `ltrans` (
  `ltrans_id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL,
  `ltrans_due` int(11) DEFAULT NULL,
  `ltrans_date` int(15) DEFAULT NULL,
  `ltrans_principaldue` int(11) DEFAULT NULL,
  `ltrans_principal` int(15) DEFAULT NULL,
  `ltrans_interestdue` int(11) DEFAULT NULL,
  `ltrans_interest` int(11) DEFAULT NULL,
  `ltrans_fined` int(1) NOT NULL DEFAULT '0',
  `ltrans_receipt` int(11) DEFAULT NULL,
  `ltrans_created` int(15) DEFAULT NULL,
  `user_id` int(6) NOT NULL,
  PRIMARY KEY (`ltrans_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `ltrans`
--

INSERT INTO `ltrans` (`ltrans_id`, `loan_id`, `ltrans_due`, `ltrans_date`, `ltrans_principaldue`, `ltrans_principal`, `ltrans_interestdue`, `ltrans_interest`, `ltrans_fined`, `ltrans_receipt`, `ltrans_created`, `user_id`) VALUES
(35, 7, 1466380800, NULL, 2000, NULL, 300, NULL, 0, NULL, 1458383626, 1),
(36, 7, 1469059200, 1458345600, 2000, 1200, 300, 300, 0, 100, 1458383674, 1),
(37, 7, 1471737600, 1458345600, 2000, 2000, 300, 300, 0, 784, 1458383769, 1),
(38, 7, 1474416000, 1458345600, 2000, 2000, 300, 300, 0, 63, 1458383794, 1);

-- --------------------------------------------------------

--
-- Table structure for table `savbalance`
--

CREATE TABLE IF NOT EXISTS `savbalance` (
  `savbal_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` int(11) NOT NULL,
  `savbal_balance` int(11) NOT NULL,
  `savbal_date` int(11) NOT NULL,
  `savbal_created` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`savbal_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=421 ;

--
-- Dumping data for table `savbalance`
--

INSERT INTO `savbalance` (`savbal_id`, `cust_id`, `savbal_balance`, `savbal_date`, `savbal_created`, `user_id`) VALUES
(31, 31, 0, 1420070400, 1457027075, 1),
(32, 32, 2003, 1420070400, 1457027075, 1),
(33, 33, 65302, 1420070400, 1457027075, 1),
(34, 34, 0, 1420070400, 1457027075, 1),
(35, 35, 20000, 1420070400, 1457027075, 1),
(36, 36, 99801, 1420070400, 1457027075, 1),
(37, 37, 1500000, 1420070400, 1457027075, 1),
(38, 38, 4001, 1420070400, 1457027075, 1),
(39, 39, 293600, 1420070400, 1457027075, 1),
(40, 40, 2, 1420070400, 1457027075, 1),
(41, 41, 4000, 1420070400, 1457027075, 1),
(42, 42, 11201, 1420070400, 1457027075, 1),
(43, 43, 91800, 1420070400, 1457027075, 1),
(44, 44, 0, 1420070400, 1457027075, 1),
(45, 45, 100001, 1454972400, 1457027075, 1),
(46, 46, 20001, 1420070400, 1457027075, 1),
(47, 47, 0, 1420070400, 1457027075, 1),
(48, 48, 0, 1420070400, 1457027075, 1),
(49, 49, 25002, 1420070400, 1457027075, 1),
(50, 50, 22500, 1420070400, 1457027075, 1),
(51, 51, 85000, 1420070400, 1457027075, 1),
(52, 52, 263402, 1420070400, 1457027075, 1),
(53, 53, 20000, 1420070400, 1457027075, 1),
(54, 54, 0, 1420070400, 1457027075, 1),
(55, 55, 25801, 1420070400, 1457027075, 1),
(56, 56, 2029, 1420070400, 1457027075, 1),
(57, 57, 0, 1420070400, 1457027075, 1),
(58, 58, 30601, 1420070400, 1457027075, 1),
(59, 59, 90800, 1420070400, 1457027075, 1),
(60, 60, 10040001, 1420070400, 1457027075, 1),
(61, 61, 360700, 1420070400, 1457027075, 1),
(62, 62, 165900, 1420070400, 1457027075, 1),
(63, 63, 27500, 1420070400, 1457027075, 1),
(64, 64, 11500, 1420070400, 1457027075, 1),
(65, 65, 11400, 1420070400, 1457027075, 1),
(66, 66, 141400, 1420070400, 1457027075, 1),
(67, 67, 35000, 1453590000, 1457027075, 1),
(68, 68, 81400, 1420070400, 1457027075, 1),
(69, 69, 1400, 1420070400, 1457027075, 1),
(70, 70, 0, 1420070400, 1457027075, 1),
(71, 71, 0, 1420070400, 1457027075, 1),
(72, 72, 80000, 1420070400, 1457027075, 1),
(73, 73, 0, 1420070400, 1457027075, 1),
(74, 74, 25000, 1420070400, 1457027075, 1),
(75, 75, 0, 1420070400, 1457027075, 1),
(76, 76, 600, 1420070400, 1457027075, 1),
(77, 77, 471001, 1420070400, 1457027075, 1),
(78, 78, 169002, 1420070400, 1457027075, 1),
(79, 79, 60000, 1420070400, 1457027075, 1),
(80, 80, 79000, 1420070400, 1457027075, 1),
(81, 81, 10200, 1420070400, 1457027075, 1),
(82, 82, 0, 1420070400, 1457027075, 1),
(83, 83, 0, 1420070400, 1457027075, 1),
(84, 84, 51000, 1420070400, 1457027075, 1),
(85, 85, 51000, 1420070400, 1457027075, 1),
(86, 86, 199301, 1420070400, 1457027075, 1),
(87, 87, 0, 1420070400, 1457027075, 1),
(88, 88, 0, 1420070400, 1457027075, 1),
(89, 89, 100000, 1420070400, 1457027075, 1),
(90, 90, 2, 1420070400, 1457027075, 1),
(91, 91, 99877, 1420070400, 1457027075, 1),
(92, 92, 375001, 1420070400, 1457027075, 1),
(93, 93, 10000, 1420070400, 1457027075, 1),
(94, 94, 400000, 1420070400, 1457027075, 1),
(95, 95, 86082, 1420070400, 1457027075, 1),
(96, 96, 185, 1420070400, 1457027075, 1),
(97, 97, 377800, 1420070400, 1457027075, 1),
(98, 98, 28000, 1420070400, 1457027075, 1),
(99, 99, 63, 1420070400, 1457027075, 1),
(100, 100, 49001, 1454281200, 1457027075, 1),
(101, 1, 19000, 1457046000, 1458381506, 1),
(102, 2, 190400, 1420070400, 1458381506, 1),
(103, 3, 18002, 1469484000, 1458381506, 1),
(104, 4, 38500, 1456527600, 1458381506, 1),
(105, 5, 299700, 1455750000, 1458381506, 1),
(106, 6, 25701, 1420070400, 1458381506, 1),
(107, 7, 105906, 1420070400, 1458381506, 1),
(108, 8, 93000, 1456614000, 1458381506, 1),
(109, 9, 100002, 1423602000, 1458381506, 1),
(110, 10, 35000, 1456354800, 1458381506, 1),
(111, 11, 5276, 1420070400, 1458381506, 1),
(112, 12, 10000, 1435615200, 1458381506, 1),
(113, 13, 379700, 1420070400, 1458381506, 1),
(114, 14, 0, 1420070400, 1458381506, 1),
(115, 15, 80000, 1457046000, 1458381506, 1),
(116, 16, 30000, 1420070400, 1458381506, 1),
(117, 17, 0, 1420070400, 1458381506, 1),
(118, 18, 24033, 1420070400, 1458381506, 1),
(119, 19, 74000, 1420070400, 1458381506, 1),
(120, 20, 477001, 1456441200, 1458381506, 1),
(121, 21, 143302, 1420070400, 1458381506, 1),
(122, 22, 16201, 1420070400, 1458381506, 1),
(123, 23, 100000, 1420070400, 1458381506, 1),
(124, 24, 0, 1420070400, 1458381506, 1),
(125, 25, 10201, 1420070400, 1458381506, 1),
(126, 26, 62501, 1420070400, 1458381506, 1),
(127, 27, 5101, 1420070400, 1458381506, 1),
(128, 28, 1201, 1420070400, 1458381506, 1),
(129, 29, 36002, 1420070400, 1458381506, 1),
(130, 30, 0, 1420070400, 1458381506, 1),
(131, 31, 0, 1420070400, 1458381506, 1),
(132, 32, 2003, 1420070400, 1458381506, 1),
(133, 33, 65302, 1420070400, 1458381506, 1),
(134, 34, 0, 1420070400, 1458381506, 1),
(135, 35, 20000, 1420070400, 1458381506, 1),
(136, 36, 99801, 1420070400, 1458381506, 1),
(137, 37, 1500000, 1420070400, 1458381506, 1),
(138, 38, 4001, 1420070400, 1458381506, 1),
(139, 39, 293600, 1420070400, 1458381506, 1),
(140, 40, 2, 1420070400, 1458381506, 1),
(141, 41, 4000, 1420070400, 1458381506, 1),
(142, 42, 11201, 1420070400, 1458381506, 1),
(143, 43, 91800, 1420070400, 1458381506, 1),
(144, 44, 0, 1420070400, 1458381506, 1),
(145, 45, 100001, 1454972400, 1458381506, 1),
(146, 46, 20001, 1420070400, 1458381506, 1),
(147, 47, 0, 1420070400, 1458381506, 1),
(148, 48, 0, 1420070400, 1458381506, 1),
(149, 49, 25002, 1420070400, 1458381506, 1),
(150, 50, 22500, 1420070400, 1458381506, 1),
(151, 51, 85000, 1420070400, 1458381506, 1),
(152, 52, 263402, 1420070400, 1458381506, 1),
(153, 53, 20000, 1420070400, 1458381506, 1),
(154, 54, 0, 1420070400, 1458381506, 1),
(155, 55, 25801, 1420070400, 1458381506, 1),
(156, 56, 2029, 1420070400, 1458381506, 1),
(157, 57, 0, 1420070400, 1458381506, 1),
(158, 58, 30601, 1420070400, 1458381506, 1),
(159, 59, 90800, 1420070400, 1458381506, 1),
(160, 60, 10040001, 1420070400, 1458381506, 1),
(161, 61, 360700, 1420070400, 1458381506, 1),
(162, 62, 165900, 1420070400, 1458381506, 1),
(163, 63, 27500, 1420070400, 1458381506, 1),
(164, 64, 11500, 1420070400, 1458381506, 1),
(165, 65, 11400, 1420070400, 1458381506, 1),
(166, 66, 141400, 1420070400, 1458381506, 1),
(167, 67, 35000, 1453590000, 1458381506, 1),
(168, 68, 81400, 1420070400, 1458381506, 1),
(169, 69, 1400, 1420070400, 1458381506, 1),
(170, 70, 0, 1420070400, 1458381506, 1),
(171, 71, 0, 1420070400, 1458381506, 1),
(172, 72, 80000, 1420070400, 1458381506, 1),
(173, 73, 0, 1420070400, 1458381506, 1),
(174, 74, 25000, 1420070400, 1458381506, 1),
(175, 75, 0, 1420070400, 1458381506, 1),
(176, 76, 600, 1420070400, 1458381506, 1),
(177, 77, 471001, 1420070400, 1458381506, 1),
(178, 78, 169002, 1420070400, 1458381506, 1),
(179, 79, 60000, 1420070400, 1458381506, 1),
(180, 80, 79000, 1420070400, 1458381506, 1),
(181, 81, 10200, 1420070400, 1458381506, 1),
(182, 82, 0, 1420070400, 1458381506, 1),
(183, 83, 0, 1420070400, 1458381506, 1),
(184, 84, 51000, 1420070400, 1458381506, 1),
(185, 85, 51000, 1420070400, 1458381506, 1),
(186, 86, 199301, 1420070400, 1458381506, 1),
(187, 87, 0, 1420070400, 1458381506, 1),
(188, 88, 0, 1420070400, 1458381506, 1),
(189, 89, 100000, 1420070400, 1458381506, 1),
(190, 90, 2, 1420070400, 1458381506, 1),
(191, 91, 99877, 1420070400, 1458381506, 1),
(192, 92, 375001, 1420070400, 1458381506, 1),
(193, 93, 10000, 1420070400, 1458381506, 1),
(194, 94, 400000, 1420070400, 1458381506, 1),
(195, 95, 86082, 1420070400, 1458381506, 1),
(196, 96, 185, 1420070400, 1458381506, 1),
(197, 97, 377800, 1420070400, 1458381506, 1),
(198, 98, 28000, 1420070400, 1458381506, 1),
(199, 99, 63, 1420070400, 1458381506, 1),
(200, 100, 49001, 1454281200, 1458381506, 1),
(201, 101, 100000, 1458086400, 1458381506, 1),
(202, 102, 35500, 1458345600, 1458381506, 1),
(203, 1, 19000, 1457046000, 1458469618, 1),
(204, 2, 190400, 1420070400, 1458469618, 1),
(205, 3, 18002, 1469484000, 1458469618, 1),
(206, 4, 38500, 1456527600, 1458469618, 1),
(207, 5, 299700, 1455750000, 1458469618, 1),
(208, 6, 25701, 1451520000, 1458469618, 1),
(209, 7, 105906, 1451520000, 1458469618, 1),
(210, 8, 93000, 1456614000, 1458469618, 1),
(211, 9, 100002, 1451520000, 1458469618, 1),
(212, 10, 35000, 1456354800, 1458469618, 1),
(213, 11, 5276, 1420070400, 1458469618, 1),
(214, 12, 10000, 1435615200, 1458469618, 1),
(215, 13, 379700, 1420070400, 1458469618, 1),
(216, 14, 0, 1420070400, 1458469618, 1),
(217, 15, 80000, 1457046000, 1458469618, 1),
(218, 16, 30000, 1420070400, 1458469618, 1),
(219, 17, 0, 1420070400, 1458469618, 1),
(220, 18, 24033, 1420070400, 1458469618, 1),
(221, 19, 74000, 1420070400, 1458469618, 1),
(222, 20, 477001, 1456441200, 1458469618, 1),
(223, 21, 143302, 1451520000, 1458469618, 1),
(224, 22, 16201, 1451520000, 1458469618, 1),
(225, 23, 100000, 1420070400, 1458469618, 1),
(226, 24, 0, 1420070400, 1458469618, 1),
(227, 25, 10201, 1451520000, 1458469618, 1),
(228, 26, 62501, 1451520000, 1458469618, 1),
(229, 27, 5101, 1451520000, 1458469618, 1),
(230, 28, 1201, 1451520000, 1458469618, 1),
(231, 29, 36002, 1451520000, 1458469618, 1),
(232, 30, 0, 1420070400, 1458469618, 1),
(233, 31, 0, 1420070400, 1458469618, 1),
(234, 32, 2003, 1451520000, 1458469618, 1),
(235, 33, 65302, 1420070400, 1458469618, 1),
(236, 34, 0, 1420070400, 1458469618, 1),
(237, 35, 20000, 1420070400, 1458469618, 1),
(238, 36, 99801, 1451520000, 1458469618, 1),
(239, 37, 1500000, 1420070400, 1458469618, 1),
(240, 38, 4001, 1451520000, 1458469618, 1),
(241, 39, 293600, 1420070400, 1458469618, 1),
(242, 40, 2, 1451520000, 1458469618, 1),
(243, 41, 4000, 1420070400, 1458469618, 1),
(244, 42, 11201, 1451520000, 1458469618, 1),
(245, 43, 91800, 1420070400, 1458469618, 1),
(246, 44, 0, 1420070400, 1458469618, 1),
(247, 45, 100001, 1454972400, 1458469618, 1),
(248, 46, 20001, 1451520000, 1458469618, 1),
(249, 47, 0, 1420070400, 1458469618, 1),
(250, 48, 0, 1420070400, 1458469618, 1),
(251, 49, 25002, 1451520000, 1458469618, 1),
(252, 50, 22500, 1420070400, 1458469618, 1),
(253, 51, 85000, 1420070400, 1458469618, 1),
(254, 52, 263402, 1451520000, 1458469618, 1),
(255, 53, 20000, 1420070400, 1458469618, 1),
(256, 54, 0, 1420070400, 1458469618, 1),
(257, 55, 25801, 1451520000, 1458469618, 1),
(258, 56, 2029, 1420070400, 1458469618, 1),
(259, 57, 0, 1420070400, 1458469618, 1),
(260, 58, 30601, 1451520000, 1458469618, 1),
(261, 59, 90800, 1420070400, 1458469618, 1),
(262, 60, 10040001, 1451520000, 1458469618, 1),
(263, 61, 360700, 1420070400, 1458469618, 1),
(264, 62, 165900, 1420070400, 1458469618, 1),
(265, 63, 27500, 1420070400, 1458469618, 1),
(266, 64, 11500, 1420070400, 1458469618, 1),
(267, 65, 11400, 1420070400, 1458469618, 1),
(268, 66, 141400, 1420070400, 1458469618, 1),
(269, 67, 35000, 1453590000, 1458469618, 1),
(270, 68, 81400, 1420070400, 1458469618, 1),
(271, 69, 1400, 1420070400, 1458469618, 1),
(272, 70, 0, 1420070400, 1458469618, 1),
(273, 71, 0, 1420070400, 1458469618, 1),
(274, 72, 80000, 1420070400, 1458469618, 1),
(275, 73, 0, 1420070400, 1458469618, 1),
(276, 74, 25000, 1420070400, 1458469618, 1),
(277, 75, 0, 1420070400, 1458469618, 1),
(278, 76, 600, 1420070400, 1458469618, 1),
(279, 77, 471001, 1451520000, 1458469618, 1),
(280, 78, 169002, 1451520000, 1458469618, 1),
(281, 79, 60000, 1420070400, 1458469618, 1),
(282, 80, 79000, 1420070400, 1458469618, 1),
(283, 81, 10200, 1420070400, 1458469618, 1),
(284, 82, 0, 1420070400, 1458469618, 1),
(285, 83, 0, 1420070400, 1458469618, 1),
(286, 84, 51000, 1420070400, 1458469618, 1),
(287, 85, 51000, 1420070400, 1458469618, 1),
(288, 86, 199301, 1451520000, 1458469618, 1),
(289, 87, 0, 1420070400, 1458469618, 1),
(290, 88, 0, 1420070400, 1458469618, 1),
(291, 89, 100000, 1420070400, 1458469618, 1),
(292, 90, 2, 1451520000, 1458469618, 1),
(293, 91, 99877, 1451520000, 1458469618, 1),
(294, 92, 375001, 1451520000, 1458469618, 1),
(295, 93, 10000, 1420070400, 1458469618, 1),
(296, 94, 400000, 1420070400, 1458469618, 1),
(297, 95, 86082, 1451520000, 1458469618, 1),
(298, 96, 185, 1451520000, 1458469618, 1),
(299, 97, 377800, 1420070400, 1458469618, 1),
(300, 98, 28000, 1420070400, 1458469618, 1),
(301, 99, 63, 1451520000, 1458469618, 1),
(302, 100, 49001, 1454281200, 1458469618, 1),
(303, 101, 100000, 1458086400, 1458469618, 1),
(304, 102, 35500, 1458345600, 1458469618, 1),
(305, 103, 2000, 1458345600, 1458469618, 1),
(306, 104, 10000, 1458345600, 1458469618, 1),
(307, 1, 19000, 1457046000, 1458469757, 1),
(308, 2, 190400, 1420070400, 1458469757, 1),
(309, 3, 18002, 1469484000, 1458469757, 1),
(310, 4, 38500, 1456527600, 1458469757, 1),
(311, 5, 299700, 1455750000, 1458469757, 1),
(312, 6, 25701, 1451520000, 1458469757, 1),
(313, 7, 105906, 1451520000, 1458469757, 1),
(314, 8, 93000, 1456614000, 1458469757, 1),
(315, 9, 100002, 1451520000, 1458469757, 1),
(316, 10, 35000, 1456354800, 1458469757, 1),
(317, 11, 5276, 1420070400, 1458469757, 1),
(318, 12, 10000, 1435615200, 1458469757, 1),
(319, 13, 379700, 1420070400, 1458469757, 1),
(320, 14, 0, 1420070400, 1458469757, 1),
(321, 15, 80000, 1457046000, 1458469757, 1),
(322, 16, 30000, 1420070400, 1458469757, 1),
(323, 17, 0, 1420070400, 1458469757, 1),
(324, 18, 24033, 1420070400, 1458469757, 1),
(325, 19, 74000, 1420070400, 1458469757, 1),
(326, 20, 477001, 1456441200, 1458469757, 1),
(327, 21, 143302, 1451520000, 1458469757, 1),
(328, 22, 16201, 1451520000, 1458469757, 1),
(329, 23, 100000, 1420070400, 1458469757, 1),
(330, 24, 0, 1420070400, 1458469757, 1),
(331, 25, 10201, 1451520000, 1458469757, 1),
(332, 26, 62501, 1451520000, 1458469757, 1),
(333, 27, 5101, 1451520000, 1458469757, 1),
(334, 28, 1201, 1451520000, 1458469757, 1),
(335, 29, 36002, 1451520000, 1458469757, 1),
(336, 30, 0, 1420070400, 1458469757, 1),
(337, 31, 0, 1420070400, 1458469757, 1),
(338, 32, 2003, 1451520000, 1458469757, 1),
(339, 33, 65302, 1420070400, 1458469757, 1),
(340, 34, 0, 1420070400, 1458469757, 1),
(341, 35, 20000, 1420070400, 1458469757, 1),
(342, 36, 99801, 1451520000, 1458469757, 1),
(343, 37, 1500000, 1420070400, 1458469757, 1),
(344, 38, 4001, 1451520000, 1458469757, 1),
(345, 39, 293600, 1420070400, 1458469757, 1),
(346, 40, 2, 1451520000, 1458469757, 1),
(347, 41, 4000, 1420070400, 1458469757, 1),
(348, 42, 11201, 1451520000, 1458469757, 1),
(349, 43, 91800, 1420070400, 1458469757, 1),
(350, 44, 0, 1420070400, 1458469757, 1),
(351, 45, 100001, 1454972400, 1458469757, 1),
(352, 46, 20001, 1451520000, 1458469757, 1),
(353, 47, 0, 1420070400, 1458469757, 1),
(354, 48, 0, 1420070400, 1458469757, 1),
(355, 49, 25002, 1451520000, 1458469757, 1),
(356, 50, 22500, 1420070400, 1458469757, 1),
(357, 51, 85000, 1420070400, 1458469757, 1),
(358, 52, 263402, 1451520000, 1458469757, 1),
(359, 53, 20000, 1420070400, 1458469757, 1),
(360, 54, 0, 1420070400, 1458469757, 1),
(361, 55, 25801, 1451520000, 1458469757, 1),
(362, 56, 2029, 1420070400, 1458469757, 1),
(363, 57, 0, 1420070400, 1458469757, 1),
(364, 58, 30601, 1451520000, 1458469757, 1),
(365, 59, 90800, 1420070400, 1458469757, 1),
(366, 60, 10040001, 1451520000, 1458469757, 1),
(367, 61, 360700, 1420070400, 1458469757, 1),
(368, 62, 165900, 1420070400, 1458469757, 1),
(369, 63, 27500, 1420070400, 1458469757, 1),
(370, 64, 11500, 1420070400, 1458469757, 1),
(371, 65, 11400, 1420070400, 1458469757, 1),
(372, 66, 141400, 1420070400, 1458469757, 1),
(373, 67, 35000, 1453590000, 1458469757, 1),
(374, 68, 81400, 1420070400, 1458469757, 1),
(375, 69, 1400, 1420070400, 1458469757, 1),
(376, 70, 0, 1420070400, 1458469757, 1),
(377, 71, 0, 1420070400, 1458469757, 1),
(378, 72, 80000, 1420070400, 1458469757, 1),
(379, 73, 0, 1420070400, 1458469757, 1),
(380, 74, 25000, 1420070400, 1458469757, 1),
(381, 75, 0, 1420070400, 1458469757, 1),
(382, 76, 600, 1420070400, 1458469757, 1),
(383, 77, 471001, 1451520000, 1458469757, 1),
(384, 78, 169002, 1451520000, 1458469757, 1),
(385, 79, 60000, 1420070400, 1458469757, 1),
(386, 80, 79000, 1420070400, 1458469757, 1),
(387, 81, 10200, 1420070400, 1458469757, 1),
(388, 82, 0, 1420070400, 1458469757, 1),
(389, 83, 0, 1420070400, 1458469757, 1),
(390, 84, 51000, 1420070400, 1458469757, 1),
(391, 85, 51000, 1420070400, 1458469757, 1),
(392, 86, 199301, 1451520000, 1458469757, 1),
(393, 87, 0, 1420070400, 1458469757, 1),
(394, 88, 0, 1420070400, 1458469757, 1),
(395, 89, 100000, 1420070400, 1458469757, 1),
(396, 90, 2, 1451520000, 1458469757, 1),
(397, 91, 99877, 1451520000, 1458469757, 1),
(398, 92, 375001, 1451520000, 1458469757, 1),
(399, 93, 10000, 1420070400, 1458469757, 1),
(400, 94, 400000, 1420070400, 1458469757, 1),
(401, 95, 86082, 1451520000, 1458469757, 1),
(402, 96, 185, 1451520000, 1458469757, 1),
(403, 97, 377800, 1420070400, 1458469757, 1),
(404, 98, 28000, 1420070400, 1458469757, 1),
(405, 99, 63, 1451520000, 1458469757, 1),
(406, 100, 49001, 1454281200, 1458469757, 1),
(407, 101, 100000, 1458086400, 1458469757, 1),
(408, 102, 35500, 1458345600, 1458469757, 1),
(409, 103, 2000, 1458345600, 1458469757, 1),
(410, 104, 10000, 1458345600, 1458469757, 1),
(411, 13, 0, 0, 1458472847, 1),
(412, 16, 0, 0, 1458472847, 1),
(413, 20, 450501, 1456441200, 1458472847, 1),
(414, 57, 0, 1420070400, 1458472847, 1),
(415, 78, 169002, 1451520000, 1458472847, 1),
(416, 82, 0, 1420070400, 1458472847, 1),
(417, 84, 51000, 1420070400, 1458472847, 1),
(418, 101, 100000, 1458086400, 1458472847, 1),
(419, 103, 2000, 1458345600, 1458472847, 1),
(420, 104, 10000, 1458345600, 1458472847, 1);

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE IF NOT EXISTS `savings` (
  `sav_id` int(11) NOT NULL AUTO_INCREMENT,
  `savtype_id` int(11) NOT NULL,
  `sav_mother` int(11) DEFAULT NULL,
  `cust_id` int(11) NOT NULL,
  `ltrans_id` int(11) DEFAULT NULL,
  `sav_date` int(15) NOT NULL,
  `sav_amount` int(15) NOT NULL DEFAULT '0',
  `sav_receipt` int(11) DEFAULT NULL,
  `sav_slip` int(10) NOT NULL,
  `sav_created` int(15) DEFAULT NULL,
  `user_id` int(6) NOT NULL,
  PRIMARY KEY (`sav_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=494 ;

--
-- Dumping data for table `savings`
--

INSERT INTO `savings` (`sav_id`, `savtype_id`, `sav_mother`, `cust_id`, `ltrans_id`, `sav_date`, `sav_amount`, `sav_receipt`, `sav_slip`, `sav_created`, `user_id`) VALUES
(32, 1, NULL, 32, NULL, 1420070400, 2000, 0, 0, NULL, 1),
(33, 1, NULL, 33, NULL, 1420070400, 65302, 0, 0, NULL, 1),
(34, 1, NULL, 34, NULL, 1420070400, 0, 0, 0, NULL, 1),
(35, 1, NULL, 35, NULL, 1420070400, 20000, 0, 0, NULL, 1),
(36, 1, NULL, 36, NULL, 1420070400, 99800, 0, 0, NULL, 1),
(37, 1, NULL, 37, NULL, 1420070400, 1500000, 0, 0, NULL, 1),
(38, 1, NULL, 38, NULL, 1420070400, 4000, 0, 0, NULL, 1),
(39, 1, NULL, 39, NULL, 1420070400, 293600, 0, 0, NULL, 1),
(40, 1, NULL, 40, NULL, 1420070400, 0, 0, 0, NULL, 1),
(41, 1, NULL, 41, NULL, 1420070400, 4000, 0, 0, NULL, 1),
(42, 1, NULL, 42, NULL, 1420070400, 11200, 0, 0, NULL, 1),
(43, 1, NULL, 43, NULL, 1420070400, 91800, 0, 0, NULL, 1),
(44, 1, NULL, 44, NULL, 1420070400, 0, 0, 0, NULL, 1),
(45, 1, NULL, 45, NULL, 1420070400, 90800, 0, 0, NULL, 1),
(46, 1, NULL, 46, NULL, 1420070400, 20000, 0, 0, NULL, 1),
(47, 1, NULL, 47, NULL, 1420070400, 0, 0, 0, NULL, 1),
(48, 1, NULL, 48, NULL, 1420070400, 0, 0, 0, NULL, 1),
(49, 1, NULL, 49, NULL, 1420070400, 25000, 0, 0, NULL, 1),
(50, 1, NULL, 50, NULL, 1420070400, 22500, 0, 0, NULL, 1),
(51, 1, NULL, 51, NULL, 1420070400, 85000, 0, 0, NULL, 1),
(52, 1, NULL, 52, NULL, 1420070400, 263400, 0, 0, NULL, 1),
(53, 1, NULL, 53, NULL, 1420070400, 20000, 0, 0, NULL, 1),
(54, 1, NULL, 54, NULL, 1420070400, 0, 0, 0, NULL, 1),
(55, 1, NULL, 55, NULL, 1420070400, 25800, 0, 0, NULL, 1),
(56, 1, NULL, 56, NULL, 1420070400, 2029, 0, 0, NULL, 1),
(57, 1, NULL, 57, NULL, 1420070400, 0, 0, 0, NULL, 1),
(58, 1, NULL, 58, NULL, 1420070400, 30600, 0, 0, NULL, 1),
(59, 1, NULL, 59, NULL, 1420070400, 90800, 0, 0, NULL, 1),
(60, 1, NULL, 60, NULL, 1420070400, 10040000, 0, 0, NULL, 1),
(61, 1, NULL, 61, NULL, 1420070400, 360700, 0, 0, NULL, 1),
(62, 1, NULL, 62, NULL, 1420070400, 165900, 0, 0, NULL, 1),
(63, 1, NULL, 63, NULL, 1420070400, 27500, 0, 0, NULL, 1),
(64, 1, NULL, 64, NULL, 1420070400, 11500, 0, 0, NULL, 1),
(65, 1, NULL, 65, NULL, 1420070400, 11400, 0, 0, NULL, 1),
(66, 1, NULL, 66, NULL, 1420070400, 141400, 0, 0, NULL, 1),
(67, 1, NULL, 68, NULL, 1420070400, 81400, 0, 0, NULL, 1),
(68, 1, NULL, 69, NULL, 1420070400, 1400, 0, 0, NULL, 1),
(69, 1, NULL, 70, NULL, 1420070400, 0, 0, 0, NULL, 1),
(70, 1, NULL, 71, NULL, 1420070400, 0, 0, 0, NULL, 1),
(71, 1, NULL, 72, NULL, 1420070400, 80000, 0, 0, NULL, 1),
(72, 1, NULL, 73, NULL, 1420070400, 0, 0, 0, NULL, 1),
(73, 1, NULL, 74, NULL, 1420070400, 25000, 0, 0, NULL, 1),
(74, 1, NULL, 75, NULL, 1420070400, 0, 0, 0, NULL, 1),
(75, 1, NULL, 76, NULL, 1420070400, 600, 0, 0, NULL, 1),
(76, 1, NULL, 77, NULL, 1420070400, 471000, 0, 0, NULL, 1),
(77, 1, NULL, 78, NULL, 1420070400, 169000, 0, 0, NULL, 1),
(78, 1, NULL, 79, NULL, 1420070400, 60000, 0, 0, NULL, 1),
(79, 1, NULL, 80, NULL, 1420070400, 79000, 0, 0, NULL, 1),
(80, 1, NULL, 81, NULL, 1420070400, 10200, 0, 0, NULL, 1),
(81, 1, NULL, 82, NULL, 1420070400, 0, 0, 0, NULL, 1),
(82, 1, NULL, 83, NULL, 1420070400, 0, 0, 0, NULL, 1),
(83, 1, NULL, 84, NULL, 1420070400, 51000, 0, 0, NULL, 1),
(84, 1, NULL, 85, NULL, 1420070400, 51000, 0, 0, NULL, 1),
(85, 1, NULL, 86, NULL, 1420070400, 199300, 0, 0, NULL, 1),
(86, 1, NULL, 87, NULL, 1420070400, 0, 0, 0, NULL, 1),
(87, 1, NULL, 88, NULL, 1420070400, 0, 0, 0, NULL, 1),
(88, 1, NULL, 89, NULL, 1420070400, 100000, 0, 0, NULL, 1),
(89, 1, NULL, 90, NULL, 1420070400, 0, 0, 0, NULL, 1),
(90, 1, NULL, 91, NULL, 1420070400, 99876, 0, 0, NULL, 1),
(91, 1, NULL, 92, NULL, 1420070400, 375000, 0, 0, NULL, 1),
(92, 1, NULL, 93, NULL, 1420070400, 10000, 0, 0, NULL, 1),
(93, 1, NULL, 94, NULL, 1420070400, 400000, 0, 0, NULL, 1),
(94, 1, NULL, 95, NULL, 1420070400, 86080, 0, 0, NULL, 1),
(95, 1, NULL, 96, NULL, 1420070400, 184, 0, 0, NULL, 1),
(96, 1, NULL, 97, NULL, 1420070400, 377800, 0, 0, NULL, 1),
(97, 1, NULL, 98, NULL, 1420070400, 28000, 0, 0, NULL, 1),
(98, 1, NULL, 99, NULL, 1420070400, 62, 0, 0, NULL, 1),
(99, 1, NULL, 100, NULL, 1420070400, 550000, 0, 0, NULL, 1),
(200, 2, NULL, 9, NULL, 1423602000, -450000, 682, 0, NULL, 1),
(201, 1, NULL, 1, NULL, 1435183200, 25000, 509, 0, 1453118311, 1),
(202, 2, NULL, 1, NULL, 1453158000, -26000, 1281, 603, 1453207255, 2),
(203, 4, NULL, 1, NULL, 1453158000, -1000, 1281, 603, 1453207255, 2),
(204, 2, NULL, 1, NULL, 1453762800, -8000, 5678, 1234, 1453793443, 1),
(205, 4, NULL, 1, NULL, 1453762800, -1000, 5678, 1234, 1453793443, 1),
(206, 1, NULL, 3, NULL, 1469484000, 14000, 7, 0, 1453795583, 1),
(207, 2, NULL, 100, NULL, 1454281200, -500000, 5678, 1234, 1454329440, 1),
(208, 4, NULL, 100, NULL, 1454281200, -1000, 5678, 1234, 1454329440, 1),
(210, 1, NULL, 45, NULL, 1454972400, 465, 9200, 0, 1455010623, 1),
(211, 1, NULL, 45, NULL, 1454972400, 8000, 845, 0, 1455010641, 1),
(212, 1, NULL, 45, NULL, 1454972400, 735, 4564, 0, 1455010657, 1),
(214, 2, NULL, 4, NULL, 1423436400, -82183, 548, 54561, 1455024777, 1),
(215, 2, NULL, 5, NULL, 1448924400, -15000, 659, 13, 1455025157, 1),
(216, 2, NULL, 12, NULL, 1435615200, -10000, 884, 466, 1455025453, 1),
(397, 1, NULL, 10, NULL, 1456268400, 20000, 1323, 0, 1456314395, 1),
(398, 1, NULL, 10, NULL, 1456354800, 15000, 1324, 0, 1456314409, 1),
(408, 5, NULL, 8, NULL, 1456614000, -5000, 1234, 0, 1456319256, 1),
(409, 1, NULL, 67, NULL, 1453590000, 35000, 350, 0, 1456320996, 1),
(416, 1, NULL, 5, NULL, 1455750000, 289000, 153, 0, 1456487741, 1),
(419, 1, NULL, 20, NULL, 1456441200, 450500, 126, 0, 1456491436, 1),
(420, 1, NULL, 4, NULL, 1453244400, 9000, 999, 0, 1456572212, 1),
(421, 1, NULL, 4, NULL, 1422745200, 10000, 111, 0, 1456572245, 1),
(422, 1, NULL, 4, NULL, 1455663600, 7500, 123, 0, 1456575001, 1),
(429, 2, NULL, 4, NULL, 1456527600, -28000, 151, 848, 1456576375, 2),
(430, 4, 429, 4, NULL, 1456527600, -1000, 151, 848, 1456576375, 2),
(445, 1, NULL, 1, NULL, 1457046000, 6000, 5153, 0, 1457081348, 1),
(446, 1, NULL, 1, NULL, 1457046000, 7000, 813, 0, 1457081566, 1),
(447, 2, NULL, 1, NULL, 1456959600, -12000, 1236, 991, 1457081678, 1),
(448, 4, 447, 1, NULL, 1456959600, -1000, 1236, 991, 1457081678, 1),
(449, 1, NULL, 15, NULL, 1456614000, 99000, 1613, 0, 1457081752, 1),
(450, 2, NULL, 15, NULL, 1457046000, -18000, 1563, 516, 1457081766, 1),
(451, 4, 450, 15, NULL, 1457046000, -1000, 1563, 516, 1457081766, 1),
(452, 1, NULL, 101, NULL, 1458086400, 100000, 100, 0, 1458160318, 1),
(453, 1, NULL, 102, NULL, 1458345600, 40000, 2642, 0, 1458378922, 1),
(454, 1, NULL, 102, NULL, 1458345600, 1000, 100, 0, 1458380306, 1),
(455, 2, NULL, 102, NULL, 1458345600, -3000, 20132, 1246, 1458380398, 1),
(456, 4, 455, 102, NULL, 1458345600, -1000, 20132, 1246, 1458380398, 1),
(457, 8, NULL, 102, 36, 1458345600, -1500, 100, 0, 1458383674, 1),
(458, 9, NULL, 3, NULL, 1451520000, 2, NULL, 0, 1458384272, 1),
(459, 9, NULL, 6, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(460, 9, NULL, 7, NULL, 1451520000, 6, NULL, 0, 1458384272, 1),
(461, 9, NULL, 9, NULL, 1451520000, 2, NULL, 0, 1458384272, 1),
(462, 9, NULL, 20, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(463, 9, NULL, 21, NULL, 1451520000, 2, NULL, 0, 1458384272, 1),
(464, 9, NULL, 22, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(465, 9, NULL, 25, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(466, 9, NULL, 26, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(467, 9, NULL, 27, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(468, 9, NULL, 28, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(469, 9, NULL, 29, NULL, 1451520000, 2, NULL, 0, 1458384272, 1),
(470, 9, NULL, 32, NULL, 1451520000, 3, NULL, 0, 1458384272, 1),
(471, 9, NULL, 36, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(472, 9, NULL, 38, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(473, 9, NULL, 40, NULL, 1451520000, 2, NULL, 0, 1458384272, 1),
(474, 9, NULL, 42, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(475, 9, NULL, 45, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(476, 9, NULL, 46, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(477, 9, NULL, 49, NULL, 1451520000, 2, NULL, 0, 1458384272, 1),
(478, 9, NULL, 52, NULL, 1451520000, 2, NULL, 0, 1458384272, 1),
(479, 9, NULL, 55, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(480, 9, NULL, 58, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(481, 9, NULL, 60, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(482, 9, NULL, 77, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(483, 9, NULL, 78, NULL, 1451520000, 2, NULL, 0, 1458384272, 1),
(484, 9, NULL, 86, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(485, 9, NULL, 90, NULL, 1451520000, 2, NULL, 0, 1458384272, 1),
(486, 9, NULL, 91, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(487, 9, NULL, 92, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(488, 9, NULL, 95, NULL, 1451520000, 2, NULL, 0, 1458384272, 1),
(489, 9, NULL, 96, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(490, 9, NULL, 99, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(491, 9, NULL, 100, NULL, 1451520000, 1, NULL, 0, 1458384272, 1),
(492, 1, NULL, 103, NULL, 1458345600, 2000, 41, 0, 1458390508, 1),
(493, 1, NULL, 104, NULL, 1458345600, 10000, 145, 0, 1458390839, 1);

-- --------------------------------------------------------

--
-- Table structure for table `savtype`
--

CREATE TABLE IF NOT EXISTS `savtype` (
  `savtype_id` int(11) NOT NULL AUTO_INCREMENT,
  `savtype_type` varchar(20) NOT NULL,
  `savtype_short` varchar(8) NOT NULL,
  PRIMARY KEY (`savtype_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `savtype`
--

INSERT INTO `savtype` (`savtype_id`, `savtype_type`, `savtype_short`) VALUES
(1, 'Deposit', 'SAV_DEP'),
(2, 'Withdrawal', 'SAV_WDL'),
(3, 'Savings Interest', 'SAV_INT'),
(4, 'W/drawal Fee', 'SAV_WDF'),
(5, 'Subscription Fee', 'SAV_SUF'),
(6, 'Loan Default Fine', 'SAV_LDF'),
(7, 'Loan Fee', 'SAV_LOF'),
(8, 'Loan Repayment', 'SAV_LRP'),
(9, 'Share Dividend', 'SAV_SHD');

-- --------------------------------------------------------

--
-- Table structure for table `securities`
--

CREATE TABLE IF NOT EXISTS `securities` (
  `sec_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` int(8) NOT NULL,
  `loan_id` int(8) NOT NULL,
  `sec_no` varchar(50) NOT NULL,
  `sec_name` varchar(100) NOT NULL,
  `sec_value` int(11) NOT NULL,
  `sec_path` varchar(200) NOT NULL,
  `sec_returned` int(1) NOT NULL,
  PRIMARY KEY (`sec_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `securities`
--


-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `set_id` int(11) NOT NULL AUTO_INCREMENT,
  `set_name` varchar(100) NOT NULL,
  `set_short` varchar(8) NOT NULL,
  `set_value` varchar(50) NOT NULL,
  PRIMARY KEY (`set_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`set_id`, `set_name`, `set_short`, `set_value`) VALUES
(1, 'Minimum Savings Balance', 'SET_MSB', '10000'),
(2, 'Minimum Loan Principal', 'SET_MLP', ''),
(3, 'Maximum Loan Principal', 'SET_XLP', ''),
(4, 'Currency Abbreviation', 'SET_CUR', 'KSH'),
(5, 'Auto-fine Defaulters', 'SET_AUF', ''),
(6, 'Account Deactivation', 'SET_DEA', ''),
(7, 'Dashboard Left', 'SET_DBL', 'dashboard/dash_subscr.php'),
(8, 'Dashboard Right', 'SET_DBR', 'dashboard/dash_loandefaults.php'),
(9, 'Interest Calculation', 'SET_ICL', 'modules/mod_inter_fixed.php'),
(10, 'Guarantor Limit', 'SET_GUA', '3'),
(11, 'Minimum Membership', 'SET_MEM', '6'),
(12, 'Maximum Principal-Savings Ratio', 'SET_PSR', '500');

-- --------------------------------------------------------

--
-- Table structure for table `shares`
--

CREATE TABLE IF NOT EXISTS `shares` (
  `share_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` int(11) NOT NULL,
  `share_date` int(11) NOT NULL,
  `share_amount` int(11) NOT NULL,
  `share_value` int(11) NOT NULL,
  `share_receipt` int(11) NOT NULL,
  `share_trans` int(2) NOT NULL,
  `share_transfrom` int(11) DEFAULT NULL,
  `share_created` int(15) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`share_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=230 ;

--
-- Dumping data for table `shares`
--

INSERT INTO `shares` (`share_id`, `cust_id`, `share_date`, `share_amount`, `share_value`, `share_receipt`, `share_trans`, `share_transfrom`, `share_created`, `user_id`) VALUES
(229, 101, 1458432000, 10, 200000, 107, 0, NULL, 1458472898, 5);

-- --------------------------------------------------------

--
-- Table structure for table `shareval`
--

CREATE TABLE IF NOT EXISTS `shareval` (
  `shareval_id` int(11) NOT NULL AUTO_INCREMENT,
  `shareval_date` int(11) NOT NULL,
  `shareval_value` int(11) NOT NULL,
  PRIMARY KEY (`shareval_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `shareval`
--

INSERT INTO `shareval` (`shareval_id`, `shareval_date`, `shareval_value`) VALUES
(1, 1454423560, 10000),
(2, 1454423597, 20000);

-- --------------------------------------------------------

--
-- Table structure for table `ugroup`
--

CREATE TABLE IF NOT EXISTS `ugroup` (
  `ugroup_id` int(11) NOT NULL AUTO_INCREMENT,
  `ugroup_name` varchar(100) NOT NULL,
  `ugroup_admin` int(11) NOT NULL,
  `ugroup_delete` int(2) NOT NULL,
  `ugroup_report` int(11) NOT NULL,
  `ugroup_created` int(15) NOT NULL,
  PRIMARY KEY (`ugroup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ugroup`
--

INSERT INTO `ugroup` (`ugroup_id`, `ugroup_name`, `ugroup_admin`, `ugroup_delete`, `ugroup_report`, `ugroup_created`) VALUES
(1, 'Administrator', 1, 1, 1, 1453123220),
(2, 'Management', 0, 1, 1, 1453144125),
(3, 'Employee', 0, 1, 0, 1453125729);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(200) NOT NULL,
  `user_pw` varchar(255) NOT NULL,
  `ugroup_id` int(11) NOT NULL,
  `empl_id` int(11) NOT NULL,
  `user_created` int(15) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_pw`, `ugroup_id`, `empl_id`, `user_created`) VALUES
(1, 'admin', '381d0a66422a999884e9ffc8b75e9daca571b62f', 1, 1, 1458388793),
(5, 'demouser', '006934b98d2d831da9ec4311e881b5ddea557cac', 1, 0, 1458388040);