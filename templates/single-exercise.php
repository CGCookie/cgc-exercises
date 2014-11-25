<?php get_header();?>

	<div class="page-content">
		<div class="page-content-inner">
			<div id="main" class="main-content no-sidebar">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

					// setup some vars for this template
					$auth_id = get_the_author_meta('ID');

					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<header class="cgc-edu-mast">

							<?php echo the_title('<h1>','</h1>');?>

							<div class="post-video">
								VIDEO
							</div>

							<div class="cgc-edu-meta">
								META
							</div>

						</header>


						<section class="cgc-edu-main">

							<!-- Tabbed -->
							<div id="cgc-edu-tabs" class="cgc-edu-tabs--exercise">

								<ul class="tab-nav">
									<li class="nav-one"><a href="#about" class="current">About</a></li>
									<li class="nav-two"><a href="#discussion">Discussion</a></li>
									<li class="nav-three"><a href="#files">Exercise Files</a></li>
									<li class="nav-four last"><a href="#submissions">Student Submissions</a></li>
								</ul>

								<div class="tab-content">

									<div id="about" class="tab-display">
										<?php echo the_content();?>
									</div>

									<div id="discussion" class="tab-hide tab-display">
										DISCUSSION
									</div>

									<div id="files" class="tab-hide tab-display">
										EXERCISE FILES
									</div>

									<div id="submissions" class="tab-hide tab-display">
										STUDENT SUBMISSIONS
									</div>

								</div>

							</div>

							<div id="cgc-edu-sidebar" class="cgc-edu-sidebar--exercise">
								<div class="cgc-edu-sidebar--block cgc-edu-sidebar--block__actions">
									<ul>
										<li><a href="#"><i class="icon icon-upload"></i>Submit Exercise</a></li>
										<li><a href="#"><i class="icon icon-upload"></i>Add to Watchlist</a></li>
										<li><a href="#"><i class="icon icon-upload"></i>Download HD Video</a></li>
									</ul>
								</div>
								<div class="cgc-edu-sidebar--block cgc-edu-sidebar--block__author">
									<?php echo cgc_edu_author_block();?>
								</div>
								<div class="cgc-edu-sidebar--block cgc-edu-sidebar--block__share">
									<ul>
										<li><a href="#"><i class="icon icon-facebook-sign"></i>Like This</a></li>
										<li><a href="#"><i class="icon icon-twitter-sign"></i>Tweet This</a></li>
										<li><a href="#"><i class="icon icon-pinterest-sign"></i>Pin This</a></li>
									</ul>
								</div>
							</div>

						</section>

					</article>

				<?php endwhile;endif;?>
			</div>
		</div>
	</div>

<?php get_footer();