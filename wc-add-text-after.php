<?php

/**
 * Plugin Name: Add text after "Add To Cart" button
 * Plugin URI: http://www.rayflores.com/plugins/wc-text-after-atc-button 
 * Version: 1.0
 * Author: Ray Flores
 * Author URI: http://www.rayflores.com 
 * Description: Adds desired text immediately after Add To Cart button
 * Requires at least: 4.0
 * Tested up to: 4.1
 */
 
add_action('admin_menu', 'create_options_page');
function create_options_page() {  
	add_menu_page('Add Text', 'Add Text', 'administrator', 'wc_add_text_after', 'wc_add_text_after_options_page');
}
function wc_add_text_after_options_page() { ?>  
<div id="theme-options-wrap" class="widefat">    
	<div class="icon32" id="icon-tools"> <br /> 
	</div>    
	<h2>Add Text That Will Appear After The Add To Cart Button</h2>    
		<p>Enter text that you want to display after the add-to-cart button on products page, in the box below:</p>    
			<form method="post" action="options.php" enctype="multipart/form-data"> 
			<?php settings_fields('plugin_options'); ?>  
			<?php do_settings_sections(__FILE__); ?>  
				<p class="submit">    
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />  
				</p>
			</form> 
</div>
<?php }
add_action('admin_init', 'register_and_build_fields');
function register_and_build_fields() {   
	register_setting('plugin_options', 'plugin_options', 'validate_setting');
	//main section
	add_settings_section('main_section', 'Main Settings', 'section_cb', __FILE__);
	// fields in main section
	add_settings_field('add_text_area_1', 'Text To Add After Add To Cart Button:', 'add_text_area_1_setting', __FILE__, 'main_section');
	add_settings_field('add_text_area_2', 'Alternative Text To Add After Add To Cart Button:', 'add_text_area_2_setting', __FILE__, 'main_section');

}

function validate_setting($plugin_options) { 
	$keys = array_keys($_FILES); 
	$i = 0; 
		foreach ( $_FILES as $image ) {   
			// if a files was upload   
			if ($image['size']) {     
				// if it is an image     
				if ( preg_match('/(jpg|jpeg|png|gif)$/', $image['type']) ) {       
					$override = array('test_form' => false);       
					// save the file, and store an array, containing its location in $file       
					$file = wp_handle_upload( $image, $override );       
					$plugin_options[$keys[$i]] = $file['url'];     
					} else {       
					// Not an image.        
					$options = get_option('plugin_options');       
					$plugin_options[$keys[$i]] = $options[$logo];       
					// Die and let the user know that they made a mistake.       
						wp_die('No image was uploaded.');     
					}   
			}   // Else, the user didn't upload a file.   
			// Retain the image that's already on file.   
			else {     
				$options = get_option('plugin_options');     
				$plugin_options[$keys[$i]] = $options[$keys[$i]];   
			}   $i++; 
		} return $plugin_options;
}
function section_cb() {
}

// zip code box
function add_text_area_1_setting() {  
	$options = get_option('plugin_options');  
	echo "<textarea name='plugin_options[add_text_area_1]' value='{$options['add_text_area_1']}' cols='50' rows='15'>" . $options['add_text_area_1'] . "</textarea>";
}
function add_text_area_2_setting() {  
	$options = get_option('plugin_options');  
	echo "<textarea name='plugin_options[add_text_area_2]' value='{$options['add_text_area_2']}' cols='50' rows='15'>" . $options['add_text_area_2'] . "</textarea>";
}
 
 function wc_add_text_after_atc_button(){
 global $woocommerce, $post,$product;
	$options = get_option('plugin_options');  
	$text_to_add_1 = $options['add_text_area_1'];
	$text_to_add_2 = $options['add_text_area_2'];
	if (get_post_meta($product->id,'add_text_checkbox', true)) {
	echo '<div class="wcata">' . $text_to_add_2 . '</div>';
 } else {
	echo '<div class="wcata">' . $text_to_add_1 . '</div>';
 }
 }
 
 add_action( 'woocommerce_single_product_summary', 'wc_add_text_after_atc_button', 35 );
 
 
// Display Fields
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );
// Save Fields
add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );  
 function woo_add_custom_general_fields() {
 global $woocommerce, $post;
echo '<div class="options_group">';
	// Checkbox
	woocommerce_wp_checkbox(
		array(
		'id' => 'add_text_checkbox',
		'wrapper_class' => '',
		'label' => __('7-10 Days Shipping?', 'woocommerce' ),
		'description' => __( 'Check me!', 'woocommerce' )
		)
	); 
echo '</div>';
} 
function woo_add_custom_general_fields_save( $post_id ){
// Checkbox
$woocommerce_checkbox = isset( $_POST['_checkbox'] ) ? 'yes' : 'no';
update_post_meta( $post_id, 'add_text_checkbox', $woocommerce_checkbox );
}
