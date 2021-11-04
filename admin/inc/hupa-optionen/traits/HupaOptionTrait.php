<?php

namespace Hupa\StarterTheme;

use stdClass;

defined('ABSPATH') or die();

/**
 * ADMIN DATABASE HANDLE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */
trait HupaOptionTrait
{

    //DATABASE TABLES
    protected string $table_settings = 'hupa_settings';
    protected string $table_social = 'hupa_social';
    protected string $table_tools = 'hupa_tools';
    protected string $table_carousel = 'hupa_carousel';
    protected string $table_slider = 'hupa_slider';
    protected string $table_iframes = 'hupa_gmaps_iframe';
    //protected string $table_formulare = 'hupa_formulare';
    //protected string $table_form_message = 'hupa_form_message';

    protected array $settings_default_values;
    /*=================================================
    *============== SETTINGS ALLGEMEIN ===============
    ==================================================*/
    //VALUES SETTINGS GENERAL
    protected string $logo_image = 'logo_image';
    protected string $login_image = 'login_image';
    protected string $top_menu_aktiv = 'top_aktiv';
    protected string $top_area_container = 'top_area_container';
    protected string $menu_container = 'menu_container';
    protected string $main_container = 'main_container';
    protected string $fix_header = 'fix_header';
    protected string $fix_footer = 'fix_footer';
    protected string $scroll_top = 'scroll_top';
    protected string $edit_link = 'edit_link';
    protected string $login_img_aktiv = 'login_img_aktiv';
    protected string $logo_size = 'logo_size';
    protected string $menu = 'menu';
    protected string $handy = 'handy';
    protected string $fw_top = 'fw_top';
    protected string $fw_bottom = 'fw_bottom';
    protected string $fw_left = 'fw_left';
    protected string $fw_right = 'fw_right';
    protected string $login_logo_url = 'login_logo_url';
    protected string $bottom_area_text = 'bottom_area_text';
    protected string $preloader_aktiv = 'preloader_aktiv';
    protected string $sitemap_post = 'sitemap_post';
    protected string $sitemap_page = 'sitemap_page';
    protected string $woocommerce_aktiv ='woocommerce_aktiv';
    protected string $woocommerce_sidebar ='woocommerce_sidebar';
    protected string $social_type ='social_type';
    protected string $social_symbol_color ='social_symbol_color';
    protected string $social_extra_css ='social_extra_css';
    protected string $social_kategorie ='social_kategorie';
    protected string $social_author ='social_author';
    protected string $social_archiv ='social_archiv';
    protected string $social_farbig ='social_farbig';

    protected string $kategorie_template ='kategorie_template';
    protected string $archiv_template ='archiv_template';
    protected string $autoren_template ='autoren_template';

    protected string $post_kategorie ='post_kategorie';
    protected string $post_date ='post_date';
    protected string $post_autor ='post_author';
    protected string $post_kommentar ='post_kommentar';
    protected string $post_tags ='post_tags';
    protected string $post_breadcrumb ='post_breadcrumb';

    protected string $kategorie_image ='kategorie_image';
    protected string $archiv_image ='archiv_image';
    protected string $author_image ='author_image';


    /*========================================================
    *============== SETTINGS WordPress OPTIONS ===============
    ==========================================================*/

    //VALUES SETTINGS OPTIONS
    protected string $update_aktiv = 'update_aktiv';
    protected string $svg = 'svg';
    protected string $gutenberg = 'gutenberg';
    protected string $gb_widget = 'gb_widget';
    protected string $version = 'version';
    protected string $emoji = 'emoji';
    protected string $block_css = 'block_css';
    protected string $optimize = 'optimize';
    protected string $lizenz_page_aktiv = 'lizenz_page_aktiv';
    protected string $lizenz_login_aktiv = 'lizenz_login_aktiv';


    /*=============================================
    *============== SETTINGS COLORS ===============
    ===============================================*/
    //BACKGROUND
    protected string $site_bg = 'site_bg';
    protected string $nav_bg = 'nav_bg';
    protected string $nav_bg_opacity = 'nav_bg_opacity';
    protected string $footer_bg = 'footer_bg';

