<?php
/**
 * Comment functions and definitions.
 *
 * @package Durotan
 */

namespace Durotan;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Comments initial
 *
 */
class Comments {
	/**
	 * Instance
	 *
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instantiate the object.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'durotan_after_close_post_content', array( $this, 'get_comment' ), 20 );

		add_action( 'durotan_after_open_comments_content', array( $this, 'get_title' ), 10 );
		add_action( 'durotan_after_open_comments_content', array( $this, 'comment_content' ), 20 );
		add_action( 'durotan_after_open_comments_content', 'paginate_comments_links', 30 );
		add_action( 'durotan_after_open_comments_content', array( $this, 'comment_fields' ), 40 );

		add_filter( 'comment_form_default_fields', array( $this, 'comment_form_fields' ) );
	}

	/**
	 * If comments are open or we have at least one comment, load up the comment template.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function get_comment() {
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
	}

	/**
	 * Get title
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function get_title() {
		if( ! have_comments()) {
			return;
		}

		$comments_number = get_comments_number();
		$comments_class = $comments_number ? 'has-comments' : '';

		echo '<h5 class="comments-title ' . esc_attr( $comments_class ) . '">';
		printf( // WPCS: XSS OK.
			esc_html( _nx( '%2d Comment', '%2d Comments', $comments_number, 'comments title', 'durotan' ) ),
			number_format_i18n( $comments_number )
		);

		echo '</h5>';
	}


	/**
	 * No comment
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function comment_fields() {
		if ( ! comments_open() ) {
			echo '<p class="no-comments">' . esc_html__( 'Comments are closed.', 'durotan' ) . '</p>';
		} else {
			$comment_field = '<p class="comment-form-comment"><textarea required id="comment" placeholder="' . esc_attr__( 'Write your comment here', 'durotan' ) . '" name="comment" cols="45" rows="7" aria-required="true"></textarea></p>';
			comment_form(
				array(
					'format'        => 'xhtml',
					'comment_field' => $comment_field,
					'title_reply' => __( 'Leave A Comment', 'durotan' ),
				)
			);
		}

	}

	/**
	 * Loop content
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function comment_content() {
		echo '<ol class="comment-list ' . esc_attr( get_comments_number() ? 'has-comments' : '' ) . '">';

		wp_list_comments( array(
			'avatar_size' => 70,
			'short_ping'  => true,
			'style'       => 'ol',
			'callback'    => array( $this, 'durotan_comment' )
		) );

		echo '</ol><!-- .comment-list -->';
	}

	/**
	 * Custom fields comment form
	 *
	 * @since  1.0
	 *
	 * @return  array  $fields
	 */
	public function comment_form_fields() {
		global $aria_req;
		$commenter = wp_get_current_commenter();

		$fields = array(
			'author' => '<p class="comment-form-author">' .
			            '<input id ="author" placeholder="' . esc_attr__( 'Name', 'durotan' ) . ' " name="author" type="text" required value="' . esc_attr( $commenter['comment_author'] ) .
			            '" size    ="30"' . $aria_req . ' /></p>',

			'email' => '<p class="comment-form-email">' .
			           '<input id ="email" placeholder="' . esc_attr__( 'Your Email *', 'durotan' ) . '"name="email" type="email" required value="' . esc_attr( $commenter['comment_author_email'] ) .
			           '" size    ="30"' . $aria_req . ' /></p>',
		);

		return $fields;
	}

	/**
	 * Comment callback function
	 *
	 * @since 1.0.0
	 *
	 * @param object $comment
	 * @param array $args
	 * @param int $depth
	 *
	 * @return string
	 */
	public function durotan_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		extract( $args, EXTR_SKIP );

		$avatar = '';
		if ( $args['avatar_size'] != 0 ) {
			$avatar = get_avatar( $comment, $args['avatar_size'] );
		}

		$classes = get_comment_class( empty( $args['has_children'] ) ? '' : 'parent' );
		$classes = $classes ? implode( ' ', $classes ) : $classes;

		$comments = array(
			'comment_parent'      => 0,
			'comment_ID'          => get_comment_ID(),
			'comment_class'       => $classes,
			'comment_avatar'      => $avatar,
			'comment_author_link' => get_comment_author_link(),
			'comment_link'        => get_comment_link( get_comment_ID() ),
			'comment_date'        => get_comment_date(),
			'comment_time'        => get_comment_time(),
			'comment_approved'    => $comment->comment_approved,
			'comment_text'        => get_comment_text(),
			'comment_reply'       => get_comment_reply_link( array_merge( $args, array(
				'add_below' => 'comment',
				'depth'     => $depth,
				'max_depth' => $args['max_depth']
			) ) )

		);

		$comment = $this->comment_template( $comments );

		echo ! empty( $comment ) ? $comment : '';
	}

	/**
	 * Comment Template function
	 *
	 * @since 1.0.0
	 *
	 * @param object $comment
	 *
	 * @return string
	 */
	public function comment_template( $comments ) {
		$output    = array();
		$output[]  = sprintf( '<li id="comment-%s" class="%s">', esc_attr( $comments['comment_ID'] ), esc_attr( $comments['comment_class'] ) );
		$output[]  = sprintf( '<article id="div-comment-%s" class="comment-body">', $comments['comment_ID'] );
		$output [] = ! empty( $comments['comment_avatar'] ) ? sprintf(
			'<div class="comment-author vcard">%s</div>',
			$comments['comment_avatar'] ) : '';
		$output[]  = '<div class="comment-content"><div class="comment-metadata">';
		$output[]  = sprintf( '<cite class="fn">%s </cite>', $comments['comment_author_link'] );
		$date      = sprintf( esc_html__( 'on %1$s', 'durotan' ), $comments['comment_date'] );
		$output[]  = sprintf( '<a href="%s" class="date">%s</a>', esc_url( $comments['comment_link'] ), $date );
		$output[]  = '</div>';
		if ( $comments['comment_approved'] == '0' ) {
			$output[] = sprintf( '<em class="comment-awaiting-moderation">%s</em>', esc_html__( 'Your comment is awaiting moderation.', 'durotan' ) );
		} else {
			$output[] = '<div class="comment-desc">'.$comments['comment_text'].'</div>';
		}

		$output[] = '<div class="reply">';
		$output[] = $comments['comment_reply'];

		if ( current_user_can( 'edit_comment', $comments['comment_ID'] ) ) {
			$output[] = sprintf( '<a class="comment-edit-link" href="%s">%s</a>', esc_url( admin_url( 'comment.php?action=editcomment&amp;c=' ) . $comments['comment_ID'] ), esc_html__( 'Edit', 'durotan' ) );
		}

		$output[] = '</div></div></article>';

		return implode( ' ', $output );
	}
}
