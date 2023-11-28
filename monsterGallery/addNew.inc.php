<?php
global $SITEURL;; ?>

<style>
	.monsterspan {
		padding: 5px;
		background: #fafafa;
		width: 100%;
		box-sizing: border-box;
		margin: 10px 0;
		align-items: center;
		display: grid;
		align-items: center;
		grid-template-columns: 80px 1fr;
		gap: 10px;
		position: relative;
		padding-top: 30px;
		background: #ddd;
	}

	.monsterspan img {
		width: 80px;
		height: 80px;
		object-fit: cover;
		display: block;
		grid-column: 1/2;
		grid-row: 1/4;
		border: solid 4px #fff;
	}

	.monsterspan input {
		width: 100%;
		padding: 5px;
		box-sizing: border-box;
		margin: 5px 0;
		grid-column: 2/3;
	}

	.closeThis {
		position: absolute;
		top: 0;
		right: 0;
		background: red;
		width: 20px;
		height: 20px;
		color: #fff;
		border: none;
	}

	.optionMonster p {
		margin-bottom: 5px !important;
	}
</style>



<div style="display:flex;width:100%;justify-content:space-between;align-items:center;">
	<h3><?php echo i18n_r('monsterGallery/LANG_MG_Edit'); ?></h3>
	<a href="<?php
				global $SITEURL;
				global $GSADMIN;

				echo $SITEURL . $GSADMIN . '/load.php?id=monsterGallery&monsterGalleryList'; ?>" style="margin-bottom:10px;display:inline-block;background:#00;background:#000;text-decoration:none;color:#fff; padding:10px;"><?php echo i18n_r('monsterGallery/LANG_Back_To_List'); ?></a>
</div>