    protected string $widget_bg = 'widget_bg';
    protected string $widget_border_aktiv = 'widget_border_aktiv';
    protected string $widget_border_color = 'widget_border_color';
    protected string $mega_menu_bg = 'mega_menu_bg';
    //MENU
    protected string $menu_uppercase = 'menu_uppercase';
    protected string $menu_btn_bg_color = 'menu_btn_bg_color';
    protected string $menu_btn_bg_opacity = 'menu_btn_bg_opacity';
    protected string $menu_btn_color = 'menu_btn_color';

    protected string $menu_btn_active_bg = 'menu_btn_active_bg';
    protected string $menu_btn_active_bg_opacity = 'menu_btn_active_bg_opacity';
    protected string $menu_btn_active_color = 'menu_btn_active_color';

    protected string $menu_btn_hover_bg = 'menu_btn_hover_bg';
    protected string $menu_btn_hover_bg_opacity = 'menu_btn_hover_bg_opacity';
    protected string $menu_btn_hover_color = 'menu_btn_hover_color';

    protected string $menu_dropdown_bg = 'menu_dropdown_bg';
    protected string $menu_dropdown_bg_opacity = 'menu_dropdown_bg_opacity';
    protected string $menu_dropdown_color = 'menu_dropdown_color';

    protected string $menu_dropdown_active_bg = 'menu_dropdown_active_bg';
    protected string $menu_dropdown_active_bg_opacity = 'menu_dropdown_active_bg_opacity';
    protected string $menu_dropdown_active_color = 'menu_dropdown_active_color';

    protected string $menu_dropdown_hover_bg = 'menu_dropdown_hover_bg';
    protected string $menu_dropdown_hover_bg_opacity = 'menu_dropdown_hover_bg_opacity';
    protected string $menu_dropdown_hover_color = 'menu_dropdown_hover_color';

    //Login Site
    protected string $login_bg = 'login_bg';
    protected string $login_color = 'login_color';
    protected string $login_btn_bg = 'login_btn_bg';
    protected string $login_btn_color = 'login_btn_color';

    //Link Color
    protected string $link_color = 'link_color';
    protected string $link_hover_color = 'link_hover_color';
    protected string $link_aktiv_color = 'link_aktiv_color';

    //Scroll Btn
    protected string $scroll_btn_bg = 'scroll_btn_bg';
    protected string $scroll_btn_color = 'scroll_btn_color';

    //TOP Area
    protected string $top_area_bg_color = 'bg_color';
    protected string $top_area_bg_opacity = 'bg_opacity';

    /*============================================
    *============== SETTINGS FONTS ===============
    ==============================================*/
    //PREFIX H1 FONT
    protected string $prefix_h1 = 'h1_';
    //PREFIX H2 FONT
    protected string $prefix_h2 = 'h2_';
    //PREFIX H3 FONT
    protected string $prefix_h3 = 'h3_';
    //PREFIX H4 FONT
    protected string $prefix_h4 = 'h4_';
    //PREFIX H5 FONT
    protected string $prefix_h5 = 'h5_';
    //PREFIX H6 FONT
    protected string $prefix_h6 = 'h6_';
    //PREFIX BODY FONT
    protected string $prefix_body = 'body_';
    //PREFIX MENU FONT
    protected string $prefix_menu = 'menu_';
    //PREFIX BUTTON FONT
    protected string $prefix_btn = 'btn_';
    //PREFIX WIDGET FONT
    protected string $prefix_widget = 'widget_';
    //PREFIX UNTERTITEL FONT
    protected string $prefix_under = 'under_';
    //PREFIX FOOTER TOP FONT HEADLINE
    protected string $prefix_top_footer_headline = 'top_footer_headline_';
    //PREFIX FOOTER TOP FONT HEADLINE
    protected string $prefix_top_footer_body = 'top_footer_body_';
    //PREFIX FOOTER FONT
    protected string $prefix_footer = 'footer_';
    //PREFIX FOOTER WIDGET FONT
    protected string $prefix_footer_widget = 'footer_widget_';
    //PREFIX FOOTER WIDGET HEADLINE FONT
    protected string $prefix_footer_headline = 'footer_headline_';
    //PREFIX TOP AREA
    protected string $prefix_top_area = 'top_';
    /*=======================
    VALUES SETTINGS FONTS
    =========================*/
    protected string $font_family = 'font_family';
    protected string $font_style = 'font_style';
    protected string $font_size = 'font_size';
    protected string $font_height = 'font_height';
    protected string $font_bs_check = 'font_bs_check';
    protected string $font_display_check = 'font_display_check';
    protected string $font_color = 'font_color';
    protected string $txt_decoration = 'font_txt_decoration';

