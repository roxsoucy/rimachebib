			<!-- footer -->
			<footer class="footer" role="contentinfo">
				<div class="container">
					<div class="row">
						<!-- copyright -->
						<p class="copyright">
							Copyright &copy; <?php echo date('Y'); ?>  <strong><?php bloginfo('name'); ?></strong>. Tous droits réservés.	
						</p>
					</div>
				<!-- /copyright -->
				</div>
			
			<!-- /footer -->

		
		<?php wp_footer(); ?>
		</footer>
		</div>
		<!-- /wrapper -->

		<!-- analytics -->
		<script>
		(function(f,i,r,e,s,h,l){i['GoogleAnalyticsObject']=s;f[s]=f[s]||function(){
		(f[s].q=f[s].q||[]).push(arguments)},f[s].l=1*new Date();h=i.createElement(r),
		l=i.getElementsByTagName(r)[0];h.async=1;h.src=e;l.parentNode.insertBefore(h,l)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-XXXXXXXX-XX', 'yourdomain.com');
		ga('send', 'pageview');
		</script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/style.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/classie.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/navbar.js"></script>

	</body>
</html>
