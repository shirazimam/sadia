<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/easy_timeline.php.
 * @author  Solwin Infotech
 * @version 2.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $post;

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
<?php do_action('bdp_before_post_content'); ?>
<li class="bdp_blog_single_post_wrapp <?php echo $category; ?>">
    <?php
        $label_featured_post = (isset($bdp_settings['label_featured_post']) && $bdp_settings['label_featured_post'] != '') ? $bdp_settings['label_featured_post'] : '';
        if($label_featured_post != '' && is_sticky()) {
            ?> <div class="label_featured_post"><span><?php echo $label_featured_post; ?></span></div> <?php
        }
        ?>
    <div class="post-title">
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
    </div>
    <?php
    if ($bdp_settings['display_date'] == 1) {
        $date_link = (isset($bdp_settings['disable_link_date']) && $bdp_settings['disable_link_date'] == 1) ? false : true;
        $date_format = (isset($bdp_settings['post_date_format']) && $bdp_settings['post_date_format'] != 'default') ? $bdp_settings['post_date_format'] : get_option('date_format');
        $bdp_date = (isset($bdp_settings['dsiplay_date_from']) && $bdp_settings['dsiplay_date_from'] == 'modify') ? apply_filters('bdp_date_format', get_post_modified_time($date_format, $post->ID), $post->ID) : apply_filters('bdp_date_format', get_the_time($date_format, $post->ID), $post->ID);
        $ar_year = get_the_time('Y');
        $ar_month = get_the_time('m');
        $ar_day = get_the_time('d');
        ?>
        <div class="mdate">
            <i class="far fa-calendar-alt"></i>
            <?php
            echo ($date_link) ? '<a href="' . get_day_link($ar_year, $ar_month, $ar_day) . '">' : '<span class="date-inner">';
            echo $bdp_date;
            echo ($date_link) ? '</a>' : '</span>';
            ?>
        </div>
        <?php
    }
    if (isset($bdp_settings['display_feature_image']) && $bdp_settings['display_feature_image'] == 1) {
        if (bdp_get_first_embed_media($post->ID, $bdp_settings) && $bdp_settings['rss_use_excerpt'] == 1) {
            ?>
            <div class="post-image post-video">
                <?php echo bdp_get_first_embed_media($post->ID, $bdp_settings); ?>
            </div>
            <?php
        } else {
            $post_thumbnail = 'easy_timeline_img';
            $thumbnail = bdp_get_the_thumbnail($bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID);
            $bdp_post_image_link = (isset($bdp_settings['bdp_post_image_link']) && $bdp_settings['bdp_post_image_link'] == 0) ? false : true;
            if (!empty($thumbnail)) {
                echo '<figure class="' . $image_hover_effect . '">';
                ?>
                <div class="photo post-image">
                    <?php
                    echo ($bdp_post_image_link) ? '<a href="' . get_permalink($post->ID) . '" class="deport-img-link">' : '';
                    echo apply_filters('bdp_post_thumbnail_filter', $thumbnail, $post->ID);
                    echo ($bdp_post_image_link) ? '</a>' : '';

                    if (isset($bdp_settings['pinterest_image_share']) && $bdp_settings['pinterest_image_share'] == 1 && isset($bdp_settings['social_share']) && $bdp_settings['social_share'] == 1) {
                        echo bdp_pinterest($post->ID);
                    }
                    ?>

                </div>
                
                <?php
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
        }
    }
    if ($bdp_settings['display_author'] == 1 || $bdp_settings['display_postlike'] == 1 || $bdp_settings['display_comment_count'] == 1) {
        echo '<div class="metadatabox">';
        if ($bdp_settings['display_author'] == 1) {
            $author_link = (isset($bdp_settings['disable_link_author']) && $bdp_settings['disable_link_author'] == 1) ? false : true;
            ?>
            <span class="mauthor <?php echo ($author_link) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
                <i class="fas fa-user"></i>
                <?php echo bdp_get_post_auhtors($post->ID, $bdp_settings); ?>
            </span>
            <?php
        }

        if ($bdp_settings['display_comment_count'] == 1) {
            ?>
            <span class="mcomments">
                <i class="fas fa-comment"></i>
                <?php if (isset($bdp_settings['disable_link_comment']) && $bdp_settings['disable_link_comment'] == 1) { ?>
                    <span class="comment-count-inner"><?php comments_number(__('Leave a Comment', BLOGDESIGNERPRO_TEXTDOMAIN), __('1 comment', BLOGDESIGNERPRO_TEXTDOMAIN), '% ' . __('comments', BLOGDESIGNERPRO_TEXTDOMAIN)); ?></span>
                    <?php
                } else {
                    comments_popup_link(__('Leave a Comment', BLOGDESIGNERPRO_TEXTDOMAIN), __('1 comment', BLOGDESIGNERPRO_TEXTDOMAIN), '% ' . __('comments', BLOGDESIGNERPRO_TEXTDOMAIN), 'comments-link', __('Comments are off', BLOGDESIGNERPRO_TEXTDOMAIN));
                }
                ?>
            </span>
            <?php
        }
        if (isset($bdp_settings['display_postlike']) && $bdp_settings['display_postlike'] == 1) {
            echo do_shortcode('[likebtn_shortcode]');
        }
        echo '</div>';
    }
    ?>
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
                echo '<div class="read-more-div">';
            }
            echo '<a class="more-tag" href="' . $post_link . '">' . $readmoretxt . ' </a>';
            if($read_more_on == 2){
                echo '</div>';
            }
        }
        ?>
    </div>
    <div class="blog_footer">
        <?php
        if ($bdp_settings['custom_post_type'] == 'post') {
            if (isset($bdp_settings['display_category']) && $bdp_settings['display_category'] == 1) {
                ?>
                <div class="categories">
                    <?php
                    $categories_list = get_the_category_list(', ');
                    $categories_link = (isset($bdp_settings['disable_link_category']) && $bdp_settings['disable_link_category'] == 1) ? true : false;
                    if ($categories_link) {
                        $categories_list = strip_tags($categories_list);
                    }
                    if ($categories_list) {
                        ?>
                        <span class="categorry-inner <?php echo ($categories_list)? 'bdp_no_links' : ''; ?>">
                            <span class="link-lable"> <i class="fas fa-folder"></i> <?php _e('Categories', BLOGDESIGNERPRO_TEXTDOMAIN); ?>:&nbsp; </span>
                            <?php print_r($categories_list); ?>
                        </span>
                    <?php }
                    ?>
                </div>
                <?php
            }
            if (isset($bdp_settings['display_tag']) && $bdp_settings['display_tag'] == 1) {
                $tags_list = get_the_tag_list('', ', ');
                $tag_link = (isset($bdp_settings['disable_link_tag']) && $bdp_settings['disable_link_tag'] == 1) ? true : false;
                if ($tag_link) {
                    $tags_list = strip_tags($tags_list);
                }
                if ($tags_list) {
                    ?>
                    <div class="tags <?php echo ($tag_link) ? 'bdp_no_links' : ''; ?>">
                        <span class="link-lable"> <i class="fas fa-bookmark"></i> <?php _e('Tags', BLOGDESIGNERPRO_TEXTDOMAIN); ?>:&nbsp; </span>
                        <?php printf($tags_list); ?>
                    </div>
                    <?php
                }
            }
        } else {
            $taxonomy_names = get_object_taxonomies($bdp_settings['custom_post_type'], 'objects');
            $taxonomy_names = apply_filters('bdp_hide_taxonomies',$taxonomy_names);
            foreach ($taxonomy_names as $taxonomy_single) {
                $taxonomy = $taxonomy_single->name;
                $sep = 1;
                if (isset($bdp_settings["display_taxonomy_" . $taxonomy]) && $bdp_settings["display_taxonomy_" . $taxonomy] == 1) {
                    $term_list = wp_get_post_terms(get_the_ID(), $taxonomy, array("fields" => "all"));
                    $taxonomy_link = (isset($bdp_settings['disable_link_taxonomy_' . $taxonomy]) && $bdp_settings['disable_link_taxonomy_' . $taxonomy] == 1) ? false : true;
                    if (isset($taxonomy)) {
                        if (isset($term_list) && !empty($term_list)) {
                            ?>
                            <span class="categorry-inner <?php echo (!$taxonomy_link)? 'bdp_no_links' : ''; ?>">
                                <span class="link-lable"> <i class="fas fa-folder"></i> <?php echo $taxonomy_single->label ?> :&nbsp;</span>
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
                            </span>
                            <?php
                        }
                    }
                }
            }
        }
        bdp_get_social_icons($bdp_settings);
        ?>
    </div>
</li>
<?php do_action('bdp_after_post_content'); ?>