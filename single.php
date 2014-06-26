<?php get_header(); ?>

	<main role="main">
	<!-- section -->
	<section>

	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <?php if ( get_post_meta( get_the_ID(), '_tern_wp_youtube_video', true ) ) {
                $videoID = get_post_meta( get_the_ID(), '_tern_wp_youtube_video', true );
            ?>
                <iframe width="980" height="551" src="//www.youtube.com/embed/<?php echo $videoID; ?>?modestbranding=1&controls=2&rel=0&iv_load_policy=3&showinfo=0" frameborder="0" allowfullscreen></iframe>
			<?php } else if ( has_post_thumbnail()) { // Check if Thumbnail exists ?>
                <!-- post thumbnail -->
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail(); // Fullsize image for the single post ?>
				</a>
                <!-- /post thumbnail -->
			<?php } ?>

			<!-- post title -->
			<h1>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
			</h1>
			<!-- /post title -->

			<!-- post details -->
			<span class="date"><?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?></span>
			<!-- /post details -->

		</article>
		<!-- /article -->

	<?php endwhile; ?>

	<?php else: ?>

		<!-- article -->
		<article>

			<h1><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h1>

		</article>
		<!-- /article -->

	<?php endif; ?>
    <?php
        $tags = wp_get_post_tags( get_the_ID() );
        $tag = $tags[0]->name;
        $tagID = $tags[0]->term_id;
        $artist_page = get_page_by_title( $tag, "OBJECT", "artist");
    ?>
    <div class='artist'>
        <?php echo get_the_post_thumbnail($artist_page->ID, "thumbnail"); ?>
        <div class='content'>
            <?php echo $artist_page->post_content; ?>
        </div>
    </div>
    <div class='tracklist'>
        <?php $the_query = new WP_Query( 'tag_id='.$tagID); ?>
	    <?php if ( $the_query->found_posts > 1 ): ?> 
            <h1>Sessions</h1>
            <ul>
                <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                    <li>
                        <?php the_post_thumbnail("tiny"); ?>
                        <h2><?php the_title(); ?></h2>
                        <span><?php echo get_post_meta( get_the_ID(), 'duration', true ); ?></span>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>

	</section>
	<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
