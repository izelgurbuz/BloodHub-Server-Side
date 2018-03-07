-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 07, 2018 at 02:06 PM
-- Server version: 5.5.59-cll
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mustafa2_bloodhub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `firstname`, `lastname`, `email`, `password`, `token`, `telephone`) VALUES
(1, 'mustafa', 'Mustafa', 'Culban', 'mustafaculban1@gmail.com', 'm02800280c', '1234', '5396768149'),
(2, 'izel', 'Izel', 'Gurbuz', 'izelgurbuz@gmail.com', 'Esrakar4', 'izigo', '5317803034'),
(3, 'bloodhub', 'Bloodhub', 'Inc.', 'info@bloodhub.site', 'bloodhub', 'abc', '000000000');

-- --------------------------------------------------------

--
-- Table structure for table `apikeys`
--

CREATE TABLE `apikeys` (
  `id` int(11) NOT NULL,
  `apikey` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `awaitingDonor`
--

CREATE TABLE `awaitingDonor` (
  `id` int(11) NOT NULL,
  `patientID` int(11) NOT NULL,
  `donorID` int(11) NOT NULL,
  `bloodreqID` int(11) NOT NULL,
  `datestamp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `awaitingDonor`
--

INSERT INTO `awaitingDonor` (`id`, `patientID`, `donorID`, `bloodreqID`, `datestamp`) VALUES
(10, 1, 5, 15, '2018-03-02 23:44:48'),
(11, 1, 5, 9, '2018-03-03 19:42:09'),
(12, 5, 1, 22, '2018-03-06 21:37:38');

-- --------------------------------------------------------

--
-- Table structure for table `blogpost`
--

CREATE TABLE `blogpost` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `post_title` text NOT NULL,
  `post_text` text NOT NULL,
  `post_long_text` text NOT NULL,
  `image_link` text NOT NULL,
  `post_link` text NOT NULL,
  `post_date` varchar(255) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blogpost`
--

INSERT INTO `blogpost` (`id`, `uid`, `post_title`, `post_text`, `post_long_text`, `image_link`, `post_link`, `post_date`, `active`) VALUES
(1, 1, 'Blood Connects Us All in a Soul', 'In many countries, demand exceeds supply, and blood services face the challenge of making blood available for patient. ', '', 'images/blog_1.jpg', '', 'April 4, 2017', 1),
(2, 1, 'Give Blood and Save three Lives', 'To save a life, you don\'t need to use muscle. By donating just one unit of blood, you can save three lives or even several lives.', '', 'images/blog_2.jpg', '', 'April 4, 2017', 1),
(3, 1, 'Why Should I donate Blood ?', 'Blood is the most precious gift that anyone can give to another person.Donating blood not only saves the life also donors.', '', 'images/blog_3.jpg', '', 'April 4, 2017', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bloodrequests`
--

CREATE TABLE `bloodrequests` (
  `id` int(11) NOT NULL,
  `senderID` int(11) NOT NULL,
  `receiverID` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `msgID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bloodrequests`
--

INSERT INTO `bloodrequests` (`id`, `senderID`, `receiverID`, `type`, `msgID`) VALUES
(5, 5, 2, 'push', 1),
(6, 5, 1, 'push', 1),
(7, 1, 2, 'mail', 1),
(8, 1, 2, 'push', 1),
(9, 1, 5, 'push', 1),
(10, 1, 5, 'push', 1),
(11, 2, 5, 'push', 1),
(12, 2, 1, 'push', 1),
(13, 2, 5, 'push', 1),
(14, 2, 1, 'push', 1),
(15, 1, 5, 'mail', 3),
(16, 1, 5, 'mail', 2),
(17, 1, 8, 'push', 1),
(18, 1, 8, 'push', 1),
(19, 5, 1, 'push', 2),
(22, 5, 1, 'mail', 3),
(23, 5, 1, 'push', 4);

-- --------------------------------------------------------

--
-- Table structure for table `bloodRequestsMessage`
--

CREATE TABLE `bloodRequestsMessage` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `msg` text NOT NULL,
  `datestamp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bloodRequestsMessage`
--

INSERT INTO `bloodRequestsMessage` (`id`, `title`, `msg`, `datestamp`) VALUES
(1, 'Mustafa Culban icin acil AB- kan ihtiyaci', 'Ataturk Arastirma hastanesinde yatmakta olan Mustafa Culban hasta icin acil AB- grubu kan gerekmektedir. Bu mesaj, hastaneye olan mesafenize gore gonderilmistir.', '2018-02-21 21:29:02'),
(2, 'Mustafa Culban icin acil A- kan ihtiyaci.', 'sisli etfal hastanesinde yatmakta olan Mustafa Culban hasta icin acil A- grubu kan gerekmektedir. Bu mesaj, hastaneye olan mesafenize gore gonderilmistir.', '2018-02-23 16:53:04'),
(3, 'Mustafa Oz icin acil 0- kan ihtiyaci.', 'Ataturk Arastirma hastanesinde yatmakta olan Mustafa Culban hasta icin acil 0- grubu kan gerekmektedir. Bu mesaj, hastaneye olan \n						mesafenize gore gonderilmistir.', '2018-02-28 20:21:36'),
(4, 'Mustafa Culban icin acil 0- kan ihtiyaci.', 'Ataturk Arastirma hastanesinde yatmakta olan Mustafa Culban hasta icin acil 0- grubu kan gerekmektedir. Bu mesaj, hastaneye olan \n				mesafenize gore gonderilmistir.', '2018-02-28 20:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `donorRejecting`
--

CREATE TABLE `donorRejecting` (
  `id` int(11) NOT NULL,
  `patientID` int(11) NOT NULL,
  `donorID` int(11) NOT NULL,
  `bloodreqID` int(11) NOT NULL,
  `datestamp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` int(11) NOT NULL,
  `patientID` int(11) NOT NULL,
  `donorID` int(11) NOT NULL,
  `bloodreqID` int(11) NOT NULL,
  `whenBecameAwaiting` varchar(50) NOT NULL,
  `whenBecameDonor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `patientID`, `donorID`, `bloodreqID`, `whenBecameAwaiting`, `whenBecameDonor`) VALUES
(17, 5, 2, 1, '2018-02-28 23:33:26', '2018-03-01 00:24:03'),
(18, 1, 5, 16, '2018-03-02 22:12:11', '2018-03-07 00:02:52');

-- --------------------------------------------------------

--
-- Table structure for table `emergencyFiveLists`
--

CREATE TABLE `emergencyFiveLists` (
  `id` int(11) NOT NULL,
  `requestOwnerID` int(11) NOT NULL,
  `first_ID` int(11) NOT NULL,
  `first_isApproved` int(11) NOT NULL,
  `first_date` varchar(50) NOT NULL,
  `second_ID` int(11) NOT NULL,
  `second_isApproved` int(11) NOT NULL,
  `second_date` varchar(50) NOT NULL,
  `third_ID` int(11) NOT NULL,
  `third_isApproved` int(11) NOT NULL,
  `third_date` varchar(50) NOT NULL,
  `fourth_ID` int(11) NOT NULL,
  `fourth_isApproved` int(11) NOT NULL,
  `fourth_date` varchar(50) NOT NULL,
  `fifth_ID` int(11) NOT NULL,
  `fifth_isApproved` int(11) NOT NULL,
  `fifth_date` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `emergencyFiveLists`
--

INSERT INTO `emergencyFiveLists` (`id`, `requestOwnerID`, `first_ID`, `first_isApproved`, `first_date`, `second_ID`, `second_isApproved`, `second_date`, `third_ID`, `third_isApproved`, `third_date`, `fourth_ID`, `fourth_isApproved`, `fourth_date`, `fifth_ID`, `fifth_isApproved`, `fifth_date`) VALUES
(1, 1, 5, -1, '2018-03-02 23:05:50', 8, 1, '2018-03-01 23:39:41', 2, 1, '2018-02-23 14:57:55', 6, 1, '2018-03-01 23:41:35', 7, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `startDate` varchar(255) NOT NULL,
  `endDate` varchar(255) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `name`, `city`, `content`, `startDate`, `endDate`, `latitude`, `longitude`) VALUES
(1, 'Bilkent Kan Cadiri', 'Ankara', 'Bilkent de kan cadirimiza davetlisiniz.', '15.02.2018', '15.02.2018', 39.868043, 32.749089),
(3, 'Kan Deneme', 'Ankara', 'Bilkent de kan cadirimiza davetlisiniz.', '16.02.2018', '15.04.2018', 39.868043, 32.749089);

-- --------------------------------------------------------

--
-- Table structure for table `firebaseTokens`
--

CREATE TABLE `firebaseTokens` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `firebaseTokens`
--

INSERT INTO `firebaseTokens` (`id`, `uid`, `token`) VALUES
(10, 2, 'eEbe-VC4kBM:APA91bEo1ZRW3sCt4kptqsdh7zxnbPMtXtrj6ltcZA7wXdf4ZB3VuBqDZS1lSW7sOD_PDiE2wUQj7FaGGuewxHnCHYr265h-_AW5Ip5ZgDnVRqV5sj1OlMWxOZ8TtFlFvMlZZWiwtOrT'),
(11, 5, 'd6lnj0DHOmU:APA91bFgWYd2UKK1oyf0P1H3-xlw2ePrmAr0DXDobz9wJI_S5Ic13DIBoD1jA3VsuQPfPkr1wtUK3aEESAUqOG9uuaduMyvj5NetA1q8BLvddRMdCElwOi890K8mXVyfFcNk7F4bgRes'),
(12, 1, 'eEbe-VC4kBM:APA91bEo1ZRW3sCt4kptqsdh7zxnbPMtXtrj6ltcZA7wXdf4ZB3VuBqDZS1lSW7sOD_PDiE2wUQj7FaGGuewxHnCHYr265h-_AW5Ip5ZgDnVRqV5sj1OlMWxOZ8TtFlFvMlZZWiwtOrT'),
(13, 8, 'dekWe5XcBgI:APA91bFSYy9jZmVD3srrknCe8tvRfhy1J2mlsh9tKNoJ8OGyKqF2o7By4P6vGYqq9NxUYErLRswl3UIUd7SHPOimzRNEvCGdiTACtlnMKFwk7baCmUuIfcTd1_wKnxo3XSSkCNOa5pca'),
(14, 5, 'd6lnj0DHOmU:APA91bFgWYd2UKK1oyf0P1H3-xlw2ePrmAr0DXDobz9wJI_S5Ic13DIBoD1jA3VsuQPfPkr1wtUK3aEESAUqOG9uuaduMyvj5NetA1q8BLvddRMdCElwOi890K8mXVyfFcNk7F4bgRes'),
(15, 5, 'd6lnj0DHOmU:APA91bFgWYd2UKK1oyf0P1H3-xlw2ePrmAr0DXDobz9wJI_S5Ic13DIBoD1jA3VsuQPfPkr1wtUK3aEESAUqOG9uuaduMyvj5NetA1q8BLvddRMdCElwOi890K8mXVyfFcNk7F4bgRes'),
(16, 5, 'd6lnj0DHOmU:APA91bFgWYd2UKK1oyf0P1H3-xlw2ePrmAr0DXDobz9wJI_S5Ic13DIBoD1jA3VsuQPfPkr1wtUK3aEESAUqOG9uuaduMyvj5NetA1q8BLvddRMdCElwOi890K8mXVyfFcNk7F4bgRes'),
(17, 5, 'd6lnj0DHOmU:APA91bFgWYd2UKK1oyf0P1H3-xlw2ePrmAr0DXDobz9wJI_S5Ic13DIBoD1jA3VsuQPfPkr1wtUK3aEESAUqOG9uuaduMyvj5NetA1q8BLvddRMdCElwOi890K8mXVyfFcNk7F4bgRes'),
(18, 5, 'd6lnj0DHOmU:APA91bFgWYd2UKK1oyf0P1H3-xlw2ePrmAr0DXDobz9wJI_S5Ic13DIBoD1jA3VsuQPfPkr1wtUK3aEESAUqOG9uuaduMyvj5NetA1q8BLvddRMdCElwOi890K8mXVyfFcNk7F4bgRes'),
(19, 5, 'd6lnj0DHOmU:APA91bFgWYd2UKK1oyf0P1H3-xlw2ePrmAr0DXDobz9wJI_S5Ic13DIBoD1jA3VsuQPfPkr1wtUK3aEESAUqOG9uuaduMyvj5NetA1q8BLvddRMdCElwOi890K8mXVyfFcNk7F4bgRes');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `hastane_adi` varchar(255) NOT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','0-','0+') NOT NULL,
  `message` text,
  `longitude` varchar(100) NOT NULL,
  `latitude` varchar(100) NOT NULL,
  `n_type` enum('push','sms','mail') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `preDefinedLocations`
--

CREATE TABLE `preDefinedLocations` (
  `id` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `preDefinedLocations`
--

INSERT INTO `preDefinedLocations` (`id`, `city`, `latitude`, `longitude`) VALUES
(1, 'Ankara', '39.868043', '32.749089');

-- --------------------------------------------------------

--
-- Table structure for table `sentSMS`
--

CREATE TABLE `sentSMS` (
  `id` int(11) NOT NULL,
  `toNo` varchar(255) NOT NULL,
  `sentDate` varchar(255) NOT NULL,
  `msgUniqueID` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sentSMS`
--

INSERT INTO `sentSMS` (`id`, `toNo`, `sentDate`, `msgUniqueID`) VALUES
(1, '5396768149', '09-11-2017', '1854123229'),
(2, '5396768149', '10-11-2017', '1854123239');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `about` text NOT NULL,
  `url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`about`, `url`) VALUES
('', 'http://cs491-2.mustafaculban.net'),
('BloodHub aims to bring many easiness to blood donation process. There will be\r\ntwo types of users. First one is the users who want to donate their blood. They can see\r\navailable places to donate their blood from interactive map of application. They will\r\nbe notified when there is a need for blood. Application will send an notification alert\r\nto their phone. Users from the near of place where there is a need for blood will be\r\nalerted. Their location will be taken with their phone’s location services. Second type \r\n4\r\nof users are people who look for blood for their friends, relatives or themselves. They\r\ncan interact with volunteers by using BloodHub.', '');

-- --------------------------------------------------------

--
-- Table structure for table `tokenUsage`
--

CREATE TABLE `tokenUsage` (
  `id` int(11) NOT NULL,
  `adminID` int(11) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tokenUsage`
--

INSERT INTO `tokenUsage` (`id`, `adminID`, `count`) VALUES
(1, 1, 0),
(2, 3, 11),
(3, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) CHARACTER SET latin1 NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `surname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 NOT NULL,
  `salt` varchar(10) CHARACTER SET latin1 NOT NULL,
  `email` varchar(155) CHARACTER SET latin1 NOT NULL,
  `identitiyNum` varchar(255) CHARACTER SET latin1 NOT NULL,
  `identitySalt` varchar(10) CHARACTER SET latin1 NOT NULL,
  `bloodType` varchar(3) CHARACTER SET latin1 NOT NULL,
  `birthdate` varchar(11) CHARACTER SET latin1 NOT NULL,
  `address` varchar(255) CHARACTER SET latin1 NOT NULL,
  `telephone` varchar(15) CHARACTER SET latin1 NOT NULL,
  `available` int(1) NOT NULL,
  `last_login_ip` varchar(25) COLLATE utf8_turkish_ci NOT NULL,
  `last_login_date` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `last_login_time` varchar(50) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `firstname`, `surname`, `password`, `salt`, `email`, `identitiyNum`, `identitySalt`, `bloodType`, `birthdate`, `address`, `telephone`, `available`, `last_login_ip`, `last_login_date`, `last_login_time`) VALUES
(1, 'mustafaculban', 'Mustafa', 'Culban', 'hpwX8Xee9eXpZNDX9V6S/JO/2b43N2UyMGM4ZWFh', '77e20c8eaa', 'mustafaculban1@gmail.com', 'xJScYofeE8AC2udPsDv8yRpPiCo5ZDNjZjg5YWUz', '77e20c8eaa', '0-', '16041995', 'denemeAdress', '5396768149', 1, '178.247.39.249', 'Saturday, March 3rd, 2018', '10:53 PM'),
(2, 'karamusluk', 'Mustafa', 'Culban', 'R8jB6Ra0YZPJI9MiV7+FBkaNLl5hY2Y2YzNjMWVm', 'acf6c3c1ef', 'karamusluk@gmail.com', 'bAEk/vbYJcaBpBzmE4zTsh1crXs0YWQ4NzEwMzJl', 'acf6c3c1ef', 'AB+', '16041995', 'denemeAdress', '05396768149', 1, '5.24.184.189', 'Tuesday, February 20th, 2018', '8:37 PM'),
(5, 'izelgurbuz', 'Ä°zel', 'GÃ¼rbÃ¼z', 'hFSjvZf3XjF/Mh1woJMz02Fm64swMmE1MjY3YjRj', '02a5267b4c', 'izelgurbuz@gmail.com', 'mpNCD8HMBpY+k+DRGNv593TY3KcyMzE5ZGYzYWFl', '02a5267b4c', 'AB-', '11081995', 'Ankara Bilkent ', '5317803034', 1, '178.247.161.108', 'Wednesday, March 7th, 2018', '1:09 AM'),
(6, 'karamusluk', 'Mustafa', 'Culban', '+HFDmKc5dSHXYX18fQ4rRNOnXLYzMDQyZjNjNGI5', '3042f3c4b9', 'mustafaculban@gmail.com', 'PesQbvCy+VrAqr7eqTUkiiWCACgyMTdlMDMxODVl', '3042f3c4b9', '0+', '16041995', 'denemeAdress', '05396768149', 0, '176.33.240.215', 'Wednesday, January 24th, ', '11:09 AM'),
(7, 'izelgurbuz', 'Ä°zel', 'GÃ¼rbÃ¼z', 'qa7P9VEPpEyD2NLvgtZJi27RC0E3OTI4NGE4NDNk', '79284a843d', 'izelgurbuz1@gmail.com', 'nHsM4A4j2DbbwgwJ6LTjxYCK4ts1ODI5NThmYTNk', '79284a843d', '0+', '11081995', 'denemeAdress', '05317803034', 0, '176.33.240.215', 'Wednesday, January 24th, ', '11:09 AM'),
(8, 'orcun', 'Mehmet OrÃ§un', 'YalÃ§Ä±n', '7KkTCg6CaeZcQ5X6HEAf186t4hkxOWQ0NjM1Yjg5', '19d4635b89', 'orcunyalcin@gmail.com', 'n/RiEvZoV2xYIfjVL52qx1iiIsg1ODQ5YmQ3Zjdl', '19d4635b89', 'A-', '23011994', 'denemeAdress', '05548825328', 1, '46.154.42.26', 'Friday, February 23rd, 2018', '3:15 PM');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `awaitingDonor`
--
ALTER TABLE `awaitingDonor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donorID` (`donorID`),
  ADD KEY `patientID` (`patientID`),
  ADD KEY `bloodreqID` (`bloodreqID`);

--
-- Indexes for table `blogpost`
--
ALTER TABLE `blogpost`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `bloodrequests`
--
ALTER TABLE `bloodrequests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `senderID` (`senderID`),
  ADD KEY `receiverID` (`receiverID`),
  ADD KEY `msgID` (`msgID`);

--
-- Indexes for table `bloodRequestsMessage`
--
ALTER TABLE `bloodRequestsMessage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donorRejecting`
--
ALTER TABLE `donorRejecting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donorID` (`donorID`),
  ADD KEY `patientID` (`patientID`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patientID` (`patientID`),
  ADD KEY `donorID` (`donorID`);

--
-- Indexes for table `emergencyFiveLists`
--
ALTER TABLE `emergencyFiveLists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requestOwnerID` (`requestOwnerID`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `firebaseTokens`
--
ALTER TABLE `firebaseTokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firebaseTokens_ibfk_1` (`uid`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`,`datetime`);

--
-- Indexes for table `preDefinedLocations`
--
ALTER TABLE `preDefinedLocations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sentSMS`
--
ALTER TABLE `sentSMS`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokenUsage`
--
ALTER TABLE `tokenUsage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adminID` (`adminID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `awaitingDonor`
--
ALTER TABLE `awaitingDonor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `blogpost`
--
ALTER TABLE `blogpost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bloodrequests`
--
ALTER TABLE `bloodrequests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `bloodRequestsMessage`
--
ALTER TABLE `bloodRequestsMessage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donorRejecting`
--
ALTER TABLE `donorRejecting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `emergencyFiveLists`
--
ALTER TABLE `emergencyFiveLists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `firebaseTokens`
--
ALTER TABLE `firebaseTokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preDefinedLocations`
--
ALTER TABLE `preDefinedLocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sentSMS`
--
ALTER TABLE `sentSMS`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tokenUsage`
--
ALTER TABLE `tokenUsage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `awaitingDonor`
--
ALTER TABLE `awaitingDonor`
  ADD CONSTRAINT `awaitingDonor_ibfk_1` FOREIGN KEY (`donorID`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `awaitingDonor_ibfk_2` FOREIGN KEY (`patientID`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `awaitingDonor_ibfk_3` FOREIGN KEY (`bloodreqID`) REFERENCES `bloodrequests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `blogpost`
--
ALTER TABLE `blogpost`
  ADD CONSTRAINT `blogpost_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`);

--
-- Constraints for table `bloodrequests`
--
ALTER TABLE `bloodrequests`
  ADD CONSTRAINT `bloodrequests_ibfk_1` FOREIGN KEY (`senderID`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `bloodrequests_ibfk_2` FOREIGN KEY (`receiverID`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `bloodrequests_ibfk_3` FOREIGN KEY (`msgID`) REFERENCES `bloodRequestsMessage` (`id`);

--
-- Constraints for table `donorRejecting`
--
ALTER TABLE `donorRejecting`
  ADD CONSTRAINT `donorRejecting_ibfk_1` FOREIGN KEY (`donorID`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `donorRejecting_ibfk_2` FOREIGN KEY (`patientID`) REFERENCES `user` (`id`);

--
-- Constraints for table `donors`
--
ALTER TABLE `donors`
  ADD CONSTRAINT `donors_ibfk_1` FOREIGN KEY (`patientID`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `donors_ibfk_2` FOREIGN KEY (`donorID`) REFERENCES `user` (`id`);

--
-- Constraints for table `emergencyFiveLists`
--
ALTER TABLE `emergencyFiveLists`
  ADD CONSTRAINT `emergencyFiveLists_ibfk_1` FOREIGN KEY (`requestOwnerID`) REFERENCES `user` (`id`);

--
-- Constraints for table `firebaseTokens`
--
ALTER TABLE `firebaseTokens`
  ADD CONSTRAINT `firebaseTokens_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tokenUsage`
--
ALTER TABLE `tokenUsage`
  ADD CONSTRAINT `tokenUsage_ibfk_1` FOREIGN KEY (`adminID`) REFERENCES `admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
