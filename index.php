<?php get_header(); ?>

	<main role="main">
        <section class='slider'>
            <?php echo do_shortcode( '[advps-slideshow optset="1"]' ); ?>
        </section>
		<!-- section -->
		<section class='latest'>

			<h1><?php _e( 'Latest Posts', 'html5blank' ); ?></h1>

			<?php get_template_part('loop'); ?>

		</section>
		<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
