<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MaxSite CMS
 * (c) https://max-3000.com/ 
 * файл functions.php подключается при инициализации сайта
 */
  
if ($fn = mso_fe('set_val.php')) require_once $fn;

/**
 * Вывод секции HEAD
 */
function my_default_head_section()
{
	// атирибуты <HTML>
	$html_attr = mso_get_val('head_section_html_add');
	$html_attr = mso_hook('html_attr', $html_attr);
	$html_attr = $html_attr ? ' ' . $html_attr : '';

	echo
		'<!DOCTYPE html>
<html' . $html_attr . '><head>' . mso_hook('head_start') . '
<meta charset="UTF-8">
<title>' . mso_head_meta('title') . '</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="generator" content="MaxSite CMS">
<meta name="description" content="' . mso_head_meta('description') . '">
<link rel="icon" href="' . getinfo('template_url') . 'assets/img/favicon.gif" type="image/gif">';
	
	if (mso_get_option('default_canonical', 'templates', 0)) echo mso_link_rel('canonical');

	echo mso_rss();

	// autoload файлов
	if ($autoload_css = mso_get_path_files(getinfo('template_dir') . 'assets/css/', getinfo('template_url') . 'assets/css/', true, ['css'])) {
		foreach ($autoload_css as $fn_css) {
			echo mso_load_style($fn_css);
		}
	}

	my_out_component_css();
	mso_hook('head_css');
	my_default_out_profiles();

	echo mso_load_jquery();

	mso_hook('head');

	// autoload js-файлов
	if ($autoload_js = mso_get_path_files(getinfo('template_dir') . 'assets/js/autoload/', getinfo('template_url') . 'assets/js/autoload/', true, ['js'])) {
		foreach ($autoload_js as $fn_js) {
			echo '<script src="' . $fn_js . '"></script>' . NR;
		}
	}

	my_out_component_js(); // компоненты и файл _head.php

	if ($fn = mso_page_foreach('head')) require $fn;
	if (function_exists('ushka')) echo ushka('head');

	if (mso_fe('assets/js/my.js')) mso_add_file('assets/js/my.js');

	if ($my_style = mso_get_option('my_style', getinfo('template'), ''))
		echo NR . '<!-- custom style --><style>' . $my_style . '</style>';

	mso_hook('head_end');

	if (function_exists('ushka')) echo ushka('google_analytics_top');

	echo '</head>';
}

/**
 * вывод подключенных css-профилей
 * @param type $path
 */
function my_default_out_profiles($path = 'assets/css/profiles/')
{
	if ($default_profiles = mso_get_option('default_profiles', getinfo('template'), [])) {

		$css_out = '';

		// theme и lazy профили подключаются как link rel="stylesheet
		foreach ($default_profiles as $css_file) {
			$fn = $path . $css_file;

			$theme = (strpos($css_file, 'theme-') === 0  or strpos($css_file, '-head') !== false);
			$lazy = (strpos($css_file, '-lazy') !== false);

			if ($theme)
				mso_add_file($fn); // подключаем внешими стилями в HEAD
			elseif ($lazy)
				mso_add_file($fn, true); // подключаем внешими стилями в BODY
			else
				$css_out .= mso_out_css_file($fn, false, false); // получение и обработка CSS из файла
		}

		if ($css_out)
			echo '<style>' . $css_out . '</style>';
	}

	// здесь же выводим css-профиль записи
	// он задан через метаполе
	if (is_type('page')) {
		if ($pageData = mso_get_val('mso_pages', 0, true)) {
			if ($page_css_profiles = mso_page_meta_value('page_css_profiles', $pageData['page_meta'])) {

				$fn = $path . $page_css_profiles;

				$theme = (strpos($page_css_profiles, 'theme-') === 0);
				$lazy = (strpos($page_css_profiles, '-lazy') !== false);

				if ($theme)
					mso_add_file($fn); // подключаем внешими стилями в HEAD
				elseif ($lazy)
					mso_add_file($fn, true); // подключаем внешими стилями в BODY
				else {
					if ($css_out = mso_out_css_file($fn, false, false))
						echo '<style>' . $css_out . '</style>';
				}
			}
		}
	}
}

/**
 * получить список компонентов шапки или подвала
 * $type = header или footer
 * на выходе массив: компонент => файл для подключения
 */
