<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

my_default_head_section();
	
echo '<body' . ((mso_get_val('body_class')) ? ' class="' . mso_get_val('body_class') . '"' : '') . '>';

mso_hook('body_start');

if (function_exists('ushka')) echo ushka('body_start');
if (function_exists('ushka')) echo ushka('header-pre');

# end of file
