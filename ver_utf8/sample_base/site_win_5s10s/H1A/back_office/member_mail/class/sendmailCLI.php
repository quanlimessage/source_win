<?php
/*******************************************************************************************
■CLI(Command Line Interface)版メール送信プログラム

	大量のメールアドレスに一括送信する場合に使用します。
	ブラウザのタイムアウトエラーを起こさないように、バックグラウンドで動作させるプログラムです。

	○使い方
		基本的には、このプログラムを簡単に使用するための操作クラス(bgMail)と合わせて使用する。

		受け渡しする引数（※コマンドラインのため、受け取った引数は $_SERVER['argv'] に入る）

		$_SERVER['argv'][0] : このスクリプト名（例：/home/hoge/sendmailCLI.php）
		$_SERVER['argv'][1] : メール設定情報を記述したファイルパス

	○諸注意
		このプログラムは、コマンドラインで動作するため、実行するに当たって少し注意が必要です。
		最低限のLinuxコマンドは知っておきましょう。
		このファイルを修正して使用した場合に、失敗すると大変なことになりかねません。
		知らない方は、リファレンスを見てください。

		無限ループに陥った場合のとりあえずの対処法
			ttssh等のプログラムで、サーバに接続し、プロセスを調べます。（コマンド "ps -aux"）
			これを実行すると現在サーバで実行されているプロセスが表示されます。
			その中から、このプログラムのプロセスを探し、プロセスID 'PID' を見ます。

			<?php system("kill PID"); ?> （PIDの部分は、上で調べたもの）
			と書かれたphpファイルを用意し、このプログラムをUPしたサーバと同じサーバにUPし、
			ブラウザでこのphpファイルを開く。

			これで、無限ループに陥ったプログラムを終了させることができます。
			※PIDを調べるときは、十分注意して調べてください。間違って他のプロセスを終了させないように！

 Version Up Info
 2004/10/09 : v1.0 設定情報を引数で与え、実行する仕様
 2004/10/22 : v2.0 完成　設定情報を書き出したファイルを読み込み実行する仕様に変更
 									 （差込処理等のことを考えて）

 Author : kinoi
 Modify : 2007.02.12 n.tamiya
*******************************************************************************************/

// 操作クラスの読み込み
require_once("bgMail.php");

// 実行日時をログに出力
writeLog( date("Y/m/d H:i:s") . "\t" );

#-------------------------------------------------------------------------------------------
# 引数の数をチェック（引数の数が2以外だったらエラーを出力して終了）
#-------------------------------------------------------------------------------------------
if ( $_SERVER['argc'] != 2 ){
	writeLog( "0\tWrong number of arguments.\n" );
	exit();
}

#-------------------------------------------------------------------------------------------
# 設定ファイルの読み込みとエラーチェック
#-------------------------------------------------------------------------------------------
// 設定ファイルの存在チェック
if ( !file_exists( $mailConfigFile = $_SERVER['argv']['1'] ) ){
	writeLog( "0\tMail configuration file does not exist.\n" );
	exit();
}
// 設定ファイルの読み込み＆シリアル化したデータを元に戻す
$config = unserialize( file_get_contents( $mailConfigFile ) );
// 設定ファイルの削除
unlink( $mailConfigFile );

// 宛て先メールアドレスの形式チェック
$cnt = 0;
foreach( $config->emailList as $email ){
	$cnt++;
	if ( !ereg( "^(.+)@(.+)\\.(.+)$", $email ) ){
		writeLog( "0\tWrong mail address format at No.$cnt.\n" );
		exit();
	}
}

// 送信元メールアドレスの形式チェック
if ( !ereg( "^(.+)@(.+)\\.(.+)$", $config->fromAddress )  ){
	writeLog( "0\tWrong From mail address format.\n" );
	exit();
}

#-------------------------------------------------------------------------------------------
# メールの基本設定
#-------------------------------------------------------------------------------------------
// 文字コード設定
mb_language( "Japanese" );
mb_internal_encoding( "UTF-8" );

	////////////////////////////////////////////////////////////////
	//gmailでReply-Toをカンマ区切りでメールを送信すると弾かれる仕様なのでカンマがあった場合は一番最初のメールアドレスを
	//Reply-Toに設定する（この設定はエンドユーザーに送信するメールに対して行う）
	//mb_send_mailのReturn-Pathの設定
		$master_mail = $config->fromAddress;
		$master_mail = substr($master_mail,0,strpos($master_mail,","));//メールアドレスがカンマ区切りになっていた場合用にメールアドレスを一つだけ抽出処理
		$master_mail = ($master_mail)?$master_mail:$config->fromAddress;//空っぽだった場合はカンマ区切りは無し

