<?php
/*******************************************************************************
共通設定ファイル

	■各項目の説明(設定を要するものは※印)

		【1.マルチバイト関数用に言語と文字コードを指定する】

			プログラムの絡むページのエンコードをUTF-8に設定
			この設定ファイルをインクルードしたファイルに適用される

		※【2.DB接続情報】
			データベースの接続情報に必要な各パラメータ値を定数化
			確定数値を設置サイトのDB情報に変更する

			※設定する定数の値
				DB_SERVER	ログインサーバ名
				DB_NAME		ログインデータベース名
				DB_USER		ログインユーザ名
				DB_PASS		ログインパスワード
				DSN			DSN文字列(PEAR用)

		※【3.DBテーブル情報】
			データベースに設置、使用する各テーブル名を定数化
			テンプレートの使い回しを考慮している。
			作成したテーブル名を対応する各定数に格納

			※設定する定数の値
				CATEGORY_MST			カテゴリ情報
				CUSTOMER_LST			顧客情報
				PURCHASE_LST			購入情報
				PURCHASE_ITEM_DATA		購入商品詳細情報
				PRODUCT_LST				製品情報
				PRODUCT_PROPERTY_DATA	製品詳細(カラー/サイズ/在庫)情報
				COLOR_MST				カラー情報
				SIZE_MST				サイズ情報
				CONFIG_MST				管理者情報

		※【4.メール設定】☆要注意☆
			データベースよりデータを取得して各定数にセットしているので
			このファイルでの修正の必要は無し。

			ただしメールフォームの受付メールアドレスはデータベース管理するので
			メールフォームプログラムにこのファイル(common/INI_config.php)をインクルードし
			メール送信先にWEBMST_CONTACT_MAILをセットすることが必須

			WEBMST_CONTACT_MAIL		問合せ用メールアドレス
									各自設置したメールフォームのメール送信処理にて
									mb_send_mail()関数のパラメータにセットすること！

		※【5.代引き手数料設定】

			詳細は下記に記述

		※【6.送料設定】

			詳細は下記に記述

		※【7.コンビニ決済設定】

			コンビニ決済を利用する場合
			下記詳細を参照して設定
			(利用が無い場合はこのままでok)

		※【8.決済サイト（J-payment）で必要な情報の設定】

			クレジット利用時
			下記詳細を参照して店番等を設定
		※【9.HTMLのタイトルタグに共通して表示させる値】

			カートページと管理画面の<title></title>に挟む文字列を設定(タイトルを設定)

		※【10.商品登録数・商品一ページ表示件数】
			契約内容による商品最大登録件数と
			デザインにより商品一覧ページの表示件数を設定

			※設定する定数の値
				PRODUCT_MAX_NUM			商品最大登録件数
				PAGE_MAX				商品一覧ページでの1ページ表示件数

		※【11.商品画像の設定】
			商品画像をバックオフィスよりアップロードする際の各設定

			※設定する定数の値
				PRODUCT_IMG_NUM			登録画像数
				PRODUCT_IMG_FILEPATH	商品画像ファイルのパス

				# 商品画像サイズ設定
				IMG_SIZE_LX		拡大用横サイズ
				IMG_SIZE_LY		拡大用縦サイズ
				IMG_SIZE_MX		詳細ページ用横サイズ
				IMG_SIZE_MY		詳細ページ用縦サイズ
				IMG_SIZE_SX		一覧ページ用横サイズ
				IMG_SIZE_SY		一覧ページ用縦サイズ

		※【12.DB登録データの文字列制限】
			DB登録時の登録データの文字数制限

				PARTNO_STR_MAX			// 商品番号文字列長(半角文字数をカウント)
				PRODUCTNAME_STR_MAX		// 商品名文字列長(半角文字数をカウント)
				STOCK_STR_MAX			// 在庫数文字列長(半角文字数をカウント)
				DETAILS_STR_MAX			// 商品説明文字列長(半角文字数をカウント)
				SELLINGPRICE_STR_MAX	// 単価文字列長(半角文字数をカウント)

		※【13.バックオフィスのIDとパスワード】
			管理画面へ入室するための管理ID、管理パスワードの設定

				BO_ID	管理ID
				BO_PW	管理PW

		【14.頻繁に行う簡易処理のファンクション化（匿名関数）】
			IDやパスワードなど、ランダムな識別番号を作成したい時に利用する匿名関数

			$makeID		// ID生成用関数
			$makePass	// パスワード生成用

*******************************************************************************/
// 設定ファイル＆共通ライブラリの読み込み
require_once("dbOpe.php");			// ＤＢ操作クラスライブラリ
require_once("util_lib.php");		// 汎用処理クラスライブラリ

