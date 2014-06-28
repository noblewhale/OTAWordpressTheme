<?php get_header(); ?>
  <main role="main">
    <?php if (have_posts()): while (have_posts()) : the_post(); ?>
      <?php
        $videoID = get_post_meta( get_the_ID(), '_tern_wp_youtube_video', true );
        $title = get_the_title();
        preg_match('/&#8220;(.*?)&#8221;/', $title, $song);
        if (count($song) > 0)
        {
          $song = $song[1];
          preg_match('/^(.*?) &#8220;/s', $title, $artist);
          $artist = $artist[1];
        }
        $tags = wp_get_object_terms( get_the_ID(), 'artist' );
        if (count($tags) > 0)
        {
          $tag = $tags[0]->name;
          $tagID = $tags[0]->term_id;
          $artist_page = get_page_by_title( $tag, "OBJECT", "artist" );
        }

        $hasArtistPage = true;
        if (empty($artist_page))
        {
          $artist_page = get_page( get_the_ID() ); 
          $hasArtistPage = false;
        }

        if ($artist)
        {
          // Check for disambiguated _(band) version of article first to avoid serving the wrong article for common words like 'yacht'
          $service_url = 'http://en.wikipedia.org/w/api.php?format=json&action=query&titles='.(urlencode(ucwords(strtolower($artist)))).'_(band)';
          $options = '&prop=revisions&prop=extracts&exintro';
          $curl = curl_init($service_url.$options);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          $curl_response = curl_exec($curl);
          curl_close($curl);
          $decoded = json_decode($curl_response);
          $pages = (array)$decoded->query->pages;
          $pages = array_values($pages);
          if (property_exists($pages[0], "missing")) 
          {
            $service_url = 'http://en.wikipedia.org/w/api.php?format=json&action=query&titles='.(urlencode(ucwords(strtolower($artist))));
            $curl = curl_init($service_url.$options);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
            curl_close($curl);
            $decoded = json_decode($curl_response);
            $pages = (array)$decoded->query->pages;
            $pages = array_values($pages);
          }
          $wikiSummary = $pages[0]->extract;

          // Grab the thumbnail
          $options = '&prop=pageimages&format=json&pithumbsize=548';
          $curl = curl_init($service_url.$options);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          $curl_response = curl_exec($curl);
          curl_close($curl);
          $decoded = json_decode($curl_response);
          $pages = (array)$decoded->query->pages;
          $pages = array_values($pages);
          $wikipediaImageURL = $pages[0]->thumbnail->source;
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
    
          <div class='artist'>

            <?php if ($hasArtistPage) : ?>
              <?php echo get_the_post_thumbnail($artist_page->ID, "fullsize"); ?> 
            <?php else : ?>
              <img src='<?php echo $wikipediaImageURL; ?>' />
            <?php endif; ?>

            <div class='content'>
              <?php echo format_content($artist_page->post_content); ?>
              <?php if (!$hasArtistPage) echo $wikiSummary; ?>
            </div>

          </div>

        </td>
        <td class='right'>
          <div class='tracklist'>
            <?php if ($tagID) : ?>
              <?php $the_query = new WP_Query( array('tag_id'=>$tagID, 'posts_per_page'=>20) ); ?>
              <?php if ( $the_query->found_posts > 1 ): ?> 
                <h1>Sessions</h1>
                <ul>
                  <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                    <?php
                      $t = get_the_title();
                      preg_match('/&#8220;(.*?)&#8221;/', $t, $song);

                      // Skip posts with malformed titles that don't appear to contain a song name in quotes
                      if (count($song) < 1) continue;

                      $song = $song[1];
                      preg_match('/^(.*?) &#8220;/', $t, $artist);
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
