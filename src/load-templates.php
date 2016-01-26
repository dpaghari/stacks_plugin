<?php

function stacks_load_templates( $original_template ) {
  if( get_query_var( 'post_type' ) !== 'landing_page' ) {
    return;
  }
  if( is_archive() || is_search() ) {
    if( file_exists( get_stylesheet_directory() . '/archive-landing_page.php') ) {
      return get_stylesheet_directory() . '/archive-landing_page.php';
    }
    else {
      return plugin_dir_url(__FILE__) . '/templates/archive-landing_page.php';
    }
  }
  else {
    if( file_exists( get_stylesheet_directory() . '/single-landing_page.php') ) {
        return get_stylesheet_directory() . '/single-landing_page.php';
    }
    else {
        return STACKS_DIR . '/templates/single-landing_page.php';
    }
  }

  return $original_template;

}

add_action( 'template_include', 'stacks_load_templates' );



 ?>
