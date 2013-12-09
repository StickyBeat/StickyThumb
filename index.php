<?php?>


<?
if( isset($_GET['download'] ) ){

	$filename = 'StickyThumb.class.php';

	header('Content-Disposition: attachment; filename="' . $filename . '"');
	
	readfile( $filename );

	exit;
}
?>
<html>
	<head>
		<title>StickyThumb!</title>
	</head>
	<body>
		<h1>StickyThumb!</h1>
		<form>
			<p>
				<label>Input Filename or URL</label><br/>
				<input type="text" name="inFile" value="<?=@$_GET['inFile'] ?: 'Arnold-Standing-Smiling.jpg'?>" size="100"/>
			</p>
			<p>
				<label>Output Filename</label><br/>
				<input type="text" name="outFile" value="<?=@$_GET['outFile'] ?: 'thumb.jpg' ?>" size="100"/>
			</p>
			<p>
				<label>Size</label><br/>
				<input type="text" name="width" value="<?=@$_GET['width'] ?: 200?>" size="30"/> x <input type="text" name="height" value="<?=@$_GET['height'] ?: 200?>" size="30"/>
			</p>
			<p>
				<label>Mode</label><br/>
				<?
				
				$modes = array('cover','fit');
				
				if( isset( $_GET['mode'] ) ){
					$selectedMode = @$_GET['mode'];
				}
				else{
					$selectedMode = current( $modes );
				}
				
				foreach( $modes as $mode ){
					?><label for="mode_<?=$mode?>"><input id="mode_<?=$mode?>" type="radio" name="mode" value="<?=$mode?>"<? if( $mode == $selectedMode ){ ?> checked="checked"<? } ?>/> <?=$mode?></label><br/><?
				}
				?>
			</p>
			<p>
				<?
				$sizeUp = ( @$_GET['sizeUp'] ) != 'no';

				$sizeUpIsDefault = $sizeUp == true;
				?>
				<label>Upsizeing</label><br/>
				<input type="hidden" name="sizeUp" value="no" />
				<label for="sizeUp">
					<input id="sizeUp" type="checkbox" name="sizeUp" value="yes" <? if( $sizeUp ){ ?>checked="checked" <? } ?> />
					Upsize small images
				</label>
			</p>
			<p>
				<?
				if( empty( $_GET['backgroundColor'] ) ){
					$backgroundColor = 0xffffff;
				}
				else{
					$backgroundColor = hexdec( $_GET['backgroundColor'] );
				}

				$backgroundColorIsDefault = $backgroundColor == 0xffffff;

				$backgroundColorHex = str_pad( dechex( $backgroundColor ), 6, '0', STR_PAD_LEFT );

				?>
				<label>Background color</label><br/>
				<label for="backgroundColor">
					<input id="backgroundColor" type="text" name="backgroundColor" value="<?=$backgroundColorHex?>" />
				</label>
			</p>			
			<p>
				<input type="submit" value="Try it!"/>
				<input type="button" value="Download the PHP class file!" onclick="location='index.php?download';"/>
			</p>
		</form>
		<?
		
		if( !empty( $_GET['inFile'] ) and !empty( $_GET['outFile'] ) and !empty( $_GET['width'] ) and !empty( $_GET['height'] ) ){
		
			if( end(explode('.',$_GET['outFile'])) != 'php' ){
				
				include'StickyThumb.class.php';
				
				$sticky = new StickyThumb( $_GET['width'], $_GET['height'], $_GET['mode'], $sizeUp, $backgroundColor );
				$sticky->makeThumb( $_GET['inFile'], $_GET['outFile'] );
				
				?>
				<pre>
$thumb = new StickyThumb( <?=$_GET['width']?>, <?=$_GET['height']?>, '<?=$_GET['mode']?>'<?

	if( !$sizeUpIsDefault || !$backgroundColorIsDefault ){

		?>, <? var_export( $sizeUp )?><?
	}

	if( !$backgroundColorIsDefault ){
		?>, 0x<?=$backgroundColorHex?> );<?
	}

	?> );<?

echo "\n"

?>$thumb->makeThumb( <? var_export( $_GET['inFile'] )?>, <? var_export( $_GET['outFile'])?> );

				</pre>
				<p>
					<img src="<?=$_GET['outFile']?>"/><br/>
					<?
					list( $width, $height ) = getimagesize( $_GET['outFile'] );
					?>
					<?=$width?> x <?=$height?>
				</p>
				<?
			}
		}

		?>
	</body>
</html>
