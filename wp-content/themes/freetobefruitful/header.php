<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>
<body <?php body_class('bg-white text-gray-800'); ?>>
  <header class="bg-white shadow sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
      <a href="<?php echo home_url(); ?>" class="text-2xl font-bold text-blue-800">Free To Be Fruitful</a>
      <nav class="space-x-6 text-lg">
        <a href="<?php echo home_url(); ?>" class="text-gray-700 hover:text-blue-600">Home</a>
        <a href="<?php echo home_url('/about'); ?>" class="text-gray-700 hover:text-blue-600">About</a>
        <a href="<?php echo home_url('/blog'); ?>" class="text-gray-700 hover:text-blue-600">Blog</a>
        <a href="<?php echo home_url('/contact'); ?>" class="text-gray-700 hover:text-blue-600">Contact</a>
      </nav>
    </div>
  </header>