<?php

require_once("../../common/config_sale_tmpl_mail.php");	// �����������
//���顼��å�������ɽ���λ����󥳡��ɤ�sjis��ʸ�������򤹤�Τ����
	header("Content-Type: text/html; charset=EUC-JP");
	header("Content-Language: ja");

$errmsg = "";

//post_max_size�ʥ����С����ꡧPOST������ǽ�ʥ�������¡ˤ����
$post_max = ini_get('post_max_size');
$post_max = str_replace("M", "", $post_max);
$post_max = $post_max  * 1024 * 1024;

// �������褦�Ȥ����ǡ�����post_max_size�����礭�����
if($_SERVER["CONTENT_LENGTH"] > $post_max){
	//$errmsg .= "post_max_size = ".$post_max."\n";
	//$errmsg .= "CONTENT_LENGTH = ".$_SERVER["CONTENT_LENGTH"]."\n";
	$errmsg .= "������ǽ�ʥե�����Υ�������Ķ���Ƥ��ޤ���\n";
}else{

	foreach($_FILES as $file){
		if($file){
			if(is_array($file['size'] )){

				foreach($file['size'] as $size){
					if($size > MAX_MB * 1024 * 1024){
						$errmsg .= "���̤��礭�����뤿�ᡢ���åץ��ɤǤ��ޤ���\n(".MAX_MB."MB�ʲ��Υե�����Τߥ��åץ��ɲ�ǽ�Ǥ���)\n";
					}
				}

				foreach($file['error'] as $error){
					if($error){
						$errmsg .= "���Υե�����ϥ��åץ��ɤ��뤳�Ȥ��Ǥ��ޤ���\n";
					}
				}

			}else{

				if($file['size'] > MAX_MB * 1024 * 1024){
				$errmsg .= "���̤��礭���Ǥ���".MAX_MB."MB�ʲ��Υե�����ˤ��Ƥ���������\n";
				}
				if($file['error']){
					$errmsg .= "���Υե�����ϥ��åץ��ɤ��뤳�Ȥ��Ǥ��ޤ���\n";
				}

			}

			// �ե����뷿�������å�
			/*
			if(!preg_match( "/jpeg/",$file['type'])){
				$errmsg .= "�ե�����������㤤�ޤ���jpeg�ե�����ˤ��Ƥ���������";
			}
			*/
			$errmsg = ($errmsg) ? $errmsg : "success";
		}
	}
}
echo $errmsg;
?>