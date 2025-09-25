-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 11, 2025 at 02:59 AM
-- Server version: 8.2.0
-- PHP Version: 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crowdwings`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_fund_txn`
--

DROP TABLE IF EXISTS `add_fund_txn`;
CREATE TABLE IF NOT EXISTS `add_fund_txn` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `block_timestamp` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `from` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `to` varchar(100) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Add fund to this address',
  `type` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `value` decimal(10,6) NOT NULL,
  `is_transfer` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `country_id` int DEFAULT '0',
  `country` varchar(150) DEFAULT NULL,
  `phonecode` int NOT NULL,
  `shortname` varchar(5) NOT NULL,
  `nationality` varchar(150) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '9999',
  `lang` varchar(10) DEFAULT 'en',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=985 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_id`, `country`, `phonecode`, `shortname`, `nationality`, `is_default`, `is_active`, `sort_order`, `lang`, `created_at`, `updated_at`) VALUES
(1, 1, 'Afghanistan', 93, 'AF', 'Afghans', 1, 1, 1, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 2, 'Albania', 355, 'AL', 'Albanians', 1, 1, 2, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 3, 'Algeria', 213, 'DZ', 'Algerians', 1, 1, 3, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 4, 'American Samoa', 1684, 'AS', 'Americans', 1, 1, 4, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 5, 'Andorra', 376, 'AD', 'Andorrans', 1, 1, 5, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 6, 'Angola', 244, 'AO', 'Angolans', 1, 1, 6, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 7, 'Anguilla', 1264, 'AI', 'Anguilla', 1, 1, 7, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 8, 'Antarctica', 0, 'AQ', 'Antarctica', 1, 1, 8, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 9, 'Antigua And Barbuda', 1268, 'AG', 'Antiguans and Barbudans', 1, 1, 9, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 10, 'Argentina', 54, 'AR', 'Argentines', 1, 1, 10, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 11, 'Armenia', 374, 'AM', 'Armenians', 1, 1, 11, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 12, 'Aruba', 297, 'AW', 'Arubans', 1, 1, 12, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 13, 'Australia', 61, 'AU', 'Australians', 1, 1, 13, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 14, 'Austria', 43, 'AT', 'Austrians', 1, 1, 14, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 15, 'Azerbaijan', 994, 'AZ', 'Azerbaijanis', 1, 1, 15, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 16, 'Bahamas The', 1242, 'BS', 'Bahamians', 1, 1, 16, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 17, 'Bahrain', 973, 'BH', 'Bahrainis', 1, 1, 17, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 18, 'Bangladesh', 880, 'BD', 'Bangladeshis', 1, 1, 18, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 19, 'Barbados', 1246, 'BB', 'Barbadians', 1, 1, 19, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 20, 'Belarus', 375, 'BY', 'Belarusians', 1, 1, 20, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 21, 'Belgium', 32, 'BE', 'Belgians', 1, 1, 21, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 22, 'Belize', 501, 'BZ', 'Belizeans', 1, 1, 22, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 23, 'Benin', 229, 'BJ', 'Beninese', 1, 1, 23, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 24, 'Bermuda', 1441, 'BM', 'Bermudians', 1, 1, 24, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 25, 'Bhutan', 975, 'BT', 'Bhutanese', 1, 1, 25, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 26, 'Bolivia', 591, 'BO', 'Bolivians', 1, 1, 26, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 27, 'Bosnia and Herzegovina', 387, 'BA', 'Bosnians and Herzegovinians', 1, 1, 27, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 28, 'Botswana', 267, 'BW', 'Botswana', 1, 1, 28, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 29, 'Bouvet Island', 0, 'BV', 'Bouvet Island', 1, 1, 29, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 30, 'Brazil', 55, 'BR', 'Brazilians', 1, 1, 30, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 31, 'British Indian Ocean Territory', 246, 'IO', 'British Indian Ocean Territory', 1, 1, 31, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 32, 'Brunei', 673, 'BN', 'Bruneians', 1, 1, 32, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 33, 'Bulgaria', 359, 'BG', 'Bulgarians', 1, 1, 33, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 34, 'Burkina Faso', 226, 'BF', 'Burkinabés', 1, 1, 34, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 35, 'Burundi', 257, 'BI', 'Burundians', 1, 1, 35, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 36, 'Cambodia', 855, 'KH', 'Cambodians', 1, 1, 36, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 37, 'Cameroon', 237, 'CM', 'Cameroonians', 1, 1, 37, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 38, 'Canada', 1, 'CA', 'Canadians', 1, 1, 38, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 39, 'Cape Verde', 238, 'CV', 'Cape Verde', 1, 1, 39, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 40, 'Cayman Islands', 1345, 'KY', 'Cayman Islands', 1, 1, 40, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 41, 'Central African Republic', 236, 'CF', 'Central African Republic', 1, 1, 41, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 42, 'Chad', 235, 'TD', 'Chadians', 1, 1, 42, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 43, 'Chile', 56, 'CL', 'Chileans', 1, 1, 43, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 44, 'China', 86, 'CN', 'Chinese', 1, 1, 44, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 45, 'Christmas Island', 61, 'CX', 'Christmas Island', 1, 1, 45, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 46, 'Cocos (Keeling) Islands', 672, 'CC', 'Cocos (Keeling) Islands', 1, 1, 46, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 47, 'Colombia', 57, 'CO', 'Colombians', 1, 1, 47, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 48, 'Comoros', 269, 'KM', 'Comorians', 1, 1, 48, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 49, 'Republic Of The Congo', 242, 'CG', 'Congolese', 1, 1, 49, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 50, 'Democratic Republic Of The Congo', 242, 'CD', 'Congolese', 1, 1, 50, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 51, 'Cook Islands', 682, 'CK', 'Cook Islands', 1, 1, 51, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 52, 'Costa Rica', 506, 'CR', 'Costa Ricans', 1, 1, 52, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 53, 'Cote D\'Ivoire (Ivory Coast)', 225, 'CI', 'Cote D\'Ivoire (Ivory Coast)', 1, 1, 53, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 54, 'Croatia (Hrvatska)', 385, 'HR', 'Croatians', 1, 1, 54, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 55, 'Cuba', 53, 'CU', 'Cubans', 1, 1, 55, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 56, 'Cyprus', 357, 'CY', 'Cypriots', 1, 1, 56, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 57, 'Czech Republic', 420, 'CZ', 'Czechs', 1, 1, 57, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 58, 'Denmark', 45, 'DK', 'Danes', 1, 1, 58, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 59, 'Djibouti', 253, 'DJ', 'Djiboutians', 1, 1, 59, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 60, 'Dominica', 1767, 'DM', 'Dominicans (Commonwealth)', 1, 1, 60, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 61, 'Dominican Republic', 1809, 'DO', 'Dominicans (Republic)', 1, 1, 61, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 62, 'East Timor', 670, 'TP', 'East Timorese', 1, 1, 62, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 63, 'Ecuador', 593, 'EC', 'Ecuadorians', 1, 1, 63, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 64, 'Egypt', 20, 'EG', 'Egyptians', 1, 1, 64, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 65, 'El Salvador', 503, 'SV', 'El Salvadorian', 1, 1, 65, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 66, 'Equatorial Guinea', 240, 'GQ', 'Equatoguineans', 1, 1, 66, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 67, 'Eritrea', 291, 'ER', 'Eritreans', 1, 1, 67, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 68, 'Estonia', 372, 'EE', 'Estonians', 1, 1, 68, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 69, 'Ethiopia', 251, 'ET', 'Ethiopians', 1, 1, 69, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 70, 'External Territories of Australia', 61, 'XA', 'External Territories of Australia', 1, 1, 70, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 71, 'Falkland Islands', 500, 'FK', 'Falkland Islanders', 1, 1, 71, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 72, 'Faroe Islands', 298, 'FO', 'Faroese', 1, 1, 72, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 73, 'Fiji Islands', 679, 'FJ', 'Fijians', 1, 1, 73, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 74, 'Finland', 358, 'FI', 'Finns', 1, 1, 74, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 75, 'France', 33, 'FR', 'French citizens', 1, 1, 75, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 76, 'French Guiana', 594, 'GF', 'French citizens', 1, 1, 76, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 77, 'French Polynesia', 689, 'PF', 'French citizens', 1, 1, 77, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 78, 'French Southern Territories', 0, 'TF', 'French citizens', 1, 1, 78, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 79, 'Gabon', 241, 'GA', 'Gabonese', 1, 1, 79, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, 80, 'Gambia The', 220, 'GM', 'Gambians', 1, 1, 80, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 81, 'Georgia', 995, 'GE', 'Georgians', 1, 1, 81, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 82, 'Germany', 49, 'DE', 'Germans', 1, 1, 82, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 83, 'Ghana', 233, 'GH', 'Ghanaians', 1, 1, 83, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 84, 'Gibraltar', 350, 'GI', 'Gibraltarians', 1, 1, 84, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 85, 'Greece', 30, 'GR', 'Greeks', 1, 1, 85, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 86, 'Greenland', 299, 'GL', 'Greenlander', 1, 1, 86, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 87, 'Grenada', 1473, 'GD', 'Grenadians', 1, 1, 87, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 88, 'Guadeloupe', 590, 'GP', 'Guadeloupe', 1, 1, 88, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 89, 'Guam', 1671, 'GU', 'Guam', 1, 1, 89, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 90, 'Guatemala', 502, 'GT', 'Guatemalans', 1, 1, 90, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 91, 'Guernsey and Alderney', 44, 'XU', 'Guernsey and Alderney', 1, 1, 91, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, 92, 'Guinea', 224, 'GN', 'Guineans', 1, 1, 92, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, 93, 'Guinea-Bissau', 245, 'GW', 'Guinea-Bissau nationals', 1, 1, 93, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 94, 'Guyana', 592, 'GY', 'Guyanese', 1, 1, 94, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, 95, 'Haiti', 509, 'HT', 'Haitians', 1, 1, 95, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, 96, 'Heard and McDonald Islands', 0, 'HM', 'Heard and McDonald Islands', 1, 1, 96, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 97, 'Honduras', 504, 'HN', 'Hondurans', 1, 1, 97, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 98, 'Hong Kong S.A.R.', 852, 'HK', 'Hong Kongers', 1, 1, 98, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, 99, 'Hungary', 36, 'HU', 'Hungarians', 1, 1, 99, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 100, 'Iceland', 354, 'IS', 'Icelanders', 1, 1, 100, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 101, 'India', 91, 'IN', 'Indians', 1, 1, 101, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 102, 'Indonesia', 62, 'ID', 'Indonesians', 1, 1, 102, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, 103, 'Iran', 98, 'IR', 'Iranians', 1, 1, 103, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, 104, 'Iraq', 964, 'IQ', 'Iraqis', 1, 1, 104, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 105, 'Ireland', 353, 'IE', 'Irish', 1, 1, 105, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 106, 'Israel', 972, 'IL', 'Israelis', 1, 1, 106, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 107, 'Italy', 39, 'IT', 'Italians', 1, 1, 107, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, 108, 'Jamaica', 1876, 'JM', 'Jamaicans', 1, 1, 108, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, 109, 'Japan', 81, 'JP', 'Japanese', 1, 1, 109, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, 110, 'Jersey', 44, 'XJ', 'Jersey', 1, 1, 110, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, 111, 'Jordan', 962, 'JO', 'Jordanians', 1, 1, 111, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(112, 112, 'Kazakhstan', 7, 'KZ', 'Kazakhs', 1, 1, 112, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, 113, 'Kenya', 254, 'KE', 'Kenyans', 1, 1, 113, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, 114, 'Kiribati', 686, 'KI', 'Kiribati', 1, 1, 114, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, 115, 'Korea North', 850, 'KP', 'Koreans', 1, 1, 115, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, 116, 'Korea South', 82, 'KR', 'Koreans', 1, 1, 116, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, 117, 'Kuwait', 965, 'KW', 'Kuwaitis', 1, 1, 117, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, 118, 'Kyrgyzstan', 996, 'KG', 'Kyrgyzs', 1, 1, 118, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, 119, 'Laos', 856, 'LA', 'Lao', 1, 1, 119, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 120, 'Latvia', 371, 'LV', 'Latvians', 1, 1, 120, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 121, 'Lebanon', 961, 'LB', 'Lebanese', 1, 1, 121, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 122, 'Lesotho', 266, 'LS', 'Lesotho', 1, 1, 122, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, 123, 'Liberia', 231, 'LR', 'Liberians', 1, 1, 123, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, 124, 'Libya', 218, 'LY', 'Libyans', 1, 1, 124, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 125, 'Liechtenstein', 423, 'LI', 'Liechtensteiners', 1, 1, 125, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 126, 'Lithuania', 370, 'LT', 'Lithuanians', 1, 1, 126, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, 127, 'Luxembourg', 352, 'LU', 'Luxembourgers', 1, 1, 127, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, 128, 'Macau S.A.R.', 853, 'MO', 'Macao', 1, 1, 128, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, 129, 'Macedonia', 389, 'MK', 'Macedonians', 1, 1, 129, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, 130, 'Madagascar', 261, 'MG', 'Malagasy', 1, 1, 130, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, 131, 'Malawi', 265, 'MW', 'Malawians', 1, 1, 131, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 132, 'Malaysia', 60, 'MY', 'Malaysians', 1, 1, 132, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 133, 'Maldives', 960, 'MV', 'Maldivians', 1, 1, 133, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 134, 'Mali', 223, 'ML', 'Malians', 1, 1, 134, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 135, 'Malta', 356, 'MT', 'Maltese', 1, 1, 135, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 136, 'Man (Isle of)', 44, 'XM', 'Manx', 1, 1, 136, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, 137, 'Marshall Islands', 692, 'MH', 'Marshallese', 1, 1, 137, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 138, 'Martinique', 596, 'MQ', 'Martinique', 1, 1, 138, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 139, 'Mauritania', 222, 'MR', 'Mauritians', 1, 1, 139, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, 140, 'Mauritius', 230, 'MU', 'Mauritanians', 1, 1, 140, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, 141, 'Mayotte', 269, 'YT', 'Mayotte', 1, 1, 141, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 142, 'Mexico', 52, 'MX', 'Mexicans', 1, 1, 142, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 143, 'Micronesia', 691, 'FM', 'Micronesians', 1, 1, 143, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 144, 'Moldova', 373, 'MD', 'Moldovans', 1, 1, 144, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, 145, 'Monaco', 377, 'MC', 'Monégasque', 1, 1, 145, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, 146, 'Mongolia', 976, 'MN', 'Mongolians', 1, 1, 146, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, 147, 'Montserrat', 1664, 'MS', 'Montenegrins', 1, 1, 147, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(148, 148, 'Morocco', 212, 'MA', 'Moroccans', 1, 1, 148, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(149, 149, 'Mozambique', 258, 'MZ', 'Mozambicans', 1, 1, 149, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(150, 150, 'Myanmar', 95, 'MM', 'Myanmar', 1, 1, 150, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(151, 151, 'Namibia', 264, 'NA', 'Namibians', 1, 1, 151, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, 152, 'Nauru', 674, 'NR', 'Naurans', 1, 1, 152, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(153, 153, 'Nepal', 977, 'NP', 'Nepalese', 1, 1, 153, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(154, 154, 'Netherlands Antilles', 599, 'AN', 'Netherlands Antilles', 1, 1, 154, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(155, 155, 'Netherlands The', 31, 'NL', 'Netherlands The', 1, 1, 155, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(156, 156, 'New Caledonia', 687, 'NC', 'New Caledonia', 1, 1, 156, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(157, 157, 'New Zealand', 64, 'NZ', 'New Zealanders', 1, 1, 157, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, 158, 'Nicaragua', 505, 'NI', 'Nicaraguans', 1, 1, 158, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(159, 159, 'Niger', 227, 'NE', 'Nigeriens', 1, 1, 159, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(160, 160, 'Nigeria', 234, 'NG', 'Nigerians', 1, 1, 160, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(161, 161, 'Niue', 683, 'NU', 'Niue', 1, 1, 161, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(162, 162, 'Norfolk Island', 672, 'NF', 'Norfolk Island', 1, 1, 162, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(163, 163, 'Northern Mariana Islands', 1670, 'MP', 'Northern Mariana Islands', 1, 1, 163, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(164, 164, 'Norway', 47, 'NO', 'Norwegians', 1, 1, 164, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(165, 165, 'Oman', 968, 'OM', 'Omani', 1, 1, 165, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(166, 166, 'Pakistan', 92, 'PK', 'Pakistanis', 1, 1, 166, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(167, 167, 'Palau', 680, 'PW', 'Palauans', 1, 1, 167, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(168, 168, 'Palestinian Territory Occupied', 970, 'PS', 'Palestinians', 1, 1, 168, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(169, 169, 'Panama', 507, 'PA', 'Panamanians', 1, 1, 169, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(170, 170, 'Papua new Guinea', 675, 'PG', 'Papua New Guineans', 1, 1, 170, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(171, 171, 'Paraguay', 595, 'PY', 'Paraguayans', 1, 1, 171, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(172, 172, 'Peru', 51, 'PE', 'Peruvians', 1, 1, 172, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(173, 173, 'Philippines', 63, 'PH', 'Philippinos', 1, 1, 173, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(174, 174, 'Pitcairn Island', 0, 'PN', 'Pitcairn Island', 1, 1, 174, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(175, 175, 'Poland', 48, 'PL', 'Poles', 1, 1, 175, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(176, 176, 'Portugal', 351, 'PT', 'Portuguese', 1, 1, 176, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(177, 177, 'Puerto Rico', 1787, 'PR', 'Puerto Ricans', 1, 1, 177, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(178, 178, 'Qatar', 974, 'QA', 'Qatari', 1, 1, 178, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(179, 179, 'Reunion', 262, 'RE', 'Réunionnais', 1, 1, 179, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(180, 180, 'Romania', 40, 'RO', 'Romanians', 1, 1, 180, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(181, 181, 'Russia', 70, 'RU', 'Russians', 1, 1, 181, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(182, 182, 'Rwanda', 250, 'RW', 'Rwandans', 1, 1, 182, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(183, 183, 'Saint Helena', 290, 'SH', 'Saint Helena', 1, 1, 183, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(184, 184, 'Saint Kitts And Nevis', 1869, 'KN', 'Saint Kitts and Nevis', 1, 1, 184, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(185, 185, 'Saint Lucia', 1758, 'LC', 'Saint Lucians', 1, 1, 185, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(186, 186, 'Saint Pierre and Miquelon', 508, 'PM', 'Saint Pierre and Miquelon', 1, 1, 186, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(187, 187, 'Saint Vincent And The Grenadines', 1784, 'VC', 'Saint Vincent And The Grenadines', 1, 1, 187, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(188, 188, 'Samoa', 684, 'WS', 'Samoans', 1, 1, 188, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(189, 189, 'San Marino', 378, 'SM', 'San Marino', 1, 1, 189, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(190, 190, 'Sao Tome and Principe', 239, 'ST', 'São Tomé and Príncipe', 1, 1, 190, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(191, 191, 'Saudi Arabia', 966, 'SA', 'Saudis', 1, 1, 191, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(192, 192, 'Senegal', 221, 'SN', 'Senegalese', 1, 1, 192, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(193, 193, 'Serbia', 381, 'RS', 'Serbs', 1, 1, 193, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(194, 194, 'Seychelles', 248, 'SC', 'Seychellois', 1, 1, 194, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(195, 195, 'Sierra Leone', 232, 'SL', 'Sierra Leoneans', 1, 1, 195, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(196, 196, 'Singapore', 65, 'SG', 'Singaporeans', 1, 1, 196, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(197, 197, 'Slovakia', 421, 'SK', 'Slovaks', 1, 1, 197, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(198, 198, 'Slovenia', 386, 'SI', 'Slovenes', 1, 1, 198, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(199, 199, 'Smaller Territories of the UK', 44, 'XG', 'Smaller Territories of the UK', 1, 1, 199, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(200, 200, 'Solomon Islands', 677, 'SB', 'Solomon Islanders', 1, 1, 200, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(201, 201, 'Somalia', 252, 'SO', 'Somalis', 1, 1, 201, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(202, 202, 'South Africa', 27, 'ZA', 'South Africans', 1, 1, 202, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(203, 203, 'South Georgia', 0, 'GS', 'South Georgia', 1, 1, 203, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(204, 204, 'South Sudan', 211, 'SS', 'South Sudan', 1, 1, 204, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(205, 205, 'Spain', 34, 'ES', 'Spaniards', 1, 1, 205, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(206, 206, 'Sri Lanka', 94, 'LK', 'Sri Lankans', 1, 1, 206, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(207, 207, 'Sudan', 249, 'SD', 'Sudanese', 1, 1, 207, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(208, 208, 'Suriname', 597, 'SR', 'Surinamese', 1, 1, 208, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(209, 209, 'Svalbard And Jan Mayen Islands', 47, 'SJ', 'Svalbard And Jan Mayen Islands', 1, 1, 209, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(210, 210, 'Swaziland', 268, 'SZ', 'Swazi', 1, 1, 210, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(211, 211, 'Sweden', 46, 'SE', 'Swedes', 1, 1, 211, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(212, 212, 'Switzerland', 41, 'CH', 'Swiss', 1, 1, 212, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(213, 213, 'Syria', 963, 'SY', 'Syrians', 1, 1, 213, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(214, 214, 'Taiwan', 886, 'TW', 'Taiwanese', 1, 1, 214, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(215, 215, 'Tajikistan', 992, 'TJ', 'Tajik', 1, 1, 215, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(216, 216, 'Tanzania', 255, 'TZ', 'Tanzanians', 1, 1, 216, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(217, 217, 'Thailand', 66, 'TH', 'Thais', 1, 1, 217, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(218, 218, 'Togo', 228, 'TG', 'Togolese', 1, 1, 218, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(219, 219, 'Tokelau', 690, 'TK', 'Tokelau', 1, 1, 219, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(220, 220, 'Tonga', 676, 'TO', 'Tongans', 1, 1, 220, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(221, 221, 'Trinidad And Tobago', 1868, 'TT', 'Trinidadians', 1, 1, 221, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(222, 222, 'Tunisia', 216, 'TN', 'Tunisians', 1, 1, 222, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(223, 223, 'Turkey', 90, 'TR', 'Turks', 1, 1, 223, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(224, 224, 'Turkmenistan', 7370, 'TM', 'Turkmenistan', 1, 1, 224, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(225, 225, 'Turks And Caicos Islands', 1649, 'TC', 'Turks And Caicos Islands', 1, 1, 225, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(226, 226, 'Tuvalu', 688, 'TV', 'Tuvaluans', 1, 1, 226, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(227, 227, 'Uganda', 256, 'UG', 'Ugandans', 1, 1, 227, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(228, 228, 'Ukraine', 380, 'UA', 'Ukrainians', 1, 1, 228, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(229, 229, 'United Arab Emirates', 971, 'AE', 'Emirati', 1, 1, 229, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(230, 230, 'United Kingdom', 44, 'GB', 'British', 1, 1, 230, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(231, 231, 'United States of America', 1, 'US', 'Americans', 1, 1, 231, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(232, 232, 'United States Minor Outlying Islands', 1, 'UM', 'Americans', 1, 1, 232, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(233, 233, 'Uruguay', 598, 'UY', 'Uruguayans', 1, 1, 233, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(234, 234, 'Uzbekistan', 998, 'UZ', 'Uzbeks', 1, 1, 234, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(235, 235, 'Vanuatu', 678, 'VU', 'Vanuatuans', 1, 1, 235, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(236, 236, 'Vatican City State (Holy See)', 39, 'VA', 'Vatican City State (Holy See)', 1, 1, 236, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(237, 237, 'Venezuela', 58, 'VE', 'Venezuelans', 1, 1, 237, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(238, 238, 'Vietnam', 84, 'VN', 'Vietnamese', 1, 1, 238, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(239, 239, 'Virgin Islands (British)', 1284, 'VG', 'Virgin Islands (British)', 1, 1, 239, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(240, 240, 'Virgin Islands (US)', 1340, 'VI', 'Virgin Islands (US)', 1, 1, 240, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(241, 241, 'Wallis And Futuna Islands', 681, 'WF', 'Wallis And Futuna Islands', 1, 1, 241, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(242, 242, 'Western Sahara', 212, 'EH', 'Western Sahara', 1, 1, 242, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(243, 243, 'Yemen', 967, 'YE', 'Yemenis', 1, 1, 243, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(244, 244, 'Yugoslavia', 38, 'YU', 'Yugoslavian', 1, 1, 244, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(245, 245, 'Zambia', 260, 'ZM', 'Zambians', 1, 1, 245, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(246, 246, 'Zimbabwe', 263, 'ZW', 'Zimbabweans', 1, 1, 246, 'en', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `direct_income`
--

DROP TABLE IF EXISTS `direct_income`;
CREATE TABLE IF NOT EXISTS `direct_income` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `income_from` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `unit_upgrade` int NOT NULL,
  `upgrade_amount` decimal(10,2) NOT NULL,
  `per` decimal(10,2) DEFAULT NULL,
  `income` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forex`
