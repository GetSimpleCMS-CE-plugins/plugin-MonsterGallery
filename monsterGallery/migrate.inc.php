<style>
	.migrateMe input{
		width: 100%;
		box-sizing: border-box;
		padding: 5px;
		margin: 5px;
	}

	.migrateMe label{
		padding: 5px;
	}

	.migrateMe input[type="submit"]{
		background: #000;
		border:solid 1px #000;
		color:#fff;	
	}

	.done{
		width:100%;
		background: green;
		width: 100%;
		margin: 5px;
		box-sizing: border-box;
		padding: 10px;
		color: #fff;
	}
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
	
	.mg-btn.mg-save{
		background-color:#4CAF50!important;
	}
</style>
<div style="display:flex;width:100%;justify-content:space-between;align-items:center;">
	<h3><?php echo i18n_r('monsterGallery/LANG_Migrate_MG') ;?></h3>
	<a href="<?php
				global $SITEURL;
				global $GSADMIN;

				echo $SITEURL . $GSADMIN . '/load.php?id=monsterGallery&monsterGalleryList'; ?>" class="mg-btn mg-migrate"><?php echo i18n_r('monsterGallery/LANG_Back_To_List'); ?></a>
</div>

<hr>

<form action="#" method="post" class="migrateMe">
	<label for=""><?php echo i18n_r('monsterGallery/LANG_Old_URL') ;?></label>
	<input type="text" name="oldurl" placeholder="https://youroldadress.com/">
	
	<label for=""><?php echo i18n_r('monsterGallery/LANG_New_URL') ;?></label>
	<input type="text" name="newurl"  placeholder="https://yournewadress.com/">
	
	<input class="mg-btn mg-save" type="submit" name="submit" value="<?php echo i18n_r('monsterGallery/LANG_Update') ;?>">
</form>

<?php 
	if(isset($_POST['submit'])){
		foreach(glob(GSDATAOTHERPATH.'monsterGallery/*.json')as $file){
			$fileContent = file_get_contents($file);

			$oldurl = str_replace('/','\/',$_POST['oldurl']);
			$newurl = str_replace('/','\/',$_POST['newurl']);

			$newContent = str_replace([$oldurl, $oldurl.'/'],[$newurl, $newurl.'/'],$fileContent);

			file_put_contents($file,$newContent);
		}

		echo '<div class="done">'. i18n_r('monsterGallery/LANG_Done') .'</div>';
	}
;?>