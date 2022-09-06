<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (defined('MO_DEBUG')) echo '<!-- file:', __FILE__, ' -->';

if (!isset($search)) $search = '';

?>
		<form class="search" name="f_search" method="get" 
			onsubmit="location.href='<?php echo getinfo('siteurl'); ?>search/' + encodeURIComponent(this.s.value).replace(/%20/g, '+'); return false;">
			<input name="s" type="search" placeholder="Поиск" value="<?php echo $search; ?>">
			<input type="submit" value="Фас!">
		</form>
<?php

if (defined('MO_DEBUG')) echo '<!-- /file:', __FILE__, ' -->';

# end of file