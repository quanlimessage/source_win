<?php
/*******************************************************************************
Sx系プログラム バックオフィス（MySQL対応版）
Logic：DB登録・更新処理

*******************************************************************************/

#=================================================================================
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#=================================================================================
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
	header("Location: ../");exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$accessChk){
	header("Location: ../");exit();
}

#=================================================================================
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#=================================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

// MySQLにおいて危険文字をエスケープしておく
$title = utilLib::strRep($title,5);
//$content = utilLib::strRep($content,5);
//$detail_content = utilLib::strRep($detail_content,5);

//ＨＴＭＬタグの有効化の処理（【utilLib::getRequestParams】の文字処理を行う前の情報を使用するためPOSTを使用する）
$content = html_tag($_POST['content']);
$detail_content = html_tag($_POST['detail_content']);

#==================================================================
# 更新する内容をここで記述をする
# フィールドの追加・変更はここで修正
#==================================================================
	$sql_update_data = "
		TITLE = '$title',
		CONTENT = '$content',
		DETAIL_CONTENT = '$detail_content',
		DISP_DATE = NOW(),
		DISPLAY_FLG = '$display_flg',
		DEL_FLG = '0'
	";

#=================================================================================
# 新規か更新かによって処理を分岐	※判断は$_POST["regist_type"]
#=================================================================================
switch($_POST["regist_type"]):
case "update":
//////////////////////////////////////////////////////////
// 対象IDのデータ更新

	// 対象記事IDデータのチェック
	if(!preg_match("/^([0-9]{10,})-([0-9]{6})$/",$res_id)||empty($res_id)){
		die("致命的エラー：不正な処理データが送信されましたので強制終了します！<br>{$res_id}");
	}

	// 画像ファイル名の決定（POSTで渡された既存の記事ID（$res_id）を使用）
	$for_imgname = $res_id; // POSTで渡された既存記事IDを使用

	// 削除指示がされていたら実行(複数)
	if($_POST["regist_type"]=="update" && $del_img){
		  foreach($del_img as $k => $v){
			if(file_exists(S5_1IMG_PATH.$res_id."_".$v.".jpg")){
			  unlink(S5_1IMG_PATH.$res_id."_".$v.".jpg") or die("画像{$v}の削除に失敗しました。");
			}
			}
	}

	// DB格納用のSQL文
	$sql = "
	UPDATE
		".S5_1PRODUCT_LST."
	SET
		$sql_update_data
	WHERE
		(RES_ID = '$res_id')
	";
	break;

