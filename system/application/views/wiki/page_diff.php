<html>
<head>
<title>Welcome to CI-Wiki</title>

<style type="text/css">

body {
 background-color: #fff;
 margin: 40px;
 font-family: Lucida Grande, Verdana, Sans-serif;
 font-size: 14px;
 color: #4F5155;
}

a {
 color: #003399;
 background-color: transparent;
 font-weight: normal;
}

h1 {
 color: #444;
 background-color: transparent;
 border-bottom: 1px solid #D0D0D0;
 font-size: 16px;
 font-weight: bold;
 margin: 24px 0 2px 0;
 padding: 5px 0 6px 0;
}

code {
 font-family: Monaco, Verdana, Sans-serif;
 font-size: 12px;
 background-color: #f9f9f9;
 border: 1px solid #D0D0D0;
 color: #002166;
 display: block;
 margin: 14px 0 14px 0;
 padding: 12px 10px 12px 10px;
}

a {
	text-decoration: none;
}

#wiki-tools {
	float: right;
}

span.ins {
	display: block; background-color: #9f9;
}

span.del {
	display: block; background-color: #f99;
}

</style>
</head>
<body>

<h3><?= $diff->title ?></h3>

<?php $lines = explode( "\n", $diff->body ); 
	foreach( $lines as $line ):
		if( strlen($line)) {
			if( $line[0] == '<' ) {
				echo '<span class="del">' . htmlspecialchars($line) . '</span>';
			}
			else if( $line[0] == '>' ) {
				echo '<span class="ins">' . htmlspecialchars($line) . '</span>';			
			} else {
				echo '<br/>' .  htmlspecialchars($line);			
			}
		} 
	endforeach;
?>