<?php
/*******************************************************************************
SiteWiN 10 20 30（MySQL版）
設定ファイルの定義	


*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("config.php");	// 共通設定情報
#=================================================================================
# ホームページURL
#=================================================================================
define('CP1_BO_HP_URL',"http://".$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'],"",dirname(dirname(__FILE__)))."/");

#=================================================================================
# 管理画面に共通して表示させる値
#=================================================================================
define('CP1_TITLE','ページ管理');	// CPx系（簡易CMS）のタイトル
define('CATE_TITLE','カテゴリー');	// CPx系（簡易CMS）のタイトル
define('CP1_TMP_TITLE','テンプレート管理');	// CPx系（簡易CMS）のタイトル

#=================================================================================
# 最大登録件数の指定
#=================================================================================
define('CP1_DBMAX_CNT',5);	// CPx系（商品紹介）の最大登録件数
define('CP1_TMP_DBMAX_CNT',5);	// CPx系（商品紹介）の最大登録件数

#=================================================================================
# 最大表示件数の指定
#=================================================================================
define('CP1_DISP_MAXROW',10);	// CPx系（商品紹介）の 1ページの最大表示件数
define('CP1_DISP_MAXROW_BACK',10);	// 管理画面での1ページの最大表示件数

#=================================================================================
# データベースでのテーブル名の指定
#=================================================================================
define('CP1_PAGE_LST','CP1_PAGE_LST');			//登録データ
define('CP1_BLOCK_LST','CP1_BLOCK_LST');		//ブロックデータ
define('CP1_CATEGORY_MST','CP1_CATEGORY_MST');		//グループ
define('CP1_PAGE_TMP_LST','CP1_PAGE_TMP_LST');	//ページテンプレート
define('CP1_VALUES_LST','CP1_VALUES_LST');		// ブロックの値データ

#=================================================================================
# ブロックフォルダの位置の指定
#=================================================================================
// 画像ファイルパス（管理画面のみで使用）
define('CP1_BLOCK_PATH','../../common/block/');
define('_COMMON_BLOCK_PATH','../common/block/');

#=================================================================================
# ブロックに入力できる値データの最大値
#=================================================================================
define('CP1_BLOCK_MAX_VALUE',100);
define('CP1_BLOCK_MAX_IMG',10);	// ブロックの画像最高登録数

#=================================================================================
# プレビュー用リンク
#=================================================================================
define('PREV_PATH',"../../CP1");

#=================================================================================
# 表示ライン列
#=================================================================================
define('LINE_MAXCOL',"2");

#=================================================================================
# 画像情報の指定
#=================================================================================
//グループで利用する画像数
define('CP1_IMG_CNT','1');

// 画像ファイルパス（管理画面のみで使用）
define('CP1_IMG_PATH','../../CP1/up_img/');

// 画像ファイルサイズ
define('CP1_IMGSIZE_SX',40);	// 管理画面サムネイル用
define('CP1_IMGSIZE_SY',30);	// 管理画面サムネイル用
define('CP1_IMGSIZE_MX1',240);	// アップロード画像幅（WhatsNew）
define('CP1_IMGSIZE_MY1',180);	// アップロード画像高（WhatsNew）
define('CP1_IMGSIZE_MX2',680);	// アップロード画像幅（WhatsNew）
define('CP1_IMGSIZE_MY2',105);	// アップロード画像高（WhatsNew）
$ox = array(CP1_IMGSIZE_MX1,CP1_IMGSIZE_MY1);

define('MAX_MB',2);	// ブロックの画像最高登録数

#=================================================================================
# 
#=================================================================================
function getEntryBlockName($path){
	return getFileLst($path,"block");
}

function getBlockName($id){
	return "block".$id.".php";
}

function getImageCode($file_name,$xml = null,$param = array()){

    // ファイル名が無い場合終了
    if(!$file_name) return "";

	$file_path = "./up_img/".$file_name."";
	$file_path2 = "./up_img/".$file_name."";

	if(!file_exists($file_path) ){
		return "";
	// ある場合は[BLANK:F*]の削除。
	}else{
		// パラメータ生成
		$param_xml = simplexml_load_string($xml);
		$param_str = "";
		
		// ====================
		// クラス設定
		// ====================
		$class = " class=\"".$param['class'];
		
		// ====================
		// サイズ設定
		// ====================
		$param_str .= $param['height'] ? " height=\"".$param['height']."\"" : "";
		$param_str .= $param['width'] ? " width=\"".$param['width']."\"" : "";
		
		// ====================
		// その他イレギュラー設定
		// ====================
		$param_str .= $param['etc'] ?  $param['etc'] : "";
			

		if($param_xml){
			// ====================
			// ボーダー設定
			// ====================
			if($param_xml->border == 1){
				$class .= " drop01";
			}
		
			// ====================
			// リンク設定
			// ====================
			if($param_xml->link_target > 1){
			
				$target="";$lb_head="";$lb_foot="";$rel="";
				if($param_xml->link_target == 2){ $target = "target=\"_self\""; }
				else if($param_xml->link_target == 3){ $target = "target=\"_blank\"";}
				//else if($param_xml->link_target == 4){ $lb_head = "<div class=\"lightbox_ph\">";$lb_foot = "</div>";}
				else if($param_xml->link_target == 4){ $rel = "rel=\"lightbox\"";}
			
				$link = $param_xml->link_url;
				if( $rel){
					$link = $file_path2;
				}

				
				$code_data_h = "{$lb_head}<a href=\"".$link."\" {$target} {$rel}>";
				$code_data_f = "</a>{$lb_foot}";
			}else{
				$code_data_h = "";
				$code_data_f = "";
			}
		
		}else{
			$code_data_h = "";
			$code_data_f = "";
		}

		$param_str .= $class . "\"";

		return $code_data_h."<img src=\"{$file_path}\"{$param_str}>".$code_data_f;

	}
	
}


function getFileLst($path,$file){
	$result = array();
	$open_dir = $path;
	    if ($dh = opendir($open_dir)) {
	        while (($file_name = readdir($dh)) !== false) {
	            if(!is_dir($file_name)){
					if(preg_match('/^'.$file.'[0-9]+\.php$/',$file_name )) $result[] =  $file_name;
	            }
	        }
	    }
	closedir($dh);
	return $result;

}

#=================================================================================
# ブロック登録用のサニタイズ関数
#=================================================================================
function sntz($str){

	$str  = mb_convert_kana($str,"KV");//半角を全角に変換処理
	$str = utilLib::strRep($str ,8); // タグの除去
	$str = utilLib::strRep($str ,7); // 前後の空白除去
	$str  = utilLib::strRep($str ,4); // stripslashes
	$str  = utilLib::strRep($str ,5); // addslashes

	return $str;
}

?>