<?php
defined( 'ABSPATH' ) or die();
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

            <form class="sendAjaxBtnForm" action="#" method="post">
                <h5 class="card-header d-flex align-items-center bg-hupa py-4">
                    <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
					<?= __( 'Theme Google Maps', 'bootscore' ) ?> </h5>
                <div class="card-body pb-4" style="min-height: 72vh">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title"><i
                                    class="hupa-color fa fa-arrow-circle-right"></i> <?= __( 'Google API Maps', 'bootscore' ) ?>
                            / <span id="currentSideTitle"><?= __( 'Settings', 'bootscore' ) ?></span>
                        </h5>
                        <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                    </div>
                    <hr>
                    <div class="settings-btn-group d-flex">
                        <button data-site="<?= __( 'API Settings', 'bootscore' ) ?>" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseSettingsMapsSite"
                                aria-expanded="true" aria-controls="collapseSettingsMapsSite"
                                class="btn-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled><i
                                    class="fa fa-gears"></i>&nbsp;
							<?= __( 'Maps API Settings', 'bootscore' ) ?>
                        </button>

                        <button onclick="changeRangeUpdate();" data-site="<?= __( 'Manage API pins', 'bootscore' ) ?>" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseSettingsPinsSite"
                                aria-expanded="false" aria-controls="collapseSettingsPinsSite"
                                class="btn-collapse btn btn-hupa btn-outline-secondary btn-sm"><i class="fa fa-map"></i>&nbsp;
							<?= __( 'Manage API pins', 'bootscore' ) ?>
                        </button>

                        <button class="btn btn-blue btn-sm ms-auto" onclick="element_onblur(this);" type="submit">
                            <i class="fa fa-save"></i>&nbsp; <?= __( 'save', 'bootscore' ) ?>
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
                                    Es können Breite und Höhe zu den Shortcode hinzugefügt werden.
                                    (<code>width="1200px" height="800px"</code>) oder auch (<code>width="100%" height="450px"</code>)
                                </div>
                                <hr>
                                <h5 class="card-title d-flex align-items-center flex-wrap">
                                    <i class="font-blue fa fa-gears"></i>&nbsp; <?= __( 'General settings', 'bootscore' ) ?>
                                    <small class="small ms-auto"> <b>Shortcode:</b> [gmaps id="api-maps"]</small>
                                </h5>

                                <hr>

                                <!--=============================================================
								========================= SEITE 1 START =========================
								=================================================================
								-->
                                <div class="container">
                                    <div class="mb-4 mt-4 row">
                                        <label for="MapApiKey" class="col-sm-2 col-form-label fw-bold">API Key:</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" value="<?=get_hupa_option('map_apikey')?>" name="map_apikey" type="text" id="MapApiKey">
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap">
                                        <div class="form-check form-switch my-2 my-md-1 me-md-3">
                                            <input class="form-check-input" name="map_datenschutz" type="checkbox"
                                                   id="MapDatenschutzActive" <?=!get_hupa_option('map_datenschutz') ?: 'checked'?>>
                                            <label class="form-check-label" for="MapDatenschutzActive">
												<?= __( 'Activate privacy query', 'bootscore' ) ?>
                                            </label>
                                        </div>
                                        <div class="form-check form-switch my-2 my-md-1 me-md-3">
                                            <input class="form-check-input" name="map_colorcheck" type="checkbox"
                                                   id="MapColorActive" data-bs-toggle="collapse"
                                                   data-bs-target="#custom-color-container" aria-expanded="false"
                                                   aria-controls="custom-color-container" <?=!get_hupa_option('map_colorcheck') ?: 'checked'?>>
                                            <label class="form-check-label" for="MapColorActive">
												<?= __( 'custom colour scheme', 'bootscore' ) ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- border rounded-->

                            <div class="border rounded mt-3 shadow-sm p-3 bg-custom-gray">
                                <hr>
                                <h5 class="card-title">
                                    <i class="font-blue fa fa-map-marker"></i>&nbsp; <?= __( 'Standard Pin', 'bootscore' ) ?>
                                </h5>
                                <hr>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-6">

                                            <div id="media-standard-pin">
                                                <!--Image Upload-->
		                                        <?php
		                                        $imgStPin = '';
		                                        $imgId   = get_hupa_option('map_standard_pin');
		                                        if ( $imgId ) {
			                                        $img     = wp_get_attachment_image_src( $imgId, 'large' );
			                                        $imgStPin = '<img class="range-image img-fluid" src="' . $img[0] . '" width="80">';
		                                        } ?>
                                                <!-- LOGO IMAGE -->
                                                <div class="gmaps-image-container <?= $imgStPin ? '' : 'd-none' ?>">
			                                        <?= $imgStPin ?>
                                                </div>
                                                <!-- DEFAULT IMAGE -->
                                                <div class="maps-default-image <?= $imgStPin ? 'd-none' : '' ?>">
                                                    <img class="img-fluid"
                                                         src="<?= THEME_ADMIN_URL . 'assets/images/img-placeholder.svg' ?>"
                                                         alt=""
                                                         width="80">
                                                </div>
                                                <p class="hide-if-no-js">
                                                <a href="<?=esc_url(get_upload_iframe_src());?>" onclick="gmaps_add_pin(this); return false;" type="button" data-container="media-standard-pin"
                                                  class="add-gmaps-media-img mt-3 mb-2 btn btn-outline-secondary btn-sm <?=$imgStPin ? 'd-none' : ''?>">
                                                    <i class="fa fa-picture-o"></i>&nbsp;
			                                        <?= __( 'Click here to select image', 'bootscore' ) ?>
                                                </a>

                                                <button type="button" data-container="media-standard-pin" onclick="gmaps_delete_pin(this); return false;"
                                                        class="delete-gmaps-media-img btn btn-outline-danger mt-3 mb-2 btn-sm <?=$imgStPin ? '': 'd-none'?>">
                                                    <i class="fa fa-trash"></i>
                                                    &nbsp;<?= __( 'Remove image', 'bootscore' ); ?>
                                                </button>
                                                </p>
                                                <input class="gmaps-input-pins" value="<?=$imgId?>" type="hidden" name="map_standard_pin">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div id="pin-height-range" class="col-lg-12 pt-3">
                                                <label for="PinHeightRange"
                                                       class="count-box form-label pb-1"><?= __( 'Height', 'bootscore' ); ?>
                                                    :
                                                    <span class="show-range-value"><?=get_hupa_option('map_pin_height')?></span> (px)</label>
                                                <input data-container="pin-height-range" type="range"
                                                       name="map_pin_height" min="10" max="70" value="<?=get_hupa_option('map_pin_height')?>"
                                                       class="form-range sizeRange" id="PinHeightRange">
                                            </div>

                                            <div id="pin-width-range" class="col-lg-12 pt-3">
                                                <label for="PinWidthRange"
                                                       class="count-box form-label pb-1"><?= __( 'Wide', 'bootscore' ); ?>
                                                    :
                                                    <span class="show-range-value"><?=get_hupa_option('map_pin_width')?></span> (px)</label>
                                                <input data-container="pin-width-range" type="range"
                                                       name="map_pin_width" min="10" max="70" value="<?=get_hupa_option('map_pin_width')?>"
                                                       class="form-range sizeRange" id="PinWidthRange">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- border rounded-->

                            <div class="border rounded mt-3 shadow-sm p-3 bg-custom-gray collapse <?=get_hupa_option('map_colorcheck') ?'show' : ''?>"
                                 id="custom-color-container">
                                <hr>
                                <h5 class="card-title">
                                    <i class="font-blue fa fa-paint-brush"></i>&nbsp; <?= __( 'Map colour scheme', 'bootscore' ) ?>
                                </h5>
                                <hr>
                                <div class="container">
                                    <div class="row">
                                        <div class="form-floating">
                                            <p>JavaScript Style Array</p>
                                            <textarea class="form-control" name="map_color" id="MapColor"
                                                      style="height: 400px"><?=get_hupa_option('map_color')?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- border rounded-->

                            <!--============================================================
								========================= SEITE 1 ENDE =========================
								================================================================
								-->
                        </div><!--Collapse-Container-->
                        <!--  TODO JOB WARNING SEITE ZWEI -->
                        <div class="collapse" id="collapseSettingsPinsSite" data-bs-parent="#settings_display_data">
                            <div id="maps-pin-wrapper"></div>
                        </div><!--Collapse-Container-->
                    </div><!--collapse-parent-wrapper-->
                </div><!--card-body-->

            </form>

        </div><!--card-->
    </div><!--container-->
</div><!--bs-wrapper-->
<div id="snackbar-success"></div>
<div id="snackbar-warning"></div>