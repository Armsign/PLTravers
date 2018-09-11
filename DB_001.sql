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

Of the mother, there was little to say except that she was shy and prone to bouts of furry melancholia. 
The father, it was said by friends and neighbours, compensated by creating fantastic stories, often told over an ale or two.
The children, three furry little agents of chaos, often confused these inebriated ramblings for reality, and as such were known to be both fearless and foolish.
But, it was whispered, the worst of them was also the youngest. 
Whilst her older brothers had through trial and error learnt some boundaries, their very existence shielded her from the worst of the consequences that would have taught her a lesson or three.
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


http://192.168.1.2/Vault/API.php?
action=deposit&
method=update&
token=c4fbb242c5ba845c3271e660fefe45d8072814c412d044e52cb530a6fe7e65a1&
id=00000000000000000006&
promptId=00000000000000000000&
nomDePlume=Anon&isPlayable=0&title=Title&email=anon@storybank.com.au&hasConsent=0&useEmail=0&story=Once%20upon%20a%20time%20in%20a%20land%20far,%20far%20away,%20there%20lived%20a%20small%20family%20of%20ocelots.Of%20the%20mother,%20there%20was%20little%20to%20say%20except%20that%20she%20was%20shy%20and%20prone%20to%20bouts%20of%20furry%20melancholia.%20The%20father,%20it%20was%20said%20by%20friends%20and%20neighbours,%20compensated%20by%20creating%20fantastic%20stories,%20often%20told%20over%20an%20ale%20or%20two.The%20children,%20three%20furry%20little%20agents%20of%20chaos,%20often%20confused%20these%20inebriated%20ramblings%20for%20reality,%20and%20as%20such%20were%20known%20to%20be%20both%20fearless%20and%20foolish.But,%20it%20was%20whispered,%20the%20worst%20of%20them%20was%20also%20the%20youngest.%20Whilst%20her%20older%20brothers%20had%20through%20trial%20and%20error%20learnt%20some%20boundaries,%20their%20very%20existence%20shielded%20here%20from%20the%20worst%20of%20the%20consequences%20that%20would%20have%20taught%20her%20a%20lesson%20or%20three.And%20so%20it%20was%20that%20one%20day,%20whilst%20her%20mother%20listlessly%20swept%20their%20barrow%20and%20their%20father%20tied%20another%20one%20on%20that%20she%20crept,%20silent%20as%20a%20shadow,%20out%20of%20the%20house%20and%20took%20herself%20down%20the%20river%20where%20her%20brothers%20often%20fished.They%20were%20not%20there%20on%20that%20portentous%20day%20or%20else%20they%20may%20have%20curtailed%20that%20tragedy%20before%20it%20had%20begun.%20But%20they%20were%20not.Indeed,%20the%20youngest%20sister%20had%20counted%20on%20this,%20for%20she%20had%20come%20to%20the%20surprising%20belief,%20after%20listening%20to%20her%20father%27s%20most%20outlandish%20tale,%20that%20the%20river%20was%20populated%20by%20mer-ocelots%20and%20was%20quite%20determined%20to%20meet%20their%20Queen.She%20knew%20that%20she%20couldn%27t%20breathe%20underwater%20unless%20a%20mer-ocelot%20was%20with%20her%20and%20so%20she%20set%20about%20seducing%20one%20closer%20to%20the%20surface%20so%20she%20could%20catch%20him%20and%20swim%20back%20to%20their%20castle%20under%20in%20the%20mysterious%20depths.This%20audacious%20sister%20had%20prepared%20by%20creating%20a%20skirt%20out%20of%20lily%20pads,%20cleverly%20stitched%20into%20a%20tail%20so%20that%20they%20appeared%20as%20beautiful%20green%20scales%20...

*/