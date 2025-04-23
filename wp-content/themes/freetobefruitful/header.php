<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
  
</head>
<body>
<div class="presence">
    <div id="header">
        <!-- 8/12 and 4/12 layout -->
        <div class="row logo_screen logo_themed">
            <div class="fivecol">
                <div class="logo_flag">
                    <p><span style="font-size: xx-large;">Freedom Through Faith Ministries</span></p>													</div>
                &nbsp;
            </div>
            <div class="sevencol last navigation">
                <div class="ddsmoothmenu" id="smoothmenu">
                    <!-- HIDE THE GET DIRECTIONS MODULE
                    <a href="#map" class="get_directions_header show_map" onClick="settupMap()" rel="prettyPhoto"><span class="icon-font-location"></span> Get Directions</a>
                    -->
                        
                    <?php
wp_nav_menu([
    'theme_location' => 'header',
    'container' => false,
    'menu_class' => 'nav',
    'walker' => new Custom_Nav_Walker(),
]);
?>






                       <!-- <li class="nav-selected nav-path-selected"><a href="/" target="_self" class="nav-selected nav-path-selected">Home</a></li><li class=""><a href="/index.php/about-us/" target="_self" class="">About Us</a></li><li class=""><a href="/index.php/photo-gallery/" target="_self" class="">Photos</a></li><li class=""><a href="/index.php/itinerary/" target="_self" class="">Itinerary</a></li><li class=""><a href="/index.php/testimonies/" target="_self" class="">Testimonies</a></li><li class=""><a href="/index.php/blog/" target="_self" class="">Blog</a></li><li class=""><a href="/index.php/golf-event/" target="_self" class="">Sponsor an Orphan</a></li> -->
                    						</div>
                <div class="select_nav styled-select"><select><option selected="selected" value="">Menu</option><option value="/">Home</option><option value="/index.php/about-us/">About Us</option><option value="/index.php/photo-gallery/">Photos</option><option value="/index.php/itinerary/">Itinerary</option><option value="/index.php/testimonies/">Testimonies</option><option value="/index.php/blog/">Blog</option><option value="/index.php/golf-event/">Sponsor an Orphan</option></select></div>
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

     
