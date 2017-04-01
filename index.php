<!DOCTYPE html>
<head>
	<link rel="stylesheet" href="style.css" type="text/css">
	<title>Tarhaneh Remote File Transfer</title>
</head>
<body>
	<?php
	if ( $_GET['upload'] ) {
		$url  = $_POST['url'];
		$name = basename( $url );
		if      ( $_POST['metod'] == 'copy' ) { copy( $url, $name ); }
		elseif  ( $_POST['metod'] == 'curl' ) {
			set_time_limit(0);
			$ch = curl_init ($url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
			
			curl_setopt($ch, CURLOPT_TIMEOUT,300);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,0);
			
			curl_setopt($ch, CURLOPT_VERBOSE, 0);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
			
			$raw=curl_exec($ch);
			curl_close ($ch);
			if(file_exists($name)){
				unlink($name);
			}
			$fp = fopen($name,'x');
			fwrite($fp, $raw);
			fclose($fp);
		}
		$size = round( ( filesize( $name ) / 1000000 ), 3 );
		?>
		<div class="result">
			انتقال با موفقیت به پایان رسید<br><br>
			<a href="<?php echo $name ?>"><?php echo $name ?></a><br>
<!--			<a href="--><?php //echo $url ?><!--">--><?php //echo $name ?><!--</a><br><br>-->
		</div>
	<?php } else { ?>
		<form action="/?upload=true" id="uploader" method="post">
			<input name="url" placeholder="File URL" required>
			<input type="submit" value="Start">
			<br>
			<span>Select Transfer Metod: </span>
			<label><input type="radio" name="metod" value="copy" checked>copy</label>
			<label><input type="radio" name="metod" value="curl">curl</label>
		</form>
	<?php } ?>
</body>
</html>
