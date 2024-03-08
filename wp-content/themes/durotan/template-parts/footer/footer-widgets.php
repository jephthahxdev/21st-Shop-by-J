<?php
/**
 * Template part for displaying footer widgets
 *
 * @package Durotan
 */

use Durotan\Helper;

$has_widgets = false;
$columns = intval( Helper::get_option( 'footer_widgets_columns' ));

for ( $i = 1; $i <= $columns; $i++ ) {
	$has_widgets = $has_widgets || is_active_sidebar( 'footer-' . $i );
}

if ( ! $has_widgets ) {
	return;
}

$class_columns = $columns == 1 ? 'child-1' : 'child-more';
?>

<div class="footer-widgets widgets-area footer-widgets__<?php echo esc_attr( Helper::get_option( 'footer_widgets_layout' ) ); ?> <?php echo esc_attr( $class_columns ) ?>">
	<div class="footer-container <?php echo esc_attr( apply_filters( 'durotan_footer_container_class', Helper::get_option( 'footer_container' ), 'widget' ) ); ?>">
		<div class="row">
			<?php
				if ( $columns == 4 && Helper::get_option( 'footer_widgets_layout' ) === 'v1' ) {
					if ( Helper::get_option( 'footer_container' ) === 'container' ) :
						?>
							<div class="footer-widget-1 footer-widget col-xs-12 col-sm-4 col-md-3">
								<?php
									ob_start();
									dynamic_sidebar( 'footer-1' );
									$output = ob_get_clean();
									echo apply_filters( 'durotan_footer_widget_1', $output );
								?>
							</div>
							<div class="footer-widgets-diff footer-widget col-xs-12 col-sm-8 col-md-6">
								<?php
									for ( $i = 2; $i <= ( $columns - 1 ); $i++ ) {
										?>
											<div class="footer-widget-diff-<?php echo esc_attr( $i ); ?> footer-widget-diff">
												<?php
													ob_start();
													dynamic_sidebar( 'footer-' . $i );
													$output = ob_get_clean();
													echo apply_filters( 'durotan_footer_widget_' . $i, $output );
												?>
											</div>
										<?php
									}
								?>
							</div>
							<div class="footer-widget-4 footer-widget col-xs-12 col-sm-12 col-md-3">
								<?php
									ob_start();
									dynamic_sidebar( 'footer-4' );
									$output = ob_get_clean();
									echo apply_filters( 'durotan_footer_widget_4', $output );
								?>
							</div>
						<?php
					else :
						?>
							<div class="footer-widget-1 footer-widget col-xs-12 col-sm-4 col-md-3">
								<?php
									ob_start();
									dynamic_sidebar( 'footer-1' );
									$output = ob_get_clean();
									echo apply_filters( 'durotan_footer_widget_1', $output );
								?>
							</div>
							<div class="footer-widgets-diff footer-widget col-xs-12 col-sm-8 col-md-5">
								<?php
									for ( $i = 2; $i <= ( $columns - 1 ); $i++ ) {
										?>
											<div class="footer-widget-diff-<?php echo esc_attr($i); ?> footer-widget-diff">
												<?php
													ob_start();
													dynamic_sidebar( 'footer-' . $i );
													$output = ob_get_clean();
													echo apply_filters( 'durotan_footer_widget_' . $i, $output );
												?>
											</div>
										<?php
									}
								?>
							</div>
							<div class="footer-widget-4 footer-widget col-xs-12 col-sm-12 col-md-4">
								<?php
									ob_start();
									dynamic_sidebar( 'footer-4' );
									$output = ob_get_clean();
									echo apply_filters( 'durotan_footer_widget_4', $output );
								?>
							</div>
						<?php
					endif;
				} elseif ( $columns == 3 && Helper::get_option( 'footer_widgets_layout' ) === 'v3' ) {
					?>
						<div class="footer-widget-1 footer-widget col-xs-12 col-sm-6 col-md-4">
							<?php
								ob_start();
								dynamic_sidebar( 'footer-1' );
								$output = ob_get_clean();
								echo apply_filters( 'durotan_footer_widget_1', $output );
							?>
						</div>
						<div class="footer-widget-2 footer-widget col-xs-12 col-sm-6 col-md-4">
							<?php
								ob_start();
								dynamic_sidebar( 'footer-2' );
								$output = ob_get_clean();
								echo apply_filters( 'durotan_footer_widget_2', $output );
							?>
						</div>
						<div class="footer-widget-3 footer-widget col-xs-12 col-sm-12 col-md-4">
							<?php
								ob_start();
								dynamic_sidebar( 'footer-3' );
								$output = ob_get_clean();
								echo apply_filters( 'durotan_footer_widget_3', $output );
							?>
						</div>
					<?php
				} else {
					for ( $i = 1; $i <= $columns; $i++ ) {
						if ( is_int( 10 / $columns ) ) {
							$column_class = 'col-xs-10-10 col-sm-5-10 col-md-' . 10 / $columns . '-10';
						}else {
							if ( Helper::get_option( 'footer_widgets_layout' ) === 'v2' ) {
								$column_class = 'col-xs-12 col-sm-4 col-md-' . 12 / $columns;
							} else {
								$column_class = 'col-xs-12 col-sm-6 col-md-' . 12 / $columns;
							}
						}
					?>

						<div class="footer-widget-<?php echo esc_attr( $i ); ?> footer-widget <?php echo esc_attr( $column_class ) ?>">
							<?php
								ob_start();
								dynamic_sidebar( 'footer-' . $i );
								$output = ob_get_clean();
								echo apply_filters( 'durotan_footer_widget_' . $i, $output );
							?>
						</div>
					<?php
					}
				}
            ?>
		</div>
	</div>
</div>