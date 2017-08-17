<?php
/*******************************************************************************
ＣＳＶ商品登録処理
*******************************************************************************/

//fgetcsvだと日本語の最初一文字が文字化けを起こす為、この関数を使用する
function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
	$d = preg_quote($d);
	$e = preg_quote($e);
	$_line = "";

	while (($eof != true)and(!feof($handle))) {
		$_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
		$itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
		if ($itemcnt % 2 == 0) $eof = true;
	}

	$_csv_line = preg_replace('/(?:\r\n|[\r\n])?$/', $d, trim($_line));
	$_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
	preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
	$_csv_data = $_csv_matches[1];

	for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
		$_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
		$_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
	}

	return empty($_line) ? false : $_csv_data;
}

if(is_uploaded_file($_FILES['UploadFile']['tmp_name'])){//中カテゴリーとＣＳＶデータがある場合は処理を行う
#=================================================================================
# CSVファイルのアップロード
#=================================================================================

	#=================================================================================
	# データベース登録の下準備
	#=================================================================================
		//最後尾の並び順を取得する（もし、CSV登録データを最初の方に表示したい場合は既存データに最大登録数を並び順に加算してCSV登録をした後に並び順を直した方が楽）
			$sqlvm = "
				SELECT
					MAX(VIEW_ORDER) AS VMAX
				FROM
					D1_OUTPUT
				WHERE
					(DEL_FLG = '0')
				";

			// ＳＱＬを実行
			$fetchVM = dbOpe::fetch($sqlvm,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			$vm_num = $fetchVM[0]['VMAX'] + 1;

		//今現在の登録件数を取得する
			$sqlreg = "
				SELECT
					COUNT(*) AS REG_NUM
				FROM
					D1_OUTPUT
				WHERE
					(DEL_FLG = '0')
				";

			// ＳＱＬを実行
			$fetchRN = dbOpe::fetch($sqlreg,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			$reg_cnt = ($fetchRN[0]['REG_NUM'])?:0;

	//テンポラリファイル名がある場合
	if(is_uploaded_file($_FILES['UploadFile']['tmp_name'])) {

		//CSVファイル名を格納
		$fname=$_FILES['UploadFile']['tmp_name'];

		if(file_exists($fname)){//ファイルのチェック
			$fp=fopen($fname, "r");//ファイルの読み込み
			$cnt=0;//カウント、ＣＳＶでの最初は項目名を表示させているので最初のデータは入力させない

			while(($line = fgetcsv_reg($fp)) !== false){

				if($cnt && $line[0]){//最初は項目欄として入力させない、またタイトルが無い場合は登録しない
					if($reg_cnt  < D1_DBMAX_CNT){//最大登録件数を超えた場合は登録させない。

						//文字処理を行う
						for($j=0;$j < count($line);$j++){
							$line[$j] = mb_convert_encoding($line[$j],"UTF-8","SJIS");//エンコード
							$line[$j] = utilLib::strRep($line[$j],8);
							$line[$j] = utilLib::strRep($line[$j],7);
							$line[$j] = utilLib::strRep($line[$j],1);
							$line[$j] = utilLib::strRep($line[$j],4);
							$line[$j] = utilLib::strRep($line[$j],5);
						}

						//商品IDを作成
						$r_id = $makeID();

						#-----------------------------------------------------
						# 商品情報
						#-----------------------------------------------------
						$sql = "
						INSERT INTO

							D1_OUTPUT
						SET
							RES_ID = '$r_id',
							TITLE = '".$line[0]."',
							CONTENT = '".$line[1]."',

							DISP_DATE = NOW(),
							VIEW_ORDER = '{$vm_num}',
							DISPLAY_FLG = '0',
							DEL_FLG = '0'
						";

						// ＳＱＬを実行
						if(!empty($sql)){
							$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
							if($db_result)die("DB登録失敗しました<hr>{$db_result}");

							$reg_cnt++;//登録されたらカウント
						}
					}else{
						$mess = "<span style=\"color:#FF0000;\">最大登録件数を超えた為、<br>全てのデータが登録できませんでした。</span><br><br>";

					}
				}

				$cnt = 1;//入力できるようにフラグを立てる
			}
		}
	}

	if(!$mess){
		$mess = "<span style=\"color:#FF0000;\">登録が完了いたしました。</span><br><br>";
	}

}else{
	$mess = "<span style=\"color:#FF0000;\">カテゴリーまたはＣＳＶファイルが選択されておりません。</span><br><br>";

}

?>