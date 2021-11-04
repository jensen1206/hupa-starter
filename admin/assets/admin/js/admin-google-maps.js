/**
 * JavaScript Google-Maps
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 *
 */


/*=============================================
========== Google Maps Image Upload  ==========
===============================================
*/

function gmaps_add_pin(elm) {
    let mediaFrame,
        btnAddImg = elm,
        data_container = elm.getAttribute('data-container'),
        imgContainer = document.querySelector("#" + data_container + " .gmaps-image-container"),
        defaultImg = document.querySelector("#" + data_container + " .maps-default-image"),
        inputPin = document.querySelector("#" + data_container + " .gmaps-input-pins"),
        delImgBtn = document.querySelector("#" + data_container + " .delete-gmaps-media-img");

    if (mediaFrame) {
        mediaFrame.open();
        return;
    }
    mediaFrame = wp.media({
        title: hupa_starter.theme_language.media_frame_pin_title,
        button: {
            text: hupa_starter.theme_language.media_frame_select_btn
        },
        multiple: false
    });

    mediaFrame.on('select', function () {
        const attachment = mediaFrame.state().get('selection').first().toJSON();

        imgContainer.innerHTML = '<img class="img-fluid" src="' + attachment.url + '" alt="' + attachment.alt + '" width="80"/>';
        imgContainer.classList.remove('d-none');
        btnAddImg.classList.add('d-none');
        delImgBtn.classList.remove('d-none');
        defaultImg.classList.add('d-none');
        inputPin.value = attachment.id;
    });
    mediaFrame.open();
}

function gmaps_delete_pin(elm) {

    let data_container = elm.getAttribute('data-container'),
        imgContainer = document.querySelector("#" + data_container + " .gmaps-image-container"),
        defaultImg = document.querySelector("#" + data_container + " .maps-default-image"),
        inputPin = document.querySelector("#" + data_container + " .gmaps-input-pins"),
        addImgBtn = document.querySelector("#" + data_container + " .add-gmaps-media-img");

    imgContainer.innerHTML = '';
    imgContainer.classList.add('d-none');
    addImgBtn.classList.remove('d-none');
    elm.classList.add('d-none');
    defaultImg.classList.remove('d-none');
    inputPin.value = '';
}

function element_onblur(elm){
    elm.blur();
}

let googleMapsPins = document.getElementById("maps-pin-wrapper");
if (googleMapsPins) {
    const mapPins = {
        'method': 'get_google_maps_pins',
        'handle': 'template'
    }
    send_xhr_maps_data(mapPins, false);
}

let themeSendBtnFormular = document.querySelectorAll(".sendAjaxBtnForm");
if (themeSendBtnFormular) {
    let formNodes = Array.prototype.slice.call(themeSendBtnFormular, 0);
    formNodes.forEach(function (formNodes) {
        formNodes.addEventListener("submit", function (e) {
            e.preventDefault();
            const sendData = {
                'daten': JSON.stringify(serialize_form_data(formNodes)),
                'method': 'theme_google_maps'
            }
            send_xhr_maps_data(sendData, false);
        });
    });
}

/*======================================
========== AJAX DATEN SENDEN  ==========
========================================
*/
function send_xhr_maps_data(data, is_formular = true) {
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
            if (data.status) {
                success_message(data.msg);
            } else {
                if (data.msg) {
                    warning_message(data.msg);
                }
            }
            if (data.maps_template) {
                let html = '';
                let i = 1;
                for (const [key, value] of Object.entries(data.pins)) {
                    html += render_pin_templates(value, i);
                    i++;
                }
                googleMapsPins.innerHTML = html;
                let addBtn = `
                <div class="d-flex py-3">
                <button type="button" onclick="add_gmaps_pin(this)" class="btn btn-secondary m-1">
                <i class="fa fa-plus"></i>&nbsp; ${hupa_starter.theme_language.btn_add_pin} </button>
                <div class="m-1">
                 <button class="btn btn-blue" onclick="element_onblur(this);" type="submit"><i class="fa fa-save"></i> ${hupa_starter.theme_language.save}</button>   
                </div>
                </div>`;
                googleMapsPins.insertAdjacentHTML("afterend", addBtn);
            }
        }
    }
}


function add_gmaps_pin(elm){

    elm.blur();
    let gmapsPinElements = document.querySelectorAll(".gmaps-pins-wrapper");
    let id = gmapsPinElements.length + 1;
    render_pin_templates(false, id, true);
}

function delete_custom_pin(id)
{
    if(id === 1){
        return false;
    }
    document.getElementById("mapsPin"+id).remove();
}

