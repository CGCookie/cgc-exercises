<?php


/**
*
*	Get a list of user ids who have voted for a specific exercise submission
*
*	@param $submission_id int id of the submissio to get the user votes for
*	@since 5.0.7
*	@return array of user ids
*/
function cgc_exercise_get_voters( $submission_id = 0 ) {

	if ( empty( $submission_id ) ) {
		$submission_id = get_the_ID();
	}

	$voters = get_post_meta( $submission_id, '_cgc_edu_exercise_user_votes', true );

	return !empty( $voters ) ? $voters : false;

}

/**
*	Get the video for an exercise
*
*	@param $exercise_id int id of the lesson to get the video id for
*
*	@return string video id of the exercise
*	@since 5.0
*/
function cgc_exercise_get_video( $exercise_id = 0 ){

	if ( empty( $exercise_id ) )
		return;

	$id = get_post_meta( $exercise_id, '_exercise_video', true );

	return !empty( $id ) ? $id : false;
}


/**
*	Used on the front end to properly escape attributes where users have control over what input is entered
*	as well as through a callback upon saving in the backend
*
*	@since 1.0
*	@return a sanitized string
*/
function cgc_edu_media_filter( $input = '' ) {

	// bail if no input
	if ( empty( $input ) )
		return;

	// setup our array of allowed content to pass
	$allowed_html = array(
		'a' 			=> array(
		    'href' 		=> array(),
		    'title' 	=> array(),
		    'rel'		=> array(),
		    'target'	=> array(),
		    'name' 		=> array()
		),
		'img'			=> array(
			'src' 		=> array(),
			'alt'		=> array(),
			'title'		=> array()
		),
		'p'				=> array(),
		'br' 			=> array(),
		'em' 			=> array(),
		'strong' 		=> array()
	);

	$out = wp_kses( $input, $allowed_html );

	return $out;
}

/**
*
*	Draws teh submission block shown in the submissions tab of a single exercise post type
*
*	@param $id int id of the post to pull author data from
* 	@return a formatted block of html with the submission and pass/fail actions
*/
function cgc_edu_submission_block( $id = 0 ) {

	if ( empty( $id ) )
		return;


	$status = cgc_exercise_submission_status($id);

	if ( 'true' == $status ) {

		$class = 'passed';
		$label = 'Passed!';

	} elseif( 'false' == $status ) {

		$class = 'failed';
		$label = 'Did not pass';

	} elseif( 'open' == $status ) {

		$class = 'open';
		$label = 'Ready for Grading';

	}

	// get the connected exercise
	$connected      = get_post_meta( $id, '_cgc_exercise_submission_linked_to', true);

	// get the type
	$type        = get_post_meta( $connected, '_cgc_edu_exercise_type', true);

	// yhe post author
	$post_author = get_post_field( 'post_author', $id );

	// is the account activated
	$account_activated = class_exists('cgcUserAPI') ? cgcUserAPI::account_status( $post_author ) : false;

	$cover = '';
	if ( 'video' == $type ) {

		// get the info about video
		$video  		= get_post_meta( $id, '_cgc_edu_exercise_video');
		$video_provider = $video ? $video[0]['provider'] : false;
		$video_url 		= $video ? trim($video[0]['url']) : false;

		$video_id      = cgc_get_video_id_from_string($video_provider,$video_url);


		if ( 'youtube' == $video_provider ) {

			$get_yt_cover  = sprintf('https://img.youtube.com/vi/%s/0.jpg',$video_id);
			$yt_cover 	   = $get_yt_cover ? sprintf('<div class="submission--cover" style="background-image:url(%s);"></div>',$get_yt_cover) : null;

			$cover = $yt_cover;

		} elseif ( 'vimeo' == $video_provider ) {

			// vimeo
			//$get_vim_cover = 'vimeo' == $video_provider ? unserialize(file_get_contents('http://vimeo.com/api/v2/video/'.$video_id.'.php')) : false;
			$get_vim_cover = cgc_edu_get_vimeo_cover( $video_id );

			$vim_cover 		= $get_vim_cover ? sprintf('<div class="submission--cover" style="background-image:url(%s);"></div>',$get_vim_cover) : null;

			$cover = $vim_cover;

		} else {

			$cover = false;

		}

	} elseif ( 'image' == $type || 'unity' == $type ) {

		$image 		= get_post_meta( $id, '_cgc_edu_exercise_image', true);
		$image      = $image ? wp_get_attachment_image_src($image,'medium') : 'http://placekitten.com/800/500';

		$cover 		= $image ? sprintf('<div class="submission--cover" style="background-image:url(\'%s\');"></div>',$image[0]) : null;

	} elseif ( 'sketchfab' == $type ) {

		$model  			= get_post_meta( $id , '_cgc_edu_exercise_sketchfab', true);
		$sketchfab_cover 	= cgc_edu_get_sketcfab_cover( $id, $model );
		$cover 				= $sketchfab_cover ? sprintf('<div class="submission--cover" style="background-image:url(\'%s\');"></div>',$sketchfab_cover) : null;

	}

	if ( false == $account_activated ):

		?><li id="submission-<?php echo $id;?>" class="submission--item submission-status--<?php echo $class;?>">
			<a href="<?php echo get_permalink( $id );?>" data-title="<?php echo isset( $id->post_title ) ? esc_html( $id->post_title ) : false;?>">
				<span><?php echo $label;?></span>
				<?php echo $cover;?>
			</a>
			<?php if ( is_page('activity') && is_user_logged_in() ) { ?>
			<div id="submission-controls" class="not-visible">
				<input type="checkbox" id="delete_submission_<?php echo absint( $id );?>" name="delete_submission_<?php echo absint( $id );?>">
				<label for="delete_submission_<?php echo absint( $id );?>" class="checkbox-control checkbox"></label>
			</div>
			<?php } ?>
		</li>

	<?php endif;


}

