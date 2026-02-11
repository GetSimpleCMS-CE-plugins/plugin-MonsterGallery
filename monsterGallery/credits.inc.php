<style>
	.mg-btn{
		border-radius:5px;
		padding:0.5rem 1rem;
		text-decoration: none;
		color:#fff!important;
	}
		
	.mg-btn.mg-new{
		background-color:#004F99!important;
	}
	
	.mg-btn.mg-migrate{
		background-color:#000!important;
	}
</style>

<div style="display:flex;width:100%;justify-content:space-between;align-items:center;">
	<h3><?php echo i18n_r('monsterGallery/LANG_Credits') ;?></h3>
	<a href="<?php
				global $SITEURL;
				global $GSADMIN;

				echo $SITEURL . $GSADMIN . '/load.php?id=monsterGallery&monsterGalleryList'; ?>" class="mg-btn mg-migrate"><?php echo i18n_r('monsterGallery/LANG_Back_To_List'); ?></a>
</div>

<hr>

<p><?php echo i18n_r('monsterGallery/LANG_Galleries_used') ;?>:</p>

<ul style="list-style-type:square;margin-top:10px;margin-left:15px">
	<li><a href="https://biati-digital.github.io/glightbox/" target="_blank"><?php echo i18n_r('monsterGallery/LANG_GlightBox') ;?></a></li>
	<li><a href="https://nextapps-de.github.io/spotlight/" target="_blank"><?php echo i18n_r('monsterGallery/LANG_SpotLight') ;?></a></li>
	<li><a href="https://feimosi.github.io/baguetteBox.js/" target="_blank"><?php echo i18n_r('monsterGallery/LANG_BaguetteBox') ;?></a></li>
	<li><a href="https://photoswipe.com/" target="_blank"><?php echo i18n_r('monsterGallery/LANG_PhotoSwipe') ;?></li>
</ul>