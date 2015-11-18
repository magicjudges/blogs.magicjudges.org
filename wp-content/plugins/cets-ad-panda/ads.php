<?php



function cets_ad_panda_get_ads() {

$ads = array();

$ads[] = array(
	'id' => 'default',
	'title' => 'Default Magic Judges',
);

$ads[] = array(
	'id' => 'jace',
	'title' => 'Jace - We Want You',
);

$ads[] = array(
	'id' => 'blogportal',
	'title' => 'Blog Portal - Domens Gate Graphic',
);

	return $ads;
}