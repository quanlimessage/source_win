<?php
/*******************************************************************************

Logic : 入力データのチェック	※$_POST['status']の内容により処理を分岐

*******************************************************************************/

// 不正アクセスチェック（直接このファイルにアクセスした場合）
if ( !$injustice_access_chk ){
	header("HTTP/1.0 404 Not Found");	exit();
}

#--------------------------------------------------------------------------------
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#--------------------------------------------------------------------------------

// 	タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

##################################################################################
#
# $_POST['status']の内容により処理を分岐
#
##################################################################################
switch($_POST['status']):
case "confirm":

#===============================================================================
# 個人情報入力ページより：入力された個人情報の入力チェックを行う
# 	※入力エラーがある場合はエラーメッセージを設定する
#===============================================================================

	// 支払方法
	$error_message .= utilLib::strCheck($payment_method, 0, "支払方法を選択してください。<br>\n");
	if ( $payment_method && !($payment_method == '1' || $payment_method == '2' || $payment_method == '3' || $payment_method == '4' || $payment_method == '5' || $payment_method == '6') )
		$error_message .= "支払方法の選択方法に誤りがあります。<br>\n";

		// コンビに決済時(スマートピットの場合)
		if($payment_method == '4'):
			if(!eregi("^[0-9]{2,2}[0-9]$",$card_no1) || !eregi("^[0-9]{2,2}[0-9]$",$card_no2) || !eregi("^[0-9]{2,2}[0-9]$",$card_no3) || !eregi("^[0-9]{3,3}[0-9]$",$card_no4)){
				$error_message.="お手持ちのカード番号を正しく入力してください。<br>\n";
			}
		endif;

	// お名前（姓漢字）
	$error_message .= utilLib::strCheck($last_name, 0, "お名前（姓漢字）を入力してください。<br>\n");
	if ( mb_strlen($last_name) > 100 )$error_message .= "お名前（姓漢字）の文字数をお減らしください。<br>\n";

	// お名前（名漢字）
	$error_message .= utilLib::strCheck($first_name, 0, "お名前（名漢字）を入力してください。<br>\n");
	if ( mb_strlen($first_name) > 100 )$error_message .= "お名前（名漢字）の文字数をお減らしください。<br>\n";

	// お名前（姓ひらがな）
	$error_message .= utilLib::strCheck($last_kana, 0, "お名前（姓かな）を入力してください。<br>\n");
	if ( mb_strlen($last_kana) > 100 )$error_message .= "お名前（姓かな）の文字数をお減らしください。<br>\n";

	// お名前（名ひらがな）
	$error_message .= utilLib::strCheck($first_kana, 0, "お名前（名かな）を入力してください。<br>\n");
	if ( mb_strlen($first_kana) > 100 )$error_message .= "お名前（名かな）の文字数をお減らしください。<br>\n";

	// 郵便番号
	$zip1 = mb_convert_kana($zip1, "n");
	$error_message .= utilLib::strCheck($zip1, 0, "郵便番号（上３桁）を入力してください。<br>\n");
	if ( $zip1 )	$error_message .= utilLib::strCheck($zip1, 2, "郵便番号（上３桁）は数字で入力してください。<br>\n");
	$zip2 = mb_convert_kana($zip2, "n");
	$error_message .= utilLib::strCheck($zip2, 0, "郵便番号（下４桁）を入力してください。<br>\n");
	if ( $zip2 )	$error_message .= utilLib::strCheck($zip2, 2, "郵便番号（下４桁）は数字で入力してください。<br>\n");

	// 都道府県
	$error_message .= utilLib::strCheck($state, 0, "都道府県を選択してください。<br>\n");
	if ( $state )	$error_message .= utilLib::strCheck($state, 2, "都道府県の選択方法に誤りがあります。<br>\n");

	// ご住所（市町村以下）
	$error_message .= utilLib::strCheck($address1, 0, "ご住所(市町村以下)を入力してください。<br>\n");
	if ( mb_strlen($address1) > 250 )$error_message .= "ご住所(市町村以下)の文字数をお減らしください。<br>\n";

	// お電話番号
	$tel1 = mb_convert_kana($tel1, "n");
	$error_message .= utilLib::strCheck($tel1, 0, "お電話番号を選択してください。<br>\n");
	if ( $tel1 )	$error_message .= utilLib::strCheck($tel1, 2, "お電話番号は数字で入力してください。<br>\n");

	$tel2 = mb_convert_kana($tel2, "n");
	$error_message .= utilLib::strCheck($tel2, 0, "お電話番号を選択してください。<br>\n");
	if ( $tel2 )	$error_message .= utilLib::strCheck($tel2, 2, "お電話番号は数字で入力してください。<br>\n");

	$tel3 = mb_convert_kana($tel3, "n");
	$error_message .= utilLib::strCheck($tel3, 0, "お電話番号を選択してください。<br>\n");
	if ( $tel3 )	$error_message .= utilLib::strCheck($tel3, 2, "お電話番号は数字で入力してください。<br>\n");

	// メールアドレス
	$error_message .= utilLib::strCheck($email, 0, "Eメールアドレスを入力してください。<br>\n");
	// 半角英数字に統一
	$email = mb_convert_kana($email, "a");

	// 未入力以外のエラーチェック
	$e_chk = "";
	$e_chk .= utilLib::strCheck($email, 1, true);
	$e_chk .= utilLib::strCheck($email, 4, true);
	$e_chk .= utilLib::strCheck($email, 5, true);
	$e_chk .= utilLib::strCheck($email, 6, true);
	if ( $e_chk )	$error_message .= "Eメールアドレスに誤りがあります。<br><br>\n";

	//メールアドレスに全角文字と半角カタカナの入力は拒否させる
	mb_regex_encoding('UTF-8');//エンコードの宣言をしておく
	if((mb_strlen($email, 'UTF-8') != strlen($email)) || mb_ereg("[ｱ-ﾝ]", $email)){
		$error_message .= "Eメールアドレスに不正な文字が含まれております。<br><br>\n";
	}

	// 既存のが登録されてないか確認してＯＫならメールアドレスを
	$emailchksql = "SELECT EMAIL FROM ".CUSTOMER_LST." WHERE ( EMAIL = '".addslashes($email)."' ) AND ( DEL_FLG = '0' ) AND  (EXISTING_CUSTOMER_FLG = '1')";
	$chk_email_result = dbOpe::fetch($emailchksql, DB_USER, DB_PASS, DB_NAME, DB_SERVER);
	if ( !empty($chk_email_result) && !$_SESSION['cust']['auth'] ){
		$error_message .=<<< EDIT
		 入力されたＥメールアドレスは既に登録されています。<br>前画面に戻り、Ｅメールアドレスとパスワードを入力してください。<br>
						<form action="./" method="post" style="margin:1em 0em;">
							<input name="submit" type="submit" value="前画面に戻り修正を行う">
							<input type="hidden" name="status" value="edit">
							<input type="hidden" name="edit_type" value="to_cartList">
						</form>
EDIT;
	}

	//パスワード 初めての方のみパスワードのチェックを行う
	if($_SESSION['cust']['m'] != 1){
		// 半角英数字に統一
			$password = mb_convert_kana($password, "a");
			$password2 = mb_convert_kana($password2, "a");

		$error_message .= utilLib::strCheck($password, 0, "パスワードを入力してください。<br>\n");

		if(strlen($password)>PW_LIMIT_STR){//パスワードが規定数以上入力された場合
			$error_message .= "パスワードが長過ぎます。<br>\n";
			
		}elseif ( $password != $password2 ){
			$error_message .= "パスワードが一致しません<br>\n";
		}elseif ( preg_match("/[\\\]/", $password) || preg_match("/[\\\]/", $password2) ){
			$error_message .= '大変申し訳ございませんが“\”という文字をパスワードに使用するのはご遠慮いただけますよう何卒お願い申し上げます'."<br>\n";
		}
	}

	// 備考欄
	if ( mb_strlen($remarks) > 1000 )	$error_message .= "備考欄の文字数をお減らしください。<br>\n";

	#-----------------------------------------------------------------------------
	# エラーチェック結果にかかわらず、取得データをセッションに格納
	#-----------------------------------------------------------------------------
	$_SESSION['cust']['PAYMENT_METHOD']	 	= $payment_method;
	// コンビに決済(スマートピット利用時)
	if($_SESSION['cust']['PAYMENT_METHOD'] == 4):
		$_SESSION['cust']['CARD_NO']		= $card_no1."-".$card_no2."-".$card_no3."-".$card_no4;
	else:
		$_SESSION['cust']['CARD_NO']		= "";
	endif;
	$_SESSION['cust']['LAST_NAME']			= $last_name;
	$_SESSION['cust']['FIRST_NAME']			= $first_name;
	$_SESSION['cust']['LAST_KANA']			= $last_kana;
	$_SESSION['cust']['FIRST_KANA']			= $first_kana;
	$_SESSION['cust']['ZIP_CD1']			= $zip1;
	$_SESSION['cust']['ZIP_CD2']			= $zip2;
	$_SESSION['cust']['STATE']				= $state;
	$_SESSION['cust']['ADDRESS1']			= $address1;
	$_SESSION['cust']['ADDRESS2']			= $address2;
	$_SESSION['cust']['TEL1']				= $tel1;
	$_SESSION['cust']['TEL2']				= $tel2;
	$_SESSION['cust']['TEL3']				= $tel3;
	$_SESSION['cust']['EMAIL']				= $email;
	$_SESSION['cust']['REMARKS']			= $remarks;
	//$_SESSION['cust']['data_copy_flg']	 	= $data_copy_flg;

	$_SESSION['cust']['PASSWORD']			= $password;
	$_SESSION['cust']['PASSWORD2']			= $password2;

