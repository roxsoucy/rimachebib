<?php /* Template Name: accueil */ get_header(); ?>

	<main role="main">
		<!-- section -->
		<section id="apropos" class="section1">
			<div class="container">
				<div class="row">
					<h1 class="title"><?php the_field('titre_section1'); ?></h1>
				</div>
				<div class="row">
					<div class="col-md-4">
						<?php the_field('texte_section1'); ?>
					</div>
					<div class="col-md-1"></div>
					<div class="col-md-7">
						<?php if ( function_exists( 'easingslider' ) ) { easingslider( 41 ); } ?>
					</div>					
				</div>
			</div>


	    	<div class="section1-centered">
	    		
	    		<img src="<?php the_field('image_section1'); ?>" />
	    	</div>
	    	<div class="color-filter -opacity-normal" style="background-color:<?php the_field('fitre_section1'); ?>"></div>
		</section>
		<!-- /section1 -->


		<section id="services" class="section2">
			<div class="container">
				<div class="row">
					<h1 class="title"><?php the_field('titre_section2'); ?></h1>
				</div>
				<div class="row">
					<div class="col-sm-4 informatique">
						<i class="fa fa-laptop fa-5x"></i>
						<p><?php the_field('texte_informatique'); ?></p>
					</div>
					<div class="col-sm-4 reseaux">
						<i class="fa fa-globe fa-5x"></i>
						<p><?php the_field('texte_reseaux'); ?></p>
					</div>
					<div class="col-sm-4 wifi">
						<i class="fa fa-wifi fa-5x"></i>
						<p><?php the_field('texte_wifi'); ?></p>
					</div>											
				</div>
				<div class="row">
					<div class="col-sm-4 camera">
						<i class="fa fa-video-camera fa-5x"></i>
						<p><?php the_field('texte_camera'); ?></p>
					</div>
					<div class="col-sm-4 telephone">
						<i class="fa fa-fax fa-5x"></i>
						<p><?php the_field('texte_telephone'); ?></p>
					</div>
					<div class="col-sm-4 son">
						<i class="fa fa-volume-up fa-5x"></i>
						<p><?php the_field('texte_son'); ?></p>
					</div>											
				</div>				
			</div>
	    	<div class="section2-centered">
	    		<img src="<?php the_field('image_section2'); ?>" />
	    	</div>
	    	<div class="color-filter -opacity-dark" style="background-color:<?php the_field('filtre_section2'); ?>"></div>
		</section>
		<!-- /section2 -->	


		<section id="contact" class="contact">
			<div class="container">
				<div class="row">
					<h1 class="title"><?php the_field('titre_contact'); ?></h1>
				</div>
				<div class="row">
					<div class="col-sm-7">
						<?php echo do_shortcode( '[contact-form-7 id="48" title="Formulaire de contact 1"]' ); ?>
					</div>
					<div class="col-sm-1"></div>
					<div class="col-sm-4">
						<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-1')) ?>
					</div>					
				</div>
			</div>
			<!-- /article -->
	    	<div class="contact-centered">
	    		<img src="<?php the_field('img_contact'); ?>" />
	    	</div>
	    	<div class="color-filter -opacity-dark" style="background-color:<?php the_field('contact_filtre'); ?>"></div>
		</section>
		<!-- /contact -->				
	</main>


<?php get_footer(); ?>
