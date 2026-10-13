<?php 
/* 
	Template Name: Blank Without Sidebar ( New )
*/
	get_header();
?>

<?php 

	$l = et_page_config();

?>

<?php do_action( 'et_page_heading' ); ?>

	<div class="st-container content-page">
		<div class="st-page-content">
			<div class="row">

				<div class="content">
					<?php if(have_posts()): while(have_posts()) : the_post(); ?>
						
						<?php the_content(); ?>

						
						

					<?php endwhile; else: ?>

						<h3><?php esc_html_e('No pages were found!', 'royal') ?></h3>

					<?php endif; ?>

				</div>

				

			</div><!-- end row-fluid -->

		</div>
	</div><!-- end container -->

<?php
	get_footer();
?>
