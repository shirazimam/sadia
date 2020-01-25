<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_4
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css" integrity="sha256-PF6MatZtiJ8/c9O9HQ8uSUXr++R9KBYu4gbNG5511WE=" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css" integrity="sha256-3h45mwconzsKjTUULjY+EoEkoRhXcOIU4l5YAw2tSOU=" crossorigin="anonymous" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'wp-bootstrap-4'); ?></a>
		<div class="container-fluid">
			<div class="row top-main-menu">
				<div class="col-2 d-none d-md-block">
					<?php the_custom_logo(); ?>
				</div>
				<div class="col-md-7 col-12">
					<header id="masthead" class="site-header <?php if (get_theme_mod('sticky_header', 0)) : echo 'sticky-top';
																endif; ?>">
						<nav id="site-navigation" class="main-navigation navbar navbar-expand-lg">
							<?php if (get_theme_mod('header_within_container', 0)) : ?><div class=""><?php endif; ?>

								<div class="site-branding-text d-md-none">
									<?php the_custom_logo(); ?>
								</div>

								<button class="navbar-toggler text-white" type="button" data-toggle="collapse" data-target="#primary-menu-wrap" aria-controls="primary-menu-wrap" aria-expanded="false" aria-label="Toggle navigation">
									<span class=""> <i class="fas fa-bars"></i></span>
								</button>
								<?php
								wp_nav_menu(array(
									'theme_location'  => 'menu-1',
									'menu_id'         => 'primary-menu',
									'container'       => 'div',
									'container_class' => 'collapse navbar-collapse',
									'container_id'    => 'primary-menu-wrap',
									'menu_class'      => 'navbar-nav mx-auto',
									'fallback_cb'     => '__return_false',
									'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
									'depth'           => 2,
									'walker'          => new WP_bootstrap_4_walker_nav_menu()
								));
								?>
								<?php if (get_theme_mod('header_within_container', 0)) : ?></div><!-- /.container --><?php endif; ?>
						</nav><!-- #site-navigation -->
					</header><!-- #masthead -->
				</div>
				<div class="col-md-3 col-12">
					<?php // if (function_exists('dc_dcb_dev_content_block')) echo do_shortcode('[dcb name=right-menu-top]'); 
					?>
					<ul class="list-inline d-flex menu-right-cart">
						<li class="list-inline-item"> <i class="open-search fa fa-search text-white mt-2" style="font-size:23px; cursor:pointer"></i></li>
						<li class="list-inline-item"> <?php echo do_shortcode("[woo_cart_but]"); ?> </li>
						<li class="list-inline-item"><a class="custom-btnwhite poppins text-xs-center" target="" href="http://nextinfosolutions.com/sadia/index.php/contact-us/"> CONTACT</a> </li>
					</ul>
				</div>

			</div>
		</div>

		<div class="container-fluid search-bar-wrapper d-none">
			<div class="row bg-warning" style="padding:40px">
				<div class="col-12 text-right"> <button class="btn btn-default close-search"> close</button></div>
				<div class="col-md-6 offset-md-3 col-12">
					<?php echo do_shortcode('[smart_search id="1"]'); ?>
				</div>
			</div>
		</div>

		<div id="content" class="site-content">