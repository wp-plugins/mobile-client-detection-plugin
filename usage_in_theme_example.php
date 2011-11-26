<?php
/* Template Name: Mobile Client Detection Example */
/*
	This is a basic example how query_var 'platform' and 'browser' can be used in a theme
	
	The actual switching doesn't happen here, the code just processes the a WP query_var.
*/
	
/* if the query_var 'platform' even exists */
if(get_query_var('platform')){
	$platform = get_query_var('platform');
	switch($platform){
		
		/* phones */
		case 'mobile':		$tag = 'Mobile';break;
		case 'android 1':	
		case 'android 2':	$tag = 'Android (Phone)';
											break;
		case 'android 3':	
		case 'android 4':	$tag = 'Android (Tablet)';
											break;
		case 'blackberry':$tag = 'BlackBerry';break;
		case 'iphone':		
		case 'ipod':			$tag = 'Apple (Phone)';break;
		case 'ipad':			$tag = 'Apple (Tablet)';break;
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
}
/* if the query_var 'browser' even exists */
if(get_query_var('browser')){
	$browser = get_query_var('browser');
	switch($browser){
		
		/* ... */
		
	}
}

get_header();
echo '<div>'.$tag.'</div>';
get_footer();
?>