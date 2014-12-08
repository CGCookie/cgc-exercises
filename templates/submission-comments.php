<?php if ( comments_open() ) : ?>
	<div id="comments" class="submission-comments">

		<?php if ( have_comments() ) : ?>
			<strong><?php comments_number('No Conversations', 'One Conversation', '% Conversations' );?> on &#8220;<?php the_title(); ?>&#8221;</strong>
			<ol class="commentlist clearfix">
				<?php wp_list_comments( array( 'callback' => 'cgc_comment' ) ); ?>
			</ol>
			<div class="comment-navigation">
				<div class="older"><?php previous_comments_link() ?></div>
				<div class="newer"><?php next_comments_link() ?></div>
			</div>
		 <?php else : // this is displayed if there are no comments so far ?>
		 <p class="empty">No feedback yet. Perhaps leave some for the author?</p>

		<?php endif; ?>

	</div>
					<?php
					$args = array(
					   	'label_submit'			=>'Leave Feedback',
					    'title_reply'			=>'Your Feedback',
					    'comment_notes_after' 	=> '',
					    'logged_in_as'			=> '',
					   	'comment_field' 		=> '<p class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true"></textarea></p>',
					);

					comment_form($args);
				?>
<?php endif; // if you delete this the sky will fall on your head ?>
