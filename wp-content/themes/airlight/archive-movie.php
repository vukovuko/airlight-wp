<?php
/**
 * Template for displaying movie archive
 *
 * @package air-light
 */

namespace Air_Light;

// Check for genre filter
$active_genre = isset( $_GET['genre'] ) ? sanitize_text_field( $_GET['genre'] ) : 'all';

// Build query args
$query_args = [
  'post_type'      => 'movie',
  'posts_per_page' => -1,
];

// Add taxonomy filter if not "all"
if ( $active_genre !== 'all' ) {
  $query_args['tax_query'] = [
    [
      'taxonomy' => 'genre',
      'field'    => 'slug',
      'terms'    => $active_genre,
    ],
  ];
}

$movies_query = new \WP_Query( $query_args );

// If AJAX request, return only the movies list
if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' ) {
  render_movies_list( $movies_query );
  exit;
}

// Get all genres for filters
$genres = get_terms([
  'taxonomy'   => 'genre',
  'hide_empty' => true,
]);

get_header();
?>

<main class="site-main">
  <div class="post-inner">

    <h1>Movies</h1>

    <?php if ( ! empty( $genres ) && ! is_wp_error( $genres ) ) : ?>
      <movies-filter>
        <div class="movie-filters">
          <button class="filter-btn <?php echo $active_genre === 'all' ? 'active' : ''; ?>" data-genre="all">All</button>
          <?php foreach ( $genres as $genre ) : ?>
            <button class="filter-btn <?php echo $active_genre === $genre->slug ? 'active' : ''; ?>" data-genre="<?php echo esc_attr( $genre->slug ); ?>">
              <?php echo esc_html( $genre->name ); ?>
            </button>
          <?php endforeach; ?>
        </div>
      </movies-filter>
    <?php endif; ?>

    <movies-list class="movies-list">
      <?php render_movies_list( $movies_query ); ?>
    </movies-list>

  </div>
</main>

<?php
get_footer();

/**
 * Render movies list HTML
 */
function render_movies_list( $query ) {
  if ( $query->have_posts() ) :
    while ( $query->have_posts() ) : $query->the_post();
      $director = get_field( 'director' );
      $year     = get_field( 'year' );
      ?>
      <article <?php post_class( 'movie-item' ); ?>>
        <h2>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <p>
          <?php if ( $year ) : ?><?php echo esc_html( $year ); ?><?php endif; ?>
          <?php if ( $year && $director ) : ?> - <?php endif; ?>
          <?php if ( $director ) : ?><?php echo esc_html( $director ); ?><?php endif; ?>
        </p>
      </article>
      <?php
    endwhile;
    wp_reset_postdata();
  else :
    ?>
    <p>No movies found.</p>
    <?php
  endif;
}
