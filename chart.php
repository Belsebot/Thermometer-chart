<?php 
header("Content-Type: image/png");
$height = 400;			              // picture height		
$width = 1200;			              // picture width
$reso = 20;			              // resolution 
$step = $width/$reso;		
$nolla = $height/2;
$kerroin = 4;			              // multiplier for graph values

$file = file("stats/temp.log");	  // log file location

$i=count($file)-$reso-1;	        // count temperature points from end of file

$image=imagecreate($width,$height)		// create image
	or die("cannot create image");
$bg_color = imagecolorallocate($image,255,255,255);	 // white color
$red = imagecolorallocate($image,255,0,0);		 // red color
$blue = imagecolorallocate($image,0,0,255);		// blue color
$green = imagecolorallocate($image,0,255,0);	   	// green color
$black = imagecolorallocate($image,0,0,0);		// black color

imageline($image,0,$nolla,$width,$nolla,$black);	// draw zero line in middle of picture
imagestring($image,5,5,$nolla+5,'0 C',$black);		// write 0 c near zero line

imagedashedline($image,0,$nolla+20*$kerroin,$width,$nolla+20*$kerroin,$black);	// draw -20 C line
imagestring($image,5,5,$nolla+20*$kerroin+5,'-20 C',$black);	                // write -20 C near-20 c line

imagedashedline($image,0,$nolla-20*$kerroin,$width,$nolla-20*$kerroin,$black);	// draw 20 C line
imagestring($image,5,5,$nolla-20*$kerroin+5, '20 C',$black);	                // write 20 near 20 c line

imagestring($image,5,10,10,'Ulkolampotila:',$black);			
imagestring($image,4,10,25,'Current',$green);
imagestring($image,4,10,40,'Max',$red);
imagestring($image,4,10,55,'Min',$blue);		// write on the top corner out temperature,current,max,min


@$line=explode(" ",$file[$i]);		// using space for delimiter separates values to array
@$temp_a=$line[5]*$kerroin;		// setting first temperature point
@$maxtemp_a=$line[6]*$kerroin;		// setting first max temp point
@$mintemp_a=$line[7]*$kerroin;		// setting first min temp point

$i++;

$line=explode(" ",$file[$i]);		// taking second temperature values
$temp_b=$line[5]*$kerroin;		// setting second temperature point
$maxtemp_b=$line[6]*$kerroin;		// setting second max temp point
$mintemp_b=$line[7]*$kerroin;		// setting second min temp point

$temp_a=$nolla-$temp_a;			// change temp values so that line will start at right place
$maxtemp_a=$nolla-$maxtemp_a;
$mintemp_a=$nolla-$mintemp_a;

$temp_b=$nolla-$temp_b;
$maxtemp_b=$nolla-$maxtemp_b;
$mintemp_b=$nolla-$mintemp_b;

for ($a=0;$a<$reso;$a++) {
	$x=$a+1;
	$i++;

	imageline($image,$a*$step,$temp_a,$x*$step,$temp_b,$green);		// draw current temperature line
	imageline($image,$a*$step,$maxtemp_a,$x*$step,$maxtemp_b,$red);		// draw max temperature line
	imageline($image,$a*$step,$mintemp_a,$x*$step,$mintemp_b,$blue);	// draw min temperature line

	imagestring($image,3,$a*$step+10,$height-20,$line[1],$black);		// write time on picture

	$temp_a=$temp_b;			// setting line end point to next line start poing
	$maxtemp_a=$maxtemp_b;
	$mintemp_a=$mintemp_b;

	@$line=explode(" ",$file[$i]);

	@$temp_b=$line[5]*$kerroin;
	$temp_b=$nolla-$temp_b;

	@$maxtemp_b=$line[6]*$kerroin;
	$maxtemp_b=$nolla-$maxtemp_b;

	@$mintemp_b=$line[7]*$kerroin;
	$mintemp_b=$nolla-$mintemp_b;

}
$i--;

$line=explode(" ",$file[$i]);

imagestring($image,5,140,10,$line[5],$black);		// write last temperature value to top corner
imagestring($image,4,40,40,$line[6],$red);		// write last max temp value to top corner
imagestring($image,4,40,55,$line[7],$blue);		// write last min temp value to top corner

imagepng($image);					// draw image
imagedestroy($image);

?>
