<style>
	.galitem {
		width: 100%;
		display: grid;
		grid-template-columns: 1fr 1fr 160px;
		padding: 10px;
		border: solid 1px #ddd;
		background: #fafafa;
		box-sizing: border-box;
		margin: 2px 0;
		align-items: center;
		border-radius: 5px;
	}

	.galitem a {
		background: #000;
		color: #fff !important;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #fff;
		padding: 0.5rem 1rem;
		text-decoration: none !important;
	}

	.galitem p {
		margin: 0 !important;
		padding: 0 !important;
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
	
	.mg-btn.mg-delete{
		background-color:#FF3333!important;
	}
	
	.mg-btn.mg-migrate{
		background-color:#000!important;
	}
	
	.mg-btn.mg-credits{
		background-color:#8B8B8B!important;
	}
	
	.mg-btn.mg-edit{
		background-color:#FF9900!important;
	}
	
</style>

<h3><?php echo i18n_r('monsterGallery/LANG_MG_List'); ?></h3>

<div id="mg_nav">
	<a id="mg_new" href="<?php global $SITEURL;
				echo $SITEURL; ?>admin/load.php?id=monsterGallery&addMonsterGallery" class="mg-btn mg-new"><?php echo i18n_r('monsterGallery/LANG_Add_New'); ?></a>

	<a id="mg_cache" href="<?php global $SITEURL;
				echo $SITEURL; ?>admin/load.php?id=monsterGallery&monsterGalleryList&clearCache" class="mg-btn mg-delete"><?php echo i18n_r('monsterGallery/LANG_Clear_Cache'); ?></a>

	<a id="mg_migrate" href="<?php global $SITEURL;
				echo $SITEURL; ?>admin/load.php?id=monsterGallery&migrateGallery" class="mg-btn mg-migrate"><?php echo i18n_r('monsterGallery/LANG_Migrate'); ?></a>

	<a id="mg_credits" href="<?php global $SITEURL;
				echo $SITEURL; ?>admin/load.php?id=monsterGallery&credits" class="mg-btn mg-credits"><?php echo i18n_r('monsterGallery/LANG_Credits'); ?></a>
</div>

<ul style="margin:0;padding: 0;display: block;margin-top: 30px;">
	<li class="galitem" style="font-weight: bold;">
		<p id="gal-title"><?php echo i18n_r('monsterGallery/LANG_Name'); ?></p>
		<p id="gal-codes"><?php echo i18n_r('monsterGallery/LANG_Shortcode'); ?></p>
		<p id="gal-actions"><?php echo i18n_r('monsterGallery/LANG_Edit_Delete'); ?></p>
	</li>

	<?php
	foreach (glob(GSDATAOTHERPATH . 'monsterGallery/*.json') as $file) {
		global $SITEURL;
		$name = pathinfo($file)['filename'];

		echo '
			<li class="galitem">
				<p id="gal-title" style="color:#333;font-weight:600;">' . str_replace('--', ' ', $name) . '</p>
				<p id="gal-codes" style="opacity:0.6;">
					<span style="color:#ff0066">[% mg=' . $name . ' %] </span>
					<br>
					<span style="color:#000066">&lt;?php monsterGalleryShow("' . $name . '");?&gt; </span>
				</p>
				<div id="gal-actions" style="display:flex;gap:10px;">
					<a class="mg-btn mg-edit" href="' . $SITEURL . 'admin/load.php?id=monsterGallery&addMonsterGallery&edit=' . $name . '">' . i18n_r('monsterGallery/LANG_Edit') . '</a>
					<a class="mg-btn mg-delete" onclick="return confirm(`' . i18n_r('monsterGallery/LANG_Delete_Question') . '`);" href="' . $SITEURL . 'admin/load.php?id=monsterGallery&addMonsterGallery&delete=' . $name . '">' . i18n_r('monsterGallery/LANG_Delete') . '</a>
				</div>
			</li>
			';
	};
	?>
</ul>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="box-sizing:border-box;display:grid; width:100%;grid-template-columns:1fr auto; border-radius:5px;padding:10px;background:#fafafa;border:solid 1px #ddd;margin-top:20px;">
	<p style="margin:0;padding:0;"><?php echo i18n_r('monsterGallery/LANG_PayPal'); ?></p>
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="KFZ9MCBUKB7GL">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" border="0">
	<img alt="" src="https://www.paypal.com/en_PL/i/scr/pixel.gif" width="1" height="1" border="0">
</form>

<?php
if (isset($_GET['clearCache'])) {
	$imager = glob(GSDATAOTHERPATH . 'monsterGallery/thumb/*.*', GLOB_BRACE);

	foreach ($imager as $img) {
		unlink($img);
	};

	if (file_exists(GSPLUGINPATH . 'monsterGallery/thumb/')) {
		unlink(GSPLUGINPATH . 'monsterGallery/thumb/.htaccess');
		rmdir(GSPLUGINPATH . 'monsterGallery/thumb/');
	};
}; ?>