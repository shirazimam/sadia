<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/navia.php.
 * @author  Solwin Infotech
 * @version 2.0
 */

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $post;

if (isset($bdp_settings['blog_unique_design']) && $bdp_settings['blog_unique_design'] != "") {
    $blog_unique_design = $bdp_settings['blog_unique_design'];
} else {
    $blog_unique_design = 0;
}
$unique_design_option = isset($bdp_settings['unique_design_option']) ? $bdp_settings['unique_design_option'] : '';
if ($blog_unique_design == 1) {
    if ($unique_design_option == 'first_post' && $prev_year == 0 && $paged == 1) {
        $class_name = "bdp_blog_template blog_template navia first_post";
    } elseif ($unique_design_option == 'first_post' && $prev_year != 0 && $paged == 1) {
        $class_name = "bdp_blog_template blog_template navia";
    } elseif ($unique_design_option == 'first_post' && $prev_year != 0 && $paged != 1) {
        $class_name = "bdp_blog_template blog_template navia";
    }if ($unique_design_option == 'featured_posts' && $prev_year == 0 && $paged == 1) {
        $class_name = "bdp_blog_template blog_template navia first_post";
    } elseif ($unique_design_option == 'featured_posts' && $prev_year != 0 && $paged == 1) {
        $class_name = "bdp_blog_template blog_template navia";
    } elseif ($unique_design_option == 'featured_posts' && $prev_year != 0 && $paged != 1) {
        $class_name = "bdp_blog_template blog_template navia";
    }
} else {
    $class_name = "bdp_blog_template blog_template navia";
}
if ($alter_class != '') {
    $class_name .= " " . $alter_class;
}

