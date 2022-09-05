<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * http://darim-prazdnik.by
 * 
 * Template for MaxSite CMS
 */

if ($fn = mso_fe('main/blocks/_start.php')) require $fn;
if ($fn = mso_fe('main/blocks/body-start.php')) require $fn;
if ($fn = mso_fe('main/blocks/header.php')) require $fn;
if ($fn = mso_fe('main/blocks/header-out.php')) require $fn;
?>
<main role="main">
	<?php if ($fn = mso_fe('main/blocks/main-start.php')) require $fn; ?>
	<?php if ($fn = mso_fe('main/blocks/content.php')) require $fn; ?>
	<?php if ($fn = mso_fe('main/blocks/main-end.php')) require $fn; ?>
</main>
<?php
if ($fn = mso_fe('main/blocks/footer-pre.php')) require $fn;
if ($fn = mso_fe('main/blocks/footer.php')) require $fn;
if ($fn = mso_fe('main/blocks/body-end.php')) require $fn;
?>
</body></html><?php if ($fn = mso_fe('main/blocks/_end.php')) require $fn; ?>
