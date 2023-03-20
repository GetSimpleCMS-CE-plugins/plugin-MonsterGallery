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
	}

	function getNameModules()
	{
		return $this->modules;
	}


	private function MGthumb($values, $width)
	{

		$file = file_get_contents($values);
		$folder = GSPLUGINPATH . "monsterGallery/thumb/";
		$extension =  pathinfo($values, PATHINFO_EXTENSION);
		$base = pathinfo($values, PATHINFO_BASENAME);
		$finalfile = $folder . $width . "-" . $base;
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
				imagepng($thumbnail, $finalfile);
			} elseif ($extension == 'webp') {
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
		return str_replace(GSPLUGINPATH, $SITEURL . 'plugins/', $finalfile);
	}





	function glightbox()
	{

		$this->gal = '
<style>

.monsterGallery-grid{
	display:flex;
	flex-direction:row;
	flex-wrap:wrap;'

			. ($this->gap == '' ? '' : 'gap:' . $this->gap . 'px') .

			'
}

@media(max-width:768px){

	.monsterGallery{
		width:95%;
		margin:0 auto;
		display:flex;
		flex-wrap:wrap;
		flex-direction:column;
	}


.monsterGallery-grid a{
	margin:0;
	padding:0;
}

.monsterGallery-grid img{
	max-width:100% !important;}}
</style>
';


		$this->gal .= '<div class="monsterGallery-grid">';

		foreach ($this->dataJson->images as $key => $value) {

			global $SITEURL;

			$forthumb = str_replace($SITEURL . 'data/uploads/', GSDATAUPLOADPATH, $value);
			$this->gal .=  '<a href="' . $value . '"  class="glightbox"    data-title="' . $this->dataJson->names[$key] . '"
 data-description="' . $this->dataJson->descriptions[$key] . '" data-zoomable="true"><img src="' . $this->MGthumb(
				$forthumb,
				$this->quality
			) . '" style="width:' . $this->width . 'px;height:' . $this->height . 'px;object-fit:cover;"></a>';
		}
		$this->gal .= '</div>';
		global $modules;
		$modules = 'glightbox';
	}
		//end glightbox


	function photoswipe()
	{
		$this->gal = '
<style>
.monsterGallery-grid{
	display:flex;
	flex-direction:row;
	flex-wrap:wrap;'

			. ($this->gap == '' ? '' : 'gap:' . $this->gap . 'px') .

			'
}

@media(max-width:768px){

	.monsterGallery{
		width:95%;
		margin:0 auto;
		display:flex;
		flex-wrap:wrap;
		flex-direction:column;
	}


.monsterGallery-grid a{
	margin:0;
	padding:0;
}

.monsterGallery-grid img{
	max-width:100% !important;}}
</style>
';

		$this->gal .= '<div class="monsterGallery-grid" id="gallery--with-custom-caption">';

		foreach ($this->dataJson->images as $key => $value) {

			global $SITEURL;

			$forthumb = str_replace($SITEURL . 'data/uploads/', GSDATAUPLOADPATH, $value);
			$this->gal .=  '<a href="' . $value . '" class="pswp-gallery__item" >
<img src="' . $this->MGthumb($forthumb, $this->quality) . '" style="width:' . $this->width . 'px;height:' . $this->height . 'px;object-fit:cover;"
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


		$this->gal = '

<style>

.monsterGallery-grid{
	display:flex;
	flex-direction:row;
	flex-wrap:wrap;'

			. ($this->gap == '' ? '' : 'gap:' . $this->gap . 'px') .

			'
}

@media(max-width:768px){

	.monsterGallery{
		width:95%;
		margin:0 auto;
		display:flex;
		flex-wrap:wrap;
		flex-direction:column;
	}


.monsterGallery-grid a{
	margin:0;
	padding:0;
}

.monsterGallery-grid img{
	max-width:100% !important;}}
</style>
';


		$this->gal .= '<div class="monsterGallery-grid gallery">';

		foreach ($this->dataJson->images as $key => $value) {

			global $SITEURL;

			$forthumb = str_replace($SITEURL . 'data/uploads/', GSDATAUPLOADPATH, $value);
			$this->gal .=  '<a href="' . $value . '"  data-title="' . $this->dataJson->names[$key] . '"
 data-description="' . $this->dataJson->descriptions[$key] . '" data-zoomable="true"><img src="' . $this->MGthumb($forthumb, $this->quality) . '" style="width:' . $this->width . 'px;height:' . $this->height . 'px;object-fit:cover;"></a>';
		}

		$this->gal .= '</div>';

		global $modules;
		$modules = 'simplelightbox';
	}

		//end simplelightbox


	function spotlight()
	{
		$this->gal = '

<style>

.monsterGallery-grid{
	display:flex;
	flex-direction:row;
	flex-wrap:wrap;'

			. ($this->gap == '' ? '' : 'gap:' . $this->gap . 'px') .

			'
}

@media(max-width:768px){
	.monsterGallery{
		width:95%;
		margin:0 auto;
		display:flex;
		flex-wrap:wrap;
		flex-direction:column;
	}

.monsterGallery-grid a{
	margin:0;
	padding:0;
}
.monsterGallery-grid img{
	max-width:100% !important;}}
</style>
';
		$this->gal .= '<div class="monsterGallery-grid spotlight-group">';

		foreach ($this->dataJson->images as $key => $value) {
			global $SITEURL;
			$forthumb = str_replace($SITEURL . 'data/uploads/', GSDATAUPLOADPATH, $value);
			$this->gal .=  '<a href="' . $value . '"  class="spotlight"    data-title="' . $this->dataJson->names[$key] . '"
 data-description="' . $this->dataJson->descriptions[$key] . '" data-zoomable="true"><img src="' . $this->MGthumb($forthumb, $this->quality) . '" style="width:' . $this->width . 'px;height:' . $this->height . 'px;object-fit:cover;"></a>';
		}

		$this->gal .= '</div>';
		global $modules;
		$modules = 'spotlight';
	}

		//end spotlight

	function baguettebox()
	{

		$this->gal = '

<style>

.monsterGallery-grid{
	display:flex;
	flex-direction:row;
	flex-wrap:wrap;'

			. ($this->gap == '' ? '' : 'gap:' . $this->gap . 'px') .

			'
}

@media(max-width:768px){

	.monsterGallery{
		width:95%;
		margin:0 auto;
		display:flex;
		flex-wrap:wrap;
		flex-direction:column;
	}


.monsterGallery-grid a{
	margin:0;
	padding:0;
}

.monsterGallery-grid img{
	max-width:100% !important;}}
</style>
';


		$this->gal .= '<div class="monsterGallery-grid gallery">';

		foreach ($this->dataJson->images as $key => $value) {

			global $SITEURL;

			$forthumb = str_replace($SITEURL . 'data/uploads/', GSDATAUPLOADPATH, $value);
			$this->gal .=  '<a href="' . $value . '"  class="glightbox"    data-caption="<h4>' . $this->dataJson->names[$key] . '</h4> ' . $this->dataJson->descriptions[$key] . '">
<img alt="' . $this->dataJson->names[$key] . ' ' . $this->dataJson->descriptions[$key] . '" src="' . $this->MGthumb($forthumb, $this->quality) . '" style="width:' . $this->width . 'px;height:' . $this->height . 'px;object-fit:cover;"></a>';
		}

		$this->gal .= '</div>';

		global $modules;

		$modules = 'baguettebox';
	}

	//end baguettebox
};
