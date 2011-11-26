<?php
/*
	Plugin Name: Mobile Client Detection Plugin
	Plugin URI: http://wordpress.org/extend/plugins/mobile-client-detection-plugin/
	Description: The Mobile Client Detection Plug-in provides query_vars 'platform' & 'browser' for simply switching the layout within your theme (requires editing template files). It can also be very helpful when it’s required to load different versions of CSS/JS code.
	Author: Martin Zeitler
	Version: 0.7.0
	Tags: plugin, mobile, theme, detect, query_var, layout, switch, page speed, platform, browser
	Author URI: http://www.codefx.biz
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

if(!defined('WP_PLUGIN_DIR')){die();}
define('mcd_PLUGIN_DIR', WP_PLUGIN_DIR.'/'.plugin_basename(dirname( __FILE__)));
define('mcd_PLUGIN_URL', WP_PLUGIN_URL.'/'.plugin_basename(dirname( __FILE__)));

/* load configuration options */
$mcd_options = mcd_get_option();

/* not yet implememted */
$add_query_vars = true;
$switch_template = false;
$switch_theme = false;

define('mcd_GENERAL_ONLY',(($mcd_options[0]==0)? true : false));
define('mcd_DEBUG_OUTPUT',(($mcd_options[1]==1)? true : false));
define('mcd_ADD_QUERY_VARS', $add_query_vars);
define('mcd_SWITCH_TEMPLATE', $switch_template);
define('mcd_SWITCH_THEME', $switch_theme);

/* init */
function mcd_init(){
	
	if(mcd_ADD_QUERY_VARS){
		add_filter('query_vars', 'mcd_add_vars');
		add_filter('wp_head', 'mcd_set_vars');
	}
	
	/* if debug mode is enabled */
	if(!is_admin() && mcd_DEBUG_OUTPUT){add_action('wp_footer', 'mcd_debug_output');}
	
	/* add options page for admins */
	if(is_admin()){add_action('admin_menu', 'mcd_plugin_menu');}
	
}

/* add a query var to wp router */
function mcd_add_vars($query_vars){
	$query_vars[] = 'platform';
	$query_vars[] = 'browser';
	return $query_vars;
}

/* just before the theme is loaded */
function mcd_set_vars(){
	
	global $wp_query;
	
	/* get option index 0 */
	$mcd_options = mcd_get_option();
	if((int)$mcd_options[0]==1){$general_output=true;}
	
	/* Stage 1: Platform Detection - supports several desktop platforms as well */
	$ua = trim(strtolower($_SERVER['HTTP_USER_AGENT']));
	$pattern = '/(android\s\d|blackberry|ip(hone|ad|od)|iemobile|webos|palm|symbian|kindle|windows|win64|wow64|macintosh|intel\smac\sos\sx|ppx\smac\sos\sx|googlebot|googlebot-mobile)/';
	if(preg_match($pattern,$ua,$matches)){$platform=$matches[0];}
	
	switch($platform){
		
		/* mobile platforms */
		case 'android 4':
		case 'android 3':
		case 'ipad':
		case 'kindle':
			if($general_output){$platform='tablet';}
			break;
		case 'android 2':
		case 'android 1':
		case 'blackberry':
		case 'iphone':
		case 'ipod':
		case 'iemobile':
		case 'webos':
		case 'palm':
		case 'symbian':
		case 'googlebot-mobile':
			if($general_output){$platform='mobile';}
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
			if($general_output){$platform='desktop';}
			break;
		
		/* in case nothing else matches */
		default:
			$platform='desktop';
			break;
	}
	$wp_query->set('platform',$platform);
	
	/* Stage 2: Browser Detection */
	$pattern = '/(firefox|fennec|msie\s5|msie\s6|msie\s7|msie\s8|msie\s9|msie\s10|iemobile|chrome|mobile\ssafari|safari|camino|googlebot|googlebot-mobile|w3c_validator)/';
	if (preg_match($pattern, $ua, $matches)){$browser=$matches[0];}
	switch($browser){
		
		/* mobile browsers */
		case 'fennec':
		case 'iemobile':
		case 'mobile safari':
		case 'googlebot-mobile':
			if($general_output){$browser='mobile';}
			break;
		
		/* desktop browsers */
		case 'msie 5':
		case 'msie 6':
		case 'msie 7':
		case 'msie 8':
		case 'msie 9':
		case 'msie 10':
		case 'chrome':
		case 'camino':
		case 'firefox':
		case 'safari':
			if($general_output){$browser='desktop';}
			break;
		
		/* desktop bots */
		case 'w3c_validator':
		case 'googlebot':
			if($general_output){$browser='bot';}
			break;
		
		/* this catches all bots added to the regex but not the switch */
		default:								$browser='bot';
														break;
	}
	$wp_query->set('browser',$browser);
	
}

