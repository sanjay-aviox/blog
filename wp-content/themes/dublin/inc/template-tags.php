<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Dublin
 */

if ( ! function_exists( 'dublin_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function dublin_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'dublin' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous button"><?php next_posts_link( __( '<i class="fa fa-long-arrow-left"></i> Older posts', 'dublin' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next button"><?php previous_posts_link( __( 'Newer posts <i class="fa fa-long-arrow-right"></i>', 'dublin' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'dublin_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function dublin_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'dublin' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous button">%link</div>', _x( '<i class="fa fa-long-arrow-left"></i>&nbsp;%title', 'Previous post link', 'dublin' ) );
				next_post_link(     '<div class="nav-next button">%link</div>',     _x( '%title&nbsp;<i class="fa fa-long-arrow-right"></i>', 'Next post link',     'dublin' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'dublin_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function dublin_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		'<i class="fa fa-calendar"></i> %s',
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		'<i class="fa fa-user"></i> %s',
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';

}
endif;

if ( ! function_exists( 'dublin_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function dublin_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		$categories_list = get_the_category_list( __( ', ', 'dublin' ) );
		if ( $categories_list && dublin_categorized_blog() && get_theme_mod('dublin_single_cats') != 1 ) {
			echo '<i class="fa fa-folder"></i>&nbsp;<span class="cat-links">' . $categories_list . '</span>';
		}
		$tags_list = get_the_tag_list( '', __( ', ', 'dublin' ) );
		if ( $tags_list && is_single() && get_theme_mod('dublin_single_tags') != 1 ) {
			echo '<i class="fa fa-tags"></i>&nbsp;<span class="tags-links">' . $tags_list . '</span>';
		}
	}

	edit_post_link( __( 'Edit', 'dublin' ), '<span class="edit-link"><i class="fa fa-pencil"></i>&nbsp;', '</span>' );
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function dublin_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'dublin_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'dublin_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so dublin_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so dublin_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in dublin_categorized_blog.
 */
function dublin_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'dublin_categories' );
}
add_action( 'edit_category', 'dublin_category_transient_flusher' );
add_action( 'save_post',     'dublin_category_transient_flusher' );
