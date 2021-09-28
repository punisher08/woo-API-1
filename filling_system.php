<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td,
th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
<form action="" method="post" enctype="multipart/form-data">
    <table style="margin:2rem; width:90%;">
        <tr>
            <th>
                <label for="storage">Storage</label>
                <select name="storage" id="">
                    <option value="desktop">Desktop</option>
                    <option value="dropbox">Dropbox</option>
                </select>
            </th>
            <th>
                <label for="folder1">Folder</label>
                <select name="folder1" id="">
                    <option value="accounting">Accounting</option>
                    <option value="migration">Migration</option>
                </select>
            </th>
            <th>
                <label for="tier">Tier 1</label>
                <select name="tier" id="">
                    <option value="current">Current</option>
                    <option value="new">New</option>
                    <option value="old">Old</option>
                </select>
            </th>
            <th>
                <label for="associate">Associate</label>
                <select name="associate" id="">
                    <option value="preston">Preston</option>
                    <option value="susan">Susan</option>
                </select>
            </th>
            <th>
                <label for="client">Client Name</label>
                <select name="client" id="">
                    <option value="jacob">Jacob</option>
                    <option value="parkar">Parkar</option>
                    <option value="uniwill">Uniwill</option>
                </select>
            </th>
            <th>
                <label for="folder2">Folder</label>
                <select name="folder2" id="">
                    <option value="account_summary">Accounting Summary</option>
                    <option value="subclass_188">Subclass 188</option>
                </select>
            </th>
            <th>Doc No.</th>
            <th>File Name</th>
            <th>
                <label for="upload_file">Upload</label>
                <input type="file" name="upload_file" id="upload_file" multiple="false">
                <?php wp_nonce_field('upload_file', 'upload_file_nonce'); ?>

            </th>
            <th>
                <button type="submit" name="filemanagement">Save</button>
            </th>
        </tr>

</form>
<?php
global $wpdb;

$meta_box_keys = [
    'filemanagement_storage',
    'filemanagement_folder1',
    'filemanagement_tier',
    'filemanagement_associate',
    'filemanagement_client',
    'filemanagement_folder2',
    'filemanagement_filename'
];

$sql = $wpdb->get_col("SELECT ID FROM `wp_posts` WHERE `post_type` = 'filemanagement'");
foreach ($sql as $key => $post_id) {
    $filemanagement_storage = get_post_meta($post_id, 'filemanagement_storage');
    $filemanagement_folder1 = get_post_meta($post_id, 'filemanagement_folder1');
    $filemanagement_tier = get_post_meta($post_id, 'filemanagement_tier');
    $filemanagement_associate = get_post_meta($post_id, 'filemanagement_associate');
    $filemanagement_client = get_post_meta($post_id, 'filemanagement_client');
    $filemanagement_folder2 = get_post_meta($post_id, 'filemanagement_folder2');
    $filemanagement_filename = get_post_meta($post_id, 'filemanagement_filename');
    $attachment_id_to_post_db = get_post_meta($post_id, 'attachment_id_to_post');
    $attachment_download = get_post($attachment_id_to_post_db);


    if (!empty($filemanagement_storage[0]) && !empty($filemanagement_folder1[0]) && !empty($filemanagement_associate[0])) {

?>
<tr>
    <td><?= $filemanagement_storage[0]; ?></td>
    <td><?= $filemanagement_folder1[0]; ?></td>
    <td><?= $filemanagement_tier[0]; ?></td>
    <td><?= $filemanagement_associate[0]; ?></td>
    <td><?= $filemanagement_client[0]; ?></td>
    <td><?= $filemanagement_folder2[0]; ?></td>
    <td><?= $post_id; ?></td>
    <td><?= $filemanagement_filename[0]; ?></td>
    <td><?php

                $get_attachment_id = $attachment_id_to_post_db[0];
                $attachment_object = get_post($get_attachment_id);
                $download =  $attachment_object->guid;
                echo '<a  href="' . $download . '">Download</a>';
                ?>
    </td>
    <td></td>
</tr>
<?php
    }
}
// echo '<pre>';
// print_r(ABSPATH . 'Dropbox');
// echo '<pre>';


if (isset($_POST['filemanagement']) && !empty($_FILES['upload_file'])) {
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');

    $attachment_url = $_FILES['upload_file'];
    $attachment_name = $attachment_url['name'];
    $attachment_id = upload_user_file($attachment_url);
    // save_file_to_dropbox();


    // require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');

    $storage = $_POST['storage'];
    $folder = $_POST['folder1'];
    $tier = $_POST['tier'];
    $associate = $_POST['associate'];
    $client = $_POST['client'];
    $folder2 = $_POST['folder2'];
    // $filename = $_FILES['upload_file']['name'];






    //produce exact filename
    $Stringofstorage = substr($storage, 0, 1);
    $Stringoffolder = substr($folder, 0, 1);
    $Stringoftier = substr($tier, 0, 1);
    $Stringofassociate = substr($associate, 0, 1);
    $Stringofclient = substr($client, 0, 3);
    $Stringoffolder2 = substr($folder2, 0, 3);
    $final_filename = $Stringofstorage . $Stringoffolder .  $Stringoftier . $Stringofassociate . $Stringofclient . $Stringoffolder2;    // File name String length
    //EO filename


    //CREATE POST WITH RETURN VALUE OF ID
    $my_post = array(
        'post_title'    => 'test2',
        'post_type' => 'filemanagement',
        'post_status'   => 'publish',
    );
    $insert_file_id = wp_insert_post($my_post);
    //EO CREATE POST

    //UPDATE OR CREATE POST META ON DB
    if ($insert_file_id) {
        // UPDATE ALL REQUUIRED FIELDS
        update_post_meta($insert_file_id, 'filemanagement_storage', $storage);
        update_post_meta($insert_file_id, 'filemanagement_folder1', $folder);
        update_post_meta($insert_file_id, 'filemanagement_tier', $tier);
        update_post_meta($insert_file_id, 'filemanagement_associate', $associate);
        update_post_meta($insert_file_id, 'filemanagement_client', $client);
        update_post_meta($insert_file_id, 'filemanagement_folder2', $folder2);
        update_post_meta($insert_file_id, 'filemanagement_filename', strtoupper($final_filename) . '-' . $insert_file_id);
        // attachment id 
        update_post_meta(
            $insert_file_id,
            'attachment_id_to_post',
            $attachment_id
        );
    }
    //EO UPDATE OR CREATE POST META ON DB
}
//GET AND DISPLAY FILEMANAGEMENT POSTS
$filemanagement_storage = get_post_meta(29, 'filemanagement_storage');
$filemanagement_folder = get_post_meta(29, 'filemanagement_folder1');
$filemanagement_tier = get_post_meta(29, 'filemanagement_tier');
$filemanagement_associate = get_post_meta(29, 'filemanagement_associate');
$filemanagement_client = get_post_meta(29, 'filemanagement_client');
$filemanagement_folder2 = get_post_meta(29, 'filemanagement_folder2');
$filename_db = get_post_meta(29, 'filemanagement_filename');
$filemanagement_upload_file = get_post_meta(29, 'filemanagement_upload_file');
// EO GET POST META 

?>

</table>