#=================================================================================
# 1.マルチバイト関数用に言語と文字コードを指定する（文字列置換、メール送信等で必須）
#=================================================================================
mb_language("Japanese");
mb_internal_encoding("UTF-8");

//エラーメッセージの表示の時エンコードがsjisで文字化けをするのを回避
	header("Content-Type: text/html; charset=UTF-8");
	header("Content-Language: ja");

//date_default_timezone_set('Asia/Tokyo');//php 5.3.0から時間設定をしないと警告が出てしまうためここで設定をする。
#=================================================================================
# 2.ＤＢ接続の情報（定数化）
#=================================================================================
define('DB_SERVER','localhost');
define('DB_NAME','shopwin_sample01');
define('DB_USER','shopwin_sample01');
define('DB_PASS','zeekdemo');
define('DSN',"mysql://shopwin_sample01:zeekdemo@localhost/shopwin_sample01");	// PEAR用

#=================================================================================
# 3.ＤＢテーブル情報（定数化）
#=================================================================================
/* 管理者情報 */
define('CONFIG_MST','CONFIG_MST');

#=================================================================================
# 運用サポート用の情報（定数化）
#=================================================================================
// 運用サポートURL
define('UW_INFO_URL','http://mx03.zeeksdg.net/zeeksdg/navi_info2/');

// ユーザーズウェブ上の顧客コード
define('UW_CUSTOMER_CODE','');

#=================================================================================
# メールの設定（Webマスター）
# MAIL_CONFIG
# ショッピングなどでメールアドレス、振込先など各種管理者設定をする場合は 1を設定
# お問い合わせフォームのみメールアドレスを設定する場合は 2 を設定する
#=================================================================================
// ショッピングプログラムかお問い合わせのみかの設定
define('MAIL_CONFIG',1);

// 管理画面より設定する場合はデータベース取得用ファイルをインクルードする
require_once("LGC_confDB.php");       // 設定情報取得用DBファイル

define('WEBMST_SHOP_MAIL', $fetchConfig[0]["EMAIL"]);			// ショッピング用メアド
define('WEBMST_CONTACT_MAIL', $fetchConfig[0]["EMAIL2"]);		// お問い合わせ用メアド
define('WEBMST_CONTACT_MAIL_CONTENT', $fetchConfig[0]["CONTENT"]);		// お問い合わせ用自動返信メール
define('WEBMST_NAME', $fetchConfig[0]["NAME"]);	// メアドに表示する名前
define('SUBJECT_CLIENT_NAME', $fetchConfig[0]["NAME"]);			// 件名に表示される会社名

/* メール定型文書 */
define('COMPANY_INFO', $fetchConfig[0]["COMPANY_INFO"]);	// メール表示用会社情報
define('BANK_INFO', $fetchConfig[0]["BANK_INFO"]);			// 銀行口座振込み通知用
define('POST_INFO', $fetchConfig[0]["POST_INFO"]);			// 銀行口座振込み通知用

#=================================================================================
# HTMLのタイトルタグに共通して表示させる値
#=================================================================================
define('BO_TITLE',$fetchConfig[0]["BO_TITLE"]);		// バックオフィス用タイトル

//define('FOOTER_TITLE','Copyright (C)  All rights reserved. ');
define('FOOTER_TITLE','');

#=================================================================================
# 頻繁に行う簡易処理のファンクション化（匿名関数）
#=================================================================================
// UNIX時間＋マイクロ秒によるID生成
$makeID = create_function('','return date("U")."-".sprintf("%06d",(microtime() * 1000000));');

// デジタルチェック決済時
$makeID2 = create_function('','return date("U").sprintf("%06d",(microtime() * 1000000));');

// パスワード作成
$makePass = create_function('','$pass = crypt(mt_rand(0,99999999),"CP");return preg_replace("/[^a-zA-Z0-9]/","",$pass);');

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

	return $str;
}

