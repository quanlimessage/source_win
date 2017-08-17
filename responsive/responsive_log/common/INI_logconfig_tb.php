<?php
/*******************************************************************************
アクセス解析設定ファイル SQLite版

2006.04.19 fujiyama
*******************************************************************************/

// マルチバイト関数用に言語と文字コードを指定する（文字列置換、メール送信等で必須）
mb_language("Japanese");
mb_internal_encoding("EUC-JP");

#=================================================================================
#APIキーを設定 ここには記録しているだけで定数は使用していない。
#=================================================================================
define('APIKEY','ABQIAAAAzUeQaij4iMqMQPJle9Iv4RQm2qs8Zmu-k6zztjdh0ayEoC2uLBRhCOIpme2p3-uPVbvgjpAsr5LaTA');

#===================================================================================
# バージョンの設定(１か2を設定する)
# 設定値 1 : 検索エンジン、検索キーワードの表示なしオプションの為デフォルトにしておく
# 設定値 2 : 検索エンジン、キーワードの表示あり
#===================================================================================
define('VARSION_CONFIG',2);

#=================================================================================
#ログファイルのPV数の上限値を設定
#=================================================================================
define('LOGFILE_SIZE_MAX',100000);

#=================================================================================
#PV数が上限値を越えた際のメール送信の有無（0:送信しない、1:送信する）
#=================================================================================
define('ALERT_MAIL_SEND',0);

#=================================================================================
# データベースでのテーブル名の指定
#=================================================================================
define('ACCESS_LOG','ACCESS_LOG_TB');

#=================================================================================
# ファイル接尾語の指定
#=================================================================================
define('FILE_SUFFIX','_access_log_tb_db');

#=================================================================================
#検索文字用の処理
#=================================================================================
	//ＥＵＣでは全角スペースの置換は一部の文字で不具合起こす為（【ー】を最後にするとスペースと誤認識されてしまう）
	//ＵＴＦ８に変換してから全角を半角スペースに変換する処理を行う
	function utf_preg_replace($pattern){
			$pattern = mb_convert_encoding($pattern, "UTF-8", "EUC-JP");//検索文字をUTF8に変換
			$space_data1 = mb_convert_encoding("　", "UTF-8", "EUC-JP");//比較用の全角スペースをUTF8に変換
			$space_data2 = mb_convert_encoding(" ", "UTF-8", "EUC-JP");//置換用の半角スペースをUTF8に変換
			$subject = @mb_ereg_replace($space_data1, $space_data2, $pattern);//置換処理をする
			$subject = mb_convert_encoding($subject, "EUC-JP", "UTF-8");//UTF8からEUCに戻す
		return $subject;
	}

#=================================================================================
# DBのファイルパスとDB及びテーブルを自動生成するためのSQL文を設定
#	※ディレクトリ名は必要に応じて記述し直す事！（DB名はこのままでＯＫ）
#	※DBを置くディレクトリ“db”のパーミッションは“777”にすること！
#=================================================================================

$date_name = date('Y_m');

$access_path = str_replace("/common","",dirname(__FILE__))."/db/";
define('ACCESS_PATH',$access_path);
define('DB_FILEPATH',ACCESS_PATH.$date_name.FILE_SUFFIX);
define('CREATE_SQL',"CREATE TABLE " . ACCESS_LOG . " (ID INTEGER PRIMARY KEY,REMOTE_ADDR,USER_AGENT,REFERER,QUERY_STRING,ENGINE,OS,BROWSER,PAGE_URL,STATE,UNIQUE_FLG,USER_FLG,INS_DATE,TIME,DEL_FLG DEFAULT 0);");

#==================================================================================
# 検索エンジン及び検索キーワードを取得する関数
# listファイルにあるテキストファイルの読み込み
#==================================================================================

function setting_read($uri){
	if(file_exists($uri)){
		if($arr_exclude = @file($uri)){
			$arr_exclude = @array_unique($arr_exclude);
			foreach($arr_exclude as $k => $v){
				$arr_exclude[$k] = trim($v);
			}
			return $arr_exclude;
		}
	}
}

//検索キーワード取得
function get_keyword($query ,$query_key){
	global $google_cache;

	$keyword = "";
	foreach(explode("&", $query) as $tmp){
		unset($k,$v);
		list($k,$v) = explode("=", $tmp);
		$k = eregi_replace('amp;', '', $k);
		if($k == $query_key){
			if(trim($v) == "") continue;
			$v = urldecode($v);
			if(function_exists('mb_convert_encoding')){
				$v = @mb_convert_encoding($v, "EUC", "auto");
			}else{
				$v = jcode_convert_encoding($v,'euc-jp');
			}
			$v = str_replace('+', ' ', $v);
			$v = utf_preg_replace($v);

			if(function_exists('mb_ereg_replace')){
				$v = @mb_ereg_replace('　', ' ', $v);
			}else{
				$v = jstr_replace('　', ' ', $v);
			}
			$v = ereg_replace(" {2,}", " ", $v);
			$v = trim($v);

			//Googleキャッシュのスキップ
			if($google_cache && ereg('^cache:',$v)) continue;
			if($v == "") continue;

			$v = "［".ereg_replace(' ', '］&nbsp;［', $v)."］";

			$keyword = $v;
			break;
		}
	}

	return $keyword;
}

?>
