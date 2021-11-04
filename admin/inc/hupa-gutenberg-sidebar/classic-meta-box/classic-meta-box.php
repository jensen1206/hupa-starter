<?php

namespace Hupa\StarterTheme;

use stdClass;
use WP_Query;

defined('ABSPATH') or die();

/**
 * METABOX CLASSIC EDITOR
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */
class HupaStarterClassicMetaBox
{

    //STATIC INSTANCE
    private static $classic_metabox_instance;

    /**
     * @return static
     */
    public static function classic_metabox_instance(): self
    {
        if (is_null(self::$classic_metabox_instance)) {
            self::$classic_metabox_instance = new self;
        }

        return self::$classic_metabox_instance;
    }

    /**
     * HupaStarterClassicMetaBox constructor.
     */
    public function __construct()
    {
        if (current_user_can('edit_posts')) {
            add_action('load-post.php', array($this, 'init_classic_metabox'));
            add_action('load-post-new.php', array($this, 'init_classic_metabox'));
        }
    }

    public function init_classic_metabox(): void
    {
        add_action('add_meta_boxes', array($this, 'add_hupa_metabox'));
        add_action('save_post', array($this, 'save_hupa_metabox'), 10, 2);
    }

    public function add_hupa_metabox($post_type): void
    {
        $post_types = array('post', 'page');
        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'hupa_sidebar_options_metabox',
                __('Hupa Theme options', 'bootscore'),
                array($this, 'render_hupa_metabox'),
                array('post', 'page'),
                'normal',
                'high',
                array('__back_compat_meta_box' => true)
            );
        }
    }

    public function render_hupa_metabox($post): void
    {
        wp_nonce_field('nonce_hupa_metabox', 'hupa_metabox_update_nonce');
        $checkTitle = get_post_meta($post->ID, '_hupa_show_title', true);
        $customTitle = get_post_meta($post->ID, '_hupa_custom_title', true);
        $titleCss = get_post_meta($post->ID, '_hupa_title_css', true);
        $showMenuCheck = get_post_meta($post->ID, '_hupa_show_menu', true);
        $menuSelect = get_post_meta($post->ID, '_hupa_select_menu', true);
        $handyMenuSelect = get_post_meta($post->ID, '_hupa_select_handy_menu', true);
        $topAreaSelect = get_post_meta($post->ID, '_hupa_select_top_area', true);
        $bottomFooterCheck = get_post_meta($post->ID, '_hupa_show_bottom_footer', true);
        $customHeaderSelect = get_post_meta($post->ID, '_hupa_select_header', true);
        $customFooterSelect = get_post_meta($post->ID, '_hupa_select_footer', true);
        $showTopFooterWidget = get_post_meta($post->ID, '_hupa_show_widgets_footer', true);
        $showFooterWidget = get_post_meta($post->ID, '_hupa_show_widgets_footer', true);

        $showTopAreaMenu = get_post_meta($post->ID, '_hupa_select_top_area', true);
        $topAreaContainer = get_post_meta($post->ID, '_hupa_top_area_container', true);
        $menuContainer = get_post_meta($post->ID, '_hupa_select_container', true);
        $mainContainer = get_post_meta($post->ID, '_hupa_main_container', true);

        ?>
        <div class="wp-bs-starter-wrapper">
            <div class="card my-3">
                <div class="card-header">
                    <?= __('Pages Title', 'bootscore') ?>
                    &nbsp;<span class="text-muted dashicon dashicons dashicons-edit-page components-panel__icon"></span>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="checkTitle" type="checkbox"
                               id="flexSwitchCheckChecked" <?= !$checkTitle ?: 'checked' ?>>
                        <label class="form-check-label"
                               for="flexSwitchCheckChecked"><?= __('Show title', 'bootscore') ?></label>
                    </div>
                    <hr>
                    <div class="col-xl-4 col-lg-6 col-sm-8 col-12 mb-3">
                        <label for="inputChangeTitel"
                               class="form-label"><?= __('Change title', 'bootscore') ?></label>
                        <input type="text" name="customTitle" value="<?= $customTitle ?>" id="inputChangeTitel"
                               class="form-control">
                    </div>
                    <div class="col-xl-4 col-lg-6 col-sm-8 col-12 mb-3">
                        <label for="inputChangeCss"
                               class="form-label"><?= __('extra CSS class', 'bootscore') ?></label>
                        <input type="text" name="titleCss" value="<?= $titleCss ?>" id="inputChangeCss"
                               class="form-control">
                    </div>
                </div>
            </div>
            <div class="card my-3">
                <div class="card-header">
                    <?= __('View', 'bootscore') ?>
                    &nbsp;<span class="text-muted dashicon dashicons dashicons-text-page components-panel__icon"></span>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="showMenuCheck" type="checkbox"
                               id="MenuChecked" <?= !$showMenuCheck ?: 'checked' ?>>
                        <label class="form-check-label"
                               for="MenuChecked"><?= __('Show menu', 'bootscore') ?></label>
                    </div>
                    <hr>
                    <!--<div class="col-xl-4 col-lg-6 col-sm-8 col-12 mb-3">
                        <label for="selectMainMenu"
                               class="form-label"><?= __('Select main menu', 'bootscore') ?></label>
                        <select class="form-select" name="menuSelect" id="selectMainMenu">
							<?php foreach (apply_filters('get_settings_menu_label', 'mainMenu') as $tmp):
                        $menuSelect == $tmp['value'] ? $sel = 'selected' : $sel = '';
                        ?>
                                <option value="<?= $tmp['value'] ?>" <?= $sel ?>><?= $tmp['label'] ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-sm-8 col-12 mb-3">
                        <label for="selectHandyMenu"
                               class="form-label"><?= __('Select mobile phone menu', 'bootscore') ?></label>
                        <select class="form-select" name="handyMenuSelect" id="selectHandyMenu">
							<?php foreach (apply_filters('get_settings_menu_label', 'handyMenu') as $tmp):
                        $handyMenuSelect == $tmp['value'] ? $sel = 'selected' : $sel = '';
                        ?>
                                <option value="<?= $tmp['value'] ?>" <?= $sel ?>><?= $tmp['label'] ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>-->


                    <b class="text-muted d-block pb-2">Top Area Optionen:</b>
                    <div class="col-xl-4 col-lg-6 col-sm-8 col-12 mb-3">
                        <label for="selectTopAreaMenu"
                               class="form-label"><?= __('Top Area menu anzeigen:', 'bootscore') ?></label>
                        <select class="form-select" name="topAreaShoWSelect" id="selectTopAreaMenu">
                            <?php foreach (apply_filters('get_settings_menu_label', 'showTopAreaSelect') as $tmp):
                                $showTopAreaMenu == $tmp['value'] ? $sel = 'selected' : $sel = '';
                                ?>
                                <option value="<?= $tmp['value'] ?>" <?= $sel ?>><?= $tmp['label'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="col-xl-4 col-lg-6 col-sm-8 col-12 mb-3">
                        <label for="selectTopAreaContainer"
                               class="form-label"><?= __('Top Area Menu Container:', 'bootscore') ?></label>
                        <select class="form-select" name="topAriaContainer" id="selectTopAreaContainer">
                            <?php foreach (apply_filters('get_settings_menu_label', 'selectTopAreaContainer') as $tmp):
                                $topAreaContainer == $tmp['value'] ? $sel = 'selected' : $sel = '';
                                ?>
                                <option value="<?= $tmp['value'] ?>" <?= $sel ?>><?= $tmp['label'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <hr>
                    <b class="text-muted d-block pb-2">Seitenbreite | Container:</b>

                    <div class="col-xl-4 col-lg-6 col-sm-8 col-12 mb-3">
                        <label for="selectMenuContainer"
                               class="form-label"><?= __('Menu Container:', 'bootscore') ?></label>
                        <select class="form-select" name="menuContainerSelect" id="selectMenuContainer">
                            <?php foreach (apply_filters('get_settings_menu_label', 'selectMenuContainer') as $tmp):
                                $menuContainer == $tmp['value'] ? $sel = 'selected' : $sel = '';
                                ?>
                                <option value="<?= $tmp['value'] ?>" <?= $sel ?>><?= $tmp['label'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-sm-8 col-12 mb-3">
                        <label for="selectMainContainer"
                               class="form-label"><?= __('Main Container:', 'bootscore') ?></label>
                        <select class="form-select" name="mainContainerSelect" id="selectMainContainer">
                            <?php foreach (apply_filters('get_settings_menu_label', 'selectMainContainer') as $tmp):
                                $mainContainer == $tmp['value'] ? $sel = 'selected' : $sel = '';
                                ?>
                                <option value="<?= $tmp['value'] ?>" <?= $sel ?>><?= $tmp['label'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <hr>
                    <b class="text-muted d-block pb-2">Footer Optionen:</b>
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="form-check form-switch mb-3 me-3">
                            <input class="form-check-input" name="bottomFooterCheck" type="checkbox"
                                   id="showBottomFooterCheck" <?= !$bottomFooterCheck ?: 'checked' ?>>
                            <label class="form-check-label"
                                   for="showBottomFooterCheck"><?= __('Bottom Footer show', 'bootscore') ?></label>
                        </div>

                        <div class="form-check form-switch mb-3 me-3">
                            <input class="form-check-input" name="topFooterWidgetCheck" type="checkbox"
                                   id="switchTopFooterWidget" <?= !$showTopFooterWidget ?: 'checked' ?>>
                            <label class="form-check-label"
                                   for="switchTopFooterWidget">Top Footer Widget anzeigen</label>
                        </div>

                        <div class="form-check form-switch mb-3 me-3">
                            <input class="form-check-input" name="footerWidgetCheck" type="checkbox"
                                   id="witchFooterWidgetChecked" <?= !$showFooterWidget ?: 'checked' ?>>
                            <label class="form-check-label"
                                   for="witchFooterWidgetChecked">Footer Widget anzeigen</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card my-3">
                <div class="card-header">
                    <?= __('Custom Header | Footer', 'bootscore') ?>
                    &nbsp;<span class="text-muted dashicon dashicons dashicons-schedule components-panel__icon"></span>
                </div>
                <div class="card-body">
                    <div class="col-xl-4 col-lg-6 col-sm-8 col-12 mb-3">
                        <label for="selectCustomFooter"
                               class="form-label"><?= __('Select Header:', 'bootscore') ?></label>
                        <select class="form-select" name="customHeaderSelect" id="selectCustomFooter">
                            <option value="0"><?= __('select', 'bootscore') ?>...</option>
                            <?php
                            //HEADER SELECT
                            $headerArgs = array(
                                'post_type' => 'starter_header',
                                'post_status' => 'publish',
                                'posts_per_page' => -1
                            );
                            $header = new WP_Query($headerArgs);

                            foreach ($header->posts as $tmp) :
                                $customHeaderSelect == $tmp->ID ? $sel = 'selected' : $sel = '';
                                ?>
                                <option value="<?= $tmp->ID ?>" <?= $sel ?>><?= $tmp->post_title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-sm-8 col-12 mb-3">
                        <label for="selectCustomFooter"
                               class="form-label"><?= __('Select Footer:', 'bootscore') ?></label>
                        <select class="form-select" name="customFooterSelect" id="selectCustomFooter">
                            <option value="0"><?= __('select', 'bootscore') ?>...</option>
                            <?php
                            //Footer SELECT
                            $footerArgs = array(
                                'post_type' => 'starter_footer',
                                'post_status' => 'publish',
                                'posts_per_page' => -1
                            );
                            $footer = new WP_Query($footerArgs);
                            foreach ($footer->posts as $tmp) :
                                $customFooterSelect == $tmp->ID ? $sel = 'selected' : $sel = '';
                                ?>
                                <option value="<?= $tmp->ID ?>" <?= $sel ?>><?= $tmp->post_title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div><!--wrapper-->
        <?php
    }

    public function save_hupa_metabox($post_id, $post): void
    {
        $nonce_name = $_POST['hupa_metabox_update_nonce'] ?? '';
        $nonce_action = 'nonce_hupa_metabox';

        // Check if nonce is valid.
        if (!wp_verify_nonce($nonce_name, $nonce_action)) {
            return;
        }

        // Check if user has permissions to save data.
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Check if not an auto save.
        if (wp_is_post_autosave($post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check if not a revision.
        if (wp_is_post_revision($post_id)) {
            return;
        }

        $record = new stdClass();
        filter_input(INPUT_POST, 'checkTitle', FILTER_SANITIZE_STRING) ? $record->checkTitle = true : $record->checkTitle = false;
        $record->customTitle = filter_input(INPUT_POST, 'customTitle', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $record->titleCss = filter_input(INPUT_POST, 'titleCss', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        filter_input(INPUT_POST, 'showMenuCheck', FILTER_SANITIZE_STRING) ? $record->showMenuCheck = true : $record->showMenuCheck = false;
        $record->menuSelect = filter_input(INPUT_POST, 'menuSelect', FILTER_SANITIZE_NUMBER_INT);
        $record->handyMenuSelect = filter_input(INPUT_POST, 'handyMenuSelect', FILTER_SANITIZE_NUMBER_INT);
        $record->topAreaSelect = filter_input(INPUT_POST, 'topAreaSelect', FILTER_SANITIZE_NUMBER_INT);
        filter_input(INPUT_POST, 'bottomFooterCheck', FILTER_SANITIZE_STRING) ? $record->bottomFooterCheck = true : $record->bottomFooterCheck = false;
        $record->customHeaderSelect = filter_input(INPUT_POST, 'customHeaderSelect', FILTER_SANITIZE_NUMBER_INT);
        $record->customFooterSelect = filter_input(INPUT_POST, 'customFooterSelect', FILTER_SANITIZE_NUMBER_INT);

        filter_input(INPUT_POST, 'topFooterWidgetCheck', FILTER_SANITIZE_STRING) ? $record->topFooterWidgetCheck = true : $record->topFooterWidgetCheck = false;
        filter_input(INPUT_POST, 'footerWidgetCheck', FILTER_SANITIZE_STRING) ? $record->footerWidgetCheck = true : $record->footerWidgetCheck = false;

        $record->topAreaShoWSelect = filter_input(INPUT_POST, 'topAreaShoWSelect', FILTER_SANITIZE_NUMBER_INT);
        $record->topAriaContainer = filter_input(INPUT_POST, 'topAriaContainer', FILTER_SANITIZE_NUMBER_INT);
        $record->menuContainerSelect = filter_input(INPUT_POST, 'menuContainerSelect', FILTER_SANITIZE_NUMBER_INT);
        $record->mainContainerSelect = filter_input(INPUT_POST, 'mainContainerSelect', FILTER_SANITIZE_NUMBER_INT);


        //Update Meta
        update_post_meta($post_id, '_hupa_show_title', $record->checkTitle, false);
        update_post_meta($post_id, '_hupa_custom_title', sanitize_text_field($record->customTitle), false);
        update_post_meta($post_id, '_hupa_title_css', sanitize_text_field($record->titleCss), false);
        update_post_meta($post_id, '_hupa_show_menu', $record->showMenuCheck, false);
        update_post_meta($post_id, '_hupa_select_menu', (int)$record->menuSelect, false);
        update_post_meta($post_id, '_hupa_select_handy_menu', (int)$record->handyMenuSelect, false);
        update_post_meta($post_id, '_hupa_select_top_area', (int)$record->topAreaSelect, false);
        update_post_meta($post_id, '_hupa_show_bottom_footer', $record->bottomFooterCheck, false);
        update_post_meta($post_id, '_hupa_select_header', (int)$record->customHeaderSelect, false);
        update_post_meta($post_id, '_hupa_select_footer', (int)$record->customFooterSelect, false);
        update_post_meta($post_id, '_hupa_show_top_footer', (int)$record->topFooterWidgetCheck, false);
        update_post_meta($post_id, '_hupa_show_widgets_footer', (int)$record->footerWidgetCheck, false);

        update_post_meta($post_id, '_hupa_select_top_area', (int)$record->topAreaShoWSelect, false);
        update_post_meta($post_id, '_hupa_top_area_container', (int)$record->topAriaContainer, false);
        update_post_meta($post_id, '_hupa_select_container', (int)$record->menuContainerSelect, false);
        update_post_meta($post_id, '_hupa_main_container', (int)$record->mainContainerSelect, false);
    }
}//endClass

$hupa_classic_metabox = HupaStarterClassicMetaBox::classic_metabox_instance();
if (!empty($hupa_classic_metabox)) {
    $hupa_classic_metabox->init_classic_metabox();
}