$image_hover_effect = '';
if (isset($bdp_settings['bdp_image_hover_effect']) && $bdp_settings['bdp_image_hover_effect'] == 1) {
    $image_hover_effect = (isset($bdp_settings['bdp_image_hover_effect_type']) && $bdp_settings['bdp_image_hover_effect_type'] != '') ? $bdp_settings['bdp_image_hover_effect_type'] : '';
}
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
<div class="<?php echo $class_name; ?> bdp_blog_single_post_wrapp <?php echo $category; ?>">
    <?php do_action('bdp_before_post_content'); ?>
    <div class="navia-wrap">
        <div class="navia-img-wrap bdp-post-image <?php if (!has_post_thumbnail() && $bdp_settings['bdp_default_image_id'] == '') echo 'no-bdp-post-image'; ?>">
            <?php
            $label_featured_post = (isset($bdp_settings['label_featured_post']) && $bdp_settings['label_featured_post'] != '') ? $bdp_settings['label_featured_post'] : '';
            if($label_featured_post != '' && is_sticky()) {
                ?> <div class="label_featured_post"><?php echo $label_featured_post; ?></div> <?php
            }
            ?>
            <?php
            $display_date = $bdp_settings['display_date'];
            if ($display_date == 1) {
                $date_link = (isset($bdp_settings['disable_link_date']) && $bdp_settings['disable_link_date'] == 1) ? false : true;
                $date_format = (isset($bdp_settings['post_date_format']) && $bdp_settings['post_date_format'] != 'default') ? $bdp_settings['post_date_format'] : get_option('date_format');
                $bdp_date = (isset($bdp_settings['dsiplay_date_from']) && $bdp_settings['dsiplay_date_from'] == 'modify') ? apply_filters('bdp_date_format', get_post_modified_time($date_format, $post->ID), $post->ID) : apply_filters('bdp_date_format', get_the_time($date_format, $post->ID), $post->ID);
                $ar_year = get_the_time('Y');
                $ar_month = get_the_time('m');
                $ar_day = get_the_time('d');

                echo ($date_link) ? '<a class="mdate" href="' . get_day_link($ar_year, $ar_month, $ar_day) . '">' : '';
                echo $bdp_date;
                echo ($date_link) ? '</a>' : '';
            }
            if (isset($bdp_settings['display_postlike']) && $bdp_settings['display_postlike'] == 1) {
                echo do_shortcode('[likebtn_shortcode]');
            }
            if (bdp_get_first_embed_media($post->ID, $bdp_settings) && $bdp_settings['rss_use_excerpt'] == 1) {
                ?>
                <div class="bdp-post-image post-video bdp-video">
                    <?php echo bdp_get_first_embed_media($post->ID, $bdp_settings); ?>
                </div>
                <?php
            } else {
                $post_thumbnail = 'full';
                $thumbnail = bdp_get_the_thumbnail($bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID);
                $bdp_post_image_link = (isset($bdp_settings['bdp_post_image_link']) && $bdp_settings['bdp_post_image_link'] == 0) ? false : true;

                echo '<figure class="' . $image_hover_effect . '">';
                echo ($bdp_post_image_link) ? '<a href="' . get_permalink($post->ID) . '" class="deport-img-link">' : '';
                echo apply_filters('bdp_post_thumbnail_filter', $thumbnail, $post->ID);
                echo ($bdp_post_image_link) ? '</a>' : '';

                if (isset($bdp_settings['pinterest_image_share']) && $bdp_settings['pinterest_image_share'] == 1 && (has_post_thumbnail() || (isset($bdp_settings['bdp_default_image_id']) && $bdp_settings['bdp_default_image_id'] != ''))) {
                    echo bdp_pinterest($post->ID);
                }
                if (class_exists('woocommerce') && $bdp_settings['custom_post_type'] == 'product') {
                    if(isset($bdp_settings['display_sale_tag']) && $bdp_settings['display_sale_tag'] == 1) {
                        $bdp_sale_tagtext_alignment = (isset($bdp_settings['bdp_sale_tagtext_alignment']) && $bdp_settings['bdp_sale_tagtext_alignment'] != '') ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top';
                    echo '<div class="bdp_woocommerce_sale_wrap '.$bdp_sale_tagtext_alignment.'">';
                        do_action('bdp_woocommerce_sale_tag');
                        echo '</div>';
                    }
                }
                echo '</figure>';
            }
            ?>
        </div>
        <div class="navia-content-wrap">
            <div class="post-metadata"><?php
                $display_author = $bdp_settings['display_author'];
                if ($display_author == 1) {
                    $author_link = (isset($bdp_settings['disable_link_author']) && $bdp_settings['disable_link_author'] == 1) ? false : true;
                    ?>
                    <span class="post-author <?php echo ($author_link) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
                        <?php echo bdp_get_post_auhtors($post->ID, $bdp_settings); ?>
                    </span>
                    <?php
                }
                if ($bdp_settings['custom_post_type'] == 'post') {
                    if ($bdp_settings['display_category'] == 1) {
                        $categories_list = get_the_category_list(', ');
                        $categories_link = (isset($bdp_settings['disable_link_category']) && $bdp_settings['disable_link_category'] == 1) ? true : false;
                        $categories_class = ($categories_link) ? 'bdp_no_links' : 'bdp_has_links';
                        echo '<span class="post-category '. $categories_class .'">';
                        if ($categories_link) {
                            $categories_list = strip_tags($categories_list);
                        }
                        if ($categories_list):
                            echo ' ';
                            print_r($categories_list);
                            $show_sep = true;
                        endif;
                        echo '</span>';
                    }
                } else if ($bdp_settings['custom_post_type'] == 'product') {
                    if (isset($bdp_settings['display_taxonomy_product_cat']) && $bdp_settings['display_taxonomy_product_cat'] == 1) {
                        $categories_link = (isset($bdp_settings['disable_link_taxonomy_product_cat']) && $bdp_settings['disable_link_taxonomy_product_cat'] == 1) ? false : true;
                        $product_categories = wp_get_post_terms( $post->ID,'product_cat', array( 'hide_empty' => 'false') );
                        $sep = 1;
                        ?>
                              <span class="post-category <?php echo ($categories_link) ? ' categories_link' : ''; ?>">
                                <?php
                                    foreach ($product_categories as $category) {
                                        if ($sep != 1) {
                                            ?><span class="seperater"><?php echo ', '; ?></span><?php
                                        }
                                        echo ($categories_link) ? '<a href="' . get_term_link($category->term_id) . '">' : '';
                                        echo $category->name;
                                        echo ($categories_link) ? '</a>' : '';
                                        $sep++;
                                    }
                                ?>
                           </span>
                        <?php
                    }
                }
                if ($bdp_settings['display_comment_count'] == 1) {
                    ?>
                    <span class="metacomments" <?php if (!has_post_thumbnail() && $bdp_settings['bdp_default_image_id'] == '') echo 'style="margin-right:0"'; ?>>
                        <?php
                        if (isset($bdp_settings['disable_link_comment']) && $bdp_settings['disable_link_comment'] == 1) {
                            comments_number(__('No Comments', BLOGDESIGNERPRO_TEXTDOMAIN), '1 ' . __('comment', BLOGDESIGNERPRO_TEXTDOMAIN), '% ' . __('comments', BLOGDESIGNERPRO_TEXTDOMAIN));
                        } else {
                            comments_popup_link(__('Leave a Comment', BLOGDESIGNERPRO_TEXTDOMAIN), __('1 comment', BLOGDESIGNERPRO_TEXTDOMAIN), '% ' . __('comments', BLOGDESIGNERPRO_TEXTDOMAIN), 'comments-link', __('Comments are off', BLOGDESIGNERPRO_TEXTDOMAIN));
                        }
                        ?>
                    </span>
                <?php } ?>
            </div>
            <div class="navia-title-area">
                <h2 class="post-title">
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
                </h2>
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
            } ?>
            <div class="post-content-area <?php if (!has_post_thumbnail() && $bdp_settings['bdp_default_image_id'] == '') echo 'navia_no_image'; ?>">
                <div class="post_content">
                    <?php
                    echo bdp_get_content($post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings);
                    $read_more_on = isset($bdp_settings['read_more_on']) ? $bdp_settings['read_more_on'] : 2;
                    $read_more_link = isset($bdp_settings['read_more_link']) ? $bdp_settings['read_more_link'] : 1;
                    if ($bdp_settings['rss_use_excerpt'] == 1 && $read_more_link == 1):
                        $readmoretxt =  $bdp_settings['txtReadmoretext'] != '' ? $bdp_settings['txtReadmoretext'] : __('Read More', BLOGDESIGNERPRO_TEXTDOMAIN);
                        $post_link = get_permalink($post->ID);
                        if(isset($bdp_settings['post_link_type']) && $bdp_settings['post_link_type'] == 1) {
                            $post_link = (isset($bdp_settings['custom_link_url']) && $bdp_settings['custom_link_url'] != '') ? $bdp_settings['custom_link_url'] : get_permalink($post->ID);
                        }
                        if($read_more_on == 2){
                            echo '<div class="read-more-div">';
                        }
                        echo '<a class="more-tag" href="' . $post_link . '">' . $readmoretxt . ' </a>';
                        if($read_more_on == 2){
                            echo '</div>';
                        }
                    endif;
                    ?>
                </div><?php
                if ($bdp_settings['custom_post_type'] == 'post') {
                    if ($bdp_settings['display_tag'] == 1) {
                        $tags_list = get_the_tag_list('', ', ');
                        $tag_link = (isset($bdp_settings['disable_link_tag']) && $bdp_settings['disable_link_tag'] == 1) ? true : false;
                        if ($tag_link) {
                            $tags_list = strip_tags($tags_list);
                        }
                        if ($tags_list):
                            ?>
                            <div class="tags <?php echo ($tag_link) ? 'bdp_no_links' : 'bdp_has_link'?>">
                                <i class="fas fa-tags"></i><?php
                                print_r($tags_list);
                                $show_sep = true;
                                ?>
                            </div><?php
                        endif;
                    }
                }
                if ($bdp_settings['custom_post_type'] != 'post') {
                    $taxonomy_names = get_object_taxonomies($bdp_settings['custom_post_type'], 'objects');
                    $taxonomy_names = apply_filters('bdp_hide_taxonomies',$taxonomy_names);
                    foreach ($taxonomy_names as $taxonomy_single) {
                        $taxonomy = $taxonomy_single->name;
                        $sep = 1;
                        if ($bdp_settings["display_taxonomy_" . $taxonomy] == 1) {
                            $term_list = wp_get_post_terms(get_the_ID(), $taxonomy, array("fields" => "all"));
                            $taxonomy_link = (isset($bdp_settings['disable_link_taxonomy_' . $taxonomy]) && $bdp_settings['disable_link_taxonomy_' . $taxonomy] == 1) ? false : true;
                            if (isset($taxonomy) && $taxonomy != 'product_cat') {
                                if (isset($term_list) && !empty($term_list)) {
                                    ?>
                                    <div class="tags <?php echo ($taxonomy_link) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
                                        <span class="link-lable"> <i class="fas fa-folder-open"></i> <?php echo $taxonomy_single->label; ?>&nbsp;:&nbsp;</span><?php
                                        foreach ($term_list as $term_nm) {
                                            $term_link = get_term_link($term_nm);
                                            if ($sep != 1) {
                                                ?>,&nbsp;<?php
                                            }
                                            echo ($taxonomy_link) ? '<a href="' . $term_link . '">' : '';
                                            echo $term_nm->name;
                                            echo ($taxonomy_link) ? '</a>' : '';
                                            $sep++;
                                        }
                                        ?>
                                    </div><?php
                                }
                            }
                        }
                    }
                }
                  
                ?>
                <?php bdp_get_social_icons($bdp_settings); ?>
            </div>
        </div>
    </div>
    <?php do_action('bdp_after_post_content'); ?>
</div><?php
do_action('bdp_separator_after_post');
