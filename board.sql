-- --------------------------------------------------------
-- 호스트:                          127.0.0.1
-- 서버 버전:                        10.1.21-MariaDB - mariadb.org binary distribution
-- 서버 OS:                        Win32
-- HeidiSQL 버전:                  10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- audtla 데이터베이스 구조 내보내기
CREATE DATABASE IF NOT EXISTS `audtla` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `audtla`;

-- 테이블 audtla.board 구조 내보내기
CREATE TABLE IF NOT EXISTS `board` (
  `NO` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(50) DEFAULT NULL,
  `CONTENTS` text,
  `PW` varchar(50) DEFAULT NULL,
  `WRITER` varchar(50) DEFAULT NULL,
  `IP` varchar(50) DEFAULT NULL,
  `HIT` int(10) unsigned DEFAULT '0',
  `IMAGE_ID` int(10) unsigned DEFAULT NULL,
  `COMMENT_CNT` int(11) unsigned DEFAULT '0',
  `DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`NO`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.
-- 테이블 audtla.comment 구조 내보내기
CREATE TABLE IF NOT EXISTS `comment` (
  `BOARD_NO` int(11) unsigned DEFAULT NULL,
  `NO` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `CONTENTS` varchar(50) DEFAULT NULL,
  `WRITER` varchar(50) DEFAULT NULL,
  `DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`NO`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.
-- 테이블 audtla.image 구조 내보내기
CREATE TABLE IF NOT EXISTS `image` (
  `IMAGE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `IMAGE_NAME` varchar(50) DEFAULT NULL,
  `PATH` varchar(50) DEFAULT NULL,
  `SIZE` int(11) DEFAULT NULL,
  `REG_TIME` datetime DEFAULT NULL,
  PRIMARY KEY (`IMAGE_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.
-- 테이블 audtla.member 구조 내보내기
CREATE TABLE IF NOT EXISTS `member` (
  `MEMBER_ID` varchar(50) NOT NULL,
  `MEMBER_PW` varchar(50) DEFAULT NULL,
  `MEMBER_DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`MEMBER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 내보낼 데이터가 선택되어 있지 않습니다.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
