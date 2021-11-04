let loadCarousel = document.getElementById("theme-carousel-data");
let carouselSendFormTimeout;

/*======================================
========== LOAD CAROUSEL DATA ==========
========================================
*/
function loadModulScript(src) {
    return new Promise(function (resolve, reject) {
        let script = document.createElement('script');
        script.src = src;
        script.onload = () => resolve(script);
        script.onerror = () => reject(new Error(`Script load error for ${src}`));
        document.head.append(script);
    });
}

if (loadCarousel) {
    let loadCarouselModul = loadModulScript(hupa_starter.admin_js_module + 'carousel-modul.js');
    loadCarouselModul.then(
        script => {
            const loadMethod = {
                'method': 'get_carousel_data'
            }
            send_xhr_carousel_data(loadMethod, false);
        },
        error => console.log(`Error: ${error.message}`)
    );

}

/*============================================
========== AJAX FORMULAR SUBMIT BTN ==========
==============================================
*/
let themeSendBtnCarouselFormular = document.querySelectorAll(".sendAjaxCarouselBtnForm");
if (themeSendBtnCarouselFormular) {
    let formNodes = Array.prototype.slice.call(themeSendBtnCarouselFormular, 0);
    formNodes.forEach(function (formNodes) {
        formNodes.addEventListener("submit", function (e) {
            e.preventDefault();
            send_xhr_carousel_data(formNodes);
        });
    });
}

/*======================================
========== AJAX DATEN SENDEN ===========
========================================
*/
function send_xhr_carousel_data(data, is_formular = true) {

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
    xhr.onload = function () {
        if (this.readyState === 4 && this.status === 200) {
            let data = JSON.parse(this.responseText);
            if (data.spinner) {
                show_ajax_spinner(data);
            }

            if (data.reset_form) {
                reset_formular_input();
            }
            switch (data.render) {
                case'carousel':
                    if (data.renderData.status) {
                        render_carousel(data.renderData);

                        carousel_autosave_events();
                    }
                    break;
                case'slider':
                    render_slider_items(data.slider, data.lang, data.carouselId, data.record,'add');
                    carousel_autosave_events();
                    return false;
            }
        }
    }
}

//document.addEventListener('DOMContentLoaded',loadPickrColor);


function reset_formular_input() {
    let inputs = document.querySelectorAll('.sendAjaxCarouselBtnForm input.form-control');
    if (inputs) {
        inputs.forEach(input => input.value = '');
    }
}

/*=================================================
========== TOGGLE SETTINGS COLLAPSE BTN  ==========
===================================================
*/
function change_collapse_btn(e) {
    e.blur();
    let siteTitle = document.getElementById("currentSideTitle");
    let parentHeader = e.parentNode.parentElement.getElementsByClassName('carousel-header');
    if (e.classList.contains("active")) {
        e.classList.remove('active');
        siteTitle.innerText = 'Settings';
        parentHeader[0].classList.add('bg-custom-gray');
        parentHeader[0].classList.remove('carousel-aktiv');
        return false;
    }

    let cardHeader = document.querySelectorAll(".carousel-header");
    remove_carousel_header();

    let colCarouselBtn = document.querySelectorAll("button.btn-collapse");
    let CollapseEvent = Array.prototype.slice.call(colCarouselBtn, 0);
    CollapseEvent.forEach(function (CollapseEvent) {
        remove_active_btn();
    });

    parentHeader[0].classList.remove('bg-custom-gray');
    parentHeader[0].classList.add('carousel-aktiv');
    e.classList.add('active');
    siteTitle.innerText = e.getAttribute('data-site');

    function remove_active_btn() {
        for (let i = 0; i < CollapseEvent.length; i++) {
            CollapseEvent[i].classList.remove('active');
            CollapseEvent[i].removeAttribute('disabled');
        }
    }

    function remove_carousel_header() {
        for (let i = 0; i < cardHeader.length; i++) {
            cardHeader[i].classList.remove('carousel-aktiv');
            cardHeader[i].classList.add('bg-custom-gray');
        }
    }
}


/*=========================================
========== WP MEDIA IMAGE UPLOAD ==========
===========================================
*/
function add_slider_img(event, rand) {

    let mediaFrame,
        btnAddImg = event,
        imgContainer = document.getElementById('imageContainer' + rand),
        inputImgId = document.getElementById('inputID' + rand),
        btnDelImg = document.getElementById('btn-delete' + rand);

    if (mediaFrame) {
        mediaFrame.open();
        return;
    }

    mediaFrame = wp.media({
        title: hupa_starter.theme_language.media_frame_select_title,
        button: {
            text: hupa_starter.theme_language.media_frame_select_btn
        },
        multiple: false
    });

    mediaFrame.on('select', function () {
        const attachment = mediaFrame.state().get('selection').first().toJSON();
        imgContainer.innerHTML = '<img class="img-fluid carousel-image" src="' + attachment.url + '" alt="' + attachment.alt + '" width="200"/>';
        imgContainer.classList.remove('d-none');
        btnAddImg.classList.add('d-none');
        btnDelImg.classList.remove('d-none');
        inputImgId.value = attachment.id;

        clearTimeout(carouselSendFormTimeout);
        carouselSendFormTimeout = setTimeout(function () {
            send_xhr_carousel_data(inputImgId.form);
        }, 1000);
    });

    mediaFrame.open();
}

