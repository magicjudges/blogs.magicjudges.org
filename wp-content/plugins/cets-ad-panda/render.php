<?php

function cets_ad_panda_widget_render($id) {
	$ad_file = dirname(__FILE__) . '/ads/' . $id . '/ad.php';
	if (file_exists($ad_file)) {
		require($ad_file);
	} else {
		$ad_file = dirname(__FILE__). '/ads/default/ad.php';
		require($ad_file);
	}
}