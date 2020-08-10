<?php
/**
 * @package Dublin
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumb">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" >
				<?php the_post_thumbnail('dublin-thumb'); ?>
			</a>			
		</div>	
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

		<?php if ( 'post' == get_post_type() && (get_theme_mod('dublin_date') != 1) ) : ?>
		<div class="entry-meta">
			<?php dublin_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">	
		<?php if ( (get_theme_mod('full_content') == 1) && is_home() ) : ?>
			<?php the_content(); ?>
		<?php else : ?>
			<?php the_excerpt(); ?>
		<?php endif; ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'dublin' ),
				'after'  => '</div>',
			) );
		?>
	</div>

	<?php if ( get_theme_mod('dublin_cats') != 1 ) : ?>
		<footer class="entry-footer">
			<?php dublin_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-## -->
