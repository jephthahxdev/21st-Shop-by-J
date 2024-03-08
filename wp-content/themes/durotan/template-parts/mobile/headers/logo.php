<?php
/**
 * Template part for displaying the logo
 *
 * @package durotan
 */

use Durotan\Helper;

$pre =
$logo_type        = Helper::get_option( 'logo_type' );

$style            = $class = '';

if ( 'svg' == $logo_type ) :
	$logo = Helper::get_option( 'logo_svg' );
elseif ( 'text' == $logo_type ) :
	$logo = Helper::get_option( 'logo_text' );
	$class = 'logo-text';
else:
	$logo = Helper::get_option( 'logo' );

	if ( ! $logo ) {
		$logo = $logo ? $logo : get_theme_file_uri( '/images/logo.svg' );
	}

	$dimension = Helper::get_option( 'logo_dimension' );
	$style     = ! empty( $dimension['width'] ) ? ' width="' . esc_attr( $dimension['width'] ) . '"' : '';
	$style     .= ! empty( $dimension['width'] ) ? ' height="' . esc_attr( $dimension['height'] ) . '"' : '';
endif;

?>
<div class="site-branding">
    <a href="<?php echo esc_url( home_url( '/' ) ) ?>" class="logo <?php echo esc_attr( $class ) ?>">
		<?php if ( 'svg' == $logo_type ) : ?>
           <?php echo '<span class="durotan-svg-icon">' . \Durotan\Icon::sanitize_svg( $logo ) . '</span>'; ?>
		<?php elseif ( 'text' == $logo_type ) : ?>
            <?php echo esc_html( $logo ); ?>
		<?php else : ?>
            <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>"
				 class="logo-dark" <?php echo wp_kses_post( $style ) ?>>
		<?php endif; ?>
    </a>

	<?php if ( is_front_page() && is_home() ) : ?>
        <h1 class="site-title">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
        </h1>
	<?php else : ?>
        <p class="site-title">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
        </p>
	<?php endif; ?>

	<?php if ( ( $description = get_bloginfo( 'description', 'display' ) ) || is_customize_preview() ) : ?>
        <p class="site-description"><?php echo wp_kses_post( $description ); /* WPCS: xss ok. */ ?></p>
	<?php endif; ?>
</div>