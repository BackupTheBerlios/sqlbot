<?php
$image = imagecreate($w,10);
if ($c == b) { $gold = imagecolorallocate($image, 8, 3, 158); }
if ($c == r) { $gold = imagecolorallocate($image, 255, 0, 0); }
if ($c == g) { $gold = imagecolorallocate($image, 0, 255, 0); }
imagepng($image);
imagedestroy($image);
?>