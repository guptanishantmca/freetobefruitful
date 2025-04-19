<article id="post-<?php the_ID(); ?>" <?php post_class('mb-8 p-4 bg-white rounded shadow'); ?>>
  <h2 class="text-2xl font-bold mb-2">
    <a href="<?php the_permalink(); ?>" class="text-blue-600 hover:underline">
      <?php the_title(); ?>
    </a>
  </h2>
  <div class="text-gray-600 text-sm mb-4">
    Posted on <?php the_time('F j, Y'); ?> by <?php the_author(); ?>
  </div>
  <div class="text-gray-800">
    <?php the_excerpt(); ?>
  </div>
</article>  
