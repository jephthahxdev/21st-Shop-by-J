<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Durotan
 */

get_header();

	\Durotan\Markup::instance()->open( 'primary_content',[
		'attr' => [
			'id'    => 'primary',
			'class' => 'content-area',
		],
		'actions' => false,
	]);

		\Durotan\Markup::instance()->open( 'posts_content',[
			'tag'     => 'main',
			'attr' => [
				'id' => 'main',
				'class' => 'site-main',
			],
			'actions' => true,
		]);

			if ( have_posts() ) :
					global $count;
					$count = 1;
					while ( have_posts() ) :
						the_post();

						echo get_template_part( 'template-parts/content/content', get_post_type() );
						$count++;
					endwhile;

			else :
				get_template_part( 'template-parts/content/content', 'none' );
			endif;

		\Durotan\Markup::instance()->close( 'posts_content' );

	\Durotan\Markup::instance()->close( 'primary_content' );

get_sidebar();
get_footer();