<?php
// Exit if accessed directly
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete custom post type entries
function delete_custom_post_type_entries()
{
    $args = array(
        'post_type' => 'board-member',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wp_delete_post(get_the_ID(), true);
        }
    }

    wp_reset_postdata();

    // Delete the custom taxonomy terms
    // need to add code to delete the custom taxonomy terms

    // Delete the field group
    // Function to delete ACF field group by key if it exists
    function delete_acf_field_group_by_key($field_group_key)
    {
        if (function_exists('acf_delete_field_group')) {
            // Check if the field group exists
            $field_group = acf_get_field_group($field_group_key);
            if ($field_group) {
                // Delete the field group and all its fields
                acf_delete_field_group($field_group_key);
            }
        }
    }

    // Example usage
    delete_acf_field_group_by_key('group_6536d2eba9be8');

}


// Hook the delete_custom_post_type_entries function to the plugin uninstall action
register_uninstall_hook(__FILE__, 'delete_custom_post_type_entries');
