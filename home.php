<?php

if ( !defined( 'ABSPATH' ) )
	exit;

add_action( 'pre_get_posts', function( WP_Query $query ): void {
	global $wp_the_query;
	if ( !$wp_the_query->is_page( 3260 ) )
		return;
	if ( $query->get( 'post_type' ) !== 'post' )
		return;
	if ( $query->get( 'orderby' ) !== 'title' )
		return;
	if ( $query->get( 'order' ) !== 'ASC' )
		return;
	if ( $query->get( 'posts_per_page' ) !== 6 )
		return;
	$query->set( 'nopaging', TRUE );
} );

add_action( 'pre_get_posts', function( WP_Query $query ): void {
	global $wp_the_query;
	if ( !$wp_the_query->is_page( 3260 ) )
		return;
	if ( $query->get( 'post_type' ) !== 'post' )
		return;
	if ( $query->get( 'orderby' ) !== 'rand' )
		return;
	if ( $query->get( 'order' ) !== 'ASC' )
		return;
	if ( $query->get( 'posts_per_page' ) !== 4 )
		return;
	$query->set( 'posts_per_page', 12 );
} );

add_action( 'the_content', function( string $content ): string {
	global $wp_the_query;
	if ( !$wp_the_query->is_page( 3260 ) )
		return $content;
	$count = count( get_posts( [
		'nopaging' => TRUE,
		'fields' => 'ids',
	] ) );
	foreach ( [ 1, 2, 3, 4, ] as $case ) {
		$count = NULL;
		$args = NULL;
		switch ( $case ) {
			case 1:
				$args = [
					'category_name' => 'songs',
				];
				break;
			case 2:
				$args = [
					'post_type' => 'attachment',
					'post_mime_type' => 'text/plain',
					's' => '.chords',
				];
				break;
			case 3:
				$args = [
					'post_type' => 'attachment',
					'post_mime_type' => 'application/pdf',
					's' => '-full',
				];
				break;
			case 4:
				$args = [
					'post_type' => 'attachment',
					'post_mime_type' => 'audio/mpeg',
					's' => '.featured',
				];
				break;
		}
		if ( !is_null( $args ) ) {
			$args['nopaging'] = TRUE;
			$args['fields'] = 'ids';
			$count = count( get_posts( $args ) );
			$pattern = '<div class="het-counter-count odometer" data-count="' . $case . '">';
			$replace = '<div class="het-counter-count odometer" data-count="' . $count . '">';
			$content = mb_ereg_replace( $pattern, $replace, $content );
		}
	}
	return $content;
} );
