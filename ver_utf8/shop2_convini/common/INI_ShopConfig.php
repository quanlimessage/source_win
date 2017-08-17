<?php
/*******************************************************************************
ショップ用設定ファイル
	アパレル対応ショッピングカート(カテゴリ)
		設定情報管理と匿名関数などの定義
			※データの変動がないものは定数化しておく（変動するものは変数で定義）

	■各項目の説明(設定を要するものは※印)

		※【1.DBテーブル情報】
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

		※【2.メール設定】☆要注意☆
			データベースよりデータを取得して各定数にセットしているので
			このファイルでの修正の必要は無し。

			ただしメールフォームの受付メールアドレスはデータベース管理するので
			メールフォームプログラムにこのファイル(common/INI_config.php)をインクルードし
			メール送信先にWEBMST_CONTACT_MAILをセットすることが必須

			WEBMST_CONTACT_MAIL		問合せ用メールアドレス
									各自設置したメールフォームのメール送信処理にて
									mb_send_mail()関数のパラメータにセットすること！

		※【3.代引き手数料設定】

			詳細は下記に記述

		※【4.送料設定】

			詳細は下記に記述

		※【5.コンビニ決済設定】

			コンビニ決済を利用する場合
			下記詳細を参照して設定
			(利用が無い場合はこのままでok)

		※【6.決済サイト（ZEUS）で必要な情報の設定】

			クレジット利用時
			下記詳細を参照して店番等を設定
		※【7.HTMLのタイトルタグに共通して表示させる値】

			カートページと管理画面の<title></title>に挟む文字列を設定(タイトルを設定)

		※【8.商品登録数・商品一ページ表示件数】
			契約内容による商品最大登録件数と
			デザインにより商品一覧ページの表示件数を設定

			※設定する定数の値
				PRODUCT_MAX_NUM			商品最大登録件数
				PAGE_MAX				商品一覧ページでの1ページ表示件数

		※【9.商品画像の設定】
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

		※【10.DB登録データの文字列制限】
			DB登録時の登録データの文字数制限

				PARTNO_STR_MAX			// 商品番号文字列長(半角文字数をカウント)
				PRODUCTNAME_STR_MAX		// 商品名文字列長(半角文字数をカウント)
				STOCK_STR_MAX			// 在庫数文字列長(半角文字数をカウント)
				DETAILS_STR_MAX			// 商品説明文字列長(半角文字数をカウント)
				SELLINGPRICE_STR_MAX	// 単価文字列長(半角文字数をカウント)

*******************************************************************************/
// 設定ファイル＆共通ライブラリの読み込み
require_once("LGC_confDB.php"); // 設定ファイル
define('PW_LIMIT_STR',30);//パスワードの最大文字数

#=================================================================================
# 1.ＤＢテーブル情報（定数化）
#=================================================================================
/* カテゴリ情報 */
define('CATEGORY_MST','CATEGORY_MST');

/* 顧客情報 */
define('CUSTOMER_LST','CUSTOMER_LST');

/* 顧客情報(仮) */
define('PRE_CUSTOMER_LST','PRE_CUSTOMER_LST');

/* 購入情報 */
define('PURCHASE_LST','PURCHASE_LST');

/* 購入商品詳細情報 */
define('PURCHASE_ITEM_DATA','PURCHASE_ITEM_DATA');

/* 製品情報 */
define('PRODUCT_LST','PRODUCT_LST');

/* 製品詳細(カラー/サイズ/在庫)情報 */
define('PRODUCT_PROPERTY_DATA','PRODUCT_PROPERTY_DATA');

/* カラー情報 */
define('COLOR_MST','COLOR_MST');

/* サイズ情報 */
define('SIZE_MST','SIZE_MST');

/* 管理者情報 */
define('CONFIG_MST','CONFIG_MST');

#=================================================================================
# 3.代引き手数料設定
#=================================================================================
/****【代引き手数料発生タイプの設定】*********************************************/

define('DAIBIKI_COND_TYPE','1');