/**
*
*	Find out if the video url they are submitting is vimeo or youtube
*
*/
function cgc_edu_video_provider( $source = ''){

	if ( empty( $source ) )
		return;

    if (strpos($source, 'youtube') > 0) {

        return 'youtube';

    } elseif (strpos($source, 'vimeo') > 0) {

        return 'vimeo';

    } else {

        return 'unknown';

    }
}


/**
*
*	Get the video ID from a string containing a youtube or vimeo video
*
*	@param $url string url of video
*	@param $provider string vimeo or youtube
*/
function cgc_get_video_id_from_string( $provider = '', $url = '') {

	// bail if no provider or url
	if ( empty( $provider ) || empty( $url ) )
		return;

	switch ($provider) {
		case 'youtube':
			$find = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $matches);
			$id = $matches ? $matches[1] : null;
			break;
		case 'vimeo':
			$find = preg_match_all('%(?:player\.)?vimeo\.com(/video)?/(\d+)%i', $url, $matches);
			$id = $matches ? $matches[2][0] : null;
			break;
		default:
			$find = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $matches);
			$id = $matches ? $matches[1] : null;
			break;
	}

	if ( $id )
		return $id;

}

/**
*	Return just the model from a sketchfab entry string
*
*	@param $post_id int the id of the entry to get the sketchfab url from
*	@since 5.0.2
*/
function cgc_edu_get_sketchfab_model( $post_id = 0 ) {

	if ( empty( $post_id ) )
		return;

	$string  	= get_post_meta( $post_id, '_cgc_edu_exercise_sketchfab', true );
	$string 	= preg_replace('~embed~', '', $string);
	$string     = basename( $string );

	return !empty( $string ) ? $string : false;

}

