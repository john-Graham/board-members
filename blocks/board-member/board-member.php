<?php

/**
 * Board member slider block template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or its parent block.
 */

// Support custom "anchor" values.
$anchor = '';
if (!empty($block['anchor'])) :
    $anchor = 'id="' . esc_attr($block['anchor']) . '" ';
endif;

// WP block settings
$classes = array('acf-board-member', $block['id'], 'position-relative');
if (!empty($block['className'])) :
    $classes = array_merge($classes, explode(' ', $block['className']));
endif;
if (!empty($block['align'])) :
    $classes[] = 'align' . $block['align'];
endif;
if (empty($block['className']) || (isset($block['className']) && !preg_match('(m[tebsxy]{0,1}?-n?\d)', $block['className']))) :
    $classes[] = 'my-5';
endif;
if ((empty($block['className']) || (isset($block['className']) && !preg_match('(p[tebsxy]{0,1}?-n?\d)', $block['className']))) && (isset($block['align']) && $block['align'] == 'full')) :
    $classes[] = 'py-5';
endif;

$container_classes = get_field('block_wrapper_class') ? explode(' ', get_field('block_wrapper_class')) : array();
if ($block['align'] == 'full') :
    $container_classes[] = get_theme_mod('theme_container_type');
endif;

$styles = array();
if (!empty($block['gradient'])) :
    $styles[] = 'background: var(--wp--preset--gradient--' . $block['gradient'] . ')';
endif;
if (!empty($block['style'])) :
    if (!empty($block['style']['color']['gradient'])) :
        $styles[] = 'background: ' . $block['style']['color']['gradient'];
    endif;
endif;

$filter_taxonomy_board = get_field('filter_taxonomy_board');
$selected_posts = get_field('posts');
$mode = get_field('mode') ? get_field('mode') : "manual";
$display_type = get_field('display_type') ? get_field('display_type') : "title";
if (!empty($display_type)) :
    $classes[] = 'acf-board-members-' . $display_type;
endif;
// block configuration settings
?>
<div <?php echo $anchor; ?>class="<?php echo esc_attr(implode(' ', $classes)); ?>" <?php if ($styles) : ?> style="<?php echo esc_attr(implode(';', $styles)); ?>" <?php endif; ?>>
    <?php if ($container_classes) : ?><div class="<?php echo esc_attr(implode(' ', $container_classes)); ?>"><?php endif; ?>
        <?php if ($mode == 'query') :

            $args = array(
                'post_type'              => 'board-member',
                'posts_per_page'         => '-1',
                'meta_key'               => 'last_name',
                'orderby'                => 'meta_value',
                'order'                  => 'ASC',
            );

            // print_r($args);

            if (get_field('filter_taxonomy_board')) {
                $args['tax_query'] = ['relation' => 'OR'];
                foreach (get_field('filter_taxonomy_board') as $term) {
                    $args['tax_query'][] = array(
                        'taxonomy' => 'board',
                        'field' => 'term_id',
                        'terms' => $term
                    );
                }
            }
            $theme = wp_get_theme(); // Retrieves the current theme object
            $theme_directory_name = $theme->get_stylesheet(); // Gets the directory name of the theme

            $wp_query = new WP_Query($args);
            if ($wp_query->have_posts()) :
                echo $theme_directory_name == 'coe' ? '<div class="row">' : '';

                while ($wp_query->have_posts()) : $wp_query->the_post();
                    if ($theme_directory_name == 'coe') {
                        include(plugin_dir_path(__FILE__) . 'themes/coe-template.php');
                    } elseif ($theme_directory_name == 'uw-theme') {
                        include(plugin_dir_path(__FILE__) . 'themes/uw-template.php');
                    } else {
                        include(plugin_dir_path(__FILE__) . 'themes/default-template.php');
                    }
                endwhile;
                echo $theme_directory_name == 'coe' ? '</div>' : '';

            else :
                echo 'No profiles found';
            endif;

            wp_reset_postdata();
            wp_reset_query();
        elseif ($mode == 'manual') :
            if ($selected_posts) :
                global $post;
                //echo "Number of posts: $total_posts";
                // needed to create wrapper for accordion of titles
                foreach ($selected_posts as $post) :
                    // Setup this post for WP functions (variable must be named $post).
                    setup_postdata($post);


                    if ($theme_directory_name == 'coe') {
                        echo '<div class="row row-cols-6 pt-3">';
                        // Directly include the specific template
                        include(plugin_dir_path(__FILE__) . 'themes/coe-template.php');
                        echo '</div>';
                    } elseif ($theme_directory_name == 'uw-theme') {
                        // Directly include the specific template
                        include(plugin_dir_path(__FILE__) . 'themes/uw-template.php');
                    } else {
                        // Include the default template
                        include(plugin_dir_path(__FILE__) . 'themes/default-template.php');
                    }
                endforeach;
                wp_reset_postdata();
            else :  ?>
                No profiles found.
            <?php endif;  ?>
        <?php endif;  ?>

        <?php if ($container_classes) : ?></div><?php endif; ?>
</div>