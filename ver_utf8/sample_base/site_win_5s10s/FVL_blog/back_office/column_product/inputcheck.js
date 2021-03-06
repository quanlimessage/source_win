// JavaScript Document

/////////////////////////////////////
// 汎用確認メッセージ
/////////////////////////////////////
function ConfirmMsg(msg) {
  return (confirm(msg)) ? true : false;
}

/////////////////////////////////////////////////////////////////////////////////
// 未入力及び不正入力のチェック（※Safariのバグ（エスケープ文字認識）を回避）
/////////////////////////////////////////////////////////////////////////////////
function confirm_message(f) {

  // フラグの初期化
  var flg = false;
  var error_mes = "Error Message\r\n恐れ入りますが、下記の内容を確認してください。\r\n\r\n";

  // 未入力と不正入力のチェック

  /*
  if (!f.ca.value) {
    error_mes += "・カテゴリーを選択してください。\r\n";
    flg = true;
  }

  if (!f.title.value) {
    error_mes += "・タイトルを入力してください。\r\n";
    flg = true;
  }

  if (!f.content.value) {
    error_mes += "・本文を入力してください。\r\n";
    flg = true;
  }
  */

  // 判定
  if (flg) {
    // アラート表示して再入力を警告
    window.alert(error_mes);
    return false;
  } else {
    // 確認メッセージ
    return ConfirmMsg('入力いただいた内容で登録します。\nよろしいですか？');
  }

}
