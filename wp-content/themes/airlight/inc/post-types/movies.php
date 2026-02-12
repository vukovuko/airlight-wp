<?php
/**
 * Movies custom post type.
 *
 * @package air-light
 */

namespace Air_Light;

function register_movies_post_type() {
  $labels = [
    'name'               => 'Movies',
    'singular_name'      => 'Movie',
    'menu_name'          => 'Movies',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Movie',
    'edit_item'          => 'Edit Movie',
    'view_item'          => 'View Movie',
    'all_items'          => 'All Movies',
    'search_items'       => 'Search Movies',
    'not_found'          => 'No movies found.',
    'not_found_in_trash' => 'No movies found in Trash.',
  ];

  $args = [
    'labels'       => $labels,
    'public'       => true,
    'has_archive'  => true,
    'show_in_rest' => true,
    'menu_icon'    => 'dashicons-video-alt2',
    'supports'     => [ 'title', 'editor', 'thumbnail' ],
    'rewrite'      => [ 'slug' => 'movies' ],
  ];

  register_post_type( 'movie', $args );
}

add_action( 'init', __NAMESPACE__ . '\register_movies_post_type' );
