<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/hoverbic.php.
 * @author  Solwin Infotech
 * @version 2.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $post;

$total_col = $bdp_settings['template_columns'];
$total_height = $bdp_settings['template_grid_height'];
$grid_height = (isset($bdp_settings['blog_grid_height']) && $bdp_settings['blog_grid_height'] != 1) ? false : true;
$grid_skin = $bdp_settings['template_grid_skin'];
$full_height = ($grid_height) ? 'height: '. $total_height . 'px;' : '';
$alter_class;
$col_class = '';
if ($grid_skin == 'repeat') {
    if ($alter_class == 1 || ($alter_class % 5) == 1) {
        $col_class = "two_column full-col repeat";
        $full_height .= 'clear: left;';
    } else {
        $col_class = "two_column full-col small-col repeat";
        if(($alter_class % 5) == 2 || ($alter_class % 5) == 4) {
            $full_height .= 'clear: left;';
        }
    }
} elseif ($grid_skin == 'default') {
    if ($alter_class == 1) {
        $col_class = "two_column full-col";
        $full_height .= 'clear: left;';
    } else {
        $col_class = "two_column small-col full-col";
        $full_height .= (($alter_class) % 2 == 0) ? 'clear: left;' : '';
    }
}

$div_height = ($full_height != '') ? 'style="'. $full_height .'"': '';
if (has_post_thumbnail()) {
    $post_thumbnail = 'full';
    $resizedImage = apply_filters('bdp_post_thumbnail_filter', get_the_post_thumbnail($post->ID, $post_thumbnail), $post->ID);
}

