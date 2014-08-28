<?php

// *** Include the class
include("image_resizer.php");

class image_resizer_caller {

  function resize_image($fileName, $extension) {
    /// *** 1) Initialize / load image
	$resizeObj = new resize('../../filesystem/userimages/'.$fileName.'.'.$extension);
	 
	// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
	//Receives ($newWidth, $newHeight and options).
	$resizeObj -> resizeImage(900, 650, 'crop');
	 
	// *** 3) Save image
	//Receives ($savePath and $imageQuality).
	$resizeObj -> saveImage('../../filesystem/resizeduserimages/'.$fileName.'_resized.png', 100);
  }
}
 
?>