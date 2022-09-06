<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

echo '<header id="page-header">';

if (function_exists('ushka')) echo ushka('header-start');

echo '<div id="top-panel" class="minimized">
		<a href="' . getinfo('site_url') . '" id="logo-h1-slogan" title="' . getinfo('name_site') . '">
			<img id="site-logo" src="' . getinfo('template_url') . 'assets/img/logo.png" alt="' . getinfo('name_site') . '">
			<div id="h1-slogan">
				<h1>' . mso_get_option('h1_site', 'general', getinfo('name_site')) . '</h1>
				<div id="site-slogan">' . getinfo('description_site') . '</div>
			</div>
		</a>
		<div id="phones-search"><address><a href="tel:+375447095272" class="phone"><img src="' . getinfo('template_url') . 'assets/img/velcom.png" alt="velcom">&nbsp;(044)&nbsp;709-52-72</a><a href="tel:+375297013072" class="phone"><img src="' . getinfo('template_url') . 'assets/img/mts.png" alt="МТС">&nbsp;(029)&nbsp;701-30-72</a></address>';

if (!is_type('search') and $fn = mso_fe('main/blocks/search.php')) require($fn);

echo '</div></div>';

if (is_type('home') and (mso_segment(2) != 'next')) {
	if ($fn = mso_fe('main/blocks/menu1.php')) require($fn);
	if ($fn = mso_fe('main/blocks/menu2.php')) require($fn);
	if ($fn = mso_fe('main/blocks/search.php')) require($fn);
}

$components = my_get_components('header');

foreach($components as $fn) {
	require $fn;
}

if (function_exists('ushka')) echo ushka('header-end');

echo '</header>';

# end of file
