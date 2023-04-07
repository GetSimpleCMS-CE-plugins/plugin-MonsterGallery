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
</style>

<h3><?php echo i18n_r('monsterGallery/LANG_Migrate_MG') ;?></h3>

<form action="#" method="post" class="migrateMe">
	<label for=""><?php echo i18n_r('monsterGallery/LANG_Old_URL') ;?></label>
	<input type="text" name="oldurl" placeholder="https://youroldadress.com/">
	
	<label for=""><?php echo i18n_r('monsterGallery/LANG_New_URL') ;?></label>
	<input type="text" name="newurl"  placeholder="https://yournewadress.com/">
	
	<input type="submit" name="submit" value="<?php echo i18n_r('monsterGallery/LANG_Update') ;?>">
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