<?php get_header(); ?>
	<main role="main">
	<!-- section -->
	<section>

	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <?php if ( get_post_meta( get_the_ID(), '_tern_wp_youtube_video', true ) ) : ?>
                <?php $videoID = get_post_meta( get_the_ID(), '_tern_wp_youtube_video', true ); ?>
                <iframe 
                    width="980" 
                    height="551" 
                    src="//www.youtube.com/embed/<?php echo $videoID; ?>?modestbranding=1&controls=2&rel=0&iv_load_policy=3&showinfo=0" 
                    frameborder="0" 
                    allowfullscreen>
                </iframe>
			<?php elseif ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
                <!-- post thumbnail -->
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail(); // Fullsize image for the single post ?>
				</a>
                <!-- /post thumbnail -->
			<?php endif; ?>

			<!-- post title -->
			<h1>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
			</h1>
			<!-- /post title -->

			<!-- post details -->
			<span class="date"><?php the_date(); ?></span>
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
    <table>
    <tr><td class='left'>
    <div class='artist'>
        <?php echo get_the_post_thumbnail($artist_page->ID, "thumbnail"); ?>
        <div class='content'>
            <?php echo $artist_page->post_content; ?>
        </div>
    </div>
    </td>
    <td class='right'>
    <div class='tracklist'>
        <?php $the_query = new WP_Query( 'tag_id='.$tagID); ?>
	    <?php if ( $the_query->found_posts > 1 ): ?> 
            <h1>Sessions</h1>
            <ul>
                <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail("tiny"); ?>
                        <div class='track-info'>
                            <h2>
                                <?php 
                                    $t = get_the_title();
                                    preg_match('/&#8220;(.*)&#8221;/', $t, $title);
                                    echo $title[1];
                                ?>
                            </h2>
                            <div class='duration'>
                                <?php 
                                     $seconds = get_post_meta( get_the_ID(), 'duration', true );
                                     $minutes = (int)($seconds/60);
                                     $seconds = $seconds%60;
                                     if ($seconds < 10) $seconds = '0'.$seconds;
                                     echo $minutes.":".$seconds;
                                ?>
                            </div>
                        </div>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>
    </td></tr>
    </table>
	</section>
	<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
