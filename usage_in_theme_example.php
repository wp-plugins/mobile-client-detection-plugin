<?php
	/*
		Mobile Client Detection Plug-In usage example
		this is an example how provided query_var 'platform' and 'browser' can be used in your theme
		
		- place this code into your theme (e.g. functions.php, index.php, header.php)
		  the concrete placement merely depends on the concrete situation
		
		The var $output can enable/disable debug output, possible values: true/false
		
		The actual switching doesn't happen here, the code just processes the a WP query_var.
		
	*/
	
	/* if the query_var even exists */
	if(get_query_var('platform')){
		
		$platform = get_query_var('platform');
		
		switch($platform){
			
			/* mobile clients */
			case 'mobile':		$tag = 'Mobile';break;
			case 'android':		$tag = 'Android';break;
			case 'blackberry':$tag = 'BlackBerry';break;
			case 'iphone':
			case 'ipad':
			case 'ipod':			$tag = 'Apple';break;
			case 'iemobile':	$tag = 'mobile IE';break;
			case 'webos':			$tag = 'webOS';break;
			case 'kindle':		$tag = 'Kindle';break;
			case 'palm':			$tag = 'Palm';break;
			
			/* desktop clients */
			case 'windows':
			case 'win64':
			case 'macintosh':
			case 'ppx mac os x':
			case 'intel mac os x':
			case 'googlebot':
			case 'desktop':		$tag = 'Desktop';break;
			
			/* this case will not happen - since the query_var defaults to desktop */
			default:					$tag = $platform;break;
		}
		
		if(get_query_var('browser')){
			$browser = get_query_var('browser');
			switch($browser){
				
				/* ... */
				
			}
		}
	}
?>