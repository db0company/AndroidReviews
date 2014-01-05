--
-- Database: `androidreviews`
--

-- --------------------------------------------------------

--
-- Table structure for table `apps`
--

CREATE TABLE IF NOT EXISTS `apps` (
  `id` varchar(42) NOT NULL,
  `packageName` varchar(255) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `rating` float NOT NULL,
  `ratingsCount` int(11) NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `contactEmail` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `apps_tracker`
--

CREATE TABLE IF NOT EXISTS `apps_tracker` (
  `app_id` varchar(42) NOT NULL,
  `user` varchar(254) NOT NULL,
  PRIMARY KEY (`app_id`,`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` varchar(255) NOT NULL COMMENT 'appId + authorId + CreationTime',
  `app_id` varchar(42) NOT NULL,
  `creationTime` bigint(20) NOT NULL,
  `author` varchar(32) NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rating` smallint(6) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reviews_tracker`
--

CREATE TABLE IF NOT EXISTS `reviews_tracker` (
  `review_id` varchar(255) NOT NULL,
  `user` varchar(42) NOT NULL,
  `read` tinyint(1) NOT NULL,
  PRIMARY KEY (`review_id`,`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;