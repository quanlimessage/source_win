<?php
/*******************************************************************************
Sx�ϥץ���� �Хå����ե�����MySQL�б��ǡ�
Logic��DB��Ͽ����������

*******************************************************************************/

#=================================================================================
# �����������������å���ľ�ܤ��Υե�����˥���������������
#=================================================================================
if( !$_SESSION['LOGIN'] ){
	header("Location: ../err.php");exit();
}
if( !$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW'] ){
//	header("Location: ../index.php");exit();
}
// �����������������å���ľ�ܤ��Υե�����˥���������������
if(!$accessChk){
	header("Location: ../index.php");exit();
}

#=================================================================================
# POST�ǡ����μ����ʸ��������ʶ��̽�����	�����ѽ������饹�饤�֥������
#=================================================================================
// ����������ν����ʸ��̵��������\�ɤ��롿Ⱦ�ѥ��ʤ����Ѥ��Ѵ�
extract(utilLib::getRequestParams("post",array(8,7,1,4),true));

// MySQL�ˤ����ƴ�ʸ���򥨥������פ��Ƥ���
//$title = utilLib::strRep($title,5);
//$content = utilLib::strRep($content,5);

//�ȣԣ̥ͣ�����ͭ�����ν����ʡ�utilLib::getRequestParams�ۤ�ʸ��������Ԥ����ξ������Ѥ��뤿��POST����Ѥ����
//$content = html_tag($_POST['content']);

//h14s_han2zen�Ǽ��ֻ��Ȥ�ʸ��������ʸ�����Ѵ�����������롣
$title = utilLib::strRep(h14s_han2zen($title),5);
$subject = utilLib::strRep(h14s_han2zen($subject),5);
$content = utilLib::strRep(h14s_han2zen($content),5);

#==================================================================
# �����������Ƥ򤳤��ǵ��Ҥ򤹤�
# �ե�����ɤ��ɲá��ѹ��Ϥ����ǽ���
#==================================================================
	$sql_update_data = "
		TITLE = '$title',
		SUBJECT = '$subject',
		CONTENT = '$content',
		DISP_DATE = NOW(),
		DISPLAY_FLG = '1',
		DEL_FLG = '0'
	";

#-----------------------------------------------------------------
#	VIEW_ORDER�Ѥ��ͤ����
#		��������Ͽ����Ƥ��뵭���ǡ�����κ���VIEW_ORDER�ͤ����
#		  �����1­������Τ�$view_order�˳�Ǽ���ƻ���
#		����Ͽ�������å������äƤ�����VIEW_ORDER�ͤ�����1���夲
#		��$view_order��1�򥻥åȤ����Ū����Ͽ����־�ˤ���
#-----------------------------------------------------------------

		if($_POST["copy_type"]=="new"){
			//ʣ���������VIEW_ORDER
			$vosql_old = "SELECT VIEW_ORDER AS VO FROM ".STM_PRODUCT_LST." WHERE (RES_ID = '$res_id') AND (DEL_FLG = '0')";
			$fetchVO_old = dbOpe::fetch($vosql_old,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			$view_order_old = $fetchVO_old[0]["VO"];

			$vosql ="UPDATE ".STM_PRODUCT_LST." SET VIEW_ORDER = VIEW_ORDER+1 WHERE (VIEW_ORDER > $view_order_old)";
			if(!empty($vosql)){
				$db_result = dbOpe::regist($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB��Ͽ���Ԥ��ޤ���<hr>{$db_result}");
			}
			$view_order = ($fetchVO_old[0]["VO"] + 1);
		}elseif($ins_chk == 1){
			//�ȥå���Ͽ�����å���������
			$vosql ="UPDATE ".STM_PRODUCT_LST." SET VIEW_ORDER = VIEW_ORDER+1";
			if(!empty($vosql)){
				$db_result = dbOpe::regist($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
				if($db_result)die("DB��Ͽ���Ԥ��ޤ���<hr>{$db_result}");
			}
			$view_order = 1;
		}
		else{
			//������Ͽ
			$vosql = "SELECT MAX(VIEW_ORDER) AS VO FROM ".STM_PRODUCT_LST." WHERE(DISPLAY_FLG = '1')";
			$fetchVO = dbOpe::fetch($vosql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
			$view_order = ($fetchVO[0]["VO"] + 1);
		}

#=================================================================================
# �������������ˤ�äƽ�����ʬ��	��Ƚ�Ǥ�$_POST["regist_type"]
#=================================================================================
switch($_POST["regist_type"]):
case "update":
//////////////////////////////////////////////////////////
// �о�ID�Υǡ�������

	// �оݵ���ID�ǡ����Υ����å�
	if(!ereg("^([0-9]{10,})-([0-9]{6})$",$res_id)||empty($res_id)){
		die("��̿Ū���顼�������ʽ����ǡ�������������ޤ����ΤǶ�����λ���ޤ���<br>{$res_id}");
	}

	// �����ե�����̾�η����POST���Ϥ��줿��¸�ε���ID��$res_id�ˤ���ѡ�
	$for_imgname = $res_id; // POST���Ϥ��줿��¸����ID�����

	// ����ؼ�������Ƥ�����¹�(ʣ��)
	if($_POST["regist_type"]=="update" && $del_img){
		foreach($del_img as $k => $v){
		 	search_file_del(STM_IMG_PATH,$res_id."_".$v.".*");
		}
	}

	// DB��Ǽ�Ѥ�SQLʸ
	$sql = "
	UPDATE
		".STM_PRODUCT_LST."
	SET
		$sql_update_data
	WHERE
		(RES_ID = '$res_id')
	";

	break;

case "new":
//////////////////////////////////////////////////////////////////
// ������Ͽ

	// �����ե�����̾�η���ʿ�����ID���������ƻ��ѡ�DB��Ͽ����RES_ID�ˤ���ѡ�
	$res_id = $makeID();
	$for_imgname = $res_id;

	// ���ߤ���Ͽ��������ꤷ�����̤���ξ��Τ�DB�˳�Ǽ
	$cnt_sql = "SELECT COUNT(*) AS CNT FROM ".STM_PRODUCT_LST." WHERE(DEL_FLG = '0')";
	$fetchCNT = dbOpe::fetch($cnt_sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);

	if($fetchCNT[0]["CNT"] < STM_DBMAX_CNT):

		$sql = "
		INSERT INTO ".STM_PRODUCT_LST."
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
	die("��̿Ū���顼����Ͽ�ե饰��regist_type�ˤ����ꤵ��Ƥ��ޤ���");
endswitch;

// �ӣѣ̤�¹�
if(!empty($sql)){
	$db_result = dbOpe::regist($sql,DB_USER,DB_PASS,DB_NAME,DB_SERVER);
	if($db_result)die("DB��Ͽ���Ԥ��ޤ���<hr>{$db_result}");
}

?>