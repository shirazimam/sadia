<?php /* Template Name: VideoTestimonials */ ?>

<?php get_header(); ?>

<?php
$wpblog_fetrdimg = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
?>
<?php if (!is_front_page()) : ?>
    <div class="container-fluid">
        <div class="row featured-image-wrapper d-flex align-items-center" <?php if ($wpblog_fetrdimg) : ?> style="background-image: url(<?php echo $wpblog_fetrdimg; ?>);" <?php endif; ?>>
            <div class="col-12">
                <h3 class="featured-image-title bebas"> <?php single_post_title(); ?> </h3>
            </div>

        </div>
    </div>
<?php endif; ?>
<div class="">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <div class="container-fluid">
                <!-- <h2 class="text-center text-dark text-50 bebas font-weight-bold" style="margin-top:80px;">LATEST BLOGS</h2> -->
                <div class="row mt-5">
                    <div class="col-md-10 offset-md-1 col-12">
                        <div class="row">
                            <?php global $post; // required
                            $args = array('category' => 62); // exclude category 9
                            $custom_posts = get_posts($args);

                            foreach ($custom_posts as $key => $post) : setup_postdata($post);

                                $featured_img_url = get_the_post_thumbnail_url($post->ID, 'full');

                                $youtube_video_url = get_post_meta($post->ID, 'video_url', true); //store youtube URL in variable

                                if (!empty($youtube_video_url)) : //Check Youtube URL is entered or not

                                    ?>
                                    <div class="col-md-3 col-12 py-3">
                                        <div class="video-testimonial-wrapper">

                                            <video width="100%" height="250" controls>
                                                <source src="<?php echo $youtube_video_url ?>" type="video/mp4">
                                            </video>
                                            <?php // echo $wp_embed->run_shortcode('[embed]' . $youtube_video_url . '[/embed]'); 
                                            ?>
                                        </div>
                                    </div>
                                <?php

                                endif;
                                ?>


                            <?php
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </main><!-- #main -->
    </div><!-- #primary -->
</div>

<?php get_footer(); ?>