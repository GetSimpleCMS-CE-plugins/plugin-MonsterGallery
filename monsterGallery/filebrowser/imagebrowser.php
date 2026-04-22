<?php
	include('../../../gsconfig.php');
	$admin = defined('GSADMIN') ? GSADMIN : 'admin';
	include("../../../".$admin."/inc/common.php");
	$loggedin = cookie_check();
	if (!$loggedin) die("Not logged in!");
	if (!defined('IN_GS')) {
		die('you cannot load this page directly.');
	}

	i18n_merge('i18n_gallery', substr($LANG, 0, 2));
	i18n_merge('i18n_gallery', 'en');
	i18n_merge('monsterGallery') || i18n_merge('monsterGallery', 'en_US');
	
	if (isset($_GET['path'])) {
		$subPath = preg_replace('/\.+\//', '', $_GET['path']);
		if ($subPath) $subPath .= '/';
		$path = "../../../data/uploads/" . $subPath;
	} else {
		$subPath = "";
		$path = "../../../data/uploads/";
	}
	$path = tsl($path);

	// check if host uses Linux (used for displaying permissions
	$isUnixHost = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? false : true);
	$path_parts = pathinfo($_SERVER['PHP_SELF']);
	$dir = str_replace("/plugins/i18n_gallery/browser", "", $path_parts['dirname']);
	$fullPath = htmlentities("http://" . $_SERVER['SERVER_NAME'] . ($dir == '/' ? "" : $dir) . "/data/uploads/", ENT_QUOTES);
	$sitepath = htmlentities("http://" . $_SERVER['SERVER_NAME'] . ($dir == '/' ? "" : $dir) . "/", ENT_QUOTES);

	$func = preg_replace('/[^\w]/', '', @$_GET['func'] ?? '');
	$w = (int) @$_GET['w'];
	$h = (int) @$_GET['h'];
	if (!$w && !$h) {
		$w = 160;
		$h = 120;
	}
	$autoclose = @$_GET['autoclose'];
	$debug = @$_GET['debug'];

	global $LANG;
	global $GSADMIN;
	global $SITEURL;
	$LANG_header = preg_replace('/(?:(?<=([a-z]{2}))).*/', '', $LANG);
	$count = "0";
	$dircount = "0";
	$counter = "0";
	$totalsize = 0;
	$filesArray = [];
	$dirsArray = [];

	clearstatcache();
	$dir_handle = opendir($path) or die("Unable to open $path");
	while ($file = readdir($dir_handle)) {
	  if ($file == "." || $file == ".." || $file == ".htaccess") {
		// not a upload file
		} elseif (is_dir($path . $file)) {
			$dirsArray[$dircount]['name'] = $file;
			$dircount++;
		} else {
			$ext = @strtolower(substr($file, strrpos($file, '.') + 1));
				if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png' || $ext == 'webp') {
				$ss = @stat($path . $file);
				list($width, $height) = @getimagesize($path . $file);
				$filesArray[] = ['name' => $file, 'date' => @date('M j, Y', $ss['ctime']), 'size' => fSize($ss['size']), 'bytes' => $ss['size'], 'width' => $width, 'height' => $height, 'title' => @$info['title'], 'tags' => @$info['tags'], 'description' => @$info['description'], 'debug' => @$info['debug']];
				$totalsize = $totalsize + $ss['size'];
				$count++;
			}
		}
	}
	$filesSorted = subval_sort($filesArray, 'name');
	$dirsSorted = subval_sort($dirsArray, 'name');

	$pathParts = explode("/", $subPath);
	$urlPath = "";
?>

<!DOCTYPE html>
<html lang="<?php echo $LANG_header; ?>">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php echo i18n_r('FILE_BROWSER'); ?></title>
  <link rel="shortcut icon" href="<?php echo $SITEURL . $GSADMIN; ?>/favicon.png" type="image/x-icon" />
  <link rel="stylesheet" type="text/css" href="<?php echo $SITEURL . $GSADMIN; ?>/template/style.php?v=<?php echo GSVERSION; ?>" media="screen" />
  <style>
    .wrapper,
    #maincontent,
    #imageTable {
		width: 100%
    }
	a {
		text-decoration:none!important;
	}
	a:hover {
		text-decoration:underline!important;
	}
	.mg-btn{
		border-radius:5px;
		padding:0.7rem 1rem;
		text-decoration: none;
		color:#fff!important;
		border:none;
		margin-bottom:10px;
		cursor:pointer;
	}
	.mg-btn.mg-new{
		background-color:#004F99!important;
	}
	.image-checkbox {
		width: 18px;
		height: 18px;
		cursor: pointer;
		margin-right: 10px;
		vertical-align: middle;
	}
	.select-all-container {
		padding: 10px 0;
		margin-bottom: 10px;
		border-bottom: 1px solid #ddd;
	}
	.select-all-checkbox {
		width: 18px;
		height: 18px;
		cursor: pointer;
		margin-right: 8px;
		vertical-align: middle;
	}
  </style>
</head>

