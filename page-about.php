<?php get_header(); ?>

<main role="main">
    <!-- section -->
    <section>

        <h1><?php the_title(); ?></h1>

        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
                
                <!-- post thumbnail -->
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_post_thumbnail(); ?>
                    </a>
                <?php endif; ?>
                
                <!-- article -->
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    
                    <?php the_content(); ?>
                    
                </article>
                <!-- /article -->
                    
                <!-- External links -->
                <?php $hasExternalLinks = get_post_meta( get_queried_object_id(), 'external_link_url0', true) != ""; ?>
                <?php if ($hasExternalLinks) : ?>
                    <div class='external-links-outer'>
                        <div class='content'>
                            <?php for ($i=0; $i < 5; $i++) : ?>
                              <?php if (get_post_meta( get_queried_object_id(), 'external_link_url'.$i, true) ) : ?>
                                <div class='external-links'>
                                  <a rel='nofollow' target='_blank' href='<?php echo get_post_meta( get_queried_object_id(), 'external_link_url'.$i, true ); ?>'>
                                    <?php echo wp_get_attachment_image( get_post_meta( get_queried_object_id(), 'external_link_icon'.$i, true), 32, false ); ?>
                                      <div>
                                        <?php echo get_post_meta( get_queried_object_id(), 'external_link_label'.$i, true ); ?>
                                      </div>
                                  </a>
                                </div>
                              <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endwhile; ?>

        <?php else: ?>
            <!-- article -->
            <article>
                <h2><?php _e('Sorry, nothing to display.', 'html5blank'); ?></h2>
            </article>
            <!-- /article -->
        <?php endif; ?>

    </section>
    <!-- /section -->
</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
