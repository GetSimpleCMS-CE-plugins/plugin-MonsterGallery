<?php

# get correct id for plugin
$thisfile = basename(__FILE__, ".php");

# add in this plugin's language file
i18n_merge('monsterGallery') || i18n_merge('monsterGallery', 'en_US');

# register plugin
register_plugin(
	$thisfile, //Plugin id
	'MonsterGallery', 	//Plugin name
	'3.0', 		//Plugin version
	'Multicolor',  //Plugin author
	'https://github.com/multicolor-rgb', //author website
	i18n_r('monsterGallery/LANG_Description'), //Plugin description
	'pages', //page type - on which admin tab to display
	'monsterGallery'  //main function (administration)
);

# add a link in the admin tab 'theme'
add_action('pages-sidebar', 'createSideMenu', array($thisfile, i18n_r('monsterGallery/LANG_Settings'), 'monsterGalleryList'));

add_action('theme-header', 'makeMagic');

function monsterGallery()
{

	if (isset($_GET['credits'])) {
		include(GSPLUGINPATH . 'monsterGallery/credits.inc.php');
	};

	if (isset($_GET['monsterGalleryList'])) {
		include(GSPLUGINPATH . 'monsterGallery/list.inc.php');
	};

	if (isset($_GET['addMonsterGallery'])) {
		include(GSPLUGINPATH . 'monsterGallery/addNew.inc.php');
	}

	if (isset($_GET['migrateGallery'])) {
		include(GSPLUGINPATH . 'monsterGallery/migrate.inc.php');
	}

	if (isset($_GET['delete'])) {
		unlink(GSDATAOTHERPATH . 'monsterGallery/' . $_GET['delete'] . '.json');

		global $SITEURL;

		echo "
			<script type='text/javascript'>
			window.location.href = '" . $SITEURL . "admin/load.php?id=monsterGallery&monsterGalleryList';
			</script>";
	};
};

require(GSPLUGINPATH . 'monsterGallery/modules/modules.class.php');

function makeMagic()
{

	function mgShow($matches)
	{
		//get class Monster Modules;
		$modulesClass = new MonsterModules();
		$modulesClass->set_name($matches);

		//load modules
		if ($modulesClass->getNameModules() == 'glightbox') {
			$modulesClass->glightbox();
			return  $modulesClass->gal;
		};

		if ($modulesClass->getNameModules()  == 'PhotoSwipe') {
			$modulesClass->photoswipe();
			return  $modulesClass->gal;
		};

		if ($modulesClass->getNameModules() == 'spotlight') {
			$modulesClass->spotlight();
			return  $modulesClass->gal;
		};

		if ($modulesClass->getNameModules() == 'simplelightbox') {
			$modulesClass->simplelightbox();
			return  $modulesClass->gal;
		};

		if ($modulesClass->getNameModules() == 'baguettebox') {
			$modulesClass->baguettebox();
			return  $modulesClass->gal;
		};
	};

	///shortbox create
	global $content;
	$newcontent = preg_replace_callback(
		'/\\[% mg=(.*) %\\]/i',
		"mgShow",
		$content
	);
	$content = $newcontent;
};

/// style loader
add_action('theme-header', 'styleloader');

function styleloader()
{
	global $SITEURL;
	global $modules;

	if (isset($modules)) {

		if ($modules == 'glightbox') {
			echo '<link rel="stylesheet" href="' . $SITEURL . 'plugins/monsterGallery/modules/glightbox/glightbox.min.css">';
		};

		if ($modules == 'PhotoSwipe') {
			echo '<link rel="stylesheet" href="' . $SITEURL . 'plugins/monsterGallery/modules/photoswipe/photoswipe.css">';
		};

		if ($modules == 'spotlight') {
			echo '<script src="' . $SITEURL . 'plugins/monsterGallery/modules/spotlight/spotlight.bundle.js"></script>';
		};

		if ($modules == 'simplelightbox') {
			echo '<link rel="stylesheet" href="' . $SITEURL . 'plugins/monsterGallery/modules/simplelightbox/simple-lightbox.min.css">';
		};

		if ($modules == 'baguettebox') {
			echo '<link rel="stylesheet" href="' . $SITEURL . 'plugins/monsterGallery/modules/
	baguettebox/baguetteBox.min.css">';
		};
	};
};


///grab function inside theme


function monsterGalleryShow($matches)
{
	//get class Monster Modules;
	$modulesClass = new MonsterModules();
	$modulesClass->set_name_frontend($matches);
	global $SITEURL;

	//load modules
	if ($modulesClass->getNameModules() == 'glightbox') {
		$modulesClass->glightbox();
		echo $modulesClass->gal;
	};

	if ($modulesClass->getNameModules()  == 'PhotoSwipe') {
		$modulesClass->photoswipe();
		echo  $modulesClass->gal;
	};

	if ($modulesClass->getNameModules() == 'spotlight') {
		$modulesClass->spotlight();
		echo  $modulesClass->gal;
	};

	if ($modulesClass->getNameModules() == 'simplelightbox') {
		$modulesClass->simplelightbox();
		echo  $modulesClass->gal;
	};

	if ($modulesClass->getNameModules() == 'baguettebox') {

		$modulesClass->baguettebox();
		echo $modulesClass->gal;
	};
};







/// script loader
add_action('theme-footer', 'scriptloader');

function scriptloader()
{
	global $SITEURL;
	global $modules;

	if (isset($modules)) {
		if ($modules == 'glightbox') {
			echo '<script src="' . $SITEURL . 'plugins/monsterGallery/modules/glightbox/glightbox.min.js"></script>';
			echo '<script src="' . $SITEURL . 'plugins/monsterGallery/modules/glightbox/glightboxrun.js"></script>';
		};

		if ($modules == 'PhotoSwipe') {
			echo '<script type="module" src="' . $SITEURL . 'plugins/monsterGallery/modules/photoswipe/photoSwipeModule.js"></script>';
		};

		if ($modules == 'simplelightbox') {
			echo '<script src="' . $SITEURL . 'plugins/monsterGallery/modules/simplelightbox/simple-lightbox.min.js"></script>';

			echo "
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

		if ($modules == 'baguettebox') {
			echo '<script async  src="' . $SITEURL . 'plugins/monsterGallery/modules/baguettebox/baguetteBox.min.js"></script>';

			echo '
				<script>
					window.addEventListener("load", function() {
						baguetteBox.run(".gallery",(element)=>{
							return element.getElementsByTagName("img")[0].alt;
						});
					});
				</script>';
		};
	};
}
