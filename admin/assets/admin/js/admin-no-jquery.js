/**
 * JavaScript
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 *
 */
//BS TOOLTIP
let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

//Ajax Spinner
let ajaxSpinner = document.querySelectorAll(".ajax-status-spinner");
//Admin BAR OPTIONS BTN
let clickAdminBarOptions = document.getElementById("wp-admin-bar-hupa_options_page");
//RESET MESSAGE ALERT
let resetMsgAlert = document.getElementById("reset-msg-alert");

/*=================================================
========== TOGGLE SETTINGS COLLAPSE BTN  ==========
===================================================
*/
let settingsColBtn = document.querySelectorAll("button.btn-collapse");
if (settingsColBtn) {
    let CollapseEvent = Array.prototype.slice.call(settingsColBtn, 0);
    CollapseEvent.forEach(function (CollapseEvent) {
        CollapseEvent.addEventListener("click", function (e) {
            //Spinner hide
            if (resetMsgAlert) {
                resetMsgAlert.classList.remove('show');
            }

            if (ajaxSpinner) {
                let spinnerNodes = Array.prototype.slice.call(ajaxSpinner, 0);
                spinnerNodes.forEach(function (spinnerNodes) {
                    spinnerNodes.innerHTML = '';
                });
            }

            this.blur();
            if (this.classList.contains("active")) return false;
            let siteTitle = document.getElementById("currentSideTitle");
            let target = this.getAttribute('data-bs-target');
            let dataSite = this.getAttribute('data-site');
            let dataLoad = this.getAttribute('data-load');
            switch (dataLoad) {
                case 'collapseSettingsFontsSite':
                    let fontContainer = document.querySelector('#collapseSettingsFontsSite .pcr-button');
                    if (!fontContainer) {
                        load_js_colorpickr('#collapseSettingsFontsSite');
                    }
                    break;
                case 'collapseSettingsColorSite':
                    let colorContainer = document.querySelector('#collapseSettingsColorSite .pcr-button');
                    if (!colorContainer) {
                        load_js_colorpickr('#collapseSettingsColorSite');
                    }
                    break;
                case'loadInstallFonts':
                    get_install_fonts_overview();
                    break;
                case'loadInstallFormularFonts':
                    load_install_formular_fonts();
                    break;

            }
            siteTitle.innerText = dataSite;
            remove_active_btn();
            this.classList.add('active');
            this.setAttribute('disabled', true);
        });
    });

    function remove_active_btn() {
        for (let i = 0; i < CollapseEvent.length; i++) {
            CollapseEvent[i].classList.remove('active');
            CollapseEvent[i].removeAttribute('disabled');
        }
    }
}


/*=========================================
========== AJAX FORMS AUTO SAVE  ==========
===========================================
*/

let themeSendFormTimeout;
let themeSendFormular = document.querySelectorAll(".sendAjaxThemeForm:not([type='button'])");
if (themeSendFormular) {
    let formNodes = Array.prototype.slice.call(themeSendFormular, 0);
    formNodes.forEach(function (formNodes) {
        formNodes.addEventListener("keyup", form_input_ajax_handle, {passive: true});
        formNodes.addEventListener('touchstart', form_input_ajax_handle, {passive: true});
        formNodes.addEventListener('change', form_input_ajax_handle, {passive: true});

        function form_input_ajax_handle(e) {
            let spinner = Array.prototype.slice.call(ajaxSpinner, 0);
            spinner.forEach(function (spinner) {
                spinner.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...';
            });
            clearTimeout(themeSendFormTimeout);
            themeSendFormTimeout = setTimeout(function () {
                send_xhr_form_data(formNodes);
            }, 1000);
        }
    });
}

/**====================================================
 ================ BTN DELETE FONT MODAL================
 ======================================================*/
let fontDeleteModal = document.getElementById('fontDeleteModal');
if (fontDeleteModal) {
    fontDeleteModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id');
        document.querySelector('.btn_delete_font').setAttribute('data-id', id);
    })
}

