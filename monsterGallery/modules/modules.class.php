<?php

class MonsterModules
{

	public $name;
	public $data;
	public $dataJson;
	public $width;
	public $height;
	public $gap;
	public $quality;
	public $modules;
	public $gal;
	public $ownclass;

	public $styleCSS;
	public $mobilegap;
	public $mobileheight;
	public $mobilewidth;
	public $thumbfit;
	public $descunder;
	public $modcheck;
	public $slideshow;
	public $layout;
	public $coverimage;
	public $herotransition;
	public $heroclick;

	public function set_name($matches)
	{
		$this->name = $matches[1];
		$this->data = file_get_contents(GSDATAOTHERPATH . 'monsterGallery/' . $this->name . '.json');
		$this->dataJson = json_decode($this->data, false);
		$this->width = $this->dataJson->width;
		$this->height = $this->dataJson->height;
		$this->gap = $this->dataJson->gap;
		$this->quality = $this->dataJson->quality;
		$this->modules = $this->dataJson->modules;
		$this->ownclass = $this->dataJson->ownclass;
		$this->thumbfit = $this->dataJson->thumbfit;
		$this->mobilewidth = $this->dataJson->mobilewidth;
		$this->mobileheight = $this->dataJson->mobileheight;
		$this->mobilegap = $this->dataJson->mobilegap;
		$this->slideshow = $this->dataJson->slideshow ?? 'slideshow';
		$this->layout = $this->dataJson->layout ?? 'grid';
		$this->coverimage = $this->dataJson->coverimage ?? '';
		$this->herotransition = $this->dataJson->herotransition ?? 'slide';
		$this->heroclick = $this->dataJson->heroclick ?? 'none';

		$this->styleCSS = '

		<style>
		
		.monsterGallery-grid{
			display:flex;
			flex-direction:row;
			flex-wrap:wrap;'

			. ($this->gap == '' ? '' : 'gap:' . $this->gap) .

			'
		}
		
		@media(max-width:768px){
		
		 
		
			.monsterGallery-grid{
				width:95%;
				margin:0 auto;
				display:flex;
				flex-wrap:wrap;
				flex-direction:row;
				' . ($this->mobilegap == '' ? '' : 'gap:' . $this->mobilegap . ' !improtant') . ';
			}

		
		.monsterGallery-grid a{
			margin:0;
			padding:0;
			width:' . $this->mobilewidth . ' !important;
			height:' . $this->mobileheight . ' !important;
		}
		
		.monsterGallery-grid img{
			max-width:100% !important;}}
		</style>
		';
	}

	public function set_name_frontend($matches)
	{
		global $modules;
		$this->name = $matches;
		$this->data = file_get_contents(GSDATAOTHERPATH . 'monsterGallery/' . $this->name . '.json');
		$this->dataJson = json_decode($this->data, false);
		$this->width = $this->dataJson->width;
		$this->height = $this->dataJson->height;
		$this->gap = $this->dataJson->gap;
		$this->quality = $this->dataJson->quality;
		$this->modules = $this->dataJson->modules;
		$this->ownclass = $this->dataJson->ownclass;
		$this->thumbfit = $this->dataJson->thumbfit;
		$this->descunder = $this->dataJson->descunder ?? '';
		$this->mobilewidth = $this->dataJson->mobilewidth;
		$this->mobileheight = $this->dataJson->mobileheight;
		$this->mobilegap = $this->dataJson->mobilegap;
		$this->slideshow = $this->dataJson->slideshow ?? 'slideshow';
		$this->layout = $this->dataJson->layout ?? 'grid';
		$this->coverimage = $this->dataJson->coverimage ?? '';
		$this->herotransition = $this->dataJson->herotransition ?? 'slide';
		$this->heroclick = $this->dataJson->heroclick ?? 'none';

		$this->styleCSS = '

		<style>
		
		.monsterGallery-grid{
			display:flex;
			flex-direction:row;
			flex-wrap:wrap;'

			. ($this->gap == '' ? '' : 'gap:' . $this->gap) .

			'
		}
		
		@media(max-width:768px){
		
		
			.monsterGallery-grid{
				width:95%;
				margin:0 auto;
				display:flex;
				flex-wrap:wrap;
				flex-direction:row;
				' . ($this->mobilegap == '' ? '' : 'gap:' . $this->mobilegap . ' !improtant') . ';
			}

		
		.monsterGallery-grid a{
			margin:0;
			padding:0;
			width:' . $this->mobilewidth . ' !important;
			height:' . $this->mobileheight . ' !important;
		}
		
		.monsterGallery-grid img{
			max-width:100% !important;}}
		</style>
		';
	}

