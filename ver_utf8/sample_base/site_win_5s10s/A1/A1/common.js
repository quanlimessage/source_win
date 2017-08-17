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
function inputChk(f, confirm_flg) {

    // フラグの初期化
    var flg = false;
    var error_mes = "Error Message\r\n恐れ入りますが、下記の内容を確認してください。\r\n\r\n";

    // 未入力と不正入力のチェック
    if (f.action.value == "confirm") { //入力画面用だけに処理をする

        var flag = 1;
        for (var i = 0; i < f.elements.length; i++) { //全項目数を数える
            if ((f.elements[i].name == 'inq[]') && (f.elements[i].checked)) { //条件に一致するnameを探す
                flag = 0;
                break;

            }
        }

        if (flag == 1) {
            error_mes += "・お問い合わせ項目を選択してください。\r\n";
            flg = true;
        }
    }

    if (!f.name.value) {
        error_mes += "・お名前を入力してください。\r\n";
        flg = true;
    }

    if (!f.kana.value) {
        error_mes += "・フリガナを入力してください。\r\n";
        flg = true;
    }

    if (!f.sex.value) {
        if (!f.sex[0].checked && !f.sex[1].checked) {
            error_mes += "・性別を選択してください。\r\n";
            flg = true;
        }
    }

    if (!f.zip1.value) {
        error_mes += "・郵便番号（左）を入力してください。\r\n";
        flg = true;
    }

    if (!f.zip2.value) {
        error_mes += "・郵便番号（右）を入力してください。\r\n";
        flg = true;
    }

    if (!f.state.value) {
        error_mes += "・都道府県を選択してください。\r\n";
        flg = true;
    }

    if (!f.address.value) {
        error_mes += "・ご住所を入力してください。\r\n";
        flg = true;
    }

    // メールアドレス欄が１つならfalse。２つならtrue。
    var email_flg = false;
    if (!f.email.value) {
        error_mes += "・メールアドレスを入力してください。\r\n";
        flg = true;
    } else if (!f.email.value.match(/^[^@]+@[^.]+\..+/)) {
        error_mes += "・メールアドレスの形式に誤りがあります。\r\n";
        flg = true;
    } else {
        email_flg = true;
    }

    if (email_flg) {
        var c_email_flg = false;
        if (!f.c_email.value) {
            error_mes += "・メールアドレス（確認用）を入力してください。\r\n";
            flg = true;
        } else if (!f.c_email.value.match(/^[^@]+@[^.]+\..+/)) {
            error_mes += "・メールアドレス（確認用）の形式に誤りがあります。\r\n";
            flg = true;
        } else {
            c_email_flg = true;
        }

        if (email_flg && c_email_flg) {
            if (f.email.value != f.c_email.value) {
                error_mes += "・メールアドレスとメールアドレス（確認用）が一致しません。\r\n";
                flg = true;
            }
        }
    }

    if (!f.comment.value) {
        error_mes += "・コメントを入力してください。\r\n";
        flg = true;
    }

    // 判定
    if (flg) {
        // アラート表示して再入力を警告
        window.alert(error_mes);
        return false;
    } else {

        // 確認メッセージ
        if (confirm_flg) {
            return ConfirmMsg('入力いただいた内容で送信します。\nよろしいですか？');
        }
        return true;
    }

}
