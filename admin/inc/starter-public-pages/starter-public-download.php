<?php
defined('ABSPATH') or die();
/**
 * HUPA DOWNLOAD OPTIONEN
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

/**====================================================
 * ============ PDF / FILE CUSTOM DOWNLOAD ============
 * ====================================================
 */

$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
$file = filter_input(INPUT_GET, 'file', FILTER_SANITIZE_STRING);

if(!isset($type) || !$file){
    wp_redirect(site_url());
    exit();
}
$upload_dir = wp_get_upload_dir();
$dir = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'pdf' .DIRECTORY_SEPARATOR;
$file = trim($file);

if(!file_exists($dir . $file)){
     wp_redirect(site_url());
     exit();
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($dir . $file);

switch ($type){
    case '0':
        header("Content-Type: $mimeType");
        readfile($dir . $file);
        break;
    case'1':
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Length: ' . filesize($dir . $file));
        readfile($dir . $file);
        break;
}
