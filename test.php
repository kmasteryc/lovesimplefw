<?php
ob_start();
require "public/index.php";
$output = ob_get_clean();
echo strpos($output, 'to');