// Headerの設定
$headers  = "Reply-To: {$master_mail}\n";
$headers .= "Return-Path: {$master_mail}\n";
$headers .= "From: " . mb_encode_mimeheader( $config->fromName ) . "<{$master_mail}>\n";
	$rpath = "-f ".$master_mail;//mb_send_mailのReturn-Path を設定

// Subjectの設定
$subject = $config->subject;

#-------------------------------------------------------------------------------------------
# メール送信処理（差込処理がある場合とない場合で処理を分ける）
#-------------------------------------------------------------------------------------------

if ( isset($config->insertionData) ):
////////////////////////////////////////////////////////////////////////////////////////////
// 差込処理 → メール送信

	// 宛て先メールアドレス分繰り返す
	for ( $i = 0; $i< $config->emailNum; $i++ ){

		// メールアドレス・メール本文の取り出し
		$email = $config->emailList[$i];
		$mailbody = $config->mailbody;

		// 差込処理
		for ( $j = 0; $j < count($config->insertionData); $j++ ){

			if( strstr( $mailbody, $config->insertionData[$j]['search'] ) ){
				$mailbody =  str_replace( $config->insertionData[$j]['search'], $config->insertionData[$j][$i], $mailbody );
			}

		}

		// 文字コード変換(UTF-8)
		// サーバーによって文字化け文字を入力した場合文字化けを起こす場合がございます。
		// 文字化け文字を入力した場合でメール内容が文字化けをしたら文字コード変換処理はコメントアウトをしてください。
		// （AI04サーバーで文字化けが起こります。（誠,申など先頭に入れた場合誤認識をして変換処理をします。））
		$mailbody = mb_convert_encoding( $mailbody, "UTF-8", "auto" );

		// メール送信実行
		$result_sendmail = mb_send_mail( $email, $subject, $mailbody, $headers, $rpath);

		// 失敗したら、ログファイルにエラー出力し、終了
		//if( !$result_sendmail ){ writeLog( "0\tMail send error : send to {$email}\n" ); exit(); }

	}

else:
////////////////////////////////////////////////////////////////////////////////////////////
// メール送信

	// メール本文の設定
	$mailbody = $config->mailbody;
		// サーバーによって文字化け文字を入力した場合文字化けを起こす場合がございます。
		// 文字化け文字を入力した場合でメール内容が文字化けをしたら文字コード変換処理はコメントアウトをしてください。
		// （AI04サーバーで文字化けが起こります。（誠,申など先頭に入れた場合誤認識をして変換処理をします。））
	$mailbody = mb_convert_encoding( $mailbody, "UTF-8", "auto" );

	// 宛て先メールアドレス分繰り返す
	foreach( $config->emailList as $email ){

		// メール送信実行
		$result_sendmail = mb_send_mail( $email, $subject, $mailbody, $headers, $rpath);

		// 失敗したら、ログファイルにエラー出力し、終了
		if( !$result_sendmail ){ writeLog( "0\tmail send error : send to {$email}\n" ); exit(); }

	}

endif;

// ログに成功フラグを出力し、終了
writeLog( "1\t\n" );

//////////////////////////////////////////////////////////////////////////////////////////////
// ログの出力
//////////////////////////////////////////////////////////////////////////////////////////////
function writeLog( $message ){
	// ログファイル名
	//if( !$log_file ) $log_file = '/tmp/sendmailCLI_log.log';
	//if( !$log_file ) $log_file = '../tmp/sendmailCLI_log.log';
	if( !$log_file ) $log_file = str_replace("/class","",dirname(__FILE__)).'/tmp/sendmailCLI_log.log';

	$fp = fopen( $log_file, "a" ) or exit( "file open error." );
	flock( $fp, LOCK_EX );
	fputs( $fp, $message );
	flock( $fp, LOCK_UN );
	fclose( $fp );
}

?>
