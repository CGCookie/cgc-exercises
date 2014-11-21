<?php get_header(); ?>

	<div class="page-content">
		<div class="page-content-inner">
			<div id="main" class="main-content no-sidebar">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post();?>

					<article>

						<header>

							<?php echo the_title('<h1>','</h1>');?>

							<div class="post-video">
								VIDEO
							</div>
							<div class="">
								META
							</div>

						</header>


						<section>

							<!-- Tabbed -->
							<div id="cgc-edu-tabs" class="cgc-edu-tabs--exercise">

								<ul class="tab-nav">
									<li class="nav-one"><a href="#about" class="current">About</a></li>
									<li class="nav-two"><a href="#discussion">Discussion</a></li>
									<li class="nav-three"><a href="#files">Exercise Files</a></li>
									<li class="nav-four last"><a href="#submissions">Student Submissions</a></li>
								</ul>

								<div class="tab-content">

									<div id="about">
										ABOUT
									</div>

									<div id="discussion" class="tab-hide">
										DISCUSSION
									</div>

									<div id="files" class="tab-hide">
										EXERCISE FILES
									</div>

									<div id="submissions" class="tab-hide">
										STUDENT SUBMISSIONS
									</div>

								</div>

							</div>

							<div id="cgc-edu-sidebar" class="cgc-edu-sidebar--exercise">
								<div class="cgc-edu-sidebar--inner">
									SIDEBAR
								</div>
							</div>

						</section>

					</article>

				<?php endwhile;endif;?>
			</div>
		</div>
	</div>

<?php get_footer();