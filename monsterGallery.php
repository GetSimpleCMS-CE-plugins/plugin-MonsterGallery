<?php

# get correct id for plugin
$thisfile=basename(__FILE__, ".php");
 
# register plugin
register_plugin(
	$thisfile, //Plugin id
	'MonsterGallery', 	//Plugin name
	'1.0.1', 		//Plugin version
	'Multicolor',  //Plugin author
	'https://github.com/multicolor-rgb', //author website
	'Gallery plugin you want use it!', //Plugin description
	'pages', //page type - on which admin tab to display
	'monsterGallery'  //main function (administration)
);

# add a link in the admin tab 'theme'
add_action('pages-sidebar','createSideMenu',array($thisfile,'MonsterGallery Settings','monsterGalleryList'));

add_action('theme-header','makeMagic');

 
function monsterGallery() {


	if(isset($_GET['credits'])){
		include(GSPLUGINPATH.'monsterGallery/credits.php');
	};
	

if(isset($_GET['monsterGalleryList'])){
	include(GSPLUGINPATH.'monsterGallery/settings.php');
};


if(isset($_GET['addMonsterGallery'])){
 include(GSPLUGINPATH.'monsterGallery/addNewMonsterGallery.php');
}


if(isset($_GET['delete'])){

unlink(GSDATAOTHERPATH.'monsterGallery/'.$_GET['delete'].'.json');


global $SITEURL;

echo "<script type='text/javascript'>
window.location.href = '".$SITEURL."admin/load.php?id=monsterGallery&monsterGalleryList';
</script>";

};






 

};


$MGmodules;


function makeMagic(){


function MGthumb($values,$width){


 
 $file = file_get_contents($values);
 
 $folder =GSPLUGINPATH."monsterGallery/thumb/";


 $extension =  pathinfo($values, PATHINFO_EXTENSION);

 $base = pathinfo($values, PATHINFO_BASENAME);
 
 $finalfile = $folder.$width."-".$base ;
 
 if(file_exists($finalfile) ){

 }else{
 
	$origPic = imagecreatefromstring($file);
 
	$width_orig=imagesx($origPic);
	$height_orig=imagesy($origPic);
	
	$height = $height_orig  * 1.77;


	$ratio_orig = $width_orig/$height_orig;
	
	if ($width/$height > $ratio_orig) {
	   $width = $height*$ratio_orig;
	} else {
	   $height = $width/$ratio_orig;
	}
	
	
	$thumbnail = @imagecreatetruecolor($width,$height);
	
	@imagecopyresampled($thumbnail,$origPic,0,0,0,0,$width,$height,$width_orig,$height_orig);
	

	if($extension == 'jpeg' || $extension == 'jpg'){
		imagejpeg($thumbnail, $finalfile);
	}elseif($extension == 'png'){
		imagepng($thumbnail, $finalfile);
	}elseif($extension == 'webp'){
		imagewebp($thumbnail, $finalfile);
	}elseif($extension == 'gif'){
		imagegif($thumbnail, $finalfile);
	}
	elseif($extension == 'bmp'){
		imagebmp($thumbnail, $finalfile);
	}else{
		imagejpeg($thumbnail, $finalfile);
	}

	
	imagedestroy($origPic);
	imagedestroy($thumbnail);
 };

global $SITEURL;  
return str_replace(GSPLUGINPATH,$SITEURL.'plugins/',$finalfile);
};



function mgShow($matches){
$name = $matches[1];
$data = file_get_contents(GSDATAOTHERPATH.'monsterGallery/'.$name.'.json');
$dataJson = json_decode($data,false);
$width = $dataJson->width;
$height = $dataJson->height;
$gap = $dataJson->gap;
$quality = $dataJson->quality;


if($dataJson->modules == 'glightbox'){
include(GSPLUGINPATH.'monsterGallery/modules/glightbox.php');
};

if($dataJson->modules == 'PhotoSwipe'){
include(GSPLUGINPATH.'monsterGallery/modules/photoswipe.php');
};


if($dataJson->modules == 'spotlight'){
include(GSPLUGINPATH.'monsterGallery/modules/spotlight.php');
};

if($dataJson->modules == 'simplelightbox'){
include(GSPLUGINPATH.'monsterGallery/modules/simplelightbox.php');
};

if($dataJson->modules == 'baguettebox'){
include(GSPLUGINPATH.'monsterGallery/modules/baguettebox.php');
};

return $gal;
};


global $content;
$newcontent = preg_replace_callback(
            '/\\[% mg=(.*) %\\]/i',
            "mgShow",
            $content);
$content = $newcontent;
add_action('theme-header','test');
};



/// styleloader 

add_action('theme-header','styleloader');


function styleloader(){
global $SITEURL;
global $modules;

if(isset($modules)){

if($modules == 'glightbox'){
echo '<link rel="stylesheet" href="'.$SITEURL.'plugins/monsterGallery/modules/glightbox/glightbox.min.css">';
};

if($modules == 'PhotoSwipe'){
echo '<link rel="stylesheet" href="'.$SITEURL.'plugins/monsterGallery/modules/photoswipe/photoswipe.css">';
};



if($modules == 'spotlight'){
 echo '<script src="'.$SITEURL.'plugins/monsterGallery/modules/spotlight/spotlight.bundle.js"></script>';
};



if($modules == 'simplelightbox'){
echo '<link rel="stylesheet" href="'.$SITEURL.'plugins/monsterGallery/modules/simplelightbox/simple-lightbox.min.css">';
};

if($modules == 'baguettebox'){
echo '<link rel="stylesheet" href="'.$SITEURL.'plugins/monsterGallery/modules/
baguettebox/baguetteBox.min.css">';
};


};


}


add_action('theme-footer','scriptloader');


function scriptloader(){
global $SITEURL;
global $modules;

if(isset($modules)){

if($modules == 'glightbox'){
 echo '<script src="'.$SITEURL.'plugins/monsterGallery/modules/glightbox/glightbox.min.js"></script>';
 echo '<script src="'.$SITEURL.'plugins/monsterGallery/modules/glightbox/glightboxrun.js"></script>';
};


if($modules == 'PhotoSwipe'){
 echo '<script type="module" src="'.$SITEURL.'plugins/monsterGallery/modules/photoswipe/photoSwipeModule.js"></script>';
};



if($modules == 'simplelightbox'){
 echo '<script src="'.$SITEURL.'plugins/monsterGallery/modules/simplelightbox/simple-lightbox.min.js"></script>';

echo"
<script>
let gallery = new SimpleLightbox('.gallery a');
gallery.on('show.simplelihtbox', function (e) {

e.captions = true;
e.captionSelector = 'a';
e.captionType = 'data-title';
});

</script>

";

};



if($modules == 'baguettebox'){
 echo '<script async  src="'.$SITEURL.'plugins/monsterGallery/modules/baguettebox/baguetteBox.min.js"></script>';

echo'<script>

window.addEventListener("load", function() {
  baguetteBox.run(".gallery",(element)=>{
  	        return element.getElementsByTagName("img")[0].alt;
  	});
});


</script>';

};



};


}


 




?>