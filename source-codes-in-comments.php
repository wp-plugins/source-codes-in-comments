<?php
/*
 * Plugin Name: Source Codes in Comments
 * Plugin URI: http://zenverse.net/source-codes-in-comments-plugin/
 * Description: Allow users to post source codes in comments using [code][/code] tag. No syntax highlight at the moment, it just replaces the special characters.
 * Author: Zen
 * Author URI: http://zenverse.net/
 * Version: 1.0.2
*/

$zv_scic_plugin_name = 'Source Codes in Comments';
$zv_scic_plugin_dir = WP_CONTENT_URL.'/plugins/source-codes-in-comments/';
$zv_scic_plugin_ver = '1.0.2';
$zv_scic_plugin_url = 'http://zenverse.net/source-codes-in-comments-plugin/';
$zv_scic_plugin_formmsg = 'Please wrap all source codes with [code][/code] tags. Powered by <a href="http://zenverse.net/source-codes-in-comments-plugin/">Source Codes in Comments</a>.';

$scic_options = get_option('scic_options');

/* The old way*/
function scic_strip($match) {
if ($match[1]=='') { return '[code][/code]'; }
$output = $match[1];

$output = str_replace('&','&amp;',$output);
$output = str_replace('&amp;#8216;','&#039;',$output);
$output = str_replace('&amp;#8217;','&#039;',$output);
//$output = str_replace('&#8216;','&#039;',$output);
//$output = str_replace('&#8217;','&#039;',$output);
$output = str_replace('\'','&#039;',$output);
$output = str_replace('"','&quot;',$output);
$output = str_replace('<','&lt;',$output);
$output = str_replace('>','&gt;',$output);
return '<div class="cic_codes_div"><code>'.$output.'</code></div>';
//htmlspecialchars does not work as it should
//return '<div class="cic_codes_div"><code>'.htmlspecialchars(stripslashes($match[1])).'</code></div>';
}

$scic_shortcode = array();
function scic_searchandreplace($comment) {
/* The old way*/
$comment = preg_replace_callback("/\[code\](.*)\[\/code\]/Usi","scic_strip",$comment);
return $comment;

// might need this in the future?
/*global $scic_shortcode;
$scic_shortcode = array('code'=>'scic_shortcode_process');
$tagnames = array_keys($scic_shortcode);
$tagregexp = join( '|', array_map('preg_quote', $tagnames) );
$pattern = '\[('.$tagregexp.')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\1\])?';

return preg_replace_callback('/'.$pattern.'/s', 'scic_do_shortcode_tag', $comment);
*/
}

/*
function scic_do_shortcode_tag($m) {
	global $scic_shortcode;

	$tag = $m[1];
	$attr = shortcode_parse_atts($m[2]);

	if ( isset($m[4]) ) {
		// enclosing tag - extra parameter
		return call_user_func($scic_shortcode[$tag], $attr, $m[4], $tag);
	} else {
		// self-closing tag
    //return call_user_func($scic_shortcode[$tag], $attr, NULL, $tag);
    //finding ways to return the original strings
    return '[code]';
	}
}

function scic_shortcode_process($atts, $content='') {

extract(shortcode_atts(array(
//none at this moment
), $atts));

return '<div class="cic_codes_div">'.htmlspecialchars($content).'</div>';
}
*/

function scic_cform_addon() {
global $zv_scic_plugin_formmsg,$scic_options;
if (!empty($scic_options)) { echo stripslashes($scic_options['msg']); } else { echo $zv_scic_plugin_formmsg; }
}

function scic_wphead() {
global $scic_options;
echo '<!-- start source codes in comments plugin : wp_head -->
<style type="text/css" />
'.stripslashes($scic_options['css']).'  
</style>
<!-- end source codes in comments plugin : wp_head -->
';
}

add_action('init', 'scic_init');
function scic_init() {
global $scic_options;
//add_filter('preprocess_comment', 'scic_searchandreplace', '', 1);

add_filter('comment_text', 'scic_searchandreplace');

if ($scic_options['showmsg'] == '1') { add_action('comment_form', 'scic_cform_addon'); }

if ($scic_options['css'] != '' && $scic_options['showcss']=='1') {
add_action('wp_head', 'scic_wphead');
}

}


/* admin menu */
add_action('admin_menu', 'scic_menu');

function scic_menu() {
global $zv_scic_plugin_name;
$pluginpage = add_options_page($zv_scic_plugin_name, 'Codes in Comments', 8, __FILE__, 'scic_options');
add_action('admin_head-'.$pluginpage, 'scic_admin_head');
}

