-- --------------------------------------------------------
-- Table structure for table `image-browser-types`
-- --------------------------------------------------------

CREATE TABLE `image-browser-types` (
  `id` int(11) NOT NULL,
  `image_id` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `type` enum('event','result','person','thing','location','other') NOT NULL,
  `annotator` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `image-browser-types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `image_id` (`image_id`(191));

ALTER TABLE `image-browser-types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Table structure for table `image-browser-annotations`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `image-browser-annotations` (
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

ALTER TABLE `image-browser-annotations`
    ADD PRIMARY KEY `id` (`id`),
    ADD KEY `image_id` (`image_id`(191));

ALTER TABLE `image-browser-annotations`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Table structure for table `image-browser-comments`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `image-browser-comments` (
  `id` int NOT NULL,
  `image_id` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `comment` text COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

ALTER TABLE `image-browser-comments`
    ADD PRIMARY KEY `id` (`id`),
    ADD KEY `image_id` (`image_id`(191));

ALTER TABLE `image-browser-comments`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Table structure for table `image-browser-annotators`
-- --------------------------------------------------------

CREATE TABLE `image-browser-annotators` (
    `annotator` varchar(25) COLLATE utf8mb4_bin NOT NULL,
    `password` varchar(25) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `image-browser-annotators`
    ADD PRIMARY KEY (`annotator`);
