<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.cedcommerce.com
 * @since      1.0.0
 *
 * @package    Woo_product_importer
 * @subpackage Woo_product_importer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_product_importer
 * @subpackage Woo_product_importer/admin
 * @author     cedcommerce <cedcommerce@cedcoss.com>
 */
class Woo_product_importer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_product_importer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_product_importer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo_product_importer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_product_importer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_product_importer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'ced_java_script', plugin_dir_url( __FILE__ ) . 'js/woo_product_importer-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'ced_java_script', 'ced_ajax_object',
        array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        )
    );

	}
	

	/**
	 * Ced_create_import_file_menu_page
	 * Discription : create a admin menu with the name of 'File Importer'
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	public function ced_create_import_file_menu_page() {
		add_menu_page( 'File_importer', 'File Importer', 'manage_options', 'file_importer', array($this, 'ced_content_file_importer'),'dashicons-admin-generic', 5 );
	}
	
	

	/**
	 * Ced_content_file_importer
	 * Description : create the whole content which one is the part of 'File Importer' page
	 * 
	 * @since 1.0.0 
	 * @return void
	 */
	public function ced_content_file_importer() {
		require_once WOO_PRODUCT_IMPORTER_PLUGIN_PATH . 'admin/partials/woo_product_importer-admin-display.php';
	}

	
	/**
	 * Ced_json_file_show_data_content
	 * Descripiton : function for show the imported .json file in  'WP-list' during ajax calling 
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function ced_json_file_show_data_content() {
		require WOO_PRODUCT_IMPORTER_PLUGIN_PATH . 'admin/class-woo_product_importer-list_table.php';
		if(isset($_POST['file_name'])) {
			$file_name = $_POST['file_name'];
			$upload = wp_upload_dir();
			$upload_dir = $upload['basedir'].'/Woo_product_importer/';
			$json_file_content = json_decode(file_get_contents($upload_dir.'Woo_product_importer'.$file_name),1);
			$wp_list_class_object = new My_List_Table();
			$wp_list_class_object->items = $json_file_content;
			// usort( $wp_list_class_object->item['item'], array( &$wp_list_class_object, 'usort_reorder' ) );
			$wp_list_class_object->prepare_items();
			print_r($wp_list_class_object->display());	
		}
		wp_die();
	}
	
		
	/**
	 * Ced_product_importer_manuall
	 * Description : create for save the specific (IMPORT BUTTON) clicked data into DB (wp_post table ) 
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	public function ced_product_importer_manuall() {
		if(isset($_POST['sku_id']) && isset($_POST['item']) && isset($_POST['file_name'])) {
			$sku_id = $_POST['sku_id'];
			$item_id = $_POST['item'];
			$file_name = $_POST['file_name'];
			$upload = wp_upload_dir();
			$upload_dir = $upload['basedir'].'/Woo_product_importer/';
			$json_file_content = json_decode(file_get_contents($upload_dir.'Woo_product_importer'.$file_name),1);
			
			$this->ced_import_product_after_ajaxcll_varaiblp_and_simplep($json_file_content, $item_id);
		}
		
		wp_die();
	}


		
	/**
	 * Ced_import_product_after_ajaxcll_varaiblp_and_simplep
	 * Description : create for insert above product data 
	 * 
	 * @since 1.0.0
	 * @param  mixed $json_file_content
	 * @param  mixed $item_id
	 * @return void
	 */
	public function ced_import_product_after_ajaxcll_varaiblp_and_simplep($json_file_content, $item_id) {
		foreach($json_file_content as $file_key => $file_value) {
			if ($item_id == $file_value['item']['item_id']) {
				$title = $file_value['item']['name'];
				$content = $file_value['item']['description'];
				$variation_type = $file_value['item']['has_variation'];
				$prod_type;

				if (1 == $variation_type) {
// --------------------------------------------------------------------------------------------
//								  if PRODUCT is variable product
//---------------------------------------------------------------------------------------------
					$variable_pro_post_id = wp_insert_post(array (
						'post_type' => 'product',
						'post_title' => $title,
						'post_content' => $content,
						'post_status' => 'publish',
						'comment_status' => 'closed',   // if you prefer
						'ping_status' => 'closed', // if you prefer
					 ));
					if ($variable_pro_post_id) {
						echo "1";
					}

					wp_set_object_terms($variable_pro_post_id, 'variable', 'product_type');
//-------------------------------------------------- Called Function (For variable Product) ------------------------------------------------------------------------------------------------------------
					$this->ced_product_importer_update_post_meta($variable_pro_post_id, $file_value['item']);
					$this->ced_product_importer_attributes_for_variable($variable_pro_post_id, $file_value['tier_variation']);
					$this->ced_product_importer_save_image($variable_pro_post_id, $file_value['item']);
					$this->ced_product_importer_variation_product_insertion($variable_pro_post_id, $file_value['item']['variations'], $file_value['tier_variation']);
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
				} else {
//-------------------------------------------------------------------------------------------------------------------------------------------
// 									If Product is simple product
//-------------------------------------------------------------------------------------------------------------------------------------------

					$prod_type = 'product';
					$post_id = wp_insert_post(array (
						'post_type' => $prod_type,
						'post_title' => $title,
						'post_content' => $content,
						'post_status' => 'publish',
						'comment_status' => 'closed',   // if you prefer
						'ping_status' => 'closed',      // if you prefer
					));
					if ($post_id) {
						echo "1";
					}
//----------------------------------------------------------	Called function (For simple Product) ----------------------------------------------------------------------------------------
					wp_set_object_terms($post_id, 'simple', 'product_type');
					// calling of function 'ced_product_importer_update_post_meta()' for store the meta_data about this specific post(product)
					$this->ced_product_importer_update_post_meta($post_id, $file_value['item']);
					// calling of function 'ced_product_importer_save_image()' for store the images(featured_images/gallery) about this specifinc post(product)
					$this->ced_product_importer_save_image($post_id, $file_value['item']);
					// calling of function 'ced_product_import_attributes()' for store the attributes
					$this->ced_product_import_attributes($post_id, $file_value['item']);
// --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
				}	
			}
		}
	}

	
	/**
	 * Ced_product_importer_update_post_meta
	 * Description : create for save the specfic product realted meta data into DB(wp_postmeta)
	 * 
	 * @since 1.0.0
	 * @param  mixed $post_id
	 * @param  mixed $data
	 * @return void
	 */
	public function ced_product_importer_update_post_meta($post_id, $data) {

		update_post_meta($post_id, '_price', isset($data['price'])?sanitize_text_field($data['price']):'' );
		update_post_meta($post_id, '_regular_price', isset($data['price'])?sanitize_text_field($data['price']):'' );
		update_post_meta($post_id, '_sale_price', isset($data['sale_price'])?sanitize_text_field($data['sale_price']):'' );
		update_post_meta($post_id, '_sku', isset($data['item_sku'])?sanitize_text_field($data['item_sku']):'' );
		update_post_meta($post_id, '_stock', isset($data['stock'])?sanitize_text_field($data['stock']):'' );
		update_post_meta($post_id, '_stock_status', isset($data['status'])?sanitize_text_field($data['status']):'' );
		update_post_meta($post_id, '_downloadable','no' );
		update_post_meta($post_id, '_virtual','no' );
		update_post_meta($post_id, '_sold_individually','no' );
		update_post_meta($post_id, '_backorders','no' );
	}
	
	
	/**
	 * Ced_product_importer_save_image
	 * Description : create for save the images related to specific products
	 * 
	 * @since 1.0.0
	 * @param  mixed $post_id
	 * @param  mixed $data
	 * @return void
	 */
	public function ced_product_importer_save_image($post_id, $data) {
		// Add Featured Image to Post
		foreach($data['images'] as $key_image => $val_image) {
			$image_url        = $val_image; // Define the image URL here
			$image_name       = basename($image_url );
			$upload_dir       = wp_upload_dir(); // Set upload folder
			$image_data       = file_get_contents($image_url); // Get image data
			$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
			$filename         = basename( $unique_file_name ); // Create image file name

			// Check folder permission and define file location
			if( wp_mkdir_p( $upload_dir['path'] ) ) {
				$file = $upload_dir['path'] . '/' . $filename;
			} else {
				$file = $upload_dir['basedir'] . '/' . $filename;
			}

			// Create the image  file on the server
			file_put_contents( $file, $image_data );

			// Set attachment data
			$attachment = array(
				'post_mime_type' => 'image/jpeg',
				'post_title'     => sanitize_file_name( $filename ),
				'post_content'   => '',
				'post_status'    => 'publish'
			);

			// Create the attachment
			$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

			// Include image.php
			require_once(ABSPATH . 'wp-admin/includes/image.php');

			// Define attachment metadata
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

			// Assign metadata to attachment
			wp_update_attachment_metadata( $attach_id, $attach_data );

			//	 And finally assign featured image to post
			set_post_thumbnail( $post_id, $attach_id );
		}
	}

	
	/**
	 * Ced_product_import_attributes
	 * Description : create attributes for specicfic product
	 * 
	 * @since 1.0.0
	 * @param  mixed $post_id
	 * @param  mixed $data
	 * @return void
	 */
	public function ced_product_import_attributes($post_id, $data) {
		foreach($data['attributes'] as $attr_key) {
			$attributedata[$attr_key['attribute_name']] = Array(
				
					  'name'=>$attr_key['attribute_name'], 
					  'value'=>$attr_key['attribute_value'],
					  'is_visible' => $attr_key['is_mandatory'],
					  'is_taxonomy' => '0',
					  'is_variation' => '0'
				
		   );
		   
			   update_post_meta( $post_id,'_product_attributes',$attributedata );
		}

	}

	
	
	/**
	 * Ced_product_importer_attributes_for_variable
	 * Description : create attributes for specific products (Variable Product Attributes)
	 * 
	 * @since 1.0.0
	 * @param  mixed $post_id
	 * @param  mixed $data
	 * @return void
	 */
	public function ced_product_importer_attributes_for_variable($post_id, $data) {

		foreach($data  as $attr_key) {
			$name = $attr_key['name'];
			$value = $attr_key['options'];
			$imploded_value = implode("|",$value);
			$attributedata[$name] = Array(
					  'name'=>$name, 
					  'value'=>$imploded_value,
					  'is_variation' => '1',
					  'is_visible' => '1',
					  'is_taxonomy' => '0'	
		   );
			  
		}
		update_post_meta( $post_id,'_product_attributes',$attributedata );

	}
	
	/**
	 * Ced_product_importer_variation_product_insertion
	 * Description : create for insert the varition product 
	 *
	 * @param  mixed $post_id
	 * @param  mixed $data
	 * @param  mixed $tier_variation_data
	 * @return void
	 */
	public function ced_product_importer_variation_product_insertion($post_id, $data, $tier_variation_data) {

		foreach ($data as $vari_key => $vari_val) {
			$title = isset($vari_val['name'])?sanitize_text_field($vari_val['name']):'';
			$variable_variation_post_id = wp_insert_post(array (
				'post_type' => 'product_variation',
				'post_title' => $title,
				'post_content' => '',
				'post_status' => 'publish',
				'comment_status' => 'closed',   // if you prefer
				'ping_status' => 'closed', // if you prefer
				'post_parent' => $post_id
			 ));	
			$this->ced_product_importer_update_postmeta_for_variable($variable_variation_post_id, $vari_val, $tier_variation_data);		
		}

	}
	
	/**
	 * ced_product_importer_update_postmeta_for_variable
	 * Description : Create for insert the meta data about variation product in (POST-META) table 
	 * 
	 * @since 1.0.0
	 * @param  mixed $post_id
	 * @param  mixed $key_varible
	 * @param  mixed $tier_variation_data
	 * @return void
	 */
	public function ced_product_importer_update_postmeta_for_variable($post_id, $key_varible, $tier_variation_data) {
		$price = "";
			$variation_attr_name =  isset($key_varible['name'])?sanitize_text_field($key_varible['name']):'';
			update_post_meta($post_id, '_price', isset($key_varible['price'])?sanitize_text_field($key_varible['price']):'' );
			update_post_meta($post_id, '_regular_price', isset($key_varible['price'])?sanitize_text_field($key_varible['price']):'' );
			update_post_meta($post_id, '_sale_price', isset($key_varible['sale_price'])?sanitize_text_field($key_varible['sale_price']):'');
			update_post_meta($post_id, '_sku', isset($key_varible['variation_sku'])?sanitize_text_field($key_varible['variation_sku']):'' );
			update_post_meta($post_id, '_stock', isset($key_varible['stock'])?sanitize_text_field($key_varible['stock']):'' );
			update_post_meta($post_id, '_downloadable','no' );
			update_post_meta($post_id, '_virtual','no' );
			update_post_meta($post_id, '_sold_individually','no' );
			update_post_meta($post_id, '_backorders','no' );	
			$check = false;
			$attribute_name = "";
			$option_val = "";
			foreach($tier_variation_data as $key=>$value) {
				$options = ($value['options']);
				$attribute_name =($value['name']) ;
				foreach($options as $option_key => $option_val) {
					if($variation_attr_name == $option_val) {
						$option_val = $variation_attr_name;
						$check = true;
						break;
					}
				}
			}
			update_post_meta($post_id, 'attribute_' . strtolower($attribute_name), $option_val);		
	}
	
	/**
	 * Ced_product_bulk_importer_manuall
	 * Description : ctreate for insert bulk data 
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function ced_product_bulk_importer_manuall() {
		// print_r($_POST);
		$item_id_array = isset($_POST['item'])?$_POST['item']:'';
		$file_name = isset($_POST['file_name'])?$_POST['file_name']:'';
		$upload = wp_upload_dir();
		$upload_dir = $upload['basedir'].'/Woo_product_importer/';
		$json_file_content = json_decode(file_get_contents($upload_dir.'Woo_product_importer'.$file_name),1);

		foreach($item_id_array as $item_key => $item_val) {
			$this->ced_import_product_after_ajaxcll_varaiblp_and_simplep($json_file_content, $item_val);
		}
		// echo "1";
		wp_die();
	}
}

