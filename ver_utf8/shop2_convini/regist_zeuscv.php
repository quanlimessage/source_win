<?php
/*******************************************************************************
通常ショップベース

 LGC: コンビニ決済の最終処理スクリプト
		（独立プログラムとして実行）

		※支払方法がコンビニ決済の場合のみにこのスクリプトが実行される

		テスト方法の説明
			処理完了している場合は強制終了、売上確定時にCREDIT_CLOSE_FLGのフラグが立つ、売上が確定してから取り消しは無いとする

		その１
		ゼウスからコンビニ用のIDを貰っていない、デモページでデータを送っても本番側が無くて受け取れない場合
		-----------------------------------------------------------------------------------------------
			GETでデータを受け取るのでクレジットと同じで要領でテストが行える。
			【http://ドメイン/cv_regist.php?status=01&sendid=000000000-000000】

			statusには実行させたい決済状況01～13の番号を入れる
			sendidはクレジットと同じくオーダーIDを入れる。
		-----------------------------------------------------------------------------------------------

		その２
		ゼウスからコンビニ用のIDが発行されてコンビニ決済用のテストIDがわかっている場合で本番アップされている場合
		-----------------------------------------------------------------------------------------------
			コンビニ決済のテスト
			◆運用テスト
				・システム設定完了後、システム接続の完了を確認するため、運用テストを実施してください。
				・運用テストは土・日・祝祭日を除く10:00～18:00の間に実施してください。（きっかり18時でテストができないと言う訳ではない、もしかしたら電話サポートの居る時間かもしれない）
				・入金速報通知はすぐに送られますが、売上確定通知は入金速報通知後30分以内に送信されます。（すぐには売上確定通知は送られません。）

			◆運用テスト手順
				・ゼウスのコンビニ決済の流れ

					支払うコンビニの選択
						↓
					ユーザー情報入力（ここでお客様名欄にて「お客様名」＋「_（アンダーバー）」＋「テストID」と入力）
						↓
					確認画面（お客様名欄で入力した「お客様名」＋「_（アンダーバー）」＋「テストID」は見えない）
						↓
					テスト画面または完了画面

				の流れになる

				・テスト決済では、ゼウスコンビニ決済ページのお客様名欄にて「お客様名」＋「_（アンダーバー）」＋「テストID」と入力ください。
				　※テストＩＤは当社売上管理画面「テストカード番号管理」より確認いただけます。
				・コンビニ決済お申し込みページに移動しましたら、必要な情報を入力します。
				・「お申し込み内容確認」ボタンを押すと確認画面が表示されますので、内容を確認の上「お申し込み」ボタンを押してください。
				・お申し込みテスト完了ページが表示されますので、「売上確定」ボタンまたは「入金取消」を押しテストを実行してください。
				・テスト完了画面が表示されれば完了です。
		-----------------------------------------------------------------------------------------------

==============================================================================================
決済状況
01=未入金、02=申込エラー、03=期日切、04=入金済、05=売上確定、06=入金取消、11=キャンセル後入金、12=キャンセル後売上、13=キャンセル後取消

●【CGI送信：○】でゼウス⇒加盟店様にデータが送られる。　(※CGI送信欄が「△」のステータスは通常送信しませんが、加盟店様のご要望に応じて送信することも可能です。)
●ゼウス⇒ユーザーのメールは　01　未入金　の時に送られる。
●支払対象：○なのは　05=売上確定、12=キャンセル後売　なのでその時に決済完了をさせる。

ゼウス⇒加盟店様の決済状況ステータス
	01　未入金：ユーザーの申し込み手続きが完了した
		メール送信 ：○　CGI送信：○　支払対象：×

	02　申込エラー：ユーザーの申し込み手続きが失敗した
		メール送信 ：×　CGI送信：△　支払対象：×

	03　期日切：ユーザーは申し込みをしたが、支払期限までに支払わなかった
		メール送信 ：×　CGI送信：△　支払対象：×

	04　入金済：ユーザーは支払期限内に指定のコンビニ店舗で支払いを行った
		メール送信 ：○　CGI送信：○　支払対象：×

	05　売上確定：ユーザーが支払期限内にコンビニ店舗で支払った金額が支払対象として確定した
		メール送信 ：○　CGI送信：○　支払対象：○

	06　入金取消：ユーザーが、コンビニ店舗で支払った代金の取消が行われた
		メール送信 ：○　CGI送信：○　支払対象：×

	無　キャンセル：売上管理画面より再発行処理を行った(元決済データのステータスは自動的にキャンセルとなります)
		メール送信 ：×　CGI送信：×　支払対象：×

	11　キャンセル後入金：ユーザーがキャンセルになった決済に対し、コンビニ店舗で支払いを行った
		メール送信 ：○　CGI送信：○　支払対象：×

	12　キャンセル後売上：ユーザーがキャンセルになった決済に対し、コンビニ店舗で支払った金額が支払対象として確定した
		メール送信 ：○　CGI送信：○　支払対象：○

	13　キャンセル後取消：ユーザーがキャンセルになった決済に対し、コンビニ店舗で支払った代金の取消が行われた
		メール送信 ：×　CGI送信：×　支払対象：×

	番組停止：ユーザーがサービス停止処理後に、コンビニ店舗で代金を支払った
		メール送信 ：×　CGI送信：×　支払対象：×

==============================================================================================
ゼウスから送られてくるデータ内容（CGI送信）

【CGI送信パラメータ一覧】
clientip：IPコード		ゼウス発行のIPコード （10桁固定）
money：決済金額			ユーザーの支払金額
username：名前			ユーザーの氏名
telno：電話番号			ユーザーの電話番号
email：メールアドレス		ユーザーのメールアドレス
sendid：フリーパラメータ	自由にご利用頂けます（オーダーIDを入れている）
sendpoint：フリーパラメータ	自由にご利用頂けます（任意だができれば、案件のドメイン名などを入れて欲しい）
order_no：オーダー番号		ゼウス発行のシリアルナンバー(決済時に当社で発行いたします)
pay_cvs：選択コンビニコード	コンビニ種類(D001=セブンイレブン、D002=ローソン、D030=ファミリーマート、D040=サークルKサンクス、D015=セイコーマート）
pay_no1：払込番号1		コンビニ発行の払込番号(既発行の番号が再度発行される場合がございます払込番号をキーにした、運用・管理はご注意ください)
pay_limit：支払期限		支払い期限 (yyyymmdd)
status：ステータス		決済状況（01=未入金、02=申込エラー、03=期日切、04=入金済、05=売上確定、06=入金取消、11=キャンセル後入金、12=キャンセル後売上、13=キャンセル後取消 ）
error_code：エラーコード	申込エラー時のエラーコード

*******************************************************************************/

