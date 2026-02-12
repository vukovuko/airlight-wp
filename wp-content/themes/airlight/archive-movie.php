<?php
/**
 * Template for displaying movie archive
 *
 * @package air-light
 */

namespace Air_Light;

get_header();

// Get all genres from taxonomy
$genres = get_terms([
  'taxonomy'   => 'genre',
  'hide_empty' => true,
]);
?>

<main class="site-main">
  <div class="post-inner">

    <h1>Movies</h1>

    <?php if ( ! empty( $genres ) && ! is_wp_error( $genres ) ) : ?>
      <movies-filter>
        <div class="movie-filters">
          <button class="filter-btn active" data-genre="all">All</button>
          <?php foreach ( $genres as $genre ) : ?>
            <button class="filter-btn" data-genre="<?php echo esc_attr( $genre->slug ); ?>">
              <?php echo esc_html( $genre->name ); ?>
            </button>
          <?php endforeach; ?>
        </div>
      </movies-filter>
    <?php endif; ?>

    <movies-list class="movies-list">
      <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post();
          $director = get_field( 'director' );
          $year     = get_field( 'year' );
          $movie_genres = wp_get_post_terms( get_the_ID(), 'genre', [ 'fields' => 'slugs' ] );
        ?>
          <article <?php post_class( 'movie-item' ); ?> data-genres="<?php echo esc_attr( implode( ',', $movie_genres ) ); ?>">
            <h2>
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <p>
              <?php if ( $year ) : ?><?php echo esc_html( $year ); ?><?php endif; ?>
              <?php if ( $year && $director ) : ?> - <?php endif; ?>
              <?php if ( $director ) : ?><?php echo esc_html( $director ); ?><?php endif; ?>
            </p>
          </article>
        <?php endwhile; ?>
      <?php else : ?>
        <p>No movies found.</p>
      <?php endif; ?>
    </movies-list>

  </div>
</main>

<?php get_footer();
