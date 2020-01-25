<?php /* Template Name: BlogsPage */ ?>

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
                <h2 class="text-center text-dark text-50 bebas font-weight-bold" style="margin-top:80px;">LATEST BLOGS</h2>
                <div class="row mt-5">
                    <div class="col-md-10 offset-md-1 col-12">
                        <div class="row">
                            <?php global $post; // required
                            $args = array('category' => 24); // exclude category 9
                            $custom_posts = get_posts($args);

                            foreach ($custom_posts as $key => $post) : setup_postdata($post);

                                $featured_img_url = get_the_post_thumbnail_url($post->ID, 'full');
                                ?>
                                <div class="col-md-6 col-12 py-3">
                                    <a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
                                        <div class="blog-wrapper">
                                            <div class="blog-featured-image" style="background-image:url(<?php echo $featured_img_url; ?>)">

                                            </div>
                                            <h3 class="blog-title py-2 text-justify text-uppercase"> <?php echo $post->post_title; ?> </h3>
                                            <div class="row blog-metas">
                                                <div class="col">
                                                    <span class="text-dark text-uppercase byadmin"> <?php the_author_meta('user_nicename'); ?> </span> | <span class="blogs-date"><?php echo get_the_date(' F j, D Y'); ?> </span>
                                                </div>
                                                <div class="col-4">
                                                    <ul class="list-inline d-flex social-share">

                                                        <li class="list-inline-item"><a class="text-xs-center" target="_blank" href="https://www.facebook.com/sharer?u=<?php echo the_permalink(); ?>&t=<?php echo $post->title; ?>" target="_blank" rel="noopener noreferrer"> <i class="fab fa-facebook-f"> </i> </a></li>

                                                        <li class="list-inline-item"><a class="text-xs-center" title="Click to share this post on Twitter" href="http://twitter.com/intent/tweet?text=Currently reading <?php echo the_title(); ?>&amp;url=<?php the_permalink(); ?>&gt;" target="_blank" rel="noopener noreferrer"> <i class="fab fa-twitter"> </i> </a> </li>
                                                        <li class="list-inline-item"><a class="text-xs-center" target="" href="#"> <i class="fab fa-instagram"> </i> </a> </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

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