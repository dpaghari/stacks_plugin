<!-- ======== footer section ======== -->

	<style>

		/* ======== styles for footer ======== */

		footer {
			background-color: #efefef;
			padding: 25px 0;
		}

		footer ul {
			display: table;
			list-style: none;
			margin: 0 auto;
			padding: 0;
		}

		footer ul li {
			display: inline-block;
			margin-right: 30px;
		}

	</style>

	<footer>
		<div class="row">
			<div class="small-12 medium-12 large-12 columns">
				<p class="c-input">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
				<?php wp_nav_menu( array( 'menu' => 'Utility Navigation' ) ); ?>
			</div><!-- /columns -->
		</div><!-- /row -->
	</footer>
