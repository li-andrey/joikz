<?
$url=$_GET['url'];
$w=$_GET['w'];
$h=$_GET['h'];

$arr=getImageSize($url);
$imW = $arr[0];
$imH = $arr[1];
$imT = $arr[2];
if ($imT==2) header ("content-type: image/jpeg"); else header ("content-type: image/png");

if (!$w AND !$h) {
 $w=150;
 if ($imW==$imH) {
  $urlWidth=$w;
  $urlHeight=$h;
 }
 if ($imW>$imH) {
  $urlWidth=$w;
  $urlHeight=round($imH/($imW/$w));
 }
 if ($imH>$imW) {
  $urlHeight=$h;
  $urlWidth=round($imW/($imH/$h));
 }
} else {
 if ($w==$h) {
  $urlWidth=$w;
  $urlHeight=$h;
 }
 if ($w>$h) {
  $urlWidth=$w;
  $urlHeight=round($imH/($imW/$w));
 }
 if ($h>$w) {
  $urlHeight=$h;
  $urlWidth=round($imW/($imH/$h));
 }
}

$im2=imagecreatetruecolor($urlWidth, $urlHeight);
if( $imT == 1 || $imT == 3 )
{
	imagealphablending( $im2, false );
	imagesavealpha( $im2, true );
}

if ($imT==1) {
 if (function_exists("imagecreatefromgif")) {
  $im=imagecreatefromgif ($url);
 } else {
  $im=imagecreate($w, $h);
  $c1=imagecolorallocate($im, 255, 255, 255);
  $c2=imagecolorallocate($im, 0, 0, 0);
  imagecolortransparent ($im, $c1);
  imagerectangle ($im, 0, 0, $w-1, $h-1, $c2);
  imagestring ($im, 2, 5, 5, "gif format", $c2);
  imagestring ($im, 2, 5, 15, "not", $c2);
  imagestring ($im, 2, 5, 25, "supported", $c2);
  header ("content-type: image/png");
  imagepng($im);
  return;
 }
} elseif ($imT==2) {
 $im=imagecreatefromjpeg ($url);
} elseif ($imT==3) {
 $im=imagecreatefrompng ($url);
}
imagecopyresampled($im2, $im, 0, 0, 0, 0, $urlWidth, $urlHeight, $imW, $imH);
imagedestroy ($im);

if ($imT==2) {
 imagejpeg ($im2, NULL, 100);
} else {
 imagepng ($im2, NULL);
}
?>