#=================================================================================
# 管理者情報テーブルよりメールアドレスを取得するファンクション
#	メソッド名：getInitData("カラム名")
#=================================================================================
function getInitData($colum = ""){

	if(!$colum)$colum = "EMAIL2";

	$sql = "SELECT {$colum} FROM CONFIG_MST WHERE(CONFIG_ID = '1')";
	$con = mysql_connect(DB_SERVER,DB_USER,DB_PASS);
	mysql_select_db(DB_NAME,$con);

	if($query = mysql_query($sql)):

		$fetch = mysql_result($query,0,$colum);
		mysql_free_result($query);
		mysql_close($con);

		return $fetch;

	endif;

}

#=================================================================================
# 管理情報テーブルよりIPリストを取得するファンクション
#	メソッド名：getPermitIPList("ID名")
#=================================================================================
function getPermitIPList($bo_id){

	if(!$bo_id || empty($bo_id)) return false;

	$sql = "SELECT PERMIT_IP_LST FROM CONFIG_MST WHERE(BO_ID = '".utilLib::strRep($bo_id,5)."')";
	$con = mysql_connect(DB_SERVER,DB_USER,DB_PASS);
	mysql_select_db(DB_NAME,$con);

	if($query = mysql_query($sql)):

		$fetch = mysql_result($query,0,"PERMIT_IP_LST");
		mysql_free_result($query);
		mysql_close($con);
	endif;

	return explode(",",$fetch);

}

#=================================================================================
# 転送処理を行うファンクション（引数：対象URL、戻り値：なし）
#=================================================================================
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
# データ登録の【LGC_regist.php】ファイルでＨＴＭＬタグの有効処理の上に
# addslashesの処理を行っておりますが、こちらの処理でもaddslashesをおこなっております。
# 二重に処理を行わないよう注意をしてください。
# （二重に処理をしますとaddslashesが無効になります。）
#=================================================================================
$layer_free = "Layer_free";//カラーパレットのレイヤー名を指定

function html_tag($str){

	$str  = mb_convert_kana($str,"KV");//半角を全角に変換処理
	$str = strip_tags($str,"<span><p><a><b><i><strong><em><u><iframe><object><param><embed>");//許可していないタグは削除
	$str = utilLib::strRep($str ,7); // 前後の空白除去
	$str  = utilLib::strRep($str ,4); // stripslashes
	$str  = utilLib::strRep($str ,5); // addslashes

	return $str;
}

#=================================================================================
# 改行タグのHTML形式化　※通常のnl2brだとxhtml形式<br/>になってしまう為。
#=================================================================================

function nl2br2($str){

    return preg_replace( '/\n|\r|(\r\n)/', "<br>", $str );

}

