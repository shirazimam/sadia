<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/nicy.php.
 * @author  Solwin Infotech
 * @version 2.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
add_action('bd_archive_design_format_function', 'bdp_archive_nicy_template', 10, 1);
if (!function_exists('bdp_archive_nicy_template')) {

    /**
     * @global type $post
     * @return html display nicy design
     */
    function bdp_archive_nicy_template($bdp_settings) {
        global $post;
        $image_hover_effect = '';
        if (isset($bdp_settings['bdp_image_hover_effect']) && $bdp_settings['bdp_image_hover_effect'] == 1) {
            $image_hover_effect = (isset($bdp_settings['bdp_image_hover_effect_type']) && $bdp_settings['bdp_image_hover_effect_type'] != '') ? $bdp_settings['bdp_image_hover_effect_type'] : '';
        }
        ?>
        <div class="blog_template bdp_blog_template nicy">

            <?php do_action('bdp_before_archive_post_content'); ?>
            <div class="entry-container">
                <div class="blog_header">
                    <div class="pull-left">
                        <h2>
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
                        <?php
                        $display_date = $bdp_settings['display_date'];
                        $display_author = $bdp_settings['display_author'];
                        $author_link = (isset($bdp_settings['disable_link_author']) && $bdp_settings['disable_link_author'] == 1) ? false : true;
                        $date_format = (isset($bdp_settings['post_date_format']) && $bdp_settings['post_date_format'] != 'default') ? $bdp_settings['post_date_format'] : get_option('date_format');
                        $bdp_date = (isset($bdp_settings['dsiplay_date_from']) && $bdp_settings['dsiplay_date_from'] == 'modify') ? apply_filters('bdp_date_format', get_post_modified_time($date_format, $post->ID), $post->ID) : apply_filters('bdp_date_format', get_the_time($date_format, $post->ID), $post->ID);
                        $display_comment_count = $bdp_settings['display_comment_count'];
                        $ar_year = get_the_time('Y');
                        $ar_month = get_the_time('m');
                        $ar_day = get_the_time('d');
                        if ($display_date == 1 || $bdp_settings['display_postlike'] == 1 || $display_author == 1 || $display_comment_count == 1) {
                            ?>
                            <div class="metadatabox">
                                <?php
                                if ($display_author == 1 || $display_date == 1) {
                                    ?><div class="metadata"><?php
                                    if ($display_author == 1 && $display_date == 1) {
                                        _e('Posted by', BLOGDESIGNERPRO_TEXTDOMAIN);
                                            ?>&nbsp;<span class="post_author <?php echo ($author_link) ? 'bdp_has_links' : 'bdp_no_links'; ?>"><?php
                                                echo bdp_get_post_auhtors($post->ID, $bdp_settings);
                                            ?></span><?php

                                            $date_link = (isset($bdp_settings['disable_link_date']) && $bdp_settings['disable_link_date'] == 1) ? false : true;
                                            echo ' ';
                                            _e('on', BLOGDESIGNERPRO_TEXTDOMAIN);
                                            echo ' ';
                                            echo ($date_link) ? '<a href="' . get_day_link($ar_year, $ar_month, $ar_day) . '">' : '';
                                            echo $bdp_date;
                                            echo ($date_link) ? '</a>' : '';
                                        } elseif ($display_author == 1) {
                                            _e('Posted by', BLOGDESIGNERPRO_TEXTDOMAIN);
                                            echo ' ';
                                                ?><span class="post_author <?php echo ($author_link) ? 'bdp_has_links' : 'bdp_no_links'; ?>"><?php
                                                    echo bdp_get_post_auhtors($post->ID, $bdp_settings);
                                                ?></span><?php
                                        } elseif ($display_date == 1) {
                                            $date_link = (isset($bdp_settings['disable_link_date']) && $bdp_settings['disable_link_date'] == 1) ? false : true;
                                            _e('Posted on', BLOGDESIGNERPRO_TEXTDOMAIN);
                                            echo ' ';
                                            echo ($date_link) ? '<a href="' . get_day_link($ar_year, $ar_month, $ar_day) . '">' : '';
                                            echo $bdp_date;
                                            echo ($date_link) ? '</a>' : '';
                                        }
                                        ?></div><?php
                                    }
                                    if ($display_comment_count == 1) {
                                        ?>
                                    <div class="metacomments">
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
                                    echo do_shortcode('[likebtn_shortcode]');
                                }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="blog-header-avatar">
                        <?php echo get_avatar(get_the_author_meta('ID'), 70); ?>
                    </div>
                </div>
                <?php if (bdp_get_first_embed_media($post->ID, $bdp_settings) && $bdp_settings['rss_use_excerpt'] == 1) { ?>
                    <div class="bdp-post-image post-video">
                        <?php
                        if (get_post_format() == 'quote') {
                            if (has_post_thumbnail()) {
                                $post_thumbnail = 'full';
                                $thumbnail = bdp_get_the_thumbnail($bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID);
                                echo apply_filters('bdp_post_thumbnail_filter', $thumbnail, $post->ID);
                                echo '<div class="upper_image_wrapper">';
                                echo bdp_get_first_embed_media($post->ID, $bdp_settings);
                                echo '</div>';
                            }
                        } else if (get_post_format() == 'link') {
                            if (has_post_thumbnail()) {
                                $post_thumbnail = 'full';
                                $thumbnail = bdp_get_the_thumbnail($bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID);
                                echo apply_filters('bdp_post_thumbnail_filter', $thumbnail, $post->ID);
                                echo '<div class="upper_image_wrapper bdp_link_post_format">';
                                echo bdp_get_first_embed_media($post->ID, $bdp_settings);
                                echo '</div>';
                            }
                        } else {
                            echo bdp_get_first_embed_media($post->ID, $bdp_settings);
                        }
                        ?>
                    </div>
                    <?php
                } else {
                    $post_thumbnail = 'full';
                    $thumbnail = bdp_get_the_thumbnail($bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID);
                    if (!empty($thumbnail)) {
                        $bdp_post_image_link = (isset($bdp_settings['bdp_post_image_link']) && $bdp_settings['bdp_post_image_link'] == 0) ? false : true;
                        ?>
                        <div class="bdp-post-image">
                            <?php
                            echo '<figure class="' . $image_hover_effect . '">';
                            echo ($bdp_post_image_link) ? '<a href="' . get_permalink($post->ID) . '">' : '';
                            echo apply_filters('bdp_post_thumbnail_filter', $thumbnail, $post->ID);
                            echo ($bdp_post_image_link) ? '</a>' : '';

                            if (isset($bdp_settings['pinterest_image_share']) && $bdp_settings['pinterest_image_share'] == 1 && has_post_thumbnail() && isset($bdp_settings['social_share']) && $bdp_settings['social_share'] == 1) {
                                echo bdp_pinterest($post->ID);
                            }
                            echo '</figure>';
                            ?>
                        </div>
                        <?php
                    }
                }
                $tags_list = get_the_tag_list('', ', ');
                $categories_list = get_the_category_list(', ');
                if (( $bdp_settings['display_category'] == 1 && $categories_list ) || ( $bdp_settings['display_tag'] == 1 && $tags_list )   ) {
                    echo '<div class="post-meta-cats-tags">';
                }
                if ($bdp_settings['display_category'] == 1 && $categories_list) {
                    $categories_link = (isset($bdp_settings['disable_link_category']) && $bdp_settings['disable_link_category'] == 1) ? true : false;
                    ?>
                    <div class="category-link <?php echo ($categories_link) ? 'bdpp_no_links' : 'bdp_has_links'; ?>">
                        <?php
                        $label_featured_post = (isset($bdp_settings['label_featured_post']) && $bdp_settings['label_featured_post'] != '') ? $bdp_settings['label_featured_post'] : '';
                        if($label_featured_post != '' && is_sticky()) {
                            ?> <div class="label_featured_post"><?php echo $label_featured_post; ?></div> <?php
                        }
                        ?>
                        <?php _e('Category', BLOGDESIGNERPRO_TEXTDOMAIN); ?> : <?php
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
                if ($bdp_settings['display_tag'] == 1 && $tags_list) {
                    $tag_link = (isset($bdp_settings['disable_link_tag']) && $bdp_settings['disable_link_tag'] == 1) ? true : false;
                    if ($tag_link) {
                        $tags_list = strip_tags($tags_list);
                    }
                    if ($tags_list):
                        ?>
                        <div class="tags <?php echo ($tag_link) ? 'bdp_no_links' : 'bdp_has_links'?>">
                            <span class="link-lable"><?php _e('Tags', BLOGDESIGNERPRO_TEXTDOMAIN); ?> &nbsp;:&nbsp; </span>
                            <?php
                            print_r($tags_list);
                            $show_sep = true;
                            ?>
                        </div>
                        <?php
                    endif;
                }
                  
                if (( $bdp_settings['display_category'] == 1 && $categories_list ) || ( $bdp_settings['display_tag'] == 1 && $tags_list )   ) {
                    echo '</div>';
                }
                
                ?>
                <div class="post_content">
                    <?php echo bdp_get_content($post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings); ?>
                    <?php
                    $read_more_on = isset($bdp_settings['read_more_on']) ? $bdp_settings['read_more_on'] : 2;
                    $read_more_link = isset($bdp_settings['read_more_link']) ? $bdp_settings['read_more_link'] : 1;
                    if ($bdp_settings['rss_use_excerpt'] == 1 && $read_more_link == 1):
                        $readmoretxt =  $bdp_settings['txtReadmoretext'] != '' ? $bdp_settings['txtReadmoretext'] : __('Read More', BLOGDESIGNERPRO_TEXTDOMAIN);
                        $post_link = get_permalink($post->ID);
                        if(isset($bdp_settings['post_link_type']) && $bdp_settings['post_link_type'] == 1) {
                            $post_link = (isset($bdp_settings['custom_link_url']) && $bdp_settings['custom_link_url'] != '') ? $bdp_settings['custom_link_url'] : get_permalink($post->ID);
                        }
                        if( $read_more_on == 1 ) {
                            ?>
                            <a class="more-tag" href="<?php echo $post_link; ?>">
                                <?php echo $readmoretxt; ?>
                            </a>
                            <?php
                        }
                        ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="entry-meta clearfix">
                <div class="up_arrow"></div>
                <div class="pull-left">
                    <?php
                   if ($bdp_settings['rss_use_excerpt'] == 1 && $read_more_link == 1 && $read_more_on == 2 ):
                        ?>
                        <div class="read-more">
                            <a class="more-tag" href="<?php echo $post_link; ?>">
                                <?php echo $readmoretxt; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="pull-right social-component-count-<?php echo $bdp_settings['social_count_position']; ?>">
                    <?php bdp_get_social_icons($bdp_settings); ?>
                </div>
            </div>
            <?php do_action('bdp_after_archive_post_content'); ?>
        </div>
        <?php
    }

}