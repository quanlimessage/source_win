CREATE TABLE BBS_LOG_MST_DATA (
  MASTER_ID varchar(128) NOT NULL default '',
  NAME tinytext,
  EMAIL varchar(128) NOT NULL default '',
  URL varchar(128) NOT NULL default '',
  IP varchar(128) NOT NULL default '',
  TITLE tinytext,
  COMMENT text,
  REG_DATE datetime NOT NULL default '0000-00-00 00:00:00',
  DISPLAY_FLG char(1) default '1',
  DEL_FLG char(1) NOT NULL default '0',
  PRIMARY KEY  (MASTER_ID)
);

CREATE TABLE BBS_LOG_SUB_DATA (
  SUB_ID varchar(128) NOT NULL default '',
  MASTER_ID varchar(128) NOT NULL default '',
  NAME tinytext,
  EMAIL varchar(128) NOT NULL default '',
  URL varchar(128) NOT NULL default '',
  IP varchar(128) NOT NULL default '',
  COMMENT text,
  REG_DATE datetime NOT NULL default '0000-00-00 00:00:00',
  DISPLAY_FLG char(1) default '1',
  DEL_FLG char(1) NOT NULL default '0',
  PRIMARY KEY  (SUB_ID)
);

/* NG WORDS */
CREATE TABLE NG_WORDS(
	KEY_ID CHAR(4) NOT NULL,
	WORDS TEXT,
	UPD_DATE DATETIME
);
INSERT INTO NG_WORDS(KEY_ID,WORDS,UPD_DATE)VALUES('9999','','2005-04-22 16:59:59');
