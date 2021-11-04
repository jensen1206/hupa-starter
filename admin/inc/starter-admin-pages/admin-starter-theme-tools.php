<?php
defined( 'ABSPATH' ) or die();
/**
 * Jens Wiecker PHP Class
 * @package Jens Wiecker WordPress Plugin
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */


$args = array(
	'post_type' => 'starter-footer',
	'post_status' => 'publish',
	'posts_per_page' => -1
);
$footer = new WP_Query( $args );


$args = array(
	'post_type' => 'starter-header',
	'post_status' => 'publish',
	'posts_per_page' => -1
);
$header = new WP_Query( $args );


?>
<div class="wp-bs-starter-wrapper my3">
    <div class="container">
        <div class="card shadow-sm">
            <h5 class="card-header d-flex align-items-center bg-hupa py-4">
                <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
				<?= __( 'Social Media', 'bootscore' ) ?> </h5>
            <div class="card-body pb-4" style="min-height: 72vh">

                <div class="d-flex align-items-center">
                    <h5 class="card-title"><i
                                class="hupa-color fa fa-arrow-circle-right"></i> <?= __( 'Theme Tools', 'bootscore' ) ?>
                        / <span id="currentSideTitle"><?= __( 'Top Area', 'bootscore' ) ?></span>
                    </h5>
                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                </div>
                <hr>
                <div class="settings-btn-group d-flex">
                    <button data-site="<?= __( 'Social Media', 'bootscore' ) ?>" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseSettingsSocialStart"
                            aria-expanded="true" aria-controls="collapseSettingsSocialStart"
                            class="btn-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled><i
                                class="fa fa-share-square-o"></i>&nbsp;
		                <?= __( 'Social Media', 'bootscore' ) ?>
                    </button>
                    <button data-site="<?= __( 'Top Area', 'bootscore' ) ?>" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseSettingsTopArea"
                            aria-expanded="true" aria-controls="collapseSettingsTopArea"
                            class="btn-collapse btn btn-hupa btn-outline-secondary btn-sm"><i
                                class="fa fa-th-list"></i>&nbsp;
						<?= __( 'Top Area', 'bootscore' ) ?>
                    </button>


                </div>
                <hr>
                <div id="settings_display_data">

                    <!--  TODO JOB WARNING Social Media STARTSEITE -->
                    <div class="collapse show" id="collapseSettingsSocialStart"
                         data-bs-parent="#settings_display_data">
                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">
                            <div id="share-buttons" class="mb-3">
                                <div class="container">
                                    <form class="sendAjaxThemeForm" action="#" method="post">
                                        <input type="hidden" name="method" value="theme_form_handle">
                                        <input type="hidden" name="handle" value="theme_social">
                                        <div data-type="hupa_social" class="hupaSortable row row-cols-1 row-cols-md-2 g-md-1 row-cols-lg-2 g-2 g-lg-3 row-cols-xl-3 g-xl-2">
                                            <!--//TODO JOB SOCIAL MEDIA-->
											<?php
											$media = apply_filters( 'get_social_media', '' );
											if ( $media->status ): foreach ( $media->record as $tmp ): ?>
                                                <div class="media<?= $tmp->id ?> col">
                                                    <div class="p-3 bg-light border shadow-sm py-2 h-100">
                                                        <h5 class="text-center text-muted py-2 mb-0"><?= $tmp->bezeichnung ?>
                                                            <i class="sortableArrow float-start fa fa-arrows"></i>
                                                        </h5>
                                                        <hr class="mt-1 mb-3">
                                                        <div class="d-flex">
                                                <span class="<?= $tmp->btn ?> justify-content-center d-flex align-items-center btn-xxl rounded">
                                                    <i class="<?= $tmp->icon ?>"></i></span>
                                                            <div class="d-flex flex-column justify-content-center px-3">
                                                                <div class="form-check form-switch py-1">
                                                                    <input class="form-check-input"
                                                                           name="<?= $tmp->slug ?>post_check"
                                                                           type="checkbox"
                                                                           id="PostCheck<?= $tmp->id ?>" <?= ! $tmp->post_check ?: 'checked' ?>>
                                                                    <label class="form-check-label"
                                                                           for="PostCheck<?= $tmp->id ?>"><?= __( 'Posts', 'bootscore' ); ?></label>
                                                                </div>
                                                                <div class="form-check form-switch py-1">
                                                                    <input class="form-check-input"
                                                                           name="<?= $tmp->slug ?>top_check"
                                                                           type="checkbox"
                                                                           id="TopCheck<?= $tmp->id ?>" <?= ! $tmp->top_check ?: 'checked' ?>>
                                                                    <label class="form-check-label"
                                                                           for="TopCheck<?= $tmp->id ?>"><?= __( 'Top Area | Widget', 'bootscore' ); ?></label>
                                                                </div>
                                                            </div>
                                                        </div>
														<?php if ( $tmp->slug !== 'print_' ): ?>
                                                            <div class=" pt-1 my-1">
                                                                <label for="Share<?= $tmp->id ?>"
                                                                       class="form-label mb-0"><small
                                                                            class="text-muted"><?= __( 'Share subject', 'bootscore' ); ?></small></label>
                                                                <input type="text" name="<?= $tmp->slug ?>share_txt"
                                                                       value="<?= $tmp->share_txt ?>"
                                                                       placeholder="<?= __( 'Look what I found:', 'bootscore' ); ?>"
                                                                       class="form-control" id="Share<?= $tmp->id ?>">
                                                            </div>
                                                            <!--<div class="my-1">
                                                                <label for="Url<?= $tmp->id ?>"
                                                                       class="form-label mb-0"><small
                                                                            class="text-muted"><?= __( 'Url for Top Area or Widget', 'bootscore' ); ?></small></label>
                                                                <input type="text" name="<?= $tmp->slug ?>url"
                                                                       class="form-control" value="<?= $tmp->url ?>"
                                                                       id="Url<?= $tmp->id ?>">
                                                            </div>-->
														<?php endif; ?>
                                                    </div>
                                                </div>
											<?php endforeach; endif; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div><!--collapse-->

                    <!--  TODO JOB WARNING TOP AREA -->
                    <div class="collapse" id="collapseSettingsTopArea"
                         data-bs-parent="#settings_display_data">
                        <form class="sendAjaxThemeForm" action="#" method="post">
                            <input type="hidden" name="method" value="theme_form_handle">
                            <input type="hidden" name="handle" value="theme_tools">
                            <!--TODO JOB TOP SEKTIONEN -->
                            <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray">
                                <hr>
                                <h5 class="card-title mb-0">
                                    <i class="font-blue fa fa-gears"></i>&nbsp; <?= __( 'Top Area', 'bootscore' ); ?> <?= __( 'sections', 'bootscore' ) ?>
                                </h5>
                                <hr>
                                <div class="form-text"><?= __( 'The position or order of the individual sections can be changed by <b>moving</b> the boxes.', 'bootscore' ) ?></div>
                                <hr>
                                <div class="col-lg-12 pt-2">
                                    <div data-type="hupa_tools" class="hupaSortable row row-cols-1 row-cols-md-2 row-cols-xl-4 g-2">
						                <?php
						                $tools = apply_filters('get_hupa_tools_by_args', 'WHERE type="top_area" ORDER BY position ASC');

						                if($tools->status): foreach ($tools->record as $tmp):
							                $tmp->slug  === 'areamenu_' ? $bez = __( 'Top Menu', 'bootscore' ) : $bez = __( 'Widget Box', 'bootscore' );
							                $tmp->slug === 'areamenu_' && !has_nav_menu( 'top-area-menu' ) ? $disabled = 'disabled' : $disabled = '';
							                ?>
                                            <div class="menu<?=$tmp->id?> col">
                                                <div class="p-3 bg-light border shadow-sm py-2 h-100">
                                                    <h5 class="text-muted py-2 mb-0 text-center"><?=$tmp->bezeichnung?>
                                                        <i class="sortableArrow float-start fa fa-arrows"></i>
                                                    </h5>
                                                    <fieldset <?=$disabled?>>
                                                    <small class="d-block form-text text-center mt-0"> <?=$bez?></small>
                                                    <hr class="mt-2 mb-3">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" name="<?=$tmp->slug?>aktiv"
                                                               type="checkbox" id="flexSwitchAktiv<?= $tmp->id ?>" <?=!$tmp->aktiv ?: 'checked'?>>
                                                        <label class="form-check-label" for="flexSwitchAktiv<?= $tmp->id ?>"><?= __( 'show', 'bootscore' ) ?></label>
                                                    </div>
                                                    <hr>
                                                    <div class="mb-3">
                                                        <label for="InputCssClass<?= $tmp->id ?>" class="form-label"><?= __( 'Box CSS Class', 'bootscore' ) ?></label>
                                                        <input type="text" class="form-control"
                                                               id="InputCssClass<?= $tmp->id ?>"
                                                               name="<?=$tmp->slug?>css_class"
                                                               value="<?=$tmp->css_class?>"
                                                               aria-describedby="InputCssClassHelp<?= $tmp->id ?>">
                                                        <div id="InputCssClassHelp<?= $tmp->id ?>" class="form-text"><?= __( 'Enter CSS class <b>without</b> point.', 'bootscore' ) ?></div>
                                                    </div>
                                                    </fieldset>
                                                </div>
                                            </div>
						                <?php endforeach; endif; ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="ajax-status-spinner ms-auto d-inline-block pt-3 mb-2 pe-2"></div>
                    </div>
                </div><!--display-data-->

            </div>
        </div>
    </div>
</div>