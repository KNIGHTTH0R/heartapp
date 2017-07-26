--
-- Database: `heart`
--

-- --------------------------------------------------------

--
-- Table structure for table `fbapp`
--

CREATE TABLE `fbapp` (
  `user_id` int(11) NOT NULL,
  `fbuser_id` varchar(24) NOT NULL,
  `access_token` varchar(256) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for table `fbapp`
--
ALTER TABLE `fbapp`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `fbuser_id` (`fbuser_id`);

--
-- AUTO_INCREMENT for table `fbapp`
--
ALTER TABLE `fbapp` 
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
