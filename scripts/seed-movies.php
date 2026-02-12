<?php
/**
 * Seed 100 movies with ACF fields and genres
 * Run with: lando wp eval-file scripts/seed-movies.php
 */

$genres = ['action', 'comedy', 'drama', 'horror', 'sci-fi', 'thriller', 'romance', 'documentary'];
$directors = ['Christopher Nolan', 'Quentin Tarantino', 'Martin Scorsese', 'Steven Spielberg', 'Denis Villeneuve', 'Greta Gerwig', 'Jordan Peele', 'Bong Joon-ho'];
$adjectives = ['Amazing', 'Incredible', 'Dark', 'Silent', 'Hidden', 'Lost', 'Final', 'Last', 'First', 'Secret'];
$nouns = ['Journey', 'Night', 'Day', 'World', 'Dream', 'Story', 'Adventure', 'Mystery', 'Legend', 'Quest'];

// First, ensure genres exist as taxonomy terms
foreach ($genres as $genre) {
  if (!term_exists($genre, 'genre')) {
    wp_insert_term(ucfirst($genre), 'genre', ['slug' => $genre]);
    echo "Created genre: $genre\n";
  }
}

// Create 100 movies
for ($i = 1; $i <= 100; $i++) {
  $adjective = $adjectives[array_rand($adjectives)];
  $noun = $nouns[array_rand($nouns)];
  $title = "The $adjective $noun $i";

  // Check if movie already exists
  $existing = get_page_by_title($title, OBJECT, 'movie');
  if ($existing) {
    echo "Skipping (exists): $title\n";
    continue;
  }

  // Create movie post
  $post_id = wp_insert_post([
    'post_title'  => $title,
    'post_type'   => 'movie',
    'post_status' => 'publish',
    'post_content' => "This is the description for $title. A compelling story that will keep you on the edge of your seat.",
  ]);

  if (is_wp_error($post_id)) {
    echo "Error creating: $title\n";
    continue;
  }

  // Add ACF fields
  $director = $directors[array_rand($directors)];
  $year = rand(1990, 2026);

  update_field('director', $director, $post_id);
  update_field('year', $year, $post_id);

  // Assign 1-3 random genres
  $num_genres = rand(1, 3);
  $movie_genres = array_rand(array_flip($genres), $num_genres);
  if (!is_array($movie_genres)) {
    $movie_genres = [$movie_genres];
  }
  wp_set_object_terms($post_id, $movie_genres, 'genre');

  echo "Created: $title ($year) - $director - " . implode(', ', $movie_genres) . "\n";
}

echo "\nDone! Created 100 movies.\n";
