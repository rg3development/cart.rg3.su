<?php

class Image_filters {

	public function convertGrayColor($img_path, $output_path ){
		$type_img = exif_imagetype( $img_path );
		$gd = gd_info();
		if( $type_img == 3 AND $gd['PNG Support'] == 1 ){
			$img_png = imagecreatefromPNG( $img_path );
			imagesavealpha( $img_png, TRUE );
			if( $img_png AND imagefilter( $img_png, IMG_FILTER_GRAYSCALE )) {
				@unlink( $output_path );
				imagepng( $img_png, $output_path );
			}
			imagedestroy( $img_png );
		} elseif( $type_img == 2 AND $gd['JPG Support'] == 1 ) {
			$img_jpg 	 = imagecreatefromJPEG( $img_path );
			if ( !$color_total = imagecolorstotal( $img_jpg )) {
				$color_total = 256;
			}
		    imagetruecolortopalette( $img_jpg, FALSE, $color_total );
		    for( $c = 0; $c < $color_total; $c++ ) {
		         $col = imagecolorsforindex( $img_jpg, $c );
				 $i   = ( $col['red']+$col['green']+$col['blue'] )/3;
		         imagecolorset( $img_jpg, $c, $i, $i, $i );
		    }
			@unlink( $output_path );
			imagejpeg( $img_jpg, $output_path );
			imagedestroy( $img_jpg );
		} elseif( $type_img == 1 AND $gd['GIF Create Support'] == 1  ) {
			$img_gif 	 = imagecreatefromGIF( $img_path );
		    if ( !$color_total = imagecolorstotal( $img_gif )) {
		        $color_total = 256;
		    }
		    imagetruecolortopalette( $img_gif, FALSE, $color_total );
		    for( $c = 0; $c < $color_total; $c++ ) {
		         $col = imagecolorsforindex( $img_gif, $c );
				 $i   = ( $col['red']+$col['green']+$col['blue'] )/3;
		         imagecolorset( $img_gif, $c, $i, $i, $i );
		    }
			@unlink( $output_path );
			imagegif( $img_gif, $output_path );
			imagedestroy( $img_gif );
		} else {
			return false;
		}
	}
	
	public function cropImage($file_input, $file_output, $crop = 'square', $percent = false) {
		list($w_i, $h_i, $type) = getimagesize($file_input);
		if (!$w_i || !$h_i) {
			return false;
		}
		$types = array('','gif','jpeg','png');
		$ext = $types[$type];
		if ($ext) {
			$func = 'imagecreatefrom'.$ext;
			$img = $func($file_input);
		} else {
			return false;
		}
		if ($crop == 'square') {
			$min = $w_i;
			if ($w_i > $h_i) $min = $h_i;
			$w_o = $h_o = $min;
		} else {
			list($x_o, $y_o, $w_o, $h_o) = $crop;
			if ($percent) {
				$w_o *= $w_i / 100;
				$h_o *= $h_i / 100;
				$x_o *= $w_i / 100;
				$y_o *= $h_i / 100;
			}
			if ($w_o < 0) {
				$w_o += $w_i;
			}
			$w_o -= $x_o;
			if ($h_o < 0) {
				$h_o += $h_i;
			}
			$h_o -= $y_o;
		}
		$img_o = imagecreatetruecolor($w_o, $h_o);
		imagecopy($img_o, $img, 0, 0, $x_o, $y_o, $w_o, $h_o);
		if ($type == 2) {
			return imagejpeg($img_o,$file_output,100);
		} else {
			$func = 'image'.$ext;
			return $func($img_o,$file_output);
		}
	}


}
?>