--

DROP TABLE IF EXISTS `forex`;
CREATE TABLE IF NOT EXISTS `forex` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `rate` float NOT NULL,
  `timestamp` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `level_income`
--

DROP TABLE IF EXISTS `level_income`;
CREATE TABLE IF NOT EXISTS `level_income` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `income_from` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `level` int NOT NULL,
  `unit_upgrade` int NOT NULL,
  `member_daily` decimal(10,2) NOT NULL,
  `per` decimal(10,2) NOT NULL,
  `daily` decimal(10,3) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matching_income`
--

DROP TABLE IF EXISTS `matching_income`;
CREATE TABLE IF NOT EXISTS `matching_income` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `leftB` int DEFAULT NULL,
  `rightB` int DEFAULT NULL,
  `totalLeftB` int DEFAULT NULL,
  `totalRightB` int DEFAULT NULL,
  `matching` int DEFAULT NULL,
  `income` decimal(10,2) DEFAULT NULL,
  `leftC` int DEFAULT NULL,
  `rightC` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_wallet` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `wallet_address` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address_hex` text COLLATE utf8mb4_general_ci,
  `private_key` text COLLATE utf8mb4_general_ci,
  `public_key` text COLLATE utf8mb4_general_ci,
  `username` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `wallet` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sponsor_id` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `upline` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `position` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address1` text COLLATE utf8mb4_general_ci,
  `address2` text COLLATE utf8mb4_general_ci,
  `zip` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` date DEFAULT NULL,
  `country_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `wallet_address` (`wallet_address`),
  UNIQUE KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=380 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `member_id`, `user_wallet`, `wallet_address`, `address_hex`, `private_key`, `public_key`, `username`, `wallet`, `sponsor_id`, `upline`, `position`, `name`, `email`, `phone`, `image`, `address1`, `address2`, `zip`, `password`, `dob`, `created_at`, `updated_at`, `country_code`, `status`) VALUES
(1, '10001', '0x39d31416AE0f14d60152F70B55B3818B7023A1c9', '0x45c2d0f5d3ee6009b39a1e1b89e3cfb6a6710bb7', '', '', '', '10001', 39070.00, NULL, NULL, NULL, 'Crowd Wings', 'veeneshsaxena1@gmail.com', '9999999999', '1703759684_00e27046ce35ecf07dd8.jpg', NULL, NULL, NULL, 'hjj55$$55', '1990-06-01', '2023-12-05 00:00:00', '0000-00-00', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `message_from` int NOT NULL,
  `message_to` int NOT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `heading` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_on` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