#=================================================================================
# ファイル検索システム
#
# 管理画面でjpag,gif,pngの３種類のファイルを登録できるようになったので
# 昔の様にjpagのみとは行かなくなったのでこちらの関数でファイルを取得して
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

	//階層と条件が入っているか確認（ただし*のみの場合は処理しない全ファイルが削除される為）
	if($path && $con && ($con != "*")){

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

#=================================================================================
# 消費税の設定ファイルを読み込ませる
#=================================================================================
require_once("INI_tax.php");		// 消費税の処理

#=================================================================================
# メール本文で自動的に改行を行う
# 
# 「RFC5322」の「行の長さの制限」
# Emailにおいて1行（改行コードまで）の長さは、半角998文字を越えてはいけない
# 
# この半角998文字を超えて入力をしてしまうユーザーを何とかするプログラム
# wordwrap を日本語用に改良した物、ただしUTF-8用に設定されている
# 他のエンコードの場合$cutの箇所を調整する必要がある可能性がある。
# 
# 
# 
# 1行あたりの制限文字数（日本語を取り扱う前提） 39*2 = 78 Byte　でデフォルト39文字に設定
# （URLを入力とか長い物を入力は意識していない文字数を設定している）
# 
# ここから引用
# http://ameblo.jp/itboy/entry-10018306820.html
# 
# $body		メール本文を設定
# $part_length	カットする文字数を設定最大450にしておく
#=================================================================================
function mbody_auto_lf($body = "",$part_length=39){
	
	///////////////////////////////////////////////////
	// 改行(一行)ごとにデータを取得する 
		$line		 = mb_split("\n", $body);//改行コードLFを目印に区切る
		$body_tmp	 = NULL;//一時保存のメール本文
		$line_length	 = 0;//
		
		///////////////////////////////////
		//一行ごと調べていく
		for ($i = 0; $i < count($line); $i++){
			
			$line_length	 = strlen($line[$i]);//文字数を取得（全角半角関係なしに）
			$one_line	 = NULL;// ASCII文字のみであれば、最大制限文字数の2倍の文字数までを許可する
			
			if ($line_length > ($part_length * 2)){//バイト数でカットする文字数を超えていた場合
				
				$mb_length = mb_strlen($line[$i]);// 文字数を取得する。
				
				////////////////////////////////////////////////
				//カットする文字数で割り切れる場合
				if (($mb_length % $part_length) == 0) {
					$loop_cnt = $mb_length / $part_length;//カット数を取得
					
				////////////////////////////////////////////////
				//割り切れない場合
				} else {
					$loop_cnt = ceil(mb_strlen($line[$i]) / $part_length);//カット数を取得
				}
				
				$start_num = 0;
				
				////////////////////////////////////////////////
				// 1行ごとに制限文字数内で分解して改行コードを挿入する
				for ($j = 1; $j <= $loop_cnt; $j++) {
					
					////////////////////////////////////////////////
					// 制限文字数単位で改行コード挿入
					$one_line .= mb_substr($line[$i], $start_num, $part_length) . "\n";
					$start_num = $part_length * $j;
				}
				
			} else {//超えていなければそのまま渡す。
				$one_line = $line[$i] . "\n";
			}
				$body_tmp .= $one_line;
		}
		
	return $body_tmp;//結果を返す。
}

#=================================================================================
# 検索キーワードで入力データを全て全角に変換する処理
# 
# 現在自社のデータベースの仕様ではMYSQLのLIKEでなど検索した場合
# 全角同士の大文字、小文字は区別せずにヒット
# 半角同士の大文字、小文字は区別せずにヒット
# ができるが、全角と半角では区別されてしまう為
# 検索の入力によっては検索に出てこない商品が出てしまうので
# 検索キーワードは全て全角に統一する
# 
# この関数を使用するタイミングはデータベースに登録と表ページで
# キーワード検索で入力した文字に処理して、そのデータで検索させる
# 
#=================================================================================

function key_word_con( $str="" ) {
	
	//タグの除去
		$str = strip_tags($str);
	
	//前後の空白除去
		$str = utilLib::strRep($str ,7); 
		
	// stripslashes
		$str  = utilLib::strRep($str ,4); 
	
	//半角カタカナを全角カタカナに変換
		$str = mb_convert_kana($str,"KV");
	
	 // 半角英数字、カタカナを全角に変換
		 $str = mb_convert_kana( $str , "AK" , "UTF-8" );
	
	 // 小文字英字を全角英字に変換
		 $str = mb_convert_case( $str , MB_CASE_UPPER , "UTF-8"  );
	
	//エスケープ処理
		$str = addslashes($str);
	
	 return $str;
}

#=================================================================================
# サイトのトップページURLを生成する
# 
# 用途はショッピングのパスワード変更へのURL,静的化ページのbaseタグの設定
# など、誘導する為のパス生成の補助
# 
# $type 出力タイプ　1は今現在のSSLページか判断してトップページのURLを出力　0はトップページまでのディレクトリ階層を返す。（先頭部分をhttpsを付けたり加工目的）
# $ht $typeが2の時に頭に付けれるパスの設定
#=================================================================================
	function get_top_url($type=0,$ht=""){
		$base_path = dirname(__FILE__)."/";//今現在の階層を取得する
		$base_path = str_replace($_SERVER['DOCUMENT_ROOT'],"",$base_path);//ドキュメントルート ディレクトリのパスを除去する
		
		///////////////////////////////////////////////////////
		//A1お問い合わせ用,ショッピングのパスワード変更用（SSLとかの判断が必要なもの）
			if($type==1){
				$base_path = $_SERVER["HTTP_HOST"].$base_path;//頭にドメイン名をくっつける
				$url = empty($_SERVER["HTTPS"]) ? "http://".$base_path : "https://".$base_path;//SSLか判断してhttpをくっつける
				
		///////////////////////////////////////////////////////
		//PHP側で使うパスを出力　ショッピングのメールテンプレート読み込みなど（スマホとかだと階層が違ってくるため）
			}elseif($type==2){
				$url = $_SERVER['DOCUMENT_ROOT'];
				
		///////////////////////////////////////////////////////
		//加工用用　http,httpsを固定にする必要がある物など
			}else{
				$url = $base_path;
			}
		
		return $url;//結果を返す。
	}

?>
