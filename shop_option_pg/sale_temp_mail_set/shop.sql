
CREATE TABLE IF NOT EXISTS STM_PRODUCT_LST (
	NUMBER_ID int(10) unsigned NOT NULL auto_increment,
	RES_ID varchar(25) NOT NULL default '',
	TITLE text,
	SUBJECT text,
	CONTENT text,
	DISP_DATE datetime default NULL,
	VIEW_ORDER int(10) unsigned default NULL,
	DISPLAY_FLG char(1) default '1',
	UPD_DATE timestamp NOT NULL,
	DEL_FLG char(1) default '0',
	PRIMARY KEY  (NUMBER_ID)
);

CREATE TABLE IF NOT EXISTS MAIL_HIST_LST (
	RES_ID varchar(25) NOT NULL default '',
	ORDER_ID varchar(25) NOT NULL default '',
	CUSTOMER_ID varchar(25) NOT NULL default '',
	EMAIL text,
	SUBJECT text,
	CONTENT text,
	INS_DATE datetime default NULL,
	UPD_DATE datetime default NULL,
	DEL_FLG char(1) default '0',
	PRIMARY KEY  (RES_ID)
);

-- カテゴリ --
CREATE TABLE CATEGORY_MST(
	CATEGORY_CODE INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	CATEGORY_NAME TINYTEXT,
	CATEGORY_DETAILS TINYTEXT,
	VIEW_ORDER INT UNSIGNED,
	INS_DATE DATETIME,
	UPD_DATE DATETIME,
	DEL_FLG CHAR(1) DEFAULT 0
);
INSERT INTO CATEGORY_MST (CATEGORY_CODE,CATEGORY_NAME,CATEGORY_DETAILS,VIEW_ORDER,INS_DATE,UPD_DATE,DEL_FLG) VALUES(1,'','',1,'','','0');

-- 顧客情報情報テーブル作成SQL --
CREATE TABLE CUSTOMER_LST(
	CUSTOMER_ID VARCHAR(25) NOT NULL PRIMARY KEY,
	LAST_NAME TINYTEXT,
	FIRST_NAME TINYTEXT,
	LAST_KANA TINYTEXT,
	FIRST_KANA TINYTEXT,
	ALPWD TINYTEXT,
	ZIP_CD1 VARCHAR(3),
	ZIP_CD2 VARCHAR(4),
	STATE VARCHAR(2),
	ADDRESS1 TINYTEXT,
	ADDRESS2 TINYTEXT,
	EMAIL TINYTEXT,
	TEL1 VARCHAR(5),
	TEL2 VARCHAR(5),
	TEL3 VARCHAR(5),
	EXISTING_CUSTOMER_FLG CHAR(1) DEFAULT 0,
	INS_DATE DATETIME,
	UPD_DATE DATETIME,
	DEL_FLG CHAR(1) DEFAULT 0
);

-- 注文情報リスト --
CREATE TABLE PURCHASE_LST(
	ORDER_ID VARCHAR(25) NOT NULL PRIMARY KEY,
	CUSTOMER_ID VARCHAR(25) NOT NULL,
	TOTAL_PRICE INT UNSIGNED,
	SUM_PRICE INT UNSIGNED,
	SHIPPING_AMOUNT INT UNSIGNED,
	DAIBIKI_AMOUNT INT UNSIGNED,
	CONV_AMOUNT INT UNSIGNED,
	DELI_LAST_NAME TINYTEXT,
	DELI_FIRST_NAME TINYTEXT,
	DELI_ZIP_CD1 VARCHAR(3),
	DELI_ZIP_CD2 VARCHAR(4),
	DELI_STATE VARCHAR(2),
	DELI_ADDRESS1 TINYTEXT,
	DELI_ADDRESS2 TINYTEXT,
	DELI_TEL1 VARCHAR(5),
	DELI_TEL2 VARCHAR(5),
	DELI_TEL3 VARCHAR(5),
	PAYMENT_TYPE CHAR(1),
	CONV_NO VARCHAR(16),
	ORDER_DATE DATETIME,
	PAYMENT_FLG CHAR(1),
	PAYMENT_DATE DATETIME,
	SHIPPED_FLG CHAR(1),
	SHIPPED_DAY DATETIME,
	REMARKS TEXT,
	CONFIG_MEMO TEXT,
	CREDIT_CLOSE_FLG CHAR(1) DEFAULT 0,
	UPD_DATE DATETIME,
	DEL_FLG CHAR(1) DEFAULT 0
);

