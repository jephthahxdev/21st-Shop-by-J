<div id="tamm-panel-badges" class="tamm-panel-badges tamm-panel">
	<div class="tamm-panel-box-large mega-setting">
		<div class="setting-field setting-field-badges">
			<p><label><?php esc_html_e( 'Text', 'durotan' ) ?></label></p>
			<p>
				<input class="tamm-panel-badges_text widefat" type="text" name="{{ taMegaMenu.getFieldName( 'badges_text', data.data['menu-item-db-id'] ) }}"
					value="{{ data.megaData.badges_text }}">
			</p>
		</div>

		<div class="setting-field setting-field-badges setting-field-select">
			<p><label><?php esc_html_e( 'Color', 'durotan' ) ?></label></p>
			<p class="tamm-badges-color">
				<input type="text" class="tamm-badges-color-picker"
					name="{{ taMegaMenu.getFieldName( 'badges_text_color', data.data['menu-item-db-id'] ) }}"
					value="{{ data.megaData.badges_text_color }}">
			</p>
		</div>
	</div>

</div>