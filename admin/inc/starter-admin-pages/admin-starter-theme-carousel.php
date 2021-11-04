<?php
defined('ABSPATH') or die();
/**
 * ADMIN CAROUSEL
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

?>
<div class="wp-bs-starter-wrapper">

    <div class="container">
        <!--  <select name="page-dropdown"
                onchange='document.location.href=this.options[this.selectedIndex].value;'>
            <option value="">
                <?php echo esc_attr(__('Select page')); ?></option>
            <?php
        $pages = get_pages();
        foreach ($pages as $page) {
            $option = '<option value="' . get_page_link($page->ID) . '">';
            $option .= $page->post_title;
            $option .= '</option>';
            echo $option;
        }
        ?>
        </select>-->
        <div class="card shadow-sm">

            <h5 class="card-header d-flex align-items-center bg-hupa py-4">
                <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
                <?= __('Theme Carousel', 'bootscore') ?> </h5>
            <div class="card-body pb-4" style="min-height: 72vh">
                <div class="d-flex align-items-center">
                    <h5 class="card-title"><i
                                class="hupa-color fa fa-arrow-circle-right"></i> <?= __('Carousel', 'bootscore') ?>
                        / <span id="currentSideTitle"><?= __('Overview', 'bootscore') ?></span>
                    </h5>
                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                </div>
                <hr>
                <button class="btn btn-blue" data-bs-toggle="modal"
                        data-bs-target="#carouselModal">
                    <i class="fa fa-plus"></i>
                    &nbsp; <?= __('Create new carousel', 'bootscore') ?></button>
                <!-- CAROUSEL -->
                <hr>
                <div id="theme-carousel-data"></div>
            </div>
        </div>
    </div>

    <!--MODAL-->
    <div class="modal fade" id="carouselModal" tabindex="-1" aria-labelledby="carouselModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="sendAjaxCarouselBtnForm" action="#" method="post">
                    <input type="hidden" name="method" value="add_carousel">
                    <div class="modal-header bg-accordion-gray">
                        <h5 class="modal-title" id="carouselModalLabel"><i
                                    class="font-blue fa fa-tasks"></i>&nbsp; <?= __('Create new carousel', 'bootscore') ?>
                            .</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="inputCarouselName"
                                   class="col-form-label fs-5"><b><?= __('Designation', 'bootscore') ?>:</b></label>
                            <input type="text" class="form-control" name="bezeichnung"
                                   aria-describedby="inputCarouselHelp" id="inputCarouselName" required>
                            <div id="inputCarouselHelp"
                                 class="form-text"><?= __('Enter carousel name (max 50 characters)', 'bootscore') ?>.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="text-danger fa fa-times"></i> &nbsp; <?= __('Cancel', 'bootscore') ?>
                        </button>
                        <button type="submit" class="btn btn-blue btn-sm" data-bs-dismiss="modal">
                            <i class="fa fa-plus"></i>
                            &nbsp; <?= __('Create carousel', 'bootscore') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Small Modal -->
    <div class="modal fade" id="ThemeBSModal" tabindex="-1" aria-labelledby="CmsSmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="text-danger fa fa-times"></i>&nbsp <?= __('Cancel', 'bootscore') ?></button>
                    <button id="smallThemeSendModalBtn" type="button" class="btn btn-sm"></button>
                </div>
            </div>
        </div>
    </div>

    <!--Modal-->
    <div class="modal fade" id="dialog-add-icon" tabindex="-1" aria-labelledby="dialog-add-iconLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl modal-fullscreen-xl-down modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-hupa">
                    <h5 class="modal-title" id="exampleModalLabel"><?= __('Icon auswählen', 'bootscore'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="icon-grid"></div>
                </div>
            </div>
        </div>
    </div>