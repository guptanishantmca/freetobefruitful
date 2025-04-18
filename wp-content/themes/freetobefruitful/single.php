<?php get_header(); ?>

<main class="container mx-auto px-4 py-10">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article class="prose max-w-none mx-auto">
      <h1 class="text-4xl font-bold text-gray-800 mb-4"><?php the_title(); ?></h1>
      <div class="text-sm text-gray-500 mb-4"><?php the_time('F j, Y'); ?></div>
      <?php the_content(); ?>
    </article>
  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>