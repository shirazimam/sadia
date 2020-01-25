<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/media-grid.php.
 * @author  Solwin Infotech
 * @version 2.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $post, $wp_query;
$no_image_post = '';
if (!has_post_thumbnail() || empty($bdp_settings['bdp_default_image_id'])) {
    $no_image_post = 'no_image_post';
}
if (isset($bdp_settings['blog_unique_design']) && $bdp_settings['blog_unique_design'] != "") {
    $blog_unique_design = $bdp_settings['blog_unique_design'];
} else {
    $blog_unique_design = 0;
}
$unique_design_option = isset($bdp_settings['unique_design_option']) ? $bdp_settings['unique_design_option'] : '';

$image_hover_effect = '';
if (isset($bdp_settings['bdp_image_hover_effect']) && $bdp_settings['bdp_image_hover_effect'] == 1) {
    $image_hover_effect = (isset($bdp_settings['bdp_image_hover_effect_type']) && $bdp_settings['bdp_image_hover_effect_type'] != '') ? $bdp_settings['bdp_image_hover_effect_type'] : '';
}

$column_setting = (isset($bdp_settings['column_setting']) && $bdp_settings['column_setting'] != '') ? $bdp_settings['column_setting'] : 2;

if ($blog_unique_design == 1 && $unique_design_option == 'first_post' && $alter_val == 1 && $prev_year == 0 && $paged == 1) {
    echo '<div class="media-grid-wrap first_post">';
} elseif ($blog_unique_design == 1 && $unique_design_option == 'featured_posts' && $alter_val <= $count_sticky && $prev_year == 0 && $paged == 1) {
    echo '<div class="media-grid-wrap first_post">';
} else {
    if ($blog_unique_design != 1 && $alter_val == 1) {
        echo '<div class="media-grid-wrapper">';
    } elseif ($paged > 1 && $alter_val == 1) {
        echo '<div class="media-grid-wrapper">';
    } elseif ($blog_unique_design == 1 && $unique_design_option == 'first_post' && $paged == 1) {
        if ($alter_val == 2 && $column_setting <= 2) {
            echo '<div class="media-grid-wrapper">';
        } elseif ($alter_val == 3 && $column_setting >= 3) {
            echo '<div class="media-grid-wrapper">';
        }
    } elseif ($blog_unique_design == 1 && $unique_design_option == 'featured_posts' && $paged == 1) {
        $count_sticky = $count_sticky + 1;
        if ($alter_val == $count_sticky) {
            echo '<div class="media-grid-wrapper">';
        }
    }
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

<div class="blog_template bdp_blog_template media-grid <?php echo get_post_format($post->ID); ?> bdp_blog_single_post_wrapp <?php echo $category; ?>">
    <?php do_action('bdp_before_post_content'); ?>
    <div class="post-body-div <?php echo $no_image_post; ?><?php
    if ($bdp_settings['custom_post_type'] == 'post' && $bdp_settings['display_category'] == 0) {
        echo ' category-not-visible';
    }
    ?>">
        <div class="bdp-post-image">
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

            if (bdp_get_first_embed_media($post->ID, $bdp_settings) && $bdp_settings['rss_use_excerpt'] == 1) {
                echo '<div class="post-video bdp-video">';
                echo bdp_get_first_embed_media($post->ID, $bdp_settings);
                echo '</div>';
            } else {
                echo '<figure class="' . $image_hover_effect . '">';
                $post_thumbnail = 'invert-grid-thumb';
                $thumbnail = bdp_get_the_thumbnail($bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID);
                $bdp_post_image_link = (isset($bdp_settings['bdp_post_image_link']) && $bdp_settings['bdp_post_image_link'] == 0) ? false : true;

                echo ($bdp_post_image_link) ? '<a href="' . get_permalink($post->ID) . '">' : '';
                echo apply_filters('bdp_post_thumbnail_filter', $thumbnail, $post->ID);
                echo ($bdp_post_image_link) ? '</a>' : '';

                if (isset($bdp_settings['pinterest_image_share']) && $bdp_settings['pinterest_image_share'] == 1 && has_post_thumbnail() && isset($bdp_settings['social_share']) && $bdp_settings['social_share'] == 1) {
                    echo bdp_pinterest($post->ID);
                }
                echo '</figure>';
            }
            if ($bdp_settings['custom_post_type'] == 'post') {
                if ($bdp_settings['display_category'] == 1) {
                    ?>
                    <?php
                    $categories_list = get_the_category_list(', ');
                    $categories_link = (isset($bdp_settings['disable_link_category']) && $bdp_settings['disable_link_category'] == 1) ? true : false;
                    if ($categories_link) {
                        $categories_list = strip_tags($categories_list);
                    }
                    if ($categories_list):
                        echo '<span class="category-link">';
                        echo ' ';
                        print_r($categories_list);
                        $show_sep = true;
                        echo '</span>';
                    endif;
                    ?>
                    <?php
                }
            } else if ($bdp_settings['custom_post_type'] == 'product') {
                if (isset($bdp_settings['display_taxonomy_product_cat']) && $bdp_settings['display_taxonomy_product_cat'] == 1) {
                    $categories_link = (isset($bdp_settings['disable_link_taxonomy_product_cat']) && $bdp_settings['disable_link_taxonomy_product_cat'] == 1) ? false : true;
                    $product_categories = wp_get_post_terms( $post->ID,'product_cat', array( 'hide_empty' => 'false') );
                    $sep = 1;
                    ?>
                        <div class="post-category">
                          <span class="category-link<?php echo ($categories_link) ? ' categories_link' : ''; ?>">
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
                       </span></div>
                    <?php
                }
            }
            ?>
        </div>
        <div class="content-container">
            <div class="shadow-box"></div>
            <div class="content-inner">
                <h2 class="post-title entry-title" title="<?php echo get_the_title(); ?>">
                    <?php
                    $bdp_post_title_link = isset($bdp_settings['bdp_post_title_link']) ? $bdp_settings['bdp_post_title_link'] : 1;
                    if ($bdp_post_title_link == 1) {
                        ?>
                        <a href="<?php the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
                        <?php } ?>
                        <?php
                        echo get_the_title();
                        if ($bdp_post_title_link == 1) {
                            ?>
                        </a>
                    <?php } ?>
                </h2>
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
                        if($read_more_on == 1){
                            ?>
                            <a class="more-tag" href="<?php echo $post_link; ?>">
                                <?php echo $readmoretxt; ?>
                            </a>
                            <?php
                        }
                        ?>
                    <?php endif; ?>
                </div>
                <?php 
                if ($bdp_settings['rss_use_excerpt'] == 1 && $read_more_link == 1 && $read_more_on == 2):
                    ?>
                    <div class="read-more">
                        <a class="more-tag" href="<?php echo $post_link; ?>">
                            <?php echo $readmoretxt; ?>
                        </a>
                    </div>
                <?php 
                endif;
                ?>
                <div class="metadatabox">
                    <?php
                    $display_author = $bdp_settings['display_author'];
                    $display_date = $bdp_settings['display_date'];
                    if($display_author == 1 || $display_date == 1)  {
                        ?><span><?php _e('Posted ', BLOGDESIGNERPRO_TEXTDOMAIN); ?></span><?php
                    }
                    if ($display_author == 1) {
                        ?>
                        <span class="post-author">
                            <span><?php _e('by', BLOGDESIGNERPRO_TEXTDOMAIN); ?>&nbsp;</span>
                            <?php echo bdp_get_post_auhtors($post->ID, $bdp_settings); ?>
                        </span>
                        <?php
                    }
                    if ($display_date == 1) {
                        $date_link = (isset($bdp_settings['disable_link_date']) && $bdp_settings['disable_link_date'] == 1) ? false : true;
                        $date_format = (isset($bdp_settings['post_date_format']) && $bdp_settings['post_date_format'] != 'default') ? $bdp_settings['post_date_format'] : get_option('date_format');
                        $bdp_date = (isset($bdp_settings['dsiplay_date_from']) && $bdp_settings['dsiplay_date_from'] == 'modify') ? apply_filters('bdp_date_format', get_post_modified_time($date_format, $post->ID), $post->ID) : apply_filters('bdp_date_format', get_the_time($date_format, $post->ID), $post->ID);
                        $ar_year = get_the_time('Y');
                        $ar_month = get_the_time('m');
                        $ar_day = get_the_time('d');
                        ?>
                        <span class="mdate"><span><?php _e('on', BLOGDESIGNERPRO_TEXTDOMAIN); ?></span>
                            <?php
                            echo ($date_link) ? '<a href="' . get_day_link($ar_year, $ar_month, $ar_day) . '">' : '';
                            echo $bdp_date;
                            echo ($date_link) ? '</a>' : '';
                            ?>
                        </span>
                        <?php
                    }
                    if ($bdp_settings['display_comment_count'] == 1) {
                        ?>
                        <span class="metacomments">
                            <i class="fas fa-comments"></i>
                            <?php
                            if (isset($bdp_settings['disable_link_comment']) && $bdp_settings['disable_link_comment'] == 1) {
                                comments_number('0', '1', '%');
                            } else {
                                comments_popup_link('0', '1', '%');
                            }
                            ?>
                        </span>
                        <?php
                    }
                    if (isset($bdp_settings['display_postlike']) && $bdp_settings['display_postlike'] == 1) {
                        echo do_shortcode('[likebtn_shortcode]');
                    }
                    if ($bdp_settings['custom_post_type'] == 'post') {
                        if ($bdp_settings['display_tag'] == 1) {
                            $tags_list = get_the_tag_list('', ', ');
                            $tag_link = (isset($bdp_settings['disable_link_tag']) && $bdp_settings['disable_link_tag'] == 1) ? true : false;
                            if ($tag_link) {
                                $tags_list = strip_tags($tags_list);
                            }
                            if ($tags_list):
                                ?>
                                <div class="tags <?php echo ($tag_link) ? 'bdp_no_links' : ''; ?>">
                                    <i class="fas fa-tag"></i>
                                    <?php
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
                                if (isset($taxonomy) && $taxonomy !='product_cat') {
                                    if (isset($term_list) && !empty($term_list)) {
                                        ?>
                                        <div class="taxonomies <?php echo $taxonomy; ?> <?php echo (!$taxonomy_link) ? 'bdp_no_links' : ''; ?>">
                                            <div class="tags <?php echo $no_image_post; ?>">
                                                <span class="link-lable"> <i class="fas fa-folder-open"></i> <?php echo $taxonomy_single->label; ?>&nbsp;:&nbsp;</span>
                                                <?php
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
                                            </div>
                                        </div><?php
                                    }
                                }
                            }
                        }
                    }
                      
                    ?>
                </div>
                <?php bdp_get_social_icons($bdp_settings); ?>
            </div>
        </div>
    </div>
    <?php do_action('bdp_after_post_content'); ?>
</div>
<?php
if ($blog_unique_design == 1 && $unique_design_option == 'first_post' && $prev_year == 0 && $paged == 1) {
    if ($column_setting >= 3 && $alter_val == 2) {
        echo '</div>';
    } elseif ($column_setting <= 2 && $alter_val == 1) {
        echo '</div>';
    }
} elseif ($blog_unique_design == 1 && $unique_design_option == 'featured_posts' && $alter_val <= $count_sticky && $prev_year == 0 && $paged == 1) {
    echo '</div>';
} elseif ($prev_year == 1 && $wp_query->post_count == $alter_val) {
    echo '</div>';
} elseif ($blog_unique_design != 1 && $wp_query->post_count == $alter_val) {
    echo '</div>';
}