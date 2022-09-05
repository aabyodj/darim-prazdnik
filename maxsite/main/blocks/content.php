<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (function_exists('ushka')) echo ushka('content-start');

global $CONTENT_OUT;
echo $CONTENT_OUT;

if (function_exists('ushka')) echo ushka('content-end');

# end of file
