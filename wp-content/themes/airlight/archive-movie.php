<?php
/**
 * Template for displaying movie archive
 *
 * @package air-light
 */

namespace Air_Light;

get_header();
?>

<main class="site-main">
  <div class="post-inner">

    <h1>Movies</h1>

    <?php if ( have_posts() ) : ?>
      <div class="movies-list">
        <?php while ( have_posts() ) : the_post(); ?>
          <article <?php post_class(); ?>>
            <h2>
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <?php
            $year = get_field( 'year' );
            $director = get_field( 'director' );
            ?>
            <?php if ( $year || $director ) : ?>
              <p>
                <?php if ( $year ) : ?>
                  <?php echo esc_html( $year ); ?>
                <?php endif; ?>
                <?php if ( $year && $director ) : ?> - <?php endif; ?>
                <?php if ( $director ) : ?>
                  <?php echo esc_html( $director ); ?>
                <?php endif; ?>
              </p>
            <?php endif; ?>
          </article>
        <?php endwhile; ?>
      </div>
    <?php else : ?>
      <p>No movies found.</p>
    <?php endif; ?>

  </div>
</main>

<?php get_footer();
