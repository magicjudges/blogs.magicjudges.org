	<br class="clear" />
 </div> <!-- /#contentArea -->
</div><!-- / #wrapper -->
  <div id="footer">
    <ul>
		<li><a href="<?php echo get_bloginfo('url'); ?>/">Home</a></li>
      <?php wp_list_pages('depth=1&title_li='); ?> 
    </ul>
    <ul>
      <?php wp_register(); ?><li> <?php wp_loginout(); ?></li>
      <?php $fyi_active_signup = get_site_option( 'registration' );
        if ( !$fyi_active_signup ) { $fyi_active_signup = 'all'; }
    
        $fyi_active_signup = apply_filters( 'wpmu_active_signup', $fyi_active_signup ); // return "all", "none", "blog" or "user"
        if ($fyi_active_signup != 'none') {
            echo '<li><a href="/wp-signup.php">Create a new blog</a></li>';
        } ?>
        
    </ul>
  </div><!-- / #footer -->
<div id="cesThemeFooter">
	
        <p id="copyright">Theme based on the <a href="http://fyi.uwex.edu">FYI</a> theme from the <a href="http://www.uwex.edu/ces/">University of Wisconsin Extension - Cooperative Extesion</a></p>
        <ul id="footerLinks">
            <li><a href="http://wordpress.org/">Blogging software based on WordPress</a></li>
        	<li><a href="http://akismet.com/">Protected by Akismet</a></li>
        </ul>
        <br class="clear" />
        
</div> <!-- /#cesThemeFooter -->

<?php wp_footer(); ?>

</body>
</html>