function render_pin_templates(data = false, id = false, add = false) {

    if(!id){
        return false;
    }

    let lang = hupa_starter.theme_language;
    let defImg = `<img class="img-fluid" src="${hupa_starter.admin_url}assets/images/img-placeholder.svg" alt="" width="80">`;
    let html = `
        <div id="mapsPin${id}" class="gmaps-pins-wrapper border rounded my-3 shadow-sm p-3 bg-custom-gray">
        <hr>
        <div class="d-flex">
        <h5 class="card-title">
        <i class="hupa-color fa fa-map-marker"></i>&nbsp; ${lang.pin} #${id}
        </h5>
        <div class="ms-auto">`;
    if (id > 1) {
        html += `<button type="button" onclick="delete_custom_pin(${id})" 
                  class="btn-delete-pin btn btn-danger btn-sm">
                  <i class="fa fa-trash-o"></i>&nbsp; ${lang.delete_btn}
                  </button>`;
    }
    html += `</div>
    </div>
    <hr>
    <div class="container">
    <div class="row">
    <div class="col-lg-6">
    <div class="form-floating">
    <input type="text" class="form-control" value="${data ? data.coords : ''}" name="map_pin_coords" id="Pin-${id}Coords">
    <label for="Pin-${id}Coords">${lang.lbl_coords}</label>
    </div>
    </div>
    <div class="col-lg-6">
    <div class="form-floating">
    <textarea class="form-control" name="map_pin_text" id="Pin-${id}Text"
    style="height: 100px">${data ? data.info_text : ''}</textarea>
    <label for="Pin-${id}Text">${lang.lbl_info_txt} </label>
    <p class="fst-italic ft-light ps-1">
    ( ${lang.help_info_txt} )
    <p>
    </div>
    </div>
    </div>
    <div class="row">
    <div class="col-lg-6">
    <div class="form-check form-switch mt-4">
    <input onclick="changeRangeUpdate(this);"  class="form-check-input" name="map_pin_custompin"
    type="checkbox" id="MapPin-${id}Custom" data-bs-toggle="collapse"
    data-bs-target="#customPinContainer${id}" ${data && data.custom_pin_check ? 'checked' : ''}>
    <label class="form-check-label" for="MapPin-${id}Custom">
    ${lang.lbl_custom_pin}
    </label>
    </div>
    </div>
    </div>
    <div class="collapse ${data && data.custom_pin_check ? 'show' : ''}" id="customPinContainer${id}">
    <div class="col-lg-12 pt-2">
    <hr>
    <h6 class="fw-bold pt-1">${lang.head_custom_pin}</h6>
    </div>
    <div class="row">
    <div class="col-lg-6">
    <!--Image Upload-->   
    <div id="media-container${id}">
    <div class="gmaps-image-container ${data && data.custom_pin_img ? '' : 'd-none'}">
        ${data && data.custom_pin_img ? data.custom_pin_img : ''}
    </div> 
    <div class="maps-default-image ${data && data.custom_pin_img ? 'd-none' : ''}">
        ${defImg}
    </div>
   <b onclick="gmaps_add_pin(this);" 
   class="add-gmaps-media-img d-inline-block text-muted cursor-pointer pt-2 ${data && data.custom_pin_img_id ? 'd-none' : ''}" 
   data-container="media-container${id}"><i class="text-success fa fa-plus"></i> ${lang.add_pin}</b>
   <b onclick="gmaps_delete_pin(this);" 
   class="delete-gmaps-media-img d-inline-block text-muted cursor-pointer pt-2 ${data && data.custom_pin_img_id ? '' : 'd-none'}" 
   data-container="media-container${id}"><i class="text-danger fa fa-trash"></i> ${lang.delete_btn}</b>
    <input class="gmaps-input-pins" value="${data && data.custom_pin_img_id ? data.custom_pin_img_id : 0}" 
    type="hidden" name="custom_pin_img">                                          
    </div><!--media-->
    <!--Image Upload--> 
    </div>
    <div class="col-lg-6">
    <div id="custom-pin-height${id}" class="col-lg-12 pt-3">
    <label for="CustomPinHeightRange${id}"
    class="count-box form-label pb-1">${lang.height}:
    <span class="show-range-value">${data ? data.custom_height : '25'}</span> (px)</label>
    <input data-container="custom-pin-height${id}" type="range"
    name="map_custom_pin_height" min="10" max="70" value="${data ? data.custom_height : '25'}"
    class="form-range sizeRange" id="CustomPinHeightRange${id}">
    </div>
    <div id="custom-pin-width${id}" class="col-lg-12 pt-3">
    <label for="CustomPinWidthRange${id}"
    class="count-box form-label pb-1">${lang.width}:
    <span class="show-range-value">${data ? data.custom_width : '25'}</span> (px)</label>
    <input data-container="custom-pin-width${id}" type="range"
    name="map_custom_pin_width" min="10" max="70" value="${data ? data.custom_width : '25'}"
    class="form-range sizeRange" id="CustomPinWidthRange${id}">
    </div>
    </div>
    </div>
    </div>
    </div>
    </div> `;
    if(add){
        document.getElementById("maps-pin-wrapper").insertAdjacentHTML("beforeend", html);
    } else {
        return html;
    }
}

function createRandomCode(length) {
    let randomCodes = '';
    let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        randomCodes += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return randomCodes;
}