    /*=======================
    GOOGLE MAPS SETTINGS
    =========================*/
    protected string $map_apikey = 'map_apikey';
    protected string $map_datenschutz = 'map_datenschutz';
    protected string $map_colorcheck = 'map_colorcheck';
    protected string $map_standard_pin = 'map_standard_pin';
    protected string $map_pin_height = 'map_pin_height';
    protected string $map_pin_width = 'map_pin_width';
    protected string $map_color = 'map_color';
    protected string $map_pins = 'map_pins';

    /*==============================
    GOOGLE MAPS PLACEHOLDER SETTINGS
    ===============================*/
    protected string $map_img_id = 'map_img_id';
    protected string $map_bg_grayscale = 'map_bg_grayscale';
    protected string $map_btn_bg = 'map_btn_bg';
    protected string $map_btn_color = 'map_btn_color';
    protected string $map_btn_border_color = 'map_btn_border_color';
    protected string $map_btn_hover_bg = 'map_btn_hover_bg';
    protected string $map_btn_hover_color = 'map_btn_hover_color';
    protected string $map_btn_hover_border = 'map_btn_hover_border';
    protected string $map_box_bg = 'map_box_bg';
    protected string $map_box_color = 'map_box_color';
    protected string $map_box_border = 'map_box_border';
    protected string $map_link_uppercase = 'map_link_uppercase';
    protected string $map_link_underline = 'map_link_underline';
    protected string $map_link_color = 'map_link_color';
    protected string $map_ds_page = 'map_ds_page';

    /*=======================
    SOCIAl MEDIA
    =========================*/
    protected string $social_id = 'id';
    protected string $social_bezeichnung = 'bezeichnung';
    protected string $social_post_check = 'post_check';
    protected string $social_top_check = 'top_check';
    protected string $social_share_txt = 'share_txt';
    protected string $social_url = 'url';
    protected string $social_btn = 'btn';
    protected string $social_icon = 'icon';
    protected string $position = 'position';


    /*=======================
    HUPA TOOLS
    =========================*/
    protected string $hupa_tools_bezeichnung = 'bezeichnung';
    protected string $hupa_tools_aktiv = 'aktiv';
    protected string $hupa_tools_type = 'type';
    protected string $hupa_tools_position = 'position';
    protected string $hupa_tools_css_class = 'css_class';
    protected string $hupa_tools_other = 'other';

