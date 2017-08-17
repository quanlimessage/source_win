<?php
/**********************************************************************************************************
★データベース操作クラスライブラリ（ＳＱＬ文とＤＢ情報を設定するだけで自動的に実行→結果取得する）

 	・MySQL版		クラス名：dbOpe(ユーザー,パスワード,データベース名);
	・PEAR版	クラス名：dbOpeP(DSN情報);	※DSN情報は定数化しておく事を推奨
　の２つのクラス構成です。※どちらも使用方法は同じです。
	注）：PEAR版はクラス内で“DB.php”を読み込んで使用しています。
		  使用時には同一ファイル内で“DB.php”を読み込んでないか注意して使用する事（２重読み込みはエラーとなる）

★メソッド
	◆select文（fetch_rowの取得結果がある）のSQL文を発行したい時に使用するメソッド

		※MySQL版
		fetch("ＳＱＬ文",[ユーザー],[パスワード],[ＤＢ名],[バックスラッシュ削除フラグ]);
		（[]内は直接クラスメソッドへアクセスした場合に記述する）
			注：registメソッドと違い、ＳＱＬ文の配列対応していないので注意！ 実行は１回につき１結果

		※PEAR版
		fetch("ＳＱＬ文");
			注：PEAR版は直接クラスメソッドへアクセスは不可。必ずインスタンスを作成してメソッドを実行する事
			注：registメソッドと違い、ＳＱＬ文の配列対応していないので注意！ 実行は１回につき１結果

	◆insert into文、update文等、fetch_rowの取得結果がないSQL文を発行したい時に使用するメソッド

		※MySQL版
		regist("単一 or 配列のＳＱＬ文",[ユーザー],[パスワード],[ＤＢ名],[メタ文字エスケープフラグ]);
			（[]内は直接クラスメソッドへアクセスした場合に記述する）

		※PEAR版
		regist("単一 or 配列のＳＱＬ文");

			注：PEAR版は直接クラスメソッドへアクセスは不可。必ずインスタンスを作成してメソッドを実行する事

	◆順序（オブジェクトシーケンス）の次の番号を取得するメソッド（PEAR版のみ）

		getNextId("オブジェクトシーケンス名");	※戻り値：オブジェクトシーケンスの次の番号

		※PEARのnextIdメソッドを使用できるようにするために作成。
		※直接クラスメソッドへアクセスは不可。必ずインスタンスを作成してメソッドを実行する事

 2004/8/11	Yossee
**********************************************************************************************************/

###########################################################################################################
#
#	MySQL版データベース操作クラス		dbOpe(ユーザー,パスワード,データベース名)
#		※直接クラスメソッドへアクセスしての使用は可能です。「 dbOpe::メソッド名() 」
#

