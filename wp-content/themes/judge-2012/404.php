<?php get_header(); ?>
  <div id="mainContent">
  		<h2 class="center">Page Not Found</h2> 
        
        <p>Unfortunately, the page you requested is no longer available or has moved (Error 404).</p>
        <p>Please check the topic areas below for complete listings of our sites. </p>
        <ul class='topics404'>
		  <?php
          if (function_exists('cets_get_topics_html')) { cets_get_topics_html(false, false, true, true); }
          ?>
        </ul>
  
    </div><!-- / #mainContent -->

<?php get_footer(); ?>