function scic_options() {
global $zv_scic_plugin_name,$zv_scic_plugin_dir,$zv_scic_plugin_ver,$zv_scic_plugin_url,$zv_scic_plugin_formmsg;

if (isset($_POST['scic_form_saveoptions'])) {

  $scic_options = array();
  if ($_POST['scic_form_showmsg']=='1') { $scic_options['showmsg'] = '1'; }
  $scic_options['msg'] = $_POST['scic_form_msg'];
  $scic_options['showcss'] = $_POST['scic_form_showcss'];
  $scic_options['css'] = $_POST['scic_form_css'];
    
  update_option('scic_options', $scic_options);
  echo '<div class="updated" style="padding:5px;"><b>Plugin Options has been updated.</b></div>';
}

if (isset($_POST['scic_form_resetoptions'])) {
if (get_option('scic_options')) {
delete_option('scic_options');
}
echo '<div class="updated" style="padding:5px;"><b>Plugin options has been resetted to default.</b></div>';
}

?>
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo wp_specialchars($zv_scic_plugin_name); ?></h2>
</div>

<div style="padding:10px;border:1px solid #dddddd;background-color:#fff;-moz-border-radius:10px;margin-top:20px;margin-bottom:20px;">
<?php
echo 'Version '.$zv_scic_plugin_ver.' | <a href="'.$zv_scic_plugin_url.'">Plugin Change Log & Info</a> | <a href="http://zenverse.net/support/">Donate via PayPal</a> | <a href="http://zenverse.net/">by ZENVERSE</a>';
?>
</div>

<?php
$scic_options = get_option('scic_options');
?>

<div class="scic_oneblock">
<span class="scic_maintitle">Plugin Options</span><br /><br />

<form method="post" action="">
<div class="scic_oneoptionblock">
<label><strong>Show plugin message after comment form?</strong> <input type="checkbox" name="scic_form_showmsg" value="1" <?php if (!empty($scic_options)) { if ($scic_options['showmsg']=='1') { echo 'checked="checked"'; } } ?> /></label>
<br /><small>Let your visitors know that they can write source codes in [code][/code] tags by showing a message after comment form.</small>
</div>

<div class="scic_oneoptionblock">
<strong>Plugin Message</strong>
<input type="text" name="scic_form_msg" value="<?php if (!empty($scic_options)) { echo str_replace('"','&quot;',stripslashes($scic_options['msg'])); } else { echo $zv_scic_plugin_formmsg; } ?>" style="padding:3px;border:1px solid #cccccc" size="70" />
<br /><small>This message will be shown after comment form, if enabled.</small>
</div>

<div class="scic_oneoptionblock">
<label><strong>Enable custom CSS?</strong> <input type="checkbox" name="scic_form_showcss" value="1" <?php if (!empty($scic_options)) { if ($scic_options['showcss']=='1') { echo 'checked="checked"'; } } ?> /></label>
<br /><small>Not all your themes has the same colour scheme, if you want to style the DIV yourself in your theme's style.css, untick this.</small>
</div>

<div class="scic_oneoptionblock">
<strong>Custom CSS for codes DIV</strong><br />
<textarea style="width:90%" rows="5" name="scic_form_css" style="padding:3px;border:1px solid #cccccc"><?php if (!empty($scic_options)) { if ($scic_options['css']!='') { echo stripslashes($scic_options['css']); } } else { echo ".cic_codes_div {\n\n}"; } ?></textarea>
<br /><small>The CSS above will be loaded for every theme, if enabled.</small>
</div>


<input type="submit" name="scic_form_saveoptions" value="Save Options" class="button-primary" /> 
<input type="submit" name="scic_form_resetoptions" onclick="return confirm('Are you sure you want to reset the options?\nWarning: this action cannot be undo.')" value="Reset" class="button" />
</form>
</div>

<div class="scic_oneblock">
<span class="scic_maintitle">Plugin Support & Extra</span><br /><br />

<b>General Message</b><br />
If you have problem when using this plugin, please report it to me at <a href="http://forums.zenverse.net/viewforum.php?f=9" target="_blank">plugin support forum</a>, I will then look into the matter as soon as possible.<br /><br />

<b>Author's Message</b><br />
Please don't forget to <a href="http://zenverse.net/support/">donate via PayPal</a> if you found this plugin useful to ensure continued development.
</div>


<br />
<hr style="border:0px;height:1px;font-size:1px;margin-bottom:5px;background:#dddddd;color:#dddddd" />
<small style="color:#999999">
My other works : <a target="_blank" href="http://zenverse.net/category/wordpress-plugins/">Wordpress Plugins</a> 
&nbsp; | &nbsp; <a target="_blank" href="http://zenverse.net/category/wpthemes/free-wp-themes/">Free Wordpress Themes</a> 
&nbsp; | &nbsp; <a target="_blank" href="http://themes.zenverse.net/">Premium Wordpress Themes</a>
&nbsp; | &nbsp; Thank you for using my plugin.
</small>


<?php
}


function scic_admin_head() {
global $zv_scic_plugin_dir;

echo '<!-- start source codes in comments admin_head -->
  <link rel="stylesheet" href="'.$zv_scic_plugin_dir.'/style.css" type="text/css" />
  ';
  
echo '<!-- end source codes in comments admin_head -->
';
}

?>