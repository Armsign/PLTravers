--
-- Table structure for table LOGIN
--
DROP TABLE IF EXISTS LOGINS;
CREATE TABLE LOGINS (
    ID bigint(20) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
    EMAIL varchar(254) NOT NULL,
    IS_ACTIVE int(11) NOT NULL DEFAULT 0,
    PREFERRED_NAME varchar(1024) NOT NULL,
    PASSWORD varchar(1024) NOT NULL,
    SESSION varchar(254) NOT NULL,
    PRIMARY KEY (ID),
    KEY IDX_EMAIL (EMAIL),
    KEY IDX_SESSION (SESSION)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--  What password encoder did I use?
 
INSERT INTO LOGINS (EMAIL, IS_ACTIVE, PREFERRED_NAME, PASSWORD, SESSION) 
VALUES ( 'paul@armsign.com.au', 1, 'Paul Dunn', 'f450ca5fcb9b2333b38dd230ddae0b300', '');

--
-- Table structure for table DEPOSITS
--
DROP TABLE IF EXISTS DEPOSITS;
CREATE TABLE DEPOSITS (
  ID bigint(20) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  PROMPT_ID bigint(20) UNSIGNED ZEROFILL DEFAULT 0,
  TITLE varchar(1024) NOT NULL DEFAULT '',
  STORED_BY varchar(2048) NOT NULL DEFAULT '', --  IS THIS THE EMAIL ... SO. HMM. IF THE DEPOSIT EXISTS ... 
  STORED_AS varchar(2048) NOT NULL DEFAULT '', --  NOM DE PLUM
  STORED_AT varchar(2048) NOT NULL DEFAULT '', --  THIS IS THE FILE LOCATION IF NECESSARY
  STORED_ON datetime NOT NULL DEFAULT NOW(),
  AUDIO_TYPE varchar(1024) NOT NULL DEFAULT '',
  AUDIO_LENGTH bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  IS_PLAYABLE int(11) NOT NULL DEFAULT 0,
  IS_TRANSCRIBED int(11) NOT NULL DEFAULT 0,
  TRANSCRIPTION varchar(8192) DEFAULT '',
  HAS_CONSENT int(11) NOT NULL DEFAULT 0,
  USE_EMAIL int(11) NOT NULL DEFAULT 0,
  REVIEWED_BY bigint(20) NOT NULL DEFAULT 0,
  REVIEWED_ON datetime NOT NULL DEFAULT NOW(),
  PRIMARY KEY (ID),
  KEY IDX_PROMPT_ID (PROMPT_ID),
  KEY IDX_STORED_ON (STORED_ON),
  KEY IDX_STORED_BY (STORED_BY),
  KEY IDX_IS_TRANSCRIBED (IS_TRANSCRIBED),
  KEY IDX_IS_PLAYABLE (IS_PLAYABLE),
  KEY IDX_REVIEWED_BY (REVIEWED_BY)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table DEPOSIT_METRIC
--
DROP TABLE IF EXISTS DEPOSIT_FLAGS;
CREATE TABLE DEPOSIT_FLAGS (
    ID bigint(20) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
    DEPOSIT bigint(20) UNSIGNED ZEROFILL NOT NULL, 
    FLAGGED_BY varchar(2048) NOT NULL,
    FLAGGED_ON datetime NOT NULL,
    REASON varchar(2048) DEFAULT NULL,
    REVIEWED_BY BIGINT NOT NULL DEFAULT '0',
    REVIEWED_ON datetime NOT NULL,
    OUTCOME varchar(2048) DEFAULT NULL,
    IS_INAPPROPRIATE INT(11) NOT NULL DEFAULT '1',
    PRIMARY KEY (ID),
    KEY IDX_DEPOSIT (DEPOSIT),
    KEY IDX_FLAGGED_ON (FLAGGED_ON),
    KEY IDX_IS_INAPPROPRIATE (IS_INAPPROPRIATE) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table DEPOSIT_METRIC
--
DROP TABLE IF EXISTS DEPOSIT_METRICS;
CREATE TABLE DEPOSIT_METRICS (
  ID bigint(20) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  DEPOSIT bigint(20) UNSIGNED ZEROFILL NOT NULL,
  PLAYED_ON datetime NOT NULL,
  PLAYED_LENGTH int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (ID),
  KEY IDX_DEPOSIT (DEPOSIT),
  KEY IDX_PLAYED_ON (PLAYED_ON)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table DEPOSIT_TAGS
--
DROP TABLE IF EXISTS DEPOSIT_TAGS;
CREATE TABLE DEPOSIT_TAGS (
    ID bigint(20) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
    DEPOSIT bigint(20) NOT NULL,
    TAG bigint(20) NOT NULL,
    PRIMARY KEY (ID),
    KEY IDX_DEPOSIT (DEPOSIT)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table TAGS
--
DROP TABLE IF EXISTS TAGS;
CREATE TABLE TAGS (
  ID bigint(20) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  TITLE varchar(1024) NOT NULL,
  DESCRIPTION varchar(2048) NOT NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table TAGS
--

INSERT INTO TAGS (ID, TITLE, DESCRIPTION) VALUES
(1, 'PL Travers', 'This content mentions PL Travers directly');

/*

Once upon a time in a land far, far away, there lived a small family of ocelots.

Of the mother, there was little to say except that she was shy and prone to bouts of furry melancholia. The father, it was said by friends and neighbours, compensated by creating fantastic stories, often told over an ale or two.

The children, three furry little agents of chaos, often confused these inebriated ramblings for reality, and as such were known to be both fearless and foolish.

But, it was whispered, the worst of them was also the youngest. Whilst her older brothers had through trial and error learnt some boundaries, their very existence shielded here from the worst of the consequences that would have taught her a lesson or three.

And so it was that one day, whilst her mother listlessly swept their barrow and their father tied another one on that she crept, silent as a shadow, out of the house and took herself down the river where her brothers often fished.

They were not there on that portentous day or else they may have curtailed that tragedy before it had begun. 

But they were not.

Indeed, the youngest sister had counted on this, for she had come to the surprising belief, after listening to her father's most outlandish tale, that the river was populated by mer-ocelots and was quite determined to meet their Queen.

She knew that she couldn't breathe underwater unless a mer-ocelot was with her and so she set about seducing one closer to the surface so she could catch him and swim back to their castle under in the mysterious depths.

This audacious sister had prepared by creating a skirt out of lily pads, cleverly stitched into a tail so that they appeared as beautiful green scales.


INSERT INTO DEPOSITS ( 
    PROMPT_ID, TITLE, STORED_BY, STORED_AS, 
    STORED_AT, STORED_ON, AUDIO_TYPE, AUDIO_LENGTH, 
    IS_PLAYABLE, IS_TRANSCRIBED, TRANSCRIPTION, HAS_CONSENT, USE_EMAIL 
) VALUES ( 0, 'Anon', 'anon@storybank.com.au', 'Anon', 'N\/A', NOW(), '', 0, 0, 1, 'bhyi', 0, 0);


<blockquote cite="http://oemdynamics.com.au/">
Thank you for anodising the cores. You guys did a great job. The fast turnaround time ensure our customers received their items in a timely manner. Thanks again, Glenn.
</blockquote>

/volume1/@appstore/MariaDB10/usr/local/mariadb10/bin/mysqldump -u root -pWipe0ut13 --opt --all-databases --flush-logs | gzip > mysqldumps.sql.gz

*/