<body id="imagebrowser">
	<div class="wrapper">
		<div id="maincontent">
			<div class="main" style="border:none;">
				<h3><?php i18n('UPLOADED_FILES'); ?></h3>
				<div class="h5">/ <a href="?func=<?php echo $func; ?>&amp;w=<?php echo $w; ?>&amp;h=<?php echo $h; ?>&amp;autoclose=<?php echo $autoclose; ?>"><?php echo i18n_r('monsterGallery/LANG_Files') ;?></a> /
				<?php
					foreach ($pathParts as $pathPart) {
						if ($pathPart != '') {
							$urlPath .= $pathPart;
				?>
				<a href="?path=<?php echo $urlPath; ?>&amp;func=<?php echo $func; ?>&amp;w=<?php echo $w; ?>&amp;h=<?php echo $h; ?>&autoclose=1"><?php echo $pathPart; ?></a> /
				<?php
						$urlPath .= '/';
						}
					}
				?>
				</div>
				
				<?php if (count((array)$filesSorted) != 0) { ?>
				<div class="select-all-container">
					<label>
						<input type="checkbox" id="selectAllCheckbox" class="select-all-checkbox">
						<strong><?php echo i18n_r('monsterGallery/LANG_Select_All_Images') ;?></strong>
					</label>
				</div>
				<?php } ?>
				
				<table class="highlight" id="imageTable">
				  <tbody>
					<?php
						if (count((array)$dirsSorted) != 0) {
							foreach ((array)$dirsSorted as $upload) {
								$p = $subPath . $upload['name'];
					?>
						<tr class="All">
						  <td class="" colspan="6">
							<a href="imagebrowser.php?path=<?php echo $p; ?>&amp;func=<?php echo $func; ?>&amp;w=<?php echo $w; ?>&amp;h=<?php echo $h; ?>&autoclose=1" title="<?php echo $upload['name']; ?>">
								<svg xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle; margin:0 10px 0 40px" width="36" height="36" viewBox="0 0 48 48"><rect width="48" height="48" fill="none"/><path fill="#ffa000" d="M40 12H22l-4-4H8c-2.2 0-4 1.8-4 4v8h40v-4c0-2.2-1.8-4-4-4"/><path fill="#ffca28" d="M40 12H8c-2.2 0-4 1.8-4 4v20c0 2.2 1.8 4 4 4h32c2.2 0 4-1.8 4-4V16c0-2.2-1.8-4-4-4"/></svg> 
								<strong><?php echo $upload['name']; ?></strong>
							</a>
						  </td>
						</tr>
					  <?php
						}
					}

					$metadata = [];
					if (count((array)$filesSorted) != 0) {
					  foreach ((array)$filesSorted as $upload) {
						$onclick = 'submitLink(' . count($metadata) . ')';
						$metadata[] = ['url' => $subPath . $upload['name'], 'size' => $upload['bytes'], 'width' => $upload['width'], 'height' => $upload['height'], 'title' => $upload['title'], 'tags' => $upload['tags'], 'description' => $upload['description']];
						if ($isUnixHost && defined('GSDEBUG') && function_exists('posix_getpwuid')) {
							$filePerms = substr(sprintf('%o', fileperms($path . $upload['name'])), -4);
							$fileOwner = posix_getpwuid(fileowner($path . $upload['name']));
						}
					  ?>
						<tr class="All images">
						  <td style="width: 30px; padding-top:25px;">
							<input type="checkbox" class="image-checkbox" data-index="<?php echo count($metadata) - 1; ?>">
						  </td>
						  <td>
							<a href="javascript:void(0)" title="<?php i18n('SELECT_FILE') . ': ' . htmlspecialchars(@$upload['name']); ?>" onclick="<?php echo $onclick; ?>">
							  <img style="width:100px; height:65px;object-fit: cover;border:solid 1px #676767; padding:2px;" src="<?php echo $SITEURL . 'data/uploads/' . $subPath . $upload['name']; ?>" />
							</a>
						  </td>
						  <td style="padding-top:25px;">
							<a class="primarylink" href="javascript:void(0)" title="<?php i18n('SELECT_FILE') . ': ' . htmlspecialchars(@$upload['name']); ?>" onclick="<?php echo $onclick; ?>">
							  <?php echo htmlspecialchars($upload['name']); ?>
							</a>
							<p>
							  <?php if (@$upload['title']) echo '<b>' . htmlspecialchars($upload['title']) . '</b><br/>'; ?>
							  <?php if (@$upload['tags']) echo '<i>' . htmlspecialchars(implode(', ', $upload['tags'])) . '</i><br/>'; ?>
							  <?php if (@$upload['description']) echo preg_replace('/\r?\n/', '<br/>', htmlspecialchars($upload['description'])); ?>
							</p>
						  </td>
						  <td style="white-space:nowrap; padding-top:25px;"><span><?php echo $upload['width']; ?> x <?php echo $upload['height']; ?></span></td>
						  <td style="width:80px; text-align:right; padding-top:25px;"><span><?php echo $upload['size']; ?></span></td>
						  <?php if (isset($filePerms) && isset($fileOwner['name'])) { ?>
							<td style="width:70px; text-align:right; padding-top:25px;"><span><?php echo $fileOwner['name']; ?>/<?php echo $filePerms; ?></span></td>
						  <?php } ?>
						  <td style="width:85px; text-align:right; padding-top:25px;"><span><?php echo shtDate($upload['date']); ?></span></td>
						</tr>
						<?php if ($debug) echo '<tr><td colspan="7"><pre>' . htmlspecialchars(@$upload['debug']) . '</pre></td></tr>'; ?>
					<?php
					  }
					}
					?>
				  </tbody>
				</table>

				<button class="addselected mg-btn mg-new"><?php echo i18n_r('monsterGallery/LANG_Add_Images') ;?></button>

				<p><em><b><?php echo count((array)$filesSorted); ?></b> <?php i18n('TOTAL_FILES'); ?> (<?php echo fSize($totalsize); ?>)</em></p>
				<p style="display:none"><a href="javascript:void(0)" onclick="submitAllLinks()"><?php i18n('i18n_gallery/ADD_ALL_IMAGES'); ?></a></p>
				<?php // foreach ($metadata as &$m) if (!@$m['title']) $m['title'] = basename($m['url']); 
				?>
				<script type='text/javascript'>
					// <![CDATA[
					var metadata = <?php echo json_encode($metadata); ?>;
					// ]]>
				</script>

				<script>
					// Select All functionality
					document.getElementById('selectAllCheckbox')?.addEventListener('change', function() {
						const checkboxes = document.querySelectorAll('.image-checkbox');
						checkboxes.forEach(checkbox => {
							checkbox.checked = this.checked;
						});
					});

					// Update Select All checkbox when individual checkboxes change
					document.querySelectorAll('.image-checkbox').forEach(checkbox => {
						checkbox.addEventListener('change', function() {
							const allCheckboxes = document.querySelectorAll('.image-checkbox');
							const checkedCheckboxes = document.querySelectorAll('.image-checkbox:checked');
							const selectAllCheckbox = document.getElementById('selectAllCheckbox');
							
							if (selectAllCheckbox) {
								selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
							}
						});
					});

					function submitLink(e) {
						let linker = document.querySelectorAll('.images img')[e].getAttribute('src');
						console.log(linker);
						let linkerNew = linker;

						const isHero1 = window.opener.document.getElementById('layoutSelect')?.value === 'hero';
						window.opener.document.querySelector('.imagelist').insertAdjacentHTML('afterbegin', `
<span class="monsterspan"> 
	<button class="closeThis" onclick="event.preventDefault();this.parentElement.remove()">X</button>
	<img src="${linkerNew}">
	<input type="text" name="name[]" placeholder="<?php echo i18n_r('monsterGallery/LANG_Image_Title') ;?>">
	<textarea name="description[]" placeholder="<?php echo i18n_r('monsterGallery/LANG_Image_Description') ;?>" style="width:100%;height:60px;box-sizing:border-box;padding:5px;"></textarea>
	<input type="text" name="image[]" value="${linkerNew}">
	<button type="button" class="setCover mg-btn" style="background:#555; font-size:11px; padding:3px 8px; margin-top:4px; display:${isHero1 ? 'inline-block' : 'none'};" onclick="setCoverImage(this, '${linkerNew.replace(/'/g, "\\'")}')">☆ <?php echo i18n_r('monsterGallery/LANG_Set_cover') ;?></button>
</span>
`);

					window.close();
					}

					// Add only selected images
					document.querySelector('.addselected').addEventListener('click', () => {
						const checkedBoxes = document.querySelectorAll('.image-checkbox:checked');
						
						if (checkedBoxes.length === 0) {
							alert('Please select at least one image');
							return;
						}

						checkedBoxes.forEach(checkbox => {
							const index = checkbox.getAttribute('data-index');
							const img = document.querySelectorAll('.images img')[index];
							let linker = img.getAttribute('src');
							console.log(linker);
							let linkerNew = linker;

							const isHero2 = window.opener.document.getElementById('layoutSelect')?.value === 'hero';
							window.opener.document.querySelector('.imagelist').insertAdjacentHTML('afterbegin', `
<span class="monsterspan"> 
	<button class="closeThis" onclick="event.preventDefault();this.parentElement.remove()">X</button>
	<img src="${linkerNew}">
	<input type="text" name="name[]" placeholder="<?php echo i18n_r('monsterGallery/LANG_Image_Title') ;?>">
	<textarea name="description[]" placeholder="<?php echo i18n_r('monsterGallery/LANG_Image_Description') ;?>" style="width:100%; height:60px; box-sizing:border-box; padding:5px;"></textarea>
	<input type="text" name="image[]" value="${linkerNew}">
	<button type="button" class="setCover mg-btn" style="background:#555; font-size:11px; padding:3px 8px; margin-top:4px; display:${isHero2 ? 'inline-block' : 'none'};" onclick="setCoverImage(this, '${linkerNew.replace(/'/g, "\\'")}')">☆ <?php echo i18n_r('monsterGallery/LANG_Set_cover') ;?></button>
</span>
`);
						});
						window.close();
					});
				</script>

			</div>
		</div>
	</div>
</body>

</html>