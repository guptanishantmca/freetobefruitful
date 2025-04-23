<?php get_header(); ?>
 
 
 
<div id="highlight">
    <!-- <div class="row">
        <div class="twelvecol">
        <div class="banner_wrapper"> -->
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 
  <!-- <div class="row"> -->
      <?php the_content(); ?>
<!-- </div> -->
      <!-- </div>
        </div>
    </div> -->
    <!-- <div class="row">
				<div class="latest_message">
				<p style="text-align: center;">Fulfilling the great commission of Jesus Christ to go and make disciples</p>
<p style="text-align: center;">&nbsp;</p>
<p style="text-align: center;">Training and providing resources to national pastors and leaders</p>
<p style="text-align: center;">&nbsp;</p>
<p style="text-align: center;">Planting bible training centers and churches</p>								</div>
			</div> -->
      <div class="row call_to_action">
				<div class="fourcol">
					<a href="/index.php/newsletters/"><img border="0" class="ccm-image-block" alt="" src="https://www.freetobefruitful.com/files/5113/9764/4665/newsletter-cta.jpg" width="300" height="135"></a>														</div>
				<div class="fourcol">
					<a href="/index.php/bible-training-centers/"><img border="0" class="ccm-image-block" alt="" src="https://www.freetobefruitful.com/files/1313/9764/4699/btc-cta.jpg" width="300" height="135"></a>														</div>
				<div class="fourcol last">
					<a href="/index.php/golf-event/"><img border="0" class="ccm-image-block" alt="" src="https://www.freetobefruitful.com/files/7116/2792/4573/IMG_3013.JPG" width=" " height=" "></a><p>HOW TO SPONSOR A UGANDAN ORPHAN</p>														</div>
			</div>
  <?php endwhile; endif; ?>
  </div>

<?php get_footer(); ?>