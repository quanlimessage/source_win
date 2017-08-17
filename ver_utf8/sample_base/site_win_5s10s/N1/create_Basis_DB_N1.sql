
CREATE TABLE N1_WHATSNEW(
	RES_ID VARCHAR(25) NOT NULL,
	TITLE TEXT,
	CONTENT TEXT,
	LINK TEXT,
	DISP_DATE DATETIME,
	DISPLAY_FLG CHAR(1) DEFAULT 1,
	LINK_FLG CHAR(1) DEFAULT 1,
	IMG_FLG CHAR(1) DEFAULT 1,
	UPD_DATE TIMESTAMP,
	DEL_FLG CHAR(1) DEFAULT 0,
	PRIMARY KEY(RES_ID)
);