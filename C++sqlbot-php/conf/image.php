<?php header ("Content-type: image/png");
import_request_variables ( "gp" );
$image = imagecreate($w,10);
$gold = imagecolorallocate($image, 8, 3, 158);
imagepng($image);
imagedestroy($image);
?>