<?php
/**
 * Media and Profile Image Helpers
 *
 * Extracted from legacy theme functions so that profile image cropping
 * and resizing functions work seamlessly regardless of the active theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'profile_image_resize_crop' ) ) {
	/**
	 * Profile Images Crop
	 */
	function profile_image_resize_crop( $src, $w, $h, $dest = null, $override = true, $createNewIfExists = true ) {
		if ( empty( $src ) || ! is_string( $src ) ) {
			return $src;
		}
		$parts = explode( '.', $src );
		$ext = strtolower( (string) end( $parts ) );
		$filenameSrc = str_replace( ".$ext", '', basename( $src ) );
		$filename = "{$filenameSrc}-{$w}X{$h}";
		$arrayUploadPath = wp_upload_dir();
		$fileUploadSubDir = '/profile/';
		$fileUploadDir = $arrayUploadPath['basedir'] . '/profile/';

		if ( ! file_exists( $fileUploadDir ) ) {
			wp_mkdir_p( $fileUploadDir );
		}

		if ( is_null( $dest ) ) {
			$dest = $fileUploadDir;
		}

		$i = '';
		if ( ! $override && $createNewIfExists ) {
			$count = 0;
			while ( file_exists( "{$dest}{$filename}-{$count}.jpg" ) ) {
				$count++;
			}
			$i = '-' . $count;
		}

		$fileFullPath = "{$dest}{$filename}{$i}.jpg";
		$fileFullUrl = $arrayUploadPath['baseurl'] . $fileUploadSubDir . $filename . $i . '.jpg';

		if ( ! $override && file_exists( $fileFullPath ) ) {
			return $fileFullUrl;
		}

		if ( $override && file_exists( $fileFullPath ) ) {
			@unlink( $fileFullPath );
		}

		$image = false;
		try {
			switch ( $ext ) {
				case 'jpg':
				case 'jpeg':
					$image = function_exists( 'imagecreatefromjpeg' ) ? @imagecreatefromjpeg( $src ) : false;
					break;
				case 'gif':
					$image = function_exists( 'imagecreatefromgif' ) ? @imagecreatefromgif( $src ) : false;
					break;
				case 'png':
					$image = function_exists( 'imagecreatefrompng' ) ? @imagecreatefrompng( $src ) : false;
					break;
				case 'wbmp':
					$image = function_exists( 'imagecreatefromwbmp' ) ? @imagecreatefromwbmp( $src ) : false;
					break;
				case 'webp':
					$image = function_exists( 'imagecreatefromwebp' ) ? @imagecreatefromwebp( $src ) : false;
					break;
				case 'bmp':
					$image = function_exists( 'imagecreatefrombmp' ) ? @imagecreatefrombmp( $src ) : false;
					break;
				default:
					$image = false;
			}
		} catch ( \Throwable $t ) {
			$image = false;
		}

		if ( ! $image ) {
			return $src;
		}

		$width = imagesx( $image );
		$height = imagesy( $image );
		if ( ! $width || ! $height ) {
			imagedestroy( $image );
			return $src;
		}

		$original_aspect = $width / $height;
		$thumb_aspect = $w / $h;

		if ( $original_aspect >= $thumb_aspect ) {
			if ( $width > $w ) {
				$new_height = $h;
				$new_width = $width / ( $height / $h );
			} else {
				$new_height = $height;
				$new_width = $width;
			}
		} else {
			if ( $width > $w ) {
				$new_width = $w;
				$new_height = $height / ( $width / $w );
			} else {
				$new_width = $width;
				$new_height = $height;
			}
		}

		$thumb = imagecreatetruecolor( $w, $h );
		if ( ! $thumb ) {
			imagedestroy( $image );
			return $src;
		}

		$bg = imagecolorallocate( $thumb, 255, 255, 255 );
		imagefill( $thumb, 0, 0, $bg );

		imagecopyresampled(
			$thumb,
			$image,
			(int) ( 0 - ( $new_width - $w ) / 2 ),
			(int) ( 0 - ( $new_height - $h ) / 2 ),
			0, 0,
			(int) $new_width, (int) $new_height,
			(int) $width, (int) $height
		);

		imagejpeg( $thumb, $fileFullPath, 90 );
		imagedestroy( $thumb );
		imagedestroy( $image );

		return $fileFullUrl;
	}
}