<form class="mgForm" method="POST">
	<input type="text" name="MGtitle" style="width:100%; padding:10px; box-sizing: border-box;" placeholder="<?php echo i18n_r('monsterGallery/LANG_Gallery_Title'); ?>" required <?php if (isset($_GET['edit'])) : ?> value="<?php echo str_replace('--', ' ', $_GET['edit']); ?>" <?php endif; ?>>

	<input type="hidden" name="check" style="width:100%; padding:10px; box-sizing: border-box;" placeholder="<?php echo i18n_r('monsterGallery/LANG_Gallery_Title'); ?>" <?php if (isset($_GET['edit'])) : ?> value="<?php echo str_replace('--', ' ', $_GET['edit']); ?>" <?php endif; ?>>

	<div class="optionMonster" style="width:100%;background: #000; padding:10px; margin-top:10px; box-sizing: border-box; color:#fff; display:grid; grid-template-columns:1fr 1fr 1fr 1fr;gap:10px; box-sizing: border-box;">

		<?php
		if (isset($_GET['edit'])) {
			$data = file_get_contents(GSDATAOTHERPATH . 'monsterGallery/' . $_GET['edit'] . '.json');

			$dataJson = json_decode($data, false);

			$width = @$dataJson->width;
			$height = @$dataJson->height;
			$gap = @$dataJson->gap;
			$ownclass = @$dataJson->ownclass;
			$quality = @$dataJson->quality;
			$mods = @$dataJson->modules;
			$thumbfit = @$dataJson->thumbfit;
			$mobilewidth = @$dataJson->mobilewidth;
			$mobileheight = @$dataJson->mobileheight;
			$mobilegap = @$dataJson->mobilegap;
		};; ?>

		<div style="grid-column: 1/6; border: solid 1px #333; padding: 10px; background: #222;">
			<p style="color:#fff; font-size:1rem; margin:0; padding:0; margin-bottom: 10px;"><?php echo i18n_r('monsterGallery/LANG_Gallery_Type'); ?></p>
			<select name="modules" class="modules" style="width:100%; margin-bottom: 10px; box-sizing: border-box;padding: 6px; font-size:13px;border-radius:0;border:none;">
				<option value="glightbox"><?php echo i18n_r('monsterGallery/LANG_GlightBox'); ?></option>
				<option value="spotlight"><?php echo i18n_r('monsterGallery/LANG_SpotLight'); ?></option>
				<option value="simplelightbox"><?php echo i18n_r('monsterGallery/LANG_SimpleLightBox'); ?></option>
				<option value="baguettebox"><?php echo i18n_r('monsterGallery/LANG_BaguetteBox'); ?></option>
				<option value="PhotoSwipe"><?php echo i18n_r('monsterGallery/LANG_PhotoSwipe'); ?></option>
			</select>

			<script>
				if ('<?php echo $mods; ?>' !== '') {
					document.querySelector('.modules').value = '<?php echo $mods; ?>';
				};
			</script>
		</div>

		<div style="grid-column:1/2">
			<p style="margin:0; padding:0;"><?php echo i18n_r('monsterGallery/LANG_Thumb_Width'); ?></p>
			<input type="" name="quality" style="width:100%; padding:5px; box-sizing: border-box; font-size:12px" required placeholder="800" value="<?php echo @$quality ?>" pattern="[0-9]+">
		</div>

		<div style="grid-column:2/3">
			<p style="margin:0; padding:0;"><?php echo i18n_r('monsterGallery/LANG_Thumb_Display_Width'); ?></p>
			<input type="" name="width" style="width:100%; padding:5px; box-sizing: border-box; font-size:12px;" required placeholder="320px" value="<?php echo @$width ?>">
		</div>

		<div style="grid-column: 3/4;">
			<p style="margin: 0;padding:0;"><?php echo i18n_r('monsterGallery/LANG_Thumb_Height'); ?></p>
			<input type="" name="height" style="width:100%; padding:5px; box-sizing: border-box; font-size:12px;" required placeholder="240px" value="<?php echo @$height; ?>">
		</div>

		<div style="grid-column: 4/6;">
			<p style="margin: 0; padding:0;"><?php echo i18n_r('monsterGallery/LANG_Thumb_Margin'); ?></p>
			<input type="" name="gap" style="width:100%; padding:5px; box-sizing: border-box; font-size:12px;" placeholder="10px" required value="<?php echo @$gap; ?>">
		</div>


		<!-- mobile -->



		<div style="grid-column: 1/2;">
			<p style="margin: 0;padding:0;"><?php echo i18n_r('monsterGallery/LANG_Mobile_Width'); ?></p>
			<input type="" name="mobilewidth" style="width:100%; padding:5px; box-sizing: border-box; font-size:12px;" required placeholder="100%" value="<?php echo @$mobilewidth; ?>">
		</div>

		<div style="grid-column: 2/3;">
			<p style="margin: 0; padding:0;"><?php echo i18n_r('monsterGallery/LANG_Mobile_Height'); ?></p>
			<input type="" name="mobileheight" style="width:100%; padding:5px; box-sizing: border-box; font-size:12px;" required placeholder="250px" value="<?php echo @$mobileheight; ?>">
		</div>

		<div style="grid-column: 3/4;">
			<p style="margin: 0; padding:0;"><?php echo i18n_r('monsterGallery/LANG_Mobile_Gap'); ?></p>
			<input type="" name="mobilegap" style="width:100%; padding:5px; box-sizing: border-box; font-size:12px;" required placeholder="10px" value="<?php echo @$mobilegap; ?>">
		</div>
		<!-- end mobile -->

		<div style="grid-column:4/6;">
			<p style="margin: 0; padding:0;"><?php echo i18n_r('monsterGallery/LANG_Own_Class'); ?></p>
			<input type="" name="ownclass" style="width:100%; padding:5px; box-sizing: border-box; font-size:12px;" placeholder="<?php echo i18n_r('monsterGallery/LANG_Own_Class'); ?>" value="<?php echo @$ownclass; ?>">
		</div>





		<div style="grid-column:1/6;">
			<p style="margin: 0; padding:0;"><?php echo i18n_r('monsterGallery/LANG_Thumbnail_Fit'); ?></p>
			<select name="thumbfit" style="padding:8px;border:none;width:100%; font-size:12px;background:#fff;">
				<option value="cover" <?php echo (@$thumbfit == 'cover' ? 'selected' : ''); ?>>Cover</option>
				<option value="contain" <?php echo (@$thumbfit == 'contain' ? 'selected' : ''); ?>>Contain</option>
			</select>
		</div>






	</div>

	<button class="addMG" style="background: #000; color:#fff; padding:0.5rem 1rem; border:none; margin-top:10px; cursor:pointer;"><?php echo i18n_r('monsterGallery/LANG_Add_Image'); ?></button>
	<br>

	<script>
		function closeThis(e) {
			e.preventDefault();
			document.querySelector('.saveMG').style.display = "block";
		};
	</script>

	<div class="imagelist" style=" padding:10px; margin:10px 0;">
		<?php
		if (isset($_GET['edit'])) {

			$ed = str_replace(' ', '--', $_GET['edit']);
			$fils = GSDATAOTHERPATH . 'monsterGallery/' . $ed . '.json';

			if (file_exists($fils)) {
				$filedit = file_get_contents($fils);
				$fileditJson = json_decode($filedit);

				foreach ($fileditJson->images as $key => $value) {
					echo '
						<span class="monsterspan"> 
							<button class="closeThis" onClick="event.preventDefault();this.parentElement.remove()" style=" cursor:pointer;">X</button>
							<img src="' . $value . '">
							<input type="text" name="name[]" value="' . @$fileditJson->names[$key] . '" placeholder="' . i18n_r('monsterGallery/LANG_Image_Title') . '">
							<textarea  name="description[]" value="description" placeholder="' . i18n_r('monsterGallery/LANG_Image_Description') . '" style="width:100%; height:60px; box-sizing:border-box; padding:5px;">' . @$fileditJson->descriptions[$key] . '
							</textarea>
							<input type="text" name="image[]" value = "' . @$value . '" >
						</span>';
				};
			}
		};; ?>
	</div>

	<input type="submit" name="saveMG" class="saveMG" value="<?php echo i18n_r('BTN_SAVESETTINGS'); ?>" style="background: #000; color:#fff; padding:0.5rem 1rem; border:none; cursor:pointer;">