case "new":
//////////////////////////////////////////////////////////////////
// 新規登録

	// 画像ファイル名の決定（新しいIDを生成して使用。DB登録時のRES_IDにも使用）
	$res_id = $makeID();
	$for_imgname = $res_id;

	// 現在の登録件数が設定した件数未満の場合のみDBに格納
	$cnt_sql = "SELECT COUNT(*) AS CNT FROM ".S5_1PRODUCT_LST." WHERE(DEL_FLG = '0')";
	$fetchCNT = dbOpe::fetch($cnt_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	if($fetchCNT[0]["CNT"] < S5_1DBMAX_CNT):

		#-----------------------------------------------------------------
		#	VIEW_ORDER用の値を作成
		#		※現在登録されている記事データ中の最大VIEW_ORDER値を取得
		#		  それに1足したものを$view_orderに格納して使用
		#		※登録場所チェックが入っていたらVIEW_ORDER値を全て1繰上げ
		#		　$view_orderに1をセットし結果的に登録を一番上にする
		#-----------------------------------------------------------------

		if($_POST["regist_type"]=="new" && $ins_chk == 1){
			$vosql ="UPDATE ".S5_1PRODUCT_LST." SET VIEW_ORDER = VIEW_ORDER+1";
			if(!empty($vosql)){
				$db_result = dbOpe::regist($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB登録失敗しました<hr>{$db_result}");
			}
			$view_order = 1;
		}
		else{
			$vosql = "SELECT MAX(VIEW_ORDER) AS VO FROM ".S5_1PRODUCT_LST." WHERE(DEL_FLG = '0')";
			$fetchVO = dbOpe::fetch($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			$view_order = ($fetchVO[0]["VO"] + 1);
		}

		$sql = "
		INSERT INTO ".S5_1PRODUCT_LST."
			SET
				RES_ID = '$res_id',
				VIEW_ORDER = '$view_order',
				$sql_update_data
		";

	else:
		header("Location: ./");
	endif;

	break;
default:
	die("致命的エラー：登録フラグ（regist_type）が設定されていません");
endswitch;

// ＳＱＬを実行
if(!empty($sql)){
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB登録失敗しました<hr>{$db_result}");

}

#=================================================================================
# 共通処理；画像アップロード件数分変更処理
#=================================================================================

// 画像処理クラスimgOpeのインスタンス生成
$imgObj = new imgOpe(S5_1IMG_PATH);

// 設定ファイルの画像最大登録枚数分ループ
for($i=1;$i<=S5_1IMG_CNT;$i++):

// アップロードされた画像ファイルがあればアップロード処理
if(is_uploaded_file($_FILES['up_img']['tmp_name'][$i])){

	// アップされてきた画像のサイズを計る
		$size = getimagesize($_FILES['up_img']['tmp_name'][$i]);

	//画像サイズを調整
		$size_x = $ox[$i-1];//横の固定サイズ
		$size_y = $size[1]/($size[0]/$size_x);

	// 画像のアップロード：画像名は(記事ID_画像番号.jpg)
		$imgObj->setSize($size_x, $size_y);//横固定、縦可変型
		//$imgObj->setSize($ox[$i-1],$oy[$i-1]);
		//$imgObj->isFixed=true;

	if(!$imgObj->up($_FILES['up_img']['tmp_name'][$i],$for_imgname."_".$i)){
		exit("画像のアップロード処理に失敗しました。");
	}

}
endfor;

#=================================================================================
# 共通処理；資料ファイルアップロード・削除処理
#=================================================================================

	//前の新規登録、更新で使われたRES_IDを受け継ぎ名前にする
	$filename = $res_id;

	/////////////////////////////////////////////////////
	//ファイル削除にチェックがされている場合の処理
	if($del_pdf){//チェックがされているか判定

		// FILE削除
		if(file_exists(S5_1IMG_PATH.$filename.".".$old_extension)){
			unlink(S5_1IMG_PATH.$filename.".".$old_extension) or die("ファイルの削除に失敗しました。");
		}

		//削除したファイルデータを更新させる
		$sql = "
		UPDATE
			".S5_1PRODUCT_LST."
		SET
			PDF_FLG = '0',
			TYPE = '',
			SIZE = '',
			EXTENTION = ''
		WHERE
			(RES_ID = '$res_id')
		";
		if(!empty($sql)){
			$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			if($db_result)die("DB登録失敗しました<hr>{$db_result}");
		}
	}

	/////////////////////////////////////////////////////
	//ファイルの登録処理
	if((W3_DBMAX_CNT > $fetch_PDF_NUM[0]['CNT']) || (file_exists(S5_1IMG_PATH.$res_id.".".$old_extension))){//ファイルのアップ制限が超えてないかチェックを行う、また古いファイルを更新する場合も許可する

		//ファイルがアップロードされているかチェックを行う
		if(is_uploaded_file($_FILES['up_file_pdf']['tmp_name'])):

			// Upload：File名
				$pathinfo = pathinfo($_FILES['up_file_pdf']['name']);

			// 拡張子を取得
				$extension = strtolower($pathinfo['extension']);

			// ファイルのサイズとタイプを取得
				$size = $_FILES['up_file_pdf']['size'];
				$type = $_FILES['up_file_pdf']['type'];

			// 拡張子のチェック
			// ※mineでのチェックだとブラウザーまたはＰＣの環境によって出されるmineが違ってくるので
			switch($extension):
				case "pdf"://ＰＤＦファイル
					$upload = "yes";
					break;
				case "doc"://ワードファイル
					$upload = "yes";
					break;
				case "docx"://office2007ワードファイル
					$upload = "yes";
					break;
				case "xls"://エクセルファイル
					$upload = "yes";
					break;
				case "xlsx"://office2007エクセルファイル
					$upload = "yes";
					break;
				case "ppt"://パワーポイントファイル
					$upload = "yes";
					break;
				case "pptx"://office2007パワーポイントファイル
					$upload = "yes";
					break;

				case "wmv"://ムービーのファイル
					$upload = "yes";
					break;
				case "mp3"://音声ファイル
					$upload = "yes";
					break;
				default:
					$upload = "";
					exit("ファイルの形式に誤りがあります。");
			endswitch;

			//ファイルサイズが超えていないかチェック
			if($_FILES['up_file_pdf']['size'] < (LIMIT_SIZE * 1024 *1024)){

				//古いファイルを削除する（ファイルタイプが変更された場合の対応）
					if(file_exists(S5_1IMG_PATH.$res_id.".".$old_extension) ){
						unlink(S5_1IMG_PATH.$res_id.".".$old_extension) or die("ファイルの削除に失敗しました。");
					}

				//ファイルのアップロード処理
					if(!copy($_FILES['up_file_pdf']['tmp_name'],S5_1IMG_PATH.$filename.".".$extension)){
						exit("アップロード処理に失敗しました。");
					}else{
						//本番アップ時に権限が変わりパーミッションの変更ができない場合、
						//処理は失敗したままで次の処理へ行かせる。
						@chmod(S5_1IMG_PATH.$filename.".".$extension, 0666);
					}

				//登録した資料ファイルのデータを更新させる
					$sql = "
					UPDATE
						".S5_1PRODUCT_LST."
					SET
						PDF_FLG = '1',
						TYPE = '$type',
						SIZE = '$size',
						EXTENTION = '$extension'
					WHERE
						(RES_ID = '$res_id')
					";
					if(!empty($sql)){
						$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
						if($db_result)die("DB登録失敗しました<hr>{$db_result}");
					}

			}else{//ファイルのサイズ制限を超えている場合
				exit(LIMIT_SIZE."MB以上のファイルはアップロードできません。");
			}

		endif;
	}

?>
