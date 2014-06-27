<?php // Template Name: Session ?>
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
        <form action="<?php bloginfo('url'); ?>/" method="get">
          <input type='hidden' name="page_id" value="6671" />
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

      <?php query_posts('cat='.$_GET['cat'].'&posts_per_page=999&orderby=title&order=asc'); ?>
      <?php get_template_part('loop-sessions'); ?>

		</section>
		<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
