<?php
defined('ABSPATH') or die();

/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */


$data = json_decode(file_get_contents("php://input"));

if($data->client_id !== get_option('hupa_product_client_id')){
    $backMsg =  [
        'client_id' => get_option('hupa_product_client_id'),
        'reply' => 'falsche Client ID',
        'status' => false,
    ];
    echo json_encode($backMsg);
    exit();
}


switch ($data->make_id) {
    case '1':
        $message = json_decode($data->message);
        $backMsg =  [
            'client_id' => get_option('hupa_product_client_id'),
            'reply' => 'Theme deaktiviert',
            'status' => true,
        ];

        update_option('hupa_starter_message',$message->msg);
        delete_option('hupa_starter_product_install_authorize');
        delete_option('hupa_product_client_id');
        delete_option('hupa_product_client_secret');
        break;
    case'2':
        $message = json_decode($data->message);
        $backMsg = [
            'client_id' => get_option('hupa_product_client_id'),
            'reply' => 'Theme Datei gelöscht',
            'status' => true,
        ];
        update_option('hupa_starter_message',$message->msg);
        $file = HUPA_THEME_DIR . $data->aktivierung_path;
        unlink($file);
        delete_option('hupa_starter_product_install_authorize');
        delete_option('hupa_product_client_id');
        delete_option('hupa_product_client_secret');
        break;

    case'download_fonts':
         $status = true;
         $message = '';
         $test = '';
         $fontsDir = THEME_FONTS_DIR;
          if(!is_dir($fontsDir . $data->font_name)){
             if (!mkdir($fontsDir . $data->font_name, 0755, true)) {
                  $status = false;
                  $message .= '#Erstellung der Verzeichnisse schlug fehl...';
              }
             if($status) {
                 foreach ($data->files as $tmp) {
                     $body = [
                         'font_file' => $tmp->font_file,
                         'font_family' => $data->font_name,
                     ];
                     $file = apply_filters('post_scope_resource', 'file/font', $body);
                     $filePath = $fontsDir . $data->font_name . DIRECTORY_SEPARATOR . $tmp->font_file;
                     $file = base64_decode($file->file);
                     @file_put_contents($filePath, $file);
                 }
                 $body = [
                     'css_file' => $data->font_name . '.css',
                     'font_family' => $data->font_name,
                 ];
                 $file = apply_filters('post_scope_resource', 'file/font', $body);
                 @file_put_contents($fontsDir . $file->file_name, $file->file);
                 $message = 'Font "<i>' . $data->font_name . '</i>" erfolgreich installiert!';
                 apply_filters('update_hupa_options', 'no-data', 'sync_font_folder');
             }
          } else {
              $status = false;
              $message = $message = 'Font "<i>'.$data->font_name.'</i>" ist <b>bereits Installiert</b>!';

          }
         $backMsg = [
             'status' => $status,
             'message' => $message,
         ];
        break;

    case'send_versions':
        $backMsg = [
            'status' => true,
            'theme_version' => THEME_VERSION,
            'child_version' => CHILD_VERSION
        ];
        break;
    default:
        $backMsg = [
          'status' => false
        ];
}


$response = new stdClass();
if($data) {
    echo json_encode($backMsg);
}