/**
*
*	Return the thumbnail from the sketchfab api for the model being queried
*
*	@param $post_id int id of the entry to get the model for
*	@param $model string id of the sketchfab model
*	@return a thumbnail image
*/
function cgc_edu_get_sketcfab_cover( $post_id = 0, $model = '' ) {

	if ( empty( $model ) )
		return;

	// return just the model so we can query the api
	$id = cgc_edu_get_sketchfab_model( $post_id );

    $apiurl = sprintf('https://sketchfab.com/oembed?url=https://sketchfab.com/models/%s', $id );

    $return = wp_cache_get('cgc_edu_sketchfab_covr-'.$id );

    if( false === $return ) {

    	$fetch 	= wp_remote_get( $apiurl, array( 'sslverify'=> true ) );
    	$return = json_decode( wp_remote_retrieve_body( $fetch ), true );

        wp_cache_set( 'cgc_edu_sketchfab_covr-'.$id, $return, '', 12 * HOUR_IN_SECONDS );

    }

    $out = isset( $return['thumbnail_url'] ) ? $return['thumbnail_url'] : CGC5_THEME_URL.'/assets/img/default-sketchfab-cover.png';

    if ( $out )
		return $out;
}

/**
*
*	Return the thumbnail from the vimeo api for the video being queried
*
*	@param $id int id of the vimeo video
*	@return a thumbnail image
*/
function cgc_edu_get_vimeo_cover( $id = '' ) {

	if ( empty( $id ) )
		return;

    $apiurl = sprintf('http://vimeo.com/api/v2/video/%s.json', $id );

    $fetch = wp_remote_get($apiurl, array('sslverify'=>false));
    $remote = wp_remote_retrieve_body($fetch);

    $return = wp_cache_get('cgc_edu_vimeo_cover-'.$id.'');

    if( !is_wp_error( $remote ) && false === $return ) {

        $return = json_decode( $remote, true );
        wp_cache_set( 'cgc_edu_vimeo_cover-'.$id.'', $return );
    }

   	$out = $return[0]['thumbnail_medium'];

    if ( $out )
		return $out;
}

/**
*
*	Get a cover for a wisita video
*
*	@param $video_id string the id of the video to get data for
*
*	@return url for image
*	@since 5.6
*/
function cgc_get_wistia_cover( $video_id = '' ) {

	if ( empty( $video_id ) )
		return;

	$api_pass = cgc_get_option('wistia_api_password','cgc_theme_settings');

	if ( empty( $api_pass ) )
		return;

    $apiurl = sprintf('https://api.wistia.com/v1/medias/%s.json?api_password=%s', trim( $video_id ), $api_pass );

    $fetch 	= wp_remote_get( $apiurl, array( 'sslverify' => true) );
    $remote = wp_remote_retrieve_body( $fetch );

    $return = wp_cache_get('cgc_wistia_cover-'.$video_id.'');

    if( !is_wp_error( $remote ) && false === $return ) {

        $return = json_decode( $remote, true );
        wp_cache_set( 'cgc_wistia_cover-'.$video_id.'', $return );
    }

   	$out = $return['thumbnail']['url'];

    if ( $out ) {

    	$ret = esc_url( remove_query_arg( 'image_crop_resized', $out ) );

    	//$upload = cgc_save_wistia_cover( $cover, $video_id );

    	return $ret;

	} else {

		return false;
	}
}

/**
*	Programmatically upload thumbnail from the wisita response to the media library
*
*	@since 5.6
*	@return array an array of data that we can use in cgc_attach_saved_wistia_cover
*	@error getting "invalid file type" so for this reason this function is currently not being used
*/
function cgc_save_wistia_cover( $cover = '', $video_id = '' ) {

	$upload = wp_upload_bits( 'cgc_wistia_cover_'.$video_id, null, $cover );

	return $upload;
}


