<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">
		
		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<link href='https://fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
		<link href="<?php echo get_template_directory_uri(); ?>/css/style.css" rel="stylesheet" type="text/css">
		<link href="<?php echo get_template_directory_uri(); ?>/css/font.css" rel="stylesheet" type="text/css">
		<link href="<?php echo get_template_directory_uri(); ?>/css/mobile.css" rel="stylesheet" type="text/css">


		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">
		<meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/img/logo.svg" />

		<?php wp_head(); ?>
		<script>
        // conditionizr.com
        // configure environment tests
        conditionizr.config({
            assets: '<?php echo get_template_directory_uri(); ?>',
            tests: {}
        });
        </script>

	</head>
	<body <?php body_class(); ?>>

		<!-- wrapper -->
		<div class="wrapper" id="page-top">

			<!-- header -->
			<header class="header clear" role="banner">
				<nav class="navbar navbar-default navbar-fixed-top">
					<div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
		                <div class="navbar-header page-scroll">
		                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		                        <span class="sr-only">Toggle navigation</span>
		                        <span class="icon-bar"></span>
		                        <span class="icon-bar"></span>
		                        <span class="icon-bar"></span>
		                    </button>
							<a class="navbar-brand page-scroll" href="#page-top">
							<!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
								<img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="Informidata - Solutions PME" class="visible-sm visible-xs" width="200" height="auto">
								<img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="Informidata - Solutions PME" class="hidden-sm hidden-xs" width="325" height="auto">
							</a>
						</div>
		                <!-- Collect the nav links, forms, and other content for toggling -->
		                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		                    <ul class="nav navbar-nav navbar-right">
		                        <li class="hidden">
		                            <a href="#page-top"></a>
		                        </li>
		                        <li>
		                            <a class="page-scroll" href="#apropos">À PROPOS</a>
		                        </li>
		                        <li>
		                            <a class="page-scroll" href="#services">NOS SERVICES</a>
		                        </li>
		                        <li>
		                            <a class="page-scroll" href="#contact">CONTACT</a>
		                        </li>
		       					<li>
		                            <ul>
		                            	<li>
		                                	<a href="http://startcontrol.com/pin.php" target="_blank"><button><i class="fa fa-wrench"></i> ASSISTANCE</button></a>
		                                </li>
		                                <li>
		                                	<a href="tel:4185623359"><button class="no-border"><i class="fa fa-phone"></i>&nbsp;418 562-3359</button></a>
		                                </li>
		                            </ul>
		                        </li>
		                    </ul>
		                    
		                </div>
		                <!-- /.navbar-collapse -->
		            </div>
		            <!-- /.container-fluid -->
				</nav>          
			    <section class="hero">
			    	<div class="text-centered">
			    		<h1><?php the_field('text_hero'); ?></h1>
			    	</div>
			    	<div class="hero-centered">
			    		<img src="<?php the_field('image_hero'); ?>" />
			    	</div>
			    	<div class="color-filter -opacity-normal" style="background-color:<?php the_field('fitre_hero'); ?>"></div>
			        <div class="container-fluid">
			        	
			           	<div class="intro-text"><br>
			               <br>
			               <br>
			               <br>   
			                <div id="fleche">
			                    <span class="effet unu"></span>
			                    <span class="effet doi"></span>
			                    <span class="effet trei"></span>
			                </div>
			            </div>  
			        </div>
			    </section>

			</header>
			<!-- /header -->
