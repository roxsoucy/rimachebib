<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">
		
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<link href="https://fonts.googleapis.com/css?family=Dancing+Script:400,700|Open+Sans:300,400,600,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css">
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
	<body <?php body_class(); ?> data-spy="scroll" data-target="#myScrollspy" data-offset="20">

		<!-- wrapper -->
		<div class="wrapper" id="page-top">
<!-- 		                <div class="navbar-header page-scroll">
		                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		                        <span class="sr-only">Toggle navigation</span>
		                        <span class="icon-bar"></span>
		                        <span class="icon-bar"></span>
		                        <span class="icon-bar"></span>
		                    </button>
							<a class="navbar-brand page-scroll" href="#page-top"> -->
							<!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
<!-- 								<img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="Informidata - Solutions PME" class="visible-sm visible-xs" width="200" height="auto">
								<img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="Informidata - Solutions PME" class="hidden-sm hidden-xs" width="325" height="auto">
							</a>
						</div> -->
			<!-- header -->
			<header class="header clear" role="banner">
				<nav class="navbar navbar-default navbar-fixed-top navbar-fixed-middle">
		                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		                        <span class="sr-only">Toggle navigation</span>
		                        <span class="icon-bar"></span>
		                        <span class="icon-bar"></span>
		                        <span class="icon-bar"></span>
		                    </button>					
                <!-- Brand and toggle get grouped for better mobile display -->

		                <!-- Collect the nav links, forms, and other content for toggling -->
		                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		                    <ul class="nav navbar-nav navbar-right" id="myScrollspy">
		                        <li class="hidden">
		                            <a href="#page-top"></a>
		                        </li>
		                        <li>
		                            <a class="page-scroll" href="#apropos">à propos</a>
		                        </li>
		                        <li>
		                            <a class="page-scroll" href="#services">services</a>
		                        </li>
		                        <li>
		                            <a class="page-scroll" href="#lookbook">lookbook</a>
		                        </li>
		                        <li>
		                            <a class="page-scroll" href="#contact">contact</a>
		                        </li>
		                    </ul>
		                    
		                </div>
		                <!-- /.navbar-collapse -->

				</nav>          
			    <section class="hero" style="background-image:url(<?php the_field('image_hero'); ?>);">
			    	<div class="hero-centered">
			    		<img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" width="300" height="362" />
			    	</div>
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
