<?php
/*******************************************************************************
tsugueda

�ᥤ�󥳥�ȥ��顼

	���ǽ�˥�������̤�ɽ������ǧ�ڤ�Ԥ�
	��ǧ�ڥǡ����ϥ��å���������ƾ�˻����ޤ魯�ʤ��줬�ʤ��ȥ��顼�ˤ����
	��ǧ�������ξ��ϥȥåץ�˥塼��ɽ��
		������
			�������ʤι���
			---------------
			�����桼��������
			�������夲����

	�����줾��Υ�˥塼����Ω�ץ����Ȥ���ư������ӥ���ȥ��顼��������������

*******************************************************************************/
session_start();
require_once("../common/INI_config.php");		  // ����ե�����
require_once("../common/INI_ShopConfig.php");	// ����å�������ե�����
require_once("../common/LGC_confDB.php");	    // ǧ���ѥե�����
require_once("util_lib.php");
require_once('authOpe.php');
#---------------------------------------------------------------------
# �����ȸ�����Τ����ͤ�demo�ڡ����������������å�

#---------------------------------------------------------------------\
if($siteopne_flg)location($domain);

#---------------------------------------------------------------------
# ǧ�ڥ饤�֥���Ȥä�sessionǧ�ڤ�Ԥ�

#---------------------------------------------------------------------
	function login($login_id,$login_pass){

		if($login_id && $login_pass){
		// SQLite���饤�֥��Υ��󥹥��󥹤�������Ƥ����ʥ��󥹥��󥹤�1�ĤΤߺ����ġ�
		//	$dbh = new sqliteOpe(ID_PW_FILEPATH,CREATE_ID_PW_SQL);

	$err_msg = "";

	//���Ѥ���ID PASSWORD������Ϥ�����Σӣѣ�ʸ���ѹ�
	$sql = "
			SELECT
				BO_ID AS user,
				BO_PW AS password
			FROM
				".CONFIG_MST."
			";

	// �ӣѣ̤�¹�
	$fetch = dbOpe::fetch($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	//	$fetch = $dbh->fetch($sql);
			if(count($fetch) == 0){
				$err_msg .= "������ǡ�����¸�ߤ��ޤ���";
			}else{
				for($i = 0;$i < count($fetch); $i++){
					if($fetch[$i]['user'] == $login_id && $fetch[$i]['password'] == $login_pass){

						# IP�����å�
						$iplst = getPermitIPList($login_id);
						$ip_chk = false;
						//var_dump($iplst);die();
						# IP���¤򤫤��Ƥ��ʤ����
						if($iplst[0] != "free" && $iplst[0] != ""){
							for($j = 0;$j < count($iplst);$j++){
								if($iplst[$j] == $_SERVER['REMOTE_ADDR']) {
									$ip_chk = true;
									break;
								}
							}
						}else{
							$ip_chk = true;
						}

						if($ip_chk){
							$_SESSION['LOGIN'] = true;
						}else{
							$err_msg = "����IP�ǤΥ�����ϵ��Ĥ���Ƥ��ޤ���";
						}
						break;
					}
				}
			}

			if($_SESSION['LOGIN'] !== true) $err_msg .= "ID�ޤ��ϥѥ���ɤ��ְ�äƤ��ޤ���";

		}else{
			$err_msg .= "ID�ޤ��ϥѥ���ɤ��ְ�äƤ��ޤ���";
		}
		return ($err_msg) ? $err_msg : true;
	}

	function logout(){
		$_SESSION['LOGIN'] = false;
	}

// �����������������å��Υե饰
$injustice_access_chk = 1;

if($_POST) extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

if($login || $_GET['login']) $result = login($login_id ,$login_pass);

if($logout || $_GET['logout'] == 1){logout();}
#---------------------------------------------------------------------
# ǧ�ڥ饤�֥���Ȥä�Basicǧ�ڤ�Ԥ�

#---------------------------------------------------------------------
/*
// �����������������å��Υե饰
$injustice_access_chk = 1;

// ǧ�ڥ��饹�ե�����
require_once('authOpe.php');

$idpw = $fetch_ipas;

authOpe::basicCheck2($idpw,"for Members");
*/
#=============================================================
# HTTP�إå��������
#	ʸ�������ɤȸ��졧EUC�����ܸ�
#	¾���ʣӤȣãӣӤ����꡿����å�����ݡ���ܥåȵ���
#=============================================================
utilLib::httpHeadersPrint("EUC-JP",true,true,true,true);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>��������</title>
</head>

<frameset rows="80,*" cols="*" frameborder="NO" border="0" framespacing="0">
		<frame src="head.php" name="head" frameborder="no" scrolling="NO" noresize title="head" >
		<?php if($_SESSION['LOGIN']){ ?>
		<frameset cols="220,*" frameborder="NO" border="0" framespacing="0">
				<frame src="menu.php" name="menu" frameborder="no" scrolling="auto" noresize marginwidth="0" marginheight="0" title="menu">
				<frame src="main.php" name="main" frameborder="no" title="main">
		</frameset>
		<?php }else{ ?>
				<frame src="login.php<?=($result !== true) ? "?err=".urlencode($result) : "";?>" name="main" frameborder="no" title="main">
		<?php } ?>
</frameset>
<noframes><body>
</body></noframes>
</html>