	function getNameModules()
	{
		return $this->modules;
	}


	private function MGthumb($values, $width)
	{

		$file = file_get_contents($values);

		$folder = GSDATAOTHERPATH . "monsterGallery/thumb/";
		if (!file_exists($folder)) {
			mkdir($folder, 0755);
			file_put_contents($folder . '.htaccess', 'Allow from all');
		};


		$extension =  pathinfo($values, PATHINFO_EXTENSION);
		$base = pathinfo($values, PATHINFO_BASENAME);
		$directoryPath = dirname($values);
$parentDirectory = basename($directoryPath);
		$base = hash('md4', $base);
		$base = hash('md2', $base);
		$finalfile = $folder . $width . "-" .$parentDirectory."-". $base . '.' . $extension;
		if (file_exists($finalfile)) {
		} else {
			$origPic = imagecreatefromstring($file);
			$width_orig = imagesx($origPic);
			$height_orig = imagesy($origPic);
			$height = $height_orig  * 1.77;
			$ratio_orig = $width_orig / $height_orig;
			if ($width / $height > $ratio_orig) {
				$width = $height * $ratio_orig;
			} else {
				$height = $width / $ratio_orig;
			}

			$thumbnail = @imagecreatetruecolor($width, $height);

			@imagecopyresampled($thumbnail, $origPic, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);



			if ($extension == 'jpeg' || $extension == 'jpg') {
				imagejpeg($thumbnail, $finalfile);
			} elseif ($extension == 'png') {

				imagealphablending($thumbnail, false);
				imagesavealpha($thumbnail, true);
				$transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
				imagefilledrectangle($thumbnail, 0, 0, $width, $height, $transparent);
				$src_w = imagesx($thumbnail);
				$src_h = imagesy($thumbnail);

				imagecopyresampled($thumbnail, $origPic, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
				imagepng($thumbnail, $finalfile);
			} elseif ($extension == 'webp') {

				imagealphablending($thumbnail, false);
				imagesavealpha($thumbnail, true);
				$transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
				imagefilledrectangle($thumbnail, 0, 0, $width, $height, $transparent);
				$src_w = imagesx($thumbnail);
				$src_h = imagesy($thumbnail);

				imagecopyresampled($thumbnail, $origPic, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);


				imagewebp($thumbnail, $finalfile);
			} elseif ($extension == 'gif') {
				imagegif($thumbnail, $finalfile);
			} elseif ($extension == 'bmp') {
				imagebmp($thumbnail, $finalfile);
			} else {
				imagejpeg($thumbnail, $finalfile);
			}


			imagedestroy($origPic);
			imagedestroy($thumbnail);
		};

		global $SITEURL;
		return str_replace(GSDATAOTHERPATH, $SITEURL . 'data/other/', $finalfile);
	}





	function glightbox()
	{
		global $modules;
		$this->gal = $this->styleCSS;

		// Unique ID for this gallery instance so JS can scope each lightbox independently
		$galleryId = 'mg-' . md5($this->name . microtime());

		$slideshowMode  = $this->slideshow      ?: 'slideshow';
		$layoutMode     = $this->layout         ?: 'grid';
		$coverImage     = $this->coverimage     ?: '';
		$heroTransition = $this->herotransition ?: 'slide';
		$heroClick      = $this->heroclick      ?: 'none';

		global $SITEURL;

		if ($layoutMode === 'hero') {

			// --- HERO MODE: inline cycling slideshow ---
			// Build ordered image list: cover image first, then the rest
			$images = $this->dataJson->images;
			$names  = $this->dataJson->names;
			$descs  = $this->dataJson->descriptions;

			// Reorder so cover image is index 0
			$coverIdx = 0;
			if ($coverImage !== '') {
				foreach ($images as $k => $v) {
					if ($v === $coverImage) { $coverIdx = $k; break; }
				}
			}
			$orderedImages = array();
			$orderedNames  = array();
			$orderedDescs  = array();
			// Cover first
			$orderedImages[] = $images[$coverIdx];
			$orderedNames[]  = $names[$coverIdx];
			$orderedDescs[]  = $descs[$coverIdx];
			// Then the rest in original order
			foreach ($images as $k => $v) {
				if ($k !== $coverIdx) {
					$orderedImages[] = $v;
					$orderedNames[]  = $names[$k];
					$orderedDescs[]  = $descs[$k];
				}
			}

			// Wrapper div — same dimensions as a normal thumbnail
			$this->gal .= '<div class="monsterGallery-grid monsterGallery-hero ' . $this->ownclass . '"'
				. ' data-mg-gallery-id="' . $galleryId . '"'
				. ' data-mg-layout="hero"'
				. ' data-mg-slideshow="' . htmlspecialchars($slideshowMode) . '"'
				. ' data-mg-slideshow-time="4000"'
				. ' data-mg-hero-transition="' . htmlspecialchars($heroTransition) . '"'
				. ' data-mg-hero-click="' . htmlspecialchars($heroClick) . '"'
				. ' style="position:relative;overflow:hidden;width:' . $this->width . ';height:' . $this->height . ';cursor:' . ($heroClick === 'lightbox' ? 'pointer' : 'default') . ';">';

			// Stack all images absolutely, first one on top
			foreach ($orderedImages as $idx => $value) {
				$forthumb = str_replace($SITEURL . 'data/uploads/', GSDATAUPLOADPATH, $value);
				$this->gal .= '<img'
					. ' src="' . $this->MGthumb($forthumb, $this->quality) . '"'
					. ' data-mg-hero-src="' . $value . '"'
					. ' alt="' . htmlspecialchars($orderedNames[$idx]) . '"'
					. ' style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:' . $this->thumbfit . ';'
					. 'opacity:' . ($idx === 0 ? '1' : '0') . ';'
					. 'transition:opacity 0.6s ease;'
					. '">';
			}

			$this->gal .= '</div>';

			// If click=lightbox, also emit hidden <a> tags for GLightbox to pick up
			if ($heroClick === 'lightbox') {
				$this->gal .= '<div style="display:none;">';
				foreach ($orderedImages as $idx => $value) {
					$this->gal .= '<a href="' . $value . '" class="glightbox"'
						. ' data-gallery="' . $galleryId . '"'
						. ' data-title="' . htmlspecialchars($orderedNames[$idx]) . '"'
						. ' data-description="' . htmlspecialchars($orderedDescs[$idx]) . '"'
						. ' data-zoomable="true"></a>';
				}
				$this->gal .= '</div>';
			}

		} else {

			// --- GRID MODE: normal thumbnail grid ---
			$this->gal .= '<div class="monsterGallery-grid ' . $this->ownclass . '"'
				. ' data-mg-gallery-id="' . $galleryId . '"'
				. ' data-mg-slideshow="' . htmlspecialchars($slideshowMode) . '"'
				. ' data-mg-layout="grid"'
				. ' data-mg-slideshow-time="4000">';

			foreach ($this->dataJson->images as $key => $value) {
				$forthumb = str_replace($SITEURL . 'data/uploads/', GSDATAUPLOADPATH, $value);
				$this->gal .= '<a href="' . $value . '" class="glightbox" style="width:' . $this->width . ';height:' . $this->height . ';"'
					. ' data-gallery="' . $galleryId . '"'
					. ' data-title="' . htmlspecialchars($this->dataJson->names[$key]) . '"'
					. ' data-description="' . htmlspecialchars($this->dataJson->descriptions[$key]) . '"'
					. ' data-zoomable="true">'
					. '<img src="' . $this->MGthumb($forthumb, $this->quality) . '" style="width:100%;height:100%;object-fit:' . $this->thumbfit . ';"></a>';
			}

			$this->gal .= '</div>';
		}

		$modules = 'glightbox';
	}
	//end glightbox


	function photoswipe()
	{
		$this->gal = $this->styleCSS;

		$this->gal .= '<div class="monsterGallery-grid ' . $this->ownclass . '" id="gallery--with-custom-caption">';

		foreach ($this->dataJson->images as $key => $value) {

			global $SITEURL;

			$forthumb = str_replace($SITEURL . 'data/uploads/', GSDATAUPLOADPATH, $value);
			$this->gal .=  '<a href="' . $value . '" style="width:' . $this->width . ';height:' . $this->height . ';"  class="pswp-gallery__item" >
<img src="' . $this->MGthumb($forthumb, $this->quality) . '" style="width:100%;height:100%;object-fit:' . $this->thumbfit . ';"
alt="' . $this->dataJson->names[$key] . ' ' . $this->dataJson->descriptions[$key] . '"
></a>';
		}
		$this->gal .= '</div>';

		global $modules;
		$modules = 'PhotoSwipe';
	}
	//end photoswipe

	function simplelightbox()
	{


		$this->gal = $this->styleCSS;


		$this->gal .= '<div class="monsterGallery-grid gallery ' . $this->ownclass . '">';

		foreach ($this->dataJson->images as $key => $value) {

			global $SITEURL;

			$forthumb = str_replace($SITEURL . 'data/uploads/', GSDATAUPLOADPATH, $value);
			$this->gal .=  '<a href="' . $value . '"  style="width:' . $this->width . ';height:' . $this->height . ';"   data-title="' . $this->dataJson->names[$key] . '"
 data-description="' . $this->dataJson->descriptions[$key] . '" data-zoomable="true"><img src="' . $this->MGthumb($forthumb, $this->quality) . '" style="width:100%;height:100%;object-fit:' . $this->thumbfit . ';"></a>';
		}

		$this->gal .= '</div>';

		global $modules;
		$modules = 'simplelightbox';
	}

	//end simplelightbox


	function spotlight()
	{
		$this->gal = $this->styleCSS;

		$this->gal .= '<div class="monsterGallery-grid spotlight-group ' . $this->ownclass . '">';

		foreach ($this->dataJson->images as $key => $value) {
			global $SITEURL;
			$forthumb = str_replace($SITEURL . 'data/uploads/', GSDATAUPLOADPATH, $value);
			$this->gal .=  '<a href="' . $value . '"  class="spotlight"  style="width:' . $this->width . ';height:' . $this->height . ';"    data-title="' . $this->dataJson->names[$key] . '"
 data-description="' . $this->dataJson->descriptions[$key] . '" data-zoomable="true"><img src="' . $this->MGthumb($forthumb, $this->quality) . '" style="width:100%;height:100%;object-fit:' . $this->thumbfit . ';"></a>';
		}

		$this->gal .= '</div>';
		global $modules;
		$modules = 'spotlight';
	}

	//end spotlight

	function baguettebox()
	{

		$this->gal = $this->styleCSS;


		$this->gal .= '<div class="monsterGallery-grid gallery ' . $this->ownclass . '">';

		foreach ($this->dataJson->images as $key => $value) {

			global $SITEURL;

			$forthumb = str_replace($SITEURL . 'data/uploads/', GSDATAUPLOADPATH, $value);
			$this->gal .=  '<a href="' . $value . '"  class="glightbox" style="width:' . $this->width . ';height:' . $this->height . ';"    data-caption="<h4>' . $this->dataJson->names[$key] . '</h4> ' . $this->dataJson->descriptions[$key] . '">
<img alt="' . $this->dataJson->names[$key] . ' ' . $this->dataJson->descriptions[$key] . '" src="' . $this->MGthumb($forthumb, $this->quality) . '" style="width:100%;height:100%;object-fit:' . $this->thumbfit . ';"></a>';
		}

		$this->gal .= '</div>';

		global $modules;
		$modules = 'baguettebox';
	}

	//end baguettebox

	public function modulesCheck()
	{

		echo $this->modcheck;
	}
};