// 設定ファイル＆共通ライブラリの読み込み
	require_once("./common/INI_config.php");		// 設定ファイル

	require_once("./common/INI_ShopConfig.php");		// ショップ用設定ファイル
	require_once("./common/INI_pref_list.php");		// 送料＆都道府県情報（配列）
	require_once("dbOpe.php");				// ＤＢ操作クラスライブラリ
	require_once("util_lib.php");				// 汎用処理クラスライブラリ
	require_once("tmpl2.class.php");			// PHPテンプレートクラスライブラリ

#=============================================================================
# 処理する分岐が多数あり if switch　などで分岐してそれぞれの処理を書くと長くなりごっちゃになるので
# 各処理をある程度functionで処理をさせていく
# （売上確定でも、【05　売上確定】【12　キャンセル後売上】のフラグがあるため同じ処理が発生する場合がある）
#=============================================================================

#----------------------------------------------------------------------------------------------------------------------------------
// function stock_down
// 変数		$oid		データベースの PURCHASE_LST にある　ORDER_ID　を入れる
//

// オーダーIDのから購入した商品の在庫を減らす。
// 【01　未入金】で実行させる。
// 在庫切れになった場合は管理者に在庫切れのメールを送る
//
// 売上確定時に在庫を減らすと売上確定する前に在庫がなくなった場合、在庫が合わなくなってしまう。
// 在庫を戻すのは【03　期日切】の時にする。
//
#----------------------------------------------------------------------------------------------------------------------------------

