<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

$menu = mso_get_option('top_menu', 'templates', 
	tf('/ | Главная ~ page/about | О сайте ~ comments | Комментарии ~ contact | Контакты ~ sitemap | Архив ~ feed | RSS'));

if ($menu) {
	echo '<nav class="menu big">
			<ul class="images-list">'
				. my_menu_build($menu)
			. '</ul>
		</nav>';
}

# end of file
