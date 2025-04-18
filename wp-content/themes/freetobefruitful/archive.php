<?php get_header(); ?>

<main>
  <h2><?php the_archive_title(); ?></h2>

  <?php
  if (have_posts()) :
    while (have_posts()) : the_post(); ?>
      <article>
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php the_excerpt(); ?>
      </article>
    <?php endwhile;
  else :
    echo '<p>No posts found</p>';
  endif;
  ?>
</main>

<?php get_footer(); ?>