/*
	 0：手数料無し

	 1：手数料一律
			DAIBIKI_CONDの設定額が手数料に

	 2：商品合計額が条件額(DAIBIKI_FREE)以上なら送料無料
			商品合計額がDAIBIKI_FREEに定義した額以上なら無料

	 3：都道府県毎に変動(条件額の影響なし)
			INI_pref_list.phpの$shipping_list["daibiki1"]に手数料設定

	 4：都道府県毎に変動(条件額の影響あり)
			INI_pref_list.phpの$shipping_list["daibiki1"]と$shipping_list["daibiki2"]に各手数料設定
				$shipping_list["daibiki1"]：通常
				$shipping_list["daibiki2"]：購入合計額がDAIBIKI_FREE定義額以上(手数料割引)

				※条件額以上無料にする場合はINI_pref_list.phpの$shipping_list["daibiki2"]を「0」に設定

	 5：オリジナルに送料条件作成(regist/LGC_setAmount.phpファイル内switch()文にて)

*****【一律送料・条件額の設定(都道府県毎はINI_config.phpの$shipping_list配列で設定)】***************/

define('DAIBIKI_COND','315');	// 一律代引き手数料
define('DAIBIKI_FREE','10000');	// 代引きの条件額

// 30万円の金額制限
define('DAIBIKI_LIMIT_FLG','1'); // （制限あり:1、制限なし:0)

#=================================================================================
# 4.送料設定
#=================================================================================

/****【送料発生タイプの設定】********************************************************************/

define('SHIPPING_COND_TYPE','2');

/*
	 0：送料無し

	 1：送料一律

	 2：商品合計額が条件額(SHIPPING_FREE)以上なら送料無料
			商品合計額がSHIPPING_FREEに定義した額以上なら無料

	 3：都道府県毎に変動(購入合計額の影響なし)
			INI_pref_list.phpの$shipping_list["shipping1"]に各送料設定

	 4：都道府県毎に変動(購入合計額の影響あり)
			INI_pref_list.phpの$shipping_list["shipping1"]と$shipping_list["shipping2"]に各送料設定
				$shipping_list["shipping1"]：通常
				$shipping_list["shipping2"]：購入合計額がSHIPPING_FREE定義額以上(送料割引)

				※条件額以上無料にする場合はINI_pref_list.phpの$shipping_list["shipping2"]を「0」に設定

	 5：オリジナルに送料条件作成(regist/LGC_setAmount.phpファイル内switch()文にて)

*****【一律送料・条件額の設定(都道府県毎はINI_config.phpの$shipping_list配列で設定)】***************/

define('SHIPPING_COND','420');	// 送料設定
define('SHIPPING_FREE','10500');	// 条件額

#=================================================================================
# 5.コンビニ決済設定 【決済サイト（ZEUS）】決済サイト（ZEUS）
#=================================================================================
define('CAID',30000);//コンビニ決済用

//コンビニ決済のテスト
//◆運用テスト
//・システム設定完了後、システム接続の完了を確認するため、運用テストを実施してください。
//・運用テストは土・日・祝祭日を除く10:00～18:00の間に実施してください。（きっかり18時で終わりと言う訳ではない）

//◆運用テスト手順
//　ゼウスのコンビニ決済で　支払うコンビニの選択→ユーザー情報入力（ここでお客様名欄にて「お客様名」＋「_（アンダーバー）」＋「テストID」と入力）→確認画面→テスト画面　の流れになる
//
//・テスト決済では、ゼウスコンビニ決済ページのお客様名欄にて「お客様名」＋「_（アンダーバー）」＋「テストID」と入力ください。
//　※テストＩＤは当社売上管理画面「テストカード番号管理」より確認いただけます。
//・コンビニ決済お申し込みページに移動しましたら、必要な情報を入力します。
//・「お申し込み内容確認」ボタンを押すと確認画面が表示されますので、内容を確認の上「お申し込み」ボタンを押してください。
//・お申し込みテスト完了ページが表示されますので、「売上確定」ボタンまたは「入金取消」を押しテストを実行してください。
//・テスト完了画面が表示されれば完了です。

#=================================================================================
# 6.クレジット決済　【決済サイト（ZEUS）】で必要な情報の設定
#
#=================================================================================
// 店舗番号
define('AID',30000);

// 決済の種類を指定。AUTH（仮売上）／ CAPTURE(仮売/実売同時)
//	※殆どのお客様は“CAPTURE”を使ってるとか・・・
define('JB_TYPE','CAPTURE');

