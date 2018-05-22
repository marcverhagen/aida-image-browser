-- --------------------------------------------------------
-- Table structure for table `ib-annotations-icrels`
-- --------------------------------------------------------

CREATE TABLE `ib-annotations-icrels` (
  `id` int(11) NOT NULL,
  `image_id` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `relation` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `annotator` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `ib-annotations-icrels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `image_id` (`image_id`(191));

ALTER TABLE `ib-annotations-icrels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Table structure for table `ib-annotations-voxml`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `ib-annotations-voxml` (
    `id` int NOT NULL,
    `image_id` varchar(255) COLLATE utf8mb4_bin NOT NULL,
    `objects` varchar(255) COLLATE utf8mb4_bin NOT NULL,
    `attributes` varchar(255) COLLATE utf8mb4_bin NOT NULL,
    `relations` varchar(255) COLLATE utf8mb4_bin NOT NULL,
    `events` varchar(255) COLLATE utf8mb4_bin NOT NULL,
    `habitat` varchar(255) COLLATE utf8mb4_bin NOT NULL,
    `annotator` varchar(255) COLLATE utf8mb4_bin NOT NULL,
    `comment` mediumtext COLLATE utf8mb4_bin NOT NULL,
    `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

ALTER TABLE `ib-annotations-voxml`
    ADD PRIMARY KEY `id` (`id`),
    ADD KEY `image_id` (`image_id`(191));

ALTER TABLE `ib-annotations-voxml`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Table structure for table `ib-comments`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `ib-comments` (
  `id` int NOT NULL,
  `image_id` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `comment` text COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

ALTER TABLE `ib-comments`
    ADD PRIMARY KEY `id` (`id`),
    ADD KEY `image_id` (`image_id`(191));

ALTER TABLE `ib-comments`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Table structure for table `ib-annotators`
-- --------------------------------------------------------

CREATE TABLE `ib-annotators` (
    `annotator` varchar(25) COLLATE utf8mb4_bin NOT NULL,
    `password` varchar(25) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `ib-annotators`
    ADD PRIMARY KEY (`annotator`);

-- --------------------------------------------------------
-- Table structure for table `ib-tasks`
-- --------------------------------------------------------

CREATE TABLE `ib-tasks` (
    `id` int(11) NOT NULL,
    `annotator` varchar(10) COLLATE utf8mb4_bin NOT NULL,
    `image` varchar(50) COLLATE utf8mb4_bin NOT NULL,
    `type` enum('ImageCaptionRelation','VoxML') COLLATE utf8mb4_bin NOT NULL,
    `done` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `ib-tasks`
    ADD PRIMARY KEY `id` (`id`);

ALTER TABLE `ib-tasks`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
