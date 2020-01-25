<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/minimal.php.
 * @author  Solwin Infotech
 * @version 2.0
 */

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

global $post;
$post_format = get_post_format($post->ID);
$image_hover_effect = 'bdp-post-image ';
if (isset($bdp_settings['bdp_image_hover_effect']) && $bdp_settings['bdp_image_hover_effect'] == 1) {
    $image_hover_effect .= (isset($bdp_settings['bdp_image_hover_effect_type']) && $bdp_settings['bdp_image_hover_effect_type'] != '') ? $bdp_settings['bdp_image_hover_effect_type'] : '';
}
$social_share = (isset($bdp_settings['social_share']) && $bdp_settings['social_share'] == 0) ? false : true;
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
<div class="bdp_blog_template blog_template minimal-post-wrapper bdp_blog_single_post_wrapp <?php echo $category; ?>">
    <div class="minimal-post-container">
        <?php
        $label_featured_post = (isset($bdp_settings['label_featured_post']) && $bdp_settings['label_featured_post'] != '') ? $bdp_settings['label_featured_post'] : '';
        if($label_featured_post != '' && is_sticky()) {
            ?> <div class="label_featured_post"><?php echo $label_featured_post; ?></div> <?php
        }
        ?>
        <?php
        $post_thumbnail = 'full';
        $thumbnail = bdp_get_the_thumbnail($bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID);
        $bdp_post_image_link = (isset($bdp_settings['bdp_post_image_link']) && $bdp_settings['bdp_post_image_link'] == 0) ? false : true;
        if (bdp_get_first_embed_media($post->ID, $bdp_settings) && $bdp_settings['rss_use_excerpt'] == 1) {
            echo '<div class="bdp-post-image post-video bdp-video">';
            echo bdp_get_first_embed_media($post->ID, $bdp_settings);
            echo '</div>';
        } else {
            echo '<figure class="' . $image_hover_effect . '">';
            echo ($bdp_post_image_link) ? '<a href="' . get_permalink($post->ID) . '" class="minimal-img-link">' : '';
            echo apply_filters('bdp_post_thumbnail_filter', $thumbnail, $post->ID);
            echo ($bdp_post_image_link) ? '</a>' : '';

            if (isset($bdp_settings['pinterest_image_share']) && $bdp_settings['pinterest_image_share'] == 1 && isset($bdp_settings['social_share']) && $bdp_settings['social_share'] == 1) {
                ?>
                <div class="bdp-pinterest-share-image">
                    <?php $img_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>
                    <a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . get_permalink($post->ID) . '&media=' . $img_url; ?>"></a>
                </div>
                <?php
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
        <div class="minimal-entry">
            <div class="minimal-content-cover">
                <?php
                if($bdp_settings['custom_post_type'] == 'post'){
                    if ($bdp_settings['display_category'] == 1) {
                        ?>
                        <div class="post-categories">
                            <?php
                            $categories_list = get_the_category_list(', ');
                            $categories_link = (isset($bdp_settings['disable_link_category']) && $bdp_settings['disable_link_category'] == 1) ? true : false;
                            if ($categories_link) {
                                $categories_list = strip_tags($categories_list);
                            }
                            if ($categories_list) {
                                print_r($categories_list);
                            }
                            ?>
                        </div>
                        <?php
                    }
                } else if ($bdp_settings['custom_post_type'] == 'product') {
                    if (isset($bdp_settings['display_taxonomy_product_cat']) && $bdp_settings['display_taxonomy_product_cat'] == 1) {
                        $categories_link = (isset($bdp_settings['disable_link_taxonomy_product_cat']) && $bdp_settings['disable_link_taxonomy_product_cat'] == 1) ? false : true;
                        $product_categories = wp_get_post_terms( $post->ID,'product_cat', array( 'hide_empty' => 'false') );
                        $sep = 1;
                        ?>
                            <div class="post-categories">
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

                <div class="post-title">
                    <h2>
                    <?php
                    $bdp_post_title_link = isset($bdp_settings['bdp_post_title_link']) ? $bdp_settings['bdp_post_title_link'] : 1;
                    if ($bdp_post_title_link == 1) {
                        echo '<a href="' . get_the_permalink() . '" title="' . get_the_title() . '">';
                    }

                    echo get_the_title();

                    if ($bdp_post_title_link == 1) {
                        echo '</a>';
                    }
                    ?>
                    </h2>
                </div>

                <?php if ($bdp_settings['display_author'] == 1 || $bdp_settings['display_date'] == 1) { ?>

                    <div class="post-meta">
                        <?php
                        if ($bdp_settings['display_author'] == 1) {
                            echo bdp_get_post_auhtors($post->ID, $bdp_settings);
                        }

                        if($bdp_settings['display_author'] == 1 && $bdp_settings['display_date'] == 1 ) {
                            echo '<span class="bdp-separator"> / </span>';
                        }

                        if ($bdp_settings['display_date'] == 1) {
                            $date_link = (isset($bdp_settings['disable_link_date']) && $bdp_settings['disable_link_date'] == 1) ? false : true;
                            $date_format = (isset($bdp_settings['post_date_format']) && $bdp_settings['post_date_format'] != 'default') ? $bdp_settings['post_date_format'] : get_option('date_format');
                            $bdp_date = (isset($bdp_settings['dsiplay_date_from']) && $bdp_settings['dsiplay_date_from'] == 'modify') ? apply_filters('bdp_date_format', get_post_modified_time($date_format, $post->ID), $post->ID) : apply_filters('bdp_date_format', get_the_time($date_format, $post->ID), $post->ID);
                            $ar_year = get_the_time('Y');
                            $ar_month = get_the_time('m');
                            $ar_day = get_the_time('d');

                            echo ($date_link) ? '<a class="mdate" href="' . get_day_link($ar_year, $ar_month, $ar_day) . '">' : '';
                            echo $bdp_date;
                            echo ($date_link) ? '</a>' : '';
                            ?>
                            <?php
                        }
                        ?>
                    </div>
                <?php } ?>
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
                <?php
                $read_more_link = isset($bdp_settings['read_more_link']) ? $bdp_settings['read_more_link'] : 1;
                $read_more_on = isset($bdp_settings['read_more_on']) ? $bdp_settings['read_more_on'] : 2;
                if ($read_more_link == 1 && $bdp_settings['rss_use_excerpt'] == 1) {
                    $readmoretxt =  $bdp_settings['txtReadmoretext'] != '' ? $bdp_settings['txtReadmoretext'] : __('Read More', BLOGDESIGNERPRO_TEXTDOMAIN);
                    $post_link = get_permalink($post->ID);
                    if(isset($bdp_settings['post_link_type']) && $bdp_settings['post_link_type'] == 1) {
                        $post_link = (isset($bdp_settings['custom_link_url']) && $bdp_settings['custom_link_url'] != '') ? $bdp_settings['custom_link_url'] : get_permalink($post->ID);
                    }
                }
                if ($bdp_settings['txtExcerptlength'] > 0) {
                    ?>
                    <div class="post-content">
                        <?php echo bdp_get_content($post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings); ?>
                        <?php
                            if ($read_more_link == 1 && $bdp_settings['rss_use_excerpt'] == 1 && $read_more_on == 1) {
                            echo '<a class="more-tag" href="' . $post_link . '"> ' . $readmoretxt . ' </a>';
                            }
                        ?>
                    </div>
                    <?php
                }
                if ($read_more_link == 1 && $bdp_settings['rss_use_excerpt'] == 1 && $read_more_on == 2) {
                    ?>
                    <div class="read_more_div"> 
                        <?php
                            echo '<a class="more-tag" href="' . $post_link . '"> ' . $readmoretxt . ' </a>';
                            ?> 
                    </div>
                    <?php
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
                        <div class="tags">
                            <span class="link-lable"> <?php _e('Tags', BLOGDESIGNERPRO_TEXTDOMAIN); ?>&nbsp;:&nbsp; </span>
                            <?php
                            print_r($tags_list);
                            $show_sep = true;
                            ?>
                        </div>
                            <?php
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
                                    <div class="tags">
                                        <span class="link-lable"><?php echo $taxonomy_single->label; ?>&nbsp;:&nbsp;</span>
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
                                    <?php
                                }
                            }
                        }
                    }
                }
                  
                ?>
            </div>

            <?php if ($bdp_settings['display_comment_count'] == 1 || $bdp_settings['display_postlike'] == 1 || $social_share) { ?>
                <div class="minimal-footer">
                    <?php
                    if (isset($bdp_settings['display_postlike']) && $bdp_settings['display_postlike'] == 1) {
                        echo do_shortcode('[likebtn_shortcode]');
                    }

                    if ($bdp_settings['display_comment_count'] == 1) {
                        ?>
                        <span class="post-comment">
                            <i class="fas fa-comment"></i>
                            <?php
                            if (isset($bdp_settings['disable_link_comment']) && $bdp_settings['disable_link_comment'] == 1) {
                                comments_number('0', '1', '%');
                            } else {
                                comments_popup_link('0', '1', '%', 'comments-link', __('Comments are off', BLOGDESIGNERPRO_TEXTDOMAIN));
                            }
                            ?>
                        </span>
                        <?php
                    }

                    if($social_share) {
                        if (($bdp_settings['facebook_link'] == 1) || ($bdp_settings['twitter_link'] == 1) ||
                                ($bdp_settings['google_link'] == 1) || ($bdp_settings['linkedin_link'] == 1) ||
                                (isset($bdp_settings['email_link']) && $bdp_settings['email_link'] == 1) || ( $bdp_settings['pinterest_link'] == 1) ||
                                ( isset($bdp_settings['telegram_link']) && $bdp_settings['telegram_link'] == 1) ||
                                ( isset($bdp_settings['pocket_link']) && $bdp_settings['pocket_link'] == 1) ||
                                ( isset($bdp_settings['skype_link']) && $bdp_settings['skype_link'] == 1) ||
                                ( isset($bdp_settings['telegram_link']) && $bdp_settings['telegram_link'] == 1) ||
                                ( isset($bdp_settings['reddit_link']) && $bdp_settings['reddit_link'] == 1) ||
                                ( isset($bdp_settings['digg_link']) && $bdp_settings['digg_link'] == 1) ||
                                ( isset($bdp_settings['tumblr_link']) && $bdp_settings['tumblr_link'] == 1) ||
                                ( isset($bdp_settings['wordpress_link']) && $bdp_settings['wordpress_link'] == 1) ||
                                ( $bdp_settings['whatsapp_link'] == 1)) {
                                ?>
                                <span class='minimal-social-share-btn'>
                                    <a href="javascript:void(0)"> <i class="fas fa-plus"></i> </a>
                                </span>
                                <?php
                        }
                    }
                    ?>
                </div>
                <?php
            }

            if($social_share) {
                if (($bdp_settings['facebook_link'] == 1) || ($bdp_settings['twitter_link'] == 1) ||
                        ($bdp_settings['google_link'] == 1) || ($bdp_settings['linkedin_link'] == 1) ||
                        (isset($bdp_settings['email_link']) && $bdp_settings['email_link'] == 1) || ( $bdp_settings['pinterest_link'] == 1) ||
                        ( isset($bdp_settings['telegram_link']) && $bdp_settings['telegram_link'] == 1) ||
                        ( isset($bdp_settings['pocket_link']) && $bdp_settings['pocket_link'] == 1) ||
                        ( isset($bdp_settings['skype_link']) && $bdp_settings['skype_link'] == 1) ||
                        ( isset($bdp_settings['telegram_link']) && $bdp_settings['telegram_link'] == 1) ||
                        ( isset($bdp_settings['reddit_link']) && $bdp_settings['reddit_link'] == 1) ||
                        ( isset($bdp_settings['digg_link']) && $bdp_settings['digg_link'] == 1) ||
                        ( isset($bdp_settings['tumblr_link']) && $bdp_settings['tumblr_link'] == 1) ||
                        ( isset($bdp_settings['wordpress_link']) && $bdp_settings['wordpress_link'] == 1) ||
                        ( $bdp_settings['whatsapp_link'] == 1)) {
                            echo '<div class="minimal-social-cover">';
                            bdp_get_social_icons($bdp_settings);
                            ?>
                            <span class='minimal-social-share-btn-close'>
                                <a href="javascript:void(0)"> <i class="fas fa-times"></i> </a>
                            </span>
                            <?php
                            echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</div>

