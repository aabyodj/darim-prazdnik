<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (function_exists('ushka')) echo ushka('footer-start');

$components = my_get_components('footer');

foreach($components as $fn) {
	require $fn;
}


if (!is_type('home')) {
	if ($fn = mso_fe('main/blocks/search.php')) require($fn);
}

?>
<div id="site-copyright">
	© <a href="<?php echo getinfo('site_url') . '">' . getinfo('name_site') . '</a>, 2010–' . date('Y'); 
	?>. Вы можете свободно использовать материалы с этого сайта при условии указания прямой действующей ссылки на него.
</div>
<aside id="counters">
<?php
if ($fn = mso_get_option('footer_counters')) {
	$rules = trim(mso_get_option('footer_counters_rules_output'));
	$rules = eval('return ( ' . $rules . ' ) ? 1 : 0;');
	if ($rules === false) $rules = 1;
	if ($rules == 1) {
		eval(mso_tmpl_prepare($fn));
	}
}
?>
</aside>
<?php

if (function_exists('ushka')) echo ushka('footer-end');

# end of file
