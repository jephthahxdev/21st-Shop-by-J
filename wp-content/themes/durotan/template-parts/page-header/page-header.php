<?php
/**
 * Page Header
 *
 * @package Durotan
 *
 */

\Durotan\Markup::instance()->open( 'page_header',[
	'attr' => [
		'id'    => 'page-header',
		'class' => 'page-header',
	],
	'actions' => true,
]);

	do_action( 'durotan_page_header_content_item' );


\Durotan\Markup::instance()->close( 'page_header' );
