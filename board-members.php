<?php
/**
 * Plugin Name:      Board Members
 * Description:       Wordpress plugin for CPT Board members at UW Madison engineering.
 * Requires at least: 6.3
 * Requires PHP:      7.4
 * Version:           0.1
 * Author:            John Graham
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       coe-boards
 *
 * @package           coe-boards
 */

// Define our handy constants.
define( 'COE_BOARDS_VERSION', '0.1' );
define( 'COE_BOARDS_PLUGIN_DIR', __DIR__ );
define( 'COE_BOARDS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'COE_BOARDS_PLUGIN_BLOCKS', COE_BOARDS_PLUGIN_DIR . '/blocks/' );



// Set custom load & save JSON points for ACF sync.
require 'includes/acf-json.php';
// Register blocks and other handy ACF Block helpers.
require 'includes/acf-blocks.php';
// Register a default "Site Settings" Options Page.
require 'includes/acf-settings-page.php';
// Restrict access to ACF Admin screens.
require 'includes/acf-restrict-access.php';
// Display and template helpers.
require 'includes/template-tags.php';
// Register taxonomies.
require 'includes/acf-taxonomies.php';

// // Register our custom post type.
require 'includes/field-groups/board-member.php';



// forcing the post title to be the last name, first name
function save_boards_post_type( $post_id ) {
    $post_type = get_post_type( $post_id );
    if ( $post_type == 'board-member' ) {
        $last_name = get_field( 'last_name', $post_id );
        $first_name = get_field( 'first_name', $post_id );

        $post_title = $last_name . ', ' . $first_name;
        $post_name = sanitize_title( $post_title );

        $post = array(
            'ID' => $post_id,
            'post_title' => $post_title,
            'post_name' => $post_name
        );
        wp_update_post( $post );
    }
}
add_action( 'acf/save_post', 'save_boards_post_type', 20 );

