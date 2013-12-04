<?

class StickyThumb
{
	private $width;
	private $height;
	private $mode;
	private $scaleUp;

	public function __construct( $width, $height, $mode, $scaleUp=true ){
	
		$this->width = $width;
		$this->height = $height;
		$this->mode = $mode;
		$this->scaleUp = $scaleUp;
	}
	
	private function getFormatIn( $fileIn ){

		if( function_exists( 'exif_imagetype' ) ){
			$exifType = @exif_imagetype( $fileIn );
		}
		else{
			$exifType = null;
		}
		
		switch( $exitType ){

			case IMAGETYPE_GIF:
				return 'gif';

			case IMAGETYPE_JPEG:
				return 'jpg';

			case IMAGETYPE_PNG:
				return 'png';

			default:
				return strtolower( end( explode( '.', $fileIn ) ) );
		}
	}
	
	private function getFormatOut( $fileOut ){
	
		return strtolower( end( explode( '.', $fileOut ) ) );
	}
	
	private function getImageIn( $fileIn, $format = null ){

		if( !$format ){
			$format = $this->getFormatIn( $fileIn );
		}
	
		switch( $format ){
		
			case'png':
				return imagecreatefrompng( $fileIn );
		
			case'jpg':
				return imagecreatefromjpeg( $fileIn );
		
			case'gif':
				return imagecreatefromgif( $fileIn );
		
			default:
				return null;
		}
	}

	public function makeThumb( $fileIn, $fileOut, $format = null ){

		$imageIn = $this->getImageIn( $fileIn );
		
		$widthIn = imagesx( $imageIn );
		$heightIn = imagesy( $imageIn );
		
		switch( $this->mode ){
		
			case'cover':
			
				$widthOut = $this->width;
				$heightOut = $this->height;
				
				$imageOut = imagecreatetruecolor( $widthOut, $heightOut );
				
				$scale = max( $this->width / (float)$widthIn, $this->height / (float)$heightIn );

				if( !$this->scaleUp ){
					$scale = min( $scale, 1 );
				}
				
				$widthScale = floor( $widthIn * $scale );
				$heightScale = floor( $heightIn * $scale );
				
				imagecopyresampled( 
					$imageOut, 
					$imageIn, 
					
					( $widthOut - $widthScale ) / 2, ( $heightOut - $heightScale ) / 2, 
					0, 0, 
					$widthScale, $heightScale, 
					$widthIn, $heightIn
					);
					
				break;
		
			case'fit':
			
				$scale = min( $this->width / (float)$widthIn, $this->height / (float)$heightIn );

				if( !$this->scaleUp ){
					$scale = min( $scale, 1 );
				}
				
				$widthOut = floor( $widthIn * $scale );
				$heightOut = floor( $heightIn * $scale );
				
				$imageOut = imagecreatetruecolor( $widthOut, $heightOut );
				
				imagecopyresampled( 
					$imageOut, 
					$imageIn, 
					
					0, 0,
					0, 0, 
					$widthOut, $heightOut, 
					$widthIn, $heightIn
					);
					
				break;
		}
		
		if( !$format ){
			$format = $this->getFormatOut( $fileOut );
		}
		
		switch( $format ){
		
			case'png':
				imagepng( $imageOut, $fileOut );
				break;
		
			case'jpg':
				imagejpeg( $imageOut, $fileOut, 90 );
				break;
		
			case'gif':
				imagegif( $imageOut, $fileOut );
				break;
		
		}
	}
}








