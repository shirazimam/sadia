<?php
/*
* Template Name: Full Width Without Container
*/

get_header(); ?>

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

            <?php
            while (have_posts()) : the_post();

                get_template_part('template-parts/content', 'page-full');

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
</div>




<!--  -->

<div class="container" id="feedbacks">
    <h2 class="text-center text-dark text-50 bebas font-weight-bold" style="margin-top:80px;">OUR HAPPY CUSTOMERS</h2>
    <hr class="" style="height:4px;width:50px;" />
    <section class="blue" style="margin: 80px 0px 120px 0px;">
        <div class="content">
            <div class="slider autoplay">
                <?php global $post; // required
                $args = array('category' => 22); // exclude category 9
                $custom_posts = get_posts($args);

                foreach ($custom_posts as $key => $post) : setup_postdata($post);

                    $featured_img_url = get_the_post_thumbnail_url($post->ID, 'full');
                    ?>

                    <div class="multiple">
                        <?php
                        if ($key % 2) {
                            ?>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="testimonial-image" style="background-image:url(<?php echo $featured_img_url; ?>)">

                                    </div>
                                </div>
                                <div class="col">
                                    <p class="review bebas left-quote">
                                        <?php echo $post->post_content; ?>
                                    </p>
                                    <h3 class="reviewer-name text-left">
                                        <?php echo $post->post_title; ?>
                                    </h3>

                                </div>
                            <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col">
                                        <p class="review bebas right-quote">
                                            <?php echo $post->post_content; ?>
                                        </p>
                                        <h3 class="reviewer-name text-right">
                                            <?php echo $post->post_title; ?>
                                        </h3>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="testimonial-image" style="background-image:url(<?php echo $featured_img_url; ?>)">

                                        </div>
                                    </div>

                                <?php
                                }
                                ?>
                            </div>

                        </div>

                    <?php
                    endforeach;
                    ?>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center testimonial-slider">
                <div class="left-arrow">
                    <img src="http://sadiahydjewellery.com/wp-content/uploads/2019/07/grey-left-arrow.png" />
                </div>
                <div class="grey-dot">
                    <img src="http://sadiahydjewellery.com/wp-content/uploads/2019/07/left-right-dot.png" />
                </div>
                <div class="active-dot">
                    <img src="http://sadiahydjewellery.com/wp-content/uploads/2019/07/middle-dot.png" />
                </div>
                <div class="grey-dot">
                    <img src="http://sadiahydjewellery.com/wp-content/uploads/2019/07/left-right-dot.png" />
                </div>
                <div class="right-arrow">
                    <img src="http://sadiahydjewellery.com/wp-content/uploads/2019/07/grey-right-arrow.png" />
                </div>
            </div>
			<div class="text-center mt-3">
				<a class="btn btn-warning" href="/video-testimonials/"> Video Testimonials</a>	
			</div>
			
        </div>
    </section>
</div>


<!--  -->

<script>
    jQuery(document).ready(function($) {
        $('.autoplay').slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 2,
            slidesToScroll: 2,
            autoplay: false,
            autoplaySpeed: 2000
        });
    });
</script>
<?php
get_footer();
