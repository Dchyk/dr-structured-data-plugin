<?php
/**
 * @package DR_Structured_Data
 * @version 1.0.0
 */

  /*
 Plugin Name: AD | DR Structured Data
 Plugin URI: http://adsnext.com
 Description: Adds an Admin Option for structured data to be added, then inserts it into the theme's HEAD element. 
 Author: DR + AdsNext / Alex D
 Version: 1.0.0
 Author URI: http://adsnext.com
 Text Domain: Wordpress
*/

add_action('wp_head', 'insert_structured_data');
add_action( 'admin_menu', 'drsd_add_admin_menu' );
add_action( 'admin_init', 'drsd_settings_init' );

// Insert the data into the <head>
function insert_structured_data() {
  $plugin_options = get_option('drsd_settings');
  $structured_data = $plugin_options['drsd_textarea_field_0'];
  $data = "<script type='application/ld+json'>". $plugin_options['drsd_textarea_field_0'] . "</script>";

  echo $data;
}

// Set up the Menu option
// From http://wpsettingsapi.jeroensormani.com/
function drsd_add_admin_menu(  ) { 
	add_menu_page( 'Structured Data', 'Structured Data', 'manage_options', 'dr_structured_data', 'drsd_options_page' );
}


function drsd_settings_init(  ) { 
	register_setting( 'pluginPage', 'drsd_settings' );

	add_settings_section(
		'drsd_pluginPage_section', 
		__( 'Insert structured data into this site\'s HEAD element here.', 'wordpress' ), 
		'drsd_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'drsd_textarea_field_0', 
		__( 'Structured Data:', 'wordpress' ), 
		'drsd_textarea_field_0_render', 
		'pluginPage', 
		'drsd_pluginPage_section' 
	);
}


function drsd_textarea_field_0_render(  ) { 
	$options = get_option( 'drsd_settings' );
	?>
	<textarea cols="40" rows="40" name='drsd_settings[drsd_textarea_field_0]'><?php echo $options['drsd_textarea_field_0']; ?></textarea>
	<?php
}


function drsd_settings_section_callback(  ) { 
	echo __( 'Only paste the JSON - it will automatically be wrapped in script tags.', 'wordpress' );
}


function drsd_options_page(  ) { 
	?>
	<form action='options.php' method='post'>

		<h2>DR | Ads Next Structured Data</h2><br/>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php
}

?>