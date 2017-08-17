<?php
/*******************************************************************************
設定情報用DBファイル

Logic：ＤＢより情報を取得

*******************************************************************************/

  /*******************************************************************************
	DBより管理情報データ取得
  *******************************************************************************/

	$sql_config="
		SELECT
			NAME,
			EMAIL,
			EMAIL2,
			CONTENT,
			BO_TITLE,
			COMPANY_INFO,
			SHOPPING_TITLE,
			BANK_INFO,
			BO_ID,
			BO_PW
		FROM
			".CONFIG_MST."
		WHERE
			(CONFIG_ID = '1')
	";

	$fetchConfig = $PDO -> fetch($sql_config);

	// 認証時に(Basic2を使用)$fetch_ipas[$i]['user']['pass']を使用する
	$sql_ipas = "
		SELECT
			BO_ID AS user,
			BO_PW AS password
		FROM
			".CONFIG_MST."
		";

	$fetch_ipas = $PDO -> fetch($sql_ipas);

	// 管理者情報が未登録なら仮管理者情報レコードを作製
	if(empty($fetchConfig)):

		// 仮管理者情報をDB登録
		// CONFIG_ID : 1 はクライアント変更用ID:PASS
		$ins_sql ="
		INSERT INTO ".CONFIG_MST."(
			CONFIG_ID,
			NAME,
			BO_ID,
			BO_PW
		)
		VALUES(
			'1',
			'クライアント会社名',
			'".utilLib::strRep("zeeksdg",5)."',
			'".utilLib::strRep("pass",5)."'
		)";
		$PDO -> regist($ins_sql);

		// CONFIG_ID : 2 は共通ZEEK用ID:PASS
		$ins_sql = "
			INSERT INTO ".CONFIG_MST." SET
				CONFIG_ID = '2',
				BO_ID = '".utilLib::strRep("zeeksdg",5)."',
				BO_PW = '".utilLib::strRep("pass",5)."'
		";

		$PDO -> regist($ins_sql);
	endif;

// 商品最大登録数

		// 現商品登録数取得
		$pnum_sql="
			SELECT
				PRODUCT_ID
			FROM
				".PRODUCT_LST."
			WHERE
				(DEL_FLG = '0')
		";

		$fetchPro = $PDO -> fetch($pnum_sql);

?>
