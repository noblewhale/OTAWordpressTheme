<?php get_header(); ?>
  <main role="main">
    <?php if (have_posts()): while (have_posts()) : the_post(); ?>
      <?php
        $currentPostID = get_the_ID();
        $videoID = get_post_meta( get_the_ID(), '_tern_wp_youtube_video', true );
        $title = get_the_title();
      
        list($artist, $song) = getArtistAndSong($title);

        $artistTerms = wp_get_object_terms( get_the_ID(), 'artist' );
        if (count($artistTerms) > 0)
        {
          $artistSlug = $artistTerms[0]->name;
          $artistID = $artistTerms[0]->term_id;
          $artist_page = get_page_by_title( $artistSlug, "OBJECT", "artist" );
        }

        $specialSessionTerms = wp_get_object_terms( get_the_ID(), 'special_session' );
        foreach ($specialSessionTerms as $specialSessionTerm)
        {
          $specialSessions[] = $specialSessionTerm->term_id;
        }

        $cats = wp_get_post_categories( get_the_ID() );

        $hasArtistPage = true;
        if (empty($artist_page))
        {
          $artist_page = get_page( get_the_ID() ); 
          $hasArtistPage = false;
        }
        else
        {
          $artistThumbnail = get_the_post_thumbnail($artist_page->ID, "fullsize");
        }
        
        $hasExternalLinks = get_post_meta( $artist_page->ID, 'external_link_url0', true) != ""; 

        if ($artist)
        {
          // Check for disambiguated _(band) version of article first to avoid serving the wrong article for common words like 'yacht'
          $service_url = 'http://en.wikipedia.org/w/api.php?format=json&action=query&titles='.(urlencode(ucwords(strtolower($artist)))).'_(band)';
          $options = '&prop=revisions&prop=extracts&exintro';
          $wikiLink = 'http://en.wikipedia.org/wiki/'.(urlencode(ucwords(strtolower($artist)))).'_(band)';
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
            $wikiLink = 'http://en.wikipedia.org/wiki/'.ucwords(strtolower($artist));
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
        
          <!-- Session date -->
          <span class="date"><?php the_date(); ?></span>
    
          <!-- post title -->
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
              <?php if ($song && $artist) : ?>
                <h1 class='song'><?php echo $song; ?></h1>
                <h2 class='artist'><?php echo $artist; ?></h2>
              <?php else : ?>
                <h1>
                  <?php the_title(); ?>
                </h1>
              <?php endif; ?>
            </a>
          
            <!-- Artist info -->
            <div class='artist'>
                <div class='table-wrap'>
                    
                    <!-- External links -->
                    <?php if ($hasExternalLinks) : ?>
                        <div class='external-links-outer'>
                            <div class='content'>
                                <?php for ($i=0; $i < 5; $i++) : ?>
                                  <?php if (get_post_meta( $artist_page->ID, 'external_link_url'.$i, true) ) : ?>
                                    <div class='external-links'>
                                      <a rel='nofollow' target='_blank' href='<?php echo get_post_meta( $artist_page->ID, 'external_link_url'.$i, true ); ?>'>
                                        <?php echo wp_get_attachment_image( get_post_meta( $artist_page->ID, 'external_link_icon'.$i, true), 32, true ); ?>
                                        <?php echo get_post_meta( $artist_page->ID, 'external_link_label'.$i, true ); ?>
                                      </a>
                                    </div>
                                  <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Artist photo -->
                    <div class='artist-photo'>
                        <div class='content'>
                            <?php if ($artistThumbnail) : ?>
                                <?php echo $artistThumbnail; ?> 
                            <?php else : ?>
                                <img src='<?php echo $wikipediaImageURL; ?>' />
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class='content <?php if ($artistThumbnail || $wikipediaImageURL) echo 'clear-both'; ?>'>
                  <?php echo format_content($artist_page->post_content); ?>
                  <?php if ((!$haveArtistPage || empty($artist_page->post_content)) && !empty($wikiSummary)) : echo $wikiSummary; ?>
                    <span class='wiki-link'>
                      <a href='<?php echo $wikiLink; ?>'>Read more..</a>
                    </span>
                  <?php endif; ?>
                </div>

            </div>
        </td>
        
        <!-- Right column. Track list, recommended sessions, widget space. -->
        <td class='right'>

          <!-- Tracklist -->
          <div class='tracklist'>
            <?php if (true || $artistID) : ?>
              <?php 
                $the_query = new WP_Query( array(
                  'artist' => $artistSlug ,
                  'tax_query' => array(
                    'relation' => 'OR',
                    array(
                      'taxonomy' => 'special_session',
                      'field' => 'id',
                      'terms' => $specialSessions
                    )
                  ),
                  'posts_per_page' => 20
                ) );
              ?>
              <?php if ( $the_query->found_posts > 1 ): ?> 
                <h1>Sessions</h1>
                <ul>
                  <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                    <?php
                      $t = get_the_title();
                      
                      list($artist, $song) = getArtistAndSong( $t ); 
                      
                      if (empty($song)) continue;

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
          <div class='recommended'>
            <?php if (!empty($cats) && $artistID) : ?>
              <?php 
                $the_query = new WP_Query(array( 
                  'tax_query' => array(
                    array(
                      'taxonomy' => 'artist',
                      'field' => 'id',
                      'terms' => array( $artistID ),
                      'operator' => 'NOT IN'
                    )
                  ),
                  'category__in'=>$cats, 
                  'posts_per_page'=>5, 
                  'post__not_in'=>array($currentPostID)
                )); 
              ?>
              <?php if ( $the_query->found_posts > 0 ): ?> 
                <h1>Recommended</h1>
                <ul>
                  <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                    <?php
                      $t = get_the_title();

                      list($artist, $song) = getArtistAndSong( $t ); 
                      
                      if (empty($song)) continue;

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

	        <div class="post-sidebar-widget">
        		<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-post-sidebar')) ?>
        	</div>
        </td>
      </tr>
    </table>
  </main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