/**
*
*	Calculates the number of passes on any given exercise submission and determins a pass or fail
*	This is only used to display the status of the vote to the user
*
*	@param $postid int id of the excersie submission to calcuate votes for
*	@param $passing int number required to pass or fail a submission
*/
function cgc_exercise_get_grade( $postid = 0 ) {

	if ( empty( $postid ) )
		$postid = get_the_ID();

	$votes 			= get_post_meta( $postid, '_cgc_edu_exercise_vote', true );

	// get total votes
	$total_votes 	= 	get_post_meta( $postid, '_cgc_edu_exercise_total_votes', true );

	// get votes required to psas
	$connected      = get_post_meta( $postid, '_cgc_exercise_submission_linked_to', true);
	$passing     	= get_post_meta( $connected, '_cgc_edu_exercise_passing', true );

	// threshold
	$threshold = get_post_meta( $connected, '_cgc_edu_exercise_vote_threshold', true ) ? get_post_meta( $connected, '_cgc_edu_exercise_vote_threshold', true ) : 10;

	// has this user voted
	$has_voted     = get_user_meta( get_current_user_ID(), '_cgc_edu_exercise-'.$postid.'_has_voted', true);

	if ( absint( $total_votes ) == absint( $threshold ) ) { // total points have reacehd teh threshold reqiured to trigger grading

		if ( $votes >= $passing ) { // votes are greater than passing

			$return = 'This piece has passed the community vote! <span class="passed">Passed!</span>';

		} else {

			$return = 'This piece did not pass the community vote. <span class="failed">Did not pass</span>';
		}

	} else {

		if ( $has_voted ) {

			$return = 'Thanks for voting!';

		} elseif ( get_current_user_ID() == get_the_author_meta('ID') ) {

			$return = 'Your submission is still be voted on, hang tight!';

		} elseif( is_user_logged_in() ) {

			$return = 'Does the above image meet the exercise criteria?';

		} elseif( !is_user_logged_in() ) {

			$return = '<span class="cgc-edu-meta-login"><a href="#" data-toggle="modal" data-target="#modal--login">Login</a> to grade this exercise!</span>';

		} else {

			$return = '';

		}

	}

	return $return;

}

/**
*
*
*	Count the number of votes for an exercise
*	@param $postid int id of exercise to check for votes on
*	@return the number of votes for the specific submission
*
*/
function cgc_exercise_count_total_votes( $postid = 0 ) {

	if ( empty( $postid ) )
		$postid = get_the_ID();

	$votes = get_post_meta( $postid, '_cgc_edu_exercise_total_votes', true );

	return !empty( $votes ) ? $votes : '0';
}

/**
*
*
*	Return the status of an exercise submission
*	@param $postid int id of exercise submission
*	@return the status of the submission - Passed for passing, Failed for failing, or ready for grading if grading still open
*
*/
function cgc_exercise_submission_status( $postid = '' ) {

	if ( empty( $postid ) )
		return;

	$votes 			= get_post_meta( $postid, '_cgc_edu_exercise_vote', true );

	// get total votes
	$total_votes 	= 	get_post_meta( $postid, '_cgc_edu_exercise_total_votes', true );

	// get votes required to psas
	$connected      = get_post_meta( $postid, '_cgc_exercise_submission_linked_to', true);
	$passing     	= get_post_meta( $connected, '_cgc_edu_exercise_passing', true );

	// threshold
	$threshold = get_post_meta( $connected, '_cgc_edu_exercise_vote_threshold', true ) ? get_post_meta( $connected, '_cgc_edu_exercise_vote_threshold', true ) : 10;

	if ( absint( $total_votes ) == absint( $threshold ) ) { // total points have reacehd teh threshold reqiured to trigger grading

		if ( $votes >= $passing ) { // votes are greater than passing

			return 'true';

		} else {

			return 'false';
		}

	} else {

		return 'open';
	}

}

/**
*
*	Return the id of the connected exercise
*
*	@param $postid int id of the submission
*	@return the id of the connected exercise from the exercise submission
*/
function cgc_exercise_get_connected( $postid = 0 ) {

	if ( empty( $postid ) )
		$postid = get_the_ID();

	$connected = get_post_meta( $postid, '_cgc_exercise_submission_linked_to', true );

	return !empty( $connected ) ? $connected : false;
}

/**
*	Get a list of submissions linked to this exercise post
*
*	@param $id int id of the post to get the submissions for
*	@return array of postids
*/
function cgc_exercise_get_submissions( $postid = ''){

	if ( empty( $postid ) )
		$postid = get_the_ID();

	$submissions = get_post_meta( $postid, '_cgc_exercise_submission_ids', true );

	return !empty($submissions) ? $submissions : false;

}

