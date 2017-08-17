<?php

function reception(){

	#=====================================================================
	# メール本文の読み込み（標準入力）
	#=====================================================================

	if (php_sapi_name()=="cli") {
		while(!feof(STDIN)) {
		$maildata .= fread(STDIN, 4096);
		}
	} elseif (php_sapi_name()=="cgi") {
		$fp = fopen('php://stdin', 'r');//標準入力がない場合ストールするので注意
		while(!feof($fp)) {
		$maildata .= fread($fp, 4096);
		}
	}


	#=====================================================================
	# メールをMIMEデコード
	#=====================================================================
	$params['decode_headers'] = true;	// ヘッダをデコードする

	$decoder = new Mail_mimeDecode($maildata);
	$structure = $decoder->decode($params);


	//////////////////////////////////////////////////////////////////////
	// ヘッダの処理

	// 送信者アドレス
	if ( $structure->headers['from'] ){
		$from = mb_convert_encoding(mb_decode_mimeheader($structure->headers['from']), "Shift_JIS", "auto");
		$from = addr_search($from);
		// メールアドレスではなかったら戻り値がfalse
		if(!$from)$from = false;
	}

	return $from;

}

#=====================================================================
# 関数群
#=====================================================================

////////////////////////////////////////////////////////////////////
// メールアドレス抽出関数
function addr_search($addr){
	if(eregi("[-!#$%&\'*+\\./0-9A-Z^_`a-z{|}~]+@[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+", $addr, $fromreg)){
		return $fromreg[0];
	}else{
		return false;
	}
}


////////////////////////////////////////////////////////////////////
// ログの出力
function writeLog($dir , $message ){
	// ログファイル名
	// if( !$log_file ) $log_file = './log/mail.php';
	if( !$log_file ) $log_file = $dir.'mail.logfile';
	
	$fp = fopen( $log_file, "a" ) or exit(99);
	flock( $fp, LOCK_EX );
	fputs( $fp, date("Y/m/d H:i:s")." {$message}\n" );
	flock( $fp, LOCK_UN );
	fclose( $fp );
	
	chmod($log_file, 0666);
}

/////////////////////////////////////////////////////////////////////
// ID生成
$makeID = create_function('','return date("U")."-".sprintf("%06d",(microtime() * 1000000));');

?>