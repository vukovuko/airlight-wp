<?php
/**
 * Template for displaying a single movie
 *
 * @package air-light
 */

namespace Air_Light;

the_post();
get_header();

// Get ACF fields
$director = get_field( 'director' );
$year     = get_field( 'year' );
$genre    = get_field( 'genre' );
?>

<main class="site-main">
  <article <?php post_class(); ?>>
    <div class="post-inner">

      <h1><?php the_title(); ?></h1>

      <div class="movie-details">
      <?php if ( $director ) : ?>
        <p><strong>Director:</strong> <?php echo esc_html( $director ); ?></p>
      <?php endif; ?>

      <?php if ( $year ) : ?>
        <p><strong>Year:</strong> <?php echo esc_html( $year ); ?></p>
      <?php endif; ?>

      <?php if ( $genre ) : ?>
        <p><strong>Genre:</strong>
          <?php
          if ( is_array( $genre ) ) {
            echo esc_html( implode( ', ', $genre ) );
          } else {
            echo esc_html( $genre );
          }
          ?>
        </p>
      <?php endif; ?>
    </div>

    <div class="entry-content">
      <?php the_content(); ?>
    </div>

    </div>
  </article>
</main>

<?php get_footer();