#===============================================================================
# 配送先情報入力ページより：入力された配送先情報の入力チェックを行う
# 	※入力エラーがある場合はエラーメッセージを設定する
#	※配送先と個人情報が一緒の場合は個人情報のデータを配送先に代入
#===============================================================================

// 配送情報が未入力なら配送先も購入者と同じにする。
if(!$deli_last_name && !$deli_first_name && !$deli_zip1 && !$deli_zip2 && !$deli_address1 && !$deli_tel1 && !$deli_tel2 && !$deli_tel3){

	$deli_last_name    = $_SESSION['cust']['LAST_NAME'];
	$deli_first_name   = $_SESSION['cust']['FIRST_NAME'];
	$deli_zip1         = $_SESSION['cust']['ZIP_CD1'];
	$deli_zip2         = $_SESSION['cust']['ZIP_CD2'];
	$deli_state        = $_SESSION['cust']['STATE'];
	$deli_address1     = $_SESSION['cust']['ADDRESS1'];
	$deli_address2     = $_SESSION['cust']['ADDRESS2'];
	$deli_tel1         = $_SESSION['cust']['TEL1'];
	$deli_tel2         = $_SESSION['cust']['TEL2'];
	$deli_tel3         = $_SESSION['cust']['TEL3'];

}

	// 配送先の受取人の氏名（漢字）
	$error_message .= utilLib::strCheck($deli_last_name,0,"配送先お名前（姓漢字）を入力してください。<br>\n");
	if ( mb_strlen($deli_last_name) > 100 )$error_message .= "配送先お名前（姓漢字）の文字数をお減らしください。<br>\n";
	$error_message .= utilLib::strCheck($deli_first_name,0,"配送先お名前（名漢字）を入力してください。<br>\n");
	if ( mb_strlen($deli_first_name) > 100 )$error_message .= "配送先お名前（名漢字）の文字数をお減らしください。<br>\n";

	// 配送先郵便番号
	$deli_zip1 = mb_convert_kana($deli_zip1,"n");
	$error_message .= utilLib::strCheck($deli_zip1,0,"配送先郵便番号（左）を入力してください。<br>\n");
	if($deli_zip1)$error_message .= utilLib::strCheck($deli_zip1,2,"配送先郵便番号（左）は数字で入力してください。<br>\n");

	$deli_zip2 = mb_convert_kana($deli_zip2,"n");
	$error_message .= utilLib::strCheck($deli_zip2,0,"配送先郵便番号（右）を入力してください。<br>\n");
	if($deli_zip2)$error_message .= utilLib::strCheck($deli_zip2,2,"配送先郵便番号（右）は数字で入力してください。<br>\n");

	// 配送先都道府県
	$error_message .= utilLib::strCheck($deli_state,0,"配送先都道府県を選択してください。<br>\n");
	if($deli_state)$error_message .= utilLib::strCheck($deli_state,2,"配送先都道府県の選択方法に誤りがあります。<br>\n");

	// 配送先ご住所（市町村以下）
	$error_message .= utilLib::strCheck($deli_address1,0,"配送先ご住所(市町村以下)を入力してください。<br>\n");
	if ( mb_strlen($deli_address1) > 250 )$error_message .= "配送先ご住所(市町村以下)の文字数をお減らしください。<br>\n";

	// 配送先電話番号
	$deli_tel1 = mb_convert_kana($deli_tel1,"n");
	$error_message .= utilLib::strCheck($deli_tel1,0,"配送先電話番号（左）を選択してください。<br>\n");
	if($deli_tel1)$error_message .= utilLib::strCheck($deli_tel1,2,"配送先電話番号（左）は数字で入力してください。<br>\n");

	$deli_tel2 = mb_convert_kana($deli_tel2,"n");
	$error_message .= utilLib::strCheck($deli_tel2,0,"配送先電話番号（中央）を選択してください。<br>\n");
	if($deli_tel2)$error_message .= utilLib::strCheck($deli_tel2,2,"配送先電話番号（中央）は数字で入力してください。<br>\n");

	$deli_tel3 = mb_convert_kana($deli_tel3,"n");
	$error_message .= utilLib::strCheck($deli_tel3,0,"配送先電話番号（右）を選択してください。<br>\n");
	if($deli_tel3)$error_message .= utilLib::strCheck($deli_tel3,2,"配送先電話番号（右）は数字で入力してください。<br>\n");

	#---------------------------------------------------------------------------------------------------------
	# 配送先情報：エラーチェック結果にかかわらず、取得データをセッションに格納(再入力画面で再表示するためｎ)
	#---------------------------------------------------------------------------------------------------------
	$_SESSION['cust']['DELI_LAST_NAME']  = $deli_last_name;
	$_SESSION['cust']['DELI_FIRST_NAME'] = $deli_first_name;
	$_SESSION['cust']['DELI_ZIP_CD1']    = $deli_zip1;
	$_SESSION['cust']['DELI_ZIP_CD2']    = $deli_zip2;
	$_SESSION['cust']['DELI_STATE']      = $deli_state;
	$_SESSION['cust']['DELI_ADDRESS1']   = $deli_address1;
	$_SESSION['cust']['DELI_ADDRESS2']   = $deli_address2;
	$_SESSION['cust']['DELI_TEL1']       = $deli_tel1;
	$_SESSION['cust']['DELI_TEL2']       = $deli_tel2;
	$_SESSION['cust']['DELI_TEL3']       = $deli_tel3;

	break;

