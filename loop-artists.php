<?php
$startLetter = '';
?>
<?php if (have_posts()): while (have_posts()) : the_post(); ?>

    <?php 
        $title = get_the_title();
        list($artist, $song) = getArtistAndSong($title);
        
        if (strtolower($artist) === strtolower($lastArtist) || empty($artist)) continue;
        $lastArtist = $artist;
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
            <h2><?php echo $artist; ?></h2>
        </a>
        <a class="title transparent-background">
            <h2><?php echo $artist; ?></h2>
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
