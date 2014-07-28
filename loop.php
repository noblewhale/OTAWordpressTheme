<?php if (have_posts()): while (have_posts()) : the_post(); ?><div class='cube-wrap'>
    <cube>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if (has_post_thumbnail()) : ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <?php the_post_thumbnail("thumbnail"); ?>
                </a>
            <?php endif; ?>
            <?php list($artist, $song) = getArtistAndSong(get_the_title(get_the_ID())); ?>
            <a class="title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php if ($song && $artist) : ?>
                    <h2 class='song'><?php echo $song; ?></h2>
                    <h3 class='artist'><?php echo $artist; ?></h3>
                <?php else : ?>
                    <h2><?php the_title(); ?></h2>
                <?php endif; ?>
            </a>
            <a class="title transparent-background">
                <?php if ($song && $artist) : ?>
                    <h2 class='song'><?php echo $song; ?></h2>
                    <h3 class='artist'><?php echo $artist; ?></h3>
                <?php else : ?>
                    <h2><?php the_title(); ?></h2>
                <?php endif; ?>
            </a>
        </article>
        <!--<div class='cube-left'></div>-->
        <div class='cube-right'></div>
        <div class='cube-bottom'></div>
        <!--<div class='cube-top'></div>-->
    </cube>
</div><?php endwhile; ?>
<?php else: ?>

    <!-- article -->
    <article>
        <h2><?php _e('Sorry, nothing to display.', 'html5blank'); ?></h2>
    </article>
    <!-- /article -->

<?php endif; ?>
