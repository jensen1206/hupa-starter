<?php
defined('ABSPATH') or die();
/**
 * ADMIN HOME SITE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

$status = false;
$loginAktiv = false;
$ifUrl = true;
$licenseInfo = apply_filters('post_scope_resource', 'license/'.site_url());

if($licenseInfo->status && $licenseInfo->success) {
    $status = true;
    if(site_url() !== $licenseInfo->install_url || !$licenseInfo->license){
        $msg = 'Die URL der Installation ist <b>nicht</b> bekannt. Theme wurde <b>deaktiviert</b>.';
        delete_option( "hupa_product_client_id" );
        delete_option( "hupa_product_client_secret" );
        update_option('hupa_starter_message',$msg);
        delete_option('hupa_starter_product_install_authorize');
    }
}
if($status && $licenseInfo->login_aktiv) {
    $loginAktiv = true;
}
?>
<div class="wp-bs-starter-wrapper">
    <div class="container">

        <div class="card card-license shadow-sm">
            <h5 class="card-header d-flex align-items-center bg-hupa py-4">

                <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
                <?= __('Theme  Licences', 'bootscore') ?> </h5>
            <div class="card-body pb-4" style="min-height: 72vh">
                <div class="d-flex align-items-center">
                    <h5 class="card-title"><i
                                class="hupa-color fa fa-arrow-circle-right"></i> <?= __('Manage licence', 'bootscore') ?>
                        / <span id="currentSideTitle"><?= __('Settings', 'bootscore') ?></span>
                    </h5>
                </div>
                <hr>
                <div class="settings-btn-group d-flex">
                    <button data-site="<?= __('Settings', 'bootscore') ?>" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseSettingsLicenseSite"
                            aria-expanded="true" aria-controls="collapseSettingsLicenseSite"
                            class="btn-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled><i
                                class="fa fa-share-alt"></i>&nbsp;
                        Hupa <?= __('Lizenzen', 'bootscore') ?>
                    </button>
                </div>
                <hr>
                <div id="licence_display_data">
                    <!--  TODO JOB WARNING licence STARTSEITE -->
                    <div class="collapse show" id="collapseSettingsLicenseSite"
                         data-bs-parent="#licence_display_data">
                        <div class="border rounded mt-1 shadow-sm p-3 bg-custom-gray" style="min-height: 50vh">
                            <?php if(get_option('hupa_starter_message')): ?>
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <i class="fa fa-exclamation-triangle fa-2x me-2"></i>
                                    <div>
                                        <?=get_option('hupa_starter_message')?>
                                    </div>
                                </div>
                            <?php else: ?>
                            <div class="d-flex flex-wrap align-items-center">
                            <h5 class="card-title">
                                <i class="font-blue fa fa-wordpress"></i>&nbsp;aktive Lizenzen <small class="small font-blue"><?= get_hupa_option('lizenz_login_aktiv') ? '('.$licenseInfo->email.')' : ''?></small>
                            </h5>
                                <?php if(get_hupa_option('lizenz_login_aktiv')): ?>
                                <div class="ms-auto">
                                    <a target="_blank" href="<?=$licenseInfo->login_url?>" style="color: #6c757d"
                                       class="text-decoration-none"> <i class="font-blue fa fa-sign-in"></i>&nbsp; Account Login</a>
                                </div>
                                <?php endif; ?>
                            </div>
                            <hr>
                            <div class="container">
                                <div class="col-xl-10 offset-xl-1 pt-3">
                                    <?php if($status):
                                        foreach ($licenseInfo->data as $tmp):
                                        ?>
                                            <span class="strong-font-weight">Type:</span>
                                            <?=$tmp->produkt_type?> |
                                            <span class="strong-font-weight">Bezeichnung:</span>
                                            <b class="font-blue"> <?=$tmp->product_bezeichnung?></b>
                                               <span class="strong-font-weight"> | Version:</span>
                                                <?=$tmp->last_ver?>
                                                <small class="d-block small-title">
                                                    aktiviert am <?=$tmp->license_date?> um <?=$tmp->license_time?></small>
                                            <hr>
                                    <?php endforeach; endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
