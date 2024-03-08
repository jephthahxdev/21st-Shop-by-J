<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Durotan
 */

get_header();
?>

    <div id="primary" class="content-area">
        <section class="error-404 not-found">
            <h1 class="page-title"><?php esc_html_e( '404', 'durotan' ); ?></h1>
            <div class="page-content">
				<?php esc_html_e( 'This page has been probably moved somewhere..', 'durotan' ); ?>
            </div><!-- .page-content -->
            <a href="<?php echo esc_url( get_home_url() ); ?>"
               class="durotan-button button-larger"><?php echo esc_html__( 'Back to hompage', 'durotan' ); ?></a>
        </section><!-- .error-404 -->

    </div><!-- #primary -->

<?php
get_footer();
