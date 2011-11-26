<?php
/*
	Plugin Name: Mobile Client Detection Plug-in
	Plugin URI: http://wordpress.org/extend/plugins/mobile-client-detection-plugin/
	Description: The Mobile Client Detection Plug-in provides query_vars 'platform' & 'browser' for simply switching the layout within your theme (requires editing template files). It can also be very helpful when it’s required to load different versions of CSS/JS code.
	Author: Martin Zeitler
	Version: 0.5
	Tags: plugin, mobile, theme, detect, query_var, layout, switch
	Author URI: http://www.codefx.biz/contact
*/

/*
	Copyright 2011 Martin Zeitler (email: martin at codefx.biz)
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/* configuration options */
$general_only = false;
$footer_output = true;
	
if(!defined('WP_PLUGIN_DIR')){die();}
define('MCD_PLUGIN_DIR', WP_PLUGIN_DIR.'/'.plugin_basename(dirname( __FILE__)));
define('MCD_PLUGIN_URL', WP_PLUGIN_URL.'/'.plugin_basename(dirname( __FILE__)));
define('MCD_GENERAL_ONLY', $general_only);
define('MCD_FOOTER_OUTPUT', $footer_output);

/* init */
function MCD_init(){

}

/* add a query var to wp router */
function MCD_add_vars($query_vars){
	$query_vars[] = 'platform';
	$query_vars[] = 'browser';
	return $query_vars;
}

/* just before the theme is loaded */
function MCD_set_vars(){
	
	global $wp_query;
	
	/* Stage 1: Platform Detection - supports several desktop platforms as well */
	$ua = trim(strtolower($_SERVER['HTTP_USER_AGENT']));
	$pattern = '/(android|blackberry|ip(hone|ad|od)|iemobile|webos|palm|symbian|kindle|windows|win64|wow64|macintosh|intel\smac\sos\sx|ppx\smac\sos\sx|googlebot|googlebot-mobile)/';
	if(preg_match($pattern,$ua,$matches)){$platform=$matches[0];}
	
	switch($platform){
		
		/* mobile platforms */
		case 'android':
		case 'blackberry':
		case 'ipad':
		case 'iphone':
		case 'ipod':
		case 'iemobile':
		case 'webos':
		case 'palm':
		case 'symbian':
		case 'kindle':
		case 'googlebot-mobile':
			if(MCD_GENERAL_ONLY){$platform='mobile';}
			break;
		
		/* desktop platforms */
		/* ... */
		case 'windows':
		case 'win64':
		case 'wow64':
		case 'macintosh':
		case 'ppx mac os x':
		case 'intel mac os x':
		case 'googlebot':
			if(MCD_GENERAL_ONLY){$platform='desktop';}
			break;
		
		/* in case nothing else matches */
		default:
			$platform='desktop';
			break;
	}
	$wp_query->set('platform',$platform);
	
	/* Stage 2: Browser Detection */
	$pattern = '/(firefox|fennec|msie\s5\.0|msie\s6\.0|msie\s7\.0|msie\s8\.0|msie\s9\.0|msie\s10\.0|iemobile|chrome|safari\smobile|safari|camino|googlebot|googlebot-mobile|w3c_validator)/';
	if (preg_match($pattern, $ua, $matches)){$browser=$matches[0];}
	switch($browser){
		
		/* mobile browsers */
		case 'fennec':
		case 'iemobile':
		case 'safari mobile':
		case 'googlebot-mobile':
			if(MCD_GENERAL_ONLY){$browser='mobile';}
			break;
		
		/* desktop browsers */
		case 'msie 5.0':
		case 'msie 6.0':
		case 'msie 7.0':
		case 'msie 8.0':
		case 'msie 9.0':
		case 'msie 10.0':
		case 'chrome':
		case 'camino':
		case 'firefox':
		case 'safari':
			if(MCD_GENERAL_ONLY){$browser='desktop';}
			break;
		
		/* bots */
		case 'w3c_validator':
			if(MCD_GENERAL_ONLY){$browser='bot';}
			break;
		
		/* this catches all bots added to the regex but not the switch */
		default:								$browser='bot';
														break;
	}
	$wp_query->set('browser',$browser);
	
}

function mcd_footer_callback($content){
	
	/* getting query_vars */
	if(get_query_var('platform')){$platform = get_query_var('platform');}
	if(get_query_var('browser')){$browser = get_query_var('browser');}
	
		switch($platform){
			
			/* mobile clients */
			case 'mobile':						$tag = 'Mobile';
																break;
			case 'android':						$tag = 'Android';
																break;
			case 'blackberry':				$tag = 'BlackBerry';
																break;
			case 'iphone':		
			case 'ipad':			
			case 'ipod':							$tag = 'Apple';
																break;
			case 'iemobile':					$tag = 'mobile IE';
																break;
			case 'webos':							$tag = 'webOS';
																break;
			
			/* desktop clients */
			case 'windows':						$tag = 'Windows';
																break;
			case 'win64':							$tag = 'Windows 64-bit';
																break;
			case 'macintosh':					$tag = 'Macintosh';
																break;
			case 'ppx mac os x':			$tag = 'PPC OSX';
																break;
			case 'intel mac os x':		$tag = 'Intel OSX';
																break;
			case 'desktop':						$tag = 'Desktop';
																break;
			
			/* bots */
			case 'googlebot':					$tag = 'Bot';
																break;
			case 'googlebot-mobile':	$tag = 'Bot';
																break;
			
			/* this case will not happen - since the query_var defaults to desktop */
			default:							$tag = $platform;break;
			
		}
	
	$html =	'<span style="color:#FCFCFC;height:16px;margin-top:-16px;display:block;">
						&raquo; You are currently viewing the '.$tag.' version of this blog ('.$browser.') &laquo;</span>';
	$html .='<span style="color:#FCFCFC;height:16px;margin-top:-16px;">('.$_SERVER['HTTP_USER_AGENT'].')</span>';
	echo $html;
}

add_action('init', 'MCD_init');
add_filter('query_vars', 'MCD_add_vars');
add_filter('wp_head', 'MCD_set_vars');
if(MCD_FOOTER_OUTPUT && !is_admin()){
	add_action('wp_footer', 'mcd_footer_callback');
}
?>