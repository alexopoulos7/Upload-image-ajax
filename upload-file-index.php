<?php 
session_start(); // start up your PHP session! 

 //if (!isset($_SESSION['image_counter']))
$_SESSION['image_counter'] = 0;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="./js/jquery-1.3.2.js" ></script>
<script type="text/javascript" src="./js/ajaxupload.3.5.js" ></script>

<style type="text/css">
#upload{
	margin:30px 200px; padding:15px;
	font-weight:bold; font-size:1.3em;
	font-family:Arial, Helvetica, sans-serif;
	text-align:center;
	background:#f2f2f2;
	color:#3366cc;
	border:1px solid #ccc;
	width:150px;
	cursor:pointer !important;
	-moz-border-radius:5px; -webkit-border-radius:5px;
}
.darkbg{
	background:#ddd !important;
}
#status{
	font-family:Arial; padding:5px;
}
ul#files{ list-style:none; padding:0; margin:0; }
ul#files li{ padding:10px; margin-bottom:2px; width:200px; float:left; margin-right:10px;}
ul#files li img{ max-width:180px; max-height:150px; }
.success{ background:#99f099; border:1px solid #339933; }
.error{ background:#f0c6c3; border:1px solid #cc6622; }
</style>
<script type="text/javascript" >
 
	
function delete_image(image_id,image_name)
{ 
	alert ('node to delete =' + image_id);
	var files_ul = document.getElementById('files');
	var imgdiv = document.getElementById(image_name);
	files_ul.removeChild(imgdiv);
	var dataString = 'name='+ image_name+'&id='+image_id;
	var url = 'delete-file.php'; // Call Delete Page & In this page delete those image &  db entry 
     
	 $.ajax({url:"delete-file.php", 
			type: "POST", 
			data:dataString,
			success: function(html) {
			if(html == "error")
			{
                    alert('Η εικόνα δεν διαγράφηκε');
            }
			}
			 });
 
     return false;
}
 
$(function(){
		var btnUpload=$('#upload');
		var lnkDelete=$('#link');
		var status=$('#status');
		new AjaxUpload(btnUpload, {
			action: 'upload-file.php',
			name: 'upload-image',
			onSubmit: function(file, ext){
				 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                    // extension is not allowed 
					status.text('Η εικόνα σας πρέπει να είναι: JPG, PNG ή GIF');
					return false;
				}
				status.text('Uploading...');
			},
			onComplete: function(file, response){
				//On completion clear the status
				status.text('');
				//Add uploaded file to list
				//var response1 = response.responseXML.documentElement;
				var result_flag =    $(response).find('result') ;
				
                if(result_flag.text() == "success")
                {
			 	var sessionId =    $(response).find('sessionId');
             	var new_fileName = $(response).find('fileName');
				
                var link = document.createElement("a");
				link.setAttribute("href", "#");
				link.onclick = function() { delete_image(sessionId.text(),new_fileName.text()); return false; };
				link.innerHTML = "Αφαίρεση";
					$('<li id='+new_fileName.text()+'></li>').appendTo('#files').html('<img src="./images/prods/'+new_fileName.text()+'" alt="" /><br />'+file).append(link).addClass('success');
				
                }
                else
                {	var reason = $(response).find('reason');
				
					if(reason.text() == "file_size")
						$('<li></li>').appendTo('#files').text("Η εικόνα είναι πολύ μεγάλη").addClass('error');
					else if(reason.text() == "notJPG")
						$('<li></li>').appendTo('#files').text("Μπορείτε να ανεβάσετε μόνο εικόνες τύπου JPG").addClass('error');
					else
					$('<li></li>').appendTo('#files').text(file).addClass('error');
                }
			}
		});
	});
</script>
</head>
<body>
 
<div id="mainbody" >
		<div id="upload" ><span>Ανέβασε φωτογραφία<span></div><span id="status" ></span>
		
		<ul id="files" >
		
		<?php 
		$txt="";
		if(isset($_SESSION['image1'])) 
			$txt = $txt.'<li id="'.$_SESSION['image1'].'" class="success"><img alt="" src="./images/prods/'.$_SESSION['image1'].'"><br>'.$_SESSION['image1'].'<a href="#" onclick="javascript:delete_image(1,'."'".$_SESSION['image1']."'".');return false">Αφαίρεση</a></li>';
		if(isset($_SESSION['image2'])) 
			$txt = $txt.'<li id="'.$_SESSION['image2'].'" class="success"><img alt="" src="./images/prods/'.$_SESSION['image2'].'"><br>'.$_SESSION['image2'].'<a href="#" onclick="javascript:delete_image(2,'."'".$_SESSION['image2']."'".');return false">Αφαίρεση</a></li>';
		if(isset($_SESSION['image3'])) 
			$txt = $txt.'<li id="'.$_SESSION['image3'].'" class="success"><img alt="" src="./images/prods/'.$_SESSION['image3'].'"><br>'.$_SESSION['image3'].'<a href="#" onclick="javascript:delete_image(3,'."'".$_SESSION['image3']."'".');return false">Αφαίρεση</a></li>';
		echo $txt;?>
		</ul>
</div>

</body>