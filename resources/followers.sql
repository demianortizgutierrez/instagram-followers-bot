SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `followers`
--
CREATE DATABASE IF NOT EXISTS `followers` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `followers`;

-- --------------------------------------------------------

--
-- Table structure for table `invites`
--

DROP TABLE IF EXISTS `invites`;
CREATE TABLE `invites` (
  `id` int(11) NOT NULL,
  `account` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `taken_from` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  `converted` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;