</form>

<?php
if (isset($_POST['saveMG'])) {
	$file = GSDATAOTHERPATH . 'monsterGallery/' . str_replace(' ', '--', $_POST['MGtitle']) . '.json';

	$folderExist = file_exists(GSDATAOTHERPATH . 'monsterGallery/') || mkdir(GSDATAOTHERPATH . 'monsterGallery/');

	$values = array();
	$descriptions = array();
	$texts = array();

	if ($folderExist) {
		foreach ($_POST['image'] as $key => $value) {
			array_push($values, $value);
		};

		foreach ($_POST['name'] as $key => $value) {
			array_push($texts, $value);
		};

		foreach ($_POST['description'] as $key => $value) {
			array_push($descriptions, $value);
		};

		$myObj = new stdClass();
		$myObj->names = $texts;
		$myObj->images = $values;
		$myObj->descriptions = $descriptions;
		$myObj->width = @$_POST['width'];
		$myObj->height = @$_POST['height'];
		$myObj->gap = @$_POST['gap'];
		$myObj->quality = @$_POST['quality'];
		$myObj->modules = @$_POST['modules'];
		$myObj->ownclass = @$_POST['ownclass'];
		$myObj->thumbfit = @$_POST['thumbfit'];

		$myObj->mobilewidth = $_POST['mobilewidth'];
		$myObj->mobileheight = $_POST['mobileheight'];
		$myObj->mobilegap = $_POST['mobilegap'];

		$myJSON = json_encode($myObj);

		file_put_contents($file, $myJSON);

		if (isset($_GET['edit']) && $_POST['MGtitle'] !== $_POST['check'] && $_POST['check'] !== null) {
			rename(GSPLUGINPATH . 'monsterGallery/' . $_POST['check'] . '.json', GSPLUGINPATH . 'monsterGallery/' . $_POST['MGtitle'] . '.json');
		}

		echo "
			<script type='text/javascript'>
			window.location.href = '" . $SITEURL . "admin/load.php?id=monsterGallery&addMonsterGallery&edit=" . $_POST['MGtitle'] . "';
			</script>";
	};
};; ?>

<script>
	document.querySelector('.addMG').addEventListener('click', (e) => {
		e.preventDefault();
		window.open('<?php global $SITEURL;
						echo $SITEURL; ?>plugins/monsterGallery/filebrowser/imagebrowser.php?type=images&CKEditor=post-content', "", "left=10,top=10,width=960,height=500");
	});

	$(function() {
		$(".imagelist").sortable();
	});
</script>