function my_get_components($type) {
	
	static $r = []; // кэш результатов
	
	$out = [];
	
	// где-то явно указаны компоненты: mso_set_val('my_header_components', 'header1');
	if ($my = mso_get_val('my_' . $type . '_components')) {
		
		if (isset($r['my_' . $type . '_components'])) return $r['my_' . $type . '_components'];
		
		$my_components = mso_explode($my, false, true, false, false);
		
		foreach($my_components as $dir) {
			if ($fn = mso_fe('components/' . $dir . '/' . $dir . '.php')) $out[$dir] = $fn;
			elseif ($fn = mso_fe('components/' . $dir . '/index.php')) $out[$dir] = $fn;
		}
		
		$r['my_' . $type . '_components'] = $out;
				
		return $out;
	}
	
	if (isset($r[$type])) return $r[$type];
	
	$flag = true; // признак, чтобы использовать общие компоненты

	// для адреса может быть заданы свои компоненты
	// они в опции custom_header_components
	if ($custom_components = mso_get_option('custom_' . $type . '_components', getinfo('template'), false)) {		
		if ($ar = mso_text_find_key($custom_components)) {
			
			$current_url = mso_current_url();
			
			if (isset($ar[$current_url])) {
		
				$flag = false; // да есть свои компоненты
				
				$my_components = mso_explode($ar[$current_url], false, true, false, false);
				
				foreach($my_components as $dir) {
					if ($fn = mso_fe('components/' . $dir . '/' . $dir . '.php')) $out[$dir] = $fn;
					elseif ($fn = mso_fe('components/' . $dir . '/index.php')) $out[$dir] = $fn;
				}
			}
		}		
	}
		
	if ($flag) {
		// общие компоненты
		$my_components = [
			$type . '_component1' => 'default_' . $type . '_component1',
			$type . '_component2' => 'default_' . $type . '_component2',
			$type . '_component3' => 'default_' . $type . '_component3',
			$type . '_component4' => 'default_' . $type . '_component4',
			$type . '_component5' => 'default_' . $type . '_component5',
		];

		foreach ($my_components as $option => $def_component) {
			if ($dir = mso_get_option($option, getinfo('template'), $def_component)) {
				if ($fn = mso_fe('components/' . $dir . '/' . $dir . '.php')) $out[$dir] = $fn;
				elseif ($fn = mso_fe('components/' . $dir . '/index.php')) $out[$dir] = $fn;
			}
			
		}
	}
	
	$r[$type] = $out;
	
	return $out;
}

/**
 * функция подключает файлы js-файлы компонентов
 * Имя js-файла совпадает и каталогом компонента
 * Также подключается файл _head.php, если есть
 */
function my_out_component_js()
{
	$components1 = my_get_components('header');
	$components2 = my_get_components('footer');
	
	$components = array_merge($components1, $components2);
	
	foreach($components as $dir => $fn) {
		mso_add_file('components/' . $dir . '/' . $dir . '.js');
		
		if ($fn = mso_fe('components/' . $dir . '/_head.php')) require $fn;
	}	
}

/**
 * функция подключает файлы style.css установленных компонентов
 * 
 */
function my_out_component_css()
{
	$components1 = my_get_components('header');
	$components2 = my_get_components('footer');
	
	$components = array_merge($components1, $components2);
	
	foreach($components as $dir => $fn) {
		mso_add_file('components/' . $dir . '/style.css'); // подключаем внешими стилями
	}
}

/**
 * Раняя инициализация компонентов в момент загрузки шаблона
 * Если в подключенном компоненте есть файл _init.php, то он подключается
 */
function my_init_components()
{
	$components1 = my_get_components('header');
	$components2 = my_get_components('footer');
	
	$components = array_merge($components1, $components2);
	
	foreach($components as $dir => $fn) {
		if ($fn = mso_fe('components/' . $dir . '/_init.php')) require $fn;
	}
}

/**
 * присваивает опции (для текущего шаблона) значение
 * если опция не содержит заданного значение
 * если $imp = true то принудительно ставим опцию
 */
function my_set_opt($key, $val = '', $imp = false)
{
	if ($imp) {
		mso_add_option($key, $val, getinfo('template'));
	} else {
		if (mso_get_option($key, getinfo('template'), false) != $val)
			mso_add_option($key, $val, getinfo('template'));
	}
}


function next_color() {
	static $colors = array('red', 'orange', 'yellow', 'green' , 'cyan', 'blue', 'violet');
	static $index = 0;
		
	$result = $colors[$index];	
	$index += (count($colors) + 1) / 2;	
	$index = $index % count($colors);	
	return $result;
}

function even_odd() {	//TODO: use CSS instead
	static $even = false;	
	if ($even) {
		$even = false;
		return 'even';
	} else {
		$even = true;
		return 'odd';
	}	
}

