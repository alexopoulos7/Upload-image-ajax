  <?php
 session_start(); // start up your PHP session! 

$deletedir = './images/prods/'; 
//$file = $deletedir . basename($_FILES['data']); 
$image 		= $_POST["name"];
$image_id 	= $_POST["id"];
 

$file = $deletedir . $image; 
$deletedir_thumbs = './images/prods/thumbs/'; 
$deletedir_medium = './images/prods/medium/';
$file_delete = $deletedir_thumbs . $image; 
$file_delete2 = $deletedir_medium . $image; 

if (unlink($file) && unlink($file_delete)&& unlink($file_delete2)) { 
	echo "true";
 $_SESSION['image_counter']=$_SESSION['image_counter']-1;
 $c = $_SESSION['next_image']-1;
 if($image == $_SESSION['image1'])
 { // the first image is deleted so we sift all the other fotos
 if($c == 1) { unset($_SESSION['image1']); $_SESSION['next_image'] = 1; }
 if($c == 2) { if(isset($_SESSION['image2']))
				{ $_SESSION['image1'] = $_SESSION['image2']; 
				unset($_SESSION['image2']); 
				$_SESSION['next_image'] = 2; }
				if(isset($_SESSION['image3']))
				{ $_SESSION['image1'] = $_SESSION['image3']; 
				unset($_SESSION['image3']); 
				$_SESSION['next_image'] = 2; }
			}
 if($c == 3) { 
	if(isset($_SESSION['image2'])) $_SESSION['image1'] = $_SESSION['image2']; 
	if(isset($_SESSION['image3'])) $_SESSION['image2'] = $_SESSION['image3']; 
	unset($_SESSION['image3']);
	$_SESSION['next_image'] = 3; }
  } //end of delete first image
if($image == $_SESSION['image2'])
 {
 if($c == 3) { 
	if(isset($_SESSION['image3'])) $_SESSION['image2'] = $_SESSION['image3']; 
	unset($_SESSION['image3']);
	$_SESSION['next_image'] = 3; }
	else
	{
	unset($_SESSION['image2']);
	$_SESSION['next_image'] = 2;
	}
  }
 if($image == $_SESSION['image3'])
 {unset($_SESSION['image3']); $_SESSION['next_image'] = 3; echo "del=3";}
 } 
else {
	echo "error";
}
 

?>