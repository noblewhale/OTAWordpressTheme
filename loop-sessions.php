<?php
$startLetter = '';
?>
<?php if (have_posts()): while (have_posts()) : the_post(); ?>

    <?php 
        $title = get_the_title();
        list($artist, $song) = getArtistAndSong($title);
    ?>

    <?php if ($_GET['_special'] == 0 && $_GET['_artist'] == 0 && $_GET['cat'] == 0 && ucfirst($title[0]) != $currentLetter) : ?>
        <?php $currentLetter = ucfirst($title[0]); ?>
        <h1 class='letter'>
            <?php echo $currentLetter; ?>
        </h1>
    <?php endif; ?>

    <!-- article -->
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <!-- post thumbnail -->
        <?php if (has_post_thumbnail()) : // Check if thumbnail exists ?>
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php the_post_thumbnail("thumbnail"); // Declare pixel size you need inside the array ?>
            </a>
        <?php endif; ?>
        <!-- /post thumbnail -->

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
<?php endwhile; else : ?>

    <!-- article -->
    <article>
        <h2><?php _e('Sorry, nothing to display.', 'html5blank'); ?></h2>
    </article>
    <!-- /article -->

<?php endif; ?>