function delete_slider_img(event, rand) {
    let btnAddImg = document.getElementById('btn-add' + rand),
        imgContainer = document.getElementById('imageContainer' + rand),
        inputImgId = document.getElementById('inputID' + rand),
        btnDelImg = event;

    imgContainer.innerHTML = `<img class="img-fluid carousel-image"
     src="${hupa_starter.admin_url}assets/images/hupa-logo.svg"
     alt=""
     width="200">`;
    btnAddImg.classList.remove('d-none');
    btnDelImg.classList.add('d-none');
    inputImgId.value = '';

    clearTimeout(carouselSendFormTimeout);
    carouselSendFormTimeout = setTimeout(function () {
        send_xhr_carousel_data(inputImgId.form);
    }, 1000);
}

function changeCarouselTitle(event, id) {
    let bezeichnung = event.value;
    if (!bezeichnung) {
        event.value = 'Carousel: ' + id;
        return false;
    }

    let header = document.querySelector("#carousel" + id + ' .carousel-header');
    header.innerHTML = event.value;

    let sliderItems = document.querySelectorAll("#sliderWrapper" + id + ' .carouselName');
    let sliderNodes = Array.prototype.slice.call(sliderItems, 0);
    sliderNodes.forEach(function (sliderNodes) {
        sliderNodes.innerText = event.value;
    });
}

function accordion_slider_handle(event, id){

    clearTimeout(carouselSendFormTimeout);
    carouselSendFormTimeout = setTimeout(function () {
        let handleWrapper = event.parentNode.children;
        let current =  document.getElementById("collapseSlider" + id);
        if (current.classList.contains("show")) {
           // handleWrapper[0].classList.add('d-none');
        } else {
           // handleWrapper[0].classList.remove('d-none');
        }
    }, 500);

    //console.log(handleWrapper);
}

/*=========================================
========== AJAX FORMS AUTO SAVE  ==========
===========================================
*/
function carousel_autosave_events() {
    let carouselSendFormular = document.querySelectorAll(".sendAjaxCarouselForm");
    let formNodes = Array.prototype.slice.call(carouselSendFormular, 0);
    formNodes.forEach(function (formNodes) {
        formNodes.addEventListener("keyup", form_input_ajax_handle, {passive: true});
        formNodes.addEventListener('touchstart', form_input_ajax_handle, {passive: true});
        formNodes.addEventListener('change', form_input_ajax_handle, {passive: true});

        function form_input_ajax_handle(e) {
            let spinner = Array.prototype.slice.call(ajaxSpinner, 0);
            spinner.forEach(function (spinner) {
                spinner.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...';
            });
            clearTimeout(carouselSendFormTimeout);
            carouselSendFormTimeout = setTimeout(function () {
                send_xhr_carousel_data(formNodes);
            }, 1000);
        }
    });
    changeRangeUpdate();


    let carouselSortable = document.querySelectorAll(".sliderSortable");
    if (carouselSortable) {
        let sortNodes = Array.prototype.slice.call(carouselSortable, 0);
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
                        'method': 'slider_sortable_position',
                        'element': elementArray
                    }
                    send_xhr_carousel_data(changeSelect, false);
                }
            });
        });
    }
}

function add_carousel_slider(event, id){
    const loadMethod = {
        'method': 'add_carousel_slider',
        'id': id
    }
    send_xhr_carousel_data(loadMethod, false);
}

/*==========================================
========== CHANGE ANIMATE SELECT ===========
============================================
*/


function change_animate_select(id, event) {

    let aniPreview = document.querySelectorAll(".ani_preview");
    //aniPreview
    let aniEvent = Array.prototype.slice.call(aniPreview, 0);
    aniEvent.forEach(function (aniEvent) {
        aniEvent.classList.add('hide');
    });
    let value = change_input_select_value(event);
    let animate = document.getElementById("ani_preview" + id);
    let animateOut = animate.childNodes[0];
    if (animateOut.hasAttribute("class")) {
        animateOut.removeAttribute("class");
    }
    // console.log(animate)
    animate.classList.remove('hide');
    animateOut.classList.add('animate__animated', 'animate__' + value);
}