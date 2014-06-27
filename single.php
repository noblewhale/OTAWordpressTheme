<?php get_header(); ?>
  <main role="main">
    <?php if (have_posts()): while (have_posts()) : the_post(); ?>
      <?php
        $videoID = get_post_meta( get_the_ID(), '_tern_wp_youtube_video', true );
        $title = get_the_title();
        preg_match('/&#8220;(.*)&#8221;/', $title, $song);
        if (count($song) > 0)
        {
          $song = $song[1];
          preg_match('/^(.*)&#8220;/', $title, $artist);
          $artist = $artist[1];
        }
        $tags = wp_get_post_tags( get_the_ID() );
        if (count($tags) > 0)
        {
          $tag = $tags[0]->name;
          $tagID = $tags[0]->term_id;
          $artist_page = get_page_by_title( $tag, "OBJECT", "artist" );
        }

        if (empty($artist_page))
        {
          $artist_page = get_page( get_the_ID() ); 
        }
      ?>
      
      <!-- article -->
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      
      <?php if ( $videoID ) : ?>
        
        <!-- post video -->
        <iframe 
          width="980" 
          height="551" 
          src="//www.youtube.com/embed/<?php echo $videoID; ?>?modestbranding=1&controls=2&rel=0&iv_load_policy=3&showinfo=0" 
          frameborder="0" 
          allowfullscreen>
        </iframe>
        
      <?php elseif ( has_post_thumbnail()) : ?>
     
        <!-- post thumbnail -->
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
          <?php the_post_thumbnail(); ?>
        </a>
        
      <?php endif; ?>
        
      <!-- post title -->
      <h1>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
          <?php if ($song && $artist) : ?>
            <span class='artist'><?php echo $artist; ?></span>
            <span class='song'>&#8220;<?php echo $song; ?>&#8221;</span>
          <?php else : ?>
            <?php the_title(); ?>
          <?php endif; ?>
        </a>
      </h1>
      
      <!-- post date -->
      <span class="date"><?php the_date(); ?></span>
        
      </article>
      <!-- /article -->
      
    <?php endwhile; else : ?>

      <!-- article -->
      <article>
        <h1><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h1>
      </article>

    <?php endif; ?>

    <table>
      <tr>
        <td class='left'>
          <div class='artist'>
            <?php echo get_the_post_thumbnail($artist_page->ID, "thumbnail"); ?>
            <div class='content'>
              <?php echo $artist_page->post_content; ?>
            </div>
          </div>
        </td>
        <td class='right'>
          <div class='tracklist'>
            <?php if ($tagID) : ?>
              <?php $the_query = new WP_Query( 'tag_id='.$tagID); ?>
              <?php if ( $the_query->found_posts > 1 ): ?> 
                <h1>Sessions</h1>
                <ul>
                  <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                    <?php
                      $t = get_the_title();
                      preg_match('/&#8220;(.*)&#8221;/', $t, $song);

                      // Skip posts with malformed titles that don't appear to contain a song name in quotes
                      if (count($song) < 1) continue;

                      $song = $song[1];
                      preg_match('/^(.*)&#8220;/', $t, $artist);
                      $artist = $artist[1];
                      $seconds = get_post_meta( get_the_ID(), 'duration', true );
                      $minutes = (int)($seconds/60);
                      $seconds = $seconds%60;
                      if ($seconds < 10) $seconds = '0'.$seconds;
                    ?>
                    <li>
                      <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail("tiny"); ?>
                        <div class='track-info'>
                          <h2><?php echo $song; ?></h2>
                          <h3><?php echo $artist; ?></h3>
                          <div class='duration'><?php echo $minutes.":".$seconds; ?></div>
                        </div>
                      </a>
                    </li>
                  <?php endwhile; ?>
                </ul>
              <?php endif; ?>
              <?php wp_reset_postdata(); ?>
            <?php endif; ?>
          </div>
        </td>
      </tr>
    </table>
  </main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