function delete_install_font(e) {
    const data = {
        'method': 'delete_font',
        'id': e.getAttribute('data-id')
    }
    send_xhr_form_data(data, false);
}


/*=====================================
========== SYNC FONT FOLDER  ==========
=======================================
*/
function sync_font_folder(e) {
    const data = {
        'method': 'sync_font_folder'
    }
    e.classList.add('d-none');
    send_xhr_form_data(data, false);
}

function after_sync_folder() {
    show_message_collapse('collapseSuccessMsg');
    message_fadeIn_opacity('collapseSuccessMsg');
}


function get_smtp_test(e) {
    this.blur();
    const data = {
        'method': 'get_smtp_test'
    }
    send_xhr_form_data(data, false);
}

function btn_install_fonts(e) {
    document.querySelector('.upload_spinner').classList.remove('d-none');
    let demoBtn = e.form.querySelector('#fontDemo');
    demoBtn.classList.add('disabled');
    e.setAttribute('disabled', true);
    send_xhr_form_data(e.form);
}

function change_font_install_select(e) {
    let btn = e.form.querySelector('button');
    let demoBtn = e.form.querySelector('#fontDemo');
    if (e.value) {
        demoBtn.setAttribute('href', `https://start.hu-ku.com/theme-update/stream/font/file/${e.value}/html`);
        demoBtn.classList.remove('disabled');
        btn.removeAttribute('disabled');
    } else {
        btn.setAttribute('disabled', true);
        demoBtn.classList.add('disabled');
    }
}

let showMessageTimeOut;

function message_fadeIn_opacity(collapseId) {
    let successMessage = document.querySelectorAll(".fontSuccessMsg");
    if (successMessage) {
        let msgNodes = Array.prototype.slice.call(successMessage, 0);
        msgNodes.forEach(function (msgNodes) {
            msgNodes.classList.add('fadeOpacity');
            clearTimeout(showMessageTimeOut);
            showMessageTimeOut = setTimeout(function () {
                msgNodes.remove('fadeOpacity');
                show_message_collapse(collapseId);
            }, 20000);
        });
    }
}

function show_message_collapse(id) {
    let SuccessCollapse = document.getElementById(id)
    let bsCollapse = new bootstrap.Collapse(SuccessCollapse, {
        toggle: true
    });
}


/*======================================
========== AJAX DATEN SENDEN  ==========
========================================
*/

function send_xhr_form_data(data, is_formular = true) {

    let xhr = new XMLHttpRequest();
    let formData = new FormData();
    xhr.open('POST', theme_ajax_obj.ajax_url, true);

    if (is_formular) {
        let input = new FormData(data);
        for (let [name, value] of input) {
            formData.append(name, value);
        }
    } else {
        for (let [name, value] of Object.entries(data)) {
            formData.append(name, value);
        }
    }

    formData.append('_ajax_nonce', theme_ajax_obj.nonce);
    formData.append('action', 'HupaStarterHandle');
    xhr.send(formData);
    //Response
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let data = JSON.parse(this.responseText);
            if (data.spinner) {
                show_ajax_spinner(data);
            }
            if (!data.method) {
                return false;
            }
            switch (data.method) {
                case 'change_font_select':
                    return change_font_style_select_input(data);
                case'sync_font_folder':
                    return after_sync_folder();
                case'get_smtp_test':
                    if (data.status) {
                        success_message(data.msg);
                    } else {
                        warning_message(data.msg);
                    }
                    break;
                case 'delete_font':
                    if (data.status) {
                        document.getElementById('installFont-'+data.font).remove();
                        success_message(data.msg);
                    } else {
                        warning_message(data.msg);
                    }
                    break;
                case'load_install_fonts':
                    if (data.status) {
                        get_install_fonts_template(data.record)
                    }
                    break;
                case'load_install_formular_fonts':
                    if(data.status){
                        let html = `<option value=""> auswählen...</option>`;
                        for (const [key, val] of Object.entries(data.record)) {
                            html += `<option value="${val.id}">${val.bezeichnung}</option>`;
                        }
                        document.getElementById('inputInstallFont').innerHTML = html;
                    }
                    break;

                case 'install_api_font':
                    if (data.status) {
                        success_message(data.msg);
                        let selectObject = document.getElementById("inputInstallFont");
                        for (let i = 0; i < selectObject.length; i++) {
                            if (selectObject.options[i].value == data.id)
                                selectObject.remove(i);
                        }
                        document.querySelector('.upload_spinner').classList.add('d-none');
                        document.getElementById('install_font_form').reset();

                    } else {
                        warning_message(data.msg);
                    }
                    break;
            }
        }
    }
}

