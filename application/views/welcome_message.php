<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<link rel="icon" type="image/png" href="<?php base_url() ?>images/logo.png">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
	<script src="<?php base_url() ?>js/matrix.js"></script>
	<script src="<?php base_url() ?>js/typing.js"></script>
	<meta charset="utf-8">
	<title>API Access</title>

	<style type="text/css">
		#message{
			width: 600px;
			height: 100px;
			/*background-color: blue;*/
			position: absolute; /*Can also be `fixed`*/
			left: 0;
			right: 0;
			top: 0;
			bottom: 0;
			margin: auto;
			padding: 0;
			/*Solves a problem in which the content is being cut when the div is smaller than its' wrapper:*/
			max-width: 100%;
			max-height: 100%;
			overflow: auto;
			color: white;
		}
		body {
		padding: 0;
		margin: 0;
		overflow: hidden; /* Hide scrollbars */
		}
		
		.red{
			color:red;
		}
		
		canvas {
			z-index: 0;
			display:inline;
			float:left;
			width: 100%;
		}
	</style>
</head>
<body>

	

		<canvas id="q" >Sorry Browser Won't Support</canvas><br/><br/>		
	
		<div id="message">
		
		
		</div>
</body>
</html>