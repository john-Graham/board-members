<?php
namespace UWBoardMembers;

// Exit if accessed directly
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

class Uninstaller {
    public function __construct() {
        $this->deleteACFFieldGroups();
        $this->deleteCustomPostTypeEntries();
        $this->deleteCustomTaxonomyTerms('board');
        $this->clearCache();
    }

    private function deleteCustomPostTypeEntries() {
        $args = array(
            'post_type' => 'board-member',
            'posts_per_page' => -1,
            'fields' => 'ids' // Retrieve only the IDs for better performance
        );

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                \wp_delete_post(get_the_ID(), true); // Set to true to bypass trash
            }
        }
    }

    private function deleteCustomTaxonomyTerms($taxonomy) {
        $terms = \get_terms(array(
            'taxonomy' => $taxonomy,
            'fields' => 'ids', // Fetch only the IDs for efficiency
            'hide_empty' => false // Get all terms, even if they're not assigned to any posts
        ));

        if (!is_wp_error($terms)) {
            foreach ($terms as $term_id) {
                \wp_delete_term($term_id, $taxonomy);
            }
        }
    }

    private function deleteACFFieldGroups() {
        $field_groups = ['group_6536d2eba9be8', 'group_65381c1c5a213']; // List all ACF group keys
        foreach ($field_groups as $group_key) {
            if (function_exists('acf_delete_field_group')) {
                \acf_delete_field_group($group_key);
            }
        }
    }

    private function clearCache() {
        \wp_cache_flush();
    }
}

new Uninstaller();
