<?php
function upload_user_file( $file = array() ) {    
    require_once( ABSPATH . 'wp-admin/includes/admin.php' );
	//

	//
    $file_return = wp_handle_upload( $file, array('test_form' => false ) );
    if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
        return false;
    } else {
        $filename = $file_return['file'];
		$post_title = preg_replace( '/\.[^.]+$/', '', basename( $filename ) );
        $attachment = array(
            'post_mime_type' => $file_return['type'],
            'post_title' => $post_title,
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $file_return['url']
        );

        $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
        require_once(ABSPATH . 'wp-admin/includes/image.php');
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
		// 
		$uploads_dir = wp_upload_dir().$attachment_data['file'];
		echo '<pre>';
		print_r($uploads_dir);
		echo '</pre>';
		// 
        wp_update_attachment_metadata( $attachment_id, $attachment_data );
        if( 0 < intval( $attachment_id ) ) {
          return $attachment_id;
        }
    }
    return false;
}

/**
 * CALL FUNCTION TO SAVE FILES
 * @use   <form method="post" enctype="multipart/form-data" action="">
 */
if(isset($_POST['submit-file']) && !empty($_FILES['file_upload'])){
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );

    $attachment_url = $_FILES['file_upload'];
    $attachment_id = upload_user_file( $attachment_url );   

}
