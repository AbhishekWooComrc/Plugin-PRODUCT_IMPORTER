<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class My_List_Table extends WP_List_Table {
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'placeholder_img' => 'Image',
          'name' => 'Title',
          'sku'    => 'SKU',
          'price'      => 'Price',
          'product_type' => 'Type',
          'action' => 'Action'
        );
        return $columns;
    }

    function column_default( $item, $column_name ) {


      $id = $item['item']['item_sku'];
      global $wpdb;
      $product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $id ) );
      if ( $product_id ){
      $html="<input type='button' value = 'Already Imported' id = 'import_product' disabled>";
      } else {
      $html="<input type='button' value = 'import' id = 'import_product' class = 'import_product' data-sku='" . $item['item']['item_sku'] . "', data-id = '" . $item['item']['item_id'] . "'>";

      }


        switch( $column_name ) { 
          case 'placeholder_img':
            return '<img src = "'. $item['item']['images'][0] .'" height="100" width = "100">';
          case 'name':
            return $item['item']['name'];
          case 'sku':
            return $item['item']['item_sku'];
          case 'price':
            return $item['item']['price'];
          case 'product_type':
            if(1 == $item['item']['has_variation']) {
                return "VARIABLE";
            } else {
                return "SIMPLE";
            }
            
          case 'action':
            return $html;
          default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }
  
    
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }


    function column_cb($item) {
    return sprintf(
        '<input type="checkbox" name="book[]" value="%s" />', $item['item']['name']
    );    
    }
    function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
    }
  
}
?>