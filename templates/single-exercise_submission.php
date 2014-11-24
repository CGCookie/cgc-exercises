<?php get_header(); ?>
	<div class="page-content">
		<div class="page-content-inner">
			<div id="main" class="main-content no-sidebar">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

					?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<!-- Image Mast -->
						<section class="cgc-edu-main cgc-edu-exercise-submission--header">
							<aside class="cgc-edu-exercise-submission--meta">

								<div>
									TITLE
								</div>

								<div class="cgc-edu-helper cgc-edu-helper--exercise-submission">
									How do exercises work?
								</div>

								<div class="cgc-edu-list cgc-edu-exercise-critera">
									<strong>Exercise Criteria</strong>
									<p>Images submitted to an exercise must meet the following examples to be given a pass rating.</p>
									<ul class="cgc-edu-block-list">
										<li>SOmething</li>
										<li>Account for relaly long text just in case things get crazy up in this mother chicken.</li>
										<li>Another awesome thing to account for</li>
									</ul>
								</div>

							</aside>
							<div class="cgc-edu-exercise-submission--image-wrap">
								<div class="cgc-edu-exercise-submission--image">
									<div style="background-image:url('http://placekitten.com/1200/800');"></div>
								</div>
								<div class="cgc-edu-meta">
									<form id="cgc-exercise-vote-form" method="post" enctype="multipart/form-data"> 

										<label for="vote-yes">
											Yes
						                	<input type="radio" name="vote" value="yes"/>
						                </label>

										<label for="vote-no">
											No
						                	<input type="radio" name="vote" value="no"/>
						                </label>

								        <input id="cgc-exercise-vote" type="submit" value="Submit">
									</form>
									<div id="cgc-edu-exercise--vote-results"></div>
								</div>
							</div>
							<div class="cgc-edu-bg-filler"></div>
						</section>

						<!-- Content/Discussion -->
						<section class="cgc-edu-main cgc-edu-exercise-submission--entry">
							<div id="cgc-edu-tabs" class="cgc-edu-tabs--exercise">

								<ul class="tab-nav">
									<li class="nav-one"><a href="#about" class="current">About</a></li>
									<li class="nav-two"><a href="#discussion">Discussion</a></li>
								</ul>

								<div class="tab-content">

									<div id="about">
										<?php echo the_content();?>
									</div>

									<div id="discussion" class="tab-hide">
										DISCUSSION
									</div>

								</div>

							</div>

							<!-- Sidebar -->
							<div id="cgc-edu-sidebar" class="cgc-edu-sidebar--exercise">
								<div class="cgc-edu-sidebar--block cgc-edu-sidebar--block__author">

									<?php echo cgc_edu_author_block( get_the_ID(), true );?>
								</div>
							</div>
						</div>

					</article>

				<?php endwhile;endif;?>
			</div>
		</div>
	</div>

<?php get_footer();