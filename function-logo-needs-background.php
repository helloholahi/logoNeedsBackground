<?php
function logoNeedsBackground($file)
{
	// detect the logo file type
	if(substr($file, -4) === '.png')
	{ $image = imagecreatefrompng($file); }
	elseif(substr($file, -4) === '.jpg' OR substr($file, -5) === '.jpeg')
	{ $image = imagecreatefromjpeg($file); }
	elseif(substr($file, -4) === '.gif')
	{ $image = imagecreatefromgif($file); }
	else
	{ return false; }

	if(isset($image))
	{
		// get the size of the image so we can loop through it
		$image_width = imagesx($image);
		$image_height = imagesy($image);
	
		$red = $green = $blue = $count = 0;

		// loop through the image and get the average color of it
		for ($x = 0; $x < $image_width; $x++)
		{
			for ($y = 0; $y < $image_height; $y++)
			{
				$color = imagecolorat($image, $x, $y);
				$alpha = ($color >> 24) & 0xFF;
				if($alpha < 110) // if the pixel is not (too) transparent, we count it (otherwise we ignore it)
				{
					$red += ($color >> 16) & 0xFF;
					$green += ($color >> 8) & 0xFF;
					$blue += $color & 0xFF;
					$count++;
				}
			}
		}
	
		$color_addition = 255 + 255 + 255; // default to white
		if($count !== 0)
		{
			$average_red = $red / $count;
			$average_green = $green / $count;
			$average_blue = $blue / $count;
			$color_addition = $average_red + $average_green + $average_blue;
		}
	
		// 600 will be the threshold, if the average color is above this value, we consider the logo to be too bright and we need a background color to see it, otherwise we won't see it on a white background
		// adjust it if you need, 765 is the maximum value, if the average color is 765, it means that the average color is white
		if($color_addition > 750)
		{ return true; }
		else
		{ return false; }
	}
	else
	{ return false; }
}

// just to test the function, here are different logos, each of them with a white background (and in jpg) and with a transparent background (and in png)
$test_logo_files[] = 'test-logos/logo-test-1.jpg';
$test_logo_files[] = 'test-logos/logo-test-1.png';
$test_logo_files[] = 'test-logos/logo-test-2.jpg';
$test_logo_files[] = 'test-logos/logo-test-2.png';
$test_logo_files[] = 'test-logos/logo-test-3.jpg';
$test_logo_files[] = 'test-logos/logo-test-3.png';
$test_logo_files[] = 'test-logos/logo-test-4.jpg';
$test_logo_files[] = 'test-logos/logo-test-4.png';
$test_logo_files[] = 'test-logos/logo-test-5.jpg';
$test_logo_files[] = 'test-logos/logo-test-5.png';
$test_logo_files[] = 'test-logos/logo-test-6.jpg';
$test_logo_files[] = 'test-logos/logo-test-6.png';
$test_logo_files[] = 'test-logos/logo-test-7.jpg';
$test_logo_files[] = 'test-logos/logo-test-7.png';

foreach($test_logo_files as $logo_file)
{
	if(logoNeedsBackground($logo_file))
	{ echo '<div style="border: 1px solid black; padding: 10px; margin-bottom: 20px; background-color: black;"><span style="float: right; color: white;">needs a background</span><img src="' . $logo_file . '"></div>'; }
	else
	{ echo '<div style="border: 1px solid black; padding: 10px; margin-bottom: 20px; "><span style="float: right;">a white background is ok</span><img src="' . $logo_file . '"></div>'; }
}
?>