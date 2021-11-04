<?php
defined('ABSPATH') or die();
/**
 * ADMIN HOME SITE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */


?>
<div class="wp-bs-starter-wrapper">
    <div class="container">

        <div class="card shadow-sm">
            <h5 class="card-header d-flex align-items-center bg-hupa py-4">
                <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
                <?= __('Theme Google Maps', 'bootscore') ?> </h5>
            <div class="card-body pb-4" style="min-height: 72vh">
                <div class="d-flex align-items-center">
                    <h5 class="card-title"><i
                                class="hupa-color fa fa-arrow-circle-right"></i> <?= __('Google Maps I-Frame', 'bootscore') ?>
                    </h5>
                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                </div>
                <hr>
                <div class="settings-btn-group d-flex">
                    <button data-site="<?= __('Google Maps I-Frame', 'bootscore') ?>" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseSettingsMapsSite"
                            aria-expanded="true" aria-controls="collapseSettingsMapsSite"
                            class="btn-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled><i
                                class="fa fa-google"></i>&nbsp;
                        <?= __('Google Maps I-Frame', 'bootscore') ?>
                    </button>
                </div>
                <hr>
                <div id="settings_display_data">
                    <!--  TODO JOB WARNING MAPS STARTSEITE -->
                    <div class="collapse show" id="collapseSettingsMapsSite"
                         data-bs-parent="#settings_display_data">
                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">
                            <div class="form-text">
                                <i class="fa fa-info-circle font-blue"></i>&nbsp;
                                Es können Breite und Höhe zu den Shortcode hinzugefügt werden. (<code>width="1200px" height="800px"</code>) oder auch (<code>width="100%" height="450px"</code>)
                            </div>
                                <hr>
                                <button data-bs-toggle="modal" data-bs-target="#addIframeMapsModal"
                                        data-bs-type="insert"
                                        class="btn btn-blue-outline btn-sm">
                                    <i class="fa fa-plus"></i>&nbsp; Karte
                                    hinzufügen
                                </button>
                                <hr>
                               <div id="iframe-table" class="table-responsive">
                                <table id="TableGoogleIframe" class="table table-striped table-bordered nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Bezeichnung</th>
                                        <th>Shortcode</th>
                                        <th>Datenschutz</th>
                                        <th>Erstellt</th>
                                        <th>Bearbeiten</th>
                                        <th>Löschen</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Bezeichnung</th>
                                        <th>Shortcode</th>
                                        <th>Datenschutz</th>
                                        <th>Erstellt</th>
                                        <th>Bearbeiten</th>
                                        <th>Löschen</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div><!--Collapse-Container-->
                    </div><!--collapse-parent-wrapper-->
                </div><!--card-body-->
            </div><!--card-->
        </div><!--container-->

        <div class="modal fade" id="addIframeMapsModal" tabindex="-1" aria-labelledby="addIframeMapsModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-hupa">
                        <h5 class="modal-title">New message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="sendAjaxJqueryBtnForm" action="#" method="post">
                        <div class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-hupa btn-outline-secondary" data-bs-dismiss="modal"><i
                                        class="fa fa-times"></i> Abbrechen
                            </button>
                            <button type="submit" data-bs-dismiss="modal"
                                    class="modal-btn btn btn-blue"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade" id="iframeDeleteModal" tabindex="-1" aria-labelledby="iframeDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-hupa">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> I-Frame löschen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <h6 class="text-center">
                                <b class="text-danger">I-Frame wirklich löschen?</b>
                                <small class="d-block">Diese Aktion kann <b class="text-danger">nicht</b> rückgängig gemacht werden!</small>
                            </h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal"><i class="text-danger fa fa-times"></i>&nbsp; Abbrechen
                        </button>
                        <button type="button" data-bs-dismiss="modal" class="btn-delete-iframe btn btn-danger">
                            <i class="fa fa-trash-o"></i>&nbsp; löschen
                        </button>
                    </div>
                </div>
            </div>
        </div>



    </div><!--bs-wrapper-->
    <div id="snackbar-success"></div>
    <div id="snackbar-warning"></div>