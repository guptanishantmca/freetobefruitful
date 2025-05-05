<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

  <?php wp_head(); ?>
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const burger = document.getElementById("burger");
    const nav = document.getElementById("nav");

    burger.addEventListener("click", function () {
      nav.classList.toggle("active");
    });
  });
</script>

</head>
<body>
<div class="presence">
    <!-- <div id="header">
        
        <div class="row logo_screen logo_themed">
            <div class="fivecol">
                <div class="logo_flag">
                    <p><span style="font-size: xx-large;">Freedom Through Faith Ministries</span></p>													</div>
                &nbsp;
            </div>
            <div class="sevencol last navigation">
                <div class="ddsmoothmenu" id="smoothmenu">
                    
                    <?php
// wp_nav_menu([
//     'theme_location' => 'header',
//     'container' => false,
//     'menu_class' => 'nav',
//     'walker' => new Custom_Nav_Walker(),
// ]);
?>
 
                    						</div>
                <div class="select_nav styled-select"><select><option selected="selected" value="">Menu</option><option value="/">Home</option><option value="/index.php/about-us/">About Us</option><option value="/index.php/photo-gallery/">Photos</option><option value="/index.php/itinerary/">Itinerary</option><option value="/index.php/testimonies/">Testimonies</option><option value="/index.php/blog/">Blog</option><option value="/index.php/golf-event/">Sponsor an Orphan</option></select></div>
            </div>
        </div>
    </div>
    <?php //if (is_active_sidebar('sub_header')) : ?>
        <div id="search">
    <div class="row">
        <div class="twelvecol">
            <div class="slogan">
                <div class="twelvecol">
        <?php //dynamic_sidebar('sub_header'); ?>
        </div></div></div></div></div>
<?php //endif; ?> -->

     
<div id="header">
  <div class="row header-container">
  <div class="col-sm-12  col-md-4    mb-3">
    <!-- Logo -->
    <div class="logo">
      <p><span>Freedom Through Faith Ministries</span></p>
    </div>
    </div>
    <div class="col-sm-12 col-md-8 p-0 m-0 ">
    <!-- Burger Button -->
    <button class="burger" id="burger" aria-label="Toggle Menu">
      ☰
    </button>

    <!-- Nav Menu -->
    <nav class="main-nav" id="nav">
    <?php
wp_nav_menu([
    'theme_location' => 'header',
    'container' => false,
    'menu_class' => 'nav',
    'walker' => new Custom_Nav_Walker(),
]);
?>
      <!-- <ul class="nav">
        <li><a href="/">Home</a></li>
        <li class="nav-selected nav-path-selected"><a href="/?page_id=19">About us</a></li>
        <li><a href="/?page_id=21">Photos</a></li>
        <li><a href="/?page_id=22">Itinerary</a></li>
        <li><a href="/?page_id=24">Testimonies</a></li>
        <li><a href="/?page_id=26">Blog</a></li>
        <li><a href="/?page_id=28">Sponsor an Orphan</a></li>
      </ul> -->
    </nav>
    </div>
    </div>
</div>
<?php if (is_active_sidebar('sub_header')) : ?>
        <div id="search">
    <div class="row">
        <div class="twelvecol">
            <div class="slogan">
                <div class="twelvecol">
        <?php dynamic_sidebar('sub_header'); ?>
        </div></div></div></div></div>
<?php endif; ?> 