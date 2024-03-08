<# if ( data.depth == 0 ) { #>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Mega Menu Content', 'durotan' ) ?>"
   data-panel="mega"><?php esc_html_e( 'Mega Menu', 'durotan' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Mega Menu Background', 'durotan' ) ?>"
   data-panel="background"><?php esc_html_e( 'Background', 'durotan' ) ?></a>
<div class="separator"></div>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Icon', 'durotan' ) ?>"
   data-panel="icon"><?php esc_html_e( 'Icon', 'durotan' ) ?></a>
<# } else if ( data.depth == 1 ) { #>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Menu Content', 'durotan' ) ?>"
   data-panel="content"><?php esc_html_e( 'Menu Content', 'durotan' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu General', 'durotan' ) ?>"
   data-panel="general"><?php esc_html_e( 'General', 'durotan' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Icon', 'durotan' ) ?>"
   data-panel="icon"><?php esc_html_e( 'Icon', 'durotan' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Badges', 'durotan' ) ?>"
   data-panel="badges"><?php esc_html_e( 'Badges', 'durotan' ) ?></a>
<# } else { #>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Menu Icon', 'durotan' ) ?>"
   data-panel="icon"><?php esc_html_e( 'Icon', 'durotan' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu Badges', 'durotan' ) ?>"
   data-panel="badges"><?php esc_html_e( 'Badges', 'durotan' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu General', 'durotan' ) ?>"
   data-panel="general_2"><?php esc_html_e( 'General', 'durotan' ) ?></a>
<# } #>
