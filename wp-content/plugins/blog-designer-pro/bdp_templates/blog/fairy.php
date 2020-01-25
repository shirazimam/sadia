<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/fairy.php.
 * @author  Solwin Infotech
 * @version 2.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $post;
$class_name = "bdp_blog_template blog_template fairy";

if ($alter_class != '') {
    $class_name .= " " . $alter_class;
}

$image_hover_effect = '';
if (isset($bdp_settings['bdp_image_hover_effect']) && $bdp_settings['bdp_image_hover_effect'] == 1) {
    $image_hover_effect = (isset($bdp_settings['bdp_image_hover_effect_type']) && $bdp_settings['bdp_image_hover_effect_type'] != '') ? $bdp_settings['bdp_image_hover_effect_type'] : '';
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
<div class="<?php echo $class_name; ?> bdp_blog_single_post_wrapp <?php echo $category; ?>">
    <?php do_action('bdp_before_post_content'); ?>
    <div class="bdp-post-image">
        <?php

        $post_thumbnail = 'fairy-thumbnail';
        $thumbnail = bdp_get_the_thumbnail($bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID);
        $bdp_post_image_link = (isset($bdp_settings['bdp_post_image_link']) && $bdp_settings['bdp_post_image_link'] == 0) ? false : true;

        if (bdp_get_first_embed_media($post->ID, $bdp_settings) && $bdp_settings['rss_use_excerpt'] == 1) {
            ?>
            <div class="post-video <?php echo (get_post_format() == 'video') ? 'bdp-video' : '';?>">
                <?php echo bdp_get_first_embed_media($post->ID, $bdp_settings); ?>
            </div>
            <?php
        } else {
            echo '<div class="post-thumbnail-cover">';

            echo ($bdp_post_image_link) ? '<a href="' . get_permalink($post->ID) . '" class="fairy-img-link">' : '';
            echo apply_filters('bdp_post_thumbnail_filter', $thumbnail, $post->ID);
            echo ($bdp_post_image_link) ? '</a>' : '';

            if (isset($bdp_settings['pinterest_image_share']) && $bdp_settings['pinterest_image_share'] == 1 && !empty($thumbnail)) {
                ?>
                <div class="bdp-pinterest-share-image">
                    <?php
                    $img_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
                    ?>
                    <a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . get_permalink($post->ID) . '&media=' . $img_url; ?>"></a>
                </div>
                <?php
            }
            echo '<div class="post-img-overlay"></div>';
            echo '</div>';

            if($bdp_settings['display_author'] == 1 || $bdp_settings['display_date'] == 1) {
                echo '<div class="post-meta-cover">';
                echo '<div class="post-meta">';
                    if ($bdp_settings['display_author'] == 1) {
                        echo '<div class="post_author">';
                        $author_link = (isset($bdp_settings['disable_link_author']) && $bdp_settings['disable_link_author'] == 1) ? false : true;

                        echo '<div class="author-avatar">';
                        echo get_avatar(get_the_author_meta('ID'), 60);
                        echo '</div>';

                        echo '<div class="author-name">';
                        echo bdp_get_post_auhtors($post->ID, $bdp_settings);
                        echo '</div>';

                        echo '</div>';
                    }

                    if ($bdp_settings['display_date'] == 1) {
                        $date_link = (isset($bdp_settings['disable_link_date']) && $bdp_settings['disable_link_date'] == 1) ? false : true;
                        $date_format = (isset($bdp_settings['post_date_format']) && $bdp_settings['post_date_format'] != 'default') ? $bdp_settings['post_date_format'] : get_option('date_format');
                        $bdp_date = (isset($bdp_settings['dsiplay_date_from']) && $bdp_settings['dsiplay_date_from'] == 'modify') ? apply_filters('bdp_date_format', get_post_modified_time($date_format, $post->ID), $post->ID) : apply_filters('bdp_date_format', get_the_time($date_format, $post->ID), $post->ID);
                        $ar_year = get_the_time('Y');
                        $ar_month = get_the_time('m');
                        $ar_day = get_the_time('d');
                        ?>
                        <span class="mdate">
                            <?php
                            echo ($date_link) ? '<a href="' . get_day_link($ar_year, $ar_month, $ar_day) . '">' : '';
                            echo $bdp_date;
                            echo ($date_link) ? '</a>' : '';
                            ?>
                        </span>
                        <?php
                    }
                echo '</div>';
                echo '</div>';
            }

            ?>

            <h2 class="post_title">
                <?php
                $bdp_post_title_link = isset($bdp_settings['bdp_post_title_link']) ? $bdp_settings['bdp_post_title_link'] : 1;
                echo ($bdp_post_title_link == 1) ? '<a href="' . get_the_permalink() . '" title="' . get_the_title() . '">' : '';
                echo get_the_title();
                echo ($bdp_post_title_link == 1) ? '</a>' : '';
                ?>
            </h2>
            <?php
            if (class_exists('woocommerce') && $bdp_settings['custom_post_type'] == 'product') {
                if(isset($bdp_settings['display_sale_tag']) && $bdp_settings['display_sale_tag'] == 1) {
                    $bdp_sale_tagtext_alignment = (isset($bdp_settings['bdp_sale_tagtext_alignment']) && $bdp_settings['bdp_sale_tagtext_alignment'] != '') ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top';
                    echo '<div class="bdp_woocommerce_sale_wrap '.$bdp_sale_tagtext_alignment.'">';
                    do_action('bdp_woocommerce_sale_tag');
                    echo '</div>';
                }
            }
        }
        ?>

    </div>

    <div class="fairy_wrap">
        <?php
        $label_featured_post = (isset($bdp_settings['label_featured_post']) && $bdp_settings['label_featured_post'] != '') ? $bdp_settings['label_featured_post'] : '';
        if($label_featured_post != '' && is_sticky()) {
            ?> <div class="label_featured_post"><?php echo $label_featured_post; ?></div> <?php
        }
        ?>
        <div class="post_content_area">
            <?php
            if (bdp_get_first_embed_media($post->ID, $bdp_settings)) {
                ?>
                <h2 class="post_title">
                    <?php
                    $bdp_post_title_link = isset($bdp_settings['bdp_post_title_link']) ? $bdp_settings['bdp_post_title_link'] : 1;
                    echo ($bdp_post_title_link == 1) ? '<a href="' . get_the_permalink() . '" title="' . get_the_title() . '">' : '';
                    echo get_the_title();
                    echo ($bdp_post_title_link == 1) ? '</a>' : '';
                    ?>
                </h2>
                <?php
            }

            if ($bdp_settings['custom_post_type'] == 'post') {
                if ($bdp_settings['display_category'] == 1) {
                    $categories_list = get_the_category_list(', ');
                    $categories_link = (isset($bdp_settings['disable_link_category']) && $bdp_settings['disable_link_category'] == 1) ? true : false;
                    if ($categories_link) {
                        $categories_list = strip_tags($categories_list);
                    }
                    if ($categories_list):
                        ?>
                        <span class="fairy-category-text<?php echo ($categories_link) ? ' categories_link' : ''; ?>"><?php
                            echo ' ';
                            print_r($categories_list);
                            $show_sep = true;
                        ?></span>
                        <?php
                    endif;
                }
            } else {
                $taxonomy_names = get_object_taxonomies($bdp_settings['custom_post_type'], 'objects');
                $taxonomy_names = apply_filters('bdp_hide_taxonomies',$taxonomy_names);
                foreach ($taxonomy_names as $taxonomy_single) {
                    $sep = 1;
                    $taxonomy = $taxonomy_single->name;
                    if ($bdp_settings["display_taxonomy_" . $taxonomy] == 1) {
                        $term_list = wp_get_post_terms(get_the_ID(), $taxonomy, array("fields" => "all"));
                        $taxonomy_link = (isset($bdp_settings['disable_link_taxonomy_' . $taxonomy]) && $bdp_settings['disable_link_taxonomy_' . $taxonomy] == 1) ? false : true;
                        if (isset($taxonomy)) {
                            if (isset($term_list) && !empty($term_list)) {
                                $class = ($taxonomy_link) ? 'bdp_has_links' : 'bdp_no_links';
                                echo '<div class="custom-categories '. $class .'">';
                                ?>
                                <span class="link-lable"><?php echo $taxonomy_single->label; ?>:&nbsp;</span>
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
                                echo '</div>';
                            }
                        }
                    }
                }
            }
            ?>
            <div class="metadatabox"><?php
                if (bdp_get_first_embed_media($post->ID, $bdp_settings)) {
                    $display_author = $bdp_settings['display_author'];
                    $display_date = $bdp_settings['display_date'];
                    if ($display_author == 1) {
                        $author_link = (isset($bdp_settings['disable_link_author']) && $bdp_settings['disable_link_author'] == 1) ? false : true;
                        ?>
                        <span class="post-author <?php echo (!$author_link) ? 'bdp-no-links' : ''; ?>">
                         <i class="fas fa-user"></i>
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
                        <span class="mdate <?php echo (!$date_link) ? 'bdp-no-links' : ''; ?>">
                            <?php
                            echo ($date_link) ? '<a href="' . get_day_link($ar_year, $ar_month, $ar_day) . '">' : '';
                            echo '<i class="far fa-clock"></i>';
                            echo $bdp_date;
                            echo ($date_link) ? '</a>' : '';
                            ?>
                        </span>
                        <?php
                    }
                }

                if ($bdp_settings['display_comment_count'] == 1) {
                    $disable_link_comment = isset($bdp_settings['disable_link_comment']) && $bdp_settings['disable_link_comment'] == 1 ? true : false;
                    ?>
                    <span class="metacomments <?php echo ($disable_link_comment) ? 'bdp-no-links': ''?>">
                        <i class="fas fa-comment"></i>
                        <?php
                        if ($disable_link_comment) {
                            comments_number('0 Comments', '1 Comments', '% Comments');
                        } else {
                            comments_popup_link('0 Comments', '1 Comments', '% Comments');
                        }
                        ?>
                    </span>
                    <?php
                }
                if (isset($bdp_settings['display_postlike']) && $bdp_settings['display_postlike'] == 1) {
                    echo do_shortcode('[likebtn_shortcode]');
                }
                ?>
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
            ?>
            <div class="post_content">
                <?php
                echo bdp_get_content($post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings);
                $read_more_on = isset($bdp_settings['read_more_on']) ? $bdp_settings['read_more_on'] : 2;
                $read_more_link = isset($bdp_settings['read_more_link']) ? $bdp_settings['read_more_link'] : 1;
                if ($read_more_link == 1 && $bdp_settings['rss_use_excerpt'] == 1) {
                    $readmoretxt =  $bdp_settings['txtReadmoretext'] != '' ? $bdp_settings['txtReadmoretext'] : __('Read More', BLOGDESIGNERPRO_TEXTDOMAIN);
                    $post_link = get_permalink($post->ID);
                    if(isset($bdp_settings['post_link_type']) && $bdp_settings['post_link_type'] == 1) {
                        $post_link = (isset($bdp_settings['custom_link_url']) && $bdp_settings['custom_link_url'] != '') ? $bdp_settings['custom_link_url'] : get_permalink($post->ID);
                    }
                    if($read_more_on == 2){
                        echo '<div class="read_more_div">';
                    }
                    echo '<a class="more-tag" href="' . $post_link . '">' . $readmoretxt . ' </a>';
                    if($read_more_on == 2){
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
        <?php if( ($bdp_settings['custom_post_type'] == 'post' && $bdp_settings['display_tag'] == 1) || isset($bdp_settings['social_share']) && $bdp_settings['social_share'] == 1 ) { ?>
        <div class="fairy_footer">
            <?php
            if($bdp_settings['custom_post_type'] == 'post'){
                if ($bdp_settings['display_tag'] == 1) {
                    $tags_lists = get_the_tags();
                    $tag_link = (isset($bdp_settings['disable_link_tag']) && $bdp_settings['disable_link_tag'] == 1) ? false : true;
                    if ($tags_lists):
                        ?>
                        <span class="tags">
                            <span><?php _e('Tags', BLOGDESIGNERPRO_TEXTDOMAIN)?> </span>
                            <?php
                            foreach ($tags_lists as $tags_list) {
                                echo '<span class="bdp-tag">';
                                if ($tag_link) {
                                    echo '<a rel="tag" href="' . get_tag_link($tags_list->term_id) . '">';
                                }
                                echo $tags_list->name;
                                if ($tag_link) {
                                    echo '</a>';
                                }
                                echo '</span>';
                            }
                            ?>
                        </span>
                        <?php
                    endif;
                }
            }
             
            ?>

            <?php
            if($social_share) {
                if (( $bdp_settings['facebook_link'] == 1) || ($bdp_settings['twitter_link'] == 1) ||
                    ( $bdp_settings['google_link'] == 1) || ($bdp_settings['linkedin_link'] == 1) ||
                    ( isset($bdp_settings['email_link']) && $bdp_settings['email_link'] == 1) || ( $bdp_settings['pinterest_link'] == 1) ||
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
                    <div class="post_share_div">
                        <a class="fairy-post-share" href="javascript:void(0)" title="<?php _e('SHARE', BLOGDESIGNERPRO_TEXTDOMAIN); ?>">
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                    <?php
                }
            }
            ?>

        </div>
        <?php } ?>
        <?php
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
                        echo '<div class="fairy-social-cover">';
                        bdp_get_social_icons($bdp_settings);
                        echo '<span class="fairy-social-div-closed"><a href="javascript:void(0)" class="fairy-social-links-closed"> <i class="fas fa-times"></i></a></span>';
                        echo '</div>';
            }
        }
        ?>
    </div>
    <?php do_action('bdp_after_post_content'); ?>
</div>
<?php
do_action('bdp_separator_after_post');

