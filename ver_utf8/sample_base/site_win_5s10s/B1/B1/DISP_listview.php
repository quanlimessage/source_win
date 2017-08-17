<?php
/*******************************************************************************
Vertex

掲示板

*******************************************************************************/

if(!$injustice_access_chk){
	header("HTTP/1.0 404 Not Found");exit();
}

#-------------------------------------------------------------
# HTTPヘッダーを出力
#	文字コードと言語：utf8で日本語
#	他：ＪＳとＣＳＳの設定／キャッシュ拒否／ロボット拒否
#-------------------------------------------------------------
//utilLib::httpHeadersPrint("UTF-8",true,true,true,true);
utilLib::httpHeadersPrint("UTF-8",true,true,false,false);

$NEXT = $page + 1;

$PREVIOUS = $page - 1;
// CHECK ALL DATA
$TCNT = $fetchCNT[0]["CNT"];
// COUNT ALL DATA
$TOTLE_PAGES = ceil($TCNT/BBS_MAX);

// SET DISPLAY
if($page > 1){
	$PREVIOUS_PAGE1 = "<a href='./?page={$PREVIOUS}'>";
	$PREVIOUS_PAGE2 = "</a>";
}else{
	$PREVIOUS_PAGE1 = "";
	$PREVIOUS_PAGE2 = "";
}

if($TOTLE_PAGES > $page){
	$NEXT_PAGE1 = "<a href='./?page={$NEXT}'>";
	$NEXT_PAGE2 = "</a>";
}else{
	$NEXT_PAGE1 = "";
	$NEXT_PAGE2 = "";
}
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
<link href="../css/base.css" rel="stylesheet" type="text/css">
<link href="../css/index.css" rel="stylesheet" type="text/css">
<link href="../css/main.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript" src="input_check.js"></script>
<script language="JavaScript" type="text/JavaScript" src="../JS/rollover.js"></script>
</head>

