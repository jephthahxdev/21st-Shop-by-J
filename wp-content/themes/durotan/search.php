<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Durotan
 */

get_header();
?>
    <?php
    \Durotan\Markup::instance()->open('search_content',[
        'attr' => [
            'id'    => 'primary',
            'class' => 'content-area',
        ],
        'actions' => true,
    ]);
    ?>

		<?php if ( have_posts() ) :

			\Durotan\Markup::instance()->open( 'search_loop',[
				'tag'     => 'main',
				'attr' => [
					'id' => 'main',
					'class' => 'site-main',
				],
				'actions' => true,
			]);

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content/content' );

			endwhile;

			\Durotan\Markup::instance()->close( 'search_loop' );

		else :

			get_template_part( 'template-parts/content/content', 'none' );

		endif;
		?>

    <?php \Durotan\Markup::instance()->close('search_content');  ?>

<?php
get_sidebar();
get_footer();
