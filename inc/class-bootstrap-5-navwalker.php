<?php

/*
https://github.com/AlexWebLab/bootstrap-5-wordpress-navbar-walker
*/

// bootstrap 5 wp_nav_menu walker
class bootstrap_5_wp_nav_menu_walker extends Walker_Nav_menu
{
  private $current_item;
  private $dropdown_menu_alignment_values = [
    'dropdown-menu-start',
    'dropdown-menu-end',
    'dropdown-menu-sm-start',
    'dropdown-menu-sm-end',
    'dropdown-menu-md-start',
    'dropdown-menu-md-end',
    'dropdown-menu-lg-start',
    'dropdown-menu-lg-end',
    'dropdown-menu-xl-start',
    'dropdown-menu-xl-end',
    'dropdown-menu-xxl-start',
    'dropdown-menu-xxl-end'
  ];

  function start_lvl(&$output, $depth = 0, $args = null)
  {
    $dropdown_menu_class[] = '';
    foreach($this->current_item->classes as $class) {
      if(in_array($class, $this->dropdown_menu_alignment_values)) {
        $dropdown_menu_class[] = $class;
      }
    }
    $indent = str_repeat("\t", $depth);
    $submenu = ($depth > 0) ? ' sub-menu' : '';
    $output .= "\n$indent<ul class=\"dropdown-menu$submenu " . esc_attr(implode(" ",$dropdown_menu_class)) . " depth_$depth\">\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
  {
    $this->current_item = $item;

    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    $li_attributes = '';
    $class_names = $value = '';

    $classes = empty($item->classes) ? array() : (array) $item->classes;
    isset($item->classes[0]) && $item->classes[0] == 'mega-menu' ? $megaMenu = 'dropdown dropdown-mega position-static' : $megaMenu = '';
    $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
    //$classes[] = 'nav-item';
    $classes[] = 'nav-item ' .$megaMenu;
    $classes[] = 'nav-item-' . $item->ID;
    if ($depth && $args->walker->has_children) {
      $classes[] = 'dropend';
    }

    $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    $class_names = ' class="' . esc_attr($class_names) . '"';

    $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
    $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

    $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';

    $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
    $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
    $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
    $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

    $active_class = ($item->current || $item->current_item_ancestor) ? 'active' : '';

      if(isset($item->classes[0]) && $item->classes[0] == 'mega-menu' ){

          $args = array(
              'meta_key' => '_wp_page_template',
              'meta_value' => 'theme-mega-menu-template.php'
          );
          $page = get_pages($args);
          if($page){
              $url = get_rest_url(null, 'wp/v2/pages/').$page[0]->ID;
              $content = file_get_contents($url, true);
              $content = json_decode($content);
              $pageContent = $content->content->rendered;
          } else {
              $pageContent = '';
          }
          $attributes .= ' class="nav-link '. $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false"';
          $argsAfter = '<div class="container-fluid dropdown-menu mega-menu-wrapper hupa-box-shadow">
                            <div class="mega-content p-3">
                                <div class="mega-menu-inner-wrapper">
                                <div class="mega-menu-header"></div>
                                    '.$pageContent.'
                                </div>
                            </div>
                        </div>';
      } else {
          $argsAfter = '';
          $nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link ';
          $attributes .= ( $args->walker->has_children ) ? ' class="'. $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false"' : ' class="'. $nav_link_class . $active_class . '"';
      }

    //$nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link ';
    //$attributes .= ( $args->walker->has_children ) ? ' class="'. $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false"' : ' class="'. $nav_link_class . $active_class . '"';

    $item_output = $args->before;
    $item_output .= '<a' . $attributes . '>';
    $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $argsAfter;
    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }
}