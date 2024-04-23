<?php
// Exit if accessed directly
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Function to delete all posts of a custom post type
function delete_custom_post_type_entries() {
    // error_log('function delete_custom_post_type_entries() called');
    $args = array(
        'post_type' => 'board-member',
        'posts_per_page' => -1,
        'fields' => 'ids', // Retrieve only the IDs for better performance
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        foreach ($query->posts as $post_id) {
            wp_delete_post($post_id, true); // Set to true to bypass trash
        }
    }
}

// Function to delete all terms of a custom taxonomy
function delete_custom_taxonomy_terms($taxonomy) {
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'fields' => 'ids', // Fetch only the IDs for efficiency
        'hide_empty' => false, // Get all terms, even if they're not assigned to any posts
    ));

    if (!is_wp_error($terms)) {
        foreach ($terms as $term_id) {
            wp_delete_term($term_id, $taxonomy);
        }
    }
}



// Function to delete an ACF field group by key
function delete_acf_field_group_by_key($field_group_key) {
    // error_log('function delete_acf_field_group_by_key() called');
    if (function_exists('acf_delete_field_group')) {
        $field_group = acf_get_field_group($field_group_key);
        if ($field_group) {
            acf_delete_field_group($field_group_key);
        }
    }
}

// Deleting the field group for board member meta fields
delete_acf_field_group_by_key('group_6536d2eba9be8');

// Deleting the field group for board member blocks
delete_acf_field_group_by_key('group_65381c1c5a213');

// Call the function to delete custom post type entries
delete_custom_post_type_entries();

// call the function to delete the custom taxonomy for board members called 'board'
delete_custom_taxonomy_terms('board');


// Clear any cached data that has been removed
wp_cache_flush();
 

