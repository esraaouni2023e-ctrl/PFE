<?php
$inputPath = "public/final.png";
$outputPath = "public/final_transparent.png";

$img = @imagecreatefrompng($inputPath);
if (!$img) {
    $img = @imagecreatefromjpeg($inputPath);
}
if (!$img) {
    die("Could not load image as PNG or JPEG.");
}

// Convert to truecolor if needed
if (!imageistruecolor($img)) {
    $temp = imagecreatetruecolor(imagesx($img), imagesy($img));
    imagecopy($temp, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
    imagedestroy($img);
    $img = $temp;
}

// Target color: pure white or the top-left pixel
$bg = imagecolorat($img, 0, 0);

// We want to make it transparent, but let's use a better method: 
// Create a new image with alpha
$width = imagesx($img);
$height = imagesy($img);
$newImg = imagecreatetruecolor($width, $height);
imagealphablending($newImg, false);
imagesavealpha($newImg, true);

$transparent = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
imagefill($newImg, 0, 0, $transparent);

for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
        $colorAt = imagecolorat($img, $x, $y);
        $r = ($colorAt >> 16) & 0xFF;
        $g = ($colorAt >> 8) & 0xFF;
        $b = $colorAt & 0xFF;
        
        // If it's very close to white, make it transparent
        if ($r > 240 && $g > 240 && $b > 240) {
            imagesetpixel($newImg, $x, $y, $transparent);
        } else {
            imagesetpixel($newImg, $x, $y, imagecolorallocatealpha($newImg, $r, $g, $b, 0));
        }
    }
}

imagepng($newImg, $outputPath);
imagedestroy($img);
imagedestroy($newImg);
echo "Background removed using thresholding and saved to $outputPath";
?>
