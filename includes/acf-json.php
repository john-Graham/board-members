<?php
/**
 * ACF Set custom load and save JSON points.
 *
 * @link https://www.advancedcustomfields.com/resources/local-json/
 */

 // This is using json file to store the ACF content exxecept for the CPT.   
 // A pro of this is the .json can be managed in git and is easily changed using the ACF interface.
 // A downside is that the .json could be easity change in the interface.   If you want to change the approach to loading these settings via php 
 // you can use the acf/import/export feature to export the settings and then import them into the php files.  suggested location is in the includes/folderName
 
namespace UWBoardMembers;

class ACFConfiguration {
    // Constructor to add all actions/filters
    public function __construct() {
        \add_filter('acf/json/load_paths', [$this, 'uw_board_members_json_load_paths']);
        \add_filter('acf/settings/save_json/key=group_6536d2eba9be8', [$this, 'uw_board_members_json_save_path_for_field_groups']);
        \add_filter('acf/settings/save_json/key=group_65381c1c5a213', [$this, 'uw_board_members_json_save_path_for_field_groups']);
        \add_filter('acf/settings/save_json/key=post_type_653695de117d4', [$this, 'uw_board_members_json_save_path_for_post_types']);
        \add_filter('acf/settings/save_json/key=taxonomy_6536b72d725e0', [$this, 'uw_board_members_json_save_path_for_taxonomies']);
        \add_filter('acf/json/save_file_name', [$this, 'uw_board_members_json_filename'], 10, 3);
    }

    public function uw_board_members_json_load_paths($paths) {
        $paths[] = UW_BOARD_MEMBERS_PLUGIN_DIR . '/acf-json/field-groups';
        $paths[] = UW_BOARD_MEMBERS_PLUGIN_DIR . '/acf-json/options-pages';
        $paths[] = UW_BOARD_MEMBERS_PLUGIN_DIR . '/acf-json/post-types';
        $paths[] = UW_BOARD_MEMBERS_PLUGIN_DIR . '/acf-json/taxonomies';

        return $paths;
    }

    public function uw_board_members_json_save_path_for_post_types() {
        return UW_BOARD_MEMBERS_PLUGIN_DIR . '/acf-json/post-types';
    }

    public function uw_board_members_json_save_path_for_field_groups() {
        return UW_BOARD_MEMBERS_PLUGIN_DIR . '/acf-json/field-groups';
    }

    public function uw_board_members_json_save_path_for_taxonomies() {
        return UW_BOARD_MEMBERS_PLUGIN_DIR . '/acf-json/taxonomies';
    }

    public function uw_board_members_json_filename($filename, $post) {
        $filename = str_replace(
            array(' ', '_'),
            array('-', '-'),
            $post['title']
        );
        $filename = strtolower($filename) . '.json';

        return $filename;
    }
}

// Create an instance of the class and setup everything
$acf_configurator = new ACFConfiguration();