/*===========================================
========== WordPress Image Upload  ==========
=============================================
*/

let themeUploadMediaImg = document.querySelectorAll(".theme_upload_media_img");
if (themeUploadMediaImg) {
    let btnNodes = Array.prototype.slice.call(themeUploadMediaImg, 0);
    btnNodes.forEach(function (btnNodes) {
        btnNodes.addEventListener("click", function (e) {
            let mediaFrame,
                addImgBtn = this,
                data_container = this.getAttribute('data-container'),
                imgContainer = document.querySelector("#" + data_container + " .admin-wp-media-container"),
                data_multiple = imgContainer.getAttribute('data-multiple'),
                defaultImg = document.querySelector("#" + data_container + " .theme-default-image"),
                multiple = data_multiple === '1',
                imgSizeRange = document.querySelector("#" + data_container + " .sizeRange"),
                img_type = this.getAttribute('data-type'),
                delImgBtn = document.querySelector("#" + data_container + " .theme_delete_media_img");

            if (mediaFrame) {
                mediaFrame.open();
                return;
            }
            // Create a new media frame
            mediaFrame = wp.media({
                title: hupa_starter.theme_language.media_frame_logo_title,
                button: {
                    text: hupa_starter.theme_language.media_frame_select_btn
                },
                multiple: multiple
            });

            mediaFrame.on('select', function () {
                let attachment = mediaFrame.state().get('selection').first().toJSON();
                imgContainer.innerHTML = '<img class="range-image img-fluid" src="' + attachment.url + '" alt="' + attachment.alt + '" width="200"/>';
                imgContainer.setAttribute('data-id', attachment.id);
                imgContainer.classList.remove('d-none');
                addImgBtn.classList.add('d-none');
                delImgBtn.classList.remove('d-none');
                if (imgSizeRange) {
                    imgSizeRange.removeAttribute('disabled');
                }
                defaultImg.classList.add('d-none');

                const logoImg = {
                    'method': 'theme_form_handle',
                    'id': attachment.id,
                    'handle': 'logo_image',
                    'type': img_type
                }
                send_xhr_form_data(logoImg, false);
            });
            mediaFrame.open();
        });
    });

    //Image löschen
    let themeDeleteMediaImg = document.querySelectorAll(".theme_delete_media_img");
    let delNodes = Array.prototype.slice.call(themeDeleteMediaImg, 0);
    delNodes.forEach(function (delNodes) {
        delNodes.addEventListener("click", function (e) {
            let data_container = this.getAttribute('data-container'),
                imgContainer = document.querySelector("#" + data_container + " .admin-wp-media-container"),
                defaultImg = document.querySelector("#" + data_container + " .theme-default-image"),
                imgSizeRange = document.querySelector("#" + data_container + " .sizeRange"),
                addImgBtn = document.querySelector("#" + data_container + " .theme_upload_media_img");

            if (imgSizeRange) {
                imgSizeRange.setAttribute('disabled', true);
            }
            imgContainer.innerHTML = '';
            addImgBtn.classList.remove('d-none');
            imgContainer.removeAttribute('data-id');
            this.classList.add('d-none');
            defaultImg.classList.remove('d-none');

            let checkHeaderLogo = document.getElementById("CheckHeaderLogoActive");
            if (checkHeaderLogo) {
                //checkHeaderLogo.checked = true;
                /*let loginLogoCollapse = document.getElementById('collapseImageForLogin');
                let bsCollapse = new bootstrap.Collapse(loginLogoCollapse, {
                    toggle: true
                });*/
            }
            const logoImg = {
                'method': 'theme_form_handle',
                'handle': 'logo_image',
                'type': this.getAttribute('data-type'),
                'id': ''
            }
            send_xhr_form_data(logoImg, false);

        });
    });
}
//


