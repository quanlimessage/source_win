
CREATE TABLE APP_INIT_DATA(
	RES_ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
	EMAIL1 TINYTEXT,
	EMAIL2 TINYTEXT,
	CONTENT TEXT,
	UPD_DATE TIMESTAMP,
	DEL_FLG CHAR(1) DEFAULT 0,
	PRIMARY KEY(RES_ID)
);

/* TEST INSERT */
INSERT INTO APP_INIT_DATA(EMAIL1,EMAIL2,CONTENT)VALUES('info@all-internet.jp','info@all-internet.jp','お問い合わせありがとうございます。
○○株式会社です。

後日メールまたはお電話にて担当よりご連絡させていただきます。
また、ご返信に時間がかかる場合がございますのでご了承ください。');