<body onLoad="MM_preloadImages('../image/menu_back_over.jpg')">
<div id="stage">
<div id="content">

		<h1>ＳＥＯワード</h1>
	<h2><a href="../"><img src="../image/header.jpg" alt="" width="760" height="55"></a></h2>

	<ul id="menu">

	</ul>

	<div id="main">
		<div id="index_image">
	<br>
	 <h5>Winシリーズ★更新プログラム&nbsp;サンプルサイト10･20･30<br>掲示板&nbsp;（ＢＢＳ）</h5>
	<!-- ここから掲示板表示 -->
  <table width="70%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="70%" align="center" valign="top">

		  <form action="./" method="post" enctype="multipart/form-data" name="commentForm" onSubmit="return inputChk<?php echo ($master_id)?"2":"1";?>(this);">
            <br>

          <table width="530" border="1" cellpadding="4" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
            <tr>

              <td align="center" bgcolor="#CCCCCC"><strong>掲示板のルール</strong></td>
            </tr>
            <tr>
              <td bgcolor="#FFFFFF" align="left"><p>インターネットは開かれた巨大ネットワークです。 どのような内容であれ、大勢の人の目にさらされる公共の場所です。

                  皆さんの使っている端末から個人の情報を簡単にさらけ出してしまうと、どのような形で悪用されてしまうか分かりません。 十分に注意してください。</p>
                <p>掲示板への書込みは、個人個人が責任をもって発言しましょう。 他人の誹謗中傷などの書込みは禁止しております。具体的には、</p>
                <p>●人の悪口を言う。<br>
                  ●嘘やデマなど人身を惑わすことを書込む。<br>
                  ●個人名や電話番号など個人情報を本人に無断で公開する。</p>
                <p>他人の迷惑になるような行ためは法により罰せられます。 悪質な場合には調査の上、警視庁に資料を提出する場合があります。 くれぐれも、他人に迷惑をかけるような行ためはやめましょう。<br>
                </p></td>
            </tr>
          </table>

            <?php echo ($error_mes)?"<br><b><font color=\"#AA0000\" size=\"3\">".$error_mes."</div></b><br>":"";?>
          <table width="530" border="1" cellpadding="4" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
            <tr>

              <td width="15%" bgcolor="#999999"> <strong><div align="right">お名前</div></strong></td>
              <td width="85%" bgcolor="#FFFFFF" align="left"> <input type="text" name="name" size="30" style="ime-mode:active;" value="<?php echo (!$reg_ed && $name)?$name:"";?>">

              </td>
            </tr>
            <tr>

              <td bgcolor="#999999"> <div align="right"><strong>E-mail</strong></div></td>
              <td bgcolor="#FFFFFF" align="left"> <input name="email" type="text" id="email" style="ime-mode:disabled;" value="<?php echo (!$reg_ed && $email)?$email:"";?>" size="40">
              </td>
            </tr>
            <?php if(!$master_id){?>
			<tr>

              <td bgcolor="#999999"> <div align="right"><strong>タイトル</strong></div></td>
              <td bgcolor="#FFFFFF" align="left"> <input name="title" type="text" id="title" size="48" style="ime-mode:active;" value="<?php echo (!$reg_ed && $title)?$title:"";?>">
              </td>
            </tr>
			<?php } ?>
            <tr>

              <td bgcolor="#999999"> <div align="right"><strong>本文</strong></div></td>
              <td bgcolor="#FFFFFF" align="left"> <textarea name="comment" cols="40" rows="5" wrap="VIRTUAL" id="comment" style="ime-mode:active;" onkeydown="keyDownFun()"><?php echo (!$reg_ed && $comment)?$comment:"";?></textarea>
              </td>
            </tr>
            <tr>

              <td bgcolor="#999999"> <div align="right"><strong>URL</strong></div></td>
              <td bgcolor="#FFFFFF" align="left"> <input name="url" type="text" id="url" style="ime-mode:disabled;" value="<?php echo (!$reg_ed && $url)?$url:"";?>" size="57">
              </td>
            </tr>
            <tr bgcolor="#CCCCCC">

              <td colspan="2"> <div align="right">

                  <input name="Submit" type="submit" class="submit" value="<?php echo ($master_id)?"返信投稿":"　送信　";?>">
					<input type="hidden" name="status" value="completion">
					<input type="hidden" name="regist_type" value="<?php echo ($master_id)?"res_new":"new";?>">
					<?php if($master_id){?><input type="hidden" name="master_id" value="<?php echo $master_id;?>"><?php }?>
					<input type="hidden" name="page" value="<?php echo $page;?>">
					<input type="hidden" name="randdata" value="123">
                </div></td>
            </tr>
          </table>
          </form>

       <!-- thread -->
	   <?php for($i=0;$i<count($MAIN_fetch);$i++):?>
        <table width="530" border="1" cellpadding="2" cellspacing="1" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
          <tr>

            <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellpadding="3" cellspacing="0">
                <tr>

                  <td bgcolor="#999999"><strong><?php echo $MAIN_fetch[$i]['TITLE'];?></strong></td>
                </tr>
                <tr>

                  <td bgcolor="#CCCCCC"> <div align="right">
<?php

if($MAIN_fetch[$i]["EMAIL"]){
if($MAIN_fetch[$i]['NAME'] == "admin"){
	echo "<a href='mailto:".$MAIN_fetch[$i]["EMAIL"]."' style='color:#FF0000;'>";
}else{
	echo "<a href='mailto:".$MAIN_fetch[$i]["EMAIL"]."'>";
}
}else{
	echo "";
}
?>
				  <?php echo ($MAIN_fetch[$i]['NAME'] == "admin")?"<span style='color:#FF0000;'>管理人</span>":$MAIN_fetch[$i]['NAME'];?><?php echo ($MAIN_fetch[$i]["EMAIL"])?"</a>":"";?><?php echo ($MAIN_fetch[$i]["URL"])?" / <a href='".$MAIN_fetch[$i]["URL"]."' target='_blank' style='color:#FF9346;'>URL</a>":"";?>
				  　：　<?php echo $MAIN_fetch[$i]["REG_DATE"];?></div></td>
                </tr>
              </table></td>
          </tr>
            <tr>

              <td height="76" align="right" bgcolor="#FFFFFF">

                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                  <tr>

                    <td><?php echo nl2br($MAIN_fetch[$i]["COMMENT"]);?></td>
                  </tr>
                  <tr>
                    <td><div align="right">

            <form action="./" method="post" style="margin:0">
                <input name="Submit2" type="submit" value="返信">
                <input name="res" type="hidden" id="res" value="yes">
                <input name="page" type="hidden" id="page" value="<?php echo $page;?>">
                <input name="master_id" type="hidden" id="master_id" value="<?php echo $MAIN_fetch[$i]["MASTER_ID"];?>">
		<input type="hidden" name="randdata" value="123">
	  		</form>
                      </div>
					  <br></td>
                  </tr>
                </table>
