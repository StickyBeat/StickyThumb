<?

class StickyThumb
{
	private $width;
	private $height;
	private $mode;

	public function __construct( $width, $height, $mode ){
	
		$this->width = $width;
		$this->height = $height;
		$this->mode = $mode;
	}
	
	private function getFormatIn( $fileIn ){
	
		return strtolower( end( explode( '.', $fileIn ) ) );
	}
	
	private function getFormatOut( $fileOut ){
	
		return strtolower( end( explode( '.', $fileOut ) ) );
	}
	
	private function getImageIn( $fileIn ){
	
		switch( $this->getFormatIn( $fileIn ) ){
		
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

	public function makeThumb( $fileIn, $fileOut ){
	
		$imageIn = $this->getImageIn( $fileIn );
		
		$widthIn = imagesx( $imageIn );
		$heightIn = imagesy( $imageIn );
		
		switch( $this->mode ){
		
			case'cover':
			
				$widthOut = $this->width;
				$heightOut = $this->height;
				
				$imageOut = imagecreatetruecolor( $widthOut, $heightOut );
				
				$scale = max( $this->width / (float)$widthIn, $this->height / (float)$heightIn );
				
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
		
		switch( $this->getFormatOut( $fileOut ) ){
		
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








