<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>
<?php if ( comments_open() ) : ?>
<div id="comments" class="exercise-comments">

	<?php if ( have_comments() ) : ?>
		<strong><?php comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</strong>
		<ol class="commentlist clearfix">
			<?php wp_list_comments( array( 'callback' => 'cgc_comment' ) ); ?>
		</ol>
		<div class="comment-navigation">
			<div class="older"><?php previous_comments_link() ?></div>
			<div class="newer"><?php next_comments_link() ?></div>
		</div>
	 <?php else : // this is displayed if there are no comments so far ?>
	 <p class="empty">No comments yet. Might we suggest something other than "first post"?</p>

	<?php endif; ?>

</div>
<?php endif; // if you delete this the sky will fall on your head ?>

