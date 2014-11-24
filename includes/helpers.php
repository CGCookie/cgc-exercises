<?php

/**
*
*	Draws teh author block used on exercise and exercise submission templates
*
*	@param $postid int id of the post to pull author data from
* 	@return a block of actions for an author
*/

function cgc_edu_author_block( $postid = 0 ) {

	if ( empty( $postid ) )
		$postid = get_the_ID();


	$auth_id 	= get_the_author_meta('ID');
	$avatar 	= get_user_meta($auth_id, 'profile_avatar_image', true);

	ob_start();

		if ( $avatar ) {

			printf('<img src="%s" alt="%s">', $avatar, the_author_meta('display_name',$auth_id) );

		} else {

			echo get_avatar( $auth_id, 80 );
		}
		?>
		<p>Instructor: <?php echo the_author_meta('display_name',$auth_id);?></p>
		<a href="#">Follow</a>
		<p>Big bio</p>
		<a href="#">More by this instructor</a>

	<?php return ob_get_clean();
}