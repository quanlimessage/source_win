<?php
/******************************************************************************************************************************
■CLI版メール送信プログラム操作クラス

	CLI版メール送信プログラム（sendmailCLI.php）を簡単に使うためのクラスです。

	○使用上の注意
		このファイルとsendmailCLI.phpファイルは同じ場所に置いてください。
		その際、コンストラクタ内（34行目）のsendmailCLI.phpのプログラムファイルパスを
		環境に合わせて変更してください。

	◆メソッド
		○メール送信
			sendmail(宛て先メールアドレス, 件名, 本文, 差出人名, 送信元メールアドレス)

		○差込情報のセット
			setInsertion(検索文字, 置換文字列リスト)

 Version Up Info
 2004/10/13 : v1.0 sendmailCLI.php v1.0対応版
 2004/10/25 : v2.0 sendmailCLI.php v2.0対応版

 Author : kinoi
 Modify : 2007.02.12 n.tamiya
******************************************************************************************************************************/
class bgMail{
	var $emailList;			// 宛て先メールアドレス（複数可）
	var $emailNum;			// 宛て先メールアドレスの数

	var $subject;			// 件名
	var $mailbody;			// メール本文
	var $fromName;			// 送信者名
	var $fromAddress;		// 送信元メールアドレス
	var $insertionData;		// 差込データ

	var $program_path = "./class/sendmailCLI.php";	// sendmailCLI.phpのパス

//=============================================================================================================================
// ◆コンストラクタ
//=============================================================================================================================
function bgMail( $sub, $body, $name, $adr){

	if( empty($sub) || empty($body) || empty($name) || empty($adr)):
		exit( "引数が未設定のため、強制終了しました。<br>“件名”、“本文”、“差出人名”、“送信元メールアドレス”の順で設定してください。" );
	else:
		$this->emailNum      = 0;
		$this->subject       = $sub;
		$this->mailbody      = $body;
		$this->fromName      = $name;
		$this->fromAddress   = $adr;
		$this->insertionData = array();
	endif;

}

//=============================================================================================================================
// ◆差込設定メソッド
// 		メール本文中の一部を送り先によって変える場合（差込）の設定を行うメソッド
//
// 		setInsertion("検索する文字", "置換する文字列リスト");
//
// 		○使用例
// 			$bgMail = new bgMail(SUBJECT, BODY, NAME, ADDRESS);
// 			$bgMail->setInsertion($search_word, $replace_word_list);
//
// 		※差込処理を行う場合は、インスタンスを生成してください。
//
//=============================================================================================================================
function setInsertion( $search, $rep ){

	// エラーチェック
	if ( empty($search) || empty($rep) ){
		exit( "引数が未設定のため、強制終了しました。<br>“検索する文字”、“置換する文字列リスト”の順で設定してください。" );
	}else if ( $this->emailNum > 0 && $this->emailNum != count($rep) ){
		exit( "送信宛て先数と置換文字列リストの数が一致していません。" );
	}else{

		#-----------------------------
		# 差込設定
		#-----------------------------
		// 送信宛て先数を$repの要素数に設定
		// emailNumが設定されていない場合を考慮
		$this->emailNum = count($rep);

		// 差込情報の作成
		$insertion = array();
		$insertion['search'] = $search;								// 検索する文字の設定
		for ( $i = 0; $i < $this->emailNum; $i++ ){
			array_push( $insertion, $rep[$i] );					// 置換文字列の設定
		}
		// メンバ変数に追加
		array_push( $this->insertionData, $insertion );

	}

}

//=============================================================================================================================
// ◆変数もしくは配列で、宛て先メールアドレスを指定し、メールを送信するメソッド
//
// 		sendmail("単一または配列のメールアドレスリスト",[件名],[本文],[差出人名],[送信元メールアドレス]);
// 		（[]内は直接クラスメソッドへアクセスした場合に記述する）
//
// 		○使用例
// 			$bgMail = new bgMail(SUBJECT, BODY, NAME, ADDRESS);
// 			$bgMail->sendmail($emailList);
//
// 			※直接クラスメソッドからコールする場合は下記のように記述
// 			bgMail::sendmail($emailList, SUBJECT, BODY, NAME, ADDRESS);
//
// 		エラー時には、exit()で強制終了。
//
//=============================================================================================================================
function sendmail( $em, $sub="", $body="", $name="", $adr="" ){

	// エラーメッセージの初期化
	$error_mes = "";

	#-----------------------------
	# 引数チェック
	#-----------------------------
	// 宛て先メールアドレス
	if( empty($em) ){
		$error_mes .= "宛て先メールアドレスが設定されていません。<br>\n";
	}else{
		$this->emailList =& $em;
		$this->emailNum = count($em);
	}

	// 件名
	if( $sub ){
		$this->subject = $sub;
	}else if ( !$this->subject ){
		$error_mes .= "件名が設定されていません。<br>\n";
	}

	// メール本文
	if( $body ){
		$this->mailbody = $body;
	}else if( !$this->mailbody ){
		$error_mes .= "メール本文が設定されていません。<br>\n";
	}

	// 送信者名
	if( $name ){
		$this->fromName = $name;
	}else if( !$this->fromName ){
		$error_mes .= "送信者名が設定されていません。<br>\n";
	}

	// 送信元メールアドレス
	if( $adr ){
		$this->fromAddress = $adr;
	}else if( !$this->fromAddress ){
		$error_mes .= "送信元メールアドレスが設定されていません。<br>\n";
	}

	// エラーがあったら強制終了
	if( $error_mes ) exit( "<p>以下の理由で強制終了しました。</p>\n{$error_mes}<p>“宛て先メールアドレス”、“件名”、“メール本文”、“送信者名”、“送信元メールアドレス”の順で設定し直してください。</p>\n" );

	#------------------------------------
	# メール送信のための設定ファイル作成
	#------------------------------------

	$data_file = getcwd() . '/tmp/mail_tmp' . date("YmdHis") . '.ini';
	$fp = fopen( $data_file, "w" ) or exit("ファイルオープンエラー" . $data_file);
	flock( $fp, LOCK_EX );
	fputs( $fp, serialize($this) );
	flock( $fp, LOCK_UN );
	fclose( $fp );

	#-----------------------------
	# メール送信プログラムの実行
	#-----------------------------
	// シェルコマンドとして有効となる文字列を除去
	$program_path = escapeshellarg( $this->program_path );
	$data_file    = escapeshellarg( $data_file );

	// 外部プログラム（sendmailCLI.php）実行
	system( "php $program_path $data_file > /dev/null &" );

}

}