function stock_down($oid = ""){

	//////////////////////////////////////////////////////////////////////////////////////
	//不正なIDの場合
		if(!ereg("^([0-9]{10,})-([0-9]{6})$",$oid)){
			return false;
		}

	//////////////////////////////////////////////////////////////////////////////////////
	//データベースから購入内容を取得する
		$sql_peritem = "
		SELECT
			PRODUCT_ID,
			PART_NO,
			PRODUCT_NAME,
			SELLING_PRICE,
			QUANTITY
		FROM
			PURCHASE_ITEM_DATA
		WHERE
			( ORDER_ID = '".$oid."' )
		AND
			( DEL_FLG = '0' )
		ORDER BY
			PID
		";

		$fetchPerItem = dbOpe::fetch($sql_peritem,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//////////////////////////////////////////////////////////////////////////////////////
	//在庫の減算処理を行う
		for ( $i = 0; $i < count($fetchPerItem); $i++ ){

			#-------------------------------------------------------------------------
			#	在庫管理処理
			#-------------------------------------------------------------------------

			# 現在個数取得
				$cnt_sql ="
					SELECT
						STOCK_QUANTITY
					FROM
						PRODUCT_LST
					WHERE
						PRODUCT_ID = '".$fetchPerItem[$i]['PRODUCT_ID']."'
				";

				$CntRst = dbOpe::fetch($cnt_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			########################################################################################################
			# 在庫数オーバーのチェックは決済前に行ってるのでここでは在庫オーバーは管理者メールに添付させる
			#	決済処理中に在庫数が変動し在庫切れになった場合・・・
			########################################################################################################

			// 在庫数が購入個数を下回ってる(決済処理中に在庫切れ・・)
				// とりあえず現在庫数を「0」に
				if($CntRst[0]["STOCK_QUANTITY"] < $fetchPerItem[$i]['QUANTITY']):
						$zaiko = 0;

						// 管理者通知用
						$zaiko_err_str .= "【！】決済処理中に在庫切れが発生してしまいました。\n";
						$zaiko_err_str .= "該当商品番号：".$fetchPerItem[$i]["PART_NO"]."\n";
						$zaiko_err_str .= "該当商品名：".$fetchPerItem[$i]["PRODUCT_NAME"]."\n";
						$zaiko_err_str .= "現在商品数：".$CntRst[0]["STOCK_QUANTITY"]."\n";
						$zaiko_err_str .= "購入数：".$fetchPerItem[$i]['QUANTITY']."\n";
						$zaiko_err_str .= "決済は完了してしまっています。至急対処をお願い致します。\n\n";

				// それ以外(通常計算)
				else:
					# 現在庫数 - 購入個数 で購入後の在庫数を求める
					$zaiko = $CntRst[0]["STOCK_QUANTITY"] - $fetchPerItem[$i]['QUANTITY'];
				endif;

			// 在庫が0になった商品があれば、メール通知するためにリスト化する
				if($zaiko < 1)$zaikogire_lst .= "商品番号：".$fetchPerItem[$i]["PART_NO"]." ".$fetchPerItem[$i]["PRODUCT_NAME"]."\n";

			/////////////////////////////////
			// 購入後の在庫数をDBに上書き
				$zaiko_sql ="
					UPDATE
						PRODUCT_LST
					SET
						STOCK_QUANTITY = ".$zaiko."
					WHERE
						PRODUCT_ID = '".$fetchPerItem[$i]['PRODUCT_ID']."'
				";

				$ZaikoRst = dbOpe::regist($zaiko_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

			// 在庫データ書換え失敗時はエラー
			if ( $ZaikoRst ){
				return false;
			}
		}

		#================================================================================
		# 設定したＳＱＬを実行（登録失敗時：ＤＢエラー出力して強制終了）
		#================================================================================
			if(!empty($sql)){
				$registDB_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if ( $registDB_result ){
					return false;
				}
			}

	//////////////////////////////////////////////////////////////////////////////////////
	// 在庫切れメール通知
		if($zaikogire_lst):

			// 件名とフッター
			$subject = "【自動通知メール】商品の在庫数が0になりました";

			$headers = "Reply-To: ".WEBMST_SHOP_MAIL."\n";
			$headers .= "Return-Path: ".WEBMST_SHOP_MAIL."\n";
			$headers .= "From:".mb_encode_mimeheader("自動送信メール")."<".WEBMST_SHOP_MAIL.">\n";

			$mailbody = "
お客様のご購入により、下記商品の在庫数が0になりました。

{$zaikogire_lst}
";

			//改行処理をする（マイクロソフトOutlook（Outlook Expressではない）で改行されていないメールが届く為）
				$mailbody = str_replace("\r","", $mailbody);//qmailでメールを送信するため勝手に改行コードをサーバー側で変換されるのでCRを全て除去LFのみにする（smatとpop3ではこの処理はさせない方がいい）

			// メール送信（失敗時：強制終了）
				$webmstmail_result = mb_send_mail(WEBMST_SHOP_MAIL,$subject,$mailbody,$headers);
				if(!$webmstmail_result)die("Send Mail Error! for WebMaster");

		endif;

	//処理が完了
	return true;
}

#----------------------------------------------------------------------------------------------------------------------------------
// function stock_up
// 変数		$oid		データベースの PURCHASE_LST にある　ORDER_ID　を入れる
//

// オーダーIDのから購入した商品の在庫を戻す。（【01　未入金】の減らした在庫を戻す）
// 【03　期日切】で実行させる。
//
#----------------------------------------------------------------------------------------------------------------------------------

function stock_up($oid = ""){

	//////////////////////////////////////////////////////////////////////////////////////
	//不正なIDの場合
		if(!ereg("^([0-9]{10,})-([0-9]{6})$",$oid)){
			return false;
		}

	//////////////////////////////////////////////////////////////////////////////////////
	//データベースから購入内容を取得する
		$sql_peritem = "
		SELECT
			PRODUCT_ID,
			PART_NO,
			PRODUCT_NAME,
			SELLING_PRICE,
			QUANTITY
		FROM
			PURCHASE_ITEM_DATA
		WHERE
			( ORDER_ID = '".$oid."' )
		AND
			( DEL_FLG = '0' )
		ORDER BY
			PID
		";

		$fetchPerItem = dbOpe::fetch($sql_peritem,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//////////////////////////////////////////////////////////////////////////////////////
	//在庫の増加処理を行う
		for ( $i = 0; $i < count($fetchPerItem); $i++ ){

			// 購入後の在庫数をDBに上書き
				$zaiko_sql ="
					UPDATE
						PRODUCT_LST
					SET
						STOCK_QUANTITY = STOCK_QUANTITY + ".$fetchPerItem[$i]['QUANTITY']."
					WHERE
						PRODUCT_ID = '".$fetchPerItem[$i]['PRODUCT_ID']."'
				";

				$ZaikoRst = dbOpe::regist($zaiko_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		}

	///////////////////////////////////////
	// PURCHASE_LST　に決済失敗のフラグを立てる

		$cv_pf_sql = "
		UPDATE
			PURCHASE_LST
		SET
			PAYMENT_FLG = '2',
			PAYMENT_DATE = NOW(),
			CREDIT_CLOSE_FLG = '1'
		WHERE
			(ORDER_ID = '".$oid."')
		AND
			(DEL_FLG = '0')
		";

		$cvRst = dbOpe::regist($cv_pf_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//処理が完了
	return true;

}

#----------------------------------------------------------------------------------------------------------------------------------
// function pay_completion
// 変数		$oid		データベースの PURCHASE_LST にある　ORDER_ID　を入れる
//

// 売上確定のデータが送られてきた場合処理をおこなう
// 【05　売上確定】で実行させる。
// PURCHASE_LST　の　PAYMENT_FLG　を決済完了に変えて、管理者とエンドユーザーに決済完了のメールを送る
#----------------------------------------------------------------------------------------------------------------------------------

function pay_completion($oid = ""){

	global $shipping_list;//メール返信の住所で都道府県を表示する為に必要な為グローバル宣言をする

	//////////////////////////////////////////////////////////////////////////////////////
	//不正なIDの場合
		if(!ereg("^([0-9]{10,})-([0-9]{6})$",$oid)){
			return false;
		}

	//関数内なのでここでデータベースから購入情報を取得する
		$sql_ordercust = "
		SELECT
			PURCHASE_LST.ORDER_ID,
			CUSTOMER_LST.CUSTOMER_ID,
			CUSTOMER_LST.LAST_NAME,
			CUSTOMER_LST.FIRST_NAME,
			CUSTOMER_LST.LAST_KANA,
			CUSTOMER_LST.FIRST_KANA,
			CUSTOMER_LST.ZIP_CD1,
			CUSTOMER_LST.ZIP_CD2,
			CUSTOMER_LST.STATE,
			CUSTOMER_LST.ADDRESS1,
			CUSTOMER_LST.ADDRESS2,
			CUSTOMER_LST.EMAIL,
			CUSTOMER_LST.TEL1,
			CUSTOMER_LST.TEL2,
			CUSTOMER_LST.TEL3,
			CUSTOMER_LST.EXISTING_CUSTOMER_FLG,
			CUSTOMER_LST.ALPWD,
			PURCHASE_LST.TOTAL_PRICE,
			PURCHASE_LST.SUM_PRICE,
			PURCHASE_LST.SHIPPING_AMOUNT,
			PURCHASE_LST.DELI_LAST_NAME,
			PURCHASE_LST.DELI_FIRST_NAME,
			PURCHASE_LST.DELI_ZIP_CD1,
			PURCHASE_LST.DELI_ZIP_CD2,
			PURCHASE_LST.DELI_STATE,
			PURCHASE_LST.DELI_ADDRESS1,
			PURCHASE_LST.DELI_ADDRESS2,
			PURCHASE_LST.DELI_TEL1,
			PURCHASE_LST.DELI_TEL2,
			PURCHASE_LST.DELI_TEL3,
			PURCHASE_LST.REMARKS,

			PURCHASE_LST.CV_PAYMENT_FLG,
			PURCHASE_LST.CV_PAYMENT_HIST,

			PURCHASE_LST.CREDIT_CLOSE_FLG
		FROM
			PURCHASE_LST,CUSTOMER_LST
		WHERE
			PURCHASE_LST.CUSTOMER_ID = CUSTOMER_LST.CUSTOMER_ID
		AND
			(PURCHASE_LST.ORDER_ID = '".$oid."')
		AND
			(PURCHASE_LST.PAYMENT_TYPE = '4')
		AND
			(PURCHASE_LST.DEL_FLG = '0')
		AND
			(CUSTOMER_LST.DEL_FLG = '0')
		";

		$fetchOrderCust = dbOpe::fetch($sql_ordercust,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

		if(!count($fetchOrderCust)){
			return false;
		}

	///////////////////////////////////////
	// PURCHASE_LST　に決済完了のフラグを立てる

		$cv_pf_sql = "
		UPDATE
			PURCHASE_LST
		SET
			PAYMENT_FLG = '1',
			PAYMENT_DATE = NOW(),
			CREDIT_CLOSE_FLG = '1'
		WHERE
			(ORDER_ID = '".$fetchOrderCust[0]["ORDER_ID"]."')
		AND
			(CUSTOMER_ID = '".$fetchOrderCust[0]["CUSTOMER_ID"]."')
		AND
			(DEL_FLG = '0')
		";

		$cvRst = dbOpe::regist($cv_pf_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//////////////////////////////////////////////////////////////////////////////////////
	//データベースから購入内容を取得する
		$sql_peritem = "
		SELECT
			PRODUCT_ID,
			PART_NO,
			PRODUCT_NAME,
			SELLING_PRICE,
			QUANTITY
		FROM
			PURCHASE_ITEM_DATA
		WHERE
			( ORDER_ID = '".$oid."' )
		AND
			( DEL_FLG = '0' )
		ORDER BY
			PID
		";

		$fetchPerItem = dbOpe::fetch($sql_peritem,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	#=============================================================================
	# ユーザーと管理者へ メール送信の設定と送信を行う
	#=============================================================================

		// 新規顧客か再度利用客か判断
		if($fetchOrderCust[0]['EXISTING_CUSTOMER_FLG'] == "1")://既存顧客の場合

			// メールにパスを添付するためのフラグ(添付しないのでfalseに)
			$pw_flg = false;

		else://新規顧客の場合

			// メールにパスを添付するためのフラグ(添付するのでtrueに)
			$pw_flg = true;

		endif;

		#-------------------------------------------------------------------------
		# 購入者（お客様）へサンキューメールを送信
		#-------------------------------------------------------------------------
			// 本文雛形を読み込み
				$mailbody = file_get_contents("./regist/mail_tmpl/INI_mailbody_payCONV.dat");

			// 基本情報
				if(strstr($mailbody,"<NAME>"))$mailbody = str_replace("<NAME>",$fetchOrderCust[0]["LAST_NAME"]." ".$fetchOrderCust[0]["FIRST_NAME"],$mailbody);
				if(strstr($mailbody,"<ORDER_ID>"))$mailbody = str_replace("<ORDER_ID>",$fetchOrderCust[0]["ORDER_ID"],$mailbody);
				if(strstr($mailbody,"<DATE>"))$mailbody = str_replace("<DATE>",date("Y年m月d日"),$mailbody);

			// 購入商品（$itemsは管理者用にも使用）
				for($i = 0; $i < count($fetchPerItem); $i++){
					$items .= "商品番号：".$fetchPerItem[$i]['PART_NO']."\n";
					$items .= "商品名：".$fetchPerItem[$i]['PRODUCT_NAME']."\n";
					$items .= "\\".number_format($fetchPerItem[$i]['SELLING_PRICE'])."\t";
					$items .= "数量：".$fetchPerItem[$i]['QUANTITY']."\n\n";
				}
				if(strstr($mailbody,"<ITEMS>"))$mailbody = str_replace("<ITEMS>",$items,$mailbody);
				if(strstr($mailbody,"<SUM_PRICE>"))$mailbody = str_replace("<SUM_PRICE>",number_format($fetchOrderCust[0]["SUM_PRICE"]),$mailbody);
				if(strstr($mailbody,"<SHIPPING>"))$mailbody = str_replace("<SHIPPING>",number_format($fetchOrderCust[0]["SHIPPING_AMOUNT"]),$mailbody);

				if(strstr($mailbody,"<TOTAL_PRICE>"))$mailbody = str_replace("<TOTAL_PRICE>",number_format($fetchOrderCust[0]["TOTAL_PRICE"]),$mailbody);

			// 個人情報
				if(strstr($mailbody,"<KANA>"))$mailbody = str_replace("<KANA>",$fetchOrderCust[0]["LAST_KANA"]." ".$fetchOrderCust[0]["FIRST_KANA"],$mailbody);
				if(strstr($mailbody,"<USRMAIL>"))$mailbody = str_replace("<USRMAIL>",$fetchOrderCust[0]["EMAIL"],$mailbody);
				if(strstr($mailbody,"<ZIP>"))$mailbody = str_replace("<ZIP>",$fetchOrderCust[0]["ZIP_CD1"]."-".$fetchOrderCust[0]["ZIP_CD2"],$mailbody);
				if(strstr($mailbody,"<ADDRESS>"))$mailbody = str_replace("<ADDRESS>",$shipping_list[$fetchOrderCust[0]["STATE"]]['pref'].$fetchOrderCust[0]["ADDRESS1"].$fetchOrderCust[0]["ADDRESS2"],$mailbody);
				if(strstr($mailbody,"<USRTEL>"))$mailbody = str_replace("<USRTEL>",$fetchOrderCust[0]["TEL1"]."-".$fetchOrderCust[0]["TEL2"]."-".$fetchOrderCust[0]["TEL3"],$mailbody);

			// ※パスワードの表示は初回のみ(フラグ$pw_flgで判定)
				if($pw_flg){
					if(strstr($mailbody,"<DISP_PASSWD>"))$mailbody = str_replace("<DISP_PASSWD>","パスワード：".$fetchOrderCust[0]['ALPWD']." → 次回のお買い物の際にご利用ください",$mailbody);

				}
				else{
					if(strstr($mailbody,"<DISP_PASSWD>"))$mailbody = str_replace("<DISP_PASSWD>","",$mailbody);
				}

			// 配送先
				if(strstr($mailbody,"<S_NAME>"))$mailbody = str_replace("<S_NAME>",$fetchOrderCust[0]["DELI_LAST_NAME"]." ".$fetchOrderCust[0]["DELI_FIRST_NAME"],$mailbody);
				if(strstr($mailbody,"<S_ZIP>"))$mailbody = str_replace("<S_ZIP>",$fetchOrderCust[0]["DELI_ZIP_CD1"]."-".$fetchOrderCust[0]["DELI_ZIP_CD2"],$mailbody);
				if(strstr($mailbody,"<S_ADDRESS>"))$mailbody = str_replace("<S_ADDRESS>",$shipping_list[$fetchOrderCust[0]["DELI_STATE"]]['pref'].$fetchOrderCust[0]["DELI_ADDRESS1"].$fetchOrderCust[0]["DELI_ADDRESS2"],$mailbody);
				if(strstr($mailbody,"<S_USRTEL>"))$mailbody = str_replace("<S_USRTEL>",$fetchOrderCust[0]["DELI_TEL1"]."-".$fetchOrderCust[0]["DELI_TEL2"]."-".$fetchOrderCust[0]["DELI_TEL3"],$mailbody);

			// 備考欄
				if(strstr($mailbody,"<REMARKS>"))$mailbody = str_replace("<REMARKS>",$fetchOrderCust[0]["REMARKS"],$mailbody);

			//パスワード変更のお知らせ（forgetpass）のURL
			//現在の位置を求めてforgetpassへのURLを出す

				$url = getcwd();
				$sname = $_SERVER["SERVER_NAME"];
				$dr = $_SERVER["DOCUMENT_ROOT"];

				$url = str_replace($dr,"",$url);
				$url = "http://".$sname.$url."/regist/forgetpass/";//階層が多くてうまくいかない場合はURLを直書きすれば問題は無い

				if(strstr($mailbody,"<FOR_GET_PASS>"))$mailbody = str_replace("<FOR_GET_PASS>",$url,$mailbody);

			// 会社情報
				if(strstr($mailbody,"<COMPANY_INFO>"))$mailbody = str_replace("<COMPANY_INFO>",COMPANY_INFO,$mailbody);

			////////////////////////////////////////////////////////////////
			//gmailでReply-Toをカンマ区切りでメールを送信すると弾かれる仕様なのでカンマがあった場合は一番最初のメールアドレスを
			//Reply-Toに設定する（この設定はエンドユーザーに送信するメールに対して行う）
			//mb_send_mailのReturn-Pathの設定
				$rt_email = substr(WEBMST_SHOP_MAIL,0,strpos(WEBMST_SHOP_MAIL,","));//メールアドレスがカンマ区切りになっていた場合用にメールアドレスを一つだけ抽出処理
				$rt_email = ($rt_email)?$rt_email:WEBMST_SHOP_MAIL;//空っぽだった場合はカンマ区切りは無し

			// 件名とフッター
				$subject = SUBJECT_CLIENT_NAME."より自動返信メール";
				$headers = "Reply-To: ".$rt_email."\n";
				$headers .= "Return-Path: ".$rt_email."\n";
				$headers .= "From:".mb_encode_mimeheader(WEBMST_NAME, "JIS", "B", "\n")."<".$rt_email.">\n";
				$rpath = "-f ".$rt_email;//mb_send_mailのReturn-Path を設定

			//改行処理をする（マイクロソフトOutlook（Outlook Expressではない）で改行されていないメールが届く為）
				$mailbody = str_replace("\r","", $mailbody);//qmailでメールを送信するため勝手に改行コードをサーバー側で変換されるのでCRを全て除去LFのみにする（smatとpop3ではこの処理はさせない方がいい）

			// メール送信（失敗時：強制終了）
				$usrmail_result = mb_send_mail($fetchOrderCust[0]["EMAIL"],$subject,$mailbody,$headers,$rpath);
				if(!$usrmail_result){
					die("致命的エラー：お客様へのメール送信に失敗しました。");
				}

		#-------------------------------------------------------------------------
		# 管理者（Webマスター宛）へ報告メール送信
		#-------------------------------------------------------------------------
			// 本文雛形読み込み
				$mailbody = file_get_contents("./regist/mail_tmpl/INI_mailbody_forADMIN.dat");

			// 基本情報
				if(strstr($mailbody,"<NAME>"))$mailbody = str_replace("<NAME>",$fetchOrderCust[0]["LAST_NAME"]." ".$fetchOrderCust[0]["FIRST_NAME"],$mailbody);
				if(strstr($mailbody,"<ORDER_ID>"))$mailbody = str_replace("<ORDER_ID>",$fetchOrderCust[0]["ORDER_ID"],$mailbody);
				if(strstr($mailbody,"<DATE>"))$mailbody = str_replace("<DATE>",date("Y年m月d日"),$mailbody);

			// 購入商品
				if(strstr($mailbody,"<ITEMS>"))$mailbody = str_replace("<ITEMS>",$items,$mailbody);
				if(strstr($mailbody,"<SUM_PRICE>"))$mailbody = str_replace("<SUM_PRICE>",number_format($fetchOrderCust[0]["SUM_PRICE"]),$mailbody);
				if(strstr($mailbody,"<SHIPPING>"))$mailbody = str_replace("<SHIPPING>",number_format($fetchOrderCust[0]["SHIPPING_AMOUNT"]),$mailbody);
				if(strstr($mailbody,"<TOTAL_PRICE>"))$mailbody = str_replace("<TOTAL_PRICE>",number_format($fetchOrderCust[0]["TOTAL_PRICE"]),$mailbody);
				if(strstr($mailbody,"<DAIBIKI_DISP>"))$mailbody = str_replace("<DAIBIKI_DISP>","",$mailbody);
				if(strstr($mailbody,"<CONV_NO>"))$mailbody = str_replace("<CONV_NO>","",$mailbody);

			// 個人情報
				if(strstr($mailbody,"<KANA>"))$mailbody = str_replace("<KANA>",$fetchOrderCust[0]["LAST_KANA"]." ".$fetchOrderCust[0]["FIRST_KANA"],$mailbody);
				if(strstr($mailbody,"<USRMAIL>"))$mailbody = str_replace("<USRMAIL>",$fetchOrderCust[0]["EMAIL"],$mailbody);
				if(strstr($mailbody,"<ZIP>"))$mailbody = str_replace("<ZIP>",$fetchOrderCust[0]["ZIP_CD1"]."-".$fetchOrderCust[0]["ZIP_CD2"],$mailbody);
				if(strstr($mailbody,"<ADDRESS>"))$mailbody = str_replace("<ADDRESS>",$shipping_list[$fetchOrderCust[0]["STATE"]]['pref'].$fetchOrderCust[0]["ADDRESS1"].$fetchOrderCust[0]["ADDRESS2"],$mailbody);
				if(strstr($mailbody,"<USRTEL>"))$mailbody = str_replace("<USRTEL>",$fetchOrderCust[0]["TEL1"]."-".$fetchOrderCust[0]["TEL2"]."-".$fetchOrderCust[0]["TEL3"],$mailbody);
				if(strstr($mailbody,"<ALPWD>"))$mailbody = str_replace("<ALPWD>",$fetchOrderCust[0]['ALPWD'],$mailbody);

			// 配送先
				if(strstr($mailbody,"<S_NAME>"))$mailbody = str_replace("<S_NAME>",$fetchOrderCust[0]["DELI_LAST_NAME"]." ".$fetchOrderCust[0]["DELI_FIRST_NAME"],$mailbody);
				if(strstr($mailbody,"<S_ZIP>"))$mailbody = str_replace("<S_ZIP>",$fetchOrderCust[0]["DELI_ZIP_CD1"]."-".$fetchOrderCust[0]["DELI_ZIP_CD2"],$mailbody);
				if(strstr($mailbody,"<S_ADDRESS>"))$mailbody = str_replace("<S_ADDRESS>",$shipping_list[$fetchOrderCust[0]["DELI_STATE"]]['pref'].$fetchOrderCust[0]["DELI_ADDRESS1"].$fetchOrderCust[0]["DELI_ADDRESS2"],$mailbody);
				if(strstr($mailbody,"<S_USRTEL>"))$mailbody = str_replace("<S_USRTEL>",$fetchOrderCust[0]["DELI_TEL1"]."-".$fetchOrderCust[0]["DELI_TEL2"]."-".$fetchOrderCust[0]["DELI_TEL3"],$mailbody);

			// もし決済処理中在庫エラーが発生していたら備考欄で通知
				if(!empty($zaiko_err_str)){
					$remarks = $fetchOrderCust[0]["REMARKS"]."\n".$zaiko_err_str;
				}else{
					$remarks = $fetchOrderCust[0]["REMARKS"];
				}

				if(strlen($error_mes) > 0)$remarks .= "\n".$error_mes;

			// 備考欄
				if(strstr($mailbody,"<REMARKS>"))$mailbody = str_replace("<REMARKS>",$remarks,$mailbody);

			// 会社情報
				if(strstr($mailbody,"<COMPANY_INFO>"))$mailbody = str_replace("<COMPANY_INFO>",COMPANY_INFO,$mailbody);

			// 支払方法
				if(strstr($mailbody,"<METHOD>"))$mailbody = str_replace("<METHOD>","コンビニ",$mailbody);

			// コンビニ決済置換用文字を空白に置換
				if(strstr($mailbody,"<CONV_DISP>"))$mailbody = str_replace("<CONV_DISP>","",$mailbody);

			// 件名とフッター
				$subject = "webよりコンビニ決済でお申し込みがありました";
				$headers = "Reply-To: ".$fetchOrderCust[0]["EMAIL"]."\n";
				$headers .= "Return-Path: ".$fetchOrderCust[0]["EMAIL"]."\n";
				$headers .= "From:".mb_encode_mimeheader("自動送信メール")."<".$fetchOrderCust[0]["EMAIL"].">\n";
				$rpath = "-f ".$fetchOrderCust[0]["EMAIL"];//mb_send_mailのReturn-Path を設定

			//改行処理をする（マイクロソフトOutlook（Outlook Expressではない）で改行されていないメールが届く為）
				$mailbody = str_replace("\r","", $mailbody);//qmailでメールを送信するため勝手に改行コードをサーバー側で変換されるのでCRを全て除去LFのみにする（smatとpop3ではこの処理はさせない方がいい）

			// メール送信（失敗時：強制終了）
				$webmstmail_result = mb_send_mail(WEBMST_SHOP_MAIL,$subject,$mailbody,$headers,$rpath);
				if(!$webmstmail_result){
					die("致命的エラー：管理者へのメール送信に失敗しました。");
				}

		//処理が終わる前に購入経験フラグをありに変える
			$sqlbuy_flg = "
				UPDATE
					CUSTOMER_LST
				SET
					EXISTING_CUSTOMER_FLG  = '1'
				WHERE
					( CUSTOMER_ID = '".$fetchOrderCust[0]["CUSTOMER_ID"]."' )
				AND
					(DEL_FLG = '0')
			";
			$registDB_buy_flg = dbOpe::regist($sqlbuy_flg,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//処理完了
	return true;

}

#=============================================================================
# 決済サイトより決済処理完了後に送信されるGETの受取とエラーチェック
# （エラー時：強制終了）
#
#	※汎用処理クラスライブラリを使用して処理
#	※処理項目：タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
#=============================================================================
if($_GET){extract(utilLib::getRequestParams("get",array(8,7,1,4,5),true));}

// 念のためURLデコードしておく（ハイフンがエンコードされる時とされない時があるようだ。by Yos）
$cod = urldecode($sendid);//オーダーID
$result  = urldecode($status);//コンビニ決済の結果

///////////////////////////////////////
// 注文情報と個人情報の取り出し
	$sql_ordercust = "
	SELECT
		PURCHASE_LST.ORDER_ID,
		PURCHASE_LST.CUSTOMER_ID,
		CUSTOMER_LST.LAST_NAME,
		CUSTOMER_LST.FIRST_NAME,
		CUSTOMER_LST.LAST_KANA,
		CUSTOMER_LST.FIRST_KANA,
		CUSTOMER_LST.ZIP_CD1,
		CUSTOMER_LST.ZIP_CD2,
		CUSTOMER_LST.STATE,
		CUSTOMER_LST.ADDRESS1,
		CUSTOMER_LST.ADDRESS2,
		CUSTOMER_LST.EMAIL,
		CUSTOMER_LST.TEL1,
		CUSTOMER_LST.TEL2,
		CUSTOMER_LST.TEL3,
		CUSTOMER_LST.EXISTING_CUSTOMER_FLG,
		CUSTOMER_LST.ALPWD,
		PURCHASE_LST.TOTAL_PRICE,
		PURCHASE_LST.SUM_PRICE,
		PURCHASE_LST.SHIPPING_AMOUNT,
		PURCHASE_LST.DELI_LAST_NAME,
		PURCHASE_LST.DELI_FIRST_NAME,
		PURCHASE_LST.DELI_ZIP_CD1,
		PURCHASE_LST.DELI_ZIP_CD2,
		PURCHASE_LST.DELI_STATE,
		PURCHASE_LST.DELI_ADDRESS1,
		PURCHASE_LST.DELI_ADDRESS2,
		PURCHASE_LST.DELI_TEL1,
		PURCHASE_LST.DELI_TEL2,
		PURCHASE_LST.DELI_TEL3,
		PURCHASE_LST.REMARKS,

		PURCHASE_LST.CV_PAYMENT_FLG,
		PURCHASE_LST.CV_PAYMENT_HIST,

		PURCHASE_LST.CREDIT_CLOSE_FLG
	FROM
		PURCHASE_LST,CUSTOMER_LST
	WHERE
		PURCHASE_LST.CUSTOMER_ID = CUSTOMER_LST.CUSTOMER_ID
	AND
		(PURCHASE_LST.ORDER_ID = '".$cod."')
	AND
		(PURCHASE_LST.PAYMENT_TYPE = '4')
	AND
		(PURCHASE_LST.DEL_FLG = '0')
	AND
		(CUSTOMER_LST.DEL_FLG = '0')
	";

	$fetchOrderCust = dbOpe::fetch($sql_ordercust,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

//データが取得できなかった場合
	if(!count($fetchOrderCust)){
			die("致命的エラー：不正な処理データが送信されましたので強制終了します！");
	}

	// 処理完了している場合は強制終了（売上確定時にCREDIT_CLOSE_FLGのフラグが立つ、売上が確定してから取り消しは無いとする）
		if($fetchOrderCust[0]["CREDIT_CLOSE_FLG"] == 1){//
			die("致命的エラー：不正な処理データが送信されましたので強制終了します！");
		}

#=============================================================================
# コンビニ決済で送られてきたデータを履歴として残しておく
# トラブルが発生した場合に把握できるように
# ゼウスの管理画面のID PWがわからなくて、ゼウス管理画面が見えない場合があるので重要
#=============================================================================

	//内容を初期化 まずは現在の日付を入れておく
		$cv_hist_post_data = date("Y/m/d l H:i:s")."：cv_data\n";//日付の後ろにあるcv_dataはここが先頭と簡単に検索できるようにつけているだけ

	//送られてきたデータを整理
		if(count($_GET)){//あんまり考えられないがGETデータが無い場合
			foreach($_GET as $k => $v){
				$str = mb_convert_encoding($v, "UTF-8", "SJIS");//ゼウスからはSJISのエンコードでデータが渡されるので変換処理を入れる。（全角のデータはusernameの購入者名のみ）
				$cv_hist_post_data .= "【".urldecode($k)."=".urldecode($str)."】\n";//変数名とデータを入れていく
			}
		}

	//今までの履歴を後ろに付ける
		$cv_hist_post_data .= "\n".$fetchOrderCust[0]['CV_PAYMENT_HIST'];

	//データベースに履歴を入れる。
		$cv_his_sql ="
			UPDATE
				PURCHASE_LST
			SET
				CV_PAYMENT_HIST = '".addslashes($cv_hist_post_data)."'
			WHERE
				(PURCHASE_LST.ORDER_ID = '".$cod."')
		";

		$cvRst = dbOpe::regist($cv_his_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

#=============================================================================
# ここからがメイン、送られてきたデータを決済内容ごとに分岐する
# 送られてきたデータによっては、何もせずにコンビニ決済の状況のフラグの更新のみで終わる
#
# 決済状況
# 01=未入金、02=申込エラー、03=期日切、04=入金済、05=売上確定、06=入金取消、11=キャンセル後入金、12=キャンセル後売上、13=キャンセル後取消

#=============================================================================
	$pro_flg = true;//処理が正常に終了しているかのフラグ（デフォルトは成功）

	switch($result):

		case '01'://01=未入金
				$pro_flg = stock_down($fetchOrderCust[0]['ORDER_ID']);//在庫の減算処理をする。
			break;

		case '02'://02=申込エラー

			break;

		case '03'://03=期日切
				$pro_flg = stock_up($fetchOrderCust[0]['ORDER_ID']);//在庫を戻す。
			break;

		case '04'://04=入金済　入金速報なのでまだ売上確定していない

			break;

		case '05'://05=売上確定
				$pro_flg = pay_completion($fetchOrderCust[0]['ORDER_ID']);//フラグの変更とメール送信処理を行う
			break;

		case '06'://06=入金取消

			break;

		case '11'://11=キャンセル後入金　入金速報なのでまだ売上確定していない

			break;

		case '12'://12=キャンセル後売上
				$pro_flg = pay_completion($fetchOrderCust[0]['ORDER_ID']);//フラグの変更とメール送信処理を行う
			break;

		case '13'://13=キャンセル後取消

			break;

		default://どれにも該当しなかった場合
			//不正なアクセスとして強制的に終了
			die("致命的エラー：不正な処理データが送信されましたので強制終了します！");

	endswitch;

	//最後にコンビニ決済の状況のフラグをデータベースに更新させる。
		$cv_pf_sql ="
			UPDATE
				PURCHASE_LST
			SET
				CV_PAYMENT_FLG = '".$result."'
			WHERE
				(PURCHASE_LST.ORDER_ID = '".$cod."')
		";

		$cvRst = dbOpe::regist($cv_pf_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

//正常に処理が済んでいればリダイレクトＨＴＭＬを表示する
	if($pro_flg){
?>
<html>
<head><meta http-equiv="refresh" content="0;URL=/regist/credit_completion.html">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>
<body>
</body>
</html>
<?php
}else{//正常終了で無い場合
	die("致命的エラー：不正な処理データが送信されましたので強制終了します！");
}

?>