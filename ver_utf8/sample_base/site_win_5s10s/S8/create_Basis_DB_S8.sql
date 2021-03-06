-- カテゴリ --
CREATE TABLE S8_PARENT_CATEGORY_MST(
	PARENT_CATEGORY_CODE INT UNSIGNED NOT NULL,
	CATEGORY_CODE INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	CATEGORY_NAME TEXT,
	CATEGORY_DETAILS TEXT,
	VIEW_ORDER INT UNSIGNED,
	INS_DATE DATETIME,
	UPD_DATE DATETIME,
	DISPLAY_FLG CHAR(1) DEFAULT 1,
	DEL_FLG CHAR(1) DEFAULT 0
);

CREATE TABLE S8_CATEGORY_MST(
	PARENT_CATEGORY_CODE INT UNSIGNED NOT NULL,
	CATEGORY_CODE INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	CATEGORY_NAME TEXT,
	CATEGORY_DETAILS TEXT,
	VIEW_ORDER INT UNSIGNED,
	INS_DATE DATETIME,
	UPD_DATE DATETIME,
	DISPLAY_FLG CHAR(1) DEFAULT 1,
	DEL_FLG CHAR(1) DEFAULT 0
);

CREATE TABLE S8_PRODUCT_LST(
	RES_ID VARCHAR(25) NOT NULL,
	CATEGORY_CODE INT UNSIGNED,
	TITLE TEXT,
	CONTENT TEXT,
	DETAIL_CONTENT TEXT,
	TITLE_TAG TEXT,
	YOUTUBE TEXT,
	IMG_FLG CHAR(1) DEFAULT 1,
	DISP_DATE DATETIME,
	VIEW_ORDER INT UNSIGNED,
	DISPLAY_FLG CHAR(1) DEFAULT 1,
	UPD_DATE TIMESTAMP,
	DEL_FLG CHAR(1) DEFAULT 0,
	PRIMARY KEY(RES_ID)
);
