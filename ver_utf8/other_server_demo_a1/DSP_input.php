<?php
/************************************************************************
  お問い合わせフォーム（POST渡しバージョン）
 View：入力画面	※デフォルトで表示する画面

************************************************************************/

// 不正アクセスチェック
if(!$accessChk){
	header("HTTP/1.0 404 Not Found");exit();
}

// HTTPヘッダー
utilLib::httpHeadersPrint("UTF-8",true,false,false,false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html401/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-language" content="ja">
<meta http-equiv="Content-script-type" content="text/javascript">
<meta http-equiv="Content-style-type" content="text/css">
<meta http-equiv="imagetoolbar" content="no">
<meta name="description" content="ＳＥＯワード">
<meta name="keywords" content="キーワード">
<meta name="robots" content="index,follow">
<title>Winシリーズ-更新プログラム|サンプルサイト|</title>
<link href="./css/base.css" rel="stylesheet" type="text/css">
<link href="./css/index.css" rel="stylesheet" type="text/css">
<link href="./css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="common.js"></script>
<script language="JavaScript" type="text/JavaScript" src="./JS/rollover.js"></script>
</head>

<body onLoad="MM_preloadImages('./image/menu_back_over.jpg')">
<div id="stage">
 <div id="content">

  <h1>ＳＥＯワード</h1>
	<h2><a href="./"><img src="./image/header.jpg" alt="" width="760" height="55"></a></h2>

	<ul id="menu">
		<li><b>メニュー</b></li>
		<li><a href="./">A1</a></li>
	</ul>

	<div id="main">
		<div id="index_image">
	<br>
	 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>お問い合わせ&nbsp;（Ａ１）</h5>
	<br>
		<table width="520px"  border="0" cellspacing="2" cellpadding="2" bgcolor="#999999">
		  <tr valign="top">
			<td bgcolor="#FFFFFF" align="left">
			* 個人情報の利用目的 ： お問い合わせ内容への回答のために利用いたします。<br>
			* 取得した個人情報は本人の同意無しに、目的以外では利用しません。<br>
			* 情報が漏洩しないよう対策を講じ従業員だけでなく委託業者も監督します。<br>
			* 本人の同意を得ずに第三者に情報を提供しません。<br>
			* 本人からの求めに応じ情報を開示します。<br>
			* 公開された個人情報が事実と異なる場合、訂正や削除に応じます。<br>
			* 個人情報の取扱いに関する苦情に対し、適切・迅速に対処します。 </td>
		  </tr>
		</table>

		  <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="form1" class="style5" onSubmit="return inputChk(this,false)">
			<table width="520px"  border="0" cellspacing="2" cellpadding="5" bgcolor="#999999">
			  <tr>
				<td>&nbsp;</td>
				<td align="left">当社へのお問合せは、下のフォームよりお願い致します。</td>
			   </tr>
			   <tr bgcolor="#FFFFFF" align="left">
				<td>名前<span style="color:crimson;">（必須）</span></td>
				<td><input name="name" type="text" id="name" size="30" style="ime-mode:active;" maxlength="60"></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>フリガナ<span style="color:crimson;">（必須）</span></td>
				<td><input name="kana" type="text" id="kana" size="30" style="ime-mode:active;" maxlength="60"></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>性別<span style="color:crimson;">（必須）</span></td>
				<td><input name="sex" type="radio" value="男" id="sex_m">	<label for="sex_m">男</label>

					<input name="sex" type="radio" value="女" id="sex_w">	<label for="sex_w">女</label></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>郵便番号<span style="color:crimson;">（必須）</span></td>
				<td>〒<input name="zip1" type="text" size="5" id="zip1" maxlength="3" style="ime-mode:disabled;">
						-
					  <input name="zip2" type="text" size="7" id="zip2" maxlength="4" style="ime-mode:disabled;">
                        </td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>ご住所<span style="color:crimson;">（必須）</span></td>
				<td>都道府県
                           <select name="state">
                              <option value="">▼選択してください▼</option>
                              <option value="北海道">北海道</option>
                              <option value="青森県">青森県</option>
                              <option value="岩手県">岩手県</option>
                              <option value="宮城県">宮城県</option>
                              <option value="秋田県">秋田県</option>
                              <option value="山形県">山形県</option>
                              <option value="福島県">福島県</option>
                              <option value="茨城県">茨城県</option>
                              <option value="栃木県">栃木県</option>
                              <option value="群馬県">群馬県</option>
                              <option value="埼玉県">埼玉県</option>
                              <option value="千葉県">千葉県</option>
                              <option value="東京都">東京都</option>
                              <option value="神奈川県">神奈川県</option>
                              <option value="山梨県">山梨県</option>
                              <option value="長野県">長野県</option>
                              <option value="新潟県">新潟県</option>
                              <option value="富山県">富山県</option>
                              <option value="石川県">石川県</option>
                              <option value="福井県">福井県</option>
                              <option value="岐阜県">岐阜県</option>
                              <option value="静岡県">静岡県</option>
                              <option value="愛知県">愛知県</option>
                              <option value="三重県">三重県</option>
                              <option value="滋賀県">滋賀県</option>
                              <option value="京都府">京都府</option>
                              <option value="大阪府">大阪府</option>
                              <option value="兵庫県">兵庫県</option>
                              <option value="奈良県">奈良県</option>
                              <option value="和歌山県">和歌山県</option>
                              <option value="鳥取県">鳥取県</option>
                              <option value="島根県">島根県</option>
                              <option value="岡山県">岡山県</option>
                              <option value="広島県">広島県</option>
                              <option value="山口県">山口県</option>
                              <option value="徳島県">徳島県</option>
                              <option value="香川県">香川県</option>
                              <option value="愛媛県">愛媛県</option>
                              <option value="高知県">高知県</option>
                              <option value="福岡県">福岡県</option>
                              <option value="佐賀県">佐賀県</option>
                              <option value="長崎県">長崎県</option>
                              <option value="熊本県">熊本県</option>
                              <option value="大分県">大分県</option>
                              <option value="宮崎県">宮崎県</option>
                              <option value="鹿児島県">鹿児島県</option>
                              <option value="沖縄県">沖縄県</option>
                            </select><br>
				市町村・番地・マンション名など<br>
				<input name="address" type="text" size="50" id="address" maxlength="400" style="ime-mode:active;">
                </td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>電話番号</td>
				<td><input name="tel" type="text" id="tel" maxlength="30" size="20" style="ime-mode:disabled;"></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>FAX番号</td>
				<td><input name="fax" type="text" id="fax" maxlength="30" size="20" style="ime-mode:disabled;"></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>メールアドレス<span style="color:crimson;">（必須）</span></td>
				<td><input name="email" type="text" id="email" size="40" maxlength="200" style="ime-mode:disabled;"></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>コメント<span style="color:crimson;">（必須）</span></td>
				<td><textarea name="comment" cols="45" rows="10" id="comment" style="ime-mode:active;"></textarea></td>
			  </tr>
			  <tr bgcolor="#FFFFFF" align="left">
				<td>&nbsp;</td>
				<td>
					<input name="Submit" type="submit" class="font1" value="確認画面へ&nbsp;&gt;&gt;">

					<input type="hidden" name="action" value="confirm">
				</td>

			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td align="left">※&nbsp;各項目を入力の上、”確認画面へ”のボタンを押してください。</td>
			  </tr>
			</table>
			</form>

	</div>
</div>
	<div id="footer">Copyright(c)2005 ○○○.All Rights Reserved.</div>

</div>
</div>

<div id="banner"><a href="http://www.all-internet.jp/" target="_blank"><img src="./image/banner.gif" alt="ホームページ制作はオールインターネット"></a></div>

</body>

</html>