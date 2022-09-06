<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * http://darim-prazdnik.by
 * 
 * Template for MaxSite CMS
 */

if (!$pages) return;

$p = new Page_out();

$p->reset_counter(count($pages));

if ($f = mso_page_foreach('format-list-' . getinfo('type'))) {
	require $f;
} else {
	if ($f = mso_page_foreach('format-list')) {
		require $f;
	} else {
		$p->format('title', '', '', true);
		$p->format('date', 'j F Y', '<span><time datetime="[page_date_publish_iso]">', '</time></span>');
	}
}

$exclude_page_id = mso_get_val('exclude_page_id', []);
$p->html('<ul class="images-list mso-pages-list">');

foreach ($pages as $page) {
	if ($f = mso_page_foreach(getinfo('type') . '-list')) {
		require $f; // подключаем кастомный вывод
		continue; // следующая итерация
	}

	$p->load($page);
	
	if (($img = image_for_page($page)) == false) $img = '';
	if ($img = thumb_generate($img, 
			mso_get_option('image_for_page_width', getinfo('template'), 280),
			mso_get_option('image_for_page_height', getinfo('template'), 210)
		)) {
		$img = '<span class="img" style="background: url(' . "'" . $img . "'" . ') no-repeat center; background-size: cover; opacity: 1;"></span>';
	} else {
		$img = '<span class="img"></span>';
	}
	
	$p->html('<li class="'. next_color() . ' ' . even_odd() . '">');
	$p->html('<a href="' . $p->page_url(false) . '" title="' . $p->meta_val('description') . '">');
	$p->html($img); 
	$p->html('<span class="title">' . $p->val('page_title') . '</span>');
	$p->html('</a></li>');

	$exclude_page_id[] = $p->val('page_id');
}

$p->html('</ul>');

mso_set_val('exclude_page_id', $exclude_page_id);
	
# end of file
