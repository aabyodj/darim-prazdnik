<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * http://darim-prazdnik.by
 * 
 * Template for MaxSite CMS
 */
 
if ($fn = mso_page_foreach('home-head-meta')) require $fn;

if ($fn = mso_find_ts_file('main/main-start.php')) require $fn;

mso_set_val('exclude_page_id', []);

if (mso_get_option('home_text_do', 'templates', '')) {
	if ($fn = mso_find_ts_file('type/home/units/home-text-top.php')) require $fn;
}

if (mso_get_option('home_page_id_top', 'templates', '0')) {
	if ($fn = mso_find_ts_file('type/home/units/home-top-page.php')) require $fn;
}

if (mso_get_option('home_last_page', 'templates', '0')) {
	if ($fn = mso_find_ts_file('type/home/units/home-last-page.php')) require $fn;
}

echo '<article><h1>' . mso_get_option('h1_site', 'general', getinfo('name_site')) . '</h1>';

if (mso_get_option('home_cat_block', 'templates', '0')) {
	if ($fn = mso_find_ts_file('type/home/units/home-cat-block-list.php')) require $fn;
} else {
	if ($fn = mso_find_ts_file('type/home/units/home-list.php')) require $fn;
}
	
echo '</article>';
	
if ($fn = mso_find_ts_file('main/main-end.php')) require $fn;

# end of file
