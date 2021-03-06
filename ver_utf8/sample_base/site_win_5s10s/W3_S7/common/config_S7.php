<?php
/*******************************************************************************
SiteWiN 10 20 30（MySQL版）
設定ファイルの定義

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
require_once("config.php");	// 共通設定情報

#=================================================================================
# 管理画面に共通して表示させる値
#=================================================================================
define('TITLE','商品紹介の更新');	// Sx系（商品紹介）のタイトル
define('CATE_TITLE','商品紹介カテゴリーの更新');	// Sx系（商品紹介）カテゴリーのタイトル

#=================================================================================
# 最大登録件数の指定
# カテゴリーは無制限で登録が可能
#=================================================================================
define('DBMAX_CNT',100);	// Sx系（商品紹介）の最大登録件数
define('W3_DBMAX_CNT',15);	// w系（商品紹介）の最大登録件数

#=================================================================================
# 最大表示件数の指定
#=================================================================================
define('DISP_MAXROW',10);	// Sx系（商品紹介）の 1ページの最大表示件数

define('DISP_MAXROW_BACK',10);	// 管理画面での1ページの最大表示件数

// 1行の列数※クロス表示でない場合は１を設定
define('LINE_MAXCOL',3);

#=================================================================================
# データベースでのテーブル名の指定
#=================================================================================
define('S7_PRODUCT_LST','W3_S7_2_PRODUCT_LST');	//商品データ
define('S7_CATEGORY_MST','W3_S7_CATEGORY_MST');	//カテゴリー

#=================================================================================
# 表画面のフォルダの指定(管理画面のみで使用・プレビュー用)
#=================================================================================
define('PREV_PATH','../../S7/');

#=================================================================================
# 画像情報の指定
#=================================================================================
// 画像ファイルパス（管理画面のみで使用）
define('IMG_PATH','../../S7/up_img/');
// 画像枚数
define('IMG_CNT',4);
// アップロードサイズ上限(MB)
define('MAX_MB',2);

// 画像ファイルサイズ
define('IMGSIZE_SX',40);	// 管理画面サムネイル用
define('IMGSIZE_SY',30);	// 管理画面サムネイル用
define('IMGSIZE_MX1',100);	// アップロード画像幅（商品紹介／高自動算出）
define('IMGSIZE_MY1',100);	// アップロード画像高（商品紹介／幅自動算出）
define('IMGSIZE_MX2',200);	// アップロード画像幅（商品紹介／高自動算出）
define('IMGSIZE_MY2',200);	// アップロード画像高（商品紹介／幅自動算出）

//定数を配列に格納しておく（back_office/s7_product/LGC_regist.phpで使用）
$ox = array(IMGSIZE_MX1,IMGSIZE_MX2,IMGSIZE_MX2,IMGSIZE_MX2);
$oy = array(IMGSIZE_MY1,IMGSIZE_MY2,IMGSIZE_MY2,IMGSIZE_MY2);

#=================================================================================
# ファイルのアップロード制限
# 大きいファイルのアップロードを行うとサーバー設定されている制限でアップロードが
# 行えない、または、ファイルの置ける容量限界を超えてしまいファイルのアップロードを
# 行えない場合がございます。
#=================================================================================
define('LIMIT_SIZE',3);	// MB単位で記入をしてください。

#=================================================================================
# 資料ファイルの詳細データを出力
# プレビューがあるので詳細データをその都度調べるは手間になる為
#
# $file_path	ファイルの階層
# $file_name	ファイル名
# $uploaded_file	アップロードしたファイル
# $limit_size	ファイルアップロードの限界サイズ
#=================================================================================
function file_detail_data($file_path="",$file_name="",$uploaded_file="",$limit_size=""){

	//$file_detail = array();//初期化

	//プレビューファイルがアップロードしている場合、そして保存する為の階層のパスが必要
		if(is_uploaded_file($uploaded_file['tmp_name']) && $file_path){
			//ファイルサイズが超えていないかチェック、超えていた場合は何もせずに空っぽのデータを返す。
			if($uploaded_file['size'] < ($limit_size * 1024 *1024)){

				// 拡張子を取得
					$pathinfo = pathinfo($uploaded_file['name']);
					$file_detail['EXTENTION'] = strtolower($pathinfo['extension']);

				//ファイルのアップロード処理とファイルパスを出力
					if(!copy($uploaded_file['tmp_name'],$file_path."prev_pdf.".$file_detail['EXTENTION'])){
						exit("アップロード処理に失敗しました。");
					}else{
						//本番アップ時に権限が変わりパーミッションの変更ができない場合、
						//処理は失敗したままで次の処理へ行かせる。
						@chmod($file_path."prev_pdf.".$file_detail['EXTENTION'], 0666);
					}

				//ファイルパス
					$file_detail['file_path'] = $file_path."prev_pdf.".$file_detail['EXTENTION'];

				//ファイルサイズ
					$file_detail['SIZE'] = $uploaded_file['size'];

			}
	//ファイルのパスとファイル名が無ければチェック処理
		}elseif($file_path && $file_name){
			//ファイルが存在しているか
			if(file_exists($file_path.$file_name)){

				// 拡張子を取得
					$pathinfo = pathinfo($file_path.$file_name);
					$file_detail['EXTENTION'] = strtolower($pathinfo['extension']);

				//ファイルサイズ
					$file_detail['SIZE'] = filesize($file_path.$file_name);

				//ファイルのパス
					$file_detail['file_path'] = $file_path.$file_name;

			}

		}

	return $file_detail;//結果を返す。
}

?>