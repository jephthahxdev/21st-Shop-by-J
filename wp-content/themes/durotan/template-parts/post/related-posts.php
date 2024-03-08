<?php
/**
 * The template part for displaying related posts
 *
 * @package Sober
 */

$related_posts = new WP_Query( $args );

if ( ! $related_posts->have_posts() ) {
	return;
}

    \Durotan\Markup::instance()->open('related_post_contents',[
        'attr' => [
            'class'    => 'related-posts blog-grid',
        ],
        'actions' => true,
    ]);

        while ( $related_posts->have_posts() ) : $related_posts->the_post();

            get_template_part( 'template-parts/content/content' );

        endwhile;

    \Durotan\Markup::instance()->close('related_post_contents');

wp_reset_postdata();