<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_4
 */

?>

</div><!-- #content -->

<footer id="colophon" class="site-footer text-center text-muted" style="background-color:#09080d">

	<section class="footer-widgets text-left">
		<div class="container-fluid">
			<div class="row py-md-5 py-1">
				<div class="col-md-3 col-12">
					<aside class="widget-area footer-1-area mb-md-2 mb-4 pl-md-5 pl-0">
						<div class="footer-logo">
							<img src="http://nextinfosolutions.com/sadia/wp-content/uploads/2019/07/logo-top.png" class="img-fluid" />
							<p class="text-justify mt-2">
								We strive to provide best quality at Best Price.
							</p>
							<?php if (function_exists('dc_dcb_dev_content_block')) echo do_shortcode('[dcb name=social-block-footer]'); ?>
						</div>
					</aside>
				</div>
				<?php if (is_active_sidebar('footer-1')) : ?>
					<div class="col-md-2 col-12">
						<aside class="widget-area footer-1-area mb-2">
							<?php dynamic_sidebar('footer-1'); ?>
						</aside>
					</div>
				<?php endif; ?>

				<?php if (is_active_sidebar('footer-2')) : ?>
					<div class="col-md-2 col-12">
						<aside class="widget-area footer-2-area mb-2">
							<?php dynamic_sidebar('footer-2'); ?>
						</aside>
					</div>
				<?php endif; ?>

				<?php if (is_active_sidebar('footer-3')) : ?>
					<div class="col-md-2 col-12">
						<aside class="widget-area footer-3-area mb-2">
							<?php dynamic_sidebar('footer-3'); ?>
						</aside>
					</div>
				<?php endif; ?>

				<?php if (is_active_sidebar('footer-4')) : ?>
					<div class="col-md-3 col-12">
						<aside class="widget-area footer-4-area mb-2 pr-5">
							<?php dynamic_sidebar('footer-4'); ?>
						</aside>
					</div>
				<?php endif; ?>
			</div>
			<!-- /.row -->
		</div>
	</section>

	<div class="container-fluid">
		<div class="row">
			<div class="col-10 offset-1">
				<div class="site-info">
					<a href=""> Copyright &copy; sadia's hyderabadi. All rights reserved.</a>
					<span class="sep"> | </span>

				</div><!-- .site-info -->
			</div>
		</div>
	</div>
	<!-- /.container -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.js" integrity="sha256-zUQGihTEkA4nkrgfbbAM1f3pxvnWiznBND+TuJoUv3M=" crossorigin="anonymous"></script>
</body>

</html>