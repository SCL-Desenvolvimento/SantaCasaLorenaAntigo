<?php 

$editor1 = (string) $_GET['editor1'];
function TagExtract( $str, $tagIni, $tagEnd ){
	$arr = explode( $tagIni, $str );
	foreach( $arr as $k => $v ){
		$arr[$k] = substr( $v, 0, strpos( $v, $tagEnd ) );
	}
	if( empty( $arr[0] ) ){
		unset( $arr[0] );
	}
	return $arr;	
}

$imgs = TagExtract( $editor1, 'src="', '"' );
foreach($imgs as $img){
	if(strripos($img, ".jpg") || strripos($img, ".png")){
		echo $img."<br>";
	}
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>A Simple Page with CKEditor</title>
        <!-- Make sure the path to CKEditor is correct. -->
        <script src="ckeditor.js"></script>
    </head>
    <body>
        <form method="GET">
            <textarea name="editor1" id="editor1" rows="10" cols="80">
                This is my textarea to be replaced with CKEditor.
            </textarea>
            <script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'editor1' );
            </script>
            <input type="submit" value="Enviar">
        </form>
    </body>
</html>