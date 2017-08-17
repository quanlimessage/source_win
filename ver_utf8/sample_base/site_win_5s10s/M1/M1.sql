
CREATE TABLE M1_INFO (
  RES_ID int(10) unsigned NOT NULL auto_increment,
  ID tinytext,
  PW tinytext,
  DEL_FLG char(1) default '0',
  PRIMARY KEY  (RES_ID)
) TYPE=MyISAM;

INSERT INTO M1_INFO VALUES('1','test','pass','0');
