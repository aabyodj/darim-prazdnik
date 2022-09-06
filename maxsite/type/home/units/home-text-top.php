<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * http://darim-prazdnik.by
 * 
 * Template for MaxSite CMS
 */

if ($home_text_do = mso_get_option('home_text_do', 'templates', '')) {
	echo '<article class="mso-home-text-top">' . $home_text_do . '</article>';
}
	
# end of file
