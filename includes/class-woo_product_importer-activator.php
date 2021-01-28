<?php

/**
 * Fired during plugin activation
 *
 * @link       www.cedcommerce.com
 * @since      1.0.0
 *
 * @package    Woo_product_importer
 * @subpackage Woo_product_importer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woo_product_importer
 * @subpackage Woo_product_importer/includes
 * @author     cedcommerce <cedcommerce@cedcoss.com>
 */
class Woo_product_importer_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {

	}
	public function ced_create_folder_inside_upload() {

		$upload = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_dir = $upload_dir . '/Woo_product_importer';
		if (! is_dir($upload_dir)) {
		   mkdir( $upload_dir, 0777 );
		}
	}
	 

}
