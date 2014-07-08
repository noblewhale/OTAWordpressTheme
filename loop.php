<?php if (have_posts()): while (have_posts()) : the_post(); ?>

    <!-- article -->
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <!-- post thumbnail -->
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php the_post_thumbnail("thumbnail"); ?>
            </a>
        <?php endif; ?>
        <!-- /post thumbnail -->

        <?php list($artist, $song) = getArtistAndSong(get_the_title(get_the_ID())); ?>

        <!-- post title -->
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
        <!-- /post title -->

    </article>
    <!-- /article -->

<?php endwhile; ?>

<?php else: ?>

    <!-- article -->
    <article>
        <h2><?php _e('Sorry, nothing to display.', 'html5blank'); ?></h2>
    </article>
    <!-- /article -->

<?php endif; ?>
