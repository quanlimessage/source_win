<?php
/*******************************************************************************
���������DB�ե�����

Logic���ģ¤���������

2006.03.13 fujiyama
*******************************************************************************/

  /*******************************************************************************
	DB����������ǡ�������
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

	$fetchConfig = dbOpe::fetch($sql_config, DB_USER, DB_PASS, DB_NAME, DB_SERVER);

	// ǧ�ڻ���(Basic2�����)$fetch_ipas[$i]['user']['pass']����Ѥ���
	$sql_ipas = "
		SELECT
			BO_ID AS user,
			BO_PW AS password
		FROM
			".CONFIG_MST."
		";

	$fetch_ipas = dbOpe::fetch($sql_ipas, DB_USER, DB_PASS, DB_NAME, DB_SERVER);

	// �����Ծ���̤��Ͽ�ʤ鲾�����Ծ���쥳���ɤ����
	if(empty($fetchConfig)):

		// �������Ծ����DB��Ͽ
		// CONFIG_ID : 1 �ϥ��饤������ѹ���ID:PASS
		$ins_sql[] ="
		INSERT INTO ".CONFIG_MST."(
			CONFIG_ID,
			NAME,
			BO_ID,
			BO_PW
		)
		VALUES(
			'1',
			'���饤����Ȳ��̾',
			'".utilLib::strRep("zeeksdg",5)."',
			'".utilLib::strRep("pass",5)."'
		)";

		// CONFIG_ID : 2 �϶���ZEEK��ID:PASS
		$ins_sql[] = "
			INSERT INTO ".CONFIG_MST." SET
				CONFIG_ID = '2',
				BO_ID = '".utilLib::strRep("zeeksdg",5)."',
				BO_PW = '".utilLib::strRep("pass",5)."'
		";

		if(!empty($ins_sql)){
			$db_result = dbOpe::regist($ins_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			if($db_result)die("�ǥ��ե���Ⱦ��󥻥åȤ˼��Ԥ��ޤ�����<hr>{$db_result}");

		}
	endif;

// ���ʺ�����Ͽ��

		// ��������Ͽ������
		$pnum_sql="
			SELECT
				PRODUCT_ID
			FROM
				".PRODUCT_LST."
			WHERE
				(DEL_FLG = '0')
		";

		$fetchPro = dbOpe::fetch($pnum_sql, DB_USER, DB_PASS, DB_NAME, DB_SERVER);

?>