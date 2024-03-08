<?php
/**
 * Template file for displaying mobile header v1
 *
 * @package Durotan
 */

\Durotan\Markup::instance()->open( 'header_mobile' ,[
	'tag'     => 'div',
	'attr' => [
		'class' => 'header__mobile',
	],
	'actions' => true,
] );

	do_action( 'durotan_header_mobile_content' );

\Durotan\Markup::instance()->close( 'header_mobile' );
