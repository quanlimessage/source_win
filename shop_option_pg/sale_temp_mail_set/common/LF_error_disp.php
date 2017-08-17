<?php
#============================================
# エラーHTML表示ファンクション
#============================================
function errorDisp($error){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>Error!!</title>
</head>

<body bgcolor="#C7D6EE">
<div align="center">
<div style="color:crimson;font-weight:bold;font-size:120%;">
<?php echo $error;?>
</div>

<form>
<input type="button" value="戻る" onClick="history.back()">
</form>

</div>
</body>
</html>

<?php
exit();
}
?>