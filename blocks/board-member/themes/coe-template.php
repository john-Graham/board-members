<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$post_id = get_the_ID();


$first_name = get_post_meta($post_id, 'first_name', true);
$last_name = get_post_meta($post_id, 'last_name', true);
$middle_name = get_post_meta($post_id, 'middle_name', true);
$credentials = get_post_meta($post_id, 'credentials', true);

$fullname = '' . $first_name . ' ' . $middle_name . ' ' . $last_name;
$email = get_post_meta($post_id, 'email', true);

//Degrees repeater
$degree = get_post_meta($post_id, 'degree', true);

//titles repeater
$titles = get_post_meta($post_id, 'titles', true);


// $links = get_post_meta($post_id, 'links', true); //repeater
// lets dynamically create the link variables from the repeater.

$linkedin_link = '';
$x_link = '';


// setting these variables here as well as below for now so I can use personal_link for the image link.
// when we have directory included in the site let's remove personal link from the repeater and use the directory link instead.
if (have_rows('links', $post_id)) :
    // Loop through rows.
    while (have_rows('links', $post_id)) : the_row();
        // Load sub field value.
        $sub_link_type = get_sub_field('link_type');
        $sub_link_url = get_sub_field('link_url');
        // echo '<!-- sub_link_type: ' . $sub_link_type . ' -->';
        // Check if the sub fields are not empty & currently not personal link, that is the directory URL for now. 
        if (!empty($sub_link_type) && !empty($sub_link_url)) {
            // Dynamically create variables based on the sub field value
            ${$sub_link_type . '_link'} = $sub_link_url;
        }
    endwhile;
endif;


//images
$image = get_post_meta($post_id, 'image', true);

$plugin_directory_url = plugins_url() . '/board-members/';

?>
<?php
if ($display_type == 'accordion') {
    // echo 'display type is accordion';
?>
    <div class="acf-board-members-accordion">
        <div class="board-title stretched-link-escape my-4">
            <?php if ($image) : ?>
                <div class="board-img"><?php echo wp_get_attachment_image($image, 'small', false, array('class' => 'img-fluid')); ?></div>
            <?php else : ?>
                <div class="board-img"><img src="<?php echo plugins_url('assets/dist/img/Profile.png', realpath(dirname(__FILE__) . '/../..')); ?>" alt="silhouette" class="img-fluid"></div>
            <?php endif; ?>
            <div class="board-icons ps-3">

                <?php
                if ($linkedin_link) :
                    echo '<a href="' . esc_url($linkedin_link) . '" class="stretched-link-escape" target="_blank" title="Open ' . $first_name . '\'s LinkedIn site in new window"><img src="' . $plugin_directory_url . 'assets/dist/img/icons/linkedin.svg" alt="LinkedIn website" class="pe-2"></a>';
                endif;
                if ($x_link) :
                    echo '<a href="' . esc_url($x_link) . '" class="stretched-link-escape" target="_blank" title="Open ' . $first_name . '\'s X site in new window"><img src="' . $plugin_directory_url . 'assets/dist/img/icons/x.svg" alt="X website" class="pe-2"></a>';
                endif; ?>
            </div>
            <div class="board-content p-3">
                <div class="board-name fw-bold">
                    <?php
                    echo $fullname;
                    if ($credentials) echo ', ' . $credentials;
                    if ($degree) echo ', ' . $degree;
                    ?>
                </div>
                <div class="board-titles small">
                    <?php
                    $num_iterations = $titles;
                    // print_r('<pre> ' . $titles . '</pre>');
                    for ($i = 0; $i < $num_iterations; $i++) {
                        // Get the sub-fields for each repeater row
                        $title = get_post_meta($post_id, 'titles_' . $i . '_title', true);
                        $company = get_post_meta($post_id, 'titles_' . $i . '_company', true);
                        $url = get_post_meta($post_id, 'titles_' . $i . '_url', true);

                        echo $title;
                        echo !empty($company)
                            ? (!empty($url) ? ", <a href='" . esc_url($url) . "' class='stretched-link-escape'>" . esc_html($company) . "</a>" : ", " . esc_html($company))
                            : "";

                        echo ($i < $num_iterations - 1) ? '<br>' : '';
                    }
                    ?>
                </div>
                <?php if ($email) : ?>
                    <div class="board-email small"><a href="mailto:<?php echo $email ?>" class="stretched-link-escape"><?php echo $email ?></a></div>
                <?php endif; ?>
                <div id="collapse-<?php echo $post_id; ?>" class="board-bio collapse pt-2" aria-labelledby="heading-<?php echo $post_id; ?>" data-bs-parent="#accordion-<?php echo $post_id; ?>">
                    <?php the_content(); ?>
                </div>
            </div>

            <?php if (!empty(get_the_content())) : ?>
                <div class="board-expander p-1">
                    <a href="#collapse-<?php echo $post_id; ?>" class="profile-button fs-4 collapsed stretched-link" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $post_id; ?>" aria-label="toggle content view" aria-expanded="false" aria-controls="collapse-<?php echo $post_id; ?>"><i class="fa-solid fa-chevron-down"></i>
                    </a>
                </div>
            <?php endif; ?>
            <div class="board-bg rounded" style="background-color: var(--bs-gray-200);"></div>
        </div>
    </div>
<?php
} elseif ($display_type == 'list') {
    // echo 'display type is lists';
?>

    <div class="col-12 col-md-6 p-3">
        <div class="name"><?php if ($fullname) : ?><?php echo $fullname ?><?php endif; ?>
            <?php if ($degree) : ?><span class="degree"><?php echo $degree ?></span><?php endif; ?>
        </div>
        <?php if ($titles) : ?>
        <?php
            $num_iterations = $titles;
            // print_r('<pre> ' . $titles . '</pre>');
            for ($i = 0; $i < $num_iterations; $i++) {
                // Get the sub-fields for each repeater row
                $title = get_post_meta($post_id, 'titles_' . $i . '_title', true);
                $company = get_post_meta($post_id, 'titles_' . $i . '_company', true);
                $url = get_post_meta($post_id, 'titles_' . $i . '_url', true);

                echo '<div class="companyTitle">' . $title . '</div>';
                echo !empty($company)
                    ? "<div class='companyName'>"
                    . (!empty($url)
                        ? "<a href='" . esc_url($url) . "' class='stretched-link-escape'>" . esc_html($company) . "</a>"
                        : "" . esc_html($company))
                    . "</div>"
                    : "";

                echo ($i < $num_iterations - 1) ? '<br>' : '';
            }
        endif; ?>
        <div class="row">
            <div class="col social">
                <?php
                if ($linkedin_link) :
                    echo '<a href="' . esc_url($linkedin_link) . '" class="stretched-link-escape" target="_blank" title="Open ' . $first_name . '\'s LinkedIn site in new window"><img src="' . $plugin_directory_url . 'assets/dist/img/icons/linkedin.svg" alt="LinkedIn website" class="pe-2"></a>';
                endif;
                if ($x_link) :
                    echo '<a href="' . esc_url($x_link) . '" class="stretched-link-escape" target="_blank" title="Open ' . $first_name . '\'s X site in new window"><img src="' . $plugin_directory_url . 'assets/dist/img/icons/x.svg" alt="X website" class="pe-2"></a>';
                endif;
                ?> </div>
        </div>
    </div>


<?php
} else {
    echo 'display type is unknown';
}
?>