/*let changeRangeFunktion = document.querySelectorAll(".sizeRange");
if(changeRangeFunktion) {
    let rangeNodes = Array.prototype.slice.call(changeRangeFunktion, 0);
    rangeNodes.forEach(function (rangeNodes) {
        rangeNodes.addEventListener('mousedown', change_range_ajax_handle, false);
       // rangeNodes.addEventListener('mousemove', change_range_ajax_handle, false);
       // rangeNodes.addEventListener('mouseup', change_range_ajax_handle, false);
       // rangeNodes.addEventListener('keydown', change_range_ajax_handle, false);
        rangeNodes.addEventListener('touchstart', change_range_ajax_handle, false);
        rangeNodes.addEventListener('change', change_range_ajax_handle, false);

        function change_range_ajax_handle() {
            this.blur();
            let rangeContainer = this.getAttribute('data-container');
            let showRange = document.querySelector("#" + rangeContainer + " .show-range-value");
            let rangeImage = document.querySelector("#" + rangeContainer + " .range-image");
            if (rangeImage) {
                //* 0.5
                rangeImage.style.width = this.value + 'px';
            }
            showRange.innerHTML = this.value;
        }
    });
}

*/

function changeRangeUpdate(event = false) {
    if (event) event.blur();
    let changeRangeFunktion = document.querySelectorAll("input.sizeRange");
    if (changeRangeFunktion) {
        changeRangeFunktion.forEach(range => {
            if ("oninput" in range) {
                range.addEventListener("input", function () {
                    range_update_input_value(range);
                }, false);
            }
        });
    }
}

function range_update_input_value(range) {
    let rangeContainer = range.getAttribute('data-container');
    let showRange = document.querySelector("#" + rangeContainer + " .show-range-value");
    let rangeImage = document.querySelector("#" + rangeContainer + " .range-image");
    if (rangeImage) {
        //* 0.5
        rangeImage.style.width = range.value + 'px';
    }
    showRange.innerHTML = range.value;
}

changeRangeUpdate();

/*======================================
========== AJAX SPINNER SHOW  ==========
========================================
*/
function show_ajax_spinner(data) {
    let msg = '';
    if (data.status) {
        msg = '<i class="text-success fa fa-check"></i>&nbsp; Saved! Last: ' + data.msg;
    } else {
        msg = '<i class="text-danger fa fa-exclamation-triangle"></i>&nbsp; ' + data.msg;
    }
    let spinner = Array.prototype.slice.call(ajaxSpinner, 0);
    spinner.forEach(function (spinner) {
        spinner.innerHTML = msg;
    });
}


/*======================================================
========== ADMIN-FORMULARE SWITCH FIELD EVENT ==========
========================================================
*/

let themeFormSwitchEventFields = document.querySelectorAll(".bs-switch-action");
if (themeFormSwitchEventFields) {
    let switchNodes = Array.prototype.slice.call(themeFormSwitchEventFields, 0);
    switchNodes.forEach(function (switchNodes) {
        switchNodes.addEventListener("click", function (e) {
            let switchContainer = this.getAttribute('data-container');
            let fieldContainer = document.querySelector("#" + switchContainer);
            if (switchNodes.checked) {
                // fieldContainer.setAttribute('disabled', true);
            } else {
                // fieldContainer.removeAttribute('disabled');
            }
        });
    });
}


let themeChangeTemplate = document.querySelectorAll(".change-template");
if (themeChangeTemplate) {
    let switchNodes = Array.prototype.slice.call(themeChangeTemplate, 0);
    switchNodes.forEach(function (switchNodes) {
        switchNodes.addEventListener("click", function (e) {
            let switchContainer = this.getAttribute('data-type');
            const changeTemplate = {
                'method': 'change_beitragslisten_template',
                'id': this.value,
                'type': switchContainer
            }
            send_xhr_form_data(changeTemplate, false);
        });
    });
}


