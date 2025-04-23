<?php
 
 function freetobefruitful_enqueue_styles() {
  wp_enqueue_style('tailwind', get_template_directory_uri() . '/style.css', [], filemtime(get_template_directory() . '/style.css'));
}
add_action('wp_enqueue_scripts', 'freetobefruitful_enqueue_styles');



function freetobefruitful_enqueue_fonts() {
  wp_enqueue_style(
      'google-fonts',
      'https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&display=swap',
      false
  );
}
add_action('wp_enqueue_scripts', 'freetobefruitful_enqueue_fonts');


 
function freetobefruitful_register_menus() {
  register_nav_menus([
      'header' => 'Header Menu',
  ]);
}
add_action('after_setup_theme', 'freetobefruitful_register_menus');

require_once get_template_directory() . '/class-custom-walker.php';

// add_filter('nav_menu_css_class', function ($classes, $item) {
//   $new_classes = [];

//   if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
//       $new_classes[] = 'nav-selected nav-path-selected';
//   }

//   return $new_classes;
// }, 10, 2);


// add_filter('nav_menu_link_attributes', function ($atts, $item, $args, $depth) {
//   echo '<!-- CLASSES: ' . implode(', ', $item->classes) . ' -->';
//   return $atts;
// }, 10, 4);



function freetobefruitful_widgets_init() {
  register_sidebar([
    'name' => 'Main Sidebar',
    'id' => 'main-sidebar',
    'before_widget' => '<div class="widget">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  ]);
}
add_action('widgets_init', 'freetobefruitful_widgets_init');


function freetobefruitful_register_widgets() {
  register_sidebar([
      'name'          => 'Sub Header',
      'id'            => 'sub_header',
      'before_widget' => '',
      'after_widget'  => '',
  ]);
}
add_action('widgets_init', 'freetobefruitful_register_widgets');


function freetobefruitful_footer_widgets() {
  register_sidebar([
      'name'          => 'Footer Column 1',
      'id'            => 'footer_col_1',
      'before_widget' => '',
      'after_widget'  => '',
  ]);
  register_sidebar([
      'name'          => 'Footer Column 2',
      'id'            => 'footer_col_2',
      'before_widget' => '',
      'after_widget'  => '',
  ]);
  register_sidebar([
      'name'          => 'Footer Column 3',
      'id'            => 'footer_col_3',
      'before_widget' => '',
      'after_widget'  => '',
  ]);
  
}
add_action('widgets_init', 'freetobefruitful_footer_widgets');

