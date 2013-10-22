StickyThumb
===========

A little PHP class for generating thumbnail images.

Example usage:
--------------

	$thumb = new StickyThumb( 200, 200, 'cover' );
	$thumb->makeThumb( 'Arnold-Standing-Smiling.jpg', 'thumb.jpg' );
	