-- 注文商品一覧 --
CREATE TABLE PURCHASE_ITEM_DATA(
	PID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ORDER_ID VARCHAR(25) NOT NULL,
	PRODUCT_ID VARCHAR(25) NOT NULL,
	PART_NO TINYTEXT,
	PRODUCT_NAME TINYTEXT,
	SELLING_PRICE INT UNSIGNED,
	QUANTITY INT UNSIGNED,
	INS_DATE DATETIME,
	DEL_FLG CHAR(1) DEFAULT 0
);

-- 商品情報 --
CREATE TABLE PRODUCT_LST(
	PRODUCT_ID VARCHAR(25) NOT NULL PRIMARY KEY,
	CATEGORY_CODE INT UNSIGNED NOT NULL,
	PART_NO TINYTEXT,
	PRODUCT_NAME TEXT,
	CAPACITY TEXT,
	PRODUCT_DETAILS TEXT,
	ITEM_DETAILS TEXT,
	TITLE_TAG TEXT,
	STOCK_QUANTITY INT UNSIGNED DEFAULT 0,
	SELLING_PRICE INT UNSIGNED,
	DISPLAY_FLG CHAR(1) DEFAULT 1,
	CART_CLOSE_FLG CHAR(1) DEFAULT 0,
	NEW_ITEM_FLG CHAR(1) DEFAULT 0,
	RECOMMEND_FLG CHAR(1) DEFAULT 0,
	RECOMMEND_VO INT UNSIGNED,
	VIEW_ORDER INT UNSIGNED,
	SALE_START_DATE DATETIME,
	SALE_END_DATE DATETIME,
	INS_DATE DATETIME,
	UPD_DATE DATETIME,
	DEL_FLG CHAR(1) DEFAULT 0
);

-- 管理者情報テーブル作成SQL --
CREATE TABLE CONFIG_MST(
	CONFIG_ID INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	NAME TINYTEXT,
	EMAIL TINYTEXT,
	EMAIL2 TINYTEXT,
	CONTENT TEXT,
	COMPANY_INFO TEXT,
	BANK_INFO TEXT,
	SHOPPING_TITLE TINYTEXT,
	BO_TITLE TINYTEXT,
	BO_ID TINYTEXT,
	BO_PW TINYTEXT,
	PERMIT_IP_LST TEXT,
	UPD_DATE DATETIME,
	DEL_FLG CHAR(1) DEFAULT 0
);

INSERT INTO CONFIG_MST (CONFIG_ID,NAME,EMAIL,EMAIL2,COMPANY_INFO,BANK_INFO,SHOPPING_TITLE,BO_TITLE,BO_ID,BO_PW,UPD_DATE,DEL_FLG,PERMIT_IP_LST) VALUES(1,'','','','','','','','','pass','','0','free');
INSERT INTO CONFIG_MST (CONFIG_ID,NAME,EMAIL,EMAIL2,COMPANY_INFO,BANK_INFO,SHOPPING_TITLE,BO_TITLE,BO_ID,BO_PW,UPD_DATE,DEL_FLG,PERMIT_IP_LST) VALUES(2,'','','','','','','','zeeksdg','pass','','0','210.138.173.44,114.173.123.220,180.17.25.205,118.151.96.205,180.10.246.236,180.26.4.229,61.119.173.50,110.2.115.221');

フォーバル案件の場合こちらのＩＤ，ＰＷを追加
INSERT INTO CONFIG_MST (CONFIG_ID,NAME,EMAIL,EMAIL2,COMPANY_INFO,BANK_INFO,SHOPPING_TITLE,BO_TITLE,BO_ID,BO_PW,UPD_DATE,DEL_FLG,PERMIT_IP_LST) VALUES(3,'','','','','','','','fvl','pass','','0','free');
