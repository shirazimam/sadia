<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/easy_timeline.php.
 * @author  Solwin Infotech
 * @version 2.0
 */

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
add_action('bd_archive_design_format_function', 'bdp_archive_easy_timeline_template', 10, 1);
if (!function_exists('bdp_archive_easy_timeline_template')) {

    /**
     *
     * @global type $post
     */
    function bdp_archive_easy_timeline_template($bdp_settings) {
        global $post;
        $image_hover_effect = '';
        if (isset($bdp_settings['bdp_image_hover_effect']) && $bdp_settings['bdp_image_hover_effect'] == 1) {
            $image_hover_effect = (isset($bdp_settings['bdp_image_hover_effect_type']) && $bdp_settings['bdp_image_hover_effect_type'] != '') ? $bdp_settings['bdp_image_hover_effect_type'] : '';
        }
        ?>
        <?php do_action('bdp_before_archive_post_content'); ?>
        <li>
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
            if ($bdp_settings['display_feature_image'] == 1) {
                if (bdp_get_first_embed_media($post->ID, $bdp_settings) && $bdp_settings['rss_use_excerpt'] == 1) {
                    ?>
                    <div class="post-image post-video">
                        <?php echo bdp_get_first_embed_media($post->ID, $bdp_settings); ?>
                    </div>
                    <?php
                } else {
                    $post_thumbnail = 'easy_timeline_img';
                    $thumbnail = bdp_get_the_thumbnail($bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID);
                    if (!empty($thumbnail)) {
                        echo '<figure class="' . $image_hover_effect . '">';
                        ?>
                        <div class="photo post-image">
                            <?php
                            echo ($date_link) ? '<a href="' . get_day_link($ar_year, $ar_month, $ar_day) . '">' : '';
                            echo get_the_date();
                            echo ($date_link) ? '</a>' : '';

                            if (isset($bdp_settings['pinterest_image_share']) && $bdp_settings['pinterest_image_share'] == 1 && isset($bdp_settings['social_share']) && $bdp_settings['social_share'] == 1) {
                                echo bdp_pinterest($post->ID);
                            }
                            ?>
                        </div>
                        <?php
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
                        <?php
                        if (isset($bdp_settings['disable_link_comment']) && $bdp_settings['disable_link_comment'] == 1) {
                            comments_number(__('Leave a Comment', BLOGDESIGNERPRO_TEXTDOMAIN), __('1 comment', BLOGDESIGNERPRO_TEXTDOMAIN), '% ' . __('comments', BLOGDESIGNERPRO_TEXTDOMAIN));
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
                if ($bdp_settings['display_category'] == 1) {
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
                if ($bdp_settings['display_tag'] == 1) {
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
                bdp_get_social_icons($bdp_settings);
                ?>
            </div>
        </li>
        <?php do_action('bdp_after_archive_post_content'); ?>
        <?php
    }

}