/*=============================================
========== WP-ADMIN-BAR CLICK EVENTS ==========
===============================================
*/
let clickAdminBarUpdates = document.getElementById("wp-admin-bar-hupa_updates");
if (clickAdminBarUpdates) {
    clickAdminBarUpdates.addEventListener("click", function (e) {
        clickAdminBarOptions.classList.remove('hover');
        console.log('CLICK');
    });
}


/*=========================================
========== FORM CHECK CLICK BLUR ==========
===========================================
*/

let clickRadioCheck = document.querySelectorAll(".form-check-input");
if (clickRadioCheck) {
    let formClick = Array.prototype.slice.call(clickRadioCheck, 0);
    formClick.forEach(function (formClick) {
        formClick.addEventListener("click", function (e) {
            this.blur();
        });
    });
}

let changeBlur = document.querySelectorAll(".form-select");
if (changeBlur) {
    let formChange = Array.prototype.slice.call(changeBlur, 0);
    formChange.forEach(function (formChange) {
        formChange.addEventListener("change", function (e) {
            this.blur();
        });
    });
}


/*=================================================
========== FONT SELECT CHANGE FONT-STYLE ==========
===================================================
*/
function font_family_change(val, select) {
    let value = change_input_select_value(val);
    const changeSelect = {
        'method': 'change_font_select',
        'font_family': value,
        'select_container': select
    }
    send_xhr_form_data(changeSelect, false);
}

// XHR RESPONSE
function change_font_style_select_input(data) {
    let container = document.getElementById(data.container);
    if (data.select) {
        let html = '';
        for (const [key, value] of Object.entries(data.select)) {
            html += `<option value="${key}">${value}</option>`;
        }
        container.innerHTML = html;
    }
}

function change_input_select_value(value) {
    let select = (value.value || value.options[value.selectedIndex].value);
    if (!select) {
        return false;
    }
    return select;
}


/*====================================
========== BOOTSTRAP MODAL  ==========
======================================
*/
let ThemeStarterModal = document.getElementById('ThemeBSModal');
if (ThemeStarterModal) {
    ThemeStarterModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let method = button.getAttribute('data-bs-method');
        let type = button.getAttribute('data-bs-type');
        let modalBtnDialog = button.getAttribute('data-bs-dialog');
        let modalBtn = document.getElementById('smallThemeSendModalBtn');

        modalBtn.setAttribute("data-id", id);
        modalBtn.setAttribute("data-method", method);
        modalBtn.setAttribute("data-type", type);
        let modalDialog = ThemeStarterModal.querySelector('.modal-dialog');
        if (modalBtnDialog) {
            modalDialog.classList.add(modalBtnDialog);
        }

        let modalTitle = ThemeStarterModal.querySelector('.modal-title');
        let modalBody = ThemeStarterModal.querySelector('.modal-body');

        //AJAX Modal Text und layout holen
        let xhr = new XMLHttpRequest();
        xhr.open('POST', theme_ajax_obj.ajax_url, true);
        let formData = new FormData();
        formData.append('id', id);
        formData.append('type', type);
        formData.append('method', 'get_modal_layout');
        formData.append('_ajax_nonce', theme_ajax_obj.nonce);
        formData.append('action', 'HupaStarterHandle');
        xhr.send(formData);
        //Response
        xhr.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                if (resetMsgAlert) {
                    resetMsgAlert.classList.remove('show');
                }
                let data = JSON.parse(this.responseText);

                ThemeStarterModal.classList.add(data.modal_typ);
                modalTitle.innerHTML = data.language.modal_header;
                modalBody.innerHTML = data.language.modal_body;
                modalBtn.classList.add(data.btn_typ);
                modalBtn.textContent = data.language.button_txt;
            }
        };
    });

}