/**
*
*	Count the total number of submission that are published and not trashed
*
*	@param $postid int id of the exercise to count the submissions of
*	@return int total number of active submissions
*/
function cgc_exercise_count_submissions( $postid = '' ) {

	if ( empty( $postid ) )
		$postid = get_the_ID();

	$submissions 	= cgc_exercise_get_submissions( $postid );

	if ( empty( $submissions ) )
		return;

	$count = 0;

	foreach( (array) $submissions as $key => $id ) {

		$sub = get_post($id);

		if ( FALSE !== get_post_status( $id ) && 'publish' == get_post_status( $id ) ) {

			$count++;

		  	$return = $count;

		}
	}

	return $return;

}

/**
*	Count the number of submissions across all exercises for a specific user, or return all the post_ids of all submissions belonging to a specific user
*
*	@param $user_id int id of the user to fetch submissions for
*	@param $get_count bool true to count the submission, false to return post ids
*	@since 5.0.4
*	@return int or array of ids basedon get_count bool
*/
function cgc_exercise_count_all_submissions( $user_id = 0, $get_count = true ) {

	if ( empty( $user_id ) )
		$user_id = get_current_user_ID();

	$exercises = get_posts( array( 'post_type' => 'exercise', 'posts_per_page' => -1 ) );
	$count = 0;
	$return = array();

	if ( $exercises ) {

		foreach ( (array) $exercises as $exercise ) {

			$submissions 	= cgc_exercise_get_submissions( $exercise->ID );

			if ( !empty( $submissions ) ) {

				foreach( (array) $submissions as $submission ) {

					$post = get_post( $submission );
					$status = get_post_status ( $submission );

					if ( FALSE !== $status && 'publish' == $status && $user_id == $post->post_author ) {

						if ( true == $get_count ) {

							$count++;

							$count = $count;

						} else {

							$return[]= $post->ID;
						}

					}

				}

			}

		}

	} else {

		$count = 0;
	}

	return true == $get_count ? $count : $return;
}

/**
*
*	Add a submission postid to the array of ids for this exercise
*
*	@param $postid - int - id of the exercise to store the submissions to
*	@param $submission_id - int - the id of the submission being created
*
*/
function cgc_exercise_log_submission( $postid = 0, $submission_id = 0 ) {

	if ( empty( $postid ) )
		return;

	// retrieve the IDs of all submissions for this exercise
	$submissions = cgc_exercise_get_submissions( $postid );

	// go through the submissions check if its empty or an array
	if ( ! empty( $submissions ) && is_array( $submissions ) ) {
		$submissions[] = $submission_id;
	} else {
		$submissions = array();
		$submissions[] = $submission_id;
	}

	// create an array of submission_ids linked to this exercise
	update_post_meta( $postid, '_cgc_exercise_submission_ids', $submissions );

	// create a connection for this submission linked to the exercise
	update_post_meta( $submission_id, '_cgc_exercise_submission_linked_to', $postid );

}

/**
*
*	Get the available downloadable files for this exercise
*
*	@param $postid int id of the post to retrieve downloadable files for
*
*/
function cgc_exercise_get_files( $postid = '' ) {

	if ( empty( $postid ) )
		$postid = get_the_ID();

	$files = get_post_meta( $postid, '_cgc_edu_exercise_files', true );

	return !empty($files) ? $files : false;
}

/**
*
*
*
*/
function cgc_exercises_share_exercise(){

	ob_start();?>

	<div class="cgc-exercise--submission__share">
		<a class="button close-modal" href="#">Okay got it</a>
		<a href="https://twitter.com/intent/tweet?text=I just submitted my work into <?php echo esc_attr( get_the_title() );?> via @cgcookie&nbsp;<?php echo get_permalink();?>&url=<?php echo get_permalink();?> " class="button cgc--share__twitter"><i class="icon icon-twitter"></i>Share</a>
	</div>

	<?php return ob_get_clean();
}
