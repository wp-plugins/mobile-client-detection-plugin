<?php
/*
	Plugin Name: Mobile Client Detection Plugin
	Plugin URI: http://wordpress.org/extend/plugins/mobile-client-detection-plugin/
	Description: The Mobile Client Detection Plugin provides query_vars 'platform' & 'browser'. It can also be very helpful when it’s required to load different versions of CSS/JS code.
	Author: Martin Zeitler
	Version: 0.7.4
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

/* init */
function mcd_init(){
	
	/* add these filters by default */
	add_filter('query_vars', 'mcd_add_vars');
	add_filter('wp_head', 'mcd_set_vars');

	/* get the options */
	$mcd_options = mcd_get_option();
	
	/* if debug mode is enabled: */
	if((int)$mcd_options[1]==1){$debug_output=true;}
	if(!is_admin() && $debug_output){add_action('wp_footer','mcd_debug_output');}
	
	if ( defined('WP_USE_THEMES') && WP_USE_THEMES ){
		
		/* if loading template is enabled: */
		if((int)$mcd_options[2]==1){$load_template=true;}
		if((int)$mcd_options[2]==2){$load_theme=true;}
		
		if($load_template){
			//add_filter('template_include', 'mcd_load_template', 1, 1);
			//add_action('template_redirect','mcd_load_template');
		}
		
		/* if loading themes is enabled: */
		if($load_theme){
			add_action('template_redirect','mcd_load_theme');
		}
	}
	/* hook options page for admins only */
	if(is_admin() && current_user_can('manage_options')){add_action('admin_menu','mcd_plugin_menu');}
}

/* get [0]theme_path, [1]theme_name, [2]template_name as array */
function mcd_get_theme() {
	
	/* some code from wp core */
	global $wp_query;
	$template = false;
	if    (is_404()            && $template = get_404_template()           ):
	elseif(is_search()         && $template = get_search_template()        ):
	elseif(is_tax()            && $template = get_taxonomy_template()      ):
	elseif(is_front_page()     && $template = get_front_page_template()    ):
	elseif(is_home()           && $template = get_home_template()          ):
	elseif(is_attachment()     && $template = get_attachment_template()    ):
	elseif(is_single()         && $template = get_single_template()        ):
	elseif(is_page()           && $template = get_page_template()          ):
	elseif(is_category()       && $template = get_category_template()      ):
	elseif(is_tag()            && $template = get_tag_template()           ):
	elseif(is_author()         && $template = get_author_template()        ):
	elseif(is_date()           && $template = get_date_template()          ):
	elseif(is_archive()        && $template = get_archive_template()       ):
	elseif(is_comments_popup() && $template = get_comments_popup_template()):
	elseif(is_paged()          && $template = get_paged_template()         ):
	else :
		$template = get_index_template();
	endif;
	
	/* but here comes the extraction regex: */
	if(preg_match('/\/(.*)\/(.*)\/(.*)\.php/i',$template,$arr)){return $arr;}
}

/* template overloading - not implemented yet */
function mcd_load_template($debug = false) {
	global $wp_query;
	if(get_query_var('platform')){
		
		$platform = get_query_var('platform');
		$theme = mcd_get_theme();
		
		$templates = array();
		$templates[] = $theme[3].'-'.$platform.'.php';
		
		if($pagename){$templates[] = $theme[3]."-$pagename-".$platform.".php";}
		if($id){$templates[] = $theme[3]."-$id-".$platform.".php";}
		$templates[] = $theme[3].'.php';
			
		if($debug){return print_r($templates,true);}
		return get_query_template( $theme[2], $templates );
	}
}

/* theme overloading - not implemented yet */
function mcd_load_theme($debug) {
	global $wp_query;
	if(get_query_var('platform')){
		
		$platform = get_query_var('platform');
		$theme = mcd_get_theme();
		
		$templates = array();
		$templates[] = $theme[2].'-'.$platform.'/'.$theme[3].'.php';
		$templates[] = $theme[2].'/'.$theme[3].'.php';

		if($debug){return print_r($templates,true);}
		return get_query_template( $theme[2], $templates );
	}
}

/* adds the query_vars to WP_query */
function mcd_add_vars($query_vars){
	$query_vars[] = 'platform';
	$query_vars[] = 'browser';
	return $query_vars;
}