let smallThemeSendModalBtn = document.getElementById("smallThemeSendModalBtn");
if (smallThemeSendModalBtn) {
    smallThemeSendModalBtn.addEventListener("click", function () {

        let id = this.getAttribute('data-id');
        let method = this.getAttribute('data-method');
        let type = this.getAttribute('data-type');

        let xhr = new XMLHttpRequest();
        xhr.open('POST', theme_ajax_obj.ajax_url, true);
        let formData = new FormData();
        formData.append('id', id);
        formData.append('method', method);
        formData.append('type', type);
        formData.append('_ajax_nonce', theme_ajax_obj.nonce);
        formData.append('action', 'HupaStarterHandle');
        xhr.send(formData);
        xhr.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                let data = JSON.parse(this.responseText);
                let ThemeStarterModalInstance = document.getElementById('ThemeBSModal');
                let modal = bootstrap.Modal.getInstance(ThemeStarterModalInstance);
                modal.hide();
                if (data.status) {
                    if (data.resetMsg) {
                        resetMsgAlert.classList.add('show');
                    }
                    if (data.delete_carousel) {
                        let delCarousel = document.getElementById("carousel" + data.id);
                        let parentCarousel = delCarousel.parentNode;
                        if (data.if_last) {
                            parentCarousel.remove();
                        } else {
                            delCarousel.remove();
                        }
                    }
                    if (data.delete_slider) {
                        let delSlider = document.getElementById("sliderWrapper" + data.id);
                        delSlider.remove();
                    }
                }
            }
        }
    });
}

let iconSettingsInfoModal = document.getElementById('dialog-add-icon');
if (iconSettingsInfoModal) {
    iconSettingsInfoModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let type = button.getAttribute('data-bs-type');
        let formId = button.getAttribute('data-bs-id');
        let xhr = new XMLHttpRequest();
        let formData = new FormData();
        xhr.open('POST', theme_ajax_obj.ajax_url, true);
        formData.append('_ajax_nonce', theme_ajax_obj.nonce);
        formData.append('action', 'HupaStarterHandle');
        formData.append('method', 'get_fa_icons');
        formData.append('type', type);
        xhr.send(formData);

        //Response
        xhr.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                let data = JSON.parse(this.responseText);
                if (data.status) {
                    let iconGrid = document.getElementById('icon-grid');
                    let icons = data.record;
                    let html = '<div class="icon-wrapper">';
                    icons.forEach(function (icons) {
                        html += `<div onclick="set_select_info_icon('${icons.title}', '${icons.code}', '${icons.icon}');"
                              data-bs-dismiss="modal"   class="info-icon-item" title="${icons.code} | ${icons.title}">`;
                        html += `<i  class="${icons.icon}"></i><small class="sm-icon">${icons.icon}</small>`;
                        html += '</div>';
                    });
                    html += '</div>';
                    iconGrid.innerHTML = html;
                }
            }
        }
    });
}

function set_select_info_icon(title, unicode, icon) {
    let html = `
        <i class="${icon} fa-4x d-block mb-2"></i>
       <span class="d-block mb-1 mt-2"><b class="text-danger d-inline-block" style="min-width: 6rem;">Shortcode:</b> [icon i="${title}"]</span>
       <span class="d-block"><b class="text-danger d-inline-block" style="min-width: 6rem;">Unicode:</b> ${unicode}</span> 
        <hr class="mt-2 mb-1">
        <div class="form-text my-2"><i class="font-blue fa fa-info-circle"></i>
            Es können noch weitere Klassen hinzugefügt werden. Für den <i><b>Unicode</b></i>
            kann als zusätzliches Argument <i class="code text-danger">code="true"</i>
            hinzugefügt werden. 
        </div> <hr class="mt-1 mb-2">
        <b class="d-block">Beispiele</b>
        <hr class="mt-2 mb-2">
        <div class="d-flex flex-wrap">
           <div class="d-block text-center me-2">
               <i class="${icon} fa-2x d-block mb-1"></i>
               [icon i="${title}"]     
            </div>
             <div class="d-block text-center me-2">
               <i class="${icon} fa-spin fa-2x d-block mb-1"></i>
               [icon i="${title} fa-spin"]  
            </div>
              <div class="d-block text-center me-2">
               <i class="${icon} text-danger fa-spin fa-2x d-block mb-1"></i>
               [icon i="${title} fa-spin text-danger"]     
            </div>
             <div class="d-block mt-2 text-center me-2">
               <b class="d-block" style="margin-bottom: .65rem">${unicode}</b>
               [icon i="${title}" code="true"]     
            </div>
        </div>`;

    document.getElementById('shortcode-info').innerHTML = html;
    document.getElementById('resetIcons').classList.remove('d-none');
    //shortWrapper.innerHTML = html;
}

