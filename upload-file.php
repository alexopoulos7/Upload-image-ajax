<?php
session_start(); // start up your PHP session! 
/*timestamp to get unique names*/
  	$timestampfile = time();
    $uploaddir= './images/prods/'; 
    $file_dimension_limit =600;
 if (isset($_FILES["upload-image"]) && $_FILES["upload-image"]["error"] === UPLOAD_ERR_OK)
	{
	
	  $my_image = array_values(getimagesize($_FILES['upload-image']["tmp_name"]));
	   //view new array
		//print_r($my_image);
	   //use list on new array
		list($width, $height, $type, $attr) = $my_image;
		if($width>$file_dimension_limit  || $height>$file_dimension_limit )
		{echo    "<result_flag><result>error</result>";
		echo    "<reason>file_size</reason>";
		 echo    "<sessionId>-1</sessionId></result_flag>";
		 exit();
		}
		if($type<>2) //not a JPG image
		{echo    "<result_flag><result>error</result>";
		 echo    "<reason>notJPG</reason>";
		 echo    "<sessionId>-1</sessionId></result_flag>";
		exit();
		}
	$file = $uploaddir . basename($_FILES['upload-image']['name']); 
	$ftmp = $_FILES['upload-image']['tmp_name'];
	$oname = $_FILES['upload-image']['name'];
 
	$extension = strstr($oname, ".");
	$fname = $uploaddir.$timestampfile.$_FILES['upload-image']['name'];
	$oname = $timestampfile.$_FILES['upload-image']['name'];
	//make a safe file	
	$oname= str_replace("#", "No.", $oname);
	$oname= str_replace("$", "Dollar", $oname);
	$oname= str_replace("%", "Percent",$oname);
	$oname= str_replace("^", "", $oname);
	$oname= str_replace("&", "and", $oname);
	$oname= str_replace("*", "", $oname);
	$oname= str_replace("?", "", $oname);

if(move_uploaded_file($ftmp, $fname)){

	$img = new img('./images/prods/'.$oname);
	$img->resize(170,170,true);
 	$img->store('./images/prods/medium/'.$oname);
	$img->resize();
 	$img->store('./images/prods/thumbs/'.$oname);
	 
	$_SESSION['image_counter']=$_SESSION['image_counter']+1;
	$c = $_SESSION['image_counter'];
	if($_SESSION['next_image']==1) $_SESSION['image1'] = $oname;
	if($_SESSION['next_image']==2) $_SESSION['image2'] = $oname;
	if($_SESSION['next_image']==3) $_SESSION['image3'] = $oname;	
    
	//if($c == 1) 	$_SESSION['next_image'] = $c+1;
	//if($c == 2)		$_SESSION['next_image'] = $c+1;
	//if($c == 3)		$_SESSION['next_image'] = 0;
	$_SESSION['next_image']=$_SESSION['next_image']+1;
	echo    "<result_flag><result>success</result>";
    echo    "<sessionId>".$_SESSION['image_counter']."</sessionId>";
    echo    "<fileName>".$oname."</fileName></result_flag>";
	} 
	else { //error in uploading
   echo    "<result_flag><result>error</result>";
   echo    "<sessionId>-1</sessionId></result_flag>";
   } }
   else { //error in file
   echo    "<result_flag><result>error</result>";
   echo    "<sessionId>-2</sessionId></result_flag>";
}
class img {
	var $image = '';
	var $temp = '';
	
	function img($sourceFile){
		if(file_exists($sourceFile)){
			$this->image = ImageCreateFromJPEG($sourceFile);
		} else {
			$this->errorHandler();
		}
		return;
	}
	
	function resize($width = 100, $height = 100, $aspectradio = true){
		$o_wd = imagesx($this->image);
		$o_ht = imagesy($this->image);
		if(isset($aspectradio)&&$aspectradio) {
			$w = round($o_wd * $height / $o_ht);
			$h = round($o_ht * $width / $o_wd);
			if(($height-$h)<($width-$w)){
				$width =& $w;
			} else {
				$height =& $h;
			}
		}
		$this->temp = imageCreateTrueColor($width,$height);
		imageCopyResampled($this->temp, $this->image,
		0, 0, 0, 0, $width, $height, $o_wd, $o_ht);
		$this->sync();
		return;
	}
	function sync(){
		$this->image =& $this->temp;
		unset($this->temp);
		$this->temp = '';
		return;
	}
	function _sendHeader(){
		header('Content-Type: image/jpeg');
	}
	function show(){
		$this->_sendHeader();
		if(!ImageJPEG($this->image))
			$this->errorHandler();
		return;
	}
	function errorHandler($erron_num=0){
	if ($error_num == 1)
		echo "Very big image";
	else if($erron_num == 2)
		echo "Only .jpg fotos supported";
	else
		echo "error";
		exit();
	}
	function store($file){
		ImageJPEG($this->image,$file);
		return;
	}
	function watermark($pngImage, $left = 0, $top = 0){
		ImageAlphaBlending($this->image, true);
		$layer = ImageCreateFromPNG($pngImage); 
		$logoW = ImageSX($layer); 
		$logoH = ImageSY($layer); 
		ImageCopy($this->image, $layer, $left, $top, 0, 0, $logoW, $logoH); 
	}
}
?>