function mcd_debug_output(){
	$mcd_options = mcd_get_option();
	if((int)$mcd_options[1]==1){
		
		/* getting query_vars */
		if(get_query_var('platform')){$platform = get_query_var('platform');}
		if(get_query_var('browser')){$browser = get_query_var('browser');}
		
		switch($platform){
			
			/* phones */
			case 'android 1':
			case 'android 2':				$tag = 'Android (Phone)';break;
			case 'blackberry':			$tag = 'BlackBerry';break;
			case 'iphone':
			case 'ipod':						$tag = 'Apple (Phone)';break;
			case 'iemobile':				$tag = 'mobile IE';break;
			case 'webos':						$tag = 'webOS';break;
			case 'mobile':					$tag = 'General Mobile';break;
			
			/* tablets */
			case 'android 3':
			case 'android 4':				$tag = 'Android (Tablet)';break;
			case 'ipad':						$tag = 'Apple (Tablet)';break;
			case 'kindle':					$tag = 'Kindle (Tablet)';break;
			case 'tablet':					$tag = 'General Tablet';break;
			
			/* desktop clients */
			case 'wow64':
			case 'win64':						$tag = 'Windows x64';break;
			case 'windows':					$tag = 'Windows x86';break;
			case 'macintosh':				$tag = 'Macintosh';break;
			case 'ppx mac os x':		$tag = 'PPC OSX';break;
			case 'intel mac os x':	$tag = 'Intel OSX';break;
			case 'desktop':					$tag = 'General Desktop';break;
			
			/* bots */
			case 'googlebot':
			case 'googlebot-mobile':
			case 'w3c_validator':		$tag = 'Bot';
															break;
			
			/* this case will not happen - since the query_var defaults to desktop */
			default:							$tag = $platform;break;
			
		}
		
		$html =	'<span style="color:#FCFCFC;height:16px;margin-top:-16px;display:block;">
							&raquo; You are currently viewing the '.$tag.' version of this blog ('.$browser.') &laquo;</span>';
		// $html .='<span style="color:#FCFCFC;height:16px;margin-top:-16px;">('.$_SERVER['HTTP_USER_AGENT'].')</span>';
		echo $html;
	}
/*
    [0] => 1
    [1] => 1
    [2] => 0
    [mcd_options] => 
*/
}

function mcd_plugin_menu() {
	add_options_page('MCD Options', 'Client Detection', 'manage_options', 'mcd_plugin', 'mcd_options_page_hook');
}

function mcd_options_page_hook(){
	
	if(!current_user_can( 'manage_options')){die (__( "You don't have sufficient privileges to display this page", 'mcd_plugin' ) );}
	
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"></div>
		<h2><a href="http://www.codefx.biz/donations"><?php echo __('Mobile Client Detection', 'mcd_plugin');?></a></h2>
		<div class="su-tabs">
		<?php
		if($_POST){
			/* update options */
			$mcd_options = array((int)$_POST['general_results'],(int)$_POST['debug_output'],(int)$_POST['mcd_mode']);
			update_option('mcd_options',$mcd_options);
		}
		else {
			
			/* get options */
			$mcd_options = mcd_get_option();
		}
		?>
		<form method="post" action="options-general.php?page=mcd_plugin">
			<fieldset>
				
				<h3>General Results (mobile,desktop,tablet):</h3>
				<select id="general_results" name="general_results">
					<option value="1"<?php echo(($mcd_options[0]==1)?' selected="selected"':'');?>>Yes</option>
					<option value="0"<?php echo(($mcd_options[0]==0)?' selected="selected"':'');?>>No</option>
				</select>
				<br/>
				
				<h3>Debug Output in Footer:</h3>
				<select id="debug_output" name="debug_output">
					<option value="1"<?php echo(($mcd_options[1]==1)?' selected="selected"':'');?>>Yes</option>
					<option value="0"<?php echo(($mcd_options[1]==0)?' selected="selected"':'');?>>No</option>
				</select>
				<br/>
				
				<h3>Modus operandi:</h3>
				<select id="mcd_mode" name="mcd_mode">
					<option value="0"<?php echo(($mcd_options[2]==0)?' selected="selected"':'');?>>Enable query_vars only</option>
					<option value="1"<?php echo(($mcd_options[2]==1)?' selected="selected"':'');?>>Load a custom template file</option>
					<option value="2"<?php echo(($mcd_options[2]==2)?' selected="selected"':'');?>>Load a custom theme directory</option>
				</select>
				<br/>
				<p>Custom templates & themes are not yet implemented.</p>
			</fieldset>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
			</form>
		</div>
	</div>
	<?php
}

function mcd_get_option( $name = NULL ){
	$options = get_option('mcd_options');
	if($options === FALSE){
		add_option('mcd_options',array(0,1,0));
	}
	if(is_null($name)){
		return get_option('mcd_options');
	}
	elseif(isset($options[$name])){
		return $options[$name];
	}
	else {
		return FALSE;
	}
}

add_action('init', 'mcd_init');
?>