function reset_show_theme_icons(e, id) {
    document.getElementById(id).innerHTML = '';
    e.classList.add('d-none')
}


function get_install_fonts_overview() {
    const installFonts = {
        'method': 'load_install_fonts',
    }
    send_xhr_form_data(installFonts, false);
}

function get_install_fonts_template(data = false) {
    let html = '';
    for (const [keyFamily, valFamily] of Object.entries(data)) {
        html += `
            <div id="installFont-${valFamily.family}" class="col-xl-4 col-lg-6 col-12 p-2">
            <div class="d-flex overflow-hidden position-relative border h-100 w-100 shadow-sm">
                <div class="p-3 d-flex flex-column w-100 h-100">
                    <div class="header-font">
                        <h5 class="strong-font-weight "><i class="font-blue fa fa-arrow-circle-right"></i>&nbsp; ${valFamily.family}</h5>
                        <hr class="mt-0">
                    </div>
                    <div class="font-body">
                        <h6>Schriftstile:</h6>
                        <ul class="li-font-list list-unstyled mb-2">`;
                         for (const [keyStyle, valStyle] of Object.entries(valFamily.styles)) {
                            html += `<li>${valStyle}</li>`;
                        }
                    html +=`</ul>
                    </div>
                    <div class="mt-auto font-footer">
                        <hr class="mt-1">
                        <button data-bs-id="${valFamily.family}" data-bs-toggle="modal"
                                data-bs-target="#fontDeleteModal"
                                class="btn btn-hupa btn-outline-secondary btn-sm">
                            <i class="fa fa-trash"></i>&nbsp; Schrift löschen
                        </button>
                    </div>
                </div>
            </div>
        </div>`;

    }
    document.getElementById('installFontsContainer').innerHTML = html;
}

load_install_formular_fonts();
function load_install_formular_fonts(){
    const installFormularFonts = {
        'method': 'load_install_formular_fonts',
    }
    send_xhr_form_data(installFormularFonts, false);
}

let themeSortable = document.querySelectorAll(".hupaSortable");
if (themeSortable) {
    let sortNodes = Array.prototype.slice.call(themeSortable, 0);
    sortNodes.forEach(function (sortNodes) {
        let elementArray = [];
        const sortable = Sortable.create(sortNodes, {
            animation: 150,
            //filter: ".adminBox",
            handle: ".sortableArrow",
            onMove: function (evt) {
                // return evt.related.className.indexOf('adminBox') === -1;
            },
            onUpdate: function (evt) {
                elementArray = [];
                evt.to.childNodes.forEach(themeSortable => {
                    if (themeSortable.className) {
                        elementArray.push(themeSortable.className);
                    }
                });
                const changeSelect = {
                    'method': 'change_sortable_position',
                    'type': sortNodes.getAttribute('data-type'),
                    'element': elementArray
                }
                send_xhr_form_data(changeSelect, false);
            }
        });
    });
}

//RELOAD PAGE
function reload_settings_page() {
    location.reload();
}

/*==============================================
========== SERIALIZE FORMULAR INPUTS  ==========
================================================
*/
function serialize_form_data(data) {
    let formData = new FormData(data);
    let o = {};
    for (let [name, value] of formData) {
        if (o[name] !== undefined) {
            if (!o[name].push) {
                o[name] = [o[name]];
            }
            o[name].push(value || '');
        } else {
            o[name] = value || '';
        }
    }
    return o;
}

/*=====================================
========== HELPER RANDOM KEY ==========
=======================================
*/
function createRandomCode(length) {
    let randomCodes = '';
    let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        randomCodes += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return randomCodes;
}

