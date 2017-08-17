<?php
/*******************************************************************************
会員メール配信 バックオフィス（MySQL対応版）
メインコントローラー

Logic：指定された検索条件を元にＤＢより情報を取得

*******************************************************************************/
#---------------------------------------------------------------
# 不正アクセスチェック（直接このファイルにアクセスした場合）
#	※厳しく行う場合はIDとPWも一致するかまで行う
#---------------------------------------------------------------
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
// 不正アクセスチェック（直接このファイルにアクセスした場合）
if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#============================================================================
# POSTデータの受取と文字列処理（共通処理）	※汎用処理クラスライブラリを使用
#============================================================================
// タグ、空白の除去／危険文字無効化／“\”を取る／半角カナを全角に変換
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

// MySQLにおいて危険文字をエスケープしておく
$title = utilLib::strRep($SUBJECT,5);
$content = utilLib::strRep($COMMENT,5);

//「|」をデリミタとし、メンバーIDを一つの文字列にする
for($i=0;$i<count($fetchCustList);$i++){
	$member_id.= ($i==0)?$fetchCustList[$i][MEMBER_ID]:"|".$fetchCustList[$i][MEMBER_ID];
}

$member_id = addslashes($member_id);

//送信数
$send_num = count($fetchCustList);

//res_idを作成
$res_id = $makeID();

//DBに送信メールの情報を格納
	$sql = "
		INSERT INTO MAIL_HISTORY(
			RES_ID,
			TITLE,
			CONTENT,
			MEMBER_ID,
			SEND_NUMBER,
			INS_DATE
		)
		VALUES(
			'$res_id',
			'$title',
			'$content',
			'$member_id',
			'$send_num',
			NOW()
		)";

// ＳＱＬを実行
$PDO -> regist($sql);
?>
