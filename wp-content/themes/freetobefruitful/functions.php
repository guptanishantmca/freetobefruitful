<?php
 
 function freetobefruitful_enqueue_styles() {
  wp_enqueue_style('tailwind', get_template_directory_uri() . '/tailwind.css', [], filemtime(get_template_directory() . '/tailwind.css'));
}
add_action('wp_enqueue_scripts', 'freetobefruitful_enqueue_styles');





 
function freetobefruitful_setup() {
  register_nav_menus([
    'main-menu' => 'Main Menu'
  ]);

  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'freetobefruitful_setup');

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
