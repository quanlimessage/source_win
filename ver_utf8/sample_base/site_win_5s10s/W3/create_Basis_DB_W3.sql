CREATE TABLE W3_DOWNLOAD(
	RES_ID VARCHAR(25) NOT NULL,
	TITLE TEXT,
	CONTENT TEXT,
	TYPE TINYTEXT,
	SIZE INT UNSIGNED,

	EXTENTION TEXT,
	PDF_FLG CHAR(1) DEFAULT 0,
	VIEW_ORDER INT UNSIGNED,
	DISPLAY_FLG CHAR(1) DEFAULT 1,
	DISP_DATE DATETIME,
	UPD_DATE DATETIME,
	DEL_FLG CHAR(1) DEFAULT 0,
	PRIMARY KEY(RES_ID)
);
