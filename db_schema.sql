SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `twssna_comments`
-- ----------------------------
DROP TABLE IF EXISTS `twssna_comments`;
CREATE TABLE `twssna_comments` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`userID` int(11) DEFAULT NULL,
	`comment` text DEFAULT NULL,
	`dateAdded` datetime DEFAULT NULL,
	`guid` varchar(100) DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `index_userID` (`userID`) comment '',
	INDEX `index_guid` (`guid`) comment '',
	FULLTEXT `index_comments` (`comment`) comment ''
) ENGINE=`MyISAM` AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ROW_FORMAT=DYNAMIC COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0;

-- ----------------------------
--  Table structure for `twssna_users`
-- ----------------------------
DROP TABLE IF EXISTS `twssna_users`;
CREATE TABLE `twssna_users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`username` varchar(50) DEFAULT NULL,
	`password` varchar(500) DEFAULT NULL,
	`salt` varchar(500) DEFAULT NULL,
	`dateCreated` datetime DEFAULT NULL,
	`numComments` int(11) DEFAULT NULL,
	`guid` varchar(100) DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `index_guid` (`guid`) comment ''
) ENGINE=`InnoDB` AUTO_INCREMENT=1 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ROW_FORMAT=DYNAMIC COMMENT='' CHECKSUM=0 DELAY_KEY_WRITE=0;

-- ----------------------------
--  Procedure structure for `spGetLatestTWSSNAComments`
-- ----------------------------
DROP PROCEDURE IF EXISTS `spGetLatestTWSSNAComments`;
delimiter ;;
CREATE PROCEDURE `spGetLatestTWSSNAComments`()
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN

SELECT c.id, c.userID, c.`comment`, c.dateAdded, c.guid, u.username
FROM twssna_comments c
INNER JOIN twssna_users u ON u.id = c.userID
ORDER BY c.dateAdded DESC
LIMIT 100;

END
 ;;
delimiter ;

-- ----------------------------
--  Procedure structure for `spTWSSACreateAccount`
-- ----------------------------
DROP PROCEDURE IF EXISTS `spTWSSACreateAccount`;
delimiter ;;
CREATE PROCEDURE `spTWSSACreateAccount`(iUsername varchar(50), iPassword varchar(500))
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN

IF NOT EXISTS(SELECT id FROM twssna_users WHERE username = iUsername)THEN
	SET @guid = SHA1(UUID());
	SET @salt = SHA2(UUID(), 512);
	SET @password = SHA2(CONCAT(iPassword, @salt), 512);
	INSERT INTO twssna_users (username, `password`, salt, dateCreated, numComments, guid)
	VALUES(iUsername, @password, @salt, NOW(), 0, @guid);

	SELECT id, username, dateCreated, numComments, guid, 0 as error, 'Account Created' AS message FROM twssna_users WHERE guid = @guid;
ELSE
	SELECT 1 AS error, 'Username already in use' AS message;
END IF;

END
 ;;
delimiter ;

-- ----------------------------
--  Procedure structure for `spTWSSADeleteComment`
-- ----------------------------
DROP PROCEDURE IF EXISTS `spTWSSADeleteComment`;
delimiter ;;
CREATE PROCEDURE `spTWSSADeleteComment`(iCommentGUID varchar(100), iUserGUID varchar(100))
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN

SET @userID = (SELECT id FROM twssna_users WHERE guid = iUserGUID);

DELETE FROM twssna_comments WHERE guid = iCommentGUID AND userID = @userID;

SELECT 0 AS error, 'Comment Removed' AS message;

END
 ;;
delimiter ;

-- ----------------------------
--  Procedure structure for `spTWSSAInsertComment`
-- ----------------------------
DROP PROCEDURE IF EXISTS `spTWSSAInsertComment`;
delimiter ;;
CREATE `spTWSSAInsertComment`(iUserID int, iComment text)
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN

SET @guid = SHA1(UUID());

INSERT INTO twssna_comments (userID, `comment`, dateAdded, guid)
VALUES(iUserID, iComment, NOW(), @guid);

UPDATE twssna_users SET numComments = numComments+1 WHERE id = iUserID;

SELECT 0 AS error, 'Comment Added' AS message;

END
 ;;
delimiter ;

-- ----------------------------
--  Procedure structure for `spTWSSALogin`
-- ----------------------------
DROP PROCEDURE IF EXISTS `spTWSSALogin`;
delimiter ;;
CREATE PROCEDURE `spTWSSALogin`(iUsername varchar(50), iPassword varchar(500))
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN

SET @salt = (SELECT salt FROM twssna_users WHERE username = iUsername);
SELECT id, username, dateCreated, numComments, guid
FROM twssna_users WHERE username = iUsername AND `password` = SHA2(CONCAT(iPassword, @salt), 512);

END
 ;;
delimiter ;

-- ----------------------------
--  Procedure structure for `spTWSSASearchComment`
-- ----------------------------
DROP PROCEDURE IF EXISTS `spTWSSASearchComment`;
delimiter ;;
CREATE PROCEDURE `spTWSSASearchComment`(iSearchTerm text, iCommentGUID varchar(100))
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN

SELECT DISTINCT c.id, c.userID, c.`comment`, c.dateAdded, c.guid, u.username
FROM (
SELECT c.id, c.userID, c.`comment`, c.dateAdded, c.guid, MATCH(c.`comment`) AGAINST (iSearchTerm IN NATURAL LANGUAGE MODE) AS score
FROM twssna_comments c
WHERE c.guid <> iCommentGUID AND MATCH(c.`comment`) AGAINST (iSearchTerm IN NATURAL LANGUAGE MODE) > 0
UNION
SELECT c.id, c.userID, c.`comment`, c.dateAdded, c.guid, 0 AS score
FROM twssna_comments c
WHERE c.guid <> iCommentGUID AND c.`comment` LIKE CONCAT('%', iSearchTerm, '%')
ORDER BY score DESC
) c
INNER JOIN twssna_users u ON u.id = c.userID
;

END
 ;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
