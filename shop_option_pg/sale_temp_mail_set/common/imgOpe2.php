<?php
/******************************************************************************************************************************
���������åץ������饹�饤�֥��

	��ñ�˲������åץ��ɡʡ��ꥵ�����ˤ�Ԥ�����Υ��饹�Ǥ���
	�ե����륿�����̤˽������졢��¸����ޤ���

	���ߤ��б������ϡ�
		JPEG	��
		GIF		�����ɤ߹��ߤϤǤ��뤬���񤭽Ф��Ȥ���JPEG�����ˤʤ��
		PNG		��
		SWF		����ñ��ʥ��åץ��ɤΤߤǤ������������Ǥ�����

	�����Ѿ�����
		ɬ�������󥹥��󥹤��������뤳��

	���᥽�å�
		�����󥹥ȥ饯��

			imgOpe(��¸��ǥ��쥯�ȥ�, ��¸�ե�����̾, ���ϲ�������, ���ϲ����ι⤵);
				��������ΰ������ά�ġ�

		����¸��ǥ��쥯�ȥ������

			setRoot(��¸��ǥ��쥯�ȥ�);
				����¸��ǥ��쥯�ȥ�ϡ�ʣ������ġ�������б���

		����¸�ե�����̾������

			setImageName(��¸�ե�����̾);
				���ե�����̾�ˤϡ���ĥ�Ҥ��դ��ʤ����ȡ�

		�����ϲ����Υ������������⤵�ˤ�����

			setSize(���ϲ�������, ���ϲ����ι⤵);

		���ꥵ�����ȥ����ڥ�����˴ؤ��ƤΥե饰����

			resizeOn();		�ꥵ�����ե饰��true������
			resizeOff();	�ꥵ�����ե饰��false������
			fixedOn();		�����ڥ��������ե饰��true������
			fixedOff();		�����ڥ��������ե饰��false������

		�������ե�������ɤ߹���

			load(�����Υƥ�ݥ��̾);
				�������Υƥ�ݥ��̾���������$_FILES['image']['tmp_name']

		����������¸

			save(��¸��ǥ��쥯�ȥꡢ��¸�ե�����̾�����ϲ������������ϲ����ι⤵�������ڥ��������ե饰);

		�������Υ��åץ��ɡ��ɤ߹��ߡ���¸��Ԥ���

			up(�����Υƥ�ݥ��̾, ��¸�ե�����̾);
				�����Υ᥽�åɤϡ����󥹥��󥹤������塢���ϲ����������ԤäƤ��顢���Ѥ��뤳�ȡ�

 Version Up Info
 2004/11/11 : v1.0 �Ȥꤢ��������

 Author : kinoi
******************************************************************************************************************************/
class imgOpe{

	// ���ϲ����ˤĤ��ƤΥǡ���
	var $srcImage;	// �����Υƥ�ݥ��̾
	var $image;			// �����Υ꥽��������ȤΥǡ�����
	var $srcW;			// ����������x��������Ĺ����
	var $srcH;			// �����ι⤵��y��������Ĺ����
	var $fileType;	// �ե��������
	var $fileExt;		// �ե�����γ�ĥ��

