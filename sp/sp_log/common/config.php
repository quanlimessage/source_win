<?php
/*******************************************************************************
SiteWin10 20 30（MySQL版）
共通設定情報ファイルの定義

*******************************************************************************/
// 設定ファイル＆共通ライブラリの読み込み
require_once("dbOpe.php");			// ＤＢ操作クラスライブラリ
require_once("util_lib.php");		// 汎用処理クラスライブラリ

#=================================================================================
# マルチバイト関数用に言語と文字コードを指定する（文字列置換、メール送信等で必須）
#=================================================================================
mb_language("Japanese");
mb_internal_encoding("UTF-8");

//エラーメッセージの表示の時エンコードがsjisで文字化けをするのを回避
	header("Content-Type: text/html; charset=UTF-8");
	header("Content-Language: ja");

date_default_timezone_set('Asia/Tokyo');//php 5.3.0から時間設定をしないと警告が出てしまうためここで設定をする。

#=================================================================================
# ＤＢ接続の情報（定数化）
#=================================================================================
define('DB_SERVER','localhost');
define('DB_NAME','win_sample4');
define('DB_USER','win_sample4');
define('DB_PASS','127YuoK3');
define('DB_DSN',"mysql:dbname=".DB_NAME.";host=".DB_SERVER.";charset=utf8;");
$PDO = new dbOpe(DB_DSN,DB_USER,DB_PASS);

#=================================================================================
# 管理画面のIDとパスワード
#=================================================================================
define('BO_ID','test');		# ID
define('BO_PW','pass');		# PW

#=================================================================================
# 運用サポート用の情報（定数化）
#=================================================================================
// 運用サポートURL
define('UW_INFO_URL','http://mx03.zeeksdg.net/zeeksdg/navi_info2/');

// ユーザーズウェブ上の顧客コード
define('UW_CUSTOMER_CODE','');

#=================================================================================
# 頻繁に行う簡易処理のファンクション化（匿名関数）
#=================================================================================
// UNIX時間＋マイクロ秒によるID生成
$makeID = create_function('','return date("U")."-".sprintf("%06d",(microtime() * 1000000));');

// パスワード作成
$makePass = create_function('','$pass = crypt(mt_rand(0,99999999),"CP");return ereg_replace("[^a-zA-Z0-9]","",$pass);');

#=================================================================================
# “htmlspecialchars()”でエンティティ化したHTML特殊文字を
#	Flashで正常表示できるように全角に変換するファンクション
#=================================================================================
function h14s_han2zen(&$str){

	$str = str_replace("&amp;","＆",$str);
	$str = str_replace("&quot;","”",$str);
	$str = str_replace("&lt;","＜",$str);
	$str = str_replace("&gt;","＞",$str);
	$str = str_replace("&#39","’",$str);
	$str = str_replace("'","’",$str);
	$str = str_replace("&","＆",$str);
	$str = str_replace("%","%25",$str);
	$str = str_replace("+","%2b",$str);

	return $str;
}

#=================================================================================
# 管理者情報テーブルよりメールアドレスを取得するファンクション
#	メソッド名：getInitData("カラム名")
#=================================================================================
function getInitData($colum = ""){
	global $PDO;
	if(!$colum)$colum = "EMAIL1";
	$sql = "SELECT {$colum} FROM APP_INIT_DATA WHERE(RES_ID = '1')";
	$fetch = $PDO->fetch($sql);
		return $fetch[0][$colum];
}

#=================================================================================
# 管理情報テーブルよりIPリストを取得するファンクション
#	メソッド名：getPermitIPList("ID名")
#=================================================================================
function getPermitIPList($bo_id){
	global $PDO;
	if(!$bo_id || empty($bo_id)) return false;
	$sql = "SELECT PERMIT_IP_LST FROM APP_ID_PASS WHERE(BO_ID = '".utilLib::strRep($bo_id,5)."')";
	$fetch = $PDO -> fetch($sql);
	return explode(",",$fetch[0]["PERMIT_IP_LST"]);
}

#=================================================================================
# 転送処理を行うファンクション（引数：対象URL、戻り値：なし）
#=================================================================================
$layer_free = "Layer_free";//カラーパレットに使用するレイヤー名

function location($url){

echo '<html><head><title></title></head>',
'<body onLoad="Javascript:document.location.submit();">',
'<form name="location" action="'.$url.'" method="post">',
'</form></body></html>',
'<noscript>header("Location: '.$url.'");</noscript>';
exit();
}

#=================================================================================
# ＨＴＭＬタグの有効処理
#
# 【back_office/n○whatsnew/LGC_regist.php】でＨＴＭＬタグの有効処理の上に
# addslashesの処理を行っておりますが、こちらの処理でもaddslashesをおこなっております。
# 二重に処理を行わないよう注意をしてください。
# （二重に処理をしますとaddslashesが無効になります。）
#=================================================================================
function html_tag($str){

	$str  = mb_convert_kana($str,"KV");//半角を全角に変換処理
	$str = strip_tags($str,"<span><p><a><b><i><strong><em><u><iframe><object><param><embed>");
	$str = utilLib::strRep($str ,7); // 前後の空白除去
	$str  = utilLib::strRep($str ,4); // stripslashes
	$str  = utilLib::strRep($str ,5); // addslashes

	return $str;
}
$layer_free = "Layer_free";//カラーボタンの付近にレイヤーを表示するレイヤー用

