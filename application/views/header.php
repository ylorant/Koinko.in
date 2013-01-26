<?php global $config; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    
	<title>Koinko.in - Fast, free and awesome url shortening !</title>
	<meta name="description" content="Koinko.in is a small, fast and free url shortening service, allowing you to shorten your urls with an URL that rocks !">
	<meta name="author" content="Yohann Lorant <yohann.lorant@gmail.com>">
    
    <script type="text/javascript" src="static/js/main.js"></script>
    <script type="text/javascript" src="static/js/jquery.js"></script>
    <script type="text/javascript" src="system/utils/debug.js"></script>
    <script>
		const BASE_URL = "<?php echo $config['base_url']; ?>";
	</script>
	<link rel="stylesheet" href="static/css/bootstrap.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="static/css/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="static/css/debug.css" type="text/css" media="screen" />
	<link rel="shortcut icon" type="image/png" href="static/images/favicon.png" />
</head>
<body>
	<div class="main">
		<a id="logo-link" href="home"><img class="logo" src="static/images/logo.png" /></a>
		<p class="slogan">
			Fast, free and awesome URL shortening service.
		</p>
