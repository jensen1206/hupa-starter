<?php
	/**
	 * The sidebar containing the main widget area
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
	 *
	 * @package Bootscore
	 */

if ( !function_exists( 'dynamic_sidebar' ) || !is_active_sidebar( 'sidebar-1' )  ) {
    exit;
}

$selSidebar = get_post_meta( $post->ID , '_hupa_select_sidebar');
if($selSidebar[0]){
  $sidebar = 'sidebar-'.$selSidebar[0];
} else {
    $sidebar = 'sidebar-1';
}


if ( is_active_sidebar( $sidebar ) ) : ?>
    <div class="col-md-4 col-xxl-3 mt-4 mt-md-0">
        <aside id="secondary" class="widget-area">
            <?php dynamic_sidebar( $sidebar ); ?>
        </aside>
        <!-- #secondary -->
    </div>
<?php endif;



