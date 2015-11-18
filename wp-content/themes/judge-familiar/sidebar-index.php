<?php
/**
 * The sidebar containing the index of the page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */
if (!is_active_sidebar('sidebar-1')) {
    return;
}
wp_enqueue_script('sidebar-index', get_template_directory_uri().'/js/sidebar-index.js', array('jquery'), '20151113', true);
?>

<div id="secondary" class="col-lg-3 hidden-md-down sidebar-index" role="complementary">
  <aside id="sidebar-index">
    <nav role="navigation">
  	   <h2><?php _e('Contents', 'judge-familiar'); ?></h2>
       <ul class="nav nav-pills nav-stacked">
       </ul>
    </nav>
  </aside>
</div><!-- #secondary -->