###########################################################################################################
class dbOpe{

var $db_user;	/* DB接続ユーザー名 */
var $db_pass;	/* DB接続パスワード */
var $db_name;	/* 使用するＤＢ名 */
var $db_host;	/* 使用するホスト名（ここでは殆どlocalhost） */

/* バックスラッシュ削除（デフォルトはTRUE）※fetchメソッドのみ使用 */
var $escape_flg;

/* コンストラクタ */
function dbOpe($u,$p,$n,$h,$e=true){
	if(empty($u)||empty($p)||empty($n)||empty($h)):
		die("引数が未設定のため、強制終了しました。<br>“ユーザー”、“パスワード”、“ＤＢ名”、“ホスト名”の順で引数に設定してください。");
	else:
		$this->db_user = $u;
		$this->db_pass = $p;
		$this->db_name = $n;
		$this->db_host = $h;
		$this->escape_flg = $e;
	endif;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//	◆取得結果が発生するSQL文（select文）を発行したい時に使用するメソッド
//
//		fetch("ＳＱＬ文",[ユーザー],[パスワード],[ＤＢ名],[バックスラッシュ除去フラグ]);
//		（[]内は直接クラスメソッドへアクセスした場合に記述する）
//

//		※戻り値：取得結果を多次元配列にて返す（ない場合は空）。エラー時はdie()にて強制終了する
//		※取得結果の多次元配列は、1番目の要素にレコード番号、2番目の要素名にカラム名を指定する
//			例：$fetch_result[0]["ID"]	$fetch_result[0]["NAME"]
//				$fetch_result[1]["ID"]	$fetch_result[1]["NAME"]
//
//		※registメソッドと違い、ＳＱＬ文の配列対応していないので注意！ ※実行は１回につき１結果
//
//		●使用例：

//		$db_ope = new dbOpe(DBUSER,DBPASS,DBNAME,DBHOST);
//		$array_result = $db_ope->fetch($sql);
//

//			※直接クラスメソッドからコールする場合は下記のように記述
//			$array_result = dbOpe::fetch($sql,DBUSER,DBPASS,DBNAME,DBHOST);
//
//		※取得結果を表示する（forは１番目の要素、foreachは２番目の要素をそれぞれループし、keyを小文字に変換）

//		for($i=0;$i<count($array_result);$i++){
//			foreach($array_result[$i] as $k=>$e)echo "KEY：".mb_strtolower(&$k)."／VALUE：{$e}<br>\n";
//			echo "<hr>";
//		}
//		echo "件数：{$i}件";
//
///////////////////////////////////////////////////////////////////////////////////////////////////////////
function fetch($sql = "",$user = "",$pass = "",$name = "",$host = "",$escape = true){

	/* 初期化しておく変数をセット */
	$error_mes = false;			# エラーメッセージ
	$fetch_result = array();	# 結果取得用の配列

	#--------------------------------------------------------------------------------------
	# 引数チェック	※第一引数以外は直接クラスメソッドからコールする場合のエラー処理
	#--------------------------------------------------------------------------------------
	/* SQL文が設定されているかチェック */
	if(empty($sql))$error_mes .= "SQL文が設定されていません<br>\n";

	/* ユーザーをチェック */
	if(!$user){
		if($this->db_user):
			$user = $this->db_user;
		else:
			$error_mes .= "ユーザーが設定されていません<br>\n";
		endif;
	}

	/* パスワードをチェック */
	if(!$pass){
		if($this->db_pass):
			$pass = $this->db_pass;
		else:
			$error_mes .= "パスワードが設定されていません<br>\n";
		endif;
	}

	/* ＤＢ名をチェック */
	if(!$name){
		if($this->db_name):
			$name = $this->db_name;
		else:
			$error_mes .= "データベースが設定されていません<br>\n";
		endif;
	}

	/* ホスト名をチェック */
	if(!$host){
		if($this->db_host):
			$host = $this->db_host;
		else:
			$error_mes .= "ホストが設定されていません<br>\n";
		endif;
	}

	/* 文字列処理フラグ（デフォルトはtrueに設定）
		※メンバ変数もfalseなら処理しない設定なのでfalseにする */
	if(!$escape){
		if($this->escape_flg):
			$escape = $this->escape_flg;
		else:
			$escape = false;
		endif;
	}

	/* エラーがあったら強制終了 */

	if($error_mes)die("<p>以下の理由で強制終了しました</p>\n{$error_mes}<p>“ＳＱＬ文”、“ユーザー”、“パスワード”、“ＤＢ名”、“ホスト名”の順で設定し直してください。</p>\n");

	#------------------------------------------------------------------------------------------------
	# DBへ接続 ～ SQL実行 ～ 結果による条件分岐まで
	#------------------------------------------------------------------------------------------------
	// 接続
	$con = @mysql_connect($host,$user,$pass);
	if(!$con||mysql_error()){
		$mysql_error = mysql_error();
		die("接続時にエラー発生！！<br>fetch Error!!（Logon）<br>{$mysql_error}");
	}

	// DBの選択
	mysql_set_charset('utf8');
	$select_db_result = @mysql_select_db($name);
	if(!$select_db_result||mysql_error()){
		$mysql_error = mysql_error();
		die("DB選択時にエラー発生！！<br>fetch Error!!（DB Select）<br>{$mysql_error}");
	}

	// SQL文を実行
	$query_result = @mysql_query($sql);
	if(!$query_result||mysql_error()){
		$mysql_error = mysql_error();
		die("クエリー実行時にエラー発生！！<br>fetch Error!!（Query）<br>{$mysql_error}");
	}
	else{
		//レコード件数とフィールド件数を取得しておく
		$num_rows = mysql_num_rows($query_result);		// レコード数

		$num_fields = mysql_num_fields($query_result);	// フィールド数
	}

	#-----------------------------------------------------------------------------------------------
	# 取得結果を多次元配列に格納→メモリ開放→接続を閉じる→取得結果を戻り値として返す
	#
	#	※カラムの件数分自動格納（多次元配列を使って擬似TABLEのようにして取得）
	#	※取得結果の多次元配列は、1番目の要素にレコード番号、2番目の要素名にカラム名を指定する
	#		例：$fetch_result[0]["ID"]	$fetch_result[0]["NAME"]	$fetch_result[0]["AGE"]
	#			$fetch_result[1]["ID"]	$fetch_result[1]["NAME"]	$fetch_result[1]["AGE"]
	#-----------------------------------------------------------------------------------------------
	for($i=0;$i<$num_rows;$i++):

		$field_data = mysql_fetch_array($query_result,MYSQL_ASSOC);
		for($j=0;$j<$num_fields;$j++){

			$field_name = mysql_field_name($query_result,$j);

			if($escape):
				$fetch_result[$i][$field_name] = stripslashes($field_data[$field_name]);
			else:
				$fetch_result[$i][$field_name] = $field_data[$field_name];
			endif;
		}

	endfor;

	mysql_free_result($query_result);
	mysql_close($con);

	return $fetch_result;

}

/////////////////////////////////////////////////////////////////////////////////////////////////
//	◆insert into文、update文等、fetch_rowの取得結果がないSQL文を発行したい時に使用するメソッド
//
//		regist("単一 or 配列のＳＱＬ文",[ユーザー],[パスワード],[ＤＢ名]);
//		（[]内は直接クラスメソッドへアクセスした場合に記述する）
//
//		※複数のＳＱＬ文に対応（配列にそれぞれ実行したいＳＱＬ文を設定）
//		※エラーがあった場合は戻り値としてエラーメッセージを返す（ない場合はvoidとする）
//
//		●使用例：

//		$db_ope = new dbOpe(DBUSER_T,DBPASS_T,DBNAME_T);
//		$result = $db_ope->regist($sql[0]);
//

//			※直接クラスメソッドからコールする場合は下記のように記述
//			$result = dbOpe::regist($sql[0],DBUSER_T,DBPASS_T,DBNAME_T);
//

//		エラーメッセージがあれば$resultにエラーメッセージが格納（結果失敗時）
//		$result = ($result)?$result:"ＤＢへデータを登録しました（成功です！）";
//		echo $result;
//
/////////////////////////////////////////////////////////////////////////////////////////////////
function regist($sql = "",$user = "",$pass = "",$name = "",$host = ""){

	/* 初期化しておく変数をセット */
	$error_mes = false;		# エラーメッセージ
	$array_sql = array();	# SQL文格納の配列

	#--------------------------------------------------------------------------------------
	# 引数チェック	※第一引数以外は直接クラスメソッドからコールする場合のエラー処理
	#--------------------------------------------------------------------------------------
	/* SQL文が設定されているかチェック */
	if(!empty($sql)):

		if(is_array($sql)):
			$array_sql = $sql;
		else:
			$array_sql[0] = $sql;
		endif;

	else:
		$error_mes .= "SQL文が設定されていません<br>\n";
	endif;

	/* ユーザーをチェック */
	if(!$user){
		if($this->db_user):
			$user = $this->db_user;
		else:
			$error_mes .= "ユーザーが設定されていません<br>\n";
		endif;
	}

	/* パスワードをチェック */
	if(!$pass){
		if($this->db_pass):
			$pass = $this->db_pass;
		else:
			$error_mes .= "パスワードが設定されていません<br>\n";
		endif;
	}

	/* ＤＢ名をチェック */
	if(!$name){
		if($this->db_name):
			$name = $this->db_name;
		else:
			$error_mes .= "データベースが設定されていません<br>\n";
		endif;
	}

	/* ホスト名をチェック */
	if(!$host){
		if($this->db_host):
			$host = $this->db_host;
		else:
			$error_mes .= "ホストが設定されていません<br>\n";
		endif;
	}

	/* エラーがあったら強制終了 */

	if($error_mes)die("<p>以下の理由で強制終了しました</p>\n{$error_mes}<p>“ＳＱＬ文”、“ユーザー”、“パスワード”、“ＤＢ名”、“ホスト名”の順で設定し直してください。</p>\n");

	#------------------------------------------------------------------------------------------------
	# DBへ接続 ～ SQL実行 ～ 結果による条件分岐まで
	#------------------------------------------------------------------------------------------------
	// 接続
	$con = @mysql_connect($host,$user,$pass);
	if(!$con||mysql_error()){
		$mysql_error = mysql_error();
		die("接続時にエラー発生！！<br>fetch Error!!（Logon）<br>{$mysql_error}");
	}

	// DBの選択
	mysql_set_charset('utf8');
	$select_db_result = @mysql_select_db($name);
	if(!$select_db_result||mysql_error()){
		$mysql_error = mysql_error();
		die("DB選択時にエラー発生！！<br>fetch Error!!（DB Select）<br>{$mysql_error}");
	}

	/* SQL文が格納されている配列の件数分SQLを実行する ※エラーがあればメッセージ格納 */
	foreach($array_sql as $k => $e):

		// SQL文を実行	※失敗時の処理をいづれやっておかねばならない。。。
		$query_result = @mysql_query($e);
		if(!$query_result||mysql_error()){
			$mysql_error = mysql_error();
			$error_mes .= "クエリー実行時にエラーが発生しました（SQLの{$k}番目）。<br>{$mysql_error}";
		}

	endforeach;

	/* メモリを開放→接続を閉じ、エラーがあれば戻り値として戻す */
	mysql_close($con);

	if($error_mes)return $error_mes;

}

}/* クラス“dbOpe”の終了 */

###########################################################################################################
#
#	PEAR版データベース操作クラス		dbOpeP(DSN情報);
#		※必ずnew演算子でインスタンスを作成してメソッドを実行する事！
#		  （直接クラスメソッドへアクセスするとエラーになる）
#

###########################################################################################################
class dbOpeP{

var $pear_dsn;	/* PEARのDSN情報	※PDC_sysini.phpに定数化してあるので、それを使用する */
var $db_cls;	/* PEARのインスタンス（オブジェクト） */

/* バックスラッシュ削除（デフォルトはTRUE）※fetchメソッドのみ使用 */
var $escape_flg;

/* コンストラクタ */
function dbOpeP($d,$e=true){

require_once("DB.php");

	if(empty($d)):
		die("引数が未設定のため、強制終了しました。<br>引数に“ＤＳＮ情報”を設定してください。");
	else:
		$this->pear_dsn = $d;
		$this->db_cls = new DB();
	endif;

	$this->escape_flg = $e;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
//	◆取得結果が発生するSQL文（select文）を発行したい時に使用するメソッド
//
//		fetch("ＳＱＬ文");
//		（PEAR版は直接クラスメソッドへアクセスは不可。必ずインスタンスを作成してメソッドを実行する事）
//

//		※戻り値：取得結果を多次元配列にて返す（ない場合は空）。エラー時はdie()にて強制終了する
//		※取得結果の多次元配列は、1番目の要素にレコード番号、2番目の要素名にカラム名を指定する
//			例：$fetch_result[0]["ID"]	$fetch_result[0]["NAME"]
//				$fetch_result[1]["ID"]	$fetch_result[1]["NAME"]
//
//		※registメソッドと違い、ＳＱＬ文の配列対応していないので注意！ ※実行は１回につき１結果
//
//		●使用例：

//		$pear_db = new dbOpeP(PEAR_DSN_T);
//		$array_result = $pear_db->fetch($sql);
//
//		※取得結果を表示する（forは１番目の要素、foreachは２番目の要素をそれぞれループし、keyを小文字に変換）

//		for($i=0;$i<count($array_result);$i++){
//			foreach($array_result[$i] as $k=>$e)echo "KEY：".mb_strtolower(&$k)."／VALUE：{$e}<br>\n";
//			echo "<hr>";
//		}
//		echo "件数：{$i}件";
//
///////////////////////////////////////////////////////////////////////////////////////////////////////////
function fetch($sql = "",$escape = true){

	/* 初期化しておく変数をセット */
	$error_mes = false;			# エラーメッセージ
	$fetch_result = array();	# 結果取得用の配列
	$db = $this->db_cls;	# インスタンスを別の箱に移す（$this->db_cls->connect()だとエラーになるので。。。）

	#--------------------------------------------------------------------------------------
	# 引数チェック	※第一引数以外は直接クラスメソッドからコールする場合のエラー処理
	#--------------------------------------------------------------------------------------
	/* SQL文が設定されているかチェック（エラーがあったら強制終了） */
	if(empty($sql))die("<p>SQL文が設定されていません</p>\n<p>引数に“ＳＱＬ文”を設定し直してください。</p>\n");

	/* 文字列処理フラグ（デフォルトはtrueに設定）
		※メンバ変数もfalseなら処理しない設定なのでfalseにする */
	if(!$escape){
		if($this->escape_flg):
			$escape = $this->escape_flg;
		else:
			$escape = false;
		endif;
	}

	#------------------------------------------------------------------------------------------------
	# DBへ接続 ～ SQL実行 ～ 結果による条件分岐まで
	#------------------------------------------------------------------------------------------------
	/* 接続 */
	$db_con = $db->connect($this->pear_dsn);
	if($db->isError($db_con)){
		$error_mes .= "接続時にエラー発生！！<br>fetch Error!!（connect）<br>\n".$db->errorMessage($db_con)."<br>\n";
		die($error_mes);
	}

	/* SQL実行 */
	$db_result = $db_con->query($sql);
	if($db->isError($db_result)){
		$error_mes .= "致命的エラー発生！！<br>fetch Error!!（SQL Execute）<br>\n".$db->errorMessage($db_result)."<br>\n";
		die($error_mes);
	}

	#-----------------------------------------------------------------------------------------------
	# 取得結果を多次元配列に格納→メモリ開放→接続を閉じる→取得結果を戻り値として返す
	#
	#	※カラムの件数分自動格納（多次元配列を使って擬似TABLEのようにして取得）
	#	※取得結果の多次元配列は、1番目の要素にレコード番号、2番目の要素名にカラム名を指定する
	#		例：$fetch_result[0]["ID"]	$fetch_result[0]["NAME"]	$fetch_result[0]["AGE"]
	#			$fetch_result[1]["ID"]	$fetch_result[1]["NAME"]	$fetch_result[1]["AGE"]
	#-----------------------------------------------------------------------------------------------
	$i=0;
	while($col = $db_result->fetchRow(DB_FETCHMODE_ASSOC)){
		foreach($col as $k=>$e){

			if($escape):
				$fetch_result[$i][$k] = stripslashes($e);
			else:
				$fetch_result[$i][$k] = $e;
			endif;

		}

		$i++;
	}
	$db_result->free();
	$db_con->disconnect();

	return $fetch_result;

}

////////////////////////////////////////////////////////////////////////////////////////////////////////
//	◆insert into文、update文等、fetch_rowの取得結果がないSQL文を発行したい時に使用するメソッド
//
//		regist("単一 or 配列のＳＱＬ文");
//		（PEAR版は直接クラスメソッドへアクセスは不可。必ずインスタンスを作成してメソッドを実行する事）
//
//		※複数のＳＱＬ文に対応（配列にそれぞれ実行したいＳＱＬ文を設定）
//		※エラーがあった場合は戻り値としてエラーメッセージを返す（ない場合はvoidとする）
//
//		●使用例：

//		$pear_db = new dbOpeP(PEAR_DSN_T);
//		$result = $pear_db->regist($sql[0]);
//

//		エラーメッセージがあれば$resultにエラーメッセージが格納（結果失敗時）
//		$result = ($result)?$result:"ＤＢへデータを登録しました（成功です！）";
//		echo $result;
////////////////////////////////////////////////////////////////////////////////////////////////////////
function regist($sql = ""){

	/* 初期化しておく変数をセット */
	$error_mes = false;		# エラーメッセージ
	$array_sql = array();	# SQL文格納の配列
	$db = $this->db_cls;	# インスタンスを別の箱に移す（$this->db_cls->connect()だとエラーになるので。。。）

	/* 引数チェック（SQL文が設定されているかチェック）※エラーがあったら強制終了 */
	if(!empty($sql)):

		if(is_array($sql)):
			$array_sql = $sql;
		else:
			$array_sql[0] = $sql;
		endif;

	else:
		die("<p>SQL文が設定されていません</p>\n<p>引数に“ＳＱＬ文”を設定してください。</p>\n");
	endif;

	#------------------------------------------------------------------------------------------------
	# DBへ接続 ～ SQL実行 ～ 結果による条件分岐まで
	#------------------------------------------------------------------------------------------------
	/* 接続 */
	$db_con = $db->connect($this->pear_dsn);
	if($db->isError($db_con)):
		$error_mes .= "データベース接続時にエラーが発生しました。".$db->errorMessage($db_con)."<br>\n";
	else:
		$db_con->autoCommit(false);	# オートコミットをオフにする（失敗したときの条件分岐にするため）
	endif;

	/* SQL文が格納されている配列の件数分SQLを実行する ※エラーがあればメッセージ格納 */
	foreach($array_sql as $k => $e):

		/* SQL実行 */
		$db_result = $db_con->query($e);
		if($db->isError($db_result)):
			$db_con->rollback();
			$error_mes .= "致命的エラー発生！！<br>SQL Execute Error!!（{$k}番目）".$db->errorMessage($db_result)."<br>\n";
		else:
			$db_con->commit();
		endif;

	endforeach;

	/* 接続を閉じ、エラーがあれば戻り値として戻す */
	$db_con->disconnect();
	if($error_mes)return $error_mes;

}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	◆順序（オブジェクトシーケンス）の次の番号を取得するメソッド
//
//		getNextId("オブジェクトシーケンス名");	※戻り値：オブジェクトシーケンスの次の番号

//
//		※PEAR版のみ（PEARのnextIdメソッドを使用できるようにするために作成）。
//		※直接クラスメソッドへアクセスは不可。必ずインスタンスを作成してメソッドを実行する事
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getNextId($obj_seq = ""){

	$db = $this->db_cls;	# インスタンスを別の箱に移す（$this->db_cls->connect()だとエラーになるので。。。）

	/* オブジェクトシーケンスが設定されているかチェック（エラーがあったら強制終了） */
	if(empty($obj_seq))die("<p>引数が設定されていません</p>\n<p>引数に“オブジェクトシーケンス”を設定してください。</p>\n");

	/* 接続 */
	$db_con = $db->connect($this->pear_dsn);
	if($db->isError($db_con)){
		$error_mes = "接続時にエラー発生！！<br>fetch Error!!（connect）<br>\n".$db->errorMessage($db_con)."<br>\n";
		die($error_mes);
	}

	$seq = $db_con->nextId($obj_seq);
	$db_con->disconnect();

	return $seq;
}

/* クラス“dbOpeP”の終了 */
}
?>