#=================================================================================
# 7.HTMLのタイトルタグに共通して表示させる値
#=================================================================================
define('SHOPPING_TITLE',$fetchConfig[0]["SHOPPING_TITLE"]);	// ショッピングページ用

// 商品最大登録件数
define('PRODUCT_MAX_NUM',100);

		/********************************************************
		商品登録制御用フラグ(定数PRODUCT_ENTRY_FLG)の自動セット

				最大登録許可数と商品登録数を比較
				制限数に達していれば、フラグをたてる
				(PRODUCT_ENTRY_FLGに1をセット)
					※設定の必要は無し
		*********************************************************/
	$pro_num = count($fetchPro);

		if($pro_num >= PRODUCT_MAX_NUM):
			define('PRODUCT_ENTRY_FLG',1);
		else:
			define('PRODUCT_ENTRY_FLG',0);
		endif;

// 商品一覧ページ表示件数設定
define('SHOP_MAXROW',6);

// 1行の列数※クロス表示でない場合は１を設定
define('LINE_MAXCOL',2);

#=================================================================================
# 表画面のフォルダの指定(管理画面のみで使用・プレビュー用)
#=================================================================================
define('PREV_PATH','../../shopping/');

#=================================================================================
# 9.商品画像の設定
#=================================================================================
// 登録画像数(一覧用サムネイル画像をのぞいた数)
define('PRODUCT_IMG_NUM',3);

// 商品画像ファイルのパス（※本番時に要パス変更！！）
define('PRODUCT_IMG_FILEPATH','../../shopping/product_img/');

// 商品画像サイズ設定
define('IMG_SIZE_LX', '360');	# 拡大用横サイズ
define('IMG_SIZE_LY', '270');	# 拡大用縦サイズ
define('IMG_SIZE_SX', '200');	# 一覧ページ用横サイズ
define('IMG_SIZE_SY', '150');	# 一覧ページ用縦サイズ

// 管理画面一覧表示用画像サイズ
define('IMG_LIST_X','40'); # 横
define('IMG_LIST_Y','30');	# 縦

#=================================================================================
# 10.DBへの登録文字列制限について
#		以下の定数で最大登録可能文字数(半角で)の上限を設定
#=================================================================================
/* 商品データ */
define('PARTNO_STR_MAX', 60);			// 商品番号文字列長(半角文字数をカウント)
define('PRODUCTNAME_STR_MAX', 200);		// 商品名＆素材文、文字列長(半角文字数をカウント)
define('STOCK_STR_MAX', 30);			// 在庫数文字列長(半角文字数をカウント)
define('DETAILS_STR_MAX', 10000);			// 商品説明文字列長(半角文字数をカウント)
define('SELLINGPRICE_STR_MAX', 50);		// 単価文字列長(半角文字数をカウント)

/* カラーデータ */
define('COLOR_STR_MAX', 60);			// カラー文字列長(半角文字数をカウント)

/* サイズデータ */
define('SIZE_STR_MAX', 60);			// サイズ文字列長(半角文字数をカウント)

#=================================================================================
# 11.カラー、サイズと在庫数の件数の設定
#=================================================================================
//define('CS_AMOUNT', 20);

#=================================================================================
# 12.管理画面の受注情報、顧客情報の表示設定
#=================================================================================

/* 受注情報ページ表示件数設定 */
define('SALES_MAXROW',50);

/* 顧客情報ページ表示件数設定 */
define('CUSTOMER_MAXROW',50);

#=================================================================================
# 12.オススメ情報表示（S15）の設定
#=================================================================================
define('RECOMMEND_DISPLAY_FLG',1); // （表示あり:1、表示なし:0)

// お勧め商品最大表示数
define('RECOMMEND_DBMAX_CNT',5);

#=================================================================================
# 13.ショップライトの設定
# この在庫数を設定する、ライト案件では在庫は無いが汎用性を持たせる為
# 在庫の数値を設ける。一回の購入で在庫数を超えない数値に設定する。
# （ライト案件の場合商品の購入時に在庫数は減らないように設定する）
#=================================================================================
define('DEF_STOCK',10000);//デフォルトの在庫数 在庫数がこの数値に設定される
define('SHOP_LITE_FLG',0);//ショップライトのフラグ　０の場合通常のショップ、１の場合はショップライトの仕様になる

?>