    protected function get_theme_default_settings(): array
    {

        return $this->settings_default_values = [

            /*===============================================
            ================= THEME GENERAL =================
            =================================================*/
            'theme_wp_general' => [
                $this->logo_image => 0,
                $this->top_menu_aktiv => 1,
                $this->top_area_container => 1,
                $this->menu_container => 1,
                $this->main_container => 1,
                $this->login_image => 0,
                $this->fix_header => 1,
                $this->fix_footer => 1,
                $this->scroll_top => 1,
                $this->login_img_aktiv => 1,
                $this->logo_size => 200,
                $this->menu => 1,
                $this->handy => 1,
                $this->edit_link => 1,
                $this->fw_top => 0,
                $this->fw_bottom => 0,
                $this->fw_left => 0,
                $this->fw_right => 0,
                $this->login_logo_url => 'https://www.hummelt-werbeagentur.de/',
                $this->bottom_area_text => '© <b>###YEAR###</b> - hummelt und partner | Werbeagentur GmbH',
                $this->preloader_aktiv => 1,
                $this->sitemap_post => 1,
                $this->sitemap_page => 1,
                $this->woocommerce_aktiv => 0,
                $this->woocommerce_sidebar => 0,
                $this->social_type => 0,
                $this->social_symbol_color => 0,
                $this->social_extra_css => '',
                $this->social_kategorie => 1,
                $this->social_author => 1,
                $this->kategorie_template => 1,
                $this->archiv_template => 1,
                $this->autoren_template => 1,
                $this->post_kategorie => 1,
                $this->post_date => 1,
                $this->post_autor => 1,
                $this->post_kommentar => 1,
                $this->post_tags => 1,
                $this->post_breadcrumb => 1,
                $this->social_archiv => 1,
                $this->social_farbig => 1,
                $this->kategorie_image => 1,
                $this->archiv_image => 1,
                $this->author_image => 1
            ],

            /*============================================================
            ================= SETTINGS WordPress OPTIONS =================
            ==============================================================*/
            'theme_wp_optionen' => [
                $this->update_aktiv = 1,
                $this->svg => 1,
                $this->gutenberg => 0,
                $this->gb_widget => 1,
                $this->version => 1,
                $this->emoji => 0,
                $this->block_css => 0,
                $this->optimize => 0,
                $this->lizenz_page_aktiv => 0,
                $this->lizenz_login_aktiv => 0
            ],

            /*=============================================
            ================= THEME FONTS =================
            ===============================================*/
            'theme_fonts' => [
                $this->prefix_h1 . $this->font_family => 'Roboto',
                $this->prefix_h1 . $this->font_style => 3,
                $this->prefix_h1 . $this->font_size => 40,
                $this->prefix_h1 . $this->font_height => 1.5,
                $this->prefix_h1 . $this->font_bs_check => 0,
                $this->prefix_h1 . $this->font_display_check => 0,
                $this->prefix_h1 . $this->font_color => '#3c434a',

                $this->prefix_h2 . $this->font_family => 'Roboto',
                $this->prefix_h2 . $this->font_style => 3,
                $this->prefix_h2 . $this->font_size => 32,
                $this->prefix_h2 . $this->font_height => 1.5,
                $this->prefix_h2 . $this->font_bs_check => 0,
                $this->prefix_h2 . $this->font_display_check => 0,
                $this->prefix_h2 . $this->font_color => '#3c434a',

                $this->prefix_h3 . $this->font_family => 'Roboto',
                $this->prefix_h3 . $this->font_style => 3,
                $this->prefix_h3 . $this->font_size => 28,
                $this->prefix_h3 . $this->font_height => 1.5,
                $this->prefix_h3 . $this->font_bs_check => 0,
                $this->prefix_h3 . $this->font_display_check => 0,
                $this->prefix_h3 . $this->font_color => '#3c434a',

                $this->prefix_h4 . $this->font_family => 'Roboto',
                $this->prefix_h4 . $this->font_style => 3,
                $this->prefix_h4 . $this->font_size => 24,
                $this->prefix_h4 . $this->font_height => 1.5,
                $this->prefix_h4 . $this->font_bs_check => 0,
                $this->prefix_h4 . $this->font_display_check => 0,
                $this->prefix_h4 . $this->font_color => '#3c434a',

                $this->prefix_h5 . $this->font_family => 'Roboto',
                $this->prefix_h5 . $this->font_style => 3,
                $this->prefix_h5 . $this->font_size => 20,
                $this->prefix_h5 . $this->font_height => 1.5,
                $this->prefix_h5 . $this->font_bs_check => 0,
                $this->prefix_h5 . $this->font_display_check => 0,
                $this->prefix_h5 . $this->font_color => '#3c434a',

                $this->prefix_h6 . $this->font_family => 'Roboto',
                $this->prefix_h6 . $this->font_style => 3,
                $this->prefix_h6 . $this->font_size => 16,
                $this->prefix_h6 . $this->font_height => 1.5,
                $this->prefix_h6 . $this->font_bs_check => 0,
                $this->prefix_h6 . $this->font_display_check => 0,
                $this->prefix_h6 . $this->font_color => '#3c434a',

                //Top Footer Headline
                $this->prefix_top_footer_headline . $this->font_family => 'Roboto',
                $this->prefix_top_footer_headline . $this->font_style => 0,
                $this->prefix_top_footer_headline . $this->font_size => 28,
                $this->prefix_top_footer_headline . $this->font_height => 1.5,
                $this->prefix_top_footer_headline . $this->font_bs_check => 0,
                $this->prefix_top_footer_headline . $this->font_display_check => 0,
                $this->prefix_top_footer_headline . $this->font_color => '#3c434a',

                //Top Footer Body
                $this->prefix_top_footer_body . $this->font_family => 'Roboto',
                $this->prefix_top_footer_body . $this->font_style => 3,
                $this->prefix_top_footer_body . $this->font_size => 16,
                $this->prefix_top_footer_body . $this->font_height => 1.5,
                $this->prefix_top_footer_body . $this->font_bs_check => 0,
                $this->prefix_top_footer_body . $this->font_display_check => 0,
                $this->prefix_top_footer_body . $this->font_color => '#3c434a',

                //BODY
                $this->prefix_body . $this->font_family => 'Roboto',
                $this->prefix_body . $this->font_style => 3,
                $this->prefix_body . $this->font_size => 16,
                $this->prefix_body . $this->font_height => 1.5,
                $this->prefix_body . $this->font_bs_check => 0,
                $this->prefix_body . $this->font_display_check => 0,
                $this->prefix_body . $this->font_color => '#3c434a',

                //WIDGET //TODO Widget Body
                $this->prefix_widget . $this->font_family => 'Roboto',
                $this->prefix_widget . $this->font_style => 11,
                $this->prefix_widget . $this->font_size => 21,
                $this->prefix_widget . $this->font_height => 1.5,
                $this->prefix_widget . $this->font_bs_check => 0,
                $this->prefix_widget . $this->font_display_check => 0,
                $this->prefix_widget . $this->font_color => '#3c434a',

                //UNTERTITEL
                $this->prefix_under . $this->font_family => 'Roboto',
                $this->prefix_under . $this->font_style => 3,
                $this->prefix_under . $this->font_size => 12,
                $this->prefix_under . $this->font_height => 1.5,
                $this->prefix_under . $this->font_bs_check => 0,
                $this->prefix_under . $this->font_display_check => 0,
                $this->prefix_under . $this->font_color => '#3c434a',

                //MENU
                $this->prefix_menu . $this->font_family => 'Roboto',
                $this->prefix_menu . $this->font_style => 0,
                $this->prefix_menu . $this->font_size => 16,
                $this->prefix_menu . $this->font_height => 1.5,
                $this->prefix_menu . $this->font_bs_check => 0,
                $this->prefix_menu . $this->font_display_check => 0,
                $this->prefix_menu . $this->font_color => '',

                //BUTTON
                $this->prefix_btn . $this->font_family => 'Roboto',
                $this->prefix_btn . $this->font_style => 3,
                $this->prefix_btn . $this->font_size => 16,
                $this->prefix_btn . $this->font_height => 1.5,
                $this->prefix_btn . $this->font_bs_check => 0,
                $this->prefix_btn . $this->font_display_check => 0,
                $this->prefix_btn . $this->font_color => '',

                //FOOTER
                //TODO INFO FOOTER FONT
                $this->prefix_footer . $this->font_family => 'Roboto',
                $this->prefix_footer . $this->font_style => 3,
                $this->prefix_footer . $this->font_size => 16,
                $this->prefix_footer . $this->font_height => 1.5,
                $this->prefix_footer . $this->font_bs_check => 0,
                $this->prefix_footer . $this->font_display_check => 0,
                $this->prefix_footer . $this->font_color => '#ffffff',

                //FOOTER WIDGET HEADLINE
                $this->prefix_footer_headline . $this->font_family => 'Roboto',
                $this->prefix_footer_headline . $this->font_style => 3,
                $this->prefix_footer_headline . $this->font_size => 24,
                $this->prefix_footer_headline . $this->font_height => 1.2,
                $this->prefix_footer_headline . $this->font_bs_check => 0,
                $this->prefix_footer_headline . $this->font_display_check => 0,
                $this->prefix_footer_headline . $this->font_color => '#3c434a',

                //FOOTER WIDGET
                $this->prefix_footer_widget . $this->font_family => 'Roboto',
                $this->prefix_footer_widget . $this->font_style => 4,
                $this->prefix_footer_widget . $this->font_size => 16,
                $this->prefix_footer_widget . $this->font_height => 1.5,
                $this->prefix_footer_widget . $this->font_bs_check => 0,
                $this->prefix_footer_widget . $this->txt_decoration => 1,
                $this->prefix_footer_widget . $this->font_color => '#3c434a',

                //TOP AREA
                $this->prefix_top_area . $this->font_family => 'Roboto',
                $this->prefix_top_area . $this->font_style => 3,
                $this->prefix_top_area . $this->font_size => 14,
                $this->prefix_top_area . $this->font_height => 1.5,
                $this->prefix_top_area . $this->font_bs_check => 0,
            ],

            /*==============================================
            ================= THEME COLORS =================
            ================================================*/
            'theme_colors' => [
                //SITE
                $this->site_bg => '#ffffff',
                $this->nav_bg => '#e6e6e6cf',
                $this->footer_bg => '#e11d2a',
                $this->nav_bg_opacity => 100,

                //WIDGET BG
                $this->widget_bg => '#F7F7F700',
                $this->widget_border_color => '#dee2e6',
                $this->widget_border_aktiv => 1,

                //MEGA MENU
                $this->mega_menu_bg = '#FFFFFF',

                //MENU
                $this->menu_uppercase => 0,
                $this->menu_btn_bg_color => '#e6e6e600',
                $this->menu_btn_bg_opacity => 100,
                $this->menu_btn_color => '#474747',
                //BTN Active
                $this->menu_btn_active_bg => '#e6e6e600',
                $this->menu_btn_active_bg_opacity => 100,
                $this->menu_btn_active_color => '#990000',
                //BTN HOVER
                $this->menu_btn_hover_bg => '#ededed00',
                $this->menu_btn_hover_bg_opacity => 100,
                $this->menu_btn_hover_color => '#800000',
                //DropDown
                $this->menu_dropdown_bg => '#e6e6e6',
                $this->menu_dropdown_bg_opacity => 100,
                $this->menu_dropdown_color => '#474747',
                //DropDown Active
                $this->menu_dropdown_active_bg => '#d4d4d4',
                $this->menu_dropdown_active_bg_opacity => 100,
                $this->menu_dropdown_active_color => '#a30000',
                //DropDown Hover
                $this->menu_dropdown_hover_bg => '#ededed',
                $this->menu_dropdown_hover_bg_opacity => 100,
                $this->menu_dropdown_hover_color => '#a30000',

                //Login Site
                $this->login_bg => '#e0222a',
                $this->login_color => '#ffffff',
                $this->login_btn_bg => '#ffffff',
                $this->login_btn_color => '#e0222a',

                //LINK COLORS
                $this->link_color => '#0062bd',
                $this->link_hover_color => '#007ce8',
                $this->link_aktiv_color => '#004480',

                //Scroll to Top Button
                $this->scroll_btn_bg => '#e11d2a',
                $this->scroll_btn_color => '#ffffff',

                //TOP AREA
                $this->prefix_top_area . $this->top_area_bg_color => '#3c434a',
                $this->prefix_top_area . $this->font_color => '#b5c2cb',
                $this->prefix_top_area . $this->top_area_bg_opacity => 100,
            ],


            /*=========================================================
            ================= GMAPS STANDARD SETTINGS =================
            ===========================================================*/
            'google_maps' => [
                $this->map_apikey => 'AIzaSyBZsmhttYU__3fAQGxMDremkGi_ywod0OA',
                $this->map_datenschutz => 0,
                $this->map_colorcheck => 0,
                $this->map_standard_pin => 0,
                $this->map_pin_height => 25,
                $this->map_pin_width => 25,
                $this->map_color => '',
                $this->map_pins => [
                    '0' => [
                        'id' => 1,
                        'coords' => '52.10865639405879, 11.633041908696315',
                        'info_text' => 'hummelt und partner | Werbeagentur GmbH',
                        'custom_pin_check' => 0,
                        'custom_pin_img' => 0,
                        'custom_height' => 25,
                        'custom_width' => 25
                    ]
                ]
            ],

            /*=========================================================
            ================= GMAPS STANDARD SETTINGS =================
            ===========================================================*/
            'google_maps_placeholder' => [
                $this->map_img_id => 0,
                $this->map_bg_grayscale => 1,
                $this->map_btn_bg => '#5192cd',
                $this->map_btn_color => '#ffffff',
                $this->map_btn_border_color => '#6c757d',
                $this->map_btn_hover_bg => '#4175a4',
                $this->map_btn_hover_color => '#ffffff',
                $this->map_btn_hover_border => '#6c757d',
                $this->map_box_bg => '#00000065',
                $this->map_box_color => '#ffffff',
                $this->map_box_border => '#cbcbcb',
                $this->map_link_uppercase => 0,
                $this->map_link_underline => 1,
                $this->map_link_color => '#ffffff',
                $this->map_ds_page => 0,
            ],

            'hupa_tools' => [
                '0' => [
                    $this->hupa_tools_bezeichnung => __('Info text', 'bootscore'),
                    $this->hupa_tools_aktiv => 1,
                    $this->hupa_tools_type => 'top_area',
                    'slug' => 'areainfo_',
                    $this->hupa_tools_position => 1,
                    $this->hupa_tools_css_class => '',
                    $this->hupa_tools_other => ''
                ],
                '1' => [
                    $this->hupa_tools_bezeichnung => __('Social Media', 'bootscore'),
                    $this->hupa_tools_aktiv => 1,
                    $this->hupa_tools_type => 'top_area',
                    'slug' => 'areasocial_',
                    $this->hupa_tools_position => 2,
                    $this->hupa_tools_css_class => '',
                    $this->hupa_tools_other => ''
                ],
                '2' => [
                    $this->hupa_tools_bezeichnung => __('Top Area Menu', 'bootscore'),
                    $this->hupa_tools_aktiv => 1,
                    $this->hupa_tools_type => 'top_area',
                    'slug' => 'areamenu_',
                    $this->hupa_tools_position => 3,
                    $this->hupa_tools_css_class => '',
                    $this->hupa_tools_other => ''
                ],
                '3' => [
                    $this->hupa_tools_bezeichnung => __('Button', 'bootscore'),
                    $this->hupa_tools_aktiv => 1,
                    $this->hupa_tools_type => 'top_area',
                    'slug' => 'areabtn_',
                    $this->hupa_tools_position => 4,
                    $this->hupa_tools_css_class => '',
                    $this->hupa_tools_other => ''
                ]
            ],

            /*==============================================
            ================= SOCIAL MEDIA =================
            ================================================*/
            'social_media' => [
                'facebook' => [
                    $this->social_bezeichnung => 'Facebook',
                    'slug' => 'facebook_',
                    $this->social_post_check => 0,
                    $this->social_top_check => 1,
                    $this->social_share_txt => __('Look what I found:', 'bootscore'),
                    $this->social_url => '',
                    $this->social_btn => 'btn-facebook',
                    $this->social_icon => 'fab fa-facebook-f',
                    $this->position => 1
                ],
                'twitter' => [
                    $this->social_bezeichnung => 'Twitter',
                    'slug' => 'twitter_',
                    $this->social_post_check => 0,
                    $this->social_top_check => 1,
                    $this->social_share_txt => __('Look what I found:', 'bootscore'),
                    $this->social_url => '',
                    $this->social_btn => 'btn-twitter',
                    $this->social_icon => 'fab fa-twitter',
                    $this->position => 2
                ],
                'whatsapp' => [
                    $this->social_bezeichnung => 'WhatsApp',
                    'slug' => 'whatsapp_',
                    $this->social_post_check => 0,
                    $this->social_top_check => 1,
                    $this->social_share_txt => __('Look what I found:', 'bootscore'),
                    $this->social_url => '',
                    $this->social_btn => 'btn-whatsapp',
                    $this->social_icon => 'fab fa-whatsapp',
                    $this->position => 3
                ],
                'pinterest' => [
                    $this->social_bezeichnung => 'Pinterest',
                    'slug' => 'pinterest_',
                    $this->social_post_check => 0,
                    $this->social_top_check => 0,
                    $this->social_share_txt => __('Look what I found:', 'bootscore'),
                    $this->social_url => '',
                    $this->social_btn => 'btn-pinterest',
                    $this->social_icon => 'fab fa-pinterest-p',
                    $this->position => 4
                ],
                'linkedin' => [
                    $this->social_bezeichnung => 'LinkedIn',
                    'slug' => 'linkedin_',
                    $this->social_post_check => 0,
                    $this->social_top_check => 1,
                    $this->social_share_txt => __('Look what I found:', 'bootscore'),
                    $this->social_url => '',
                    $this->social_btn => 'btn-linkedin',
                    $this->social_icon => 'fab fa-linkedin-in',
                    $this->position => 5
                ],
                'reddit' => [
                    $this->social_bezeichnung => 'Reddit',
                    'slug' => 'reddit_',
                    $this->social_post_check => 0,
                    $this->social_top_check => 0,
                    $this->social_share_txt => __('Look what I found:', 'bootscore'),
                    $this->social_url => '',
                    $this->social_btn => 'btn-reddit',
                    $this->social_icon => 'fab fa-reddit-alien',
                    $this->position => 6
                ],
                'tumblr' => [
                    $this->social_bezeichnung => 'Tumblr',
                    'slug' => 'tumblr_',
                    $this->social_post_check => 0,
                    $this->social_top_check => 0,
                    $this->social_share_txt => __('Look what I found:', 'bootscore'),
                    $this->social_url => '',
                    $this->social_btn => 'btn-tumblr',
                    $this->social_icon => 'fab fa-tumblr',
                    $this->position => 7
                ],
                'buffer' => [
                    $this->social_bezeichnung => 'Buffer',
                    'slug' => 'buffer_',
                    $this->social_post_check => 0,
                    $this->social_top_check => 0,
                    $this->social_share_txt => __('Look what I found:', 'bootscore'),
                    $this->social_url => '',
                    $this->social_btn => 'btn-buffer',
                    $this->social_icon => 'fab fa-buffer',
                    $this->position => 8
                ],
                'mix' => [
                    $this->social_bezeichnung => 'Mix',
                    'slug' => 'mix_',
                    $this->social_post_check => 0,
                    $this->social_top_check => 0,
                    $this->social_share_txt => __('Look what I found:', 'bootscore'),
                    $this->social_url => '',
                    $this->social_btn => 'btn-mix',
                    $this->social_icon => 'fab fa-mix',
                    $this->position => 9
                ],
                'vk' => [
                    $this->social_bezeichnung => 'VK',
                    'slug' => 'vk_',
                    $this->social_post_check => 0,
                    $this->social_top_check => 0,
                    $this->social_share_txt => __('Look what I found:', 'bootscore'),
                    $this->social_url => '',
                    $this->social_btn => 'btn-vk',
                    $this->social_icon => 'fab fa-vk',
                    $this->position => 10
                ],
                'email' => [
                    $this->social_bezeichnung => 'E-Mail',
                    'slug' => 'email_',
                    $this->social_post_check => 0,
                    $this->social_top_check => 0,
                    $this->social_share_txt => __('Look what I found:', 'bootscore'),
                    $this->social_url => '',
                    $this->social_btn => 'btn-mail',
                    $this->social_icon => 'fas fa-envelope',
                    $this->position => 11
                ],
                'print' => [
                    $this->social_bezeichnung => 'Print',
                    'slug' => 'print_',
                    $this->social_post_check => 0,
                    $this->social_top_check => 0,
                    $this->social_btn => 'btn-print',
                    $this->social_icon => 'fas fa-print',
                    $this->position => 12
                ]
            ]
        ];
    }
}