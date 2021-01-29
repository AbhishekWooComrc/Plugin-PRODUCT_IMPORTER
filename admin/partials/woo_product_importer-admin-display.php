<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.cedcommerce.com
 * @since      1.0.0
 *
 * @package    Woo_product_importer
 * @subpackage Woo_product_importer/admin/partials
 */


if(isset($_FILES['json_file'])){
    $errors= array();
    $file_name = $_FILES['json_file']['name'];
    $file_size = $_FILES['json_file']['size'];
    $file_tmp = $_FILES['json_file']['tmp_name'];
    $file_type = $_FILES['json_file']['type'];
    $file_ext=strtolower(end(explode('.',$_FILES['json_file']['name'])));
    
    $extensions= array("json");
    
    if(in_array($file_ext,$extensions)=== false){
       $errors[]="extension not allowed, please choose a JSON file.";
    }
    
   
    
    // if($file_size > 2097152) {
    //    $errors[]='File size must be excately 2 MB';
    // }
    
    if(empty($errors)==true) {
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $data_exists = $upload_dir.'/Woo_product_importer/Woo_product_importer'.$file_name;
        if(file_exists($data_exists)) {
            $errors[]="File Already Exists";  
        } else {
        move_uploaded_file($file_tmp,$upload_dir.'/Woo_product_importer/Woo_product_importer'.$file_name);
        $data = array();
        $data = get_option('importer_json_file');
        if(!empty($data)) {
            $data[] = array("file_name" => $file_name);
        } else {
            $data[0] = array("file_name" => $file_name);
        }
        update_option('importer_json_file',$data);     
        echo '<div style="background-color:green; color:white;padding:10px;">Successfully Inserted</div>';

    }
    }else{

    }
 }

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<form method="post" action="" enctype="multipart/form-data" id="myform">
    <div style="padding:15px;background-color:#23282d;color:white;font-size:30px;text-align:center;margin:15px">
        Product Importer by CED COMMEREC
    </div>
    <div style="margin:15px;float:left;">
        <div >
            <h1>Select Json file to UPLOAD</h1>
            <input type="file" name="json_file" id="json_file">
            <input type="submit" value="Upload File" name="upload_json" id="upload_json">
        </div>
        <?php 
        if(!empty($errors)) {
            echo '<div style="background-color:red; color:white;padding:10px;">'.($errors[0]) . '</div>';
        }
        ?>
        <hr>

        <h3>The all Uploaded File are mentioned in these dropdown </h3>  <br>
        <div style="float:left;">
            <select name="json_file_name" id="json_file_name">  
                <?php $data = get_option('importer_json_file');
                foreach($data as $key => $val) {
                    foreach($val as $name_key => $name) {
                        echo '<option value="' . $name . '"> '. $name .'</option> '; 
                    }
                } 
                ?>
            </select>          
        </div>
    </div>
</form>

<div id="data">

</div>


