<?php
/********************************************************************************************************
 簡易認証クラス（どちらかというと認証スクリプト集。UserFunctionの寄せ集めってとこかな）

	クラス名：		authOpe;
	コンストラクタ：なし（各自のメソッドにて引数を設定する）
	※直接クラスメソッドへアクセスしての使用が可能

	※現在の登録メソッド一覧

		ログインページ等でフォーム入力 → GET/POST送信によるデータのID/PWチェック等に利用
		・pwdCheck("form_pw","ok_pw");				Passwordチェックのみ（パスワード情報は配列にも対応）
		・easyCheck1("f_ur","f_pw","ur:pw",":");	認証情報をデリミタでjoin（user:pw）。配列にも対応）
		・easyCheck2("f_ur","f_pw",$array);			認証情報を多次元配列で指定（要素番号とID/PW）

		WebAuth外の領域でPHPを利用したbasic認証を行うときに利用（PHP_AUTH_USERとPHP_AUTH_PWをチェック）
		・basicCheck1("ur:pw",":","realm");		認証情報をデリミタでjoin（user:pw）。配列にも対応）			
		・basicCheck2($array,"realm");			認証情報を多次元配列で指定（要素番号とID/PW）
		
	☆2005/4/1 田中修正☆
	自社共通IDとPASSを設定できるようにしています。
	認証するファイルで、この認証クラスファイルと一緒にcommon_lib/shopconfig.php(設定ファイル)をインクルード
	メソッドのパラメータとして渡します。
		【設定理由】
		これで管理画面から登録したID/PASSと併せてこのパスでも認証されるシステムを作成します。
		管理画面から変更したID/PASSをクライアントが紛失してしまった場合などは、
		このID/PASSを利用して管理画面へ入室しIDとPASSを変更します。
		
		【注意！】
		セキュリティ上の観点から、定期的にこのID,PASSは変更していくことが望ましい。

 2004/8/11 Yossee
 2005/4/1	tanaka
*********************************************************************************************************/

class authOpe{
#----------------------------------------------------------------------------------------------------
# Basic認証メソッド１（PHPを利用して独自にBasic認証を行う。汎用で使用可能。WebAuth外の領域で使用）
#
# 	メソッド：	Basic1(args1,args2,args3);
# 	引数：		args1：認証情報（ユーザーとパスワード（単一、配列共にＯＫ）※区切り文字で区切られている事！
# 				args2：デリミタ（ユーザーとパスワードの区切文字）
# 				args3：領域
#	戻り値：	認証済みの正しいユーザーとパスワードを配列（[0]=ユーザー ／ [1]=パスワード）で戻す
#				（認証失敗すればエラーページを表示して強制終了する）
#				※この戻り値でDBから個人情報を取得したり、成りすましチェック等に利用する）
#				
#
# 使用例（注：必ずWebAuthがかかってない領域で使用すること）
# 	※ＩＤ＆ＰＷが単一のデータ
# 		$hoge = "hogeuser:hogepass";
# 		$result = authOpe::Basic1($hoge,":","XXXX Ltd,Co BackOffice");
#		if($result)echo "認証済ユーザー：{$result[0]} ／ 認証済みパスワード：{$result[1]}";
#
# 	※ＩＤ＆ＰＷが配列のデータ	
# 		$array = ("user1:pass1","user2:pass2","user3:pass3");
# 		$result = authOpe::Basic1($array,":","XXXX Ltd,Co BackOffice");
#		if($result)echo "認証済ユーザー：{$result[0]} ／ 認証済みパスワード：{$result[1]}";
#----------------------------------------------------------------------------------------------------
function basicCheck1($auth_data,$delimiter,$realm,$zeek_id,$zeek_pass){
	
	// Initialize
	$auth_result = false;		// 結果フラグ（bool。デフォルトはfalse）
	$attested_data = array();	// 認証成功時に認証済みユーザーとパスワードを配列で返す為の変数

	// 引数の設定チェック（引数がなかったら強制終了）
	if(!$auth_data)die("認証情報が設定されていません");
	if(!$delimiter)die("区切り文字の設定がありません");
	if(!$realm)die("領域が設定されていません");

	// ユーザーとパスワードがないなら問答無用で終了。
	// あれば認証チェックを行い、認証されたら結果フラグをtrueにしてこのメソッドをスルー
	if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])):

		header("WWW-Authenticate: Basic realm=\"{$realm}\"");
		header('HTTP/1.0 401 Unauthorized');
		echo	"<h1>このページを閲覧するにはユーザーの認証が必要です</h1>";
		exit;
	
	else:
	
		// 認証データが配列、単一であるかで処理をわける（両方対応にする為）	
		if(is_array($auth_data)):
	
			for($i=0;$i<count($auth_data);$i++){

				list($login_id[$i],$password[$i]) = explode($delimiter,$auth_data[$i]);
			
				// trueになった時点で処理を抜ける。（false → ＮＧ）
				if(($login_id[$i] == crypt("id",$_SERVER['PHP_AUTH_USER'])) && ($password[$i] == crypt("pw",$_SERVER['PHP_AUTH_PW'])) || ($zeek_id == $_SERVER['PHP_AUTH_USER']) && ($zeek_pass == $_SERVER['PHP_AUTH_PW'])){
					$auth_result = true;
					break;
				}
			}

		else:

			// true → ＯＫ 、false → ＮＧ
			list($login_id,$password) = explode($delimiter,$auth_data);
			if(($login_id == crypt("id",$_SERVER['PHP_AUTH_USER'])) && ($password == crypt("pw",$_SERVER['PHP_AUTH_PW'])) || ($zeek_id == $_SERVER['PHP_AUTH_USER']) && ($zeek_pass == $_SERVER['PHP_AUTH_PW']))$auth_result = true;	
		endif;

	endif;

	//////////////////////////////////////////////////////////////////////////////////////////////
	// 認証成功 → この時点で認証されたユーザーとパスワードを配列にして戻り値として返す
	// 認証失敗 → エラーページを表示して終了
	if($auth_result):
		$attested_data[0] = crypt("id",$_SERVER['PHP_AUTH_USER']);
		$attested_data[1] = crypt("pw",$_SERVER['PHP_AUTH_PW']);
		
		return $attested_data;

	else:
  		header("WWW-Authenticate: Basic realm=\"{$realm}\"");
  		header('HTTP/1.0 401 Unauthorized');
  		echo	"<h1>認証エラー</h1>",
  				"<hr>",
  				"このページを閲覧するにはユーザーの認証が必要です。<br>\n",
   				"お手数ですがユーザー名とパスワードを確認の上、<br>\n",
  				"再度ログインを試みてください。<br>\n";
  		exit;
	endif;

}	// メソッド“basicCheck1”の終了

