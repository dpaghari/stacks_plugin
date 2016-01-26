<?php

// // Save post meta
// function trstacks_meta_save ( $post_id ) {
//     // Checks save status
//     $is_autosave = wp_is_post_autosave( $post_id );
//     $is_revision = wp_is_post_revision( $post_id );
//     $is_valid_nonce = ( isset( $_POST[ 'stacks_bands_nonce' ] ) && wp_verify_nonce( $_POST[ 'stacks_bands_nonce' ],  basename( __FILE__ ) ) ) ? 'true' : 'false';
//
//     // Exits script depending on save status
//     if ( $is_autosave || $is_revision || !$is_valid_nonce )
//       return;
//
//     if ( isset( $_POST[ 'band_id0' ] ) ) {
//       update_post_meta( $post_id, 'band_id0', $_POST[ 'band_id0' ] );
//     }
//
//     $numBandsSaved = isset( $_POST[ 'stacksCount' ] ) ? $_POST[ 'stacksCount'] : 0;
//     //exit($numBandsSaved);
//     //exit(json_encode(array("data" => $numBandsSaved )));
// }
//add_action( 'save_post', 'trstacks_meta_save' );

?>
