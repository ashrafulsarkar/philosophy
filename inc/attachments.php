<?php
define( 'ATTACHMENTS_SETTINGS_SCREEN', false );
add_filter( 'attachments_default_instance', '__return_false' );
function philosophy_attachments( $attachments ){
    $post_id = null;
    if(isset($_REQUEST['post']) || isset($_REQUEST['post_ID'])){
        $post_id = empty($_REQUEST['post_ID']) ? $_REQUEST['post'] : $_REQUEST['post_ID'];
    }
    if(!$post_id || get_post_format($post_id) != "gallery"){
        return;
    }

  $fields         = array(
    array(
      'name'      => 'title',                         // unique field name
      'type'      => 'text',                          // registered field type
      'label'     => __( 'Title', 'attachments' ),    // label to display
      'default'   => 'title',                         // default value upon selection
    )
  );

  $args = array(

    'label'         => 'Gallery',
    'post_type'     => array( 'post'),
    'filetype'      => array('image'),
    'note'          => 'Attach files here!',
    'button_text'   => __( 'Attach Images', 'attachments' ),
    'fields'        => $fields,
  );

  $attachments->register( 'philosophy_gallery', $args ); // unique instance name
}

add_action( 'attachments_register', 'philosophy_attachments' );