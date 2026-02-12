<?php
/**
 * Genre taxonomy for movies.
 *
 * @package air-light
 */

namespace Air_Light;

function register_genre_taxonomy() {
  $labels = [
    'name'              => 'Genres',
    'singular_name'     => 'Genre',
    'search_items'      => 'Search Genres',
    'all_items'         => 'All Genres',
    'edit_item'         => 'Edit Genre',
    'update_item'       => 'Update Genre',
    'add_new_item'      => 'Add New Genre',
    'new_item_name'     => 'New Genre Name',
    'menu_name'         => 'Genres',
  ];

  $args = [
    'labels'            => $labels,
    'public'            => true,
    'hierarchical'      => false,
    'show_in_rest'      => true,
    'show_admin_column' => true,
    'rewrite'           => [ 'slug' => 'genre' ],
  ];

  register_taxonomy( 'genre', [ 'movie' ], $args );
}

add_action( 'init', __NAMESPACE__ . '\register_genre_taxonomy' );