function createRandomInteger(length) {
    let randomCodes = '';
    let characters = '0123456789';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        randomCodes += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return randomCodes;
}

function load_js_colorpickr(container) {
    let clrPickrContainer = document.querySelectorAll(container + ' .colorPickers');
    if (clrPickrContainer) {
        let colorNode = Array.prototype.slice.call(clrPickrContainer, 0);
        colorNode.forEach(function (colorNode) {
            let setColor = colorNode.getAttribute('data-color');
            let containerId = colorNode.getAttribute('data-id');
            const newPickr = document.createElement('div');
            colorNode.appendChild(newPickr);
            const pickr = new Pickr({
                el: newPickr,
                default: '#42445a',
                useAsButton: false,
                defaultRepresentation: 'RGBA',
                position: 'left',
                swatches: [
                    '#2271b1',
                    '#3c434a',
                    '#e11d2a',
                    '#198754',
                    '#F44336',
                    '#adff2f',
                    '#E91E63',
                    '#9C27B0',
                    '#673AB7',
                    '#3F51B5',
                    '#2196F3',
                    '#03A9F4',
                    '#00BCD4',
                    '#009688',
                    '#4CAF50',
                    '#8BC34A',
                    '#CDDC39',
                    '#FFEB3B',
                    '#FFC107',
                    'rgba(244, 67, 54, 1)',
                    'rgba(233, 30, 99, 0.95)',
                    'rgba(156, 39, 176, 0.9)',
                    'rgba(103, 58, 183, 0.85)',
                    'rgba(63, 81, 181, 0.8)',
                    'rgba(33, 150, 243, 0.75)',
                    'rgba(3, 169, 244, 0.7)',
                    'rgba(0, 188, 212, 0.7)',
                    'rgba(0, 150, 136, 0.75)',
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(139, 195, 74, 0.85)',
                    'rgba(205, 220, 57, 0.9)',
                    'rgba(255, 235, 59, 0.95)',
                    'rgba(255, 193, 7, 1)'
                ],

                components: {

                    // Main components
                    preview: true,
                    opacity: true,
                    hue: true,

                    // Input / output Options
                    interaction: {
                        hex: true,
                        rgba: true,
                        hsla: true,
                        hsva: true,
                        cmyk: false,
                        input: true,
                        clear: false,
                        save: true,
                        cancel: true,
                    }
                },
                i18n: {

                    // Strings visible in the UI
                    'ui:dialog': 'color picker dialog',
                    'btn:toggle': 'toggle color picker dialog',
                    'btn:swatch': 'color swatch',
                    'btn:last-color': 'use previous color',
                    'btn:save': 'Speichern',
                    'btn:cancel': 'Abbrechen',
                    'btn:clear': 'Löschen',

                    // Strings used for aria-labels
                    'aria:btn:save': 'save and close',
                    'aria:btn:cancel': 'cancel and close',
                    'aria:btn:clear': 'clear and close',
                    'aria:input': 'color input field',
                    'aria:palette': 'color selection area',
                    'aria:hue': 'hue selection slider',
                    'aria:opacity': 'selection slider'
                }
            }).on('init', pickr => {
                pickr.setColor(setColor)
                pickr.setColorRepresentation(setColor);
            }).on('save', color => {
                pickr.hide();
            }).on('changestop', (instance, color, pickr) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = pickr._color.toHEXA().toString(0);
                send_xhr_form_data(colorInput.form);
            }).on('cancel', (instance) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = instance._lastColor.toHEXA().toString(0);
                send_xhr_form_data(colorInput.form);
                pickr.hide();
            }).on('swatchselect', (color, instance) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = color.toHEXA().toString(0);
                send_xhr_form_data(colorInput.form);
            });
        });
    }
}

/*==========================================
========== AJAX RESPONSE MESSAGE  ==========
============================================
*/
function success_message(msg) {
    let x = document.getElementById("snackbar-success");
    x.innerHTML = msg;
    x.className = "show";
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, 3000);
}

function warning_message(msg) {
    let x = document.getElementById("snackbar-warning");
    x.innerHTML = msg;
    x.className = "show";
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, 3000);
}

