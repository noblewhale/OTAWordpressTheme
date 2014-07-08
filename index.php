<?php get_header(); ?>
<main role="main">
    <section class='slider'>
        <?php echo do_shortcode('[advps-slideshow optset="1"]'); ?>
    </section>
    <!-- section -->
    <div class="middle-widget">
        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-1'))  ?>
    </div>
    <div class="middle-widget">
        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-2'))  ?>
    </div>
    <section class='latest'>

        <h1><?php _e('Latest Sessions', 'html5blank'); ?></h1>

        <?php query_posts('tag__not_in=577');
        get_template_part('loop'); ?>

    </section>
    <section class='upcoming'>

        <h1><?php _e('Upcoming Sessions', 'html5blank'); ?></h1>

<?php query_posts('tag=upcoming');
get_template_part('loop'); ?>

    </section>
    <!-- /section -->
</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