DROP TABLE IF EXISTS `otp`;
CREATE TABLE IF NOT EXISTS `otp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `otp` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `otp_for` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `valid_till` datetime NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reward`
--

DROP TABLE IF EXISTS `reward`;
CREATE TABLE IF NOT EXISTS `reward` (
  `id` int NOT NULL AUTO_INCREMENT,
  `business` int NOT NULL,
  `leg1` int NOT NULL,
  `leg2` int NOT NULL,
  `leg3` int NOT NULL,
  `one_time` int NOT NULL,
  `monthly` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roi`
--

DROP TABLE IF EXISTS `roi`;
CREATE TABLE IF NOT EXISTS `roi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `up_id` int NOT NULL,
  `up_date` date DEFAULT NULL,
  `up_amount` decimal(10,2) NOT NULL,
  `income` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id` (`member_id`,`up_id`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE IF NOT EXISTS `team` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` varchar(50) NOT NULL,
  `level` int NOT NULL,
  `level_member` varchar(50) NOT NULL,
  `id_check` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `team_matching`
--

DROP TABLE IF EXISTS `team_matching`;
CREATE TABLE IF NOT EXISTS `team_matching` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_member` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `spredd` decimal(10,5) NOT NULL,
  `image` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `image2` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `live_rate` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `txn_details`
--

DROP TABLE IF EXISTS `txn_details`;
CREATE TABLE IF NOT EXISTS `txn_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `type` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `transfer_from` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hash` text COLLATE utf8mb4_general_ci,
  `upgrade_id` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upgrades`
--

DROP TABLE IF EXISTS `upgrades`;
CREATE TABLE IF NOT EXISTS `upgrades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` int NOT NULL,
  `unit_upgrade` int NOT NULL,
  `upgrade_amount` decimal(10,3) NOT NULL,
  `withdrawal_limit` decimal(10,3) NOT NULL,
  `daily` decimal(10,3) NOT NULL DEFAULT '0.000',
  `date` datetime NOT NULL,
  `capping` decimal(10,2) NOT NULL DEFAULT '0.00',
  `capping_copy` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usdt_transfer`
--

DROP TABLE IF EXISTS `usdt_transfer`;
CREATE TABLE IF NOT EXISTS `usdt_transfer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `add_fund_id` int NOT NULL,
  `amount` decimal(10,6) NOT NULL,
  `from_wallet` text COLLATE utf8mb4_general_ci NOT NULL,
  `to_wallet` text COLLATE utf8mb4_general_ci NOT NULL,
  `hash_code` text COLLATE utf8mb4_general_ci NOT NULL,
  `date` datetime NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `balance` float NOT NULL,
  `refer_id` varchar(15) NOT NULL,
  `franchise_id` varchar(15) NOT NULL,
  `type` varchar(20) NOT NULL,
  `name` varchar(25) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(50) NOT NULL,
  `city` varchar(25) NOT NULL,
  `state` varchar(25) NOT NULL,
  `pin` varchar(10) NOT NULL,
  `pan` varchar(15) NOT NULL,
  `bank` varchar(50) NOT NULL,
  `account_no` varchar(20) NOT NULL,
  `account_holder` varchar(25) NOT NULL,
  `ifsc` varchar(15) NOT NULL,
  `role` varchar(15) NOT NULL DEFAULT 'franchise',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `balance`, `refer_id`, `franchise_id`, `type`, `name`, `phone`, `address`, `city`, `state`, `pin`, `pan`, `bank`, `account_no`, `account_holder`, `ifsc`, `role`) VALUES
(1, 'admin', 'dc06698f0e2e75751545455899adccc3', '', 0, 'abc', 'aaa', '', '', '', '', '', '', '', '', '', '', '', '', 'admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
