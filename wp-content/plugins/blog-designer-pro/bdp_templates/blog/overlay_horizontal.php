<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/overlay_horizontal.php.
 * @author  Solwin Infotech
 * @version 2.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $post;
?>

<div class="blog_template bdp_blog_template overlay_horizontal blog-wrap lb-item" data-id="<?php echo get_the_date('d/m/Y'); ?>" data-description="<?php echo get_the_title(); ?>">
    <?php do_action('bdp_before_post_content'); ?>
    <div class="post_hentry">
        <div class="post_content_wrap">
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
            <div class="photo post-image">
                <?php
                if (has_post_thumbnail()) {
                    ?>
                    <a href="<?php echo get_permalink($post->ID); ?>">
                        <?php
                        $url = wp_get_attachment_url(get_post_thumbnail_id());
                        $width = isset($bdp_settings['item_width']) ? $bdp_settings['item_width'] : 400;
                        $height = isset($bdp_settings['item_height']) ? $bdp_settings['item_height'] : 570;
                        $resizedImage = bdp_resize($url, $width, $height, true, get_post_thumbnail_id());
                        echo '<img src="' . $resizedImage["url"] . '" width="' . $resizedImage["width"] . '" height="' . $resizedImage["height"] . '" title="' . $post->post_title . '" alt="' . $post->post_title . '" />';
                        ?>
                    </a>
                    <?php
                    if (isset($bdp_settings['pinterest_image_share']) && $bdp_settings['pinterest_image_share'] == 1 && isset($bdp_settings['social_share']) && $bdp_settings['social_share'] == 1) {
                        echo bdp_pinterest($post->ID);
                    }
                    ?>
                    <?php
                } elseif (isset($bdp_settings['bdp_default_image_id']) && $bdp_settings['bdp_default_image_id'] != '') {
                    ?>
                    <a href="<?php echo get_permalink($post->ID); ?>">
                        <?php
                        $url = wp_get_attachment_url($bdp_settings['bdp_default_image_id']);
                        $width = isset($bdp_settings['item_width']) ? $bdp_settings['item_width'] : 400;
                        $height = isset($bdp_settings['item_height']) ? $bdp_settings['item_height'] : 570;
                        $resizedImage = bdp_resize($url, $width, $height, true, $bdp_settings['bdp_default_image_id']);
                        echo '<img src="' . $resizedImage["url"] . '" width="' . $resizedImage["width"] . '" height="' . $resizedImage["height"] . '" title="' . $post->post_title . '" alt="' . $post->post_title . '" />';
                        ?>
                    </a>
                    <?php
                }
                ?>
            </div>
            <div class="lb-overlay"> </div>
            <div class="post-content-area">
                <div class="metadatabox">
                    <?php
                    $display_date = $bdp_settings['display_date'];
                    if ($display_date == 1) {
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
                            echo '<i class="far fa-calendar-alt"></i>';
                            echo $bdp_date;
                            echo ($date_link) ? '</a>' : '';
                            ?>
                        </span>
                        <?php
                    }
                    if ($bdp_settings['display_author'] == 1) {
                        $author_link = (isset($bdp_settings['disable_link_author']) && $bdp_settings['disable_link_author'] == 1) ? false : true;
                        ?>
                        <span class="mauthor">
                            <i class="fas fa-user"></i>
                            <?php echo bdp_get_post_auhtors($post->ID, $bdp_settings);?>
                        </span>
                        <?php
                    }
                    if ($bdp_settings['display_comment_count'] == 1) {
                        if (!post_password_required() && ( comments_open() || get_comments_number() )) :
                            ?>
                            <span class="comment">
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
                        endif;
                    }
                    if (isset($bdp_settings['display_postlike']) && $bdp_settings['display_postlike'] == 1) {
                        echo do_shortcode('[likebtn_shortcode]');
                    }
                    ?>
                </div>
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
                    $read_more_link = isset($bdp_settings['read_more_link']) ? $bdp_settings['read_more_link'] : 1;
                    $read_more_on = isset($bdp_settings['read_more_on']) ? $bdp_settings['read_more_on'] : 2;
                    if ($read_more_link == 1 && $bdp_settings['rss_use_excerpt'] == 1) {
                        $readmoretxt =  $bdp_settings['txtReadmoretext'] != '' ? $bdp_settings['txtReadmoretext'] : __('Read More', BLOGDESIGNERPRO_TEXTDOMAIN);
                        $post_link = get_permalink($post->ID);
                        if(isset($bdp_settings['post_link_type']) && $bdp_settings['post_link_type'] == 1) {
                            $post_link = (isset($bdp_settings['custom_link_url']) && $bdp_settings['custom_link_url'] != '') ? $bdp_settings['custom_link_url'] : get_permalink($post->ID);
                        }
                        if($read_more_on == 1){
                            echo '<a class="more-tag" href="' . $post_link . '">' . $readmoretxt . ' </a>';
                        }
                       
                    }
                    ?>
                </div>
                <div class="blog_footer">
                    <?php
                     if ($read_more_link == 1 && $bdp_settings['rss_use_excerpt'] == 1 && $read_more_on == 2) {
                        echo '<div class="read-more-div"><a class="more-tag" href="' . $post_link . '">' . $readmoretxt . ' </a></div>';
                     }
                    if ($bdp_settings['custom_post_type'] == 'post') {
                        if (isset($bdp_settings['display_category']) && $bdp_settings['display_category'] == 1) {
                            ?>
                            <div class="categories">
                                <i class="fas fa-folder"></i>
                                <?php
                                $categories_list = get_the_category_list(', ');
                                $categories_link = (isset($bdp_settings['disable_link_category']) && $bdp_settings['disable_link_category'] == 1) ? true : false;
                                if ($categories_link) {
                                    $categories_list = strip_tags($categories_list);
                                }
                                if ($categories_list) {
                                    _e('Categories', BLOGDESIGNERPRO_TEXTDOMAIN);
                                    echo ' : ';
                                    print_r($categories_list);
                                }
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
                                <div class="tags">
                                    <i class="fas fa-bookmark"></i>
                                    <?php
                                    _e('Tags', BLOGDESIGNERPRO_TEXTDOMAIN);
                                    echo ' : ';
                                    printf($tags_list);
                                    ?>
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
                                        <div class="categories">
                                            <i class="fas fa-folder"></i>
                                            <?php
                                            echo $taxonomy_single->label . ' : ';
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
    </div>
<?php do_action('bdp_after_post_content'); ?>
</div>
<?php
do_action('bdp_separator_after_post');