	// ��������
	var $root;			// ��������¸��롼�ȥǥ��쥯�ȥ��ʣ���ġ�
	var $rootNum;		// ��¸��ο�
	var $dstW;			// ���ϲ���������x��������Ĺ����
	var $dstH;			// ���ϲ����ι⤵��y��������Ĺ����
	var $imageName;	// ��������¸�ե�����̾
	var $isResize;	// ������ꥵ�������뤫�ɤ����Υե饰
	var $isFixed;		// �����򥢥��ڥ�����������¸���뤫�ɤ����Υե饰

//=============================================================================================================================
// �����󥹥ȥ饯��
//

// 		�����㣱��$myimgOpe = new imgOpe();
// 							������������ƾ�ά��������
//

// 					����$myimgOpe = new imgOpe( "/home/demo3/html/a/", "upImage", 200, 150 );
// 							����������������ꤷ�����������ꤷ�ʤ����ܤ� NULL �⤷���� "" �ˤ����
//

//=============================================================================================================================
function imgOpe( $dstRoot="", $dstImageName="", $width="", $height="" ){
	// ���ϲ����ν����
	$this->image    = "";
	$this->srcW     = 0;
	$this->srcH     = 0;
	$this->fileType = "";
	$this->fileExt  = "";

	// �����������
	$this->rootNum  = 0;
	$this->isResize = true;		// �ꥵ��������
	$this->isFixed  = false;	// �����ڥ�������ꤷ�ʤ��ʽ��ϲ����������˶�����
	if ( !empty($dstRoot) )										$this->setRoot( $dstRoot );
	if ( !empty($dstImageName) )							$this->setImageName( $dstImageName );
	if ( !empty($width) || !empty($height) )	$this->setSize( $width, $height );
}

//=============================================================================================================================
// ����¸��ǥ��쥯�ȥ������᥽�å�
//

// 		������ˡ����������¸��ǥ��쥯�ȥ�Υѥ�$dstRoot��Ϳ���롣$dstRoot������Ǥ��
//

// 		�����㣱��$myimgOpe->setRoot( "/home/demo3/html/...../" );
//
//					����$roots[0] = "/home/demo3/html/a/";
//							$roots[0] = "/home/demo3/html/b/";
// 							$myimgOpe->setRoot( $roots );
//
//=============================================================================================================================
function setRoot( $dstRoot ){

	#-----------------------------
	# ���������å�
	#-----------------------------
	if ( empty($dstRoot) )
		exit( "��¸��ǥ��쥯�ȥ����ꥨ�顼<br>������̤����Τ��ᡢ������λ���ޤ�����<br>�Ȳ�������¸��ǥ��쥯�ȥ�ɤΥꥹ�Ȥ���ꤷ�Ƥ���������" );

	#-----------------------------
	# ����ǥ��쥯�ȥ꤬�񤭹��߲�ǽ���ɤ��������å�
	#-----------------------------

	if ( is_array($dstRoot) ):	// ����ǥ��쥯�ȥ꤬ʣ���ξ��

		foreach ( $dstRoot as $oneroot ){
			if ( !is_writable($oneroot) )
				exit( "��¸��ǥ��쥯�ȥ����ꥨ�顼<br>���ꤵ�줿��¸��ǥ��쥯�ȥ� \"{$oneroot}\" �Ͻ񤭹����ԲĤǤ���" );
		}

	else:												// ����ǥ��쥯�ȥ꤬���Ĥξ��

		if ( !is_writable($dstRoot) )
			exit( "��¸��ǥ��쥯�ȥ����ꥨ�顼<br>���ꤵ�줿��¸��ǥ��쥯�ȥ� \"{$dstRoot}\" �Ͻ񤭹����ԲĤǤ���" );

	endif;

	#-----------------------------
	# ����
	#-----------------------------
	$this->root =& $dstRoot;
	$this->rootNum = count($dstRoot);

}

//=============================================================================================================================
// �����ϲ����Υե�����̾������᥽�å�
//

// 		������ˡ����������¸�ե�����̾$dstImageName��Ϳ���롣����ĥ�Ҥ��դ��ʤ�
//

// 		������  ��$myimgOpe->setImageName("upImage");
//
//=============================================================================================================================
function setImageName( $dstImageName ){

	#-----------------------------
	# ���������å�
	#-----------------------------
	if ( empty($dstImageName) )
		exit( "���ϲ����ե�����̾���ꥨ�顼<br>������̤����Τ��ᡢ������λ���ޤ�����<br>�Ȳ�������¸�ե�����̾�ɤ���ꤷ�Ƥ���������" );

	#-----------------------------
	# ����
	#-----------------------------
	$this->imageName = $dstImageName;

}

//=============================================================================================================================
// �����ϲ���������������᥽�å�
//

// 		������ˡ�������˽��ϲ����Υ�����������$width���⤵��$height�ˤ�Ϳ���롣
//

// 		������  ��$myimgOpe->setSize(200, 150);
//
//=============================================================================================================================
function setSize( $width, $height ){

	#-----------------------------
	# ���������å�
	#-----------------------------
	if ( empty($width) || empty($height) )
		exit( "���ϲ������������ꥨ�顼<br>������̤����Τ��ᡢ������λ���ޤ�����<br>�Ƚ��ϲ��������ɡ��Ƚ��ϲ����ι⤵�ɤν�����ꤷ�Ƥ���������" );

	#-----------------------------
	# ����
	#-----------------------------
	if ( $width <= 0 || $height <= 0 ){	// �����⤵���ͥ����å�
		$this->isResize = false;
	}else{
		$this->dstW = $width;
		$this->dstH = $height;
	}
}

//=============================================================================================================================
// ���ե饰������
//

// 		�ꥵ�����ȥ����ڥ��������˴ؤ��ƤΥե饰�����ꤹ��
// 		�ǥե���Ȥϡ��ꥵ������On�������ڥ�������ꡧoff
//

// 		������  ��$myimgOpe->resizeOn();
// 							���ꤷ�������������ˡ��ꥵ��������褦������
//
//=============================================================================================================================
function resizeOn(){ $this->isResize = true; }
function resizeOff(){ $this->isResize = false; }
function fixedOn(){ $this->isFixed = true; }
function fixedOff(){ $this->isFixed = false; }

//=============================================================================================================================
// �������ե�������ɤ߹��ߥ᥽�å�
//

// 		������ˡ�����������ϲ����Υƥ�ݥ��̾$srcImage��Ϳ���롣
//

// 		������  ��$myimgOpe->load($_FILES['image']['tmp_name']);
//
//=============================================================================================================================
function load( $srcImage ){

	/*******************************************************************************
		GetImageSize���֤��͡�Ϣ�������

		0       �� ���������ʥԥ������
		1       �� �����ι⤵�ʥԥ������
		2       �� �����μ���ʥե饰��
		3       �� IMG������ľ�����ѤǤ���ʸ����"height=yyy width=xxx"
		bits    �� RGB�ξ���3��CMYK�ξ���4���֤���JPEG�����ʳ��Ͽ��ѤǤ��ʤ�������
		channels�� �����ο�����JPEG�����ʳ��Ͽ��ѤǤ��ʤ�������
		mime    �� ������MIME������

		�����μ���ե饰
			1 = GIF, 2 = JPG, 3 =PNG, 4 = SWF, 5 = PSD, 6 = BMP,

			7 = TIFF_II(intel byte order), 8 = TIFF_MM(motorola byte order),

			9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM

	********************************************************************************/

	#-----------------------------
	# ���������å�
	#-----------------------------
	if ( empty($srcImage) ){
		return true;
	}else if ( !is_uploaded_file($srcImage) ) {
		return false;
	}else{
		$this->srcImage = $srcImage;
	}

	#-----------------------------
	# ��������μ���
	#-----------------------------
	$param = GetImageSize( $this->srcImage );

	// RGB�����β����ե����뤬���åץ��ɤ���Ƥ�����票�顼
	if($param["channels"] == 4)exit( "CMYK�����β����ϥ��åץ��ɽ���ޤ���RGB��������¸��ľ���Ƥ��饢�åץ��ɤ��Ʋ�������" );

	$this->srcW     = $param[0];	// ��
	$this->srcH     = $param[1];	// �⤵
	$this->fileType = $param[2];	// �ե����륿����
	if ( !$this->fileType ){
		foreach ($_FILES as $file){
			if ($file['tmp_name'] == $this->srcImage){
				$filename = $file['name'];
				break;
			}
		}
		exit( "�����ե�������ɤ߹��ߥ��顼<br>���ꤵ�줿�����ե����� \"{$filename}\" �˥��������Ǥ��ʤ������뤤�������������ǤϤʤ����ᡢ������λ���ޤ�����<br>
					�����������Υƥ�ݥ��̾����ꤷ�Ƥ���������" );
	}

	#-----------------------------
	# �ե����륿�����̤˿���������������ե������ĥ�Ҥ�����
	#-----------------------------
	switch( $this->fileType ):
		case IMAGETYPE_GIF:
			$this->image = ImageCreateFromGIF($this->srcImage);
			$this->fileExt = '.gif';
			break;
		case IMAGETYPE_JPEG:
			$this->image = ImageCreateFromJPEG($this->srcImage);
			$this->fileExt = '.jpg';
			break;
		case IMAGETYPE_PNG:
			$this->image = ImageCreateFromPNG($this->srcImage);
			$this->fileExt = '.png';
			break;
		case IMAGETYPE_SWC:
			$this->fileExt = '.swf';
			break;
		default:
			foreach ($_FILES as $file){
				if ($file['tmp_name'] == $this->srcImage){
					$filename = $file['name'];
					break;
				}
			}
			exit( "�����ե�������ɤ߹��ߥ��顼<br>���ꤵ�줿�����ե����� \"{$filename}\" �ϰ����ʤ������Τ��ᡢ������λ���ޤ�����" );
	endswitch;

	return true;
}

//=============================================================================================================================
// �������ե��������¸�᥽�å�
//

// 		������ˡ����������¸��ǥ��쥯�ȥꡢ��¸�ե�����̾�����ϲ������������ϲ����ι⤵�������ڥ��������ե饰��Ϳ���롣
// 							�ɤΰ����⥳�󥹥ȥ饯����¾�Υ᥽�åɤ����ꤷ�Ƥ���С���ά��
//

// 		������  ��$myimgOpe->save("/home/demo3/html/a/", "upImage", 200, 150, true);
//
//=============================================================================================================================
function save( $dstRoot="", $dstImageName="", $width="", $height="", $aspect="" ){

	// ���顼��å������ν����
	$error_mes = "";

	#-----------------------------
	# ���������å�
	#-----------------------------
	// ��¸��ǥ��쥯�ȥ�����Υ����å�
	if ( $dstRoot ){
		$this->setRoot( $dstRoot );
	}else if ( !$this->root ){
		$error_mes .= "�����ե�����ν��ϥ��顼<br>��������¸��ǥ��쥯�ȥ�����ꤷ�Ƥ���������";
	}

	// ��������¸�ե�����̾����Υ����å�
	if ( $dstImageName ){
		$this->setImageName( $dstImageName );
	}else if ( !$this->imageName ){
		$error_mes .= "�����ե�����ν��ϥ��顼<br>��������¸�ե�����̾�����ꤷ�Ƥ���������";
	}

	// ���ϲ��������ȹ⤵����Υ����å�
	if ( $width && $height ){
		$this->setSize( $width, $height );
	}else if ( !$this->dstW && !$this->dstH && $this->isResize == true ){
		$error_mes .= "�����ե�����ν��ϥ��顼<br>���ϲ��������ȹ⤵�����ꤷ�Ƥ���������";
	}

	// �����ڥ���������Υ����å�
	if( $aspect ){
		$this->isFixed = $aspect;
	}

	// ���顼�����ä��鶯����λ
	if( $error_mes ) exit( "<p>�ʲ�����ͳ�Ƕ�����λ���ޤ�����</p>\n{$error_mes}<p>�Ȳ�������¸��ǥ��쥯�ȥ�ɡ��Ȳ�������¸�ե�����̾�ɡ��Ƚ��ϲ��������ɡ��Ƚ��ϲ����ι⤵�ɡ��ȥ����ڥ���������ե饰�ɤν�����ꤷľ���Ƥ���������</p>\n" );

	#-----------------------------
	# �����βù�����¸
	#-----------------------------
	if ( $this->fileType == IMAGETYPE_SWC || ( $this->isResize == false || !$this->dstW || !$this->dstH ) ):
	///////////////////////////////////////////////
	// Flash�ե�����ξ��ȡ��ꥵ�������ʤ����Ϥ��Τޤޥե�����򥢥åץ���

		for ( $i = 0; $i < $this->rootNum; $i++ ){

			if ( $this->rootNum == 1 )
				$up_result = move_uploaded_file($this->srcImage, $this->root . $this->imageName . $this->fileExt);
			else
				$up_result = copy($this->srcImage, $this->root[$i] . $this->imageName . $this->fileExt);

			if ( !$up_result ) return false;

		}

	else:
	///////////////////////////////////////////////
	// �ꥵ��ץ���ꥵ����������¸

		// �����ڥ����������ˤ�äơ�������ʬ����
		if( $this->isFixed ){		// �����ڥ��������ʸ������˹�碌���

				$aspect_ratio = $this->srcW / $this->srcH;

				if ( $this->srcW > $this->dstW && $this->srcH > $this->dstH ){
				// ���ϲ����������⤵�Ȥ�����ꤷ�����ϲ�������������礭�����

					if ( abs( $this->srcW - $this->dstW ) < abs( $this->srcH - $this->dstH ) ){
						// �⤵�������礭�����
						// $this->dstH �Ϥ��Τޤ�
						$this->dstW = ceil( $aspect_ratio * $this->dstH );	// ��������
					}else{
						// ���������礭�����
						// $this->dstW �Ϥ��Τޤ�
						$this->dstH = ceil( $this->dstW / $aspect_ratio );	// �ĥ�����
					}

				}else if ( $this->srcW > $this->dstW ){
				// ���Τ����ꤷ�����ϲ�������������礭�����

					// $this->dstW �Ϥ��Τޤ�
					$this->dstH = ceil( $this->dstW / $aspect_ratio );	// �ĥ�����

				}else if ( $this->srcH > $this->dstH ){
				// �⤵�Τ����ꤷ�����ϲ�������������礭�����

					// $this->dstH �Ϥ��Τޤ�
					$this->dstW = ceil( $aspect_ratio * $this->dstH );	// ��������

				}else{
				// ���ϲ����Υ����������ϲ����������ʲ��ξ��ξ������ϲ����Υ������ˤ���

					$this->dstW = $this->srcW;
					$this->dstH = $this->srcH;
				}

				// ���ϲ����κ���
				$outputImg = ImageCreateTrueColor( $this->dstW, $this->dstH );
				$icr = ImageCopyResampled( $outputImg, $this->image, 0, 0, 0, 0, $this->dstW, $this->dstH, $this->srcW, $this->srcH );
				if ( !$icr ) return false;

		}else{			// �����ڥ�����̵������ꤷ�����ϲ����������˶�����

				// ���ϲ����κ���
				$outputImg = ImageCreateTrueColor( $this->dstW, $this->dstH );
				$icr = ImageCopyResampled( $outputImg, $this->image, 0, 0, 0, 0, $this->dstW, $this->dstH, $this->srcW, $this->srcH );
				if ( !$icr ) return false;

		}

		// ��������¸
		for ( $i = 0; $i < $this->rootNum; $i++ ){

			if ($this->rootNum == 1) $outName = $this->root . $this->imageName . $this->fileExt;
			else $outName = $this->root[$i] . $this->imageName . $this->fileExt;

			switch( $this->fileType ):
				case IMAGETYPE_GIF:  $up_result = ImageGIF( $outputImg, $outName, 100 ); break;
				case IMAGETYPE_JPEG: $up_result = ImageJPEG( $outputImg, $outName, 100 ); break;
				case IMAGETYPE_PNG:  $up_result = ImagePNG( $outputImg, $outName, 0 ); break;
			endswitch;
			if ( !$up_result ) return false;

		}

		ImageDestroy($outputImg);

	endif;

	return true;
}

//=============================================================================================================================
// �������ե�����δʰץ��åץ��ɥ᥽�å�
//

// 		�����ݤʤΤǡ�load �� save ��ޤȤ�ư��٤ˤ�뤿��Υ᥽�å�
//

// 		������ˡ�����󥹥��󥹤������������ϲ���������򤷤��塢���Υ᥽�åɤ���Ѥ���
//

// 		������  ��$myimgOpe = new imgOpe("/home/demo3/html/a/", "", 200, 150);
// 							$myimgOpe->up($_FILES['image1']['tmp_name'], "upImage1");
// 							$myimgOpe->up($_FILES['image2']['tmp_name'], "upImage2");
// 							$myimgOpe->up($_FILES['image3']['tmp_name'], "upImage3");
//

// 							image1, image2, image3�Σ��Ĥβ��������� /home/demo3/html/a/ �����
// 							200��150�Υ���������¸����롣
// 							�ե�����̾�Ϥ��줾�� upImage1, upImage2, upImage3 �˳�ĥ�Ҥ��դ�����ΤȤʤ롣
//
//=============================================================================================================================
function up( $srcImage, $dstImageName="" ){

	#-----------------------------
	# ���������å�
	#-----------------------------
	if ( empty($srcImage) ){
		return true;
	}

	#-----------------------------
	# �������åץ���
	#-----------------------------
	$load_result = $this->load( $srcImage );	// �������ɤ߹���
	if ( !$load_result ) return false;

	if ( $dstImageName ){
		$this->setImageName( $dstImageName );		// �����ե�����̾������
	}

	if ( $load_result ){
		$save_result = $this->save();						// ��������¸
	}

	return $save_result;
}

}

?>