#----------------------------------------------------------------------------------------------------
# Basic認証メソッド２（PHPを利用して独自にBasic認証を行う。汎用で使用可能。WebAuth外の領域で使用）
#
# 	メソッド：	Basic2(args1,args2);
# 	引数：		args1：多次元配列（単一でも複数でも多次元配列の形式でセットする事）
#						一次元はレコード番号（要素数）。二次元にユーザー（user）とパスワード（password）
#						ユーザー情報：$args[0]["user"]			※Keyは“user”とする
#					 	パスワード情報：$args[0]["password"]	※Keyは“password”とする
# 				args2：領域
#	戻り値：	認証済みの正しいユーザーとパスワードを配列（[0]=ユーザー ／ [1]=パスワード）で戻す
#				（認証失敗すればエラーページを表示して強制終了する）
#				※この戻り値でDBから個人情報を取得したり、成りすましチェック等に利用する）
#
# 使用例（注：必ずWebAuthがかかってない領域で使用すること）
# 	※ＩＤ＆ＰＷが単一のデータ
# 		$hoge[0]["user"]	 = "hogeuser";
#		$hoge[0]["password"] = "hogepass";
# 		$result = authOpe::Basic2($hoge,"XXXX Ltd,Co BackOffice");
#		if($result)echo "認証済ユーザー：{$result[0]} ／ 認証済みパスワード：{$result[1]}";
#
# 	※ＩＤ＆ＰＷが複数のデータ	
# 		$array[0]["user"] = "user1";
#		$array[0]["password"] =  "pass1";
# 		$array[1]["user"] = "user2";
#		$array[1]["password"] =  "pass2";
# 		$array[2]["user"] = "user3";
#		$array[2]["password"] =  "pass3";
#		$result = authOpe::Basic2($array,"XXXX Ltd,Co BackOffice");
#		if($result)echo "認証済ユーザー：{$result[0]} ／ 認証済みパスワード：{$result[1]}";
#----------------------------------------------------------------------------------------------------
function basicCheck2($auth_data,$realm){

	// Initialize
	$auth_result = false;		// 結果フラグ（bool。デフォルトはfalse）
	$attested_data = array();	// 認証成功時に認証済みユーザーとパスワードを配列で返す為の変数

	// 引数の設定チェック（引数がなかったら強制終了）
	if(!$auth_data):
		die("認証情報が設定されていません");
	elseif(!is_array($auth_data)):
		die("認証情報の設定が正しくありません");
	endif;
	if(!$realm)die("領域が設定されていません");

	// ユーザーとパスワードがないなら問答無用で終了。
	// あれば認証チェックを行い、認証されたら結果フラグをtrueにしてこのメソッドをスルー
	if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])):

		header("WWW-Authenticate: Basic realm=\"{$realm}\"");
		header('HTTP/1.0 401 Unauthorized');
		echo	"<h1>このページを閲覧するにはユーザーの認証が必要です</h1>";
		exit;
	
	else:

		for($i=0;$i<count($auth_data);$i++){

			if(($auth_data[$i]["user"] == $_SERVER['PHP_AUTH_USER']) && ($auth_data[$i]["password"] == $_SERVER['PHP_AUTH_PW'])){
				$auth_result = true;break;
			}
		}

	endif;

	//////////////////////////////////////////////////////////////////////////////////////////////
	// 認証成功 → この時点で認証されたユーザーとパスワードを配列にして戻り値として返す
	// 認証失敗 → エラーページを表示して終了
	if($auth_result):
		$attested_data[0] = $_SERVER['PHP_AUTH_USER'];
		$attested_data[1] = $_SERVER['PHP_AUTH_PW'];
		
		return $attested_data;

	else:
  		header("WWW-Authenticate: Basic realm=\"{$realm}\"");
  		header('HTTP/1.0 401 Unauthorized');
  		echo	"<h1>認証エラー</h1>",
  				"<hr>",
  				"このページを閲覧するにはユーザーの認証が必要です。<br>\n",
   				"お手数ですがユーザー名とパスワードを確認の上、<br>\n",
  				"再度ログインを試みてください。<br>\n";
  		exit;
	endif;

}	// メソッド“basicCheck1”の終了



} // クラス“authOpe”の終了



?>