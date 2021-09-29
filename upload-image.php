<?php
/**
 * Script for uploading Image and set to post type
 */

// complete image url
$attachment_url = $product1['ImageUrl'];

$image_url = $attachment_url;
$upload_dir = wp_upload_dir();

$image_data = file_get_contents( $image_url );

$filename = basename( $image_url );

if ( wp_mkdir_p( $upload_dir['path'] ) ) {
  $file = $upload_dir['path'] . '/' . $filename;
}
else {
  $file = $upload_dir['basedir'] . '/' . $filename;
}

file_put_contents( $file, $image_data );

$wp_filetype = wp_check_filetype( $filename, null );

$attachment = array(
  'post_mime_type' => $wp_filetype['type'],
  'post_title' => sanitize_file_name( $filename ),
  'post_content' => '',
  'post_status' => 'inherit'
);

$attach_id = wp_insert_attachment( $attachment, $file );
require_once( ABSPATH . 'wp-admin/includes/image.php' );
$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
wp_update_attachment_metadata( $attach_id, $attach_data );
$attachmentURL = wp_get_attachment_url($attach_id);

$args = array(
    'post_author' => 1,
    'post_content' => '',
    'post_status' => "publish", // (Draft | Pending | Publish)
    'post_title' => $product_name,
    'post_parent' => '',
    'post_type' => "product",
);

//Create a simple WooCommerce product
$post_id = wp_insert_post($args);

wp_set_object_terms($post_id, 'simple', 'product_type');

update_post_meta($post_id, '_price', $product_price);
update_post_meta($post_id, '_regular_price', $product_price);
// update_post_meta( $post_id,  '_product_image_gallery', $attachment);
update_post_meta($post_id, '_product_image_gallery',  $attachmentURL);
/**
 * Set top post
 */
set_post_thumbnail($post_id , $attach_id );



