<?php
defined('ABSPATH') or die();
/**
 * ADMIN AJAX
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

$responseJson = new stdClass();
$record = new stdClass();
$responseJson->status = false;
$data = '';
$method = filter_input(INPUT_POST, 'method', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

switch ($method) {
    case 'theme_form_handle':

        $handle = filter_input(INPUT_POST, 'handle', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        if (!$handle) {
            $responseJson->spinner = true;
            $responseJson->msg = sprintf(__('Save <b class="text-danger">failed</b> (transmission error) - %s', 'bootscore'), date('H:i:s', current_time('timestamp')));

            return $responseJson;
        }
        switch ($handle) {

            case'theme_optionen':
                filter_input(INPUT_POST, 'update_aktiv', FILTER_SANITIZE_STRING) ? $record->update_aktiv = 1 : $record->update_aktiv = 0;
                filter_input(INPUT_POST, 'svg_aktiv', FILTER_SANITIZE_STRING) ? $record->svg = 1 : $record->svg = 0;
                filter_input(INPUT_POST, 'gb_aktiv', FILTER_SANITIZE_STRING) ? $record->gutenberg = 1 : $record->gutenberg = 0;
                filter_input(INPUT_POST, 'version_aktiv', FILTER_SANITIZE_STRING) ? $record->version = 1 : $record->version = 0;
                filter_input(INPUT_POST, 'emoji_aktiv', FILTER_SANITIZE_STRING) ? $record->emoji = 1 : $record->emoji = 0;
                filter_input(INPUT_POST, 'css_aktiv', FILTER_SANITIZE_STRING) ? $record->block_css = 1 : $record->block_css = 0;
                filter_input(INPUT_POST, 'optimize', FILTER_SANITIZE_STRING) ? $record->optimize = 1 : $record->optimize = 0;
                filter_input(INPUT_POST, 'gb_widget_aktiv', FILTER_SANITIZE_STRING) ? $record->gb_widget = 1 : $record->gb_widget = 0;

                filter_input(INPUT_POST, 'lizenz_login_aktiv', FILTER_SANITIZE_STRING) ? $record->lizenz_login_aktiv = 1 : $record->lizenz_login_aktiv = 0;
                filter_input(INPUT_POST, 'lizenz_page_aktiv', FILTER_SANITIZE_STRING) ? $record->lizenz_page_aktiv = 1 : $record->lizenz_page_aktiv = 0;

                apply_filters('update_hupa_options', $record, 'wp_optionen');
                $responseJson->spinner = true;
                break;

            case'theme_tools':
                filter_input(INPUT_POST, 'areainfo_aktiv', FILTER_SANITIZE_STRING) ? $areainfo_aktiv = 1 : $areainfo_aktiv = 0;
                $areainfo_css_class = filter_input(INPUT_POST, 'areainfo_css_class', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

                filter_input(INPUT_POST, 'areasocial_aktiv', FILTER_SANITIZE_STRING) ? $areasocial_aktiv = 1 : $areasocial_aktiv = 0;
                $areasocial_css_class = filter_input(INPUT_POST, 'areasocial_css_class', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

                filter_input(INPUT_POST, 'areamenu_aktiv', FILTER_SANITIZE_STRING) ? $areamenu_aktiv = 1 : $areamenu_aktiv = 0;
                $areamenu_css_class = filter_input(INPUT_POST, 'areamenu_css_class', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

                filter_input(INPUT_POST, 'areabtn_aktiv', FILTER_SANITIZE_STRING) ? $areabtn_aktiv = 1 : $areabtn_aktiv = 0;
                $areabtn_css_class = filter_input(INPUT_POST, 'areabtn_css_class', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

                $topArea = [
                    'top_area' => [
                        'info' => [
                            'aktiv' => $areainfo_aktiv,
                            'css_class' => $areainfo_css_class,
                            'slug' => 'areainfo_'
                        ],
                        'social' => [
                            'aktiv' => $areasocial_aktiv,
                            'css_class' => $areasocial_css_class,
                            'slug' => 'areasocial_'
                        ],
                        'menu' => [
                            'aktiv' => $areamenu_aktiv,
                            'css_class' => $areamenu_css_class,
                            'slug' => 'areamenu_'
                        ],
                        'btn' => [
                            'aktiv' => $areabtn_aktiv,
                            'css_class' => $areabtn_css_class,
                            'slug' => 'areabtn_'
                        ],
                    ],
                ];
                apply_filters('update_hupa_options', apply_filters('arrayToObject', $topArea), 'update_top_area_data');
                $responseJson->spinner = true;
                break;

            case'smtp_settings':
                $email_abs_name = filter_input(INPUT_POST, 'email_abs_name', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $email_adresse = filter_input(INPUT_POST, 'email_adresse', FILTER_VALIDATE_EMAIL);
                $smtp_host = filter_input(INPUT_POST, 'smtp_host', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $smtp_port = filter_input(INPUT_POST, 'smtp_port', FILTER_SANITIZE_NUMBER_INT);
                $smtp_secure = filter_input(INPUT_POST, 'smtp_secure', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $email_benutzer = filter_input(INPUT_POST, 'email_benutzer', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $email_passwort = filter_input(INPUT_POST, 'email_passwort', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                filter_input(INPUT_POST, 'smtp_auth_check', FILTER_SANITIZE_STRING) ? $smtp_auth_check = 1 : $smtp_auth_check = 0;

                if (!$email_passwort) {
                    $email_passwort = get_hupa_option('email_passwort');
                }

                $theme_email_settings = [
                    'email_abs_name' => $email_abs_name,
                    'email_adresse' => $email_adresse,
                    'smtp_host' => $smtp_host,
                    'smtp_port' => $smtp_port,
                    'smtp_secure' => $smtp_secure,
                    'email_benutzer' => $email_benutzer,
                    'email_passwort' => $email_passwort,
                    'smtp_auth_check' => $smtp_auth_check
                ];
                apply_filters('update_hupa_options', apply_filters('arrayToObject', $theme_email_settings), 'hupa_smtp');
                $responseJson->spinner = true;
                break;

            case'theme_general':
                filter_input(INPUT_POST, 'fix_header', FILTER_SANITIZE_STRING) ? $record->fix_header = 1 : $record->fix_header = 0;
                filter_input(INPUT_POST, 'fix_footer', FILTER_SANITIZE_STRING) ? $record->fix_footer = 1 : $record->fix_footer = 0;
                filter_input(INPUT_POST, 'scroll_top', FILTER_SANITIZE_STRING) ? $record->scroll_top = 1 : $record->scroll_top = 0;
                filter_input(INPUT_POST, 'edit_link', FILTER_SANITIZE_STRING) ? $record->edit_link = 1 : $record->edit_link = 0;
                filter_input(INPUT_POST, 'login_img_aktiv', FILTER_SANITIZE_STRING) ? $record->login_img_aktiv = 1 : $record->login_img_aktiv = 0;

                filter_input(INPUT_POST, 'top_aktiv', FILTER_SANITIZE_STRING) ? $record->top_aktiv = 1 : $record->top_aktiv = 0;

                $record->top_area_container = filter_input(INPUT_POST, 'top_area_container', FILTER_SANITIZE_NUMBER_INT);

                $record->menu_container = filter_input(INPUT_POST, 'menu_container', FILTER_SANITIZE_NUMBER_INT);
                $record->main_container = filter_input(INPUT_POST, 'main_container', FILTER_SANITIZE_NUMBER_INT);

                $record->logo_size = filter_input(INPUT_POST, 'logo_size', FILTER_SANITIZE_NUMBER_INT);
                $record->menu = filter_input(INPUT_POST, 'menu', FILTER_SANITIZE_NUMBER_INT);
                $record->handy = filter_input(INPUT_POST, 'handy', FILTER_SANITIZE_NUMBER_INT);

                //Fullwidth Container
                $record->fw_top = filter_input(INPUT_POST, 'fw_top', FILTER_SANITIZE_NUMBER_INT);
                $record->fw_bottom = filter_input(INPUT_POST, 'fw_bottom', FILTER_SANITIZE_NUMBER_INT);
                $record->fw_left = filter_input(INPUT_POST, 'fw_left', FILTER_SANITIZE_NUMBER_INT);
                $record->fw_right = filter_input(INPUT_POST, 'fw_right', FILTER_SANITIZE_NUMBER_INT);

                $record->login_logo_url = filter_input(INPUT_POST, 'login_logo_url');
                if ($record->login_logo_url && !filter_var($record->login_logo_url, FILTER_VALIDATE_URL)) {
                    $record->login_logo_url = '';
                }

                $record->logo_image = get_hupa_option('logo_image');
                $record->login_image = get_hupa_option('login_image');
                if (!$record->logo_image) {
                    $record->logo_size = 200;
                }
                //Sonstige Settings
                filter_input(INPUT_POST, 'preloader_aktiv', FILTER_SANITIZE_STRING) ? $record->preloader_aktiv = 1 : $record->preloader_aktiv = 0;
                $bottom_area_text = filter_input(INPUT_POST, 'bottom_area_text');
                $record->bottom_area_text = esc_textarea($bottom_area_text);

                // Sitemap
                filter_input(INPUT_POST, 'sitemap_post', FILTER_SANITIZE_STRING) ? $record->sitemap_post = 1 : $record->sitemap_post = 0;
                filter_input(INPUT_POST, 'sitemap_page', FILTER_SANITIZE_STRING) ? $record->sitemap_page = 1 : $record->sitemap_page = 0;

                //WooCommerce
                filter_input(INPUT_POST, 'woocommerce_aktiv', FILTER_SANITIZE_STRING) ? $record->woocommerce_aktiv = 1 : $record->woocommerce_aktiv = 0;
                filter_input(INPUT_POST, 'woocommerce_sidebar', FILTER_SANITIZE_STRING) ? $record->woocommerce_sidebar = 1 : $record->woocommerce_sidebar = 0;

                //Soziale Medien
                filter_input(INPUT_POST, 'social_symbol_color', FILTER_SANITIZE_STRING) ? $record->social_symbol_color = 1 : $record->social_symbol_color = 0;
                $record->social_type = filter_input(INPUT_POST, 'social_type', FILTER_SANITIZE_NUMBER_INT);
                $record->social_extra_css = filter_input(INPUT_POST, 'social_extra_css', FILTER_SANITIZE_STRING);

                // Soziale ARCHIV SEITEN
                filter_input(INPUT_POST, 'social_kategorie', FILTER_SANITIZE_STRING) ? $record->social_kategorie = 1 : $record->social_kategorie = 0;
                filter_input(INPUT_POST, 'social_author', FILTER_SANITIZE_STRING) ? $record->social_author = 1 : $record->social_author = 0;
                filter_input(INPUT_POST, 'social_archiv', FILTER_SANITIZE_STRING) ? $record->social_archiv = 1 : $record->social_archiv = 0;
                filter_input(INPUT_POST, 'social_farbig', FILTER_SANITIZE_STRING) ? $record->social_farbig = 1 : $record->social_farbig = 0;

                // Template Vorlagen
                $record->kategorie_template = filter_input(INPUT_POST, 'kategorie_template', FILTER_SANITIZE_NUMBER_INT);
                $record->archiv_template = filter_input(INPUT_POST, 'archiv_template', FILTER_SANITIZE_NUMBER_INT);
                $record->autoren_template = filter_input(INPUT_POST, 'autoren_template', FILTER_SANITIZE_NUMBER_INT);

                filter_input(INPUT_POST, 'kategorie_image', FILTER_SANITIZE_STRING) ? $record->kategorie_image = 1 : $record->kategorie_image = 0;
                filter_input(INPUT_POST, 'archiv_image', FILTER_SANITIZE_STRING) ? $record->archiv_image = 1 : $record->archiv_image = 0;
                filter_input(INPUT_POST, 'author_image', FILTER_SANITIZE_STRING) ? $record->author_image = 1 : $record->author_image = 0;

                //Post Info Settings
                filter_input(INPUT_POST, 'post_kategorie', FILTER_SANITIZE_STRING) ? $record->post_kategorie = 1 : $record->post_kategorie = 0;
                filter_input(INPUT_POST, 'post_date', FILTER_SANITIZE_STRING) ? $record->post_date = 1 : $record->post_date = 0;
                filter_input(INPUT_POST, 'post_autor', FILTER_SANITIZE_STRING) ? $record->post_autor = 1 : $record->post_autor = 0;
                filter_input(INPUT_POST, 'post_kommentar', FILTER_SANITIZE_STRING) ? $record->post_kommentar = 1 : $record->post_kommentar = 0;
                filter_input(INPUT_POST, 'post_tags', FILTER_SANITIZE_STRING) ? $record->post_tags = 1 : $record->post_tags = 0;
                filter_input(INPUT_POST, 'post_breadcrumb', FILTER_SANITIZE_STRING) ? $record->post_breadcrumb = 1 : $record->post_breadcrumb = 0;

                apply_filters('update_hupa_options', $record, 'hupa_general');
                //TODO JOB WARNING UPDATE CSS FILE
                apply_filters('generate_theme_css', '');

                $responseJson->spinner = true;
                break;

            case'logo_image':
                $record->type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $record->id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
                apply_filters('update_hupa_options', $record, 'image_upload');
                $responseJson->spinner = true;
                break;

            case 'theme_fonts':
                $record->fontType = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $record->font_family = filter_input(INPUT_POST, 'font_family', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $record->font_style = filter_input(INPUT_POST, 'font_style', FILTER_SANITIZE_NUMBER_INT);
                $record->font_size = filter_input(INPUT_POST, 'font_size', FILTER_SANITIZE_STRING);
                $record->font_height = filter_input(INPUT_POST, 'font_height', FILTER_SANITIZE_STRING);
                $record->font_color = filter_input(INPUT_POST, 'font_color', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                filter_input(INPUT_POST, 'font_display_check', FILTER_SANITIZE_STRING) ? $record->font_display_check = 1 : $record->font_display_check = 0;
                filter_input(INPUT_POST, 'font_bs_check', FILTER_SANITIZE_STRING) ? $record->font_bs_check = 1 : $record->font_bs_check = 0;
                //NUR Footer Widget
                filter_input(INPUT_POST, 'font_txt_decoration', FILTER_SANITIZE_STRING) ? $record->font_txt_decoration = 1 : $record->font_txt_decoration = 0;

                apply_filters('update_hupa_options', $record, 'hupa_fonts');
                //TODO JOB WARNING UPDATE CSS FILE
                apply_filters('generate_theme_css', '');

                $responseJson->spinner = true;
                break;

            case 'theme_colors':

                //SEITEN FARBEN
                $record->site_bg = filter_input(INPUT_POST, 'site_bg', FILTER_SANITIZE_STRING);
                $record->nav_bg = filter_input(INPUT_POST, 'nav_bg', FILTER_SANITIZE_STRING);
                $record->nav_bg_opacity = filter_input(INPUT_POST, 'nav_bg_opacity', FILTER_SANITIZE_NUMBER_INT);
                $record->footer_bg = filter_input(INPUT_POST, 'footer_bg', FILTER_SANITIZE_STRING);
                $record->mega_menu_bg = filter_input(INPUT_POST, 'mega_menu_bg', FILTER_SANITIZE_STRING);

                //UPPERCASE
                filter_input(INPUT_POST, 'menu_uppercase', FILTER_SANITIZE_STRING) ? $record->menu_uppercase = 1 : $record->menu_uppercase = 0;

                //MENU BUTTON
                $record->menu_btn_bg_color = filter_input(INPUT_POST, 'menu_btn_bg_color', FILTER_SANITIZE_STRING);
                $record->menu_btn_color = filter_input(INPUT_POST, 'menu_btn_color', FILTER_SANITIZE_STRING);
                $record->menu_btn_bg_opacity = filter_input(INPUT_POST, 'menu_btn_bg_opacity', FILTER_SANITIZE_NUMBER_INT);

                //MENU BUTTON AKTIV
                $record->menu_btn_active_bg = filter_input(INPUT_POST, 'menu_btn_active_bg', FILTER_SANITIZE_STRING);
                $record->menu_btn_active_color = filter_input(INPUT_POST, 'menu_btn_active_color', FILTER_SANITIZE_STRING);
                $record->menu_btn_active_bg_opacity = filter_input(INPUT_POST, 'menu_btn_active_bg_opacity', FILTER_SANITIZE_NUMBER_INT);

                //MENU BUTTON HOVER
                $record->menu_btn_hover_bg = filter_input(INPUT_POST, 'menu_btn_hover_bg', FILTER_SANITIZE_STRING);
                $record->menu_btn_hover_color = filter_input(INPUT_POST, 'menu_btn_hover_color', FILTER_SANITIZE_STRING);
                $record->menu_btn_hover_bg_opacity = filter_input(INPUT_POST, 'menu_btn_hover_bg_opacity', FILTER_SANITIZE_NUMBER_INT);

                //DROPDOWN
                $record->menu_dropdown_bg = filter_input(INPUT_POST, 'menu_dropdown_bg', FILTER_SANITIZE_STRING);
                $record->menu_dropdown_color = filter_input(INPUT_POST, 'menu_dropdown_color', FILTER_SANITIZE_STRING);
                $record->menu_dropdown_bg_opacity = filter_input(INPUT_POST, 'menu_dropdown_bg_opacity', FILTER_SANITIZE_NUMBER_INT);

                //DROPDOWN AKTIV
                $record->menu_dropdown_active_bg = filter_input(INPUT_POST, 'menu_dropdown_active_bg', FILTER_SANITIZE_STRING);
                $record->menu_dropdown_active_color = filter_input(INPUT_POST, 'menu_dropdown_active_color', FILTER_SANITIZE_STRING);
                $record->menu_dropdown_active_bg_opacity = filter_input(INPUT_POST, 'menu_dropdown_active_bg_opacity', FILTER_SANITIZE_NUMBER_INT);

                //DROPDOWN HOVER
                $record->menu_dropdown_hover_bg = filter_input(INPUT_POST, 'menu_dropdown_hover_bg', FILTER_SANITIZE_STRING);
                $record->menu_dropdown_hover_color = filter_input(INPUT_POST, 'menu_dropdown_hover_color', FILTER_SANITIZE_STRING);
                $record->menu_dropdown_hover_bg_opacity = filter_input(INPUT_POST, 'menu_dropdown_hover_bg_opacity', FILTER_SANITIZE_NUMBER_INT);

                //LOGIN SEITE
                $record->login_bg = filter_input(INPUT_POST, 'login_bg', FILTER_SANITIZE_STRING);
                $record->login_color = filter_input(INPUT_POST, 'login_color', FILTER_SANITIZE_STRING);
                $record->login_btn_bg = filter_input(INPUT_POST, 'login_btn_bg', FILTER_SANITIZE_STRING);
                $record->login_btn_color = filter_input(INPUT_POST, 'login_btn_color', FILTER_SANITIZE_STRING);

                //LINK COLOR
                $record->link_color = filter_input(INPUT_POST, 'link_color', FILTER_SANITIZE_STRING);
                $record->link_aktiv_color = filter_input(INPUT_POST, 'link_aktiv_color', FILTER_SANITIZE_STRING);
                $record->link_hover_color = filter_input(INPUT_POST, 'link_hover_color', FILTER_SANITIZE_STRING);

                //TOP AREA COLOR
                $record->top_bg_color = filter_input(INPUT_POST, 'top_bg_color', FILTER_SANITIZE_STRING);
                $record->top_font_color = filter_input(INPUT_POST, 'top_font_color', FILTER_SANITIZE_STRING);
                $record->top_bg_opacity = filter_input(INPUT_POST, 'top_bg_opacity', FILTER_SANITIZE_NUMBER_INT);

                //ScrollToTop Button
                $record->scroll_btn_bg = filter_input(INPUT_POST, 'scroll_btn_bg', FILTER_SANITIZE_STRING);
                $record->scroll_btn_color = filter_input(INPUT_POST, 'scroll_btn_color', FILTER_SANITIZE_STRING);

                //WIDGET BACKGROUND COLOR
                $record->widget_bg = filter_input(INPUT_POST, 'widget_bg', FILTER_SANITIZE_STRING);
                $record->widget_border_color = filter_input(INPUT_POST, 'widget_border_color', FILTER_SANITIZE_STRING);
                filter_input(INPUT_POST, 'widget_border_aktiv', FILTER_SANITIZE_STRING) ? $record->widget_border_aktiv = 1 : $record->widget_border_aktiv = 0;

                apply_filters('update_hupa_options', $record, 'theme_colors');

                //TODO JOB WARNING UPDATE CSS FILE
                apply_filters('generate_theme_css', '');

                $responseJson->spinner = true;
                break;

            case 'theme_social':
                filter_input(INPUT_POST, 'twitter_post_check', FILTER_SANITIZE_STRING) ? $twitter_post_check = 1 : $twitter_post_check = 0;
                filter_input(INPUT_POST, 'twitter_top_check', FILTER_SANITIZE_STRING) ? $twitter_top_check = 1 : $twitter_top_check = 0;
                $twitter_share_txt = filter_input(INPUT_POST, 'twitter_share_txt', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $twitter_url = filter_input(INPUT_POST, 'twitter_url', FILTER_VALIDATE_URL);

                filter_input(INPUT_POST, 'facebook_post_check', FILTER_SANITIZE_STRING) ? $facebook_post_check = 1 : $facebook_post_check = 0;
                filter_input(INPUT_POST, 'facebook_top_check', FILTER_SANITIZE_STRING) ? $facebook_top_check = 1 : $facebook_top_check = 0;
                $facebook_share_txt = filter_input(INPUT_POST, 'facebook_share_txt', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $facebook_url = filter_input(INPUT_POST, 'facebook_url', FILTER_VALIDATE_URL);

                filter_input(INPUT_POST, 'whatsapp_post_check', FILTER_SANITIZE_STRING) ? $whatsapp_post_check = 1 : $whatsapp_post_check = 0;
                filter_input(INPUT_POST, 'whatsapp_top_check', FILTER_SANITIZE_STRING) ? $whatsapp_top_check = 1 : $whatsapp_top_check = 0;
                $whatsapp_share_txt = filter_input(INPUT_POST, 'whatsapp_share_txt', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $whatsapp_url = filter_input(INPUT_POST, 'whatsapp_url', FILTER_VALIDATE_URL);

                filter_input(INPUT_POST, 'pinterest_post_check', FILTER_SANITIZE_STRING) ? $pinterest_post_check = 1 : $pinterest_post_check = 0;
                filter_input(INPUT_POST, 'pinterest_top_check', FILTER_SANITIZE_STRING) ? $pinterest_top_check = 1 : $pinterest_top_check = 0;
                $pinterest_share_txt = filter_input(INPUT_POST, 'pinterest_share_txt', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $pinterest_url = filter_input(INPUT_POST, 'pinterest_url', FILTER_VALIDATE_URL);

                filter_input(INPUT_POST, 'linkedin_post_check', FILTER_SANITIZE_STRING) ? $linkedin_post_check = 1 : $linkedin_post_check = 0;
                filter_input(INPUT_POST, 'linkedin_top_check', FILTER_SANITIZE_STRING) ? $linkedin_top_check = 1 : $linkedin_top_check = 0;
                $linkedin_share_txt = filter_input(INPUT_POST, 'linkedin_share_txt', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $linkedin_url = filter_input(INPUT_POST, 'linkedin_url', FILTER_VALIDATE_URL);

                filter_input(INPUT_POST, 'reddit_post_check', FILTER_SANITIZE_STRING) ? $reddit_post_check = 1 : $reddit_post_check = 0;
                filter_input(INPUT_POST, 'reddit_top_check', FILTER_SANITIZE_STRING) ? $reddit_top_check = 1 : $reddit_top_check = 0;
                $reddit_share_txt = filter_input(INPUT_POST, 'reddit_share_txt', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $reddit_url = filter_input(INPUT_POST, 'reddit_url', FILTER_VALIDATE_URL);

                filter_input(INPUT_POST, 'tumblr_post_check', FILTER_SANITIZE_STRING) ? $tumblr_post_check = 1 : $tumblr_post_check = 0;
                filter_input(INPUT_POST, 'tumblr_top_check', FILTER_SANITIZE_STRING) ? $tumblr_top_check = 1 : $tumblr_top_check = 0;
                $tumblr_share_txt = filter_input(INPUT_POST, 'tumblr_share_txt', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $tumblr_url = filter_input(INPUT_POST, 'tumblr_url', FILTER_VALIDATE_URL);

                filter_input(INPUT_POST, 'buffer_post_check', FILTER_SANITIZE_STRING) ? $buffer_post_check = 1 : $buffer_post_check = 0;
                filter_input(INPUT_POST, 'buffer_top_check', FILTER_SANITIZE_STRING) ? $buffer_top_check = 1 : $buffer_top_check = 0;
                $buffer_share_txt = filter_input(INPUT_POST, 'buffer_share_txt', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $buffer_url = filter_input(INPUT_POST, 'buffer_url', FILTER_VALIDATE_URL);

                filter_input(INPUT_POST, 'mix_post_check', FILTER_SANITIZE_STRING) ? $mix_post_check = 1 : $mix_post_check = 0;
                filter_input(INPUT_POST, 'mix_top_check', FILTER_SANITIZE_STRING) ? $mix_top_check = 1 : $mix_top_check = 0;
                $mix_share_txt = filter_input(INPUT_POST, 'mix_share_txt', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $mix_url = filter_input(INPUT_POST, 'mix_url', FILTER_VALIDATE_URL);

                filter_input(INPUT_POST, 'vk_post_check', FILTER_SANITIZE_STRING) ? $vk_post_check = 1 : $vk_post_check = 0;
                filter_input(INPUT_POST, 'vk_top_check', FILTER_SANITIZE_STRING) ? $vk_top_check = 1 : $vk_top_check = 0;
                $vk_share_txt = filter_input(INPUT_POST, 'vk_share_txt', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $vk_url = filter_input(INPUT_POST, 'vk_url', FILTER_VALIDATE_URL);

                filter_input(INPUT_POST, 'email_post_check', FILTER_SANITIZE_STRING) ? $email_post_check = 1 : $email_post_check = 0;
                filter_input(INPUT_POST, 'email_top_check', FILTER_SANITIZE_STRING) ? $email_top_check = 1 : $email_top_check = 0;
                $email_share_txt = filter_input(INPUT_POST, 'email_share_txt', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $email_url = filter_input(INPUT_POST, 'email_url', FILTER_VALIDATE_URL);

                filter_input(INPUT_POST, 'print_post_check', FILTER_SANITIZE_STRING) ? $print_post_check = 1 : $print_post_check = 0;
                filter_input(INPUT_POST, 'print_top_check', FILTER_SANITIZE_STRING) ? $print_top_check = 1 : $print_top_check = 0;

                $media = [
                    'social_media' => [
                        'facebook' => [
                            'slug' => 'facebook_',
                            'post_check' => $facebook_post_check,
                            'top_check' => $facebook_top_check,
                            'share_txt' => $facebook_share_txt,
                            'url' => $facebook_url,
                        ],
                        'twitter' => [
                            'slug' => 'twitter_',
                            'post_check' => $twitter_post_check,
                            'top_check' => $twitter_top_check,
                            'share_txt' => $twitter_share_txt,
                            'url' => $twitter_url,
                        ],
                        'whatsapp' => [
                            'slug' => 'whatsapp_',
                            'post_check' => $whatsapp_post_check,
                            'top_check' => $whatsapp_top_check,
                            'share_txt' => $whatsapp_share_txt,
                            'url' => $whatsapp_url,
                        ],
                        'pinterest' => [
                            'slug' => 'pinterest_',
                            'post_check' => $pinterest_post_check,
                            'top_check' => $pinterest_top_check,
                            'share_txt' => $pinterest_share_txt,
                            'url' => $pinterest_url,
                        ],
                        'linkedin' => [
                            'slug' => 'linkedin_',
                            'post_check' => $linkedin_post_check,
                            'top_check' => $linkedin_top_check,
                            'share_txt' => $linkedin_share_txt,
                            'url' => $linkedin_url,
                        ],
                        'reddit' => [
                            'slug' => 'reddit_',
                            'post_check' => $reddit_post_check,
                            'top_check' => $reddit_top_check,
                            'share_txt' => $reddit_share_txt,
                            'url' => $reddit_url,
                        ],
                        'tumblr' => [
                            'slug' => 'tumblr_',
                            'post_check' => $tumblr_post_check,
                            'top_check' => $tumblr_top_check,
                            'share_txt' => $tumblr_share_txt,
                            'url' => $tumblr_url,
                        ],
                        'buffer' => [
                            'slug' => 'buffer_',
                            'post_check' => $buffer_post_check,
                            'top_check' => $buffer_top_check,
                            'share_txt' => $buffer_share_txt,
                            'url' => $buffer_url,
                        ],
                        'mix' => [
                            'slug' => 'mix_',
                            'post_check' => $mix_post_check,
                            'top_check' => $mix_top_check,
                            'share_txt' => $mix_share_txt,
                            'url' => $mix_url,
                        ],
                        'vk' => [
                            'slug' => 'vk_',
                            'post_check' => $vk_post_check,
                            'top_check' => $vk_top_check,
                            'share_txt' => $vk_share_txt,
                            'url' => $vk_url,
                        ],
                        'email' => [
                            'slug' => 'email_',
                            'post_check' => $email_post_check,
                            'top_check' => $email_top_check,
                            'share_txt' => $email_share_txt,
                            'url' => $email_url,
                        ],
                        'print' => [
                            'slug' => 'print_',
                            'post_check' => $print_post_check,
                            'top_check' => $print_top_check,
                            'share_txt' => '',
                            'url' => '',
                        ]
                    ]
                ];
                apply_filters('update_hupa_options', apply_filters('arrayToObject', $media), 'update_social_media_data');
                $responseJson->spinner = true;
                break;

            case 'theme_map_placeholder':
                $map_img_id = filter_input(INPUT_POST, 'map_img_id', FILTER_SANITIZE_NUMBER_INT);
                $map_ds_page = filter_input(INPUT_POST, 'map_ds_page', FILTER_SANITIZE_NUMBER_INT);
                filter_input(INPUT_POST, 'map_bg_grayscale', FILTER_SANITIZE_STRING) ? $map_bg_grayscale = 1 : $map_bg_grayscale = 0;
                $map_btn_bg = filter_input(INPUT_POST, 'map_btn_bg', FILTER_SANITIZE_STRING);
                $map_btn_color = filter_input(INPUT_POST, 'map_btn_color', FILTER_SANITIZE_STRING);
                $map_btn_border_color = filter_input(INPUT_POST, 'map_btn_border_color', FILTER_SANITIZE_STRING);
                $map_btn_hover_bg = filter_input(INPUT_POST, 'map_btn_hover_bg', FILTER_SANITIZE_STRING);
                $map_btn_hover_color = filter_input(INPUT_POST, 'map_btn_hover_color', FILTER_SANITIZE_STRING);
                $map_btn_hover_border = filter_input(INPUT_POST, 'map_btn_hover_border', FILTER_SANITIZE_STRING);
                $map_box_bg = filter_input(INPUT_POST, 'map_box_bg', FILTER_SANITIZE_STRING);
                $map_box_color = filter_input(INPUT_POST, 'map_box_color', FILTER_SANITIZE_STRING);
                $map_box_border = filter_input(INPUT_POST, 'map_box_border', FILTER_SANITIZE_STRING);
                filter_input(INPUT_POST, 'map_link_uppercase', FILTER_SANITIZE_STRING) ? $map_link_uppercase = 1 : $map_link_uppercase = 0;
                filter_input(INPUT_POST, 'map_link_underline', FILTER_SANITIZE_STRING) ? $map_link_underline = 1 : $map_link_underline = 0;
                $map_link_color = filter_input(INPUT_POST, 'map_link_color', FILTER_SANITIZE_STRING);


                $google_maps_placeholder = [
                    'map_img_id' => $map_img_id,
                    'map_bg_grayscale' => $map_bg_grayscale,
                    'map_btn_bg' => $map_btn_bg,
                    'map_btn_color' => $map_btn_color,
                    'map_btn_border_color' => $map_btn_border_color,
                    'map_btn_hover_bg' => $map_btn_hover_bg,
                    'map_btn_hover_color' => $map_btn_hover_color,
                    'map_btn_hover_border' => $map_btn_hover_border,
                    'map_box_bg' => $map_box_bg,
                    'map_box_color' => $map_box_color,
                    'map_box_border' => $map_box_border,
                    'map_link_uppercase' => $map_link_uppercase,
                    'map_link_underline' => $map_link_underline,
                    'map_link_color' => $map_link_color,
                    'map_ds_page' => $map_ds_page
                ];
                apply_filters('update_hupa_options', apply_filters('arrayToObject', $google_maps_placeholder), 'google_maps_settings');
                $responseJson->spinner = true;
                break;
        }

        $responseJson->status = true;
        $responseJson->msg = date('H:i:s', current_time('timestamp'));
        break;

    case 'change_font_select':
        $font_family = filter_input(INPUT_POST, 'font_family', FILTER_SANITIZE_STRING);
        $container = filter_input(INPUT_POST, 'select_container', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $responseJson->select = apply_filters('get_font_style_select', $font_family);

        $responseJson->method = $method;
        $responseJson->container = $container;
        $responseJson->font_family = $font_family;
        $responseJson->status = true;
        break;

    case 'get_modal_layout':
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        switch ($type) {
            case'reset_general':
            case'reset_fonts':
            case'reset_colors':
            case 'reset_wp_optionen':
            case 'reset_all_settings':
            case 'reset_gmaps':
            case 'reset_gmaps_settings':
            case 'reset_social_media':
                $responseJson->language = apply_filters('get_theme_language', 'ajax_reset_modal')->language;
                $responseJson->btn_typ = 'btn-danger';
                $responseJson->modal_typ = 'danger';
                break;
            case'carousel':
                $responseJson->language = apply_filters('get_theme_language', 'ajax_delete_carousel')->language;
                $responseJson->btn_typ = 'btn-danger';
                $responseJson->modal_typ = 'danger';
                break;
            case'slider':
                $responseJson->language = apply_filters('get_theme_language', 'ajax_delete_slider')->language;
                $responseJson->btn_typ = 'btn-danger';
                $responseJson->modal_typ = 'danger';
                break;
        }
        $responseJson->status = true;
        $responseJson->id = $id;
        break;

    case'delete_carousel_item':
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        switch ($type) {
            case'carousel':
                $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
                apply_filters('delete_theme_carousel', $id);
                $carousel = apply_filters('get_carousel_data', 'hupa_carousel');
                $carousel->count ? $responseJson->if_last = false : $responseJson->if_last = true;
                $responseJson->id = $id;
                $responseJson->status = true;
                $responseJson->delete_carousel = true;
                break;

            case'slider':
                $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
                $cid = explode('_', $id);
                $sliderId = $cid[1];
                apply_filters('delete_hupa_slider', $sliderId);
                $responseJson->id = $id;
                $responseJson->status = true;
                $responseJson->delete_slider = true;
                break;
        }
        break;


    case'reset_settings':
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        apply_filters('update_hupa_options', $type, $method);
        $responseJson->status = true;
        $responseJson->resetMsg = true;
        break;


    case'theme_google_maps':
        $data = json_decode(stripslashes_deep($_POST['daten']));
        $record->map_apikey = filter_var($data->map_apikey, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        filter_var($data->map_datenschutz, FILTER_SANITIZE_STRING) ? $record->map_datenschutz = 1 : $record->map_datenschutz = 0;
        filter_var($data->map_colorcheck, FILTER_SANITIZE_STRING) ? $record->map_colorcheck = 1 : $record->map_colorcheck = 0;
        $record->map_standard_pin = filter_var($data->map_standard_pin, FILTER_SANITIZE_NUMBER_INT);
        $record->map_pin_height = filter_var($data->map_pin_height, FILTER_SANITIZE_NUMBER_INT);
        $record->map_pin_width = filter_var($data->map_pin_width, FILTER_SANITIZE_NUMBER_INT);
        $record->map_color = filter_var($data->map_color, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        if (!$data->map_pin_coords) {
            $responseJson->status = false;
            $responseJson->msg = apply_filters('get_theme_language', 'ajax-return-msg')->language->error;

            return $responseJson;
        }
        //Custom Pin
        $map_pin_coords = (array)$data->map_pin_coords;
        $map_pin_text = (array)$data->map_pin_text;
        $map_pin_custompin = (array)$data->map_pin_custompin;
        $map_custom_pin_height = (array)$data->map_custom_pin_height;
        $map_custom_pin_width = (array)$data->map_custom_pin_width;
        $custom_pin_img = (array)$data->custom_pin_img;

        if (is_array($map_pin_coords)) {
            $pinCoords = [$map_pin_coords][0];
        } else {
            $pinCoords[] = $map_pin_coords;
        }

        if (is_array($map_pin_text)) {
            $pinText = [$map_pin_text][0];
        } else {
            $pinText[] = $map_pin_text;
        }

        if (is_array($map_pin_custompin)) {
            $customCheck = [$map_pin_custompin][0];
        } else {
            $customCheck[] = $map_pin_custompin;
        }

        if (is_array($map_custom_pin_height)) {
            $customHeight = [$map_custom_pin_height][0];
        } else {
            $customHeight[] = $map_custom_pin_height;
        }

        if (is_array($map_custom_pin_width)) {
            $customWidth = [$map_custom_pin_width][0];
        } else {
            $customWidth[] = $map_custom_pin_width;
        }

        if (is_array($custom_pin_img)) {
            $pinImgId = [$custom_pin_img][0];
        } else {
            $pinImgId[] = $custom_pin_img;
        }

        $pinArr = [];
        $i = 0;
        foreach ($pinCoords as $key => $val) {

            filter_var($customCheck[$i], FILTER_SANITIZE_STRING) ? $check = 1 : $check = 0;
            filter_var($pinImgId[$i], FILTER_SANITIZE_NUMBER_INT) !== null ? $imgId = $pinImgId[$i] : $imgId = 0;

            $pinItem = [
                'id' => $i + 1,
                'coords' => filter_var($pinCoords[$i], FILTER_SANITIZE_STRING),
                'info_text' => filter_var($pinText[$i], FILTER_SANITIZE_STRING),
                'custom_pin_check' => $check,
                'custom_pin_img' => $imgId,
                'custom_height' => filter_var($customHeight[$i], FILTER_SANITIZE_NUMBER_INT),
                'custom_width' => filter_var($customWidth[$i], FILTER_SANITIZE_NUMBER_INT)
            ];
            $i++;
            $pinArr[] = $pinItem;
        }

        $record->map_pins = $pinArr;
        apply_filters('update_hupa_options', $record, 'google_maps');
        $responseJson->status = true;
        $responseJson->msg = apply_filters('get_theme_language', 'ajax-return-msg')->language->success;

        break;
    case 'get_google_maps_pins':
        $handle = filter_input(INPUT_POST, 'handle', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $dbPins = get_hupa_option('map_pins');
        $retArr = [];
        foreach ($dbPins as $tmp) {
            $handle === 'template' ? $factor = 50 : $factor = 0;
            if ($tmp->custom_pin_check) {
                if ($tmp->custom_pin_img) {
                    $imdId = $tmp->custom_pin_img;
                    $img = wp_get_attachment_image_src($tmp->custom_pin_img);
                    $imgUrl = $img[0];
                    $imgStPin = '<img class="range-image img-fluid" src="' . $img[0] . '" width="' . $tmp->custom_width + $factor . '" height="' . $tmp->custom_height + $factor . '">';
                } else {
                    if (get_hupa_option('map_standard_pin')) {
                        $imdId = get_hupa_option('map_standard_pin');
                        $img = wp_get_attachment_image_src($imdId);
                        $imgUrl = $img[0];
                        $imgStPin = '<img class="range-image img-fluid" src="' . $img[0] . '" width="' . get_hupa_option('map_pin_width') + $factor . '" height="' . get_hupa_option('map_pin_height') + $factor . '">';
                    } else {
                        $imdId = false;
                        $imgUrl = THEME_ADMIN_URL . 'assets/images/img-placeholder.svg';
                        $imgStPin = '<img class="img-fluid" src="' . THEME_ADMIN_URL . '\'assets/images/img-placeholder.svg\'" alt="" width="' . $factor + 25 . '">';
                    }
                }
            } else {
                $imdId = get_hupa_option('map_standard_pin');
                if ($imdId) {
                    $img = wp_get_attachment_image_src($imdId);
                    $imgUrl = $img[0];
                    $imgStPin = '<img class="range-image img-fluid" src="' . $img[0] . '" width="' . get_hupa_option('map_pin_width') + $factor . '" height="' . get_hupa_option('map_pin_height') + $factor . '">';
                } else {
                    $imdId = false;
                    $imgUrl = THEME_ADMIN_URL . 'assets/images/img-placeholder.svg';
                    $imgStPin = '<img class="img-fluid" src="' . THEME_ADMIN_URL . '\'assets/images/img-placeholder.svg\'" alt="" width="' . $factor + 25 . '">';
                }
            }
            $retItem = [
                'id' => $tmp->id,
                'coords' => $tmp->coords,
                'info_text' => $tmp->info_text,
                'custom_pin_check' => (bool)$tmp->custom_pin_check,
                'custom_pin_img_id' => $imdId,
                'custom_pin_img' => $imgStPin,
                'image_url' => $imgUrl,
                'custom_height' => $tmp->custom_height,
                'custom_width' => $tmp->custom_width
            ];
            $retArr[] = $retItem;
        }
        $responseJson->pins = $retArr;
        $responseJson->maps_template = true;
        break;

    case 'change_sortable_position':
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $pos = explode(',', $_POST['element']);
        $i = 1;
        foreach ($pos as $tmp) {
            $id = preg_match('/(\d.+?)/i', $tmp, $matches);
            $record->id = $matches[1];
            $record->position = $i;
            $i++;
            apply_filters('update_sortable_position', $record, $type);
        }
        break;

    case'sync_font_folder':
        apply_filters('update_hupa_options', 'no-data', 'sync_font_folder');
        $responseJson->status = true;
        $responseJson->method = $method;
        break;

    case'add_carousel':
        $bezeichnung = filter_input(INPUT_POST, 'bezeichnung', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        if (!$bezeichnung) {
            $responseJson->status = false;
            $responseJson->msg = apply_filters('get_theme_language', 'ajax-return-msg')->language->error;

            return $responseJson;
        }
        $set_default_slider = '';
        $record = apply_filters('get_carousel_defaults', 'carousel');
        $record['bezeichnung'] = $bezeichnung;
        $setCarousel = apply_filters('set_carousel_defaults', (object)$record);
        if (!$setCarousel->status) {
            $responseJson->status = false;
            $responseJson->msg = apply_filters('get_theme_language', 'ajax-return-msg')->language->error;

            return $responseJson;
        }

        if (!$setCarousel->id) {
            $responseJson->status = false;
            $responseJson->msg = apply_filters('get_theme_language', 'ajax-return-msg')->language->error;

            return $responseJson;
        }

        $defSlider = apply_filters('get_slider_defaults', false);
        $defSlider['carousel_id'] = $setCarousel->id;
        for ($i = 1; $i <= HUPA_CAROUSEL_SLIDER_CREATE; $i++) {
            $set_default_slider = apply_filters('set_slider_defaults', (object)$defSlider, $i);
        }

        if (!$set_default_slider->status) {
            $responseJson->status = false;
            $responseJson->msg = apply_filters('get_theme_language', 'ajax-return-msg')->language->error;

            return $responseJson;
        }

        $args = sprintf('WHERE id=%d', $setCarousel->id);
        $responseJson->renderData = apply_filters('get_carousel_komplett_data', $args);
        $responseJson->render = 'carousel';
        $responseJson->status = '';
        $responseJson->reset_form = true;
        break;

    case'get_carousel_data':
        $responseJson->renderData = apply_filters('get_carousel_komplett_data', false);
        $responseJson->render = 'carousel';
        $responseJson->status = '';
        break;

    case 'update_carousel':
        $record->id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $record->data_animate = filter_input(INPUT_POST, 'data_animate', FILTER_SANITIZE_NUMBER_INT);
        filter_input(INPUT_POST, 'margin_aktiv', FILTER_SANITIZE_STRING) ? $record->margin_aktiv = 1 : $record->margin_aktiv = 0;
        filter_input(INPUT_POST, 'controls', FILTER_SANITIZE_STRING) ? $record->controls = 1 : $record->controls = 0;
        filter_input(INPUT_POST, 'indicator', FILTER_SANITIZE_STRING) ? $record->indicator = 1 : $record->indicator = 0;
        filter_input(INPUT_POST, 'data_autoplay', FILTER_SANITIZE_STRING) ? $record->data_autoplay = 1 : $record->data_autoplay = 0;
        $record->bezeichnung = filter_input(INPUT_POST, 'bezeichnung', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        filter_input(INPUT_POST, 'full_width', FILTER_SANITIZE_STRING) ? $record->full_width = 1 : $record->full_width = 0;
        $record->caption_bg = filter_input(INPUT_POST, 'caption_bg', FILTER_SANITIZE_NUMBER_INT);
        $record->select_bg = filter_input(INPUT_POST, 'select_bg', FILTER_SANITIZE_NUMBER_INT);

        $container_height = filter_input(INPUT_POST, 'container_height', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $record->carousel_image_size = filter_input(INPUT_POST, 'carousel_image_size', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $containerHeight = preg_replace('/\s+/', '', $container_height);

        preg_match('/(vh|px|rem)/i', $containerHeight, $matches);
        $matches ? $record->container_height = $containerHeight : $record->container_height = '65vh';

        filter_input(INPUT_POST, 'carousel_lazy_load', FILTER_SANITIZE_STRING) ? $record->carousel_lazy_load = 1 : $record->carousel_lazy_load = 0;

        apply_filters('update_hupa_carousel', $record);
        $responseJson->status = true;
        $responseJson->spinner = true;
        $responseJson->msg = date('H:i:s', current_time('timestamp'));
        break;

    case'get_page_site_select':
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        $formId = filter_input(INPUT_POST, 'formId', FILTER_SANITIZE_STRING);

        $post = [];
        $return = [];
        $pages = apply_filters('get_theme_pages', false);
        $post = apply_filters('get_theme_posts', false);

        switch ($type) {
            case 'page_site':
                $responseJson->formId = $formId;
                if ($post) {
                    $responseJson->record = array_merge_recursive($pages, $post);
                } else {
                    $responseJson->record = $pages;
                }
                break;
        }
        $responseJson->status = true;
        break;
    case'update_slider':
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
        $record->img_id = filter_input(INPUT_POST, 'img_id', FILTER_SANITIZE_NUMBER_INT);
        $record->img_size = filter_input(INPUT_POST, 'img_size', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        filter_input(INPUT_POST, 'aktiv', FILTER_SANITIZE_STRING) ? $record->aktiv = 1 : $record->aktiv = 0;
        filter_input(INPUT_POST, 'caption_aktiv', FILTER_SANITIZE_STRING) ? $record->caption_aktiv = 1 : $record->caption_aktiv = 0;
        $data_interval = filter_input(INPUT_POST, 'data_interval', FILTER_SANITIZE_NUMBER_INT);
        $record->data_alt = filter_input(INPUT_POST, 'data_alt', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $record->font_color = filter_input(INPUT_POST, 'font_color', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $record->first_caption = filter_input(INPUT_POST, 'first_caption', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $record->first_selector = filter_input(INPUT_POST, 'first_selector', FILTER_SANITIZE_NUMBER_INT);
        $record->first_css = filter_input(INPUT_POST, 'first_css', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $first_font = filter_input(INPUT_POST, 'first_font', FILTER_SANITIZE_STRING);
        $first_style = filter_input(INPUT_POST, 'first_style', FILTER_SANITIZE_NUMBER_INT);
        $record->first_size = filter_input(INPUT_POST, 'first_size', FILTER_SANITIZE_NUMBER_INT);
        $record->first_height = filter_input(INPUT_POST, 'first_height', FILTER_SANITIZE_STRING);
        $record->first_ani = filter_input(INPUT_POST, 'first_ani', FILTER_SANITIZE_STRING);

        $record->second_caption = filter_input(INPUT_POST, 'second_caption', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $record->second_css = filter_input(INPUT_POST, 'second_css', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $second_font = filter_input(INPUT_POST, 'second_font', FILTER_SANITIZE_STRING);
        $second_style = filter_input(INPUT_POST, 'second_style', FILTER_SANITIZE_NUMBER_INT);
        $record->second_size = filter_input(INPUT_POST, 'second_size', FILTER_SANITIZE_NUMBER_INT);
        $record->second_height = filter_input(INPUT_POST, 'second_height', FILTER_SANITIZE_STRING);
        $record->second_ani = filter_input(INPUT_POST, 'second_ani', FILTER_SANITIZE_STRING);

        $cid = explode('_', $id);
        $record->id = $cid[1];
        $record->carousel_id = $cid[0];


        $regEx = '/\d{5}/is';
        $btnCount = 0;
        $btnArg = [];
        $btnId = [];
        $record->slider_button = false;
        $i = 0;
        foreach ($_POST as $key => $val) {
            preg_match($regEx, $key, $matches);
            if (isset($matches[0])) {
                $btnArg[] = $matches[0];
            }
        }
        if ($btnArg) {
            $btnArg = array_merge(array_unique($btnArg));
        }
        $btArr = [];
        $btn_select = false;
        $btn_link = false;
        $if_url = false;

        for ($i = 0; $i <= count($btnArg); $i++) {

            $btn_text = filter_input(INPUT_POST, "btn_text_$btnArg[$i]", FILTER_SANITIZE_STRING);
            $btn_url = filter_input(INPUT_POST, "url_$btnArg[$i]", FILTER_VALIDATE_URL);

            $button_color = filter_input(INPUT_POST, "button_color_$btnArg[$i]", FILTER_SANITIZE_STRING);
            $border_color = filter_input(INPUT_POST, "border_color_$btnArg[$i]", FILTER_SANITIZE_STRING);
            $bg_color = filter_input(INPUT_POST, "button_bg_color_$btnArg[$i]", FILTER_SANITIZE_STRING);

            $hover_color = filter_input(INPUT_POST, "color_hover_$btnArg[$i]", FILTER_SANITIZE_STRING);
            $hover_border = filter_input(INPUT_POST, "border_hover_$btnArg[$i]", FILTER_SANITIZE_STRING);
            $bg_hover = filter_input(INPUT_POST, "bg_hover_$btnArg[$i]", FILTER_SANITIZE_STRING);

            isset($_POST["btn_icon_$btnArg[$i]"]) ? $icon = $_POST["btn_icon_$btnArg[$i]"] : $icon = false;
            isset($_POST["check_target_$btnArg[$i]"]) ? $btn_target = 1 : $btn_target = 0;

            if ($btn_url) {
                $btn_link = $btn_url;
                $if_url = true;
            } else {
                $btn_link = $_POST["select_btn_url_$btnArg[$i]"];
                $if_url = false;
            }

            if ($icon) {
                $iconData = explode('#', $icon);
                $icon = '<i class="' . $iconData[0] . ' me-1"></i>';
                $icon_value = $iconData[0] . '#' . $iconData[1];
                $iconUnicode = $iconData[1];
            } else {
                $icon = false;
                $iconUnicode = false;
                $icon_value = false;
            }

            if (!$btn_link) {
                continue;
            }

            $btn_item = [
                'id' => $btnArg[$i],
                'button_color' => $button_color,
                'border_color' => $border_color,
                'bg_color' => $bg_color,
                'hover_color' => $hover_color,
                'hover_border' => $hover_border,
                'bg_hover' => $bg_hover,
                'btn_text' => $btn_text,
                'icon' => $icon,
                'icon_value' => $icon_value,
                'icon_unicode' => $iconUnicode,
                'if_url' => $if_url,
                'btn_link' => $btn_link,
                'btn_target' => $btn_target
            ];

            $btArr[] = $btn_item;
        }

        if ($btArr) {
            $record->slider_button = json_encode($btArr);
        }

        $defaults = (object)apply_filters('get_slider_defaults', false);

        $data_interval ? $record->data_interval = $data_interval : $record->data_interval = $defaults->data_interval;
        $first_font ? $record->first_font = $first_font : $record->first_font = $defaults->first_font;
        $first_style ? $record->first_style = $first_style : $record->first_style = $defaults->first_style;
        $second_font ? $record->second_font = $second_font : $record->second_font = $defaults->second_font;
        $second_style ? $record->second_style = $second_style : $record->second_style = $defaults->second_style;
        apply_filters('update_hupa_slider', $record);
        apply_filters('generate_theme_css', '');
        $responseJson->status = true;
        $responseJson->spinner = true;
        $responseJson->msg = date('H:i:s', current_time('timestamp'));
        break;

    case'get_fa_icons':
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        $formId = filter_input(INPUT_POST, 'formId', FILTER_SANITIZE_STRING);
        $status = false;
        $responseJson->type = $type;
        switch ($type) {
            case'slider':
                $responseJson->formId = $formId;
                break;
        }

        $cheatSet = file_get_contents('tools/FontAwesomeCheats.txt', true);
        $regEx = '/fa.*?\s/m';
        preg_match_all($regEx, $cheatSet, $matches, PREG_SET_ORDER, 0);
        if (!isset($matches)) {
            $responseJson->status = $status;
            return $responseJson;
        }

        $ico_arr = [];
        foreach ($matches as $tmp) {
            $icon = trim($tmp[0]);
            $regExp = sprintf('/%s.+?\[?x(.*?);\]/m', $icon);
            preg_match_all($regExp, $cheatSet, $matches1, PREG_SET_ORDER, 0);
            $ico_item = array(
                'icon' => 'fa ' . $icon,
                'title' => substr($icon, strpos($icon, '-') + 1),
                'code' => $matches1[0][1]
            );
            $ico_arr[] = $ico_item;
        }

        $responseJson->status = true;
        $responseJson->record = $ico_arr;
        break;

    case'slider_sortable_position':
        $el = filter_input(INPUT_POST, 'element', FILTER_SANITIZE_STRING);
        $element = explode(',', $el);
        $i = 1;
        foreach ($element as $tmp) {
            preg_match('/_(.+)?/i', $tmp, $matches);
            $record->position = $i;
            $record->id = $matches[1];
            apply_filters('update_slider_position', $record);
            $i++;
        }
        $responseJson->status = true;
        break;

    case'add_carousel_slider':
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        $args = sprintf('WHERE id=%d', $id);
        $data = apply_filters('get_carousel_data', 'hupa_carousel', $args, 'get_row');
        $sliderDef = (object)apply_filters('get_slider_defaults', false);
        $sliderDef->carousel_id = $id;

        //Slider erstellen
        $setSlider = apply_filters('set_slider_defaults', $sliderDef, 0);
        if (!$setSlider->status) {
            $responseJson->msg = apply_filters('get_theme_language', 'ajax-return-msg')->language->error;

            return $responseJson;
        }

        //slider Daten abrufen
        $args = sprintf('WHERE id=%d', $setSlider->id);
        $slider = apply_filters('get_carousel_data', 'hupa_slider', $args, 'get_row');
        if (!$slider->status) {
            $responseJson->msg = apply_filters('get_theme_language', 'ajax-return-msg')->language->no_data;

            return $responseJson;
        }
        $sliderData = $slider->record;

        $slider = apply_filters('create_slider_array', $sliderData, false, $data->record->bezeichnung);
        $recordData = [];

        $recordData['animate'] = apply_filters('get_animate_option', false);
        $recordData['selector'] = apply_filters('get_container_selector', false);
        $recordData['familySelect'] = apply_filters('get_font_family_select', false);
        $sliderArr[] = $slider;

        $responseJson->record = $recordData;
        $responseJson->lang = apply_filters('get_theme_language', 'carousel')->language;
        $responseJson->carouselId = $id;
        $responseJson->slider = $sliderArr;
        $responseJson->status = true;
        $responseJson->render = 'slider';
        break;

    case 'install_api_font':
        $id = filter_input(INPUT_POST, 'font_install_id', FILTER_SANITIZE_NUMBER_INT);
        $responseJson->method = $method;
        if (!$id) {
            $responseJson->msg = 'Schrift nicht gefunden!';
            return false;
        }
        $body = [
            'font_id' => $id
        ];
        $apiFiles = apply_filters('post_scope_resource', 'file/install-font', $body);
        if (!$apiFiles->status) {
            $responseJson->msg = 'Schrift nicht gefunden!';
            return false;
        }


        $fontsDir = THEME_FONTS_DIR;
        if (is_dir($fontsDir . $apiFiles->font_family)) {
            $responseJson->msg = 'Schrift ist schon Installiert!';
            return false;
        }
        if (!mkdir($fontsDir . $apiFiles->font_family, 0755, true)) {
            $responseJson->msg = 'Erstellung der Verzeichnisse schlug fehl...';
            return false;
        }

        foreach ($apiFiles->font_files as $tmp) {
            $body = [
                'font_file' => $tmp->font_file,
                'font_family' => $apiFiles->font_family,
            ];

            $file = apply_filters('post_scope_resource', 'file/font', $body);
            $filePath = $fontsDir . $apiFiles->font_family . DIRECTORY_SEPARATOR . $tmp->font_file;
            $file = base64_decode($file->file);
            @file_put_contents($filePath, $file);
        }

        $body = [
            'css_file' => $apiFiles->font_family . '.css',
            'font_family' => $apiFiles->font_family,
        ];

        $file = apply_filters('post_scope_resource', 'file/font', $body);
        @file_put_contents($fontsDir . $file->file_name, $file->file);
        apply_filters('update_hupa_options', 'no-data', 'sync_font_folder');

        $responseJson->status = true;
        $responseJson->id = $id;
        // $responseJson->install_fonts = apply_filters('get_font_family_select', false);
        $responseJson->msg = 'Schrift erfolgreich Installiert.';
        //print_r($_POST);
        break;

    case'delete_font':
        $font = filter_input(INPUT_POST, 'id');
        $responseJson->method = $method;
        if (!$font) {
            $responseJson->msg = 'Schrift kann nicht gelöscht werden!';
            return false;
        }

        $slider = apply_filters('get_carousel_data', 'hupa_slider');
        $update = new stdClass();
        if ($slider->status) {

            foreach ($slider->record as $tmp) {
                $update->id = $tmp->id;
                if ($tmp->first_font == $font) {
                    $update->first_font = 'Roboto';
                    $update->first_style = 3;
                    $update->second_font = $tmp->second_font;
                    $update->second_style = $tmp->second_style;
                    apply_filters('update_slider_family_style', $update);
                }

                if ($tmp->second_font == $font) {
                    $update->first_font = $tmp->first_font;
                    $update->first_style = $tmp->first_style;
                    $update->second_font = 'Roboto';
                    $update->second_style = 3;
                    apply_filters('update_slider_family_style', $update);
                }
            }
        }

        $defaults = apply_filters('get_default_settings', false);
        $themeFonts = [];
        foreach ($defaults->theme_fonts as $key => $val) {
            if (strpos($key, 'font_family')) {
                $themeFonts[] = $key;
            }
        }
        $i = 0;
        if ($themeFonts) {
            $regEx = '/(.+?font?)/i';

            foreach ($themeFonts as $tmp) {
                if ($font == get_hupa_option($tmp)) {
                    preg_match($regEx, $tmp, $matches);
                    if (isset($matches[0])) {
                        $record->fontType = $matches[0];
                        $record->font_family = $defaults->theme_fonts->$tmp;
                        $record->font_style = $defaults->theme_fonts->{$matches[0] . '_style'};
                        $record->font_size = $defaults->theme_fonts->{$matches[0] . '_size'};
                        $record->font_height = $defaults->theme_fonts->{$matches[0] . '_height'};
                        $record->font_color = $defaults->theme_fonts->{$matches[0] . '_color'};
                        $record->font_bs_check = $defaults->theme_fonts->{$matches[0] . '_bs_check'};
                        $record->font_display_check = $defaults->theme_fonts->{$matches[0] . '_display_check'};
                        $record->font_txt_decoration = $defaults->theme_fonts->{$matches[0] . '_txt_decoration'};
                        apply_filters('update_hupa_options', $record, 'hupa_fonts');
                        $i++;
                    }
                }
            }
        }

        if (is_dir(THEME_FONTS_DIR . $font)) {
            do_action('destroy_dir_recursive', THEME_FONTS_DIR . $font);
            unlink(THEME_FONTS_DIR . $font . '.css');

            apply_filters('update_hupa_options', 'no-data', 'sync_font_folder');
            apply_filters('generate_theme_css', '');
        }
        $responseJson->status = true;
        $responseJson->font = $font;
        $responseJson->msg = 'Schrift gelöscht!';
        break;

    case 'load_install_fonts';
        $family = apply_filters('get_font_family_select', false);
        $style = apply_filters('get_font_style_select', 'Roboto');
        $fontsArr = [];
        foreach ($family as $tmp) {
            if($tmp->family == 'Roboto'){
                continue;
            }
            $fonts_item = [
                'family' => $tmp->family,
                'styles' => apply_filters('get_font_style_select', $tmp->family)
            ];
            $fontsArr[] = $fonts_item;
        }
        $responseJson->record = $fontsArr;
        $responseJson->method = $method;
        $responseJson->status = true;

        break;

    case 'load_install_formular_fonts':

        $fontList = apply_filters('post_scope_resource', 'file/font-list');
        $installFonts = [];
        $family = apply_filters('get_font_family_select', false);
        foreach ($family as $key => $val) $installFonts[] = $val->family;
        $fontArr = [];
        if ($fontList->status) {
            foreach ($fontList->record as $tmp) {
                if (in_array($tmp->bezeichnung, $installFonts)) {
                    continue;
                }
                $font_item = [
                    'id' => $tmp->id,
                    'bezeichnung' => $tmp->bezeichnung
                ];
                $fontArr[] = $font_item;
            }
        }

        $fontArr ? $responseJson->status = true : $responseJson->status = false;
        $responseJson->record = $fontArr;
        $responseJson->method = $method;
        break;

    case'get_maps_language':
        $responseJson->lang = apply_filters('get_theme_language', 'gmaps_pin_form')->language;
        break;

    case'change_beitragslisten_template':
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        $responseJson->status = true;
        do_action('change_beitragslisten_template', $id, $type);
        break;

    case 'iframe_data_table':
        $query = '';
        $columns = array(
            "bezeichnung",
            "shortcode",
            "datenschutz",
            "created_at",
            "",
            ""
        );

        if (isset($_POST['search']['value'])) {
            $query = 'WHERE bezeichnung LIKE "%' . $_POST['search']['value'] . '%"
         OR shortcode LIKE "%' . $_POST['search']['value'] . '%"
         OR created_at LIKE "%' . $_POST['search']['value'] . '%"
         ';
        }

        if (isset($_POST['order'])) {
            $query .= ' ORDER BY ' . $columns[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else {
            $query .= ' ORDER BY created_at DESC';
        }

        $limit = '';
        if ($_POST["length"] != -1) {
            $limit = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $table = apply_filters('get_gmaps_iframe', $query . $limit);
        $data_arr = array();
        if (!$table->status) {
            return $responseJson = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => $data_arr
            );
        }
        foreach ($table->record as $tmp) {
            $date = explode(' ', $tmp->created);
            $tmp->datenschutz ? $datenschutz = '<b class="text-success">ja</b>' : $datenschutz = '<b class="text-danger">nein</b>';
            $data_item = array();
            $data_item[] = '<b>' . $tmp->bezeichnung . '</b>';
            $data_item[] = ' [gmaps id="' . $tmp->shortcode . '"]';
            $data_item[] = '<span class="d-none">' . $tmp->datenschutz . '</span>' . $datenschutz;
            $data_item[] = '<span class="d-none">' . $tmp->created_at . '</span><b class="strong-font-weight">' . $date[0] . '</b><small style="font-size: .9rem" class="d-block">' . $date[1] . ' Uhr</small>';
            $data_item[] = '<button data-bs-id="' . $tmp->id . '" data-bs-toggle="modal" data-bs-target="#addIframeMapsModal" data-bs-type="update" class="btn btn-blue-outline btn-sm"><i class="fa fa-edit"></i>&nbsp; Bearbeiten</button>';
            $data_item[] = '<button type="button" data-bs-id="' . $tmp->id . '" data-bs-toggle="modal" data-bs-target="#iframeDeleteModal" class="btn_delete_iframe btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i>&nbsp; löschen</button>';
            $data_arr[] = $data_item;
        }

        $tbCount = apply_filters('get_gmaps_iframe', false);
        $responseJson = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $tbCount->count,
            "recordsFiltered" => $tbCount->count,
            "data" => $data_arr,
        );

        break;

    case 'gmaps_iframe_handle':
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        $bezeichnung = filter_input(INPUT_POST, 'bezeichnung', FILTER_SANITIZE_STRING);
        $iframe = esc_html($_POST['iframe']);
        filter_input(INPUT_POST, 'datenschutz', FILTER_SANITIZE_STRING) ? $record->datenschutz = true : $record->datenschutz = false;

        if (!$iframe) {
            $responseJson->msg = 'Keine Daten gespeichert! I-Frame eingabe ist leer.';
            return $responseJson;
        }

        if (!$bezeichnung) {
            $rand = apply_filters('get_hupa_random_id', 4, 0, 4);
            $bezeichnung = 'Google I-Frame-' . $rand;
        }

        $record->bezeichnung = trim(sanitize_text_field($bezeichnung));
        $record->iframe = trim($iframe);

        switch ($type) {
            case 'insert':
                $record->shortcode = apply_filters('get_hupa_random_id', 12, 0, 4);
                $insert = apply_filters('set_gmaps_iframe', $record);
                $responseJson->status = $insert->status;
                $responseJson->msg = $insert->msg;
                break;
            case'update':
                $record->id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
                if (!$record->id) {
                    $responseJson->msg = 'Ein Fehler ist aufgetreten!';
                    return $responseJson;
                }
                apply_filters('update_gmaps_iframe', $record);
                $responseJson->status = true;
                $responseJson->msg = 'Änderungen gespeichert!';
                break;
        }

        break;

    case'get_iframe_modal_data':
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        $args = sprintf('WHERE id=%d', $id);
        $iframe = $table = apply_filters('get_gmaps_iframe', $args, false);
        if (!$iframe->status) {
            $responseJson->msg = 'keine Daten gefunden!';
            return $responseJson;
        }

        $iframe->record->iframe = html_entity_decode($iframe->record->iframe);
        $iframe->record->iframe = stripslashes_deep($iframe->record->iframe);
        $responseJson->record = $iframe->record;
        $responseJson->status = true;
        break;

    case 'delete_gmaps_iframe':
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        apply_filters('delete_gmaps_iframe', $id);
        $responseJson->status = true;
        $responseJson->msg = 'I-Frame gelöscht!';
        break;

}
