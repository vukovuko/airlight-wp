<?php
/**
 * Template for displaying movie archive
 *
 * @package air-light
 */

namespace Air_Light;

// Get filter and pagination params
$active_genre = isset( $_GET['genre'] ) ? sanitize_text_field( $_GET['genre'] ) : 'all';
// Check both query var (pretty permalinks) and $_GET (AJAX requests)
$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : ( isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1 );
$per_page = 10;

// Build query args
$query_args = [
  'post_type'      => 'movie',
  'posts_per_page' => $per_page,
  'paged'          => $paged,
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
$total_pages = $movies_query->max_num_pages;

// If AJAX request, return only the movies list + pagination
if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' ) {
  render_movies_list( $movies_query );
  render_pagination( $paged, $total_pages, $active_genre );
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
      <?php render_pagination( $paged, $total_pages, $active_genre ); ?>
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
    echo '<div class="movies-grid">';
    while ( $query->have_posts() ) : $query->the_post();
      $director = get_field( 'director' );
      $year     = get_field( 'year' );
      ?>
      <article <?php post_class( 'movie-item' ); ?>>
        <a href="<?php the_permalink(); ?>" class="movie-link">
          <h2><?php the_title(); ?></h2>
          <p>
            <?php if ( $year ) : ?><?php echo esc_html( $year ); ?><?php endif; ?>
            <?php if ( $year && $director ) : ?> - <?php endif; ?>
            <?php if ( $director ) : ?><?php echo esc_html( $director ); ?><?php endif; ?>
          </p>
        </a>
      </article>
      <?php
    endwhile;
    echo '</div>';
    wp_reset_postdata();
  else :
    ?>
    <p>No movies found.</p>
    <?php
  endif;
}

/**
 * Render pagination HTML
 */
function render_pagination( $current_page, $total_pages, $genre ) {
  if ( $total_pages <= 1 ) {
    return;
  }
  ?>
  <nav class="movies-pagination" aria-label="Movies pagination">
    <?php if ( $current_page > 1 ) : ?>
      <button class="page-btn" data-page="<?php echo $current_page - 1; ?>">&laquo; Prev</button>
    <?php endif; ?>

    <?php for ( $i = 1; $i <= $total_pages; $i++ ) :
      $is_active = ( (int) $i === (int) $current_page );
    ?>
      <button class="page-btn <?php echo $is_active ? 'active' : ''; ?>" data-page="<?php echo $i; ?>">
        <?php echo $i; ?>
      </button>
    <?php endfor; ?>

    <?php if ( $current_page < $total_pages ) : ?>
      <button class="page-btn" data-page="<?php echo $current_page + 1; ?>">Next &raquo;</button>
    <?php endif; ?>
  </nav>
  <?php
}
