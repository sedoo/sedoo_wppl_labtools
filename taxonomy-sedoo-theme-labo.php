<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package aeris
 */

get_header(); 

//get_template_part( 'template-parts/header-content', 'archive' );
// recup le slug du term courant
$term = get_queried_object();
/**
 * WP_Query pour lister la page Theme correspondante
*/
$args = array(
	'post_type' => array('page'),
	'post_status'           => array( 'publish' ),
	'posts_per_page'        => 1,            // -1 pour liste sans limite
	// 'post__not_in'          => array($postID),    //exclu le post courant
	'tax_query' => array(
		array(
			'taxonomy' => 'sedoo-theme-labo',
			'field'    => 'slug',
			'terms'    => $term->slug,
		),
	),
);
$the_query = new WP_Query( $args );
while ( $the_query->have_posts() ) {
	$the_query->the_post();

?>
<div id="breadcrumbs">
	<div class="wrapper">
		<?php 
		// Show breadcrumb if checked in customizer
		if ( get_theme_mod( 'theme_aeris_breadcrumb' ) == "true") {
			if (function_exists('the_breadcrumb')) the_breadcrumb(); 
		}
		?>		
	</div>
</div>
<div class="site-branding" 
    <?php 
    if (get_the_post_thumbnail_url()) {
    ?>
    style="background-image:url(
    <?php the_post_thumbnail_url( 'full' ); ?>
    );">
    <?php 
    }
    ?>
    <div>    
        <h1 class="site-title" rel="bookmark" style="<?php ?>"><span><?php  the_archive_title(); ?></span></h1>
    </div>
</div><!-- .site-branding -->


<div id="content-area" class="wrapper archives">
	<main id="main" class="site-main" role="main">

		<section role="theme-embed-page">
			<?php
			$tax_slug = get_query_var( 'sedoo-theme-labo' );
			?>
			<article  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<section>
					<div>					
						<?php if($post->post_content != "") : ?>			
						<div class="post-excerpt">	    		            			            	                                                                                            
							<?php the_excerpt();?>
							<p><a href="<?php the_permalink(); ?>" title="<?php echo __( 'More information about ', 'sedoo-wppl-labtools' )?><?php the_title();?>"><?php echo __( 'More information about ', 'sedoo-wppl-labtools' )?><?php the_title();?></a></p>
						</div>
						
						<?php endif; ?>
					</div>
				</section>
			</article>
			<?php
			} // End of the loop.
			?>				
		</section>
<?php 
	/* Restore original Post Data */
	wp_reset_postdata();
	?>


		<?php
		/**
		 * WP_Query pour lister les posts ET les CTP

			*/
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args = array(
			'post_type' => array('post', 'sedoo-research-team', 'sedoo-platform'),
			'post_status'           => array( 'publish' ),
			'posts_per_page'        => 9,            // -1 pour liste sans limite
			'paged'					=> $paged,
			// 'post__not_in'          => array($postID),    //exclu le post courant
			'tax_query' => array(
				array(
					'taxonomy' => 'sedoo-theme-labo',
					'field'    => 'slug',
					'terms'    => $term->slug,
				),
			),
		);
		$the_query = new WP_Query( $args );

		// The Loop
		if ( $the_query->have_posts() ) { ?>

		<section role="listNews" class="posts">
			
		<?php
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
			?>
			<div class="post-container">
			<?php
				include( 'template-parts/content-theme.php' );
			?>
			</div>
			<?php
			} // End of the loop.
			?>				
		</section>
		<?php 
			the_posts_navigation();
			// next_posts_link( 'Older Entries', $the_query->max_num_pages );
			// previous_posts_link( 'Next Entries &raquo;' ); 
			/* Restore original Post Data */
			wp_reset_postdata();
			?>
		<?php
		} else {
			get_template_part( 'template-parts/content', 'none' );
		} 
		?>
	
	</main><!-- #main -->
	<?php 
	// get_sidebar();
	?>
</div><!-- #content-area -->
<?php
get_footer();
?>
