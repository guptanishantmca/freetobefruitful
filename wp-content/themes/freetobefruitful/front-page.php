<?php get_header(); ?>
 
 
<section class="bg-red-50 py-20 text-center">
  <div class="container mx-auto px-4">
    <h1 class="text-5xl font-bold text-blue-800 mb-4">Helping Christian Women Thrive in Life</h1>
    <p class="text-xl text-gray-700 mb-8 max-w-2xl mx-auto">
      Biblical encouragement, purpose-driven living, and fruitful faith for women who want more from their walk with God.
    </p>
    <a href="/blog" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">Read the Blog</a>
  </div>
</section>  

<section class="py-16">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Latest Posts</h2>
    <div class="grid md:grid-cols-3 gap-8">
      <?php
      $recent_posts = new WP_Query(['posts_per_page' => 3]);
      while ($recent_posts->have_posts()) : $recent_posts->the_post();
      ?>
        <article class="bg-white shadow-md rounded-lg overflow-hidden">
          <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
            <?php endif; ?>
            <div class="p-4">
              <h3 class="text-xl font-bold text-gray-800"><?php the_title(); ?></h3>
              <p class="text-sm text-gray-600 mt-2"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
            </div>
          </a>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>