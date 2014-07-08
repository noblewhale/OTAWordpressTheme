<?php 
  // Template Name: Session
  if ( isset($_GET['useArtist']) )
  {
    unset($_GET['cat']);
    unset($_GET['_special']);
    $artistSlug = get_term_by('id', $_GET['_artist'], 'artist');
    $artistSlug = $artistSlug->slug;
  }
  else if ( isset($_GET['useCat']) )
  {
    unset($_GET['_artist']);
    unset($_GET['_special']);
  }
  else if ( isset($_GET['useSpecial']) )
  {
    unset($_GET['_artist']);
    unset($_GET['cat']);
    $specialSlug = get_term_by('id', $_GET['_special'], 'special_session');
    $specialSlug = $specialSlug->slug;
  }

?>
<?php get_header(); ?>
  <?php if ( !isset($_GET['cat']) ) $_GET['cat'] = 0; ?>
	<main role="main">
		<!-- section -->
		<section>
      <div class='title'>
        <h1>
          Sessions
          <?php if ( $_GET['cat'] != 0 ) : ?>
      			<?php echo ": ".get_cat_name($_GET['cat']); ?></h1>
          <?php endif; ?>
        </h1>
        <div class='filters'>
          <form action="<?php bloginfo('url'); ?>/" method="get">
            <input type='hidden' name="page_id" value="6671" />
            <input type='hidden' name="useCat" />
            <?php 
              $args = array(
                'show_option_all'    => 'Genre',
                'orderby'            => 'NAME', 
                'order'              => 'ASC',
                'echo'               => 0,
                'selected'           => $_GET['cat']
              ); 
              $select = wp_dropdown_categories( $args  ); 
              $select = preg_replace("#<select([^>]*)>#", "<select$1 onchange='return this.form.submit()'>", $select);
              echo $select;
            ?>
            <noscript><div><input type="submit" value="View" /></div></noscript>
          </form>
        </div>
      </div>

      <?php 
        query_posts(array(
          // Exclude upcoming
          'tag__not_in' => array(577),
          // Filter by category
          'cat' => $_GET['cat'],
          // All the results
          'posts_per_page' => 999,
          // Alpha sort
          'orderby' => 'title',
          'order' => 'asc'
        )); 
      ?>
      <?php get_template_part('loop-sessions'); ?>

		</section>
		<!-- /section -->
    <div class='clear-both'></div>
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