<?php

if($SUB_fetch[$i]){
	for($j=0;$j<count($SUB_fetch[$i]);$j++){
?>
              <table width="450" border="0" cellpadding="2" cellspacing="1" bgcolor="#CCCCCC">
                <tr>

                  <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>

                        <td height="23" bgcolor="#999999"><strong>Re: <?php echo $MAIN_fetch[$i]['TITLE'];?>

                          </strong></td>
                      </tr>
                      <tr>

                        <td height="22" bgcolor="#CCCCCC"> <div align="right">
<?php

if($SUB_fetch[$i][$j]["EMAIL"]){
	if($SUB_fetch[$i][$j]['NAME'] == "admin"){
		echo "<a href='mailto:".$SUB_fetch[$i][$j]["EMAIL"]."' style='color:#FF0000;'>";
}else{
		echo "<a href='mailto:".$SUB_fetch[$i][$j]["EMAIL"]."'>";
}
}else{
		echo "";
}
?>
<?php echo ($SUB_fetch[$i][$j]['NAME'] == "admin")?"<span style='color:#FF0000;'>管理人</span>":$SUB_fetch[$i][$j]['NAME'];?><?php echo ($SUB_fetch[$i][$j]["EMAIL"])?"</a>":"";?><?php echo ($SUB_fetch[$i][$j]["URL"])?" / <a href='".$SUB_fetch[$i][$j]["URL"]."' target='_blank' style='color:#FF9346;'>URL</a>":"";?>：<?php echo $SUB_fetch[$i][$j]["REG_DATE"];?> </div></td>
                      </tr>
                    </table></td>
                </tr>
                  <tr>

                    <td bgcolor="#FFFFFF"><?php echo nl2br($SUB_fetch[$i][$j]["COMMENT"]);?></td>
                  </tr>
                </table>
<?php

	}
}
?>
                <!-- res -->
              </td>
            </tr>
        </table>
		  <br>
		  <?php endfor;?>
        <table width="550" height="38" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><div align="center">
							<table width="400" height="20" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td><div align="center"> <?php echo $PREVIOUS_PAGE1;?>&lt;&lt; <font size="2">前のページへ</font><?php echo $PREVIOUS_PAGE2;?>&nbsp;&nbsp;

						<?php for($i=1;$i<=$TOTLE_PAGES;$i++){if($page == $i){echo "[{$i}]&nbsp";}else{echo "<a href='./?page={$i}'>[{$i}]</a>&nbsp";}}?>
						<?php echo $NEXT_PAGE1;?><font size="2">次のページへ</font> &gt;&gt;<?php echo $NEXT_PAGE2;?>

					  </div></td>
								</tr>
							</table>
					</div></td>
				</tr>
		</table>
        <br>
        <!-- Page Change -->
      </td>
    </tr>
  </table>
<!-- ここまで掲示板表示 -->
	</div>
	</div>

	<div id="footer">Copyright(c)2005 ○○○.All Rights Reserved.</div>

</div>
</div>

<div id="banner"><a href="http://www.all-internet.jp/" target="_blank"><img src="../image/banner.gif" alt="ホームページ制作はオールインターネット"></a></div>

</body>
<script language="JavaScript" type="text/javascript">
<!--
document.write('<img src="../log.php?referrer='+escape(document.referrer)+'" width="1" height="1">');
//-->
</script>
</html>