/******************************************************************************************************************************
■CLI版メール送信プログラム操作クラス（dbOpeクラス対応仕様）

	CLI版メール送信プログラム（sendmailCLI.php）を簡単に使うためのクラスを、
	dbOpeクラスに対応させたものです。

	○使用上の注意
		このファイルとsendmailCLI.phpファイルは同じ場所に置いてください。

	◆メソッド
		○メール送信
			sendmail(fetchメソッドの結果, メールアドレスが格納されているフィールド名, 件名, 本文, 差出人名, 送信元メールアドレス)

		○差込情報のセット
			setInsertion(検索文字, 置換文字列リスト)

 Version Up Info
 2004/11/1 : ver1.0 bgMailクラスから分離。継承クラスとする。

 Author : kinoi
******************************************************************************************************************************/
class bgMailDB extends bgMail{

//=============================================================================================================================
// ◆dbOpeクラスのfetchメソッド結果で、宛て先メールアドレスを指定し、メールを送信するメソッド
//
// 		sendmail_db("fetchメソッド結果","メールアドレスが格納されているフィールド名",[件名],[本文],[差出人名],[送信元メールアドレス]);
// 		（[]内は直接クラスメソッドへアクセスした場合に記述する）
//
// 		○使用例
// 			$bgMail = new bgMail(SUBJECT, BODY, NAME, ADDRESS);
// 			$bgMail->sendmail($fetchMailList, "EMAIL");
//
//
// 		fetchメソッド結果からメールアドレスを取り出して配列に格納し、sendmailメソッドを使用して、メール送信
//
//=============================================================================================================================
function sendmail( $fetch, $field, $sub="", $body="", $name="", $adr="" ){

	#-----------------------------
	# dbOpeクラスのfetchメソッド結果からメールアドレスを取り出して配列に格納
	#-----------------------------
	$emailFromDB = array();
	for ( $i = 0; $i < count($fetch); $i++ ){
		array_push( $emailFromDB, $fetch[$i][$field] );
	}

	#-----------------------------
	# 親クラスのメソッドsendmailを実行
	#-----------------------------
	bgMail::sendmail( $emailFromDB, $sub, $body, $name, $adr );

}

//=============================================================================================================================
// ◆dbOpeクラスのfetchメソッド結果で、差込設定するメソッド
//
// 		setInsertion("検索する文字", "置換する文字列リスト（fetchメソッド結果）", "フィールド名");
//
// 		○使用例
// 			$bgMail = new bgMail(SUBJECT, BODY, NAME, ADDRESS);
// 			$bgMail->setInsertion($search_word, $replace_word_list, "REP");
//
// 		※差込処理を行う場合は、インスタンスを生成してください。
//
//=============================================================================================================================
function setInsertion( $search, $rep, $field ){

	// エラーチェック
	if ( empty($search) || empty($rep) || empty($field) ){
		exit( "引数が未設定のため、強制終了しました。<br>“検索する文字”、“置換する文字列リスト（fetchメソッド結果）”、“フィールド名”の順で設定してください。" );
	}else if ( $this->emailNum > 0 && $this->emailNum != count($rep) ){
		exit( "送信宛て先数と置換文字列リストの数が一致していません。" );
	}else{

		#-----------------------------
		# 差込設定
		#-----------------------------
		// 送信宛て先数を$repの要素数に設定
		// emailNumが設定されていない場合を考慮
		$this->emailNum = count($rep);

		// 差込情報の作成
		$insertion = array();
		$insertion['search'] = $search;									// 検索する文字の設定
		for ( $i = 0; $i < $this->emailNum; $i++ ){
			array_push( $insertion, $rep[$i][$field] );		// 置換文字列の設定
		}
		// メンバ変数に追加
		array_push( $this->insertionData, $insertion );

	}

}

}

?>
