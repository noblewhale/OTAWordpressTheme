<?php
$startLetter = '';
$z = 10;
?>
<?php if (have_posts()): while (have_posts()) : the_post(); ?>

    <?php 
        $title = get_the_title();
        list($artist, $song) = getArtistAndSong($title);
        
        if (strtolower($artist) === strtolower($lastArtist) || empty($artist)) continue;
        $lastArtist = $artist;
    ?>

    <?php if ($_GET['_special'] == 0 && $_GET['_artist'] == 0 && $_GET['cat'] == 0 && ucfirst($title[0]) != $currentLetter) : ?>
        <?php 
            $currentLetter = ucfirst($title[0]); 
            $z++;
        ?>
        <?php if ($currentLetter != 'A') : ?>
            </div>
        <?php endif; ?>
        
        <div class='letter-section'>
        <a class='letter-anchor' id='<?php echo $currentLetter; ?>'>
            <h1 class='letter' style='z-index: <?php echo $z; ?>'>
                <?php echo $currentLetter; ?>
            </h1>
        </a>
    <?php endif; ?>
    <div class='cube-wrap'>
        <cube>
            <!-- article -->
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <!-- post thumbnail -->
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_post_thumbnail("thumbnail"); ?>
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
            <div class='cube-right'></div>
            <div class='cube-bottom'></div>
        </cube>
    </div>
<?php endwhile; ?>
<?php if ($_GET['_special'] == 0 && $_GET['_artist'] == 0 && $_GET['cat'] == 0) : ?>
    </div> <!-- end the last letter section -->
<?php endif; ?>
<?php else : ?>

    <!-- article -->
    <article>
        <h2><?php _e('Sorry, nothing to display.', 'html5blank'); ?></h2>
    </article>
    <!-- /article -->

<?php endif; ?>