/**
 * This is slightly modified copy of the function mso_menu_build() from core/format.php
 * 
 * формируем li-элементы для меню
 * элементы представляют собой текст, где каждая строчка один пункт
 * каждый пункт делается так:  http://ссылка | название | подсказка | class | class_для_span | атрибуты ссылки
 * на выходе так:
 * <li class="selected"><a href="url"><span>ссылка</span></a></li>
 * если первый символ [ то это открывает группу ul 
 * если ] то закрывает - позволяет создавать многоуровневые меню
 * если адрес равен # то ссылка не формируется, только текст <li class=""><span>ссылка</span></li>
 * если пункт меню равен --- то формируется разделитель li.divider Имеет смысл только в подпунктах
 */
function my_menu_build($menu = '', $select_css = 'selected', $add_link_admin = false){	
	if ($add_link_admin and is_login()) {
		$menu .= NR . 'admin|Admin';
	}

	$menu = str_replace("\r", "", $menu); // если это windows
	$menu = str_replace("_NR_", "\n", $menu);
	$menu = str_replace(" ~ ", "\n", $menu);
	$menu = str_replace("\n\n\n", "\n", $menu);
	$menu = str_replace("\n\n", "\n", $menu);

	$menu = explode("\n", trim($menu));
	
	// обработаем меню на предмет пустых строк, корректности и подсчитаем кол-во элементов
	$count_menu = 0;
	foreach ($menu as $elem)	//TODO: get rid of this
	{
		if (strlen(trim($elem)) > 1) $count_menu++;
	}

	// определим текущий url
	$http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https://" : "http://";
	$current_url = $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	$out = '';
	// обходим в цикле
	
	$i = 1; // номер пункта
	$n = 0; // номер итерации цикла 
	
	foreach ($menu as $elem) {
		// разобъем строчку по адрес | название
		$elem = explode('|', trim($elem));
		
		if (count($elem) < 2 ) {
			continue;
		}
		$url = trim($elem[0]);  // адрес
		$name = trim($elem[1]); // название
		
		$title = (isset($elem[2])) ? 'title="' . htmlspecialchars(trim($elem[2])) . '"' : '';
		
		// если адрес = ## то не выводим ссылку
		$a_link = (($url != '##') and ($url != '#')); 
		
		// нет в адресе http:// - значит это текущий сайт
		// если начинается с # или  ? — ничего не делаем
		if (
			($url != '#') 
			and strpos($url, '#') !== 0
			and strpos($url, '?') !== 0
			and strpos($url, 'http://') === false 
			and strpos($url, 'https://') === false
		) $url = ($url == '/') ? getinfo('siteurl') : getinfo('siteurl') . $url;
		
		// если текущий адрес совпал, значит мы на этой странице			
		$class = ($url == $current_url) ? $select_css : '';
		
		// возможно указан css-класс
		$css_class = (isset($elem[3])) ? trim($elem[3]) : '';
		
		// возможно указан class_для_span
		$img = (isset($elem[4])) ? trim($elem[4]) : '';
		if ($img = thumb_generate($img, 320, 320)) {
			$img = '<span class="img" style="background: url(' . "'" . $img . "'" . ') no-repeat center; background-size: cover; opacity: 1;"></span>';
		} else {
			$img = '<span class="img"></span>';
		}
		
		
		// возможно указан атрибут_для_ссылки
		$link_attr = (isset($elem[5])) ? ' ' . trim($elem[5]) : '';

		// для первого элемента добавляем класс first
		if ($i == 1) $class.= ' first';	//TODO: move this to CSS

		// для последнего элемента добавляем класс last
		if ($i == $count_menu) $class.= ' last'; //TODO: move this to CSS
		
		if ($a_link) {
			$class .= ' ' . even_odd();	//TODO: move this to CSS
			
			if ($css_class == '') {
				$class .= ' ' . next_color();
			} else {
				$class.= ' ' . $css_class;
			}
			
			if ($class == ' ') $class = '';
			$out .= '<li class="' . trim($class) . '"><a href="' . $url . '"' .$title . $link_attr . 
				'>'. $img .'<span class="title">' . $name . '</span></a></li>' . NR;

			$i++;
		}

		$n++;	
	}
	
	$out = str_replace('<li class="">', '<li>', $out);
	
	return $out;
}

function image_for_page($p = false) {
	global $page;
	$out = false;
	if ($p == false and isset($page)) {
		$p = $page;
	}
	$p = (array) $p;	
	if ($p and isset($p['page_meta']) and isset ($p['page_meta']['image_for_page']) and isset($p['page_meta']['image_for_page'][0])) {
		$out = $p['page_meta']['image_for_page'][0];
	}
	return $out;
 }

# end of file
