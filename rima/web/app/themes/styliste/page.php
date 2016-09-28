<?php /* Template Name: accueil */ get_header(); ?>

	<main role="main">
		<!-- section -->
		<section id="apropos" class="section1" style="background-image: url(<?php echo get_template_directory_uri(); ?>/img/placeholder.jpg);">
			<div class="container">
				<div class="row">
					<h1 class="title"><?php the_field('titre_section1'); ?></h1>
				</div>
				<div class="row">
					<div class="col-xs-7 col-full">
						<?php the_field('texte_section1'); ?>
					</div>
					<div class="col-xs-5 col-full"><img src="<?php echo get_template_directory_uri(); ?>/img/placeholder.jpg" width="250" height="375"></div>				
				</div>
			</div>
		</section>
		<!-- /section1 -->


		<section id="services" class="section2">
			<div class="container">
				<div class="row">
					<h1 class="title"><?php the_field('titre_section2'); ?></h1>
				</div>
				<div class="row -service">
					<div class="col-sm-2 -icon -closet">
						<img src="<?php the_field('icone_penderie'); ?>">
					</div>
					<div class="col-sm-10">
						<p><?php the_field('penderie'); ?></p>
						<div>
							<span class="time"><i class="fa fa-clock-o" aria-hidden="true"></i><?php the_field('temps_penderie'); ?></span>
							<span class="price"><i class="fa fa-tags" aria-hidden="true"></i><?php the_field('prix_penderie'); ?></span>
						</div>
					</div>
				</div>
				<div class="row -service">	
					<div class="col-sm-2 -icon -bag">
						<img src="<?php the_field('icone_magasinage'); ?>">
					</div>
					<div class="col-sm-10">
						<p><?php the_field('magasinage'); ?></p>
						<div>
							<span class="time"><i class="fa fa-clock-o" aria-hidden="true"></i><?php the_field('temps_magasinage'); ?></span>
							<span class="price"><i class="fa fa-tags" aria-hidden="true"></i><?php the_field('prix_magasinage'); ?></span>
						</div>
					</div>
				</div>
				<div class="row -service">
					<div class="col-sm-2 -icon">
						<img src="<?php the_field('icone_style'); ?>">
					</div>
					<div class="col-sm-10">
						<p><?php the_field('style'); ?></p>
						<div>
							<span class="time"><i class="fa fa-clock-o" aria-hidden="true"></i><?php the_field('temps_style'); ?></span>
							<span class="price"><i class="fa fa-tags" aria-hidden="true"></i><?php the_field('prix_style'); ?></span>
						</div>						
					</div>										
				</div>
				<div class="row -service">
					<div class="col-sm-2 -icon">
						<img src="<?php the_field('icone_vacances'); ?>">
					</div>
					<div class="col-sm-10">
						<p><?php the_field('vacances'); ?></p>
						<div>
							<span class="time"><i class="fa fa-clock-o" aria-hidden="true"></i><?php the_field('temps_vacances'); ?></span>
							<span class="price"><i class="fa fa-tags" aria-hidden="true"></i><?php the_field('prix_vacances'); ?></span>
						</div>		
					</div>
				</div>
				<div class="row -service">
					<div class="col-sm-2 -icon">
						<img src="<?php the_field('icone_glamour'); ?>">
					</div>
					<div class="col-sm-10">
						<p><?php the_field('glamour'); ?></p>
						<div>
							<span class="time"><i class="fa fa-clock-o" aria-hidden="true"></i><?php the_field('temps_glamour'); ?></span>
							<span class="price"><i class="fa fa-tags" aria-hidden="true"></i><?php the_field('prix_glamour'); ?></span>
						</div>						
					</div>
				</div>
				<div class="row -service">
					<div class="col-sm-2 -icon">
						<img src="<?php the_field('icone_elora'); ?>">
					</div>
					<div class="col-sm-10">
						<p><?php the_field('elora'); ?></p>
					</div>									
				</div>				
			</div>
		</section>
		<!-- /section2 -->	
		<section id="lookbook">
			<?php if ( function_exists( 'envira_gallery' ) ) { envira_gallery( 'lookbook', 'slug' ); } ?>
		</section>

		<section id="contact" class="contact">
			<div class="container">
				<div class="row">
					<h1 class="title"><?php the_field('titre_contact'); ?></h1>
				</div>
				<div class="row">
					<div class="col-sm-7">
						<?php echo do_shortcode( '[contact-form-7 id="48" title="Formulaire de contact 1"]' ); ?>
					</div>
					
					<div class="col-sm-4">
						<?php the_field('coordonnees'); ?>
					</div>	
					<div class="col-sm-1"></div>				
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
