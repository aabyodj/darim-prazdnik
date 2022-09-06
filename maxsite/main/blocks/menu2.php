<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$menu = mso_get_option('menu2', 'templates', tf('/ | Главная ~ about | О сайте'));

if ($menu) {
	echo '<nav class="menu">
			<ul class="images-list">'
				. my_menu_build($menu)
			. '</ul>
		</nav>';
}

# end of file