$class_name = "blog_template bdp_blog_template hoverbic";
if ($col_class != '') {
    $class_name .= ' ' . $col_class;
}
$bdp_post_image_link = (isset($bdp_settings['bdp_post_image_link']) && $bdp_settings['bdp_post_image_link'] == 0) ? false : true;
$display_filter_by = (isset($bdp_settings['display_filter_by']) && !empty($bdp_settings['display_filter_by'])) ? $bdp_settings['display_filter_by'] : '';
$category = '';
if(!empty($display_filter_by)) {
    $category_detail = wp_get_post_terms($post->ID, $display_filter_by);
    if(!empty($category_detail)){
        foreach($category_detail as $cd){
            $category .= $cd->slug . ' ';
        }
    } 
}
?>
<div class="<?php echo $class_name; ?> bdp_blog_single_post_wrapp <?php echo $category; ?>" <?php echo $div_height; ?>>
    <?php do_action('bdp_before_post_content'); ?>
    <div class="post_hentry">
        <?php
        $label_featured_post = (isset($bdp_settings['label_featured_post']) && $bdp_settings['label_featured_post'] != '') ? $bdp_settings['label_featured_post'] : '';
        if($label_featured_post != '' && is_sticky()) {
            ?> <div class="label_featured_post"><?php echo $label_featured_post; ?></div> <?php
        }
        if (class_exists('woocommerce') && $bdp_settings['custom_post_type'] == 'product') {
            if(isset($bdp_settings['display_sale_tag']) && $bdp_settings['display_sale_tag'] == 1) {
                $bdp_sale_tagtext_alignment = (isset($bdp_settings['bdp_sale_tagtext_alignment']) && $bdp_settings['bdp_sale_tagtext_alignment'] != '') ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top';
                echo '<div class="bdp_woocommerce_sale_wrap '.$bdp_sale_tagtext_alignment.'">';
                do_action('bdp_woocommerce_sale_tag');
                echo '</div>';
            }
        }
        ?>
        <div class="bdp-post-image">
            <?php
            if (has_post_thumbnail()) {
                echo ($bdp_post_image_link) ? '<a href="' . get_permalink($post->ID) . '">' : '';
                echo $resizedImage;
                echo ($bdp_post_image_link) ? '</a>' : '';
                if (isset($bdp_settings['pinterest_image_share']) && $bdp_settings['pinterest_image_share'] == 1 && isset($bdp_settings['social_share']) && $bdp_settings['social_share'] == 1) {
                    ?>
                    <div class="bdp-pinterest-share-image">
                        <?php $img_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>
                        <a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . get_permalink($post->ID) . '&media=' . $img_url; ?>"></a>
                    </div>
                    <?php
                }
            } elseif (isset($bdp_settings['bdp_default_image_id']) && $bdp_settings['bdp_default_image_id'] != '') {
                $thumbnail = wp_get_attachment_image($bdp_settings['bdp_default_image_id'], 'full');

                echo ($bdp_post_image_link) ? '<a href="' . get_permalink($post->ID) . '">' : '';
                echo apply_filters('bdp_post_thumbnail_filter', $thumbnail, $post->ID);
                echo ($bdp_post_image_link) ? '</a>' : '';

                if (isset($bdp_settings['pinterest_image_share']) && $bdp_settings['pinterest_image_share'] == 1 && isset($bdp_settings['social_share']) && $bdp_settings['social_share'] == 1) {
                    ?>
                    <div class="bdp-pinterest-share-image">
                        <?php
                        $img_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
                        ?>
                        <a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . get_permalink($post->ID) . '&media=' . $img_url; ?>"></a>
                    </div>
                    <?php
                }
            } else {
                $thumbnail = bdp_get_sample_image('boxy_clean', $post->ID);
                echo ($bdp_post_image_link) ? '<a href="' . get_permalink($post->ID) . '">' : '';
                echo apply_filters('bdp_post_thumbnail_filter', $thumbnail, $post->ID);
                echo ($bdp_post_image_link) ? '</a>' : '';
            }
            ?>
        </div>
        <div class="blog_header">
            <div class="header_wrapper">
                <div class="post-title">
                    <?php
                    $bdp_post_title_link = isset($bdp_settings['bdp_post_title_link']) ? $bdp_settings['bdp_post_title_link'] : 1;
                    if ($bdp_post_title_link == 1) {
                        ?>
                        <a href="<?php the_permalink(); ?>">
                        <?php } ?>
                        <?php
                        echo get_the_title();
                        if ($bdp_post_title_link == 1) {
                            ?>
                        </a>
                    <?php } ?>
                </div>

                <?php 
                if (class_exists('woocommerce') && $bdp_settings['custom_post_type'] == 'product') {
                    if(isset($bdp_settings['display_product_price']) == 1 || isset($bdp_settings['display_addtocart_button']) == 1 || class_exists('YITH_WCWL') || isset($bdp_settings['display_product_rating']) == 1) { 
                    ?>
                        <div class="bdp_woocommerce_meta_box">
                            <?php 
                                if(isset($bdp_settings['display_product_price']) && $bdp_settings['display_product_price'] == 1) {
                                    echo '<div class="bdp_woocommerce_price_wrap">';
                                    do_action('bdp_woocommerce_price');
                                    echo '</div>';
                                }
                                if(isset($bdp_settings['display_product_rating']) && $bdp_settings['display_product_rating'] == 1 ) {
                                    echo '<div class="bdp_woocommerce_star_wrap">';
                                    do_action('bdp_woocommerce_product_rating');
                                    echo '</div>';
                                }
                                if(class_exists('YITH_WCWL')){                                                                                                              
                                    if( isset($bdp_settings['display_addtowishlist_button']) &&  isset($bdp_settings['bdp_wishlistbutton_on']) && $bdp_settings['display_addtowishlist_button'] == 1 && $bdp_settings['bdp_wishlistbutton_on'] == 1) {
                                    $bdp_cart_wishlistbutton_alignment = (isset($bdp_settings['bdp_cart_wishlistbutton_alignment']) && !empty($bdp_settings['bdp_cart_wishlistbutton_alignment'])) ? $bdp_settings['bdp_cart_wishlistbutton_alignment'] : '0';
                                    $bdp_cartwishlist_wrapp = '';
                                    if($bdp_cart_wishlistbutton_alignment != '') {
                                        $bdp_cartwishlist_wrapp = 'bdp_cartwishlist_wrapp';
                                    }
                                    echo '<div class="bdp_wishlistbutton_on_same_line '.$bdp_cartwishlist_wrapp.'">';
                                }
                                }
                                if(isset($bdp_settings['display_addtocart_button']) && $bdp_settings['display_addtocart_button'] == 1) {
                                    echo '<div class="bdp_woocommerce_add_to_cart_wrap">';
                                    do_action('bdp_woocommerce_add_to_cart');
                                    echo '</div>';
                                }
                                if(class_exists('YITH_WCWL')){
                                    if(isset($bdp_settings['display_addtowishlist_button']) && $bdp_settings['display_addtowishlist_button'] == 1) {
                                        echo '<div class="bdp_woocommerce_add_to_wishlist_wrap">';
                                        do_action('bdp_woocommerce_add_to_wishlist');
                                        echo '</div>';
                                    }
                                }
                                if(class_exists('YITH_WCWL')){
                                    if( isset($bdp_settings['display_addtowishlist_button']) &&  isset($bdp_settings['bdp_wishlistbutton_on']) && $bdp_settings['display_addtowishlist_button'] == 1 && $bdp_settings['bdp_wishlistbutton_on'] == 1) {
                                        echo '</div>';
                                    }
                                }
                                
                            ?>
                        </div>
                    <?php }
                } 
                if ($bdp_settings['display_author'] == 1 || $bdp_settings['display_date'] == 1 || $bdp_settings['display_comment_count'] == 1 || $bdp_settings['display_postlike'] == 1) {
                    ?>
                    <div class="metadatabox">
                        <?php
                        if ($bdp_settings['display_author'] == 1 || $bdp_settings['display_date'] == 1) {
                            echo '<div class="metabox-top">';
                            if ($bdp_settings['display_author'] == 1) {
                                $author_link = (isset($bdp_settings['disable_link_author']) && $bdp_settings['disable_link_author'] == 1) ? false : true;
                                $author_class = (bdp_get_first_embed_media($post->ID, $bdp_settings) && $bdp_settings['rss_use_excerpt'] == 1 && get_post_format($post->ID) != 'gallery') ? 'class="post-video-format"' : '';
                                ?>
                                <div class="mauthor">
                                    <i class="fas fa-user"></i>
                                    <span class="author">
                                    <?php echo bdp_get_post_auhtors($post->ID, $bdp_settings); ?>
                                    </span>
                                </div>
                                <?php
                            }
                            if ($bdp_settings['display_date'] == 1) {
                                $date_link = (isset($bdp_settings['disable_link_date']) && $bdp_settings['disable_link_date'] == 1) ? false : true;
                                ?>
                                <div class="post-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?php
                                    $date_format = (isset($bdp_settings['post_date_format']) && $bdp_settings['post_date_format'] != 'default') ? $bdp_settings['post_date_format'] : get_option('date_format');
                                    $bdp_date = (isset($bdp_settings['dsiplay_date_from']) && $bdp_settings['dsiplay_date_from'] == 'modify') ? apply_filters('bdp_date_format', get_post_modified_time($date_format, $post->ID), $post->ID) : apply_filters('bdp_date_format', get_the_time($date_format, $post->ID), $post->ID);
                                    $ar_year = get_the_time('Y');
                                    $ar_month = get_the_time('m');
                                    $ar_day = get_the_time('d');

                                    echo ($date_link) ? '<a href="' . get_day_link($ar_year, $ar_month, $ar_day) . '">' : '';
                                    echo $bdp_date;
                                    echo ($date_link) ? '</a>' : '';
                                    ?>

                                </div>
                                <?php
                            }
                            echo '</div>';
                        }
                        if ($bdp_settings['display_comment_count'] == 1 || $bdp_settings['display_postlike'] == 1) {
                            echo '<div class="metabox-bottom">';
                            if ($bdp_settings['display_comment_count'] == 1) {
                                ?>
                                <div class="post-comment">
                                    <i class="fas fa-comment"></i>
                                    <?php
                                    if (isset($bdp_settings['disable_link_comment']) && $bdp_settings['disable_link_comment'] == 1) {
                                        comments_number(__('Leave a Comment', BLOGDESIGNERPRO_TEXTDOMAIN), __('1 comment', BLOGDESIGNERPRO_TEXTDOMAIN), '% ' . __('comments', BLOGDESIGNERPRO_TEXTDOMAIN));
                                    } else {
                                        comments_popup_link(__('Leave a Comment', BLOGDESIGNERPRO_TEXTDOMAIN), __('1 comment', BLOGDESIGNERPRO_TEXTDOMAIN), '% ' . __('comments', BLOGDESIGNERPRO_TEXTDOMAIN), 'comments-link', __('Comments are off', BLOGDESIGNERPRO_TEXTDOMAIN));
                                    }
                                    ?>
                                </div>
                                <?php
                            }

                            if (isset($bdp_settings['display_postlike']) && $bdp_settings['display_postlike'] == 1) {
                                echo '<div class="postlike_btn">';
                                echo do_shortcode('[likebtn_shortcode]');
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <?php
                }
                if ($bdp_settings['custom_post_type'] == 'post') {
                    if ($bdp_settings['display_category'] == 1) {
                        ?>
                        <div class="category-link">
                            <i class="fas fa-folder"></i>
                            <?php
                            $categories_list = get_the_category_list(', ');
                            $categories_link = (isset($bdp_settings['disable_link_category']) && $bdp_settings['disable_link_category'] == 1) ? true : false;
                            if ($categories_link) {
                                $categories_list = strip_tags($categories_list);
                            }
                            if ($categories_list):
                                echo ' ';
                                print_r($categories_list);
                                $show_sep = true;
                            endif;
                            ?>
                        </div>
                        <?php
                    }

                    if ($bdp_settings['display_tag'] == 1) {
                        $tags_list = get_the_tag_list('', ', ');
                        $tag_link = (isset($bdp_settings['disable_link_tag']) && $bdp_settings['disable_link_tag'] == 1) ? true : false;
                        if ($tag_link) {
                            $tags_list = strip_tags($tags_list);
                        }
                        if ($tags_list):
                            ?>
                            <div class="tags">
                                <i class="fas fa-bookmark"></i>&nbsp;
                                <?php
                                print_r($tags_list);
                                $show_sep = true;
                                ?>
                            </div>
                            <?php
                        endif;
                    }
                } else {
                    $taxonomy_names = get_object_taxonomies($bdp_settings['custom_post_type'], 'objects');
                    $taxonomy_names = apply_filters('bdp_hide_taxonomies',$taxonomy_names);
                    foreach ($taxonomy_names as $taxonomy_single) {
                        $taxonomy = $taxonomy_single->name;
                        $sep = 1;
                        if ($bdp_settings["display_taxonomy_" . $taxonomy] == 1) {
                            $term_list = wp_get_post_terms(get_the_ID(), $taxonomy, array("fields" => "all"));
                            $taxonomy_link = (isset($bdp_settings['disable_link_taxonomy_' . $taxonomy]) && $bdp_settings['disable_link_taxonomy_' . $taxonomy] == 1) ? false : true;
                            if (isset($taxonomy)) {
                                if (isset($term_list) && !empty($term_list)) {
                                    ?>
                                    <div class="category-link">
                                        <i class="fas fa-folder"></i> 
                                        <strong><?php echo $taxonomy_single->label; ?>&nbsp;:&nbsp;</strong>
                                        <?php
                                        foreach ($term_list as $term_nm) {
                                            $term_link = get_term_link($term_nm);

                                            if ($sep != 1) {
                                                ?><span class="seperater"><?php echo ', '; ?></span><?php
                                            }
                                            echo ($taxonomy_link) ? '<a href="' . $term_link . '">' : '';
                                            echo $term_nm->name;
                                            echo ($taxonomy_link) ? '</a>' : '';
                                            $sep++;
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                            }
                        }
                    }
                }
                  
                bdp_get_social_icons($bdp_settings);
                ?>
            </div>
        </div>
    </div>
    <?php do_action('bdp_after_post_content'); ?>
</div>
<?php
do_action('bdp_separator_after_post');
