<?php
defined( 'ABSPATH' ) or die();
/**
 * Jens Wiecker PHP Class
 * @package Jens Wiecker WordPress Plugin
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 *
 */

function changeBeitragsListenTemplate($id, $type) {

    $newList = '';
    $template = '';
    switch ($type){
        case'kategorie':
            $template = get_template_directory().'/category.php';
            switch ($id){
                case 1:
                    $newList = 'listen-templates/category.php';
                    break;
                case 2:
                    $newList = 'listen-templates/category-sidebar-left.php';
                    break;
                case 3:
                    $newList = 'listen-templates/category-equal-height-sidebar-right.php';
                    break;
                case 4:
                    $newList = 'listen-templates/category-equal-height.php';
                    break;
                case 5:
                    $newList = 'listen-templates/category-masonry.php';
                    break;
            }
            break;
        case'archiv':
            $template = get_template_directory().'/archive.php';
            switch ($id){
                case 1:
                    $newList = 'listen-templates/archive.php';
                    break;
                case 2:
                    $newList = 'listen-templates/archive-sidebar-left.php';
                    break;
                case 3:
                    $newList = 'listen-templates/archive-equal-height-sidebar-right.php';
                    break;
                case 4:
                    $newList = 'listen-templates/archive-equal-height.php';
                    break;
                case 5:
                    $newList = 'listen-templates/archive-masonry.php';
                    break;
            }
            break;
        case'autor':
            $template = get_template_directory().'/author.php';
            switch ($id){
                case 1:
                    $newList = 'listen-templates/author.php';
                    break;
                case 2:
                    $newList = 'listen-templates/author-sidebar-left.php';
                    break;
                case 3:
                    $newList = 'listen-templates/author-equal-height-sidebar-right.php';
                    break;
                case 4:
                    $newList = 'listen-templates/author-equal-height.php';
                    break;
                case 5:
                    $newList = 'listen-templates/author-masonry.php';
                    break;
            }
            break;
    }

    if($newList && $template){
        $file = file_get_contents($newList, true);
        file_put_contents($template, $file, LOCK_EX);
    }

}