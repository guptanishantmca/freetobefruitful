<?php get_header(); ?>

<main class="container mx-auto px-4 py-10">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article class="mb-10 border-b pb-4">
      <h2 class="text-2xl font-bold text-gray-800 mb-2">
        <a href="<?php the_permalink(); ?>" class="hover:text-blue-600"><?php the_title(); ?></a>
      </h2>
      <div class="text-sm text-gray-500 mb-4"><?php the_time('F j, Y'); ?></div>
      <div class="prose max-w-none"><?php the_excerpt(); ?></div>
    </article>
  <?php endwhile; else : ?>
    <p>No posts found.</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