#=================================================================================
# 改行タグのHTML形式化　※通常のnl2brだとxhtml形式<br/>になってしまう為。
#=================================================================================

function nl2br2($str){

    return preg_replace( '/(\r\n)|\n|\r/', "<br>", $str );

}

#=================================================================================
# ファイル検索システム
#

# 管理画面でjpag,gif,pngの３種類のファイルを登録できるようになったため
# 昔の様にjpagのみとは行かなくなったので、こちらの関数でファイルを取得して
# その配列データでファイルの削除と表示をさせる。
#=================================================================================

#=================================================================================
# ファイル検索表示システム
#

# 検索に該当したファイルを表示する
# 登録ファイル名の基本は【RES_ID.拡張子】の組み合わせなので
# 検索方法は【path/RES_ID.".".*】【path/RES_ID."_"$i.".".*】などで
# 拡張子をワイルドカードに表示が基本になる
# 基本的に不要な登録ファイルは削除するようにして該当するファイルは一つにすること
# こちらの表示処理は一番最初に該当するファイルを表示させる。
#

# $path		ファイルが置いてある階層へのパス
# $con		表示対象のファイル条件
# $option	表示するのに必要な設定を記述（width="200" hegith="150" alt="img" style="margin:10px;" などimgタグに記述する内容）
# $size_lock

# 		1 = 検索で該当したファイルの縦横サイズをimgタグに出力
# 		2 = パスのみ表示
# 		デフォルト = サイズ固定なし
#

# 		１はN3などアンカーで、表示する時に画像読み込み中などの影響でアンカー位置がずれてしまうのを防ぐ
# 		サムネイル用など元の画像を小さくする場合にはフラグを立たせずに$optionで横幅を指定する
# 		２はライトボックスなどで画像のパスを指定しないといけない箇所にパスを置く為
#=================================================================================
function search_file_disp($path,$con,$option = "",$size_lock = null){

	$disp_image = "";//表示する変数の初期化（表示する画像が無い場合もある為）

	//階層と条件が入っているか確認
	if($path && $con){

		$s_data = glob($path.$con);//検索結果を受け取る

		//検索結果に該当するファイルがあった場合
		//if(count($s_data)){//php 5.3.3だとうまく判定してくれない
		if(file_exists($s_data[0]) && !empty($s_data[0])){
			if($size_lock == 2){//パスのみを表示
				$disp_image = $s_data[0];
			}elseif($size_lock == 1){//サイズ固定のフラグがたっている場合
				$size = getimagesize($s_data[0]);//画像サイズを取得
				$disp_image = "<img src=\"".$s_data[0]."?r=".rand()."\" width=\"".$size[0]."\" height=\"".$size[1]."\" ".$option.">";//検索に該当したファイル名を渡す。また、設定する記述も付け加える。
			}else{//固定なし
				$disp_image = "<img src=\"".$s_data[0]."?r=".rand()."\" ".$option.">";//検索に該当したファイル名を渡す。また、設定する記述も付け加える。
			}
		}
	}

	//データを返す
	return $disp_image;
}

#=================================================================================
# ファイル検索表示フラグシステム
#

# 検索に該当したファイルがあるかのチェックを行う
# 使用目的は管理画面での画像が登録しているかしていないかで分岐する
# 箇所の補助、返値はtrue（ファイル在り）とfalse（ファイルなし）で返答
#
# 例　登録している画像の削除ボタンの表示判定、複数画像があれば画像の切り替え判定
#

# $path		ファイルが置いてある階層へのパス
# $con		表示対象のファイル条件
#=================================================================================
function search_file_flg($path,$con){

	//階層と条件が入っているか確認
	if($path && $con){

		$s_data = glob($path.$con);//検索結果を受け取る

		//検索結果に該当するファイルがあった場合
		//if(count($s_data)){//php 5.3.3だとうまく判定してくれない
		if(file_exists($s_data[0]) && !empty($s_data[0])){
			return true;
		}
	}

	//該当ファイルが無かった場合
	return false;
}

#=================================================================================
# ファイル検索削除システム
#

# 検索に該当したファイルを削除する
# 登録ファイル名の基本は【RES_ID.拡張子】の組み合わせなので
# 検索方法は【path/RES_ID.".".*】【path/RES_ID."_"$i.".".*】などで
# 拡張子をワイルドカードに削除が基本になる
#

# また、W3の様に拡張子だけが違うファイルも削除される為
# W3では【path/RES_ID."_f.".*】【path/RES_ID."_"$i."_f.".*】など画像登録とは
# 別のファイル名形式にさせる必要がある。
#

# $path		ファイルが置いてある階層へのパス
# $con		削除対象のファイル条件
#=================================================================================
function search_file_del($path,$con){

	//階層と条件が入っているか確認
	if($path && $con){

		//検索に引っかかったファイルを削除する
		if(glob($path.$con)){//パスのデータがあるときのみ処理を行う
			foreach(glob($path.$con) as $filename){

				if(($filename != "") && file_exists($filename)){//ファイルが存在すれば削除処理を行う
					unlink($filename) or die("ファイルの削除に失敗しました。");
				}
			}
		}
	}
}
?>