/* the actual regex detection happens here */
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
		
		/* phones */
		case 'android 1':
		case 'android 2':
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
		
		/* tablets */
		case 'android 3':
		case 'android 4':
		case 'ipad':
		case 'kindle':
			if($general_output){$platform='tablet';}
			break;
		
		/* desktops */
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
	
	/* combining some platforms - required for template files */
	switch($platform){
		
		case 'android 1':
		case 'android 2':
			if(!$general_output){$platform='android-phone';}
			break;
		
		/* mobile platforms */
		case 'android 3':
		case 'android 4':
			if(!$general_output){$platform='android-tablet';}
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

/* get plugin options - or create them */
function mcd_get_option($name = NULL){
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

/* hooking the menu item */
function mcd_plugin_menu() {
	add_options_page('MCD Options', 'Client Detection', 'manage_options', 'mcd_plugin', 'mcd_options_page_hook');
}

/* the options page */
function mcd_options_page_hook(){
	if(!current_user_can( 'manage_options')){die (__( "You don't have sufficient privileges to display this page", 'mcd_plugin' ) );}
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"></div>
		<h2><?php echo __('Mobile Client Detection','mcd_plugin');?></h2>
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

/* footer debug output */
function mcd_debug_output(){
	
	$mcd_options = mcd_get_option();
	if((int)$mcd_options[1]==1){
		
		/* getting query_vars */
		if(get_query_var('platform')){$platform = get_query_var('platform');}
		if(get_query_var('browser')){$browser = get_query_var('browser');}
		
		switch($platform){
			
			/* phones */
			case 'android 1':
			case 'android 2':
			case 'android-phone':		$tag = 'Android (Phone)';break;
			case 'blackberry':			$tag = 'BlackBerry';break;
			case 'iphone':
			case 'ipod':						$tag = 'Apple (Phone)';break;
			case 'iemobile':				$tag = 'mobile IE';break;
			case 'webos':						$tag = 'webOS';break;
			case 'mobile':					$tag = 'Phone';break;
			
			/* tablets */
			case 'android 3':
			case 'android 4':				
			case 'android-tablet':	$tag = 'Android (Tablet)';break;
			case 'ipad':						$tag = 'Apple (Tablet)';break;
			case 'kindle':					$tag = 'Kindle (Tablet)';break;
			case 'tablet':					$tag = 'Tablet';break;
			
			/* desktop clients */
			case 'wow64':
			case 'win64':						$tag = 'Windows x64';break;
			case 'windows':					$tag = 'Windows x86';break;
			case 'macintosh':				$tag = 'Macintosh';break;
			case 'ppx mac os x':		$tag = 'PPC OSX';break;
			case 'intel mac os x':	$tag = 'Intel OSX';break;
			case 'desktop':					$tag = 'Desktop';break;
			
			/* bots */
			case 'googlebot':
			case 'googlebot-mobile':
			case 'w3c_validator':		$tag = 'Bot';
															break;
			
			/* this case will not happen - since the query_var defaults to desktop */
			default:							$tag = $platform;break;
			
		}
		
		$theme = mcd_get_theme();
		$html = '<div id="mcd_debug" style="width:420px;color:#FCFCFC;margin:auto;margin-top:-16px;cursor:default;">';
		$html .='<ul style="list-style-type:none;margin-left:-30px">';
		$html .='<li style="height:16px;">&raquo; This could be the '.$tag.' version of this blog ('.$browser.') &laquo;</li>';
		$html .='<li style="height:16px;">Option General Results Only: '.(((int)$mcd_options[0]==1)?' Yes':'No').'</li>';
		$html .='<li style="height:16px;">Standard 0: '.$theme[2].'/'.$theme[3].'.php'.(((int)$mcd_options[2]==0)?' (active)':'').'</li>';
		$html .='<li style="height:16px;">Template 1: '.mcd_load_template(true).(((int)$mcd_options[2]==1)?' (active)':'').'</li>';
		$html .='<li style="height:16px;">Theme 2: '.mcd_load_theme(true).(((int)$mcd_options[2]==2)?' (active)':'').'</li>';
		
		//$html .='<li style="float:left;height:16px;display:block;">('.$_SERVER['HTTP_USER_AGENT'].')</li>';
		
		$html .='</ul></div>';
		echo $html;
	}
}
add_action('init', 'mcd_init');

/* some add-on functions (mode general only!) - written in the WP conditional style */
function is_desktop(){return((get_query_var('platform')=='desktop')? true : false);}
function is_mobile(){return((get_query_var('platform')=='mobile')? true : false);}
function is_tablet(){return((get_query_var('platform')=='tablet')? true : false);}
?>