case "step1":
#===============================================================================
# 認証ページより：入力されたemailアドレスを元に認証チェック＆個人情報取得を行う
#
# 	利用経験なし：この処理をスルーする
# 	利用経験あり：個人情報テーブル（CUSTOMER_LST）よりデータを取得し、認証を行う
#===============================================================================

	/// 入力情報(お客様情報)を破棄
	$_SESSION['cust'] = array();

	// 買い物カゴの中身をチェック
	if ( !getItems() )	$error_message .= "買い物カゴの中身が空です。<br>\n";

	// 利用経験の有無のチェック
	// メールアドレスが入力されていれば、経験ありと判断
	if ( !empty($email) ){
	///////////////////////////////////////////////////////////////
	// 利用経験あり

		// 半角英数字に統一
		$email = mb_convert_kana($email, "a");

		// 未入力以外のエラーチェック
		$e_chk = "";
		$e_chk .= utilLib::strCheck($email, 1, true);
		$e_chk .= utilLib::strCheck($email, 4, true);
		$e_chk .= utilLib::strCheck($email, 5, true);
		$e_chk .= utilLib::strCheck($email, 6, true);

		if ( $e_chk )	$error_message .= "Eメールアドレスに誤りがあります。<br>\n";

		// パスワードの入力チェック
		$error_message .= utilLib::strCheck($pwd, 0, "パスワードを入力してください。<br>\n");

		if( !$error_message ):
		#--------------------------------------------------------------
		#	※ここまでエラーチェックに引っかかってない場合に下記の処理
		#
		# 個人情報テーブルからデータを取得し、認証を行う
		#	※結果なし：エラーメッセージを設定
		#	※結果あり：個人情報データをセッションに格納
		#--------------------------------------------------------------
			$sql = "
			SELECT
				CUSTOMER_ID,
				LAST_NAME,
				FIRST_NAME,
				LAST_KANA,
				FIRST_KANA,
				ALPWD,
				ZIP_CD1,
				ZIP_CD2,
				STATE,
				ADDRESS1,
				ADDRESS2,
				EMAIL,
				TEL1,
				TEL2,
				TEL3,
				INS_DATE,
				DEL_FLG
			FROM
				".CUSTOMER_LST."
			WHERE
				( EMAIL = '$email' )
			AND
				( ALPWD = '".utilLib::strRep($pwd,5)."' )
			AND
				( DEL_FLG = '0' )
			AND
				(EXISTING_CUSTOMER_FLG = '1')
			";
			$fetchCust = dbOpe::fetch($sql, DB_USER, DB_PASS, DB_NAME, DB_SERVER);

			if ( $fetchCust[0]['CUSTOMER_ID'] == "" || $fetchCust[0]['CUSTOMER_ID'] == NULL ){
				//$error_message .= "お客様の情報はご登録されていません。<br>\n";
				$error_message .= "メールアドレスまたはパスワードが正しくありません。<br>\n";

			}
			else{
				$_SESSION['cust'] = $fetchCust[0];
				$_SESSION['cust']['auth'] = 1;
				// 利用経験フラグをセッションに格納
				$_SESSION['cust']['m'] = 1;
			}

		endif;


	}else{
	///////////////////////////////////////////////////////////////
	// 利用経験なし
		$_SESSION['cust'] = array();

		// 利用経験フラグをセッションに格納
		$_SESSION['cust']['m'] = 0;
		$_SESSION['cust']['auth'] = 0;

	}

	break;
endswitch;
?>
