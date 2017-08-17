CREATE TABLE APP_INIT_DATA(
  RES_ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
  EMAIL1 TINYTEXT,
  EMAIL2 TINYTEXT,
  UPD_DATE TIMESTAMP,
  DEL_FLG CHAR(1) DEFAULT 0,
  PRIMARY KEY(RES_ID)
);

/* TEST INSERT */
INSERT INTO APP_INIT_DATA(EMAIL1,EMAIL2)VALUES('','');
//会員情報
CREATE TABLE H1A_MEMBER_NEW (
  MEMBER_ID varchar(25) NOT NULL,
  UPD_DATE timestamp(14) NOT NULL,
  INS_DATE timestamp(14) NOT NULL,
  DEL_FLG char(1) default '0',
  SENDMAIL char(1) default '0',
  SENDMAIL_FLG char(1) default '0',
  OLD_SENDMAIL_FLG char(1) default '0',
  NAME tinytext,
  KANA tinytext,
  ZIP_CD1 tinytext,
  ZIP_CD2 tinytext,
  STATE tinytext,
  ADDRESS1 tinytext,
  ADDRESS2 tinytext,
  TEL tinytext,
  FAX tinytext,
  EMAIL tinytext,
  GENERATION_CD tinytext,
  JOB_CD tinytext,
  PRIMARY KEY  (MEMBER_ID)
);

//配信履歴
CREATE TABLE MAIL_HISTORY(
  RES_ID  varchar(25) NOT NULL,
  TITLE text,
  CONTENT text,
  MEMBER_ID text,
  SEND_NUMBER int,
  INS_DATE timestamp(14) NOT NULL,
  DEL_FLG char(1) default '0',
  PRIMARY KEY(RES_ID)
);
