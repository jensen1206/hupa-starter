function load_color_pickr(addId = false) {

    let clrPickrContainer;
    if(addId){
        clrPickrContainer = addId.querySelectorAll('.colorPickers');
    } else {
        clrPickrContainer = document.querySelectorAll('.colorPickers');
    }
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
                send_xhr_carousel_data(colorInput.form);
            }).on('cancel', (instance) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = instance._lastColor.toHEXA().toString(0);
                send_xhr_carousel_data(colorInput.form);
                pickr.hide();
            }).on('swatchselect', (color, instance) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = color.toHEXA().toString(0);
                send_xhr_carousel_data(colorInput.form);
            });
        });
    }
}

function render_carousel(data, handle = false) {
    let lang = data.language;
    let defImage = `<img class="img-fluid carousel-image"
     src="${hupa_starter.admin_url}assets/images/hupa-logo.svg"
     alt=""
     width="200">`;
    let selectCaptionBG = '';
    let selectSelectorBG = '';
    let html = '<div id="accordionSliderParent">';
    for (const [key, val] of Object.entries(data.record)) {
        html += `
    <div id="carousel${val.id}" class="my-4">
    <div class="card shadow">
    <h5 class="d-flex card-header carousel-header py-4 carousel-box-bg-header">${val.bezeichnung}
    <span class="ms-auto">
    <i data-bs-id="${val.id}" data-bs-method="delete_carousel_item" data-bs-type="carousel" 
    data-bs-toggle="modal" 
    data-bs-target="#ThemeBSModal" 
    class="btn-trash-icon text-danger fa fa-trash-o"></i>
    </span>
    </h5>
    <div class="card-body">
    <div class="d-flex align-items-center">
    <div class="header">
    <h6 class="card-title">Shortcode: <b class="font-blue">[carousel id=${val.id}]</b></h6>
    </div>
    </div>
    </div>
    <div class="card-footer py-3 mt-auto carousel-box-bg-footer">
    <button data-site="${lang.btn_carousel_settings}" onclick="change_collapse_btn(this);"
    class="btn-collapse btn btn-hupa btn-outline-secondary"
    data-bs-toggle="collapse" data-bs-target="#carouselSettings${val.id}">
    <i class="fa fa-gears"></i>&nbsp; ${lang.btn_carousel_settings}
    </button>
    <button data-site="${lang.btn_slider_settings}" onclick="change_collapse_btn(this);"
    class="btn-collapse btn btn-hupa btn-outline-secondary"
    data-bs-toggle="collapse" data-bs-target="#sliderSettings${val.id}">
    <i class="fa fa-exchange"></i>&nbsp; ${lang.btn_slider_settings}
    </button>
    </div>

    <div class="collapse" id="carouselSettings${val.id}" data-bs-parent="#theme-carousel-data">
    <div class="container">
    <form class="sendAjaxCarouselForm" action="#" method="post">
    <input type="hidden" name="method" value="update_carousel">
    <input type="hidden" name="id" value="${val.id}">
    <h5 class="card-title py-2"><i
    class="font-blue fa fa-gears"></i>&nbsp; ${lang.title_carousel_options}
    <small class="small text-muted">( ID: ${val.id} )</small></h5>
    
    <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option first-box align-items-center">
    <div class="col">
    <div class="col-xl-8 col-lg-8 col-12 p-2">
    <div class="mb-3">
    <label for="inputBezeichnung${val.id}"
    class="form-label">${lang.lbl_bezeichnung}</label>
    <input name="bezeichnung" type="text" class="form-control" 
    onkeyup="changeCarouselTitle(this, ${val.id});"
    value="${val.bezeichnung}"
    id="inputBezeichnung${val.id}">
    </div>
    </div>
    </div>
    <div class="col">
    <small class="small d-block p-2">${lang.help_bezeichnung}</small>
    </div>
    </div>
    
    <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option first-box align-items-center">
    <div class="col">
    <div class="col-xl-6 col-lg-8 col-12 p-2">
    <div class="mb-3">
    <label for="selectCarouselImageSize${val.id}"
    class="form-label">Bildgröße auswählen</label>
    <select id="selectCarouselImageSize${val.id}" class="form-select"
    name="carousel_image_size">
    <option value="medium" ${val.carousel_image_size === 'medium' ? ' selected' : ''}>medium</option>
    <option value="large" ${val.carousel_image_size === 'large' ? ' selected' : ''}>large</option>
    <option value="full" ${val.carousel_image_size === 'full' ? ' selected' : ''}>full</option>
    </select>
    </div>
    </div>
    </div>
    <div class="col">
    </div>
    </div>
    
    
    <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option align-items-center">
    <div class="col">
    <div class="col-xl-6 col-lg-8 col-12 p-2">
    <div class="form-check form-switch py-3">
    <input onclick="this.blur()" class="form-check-input" name="carousel_lazy_load" type="checkbox"
    id="checkLazyLoad${val.id}" ${val.carousel_lazy_load ? 'checked' : ''}>
    <label class="form-check-label"
    for="checkLazyLoad${val.id}">lazy load aktiv</label>
    </div>
    </div>
    </div>
    <div class="col">
    </div>
    </div>
    
    
    <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option first-box align-items-center">
    <div class="col">
    <div class="col-xl-8 col-lg-8 col-12 p-2">
    <div class="mb-3">
    <label for="inputContainerHeight${val.id}"
    class="form-label">${lang.lbl_container_height}</label>
    <input name="container_height" type="text" class="form-control" 
    value="${val.container_height}"
    id="inputContainerHeight${val.id}">
    </div>
    </div>
    </div>
    <div class="col">
    <small class="small d-block p-2">${lang.help_container_height}</small>
    </div>
    </div>
    
    <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option first-box align-items-center">
    <div class="col">
    <div class="col-xl-6 col-lg-8 col-12 p-2">
    <div class="mb-3">
    <label for="selectCarouselAnimation${val.id}"
    class="form-label">${lang.animation}</label>
    <select id="selectCarouselAnimation${val.id}" class="form-select"
    name="data_animate">
    <option value="1" ${val.data_animate === '1' ? ' selected' : ''}>slide</option>
    <option value="2" ${val.data_animate === '2' ? ' selected' : ''}>fade</option>
    </select>
    </div>
    </div>
    </div>
    <div class="col">
    <small class="small d-block p-2">${lang.animation_help}</small>
    </div>
    </div>
    
    <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option first-box align-items-center">
    <div class="col">
    <div class="col-xl-6 col-lg-8 col-12 p-2">
    <div class="mb-3">
    <label for="selectCaptionBg${val.id}"
    class="form-label">${lang.lbl_caption_bg}</label>
    <select id="selectCaptionBg${val.id}" class="form-select"
    name="caption_bg">`;
        for (const [keyCaptionBG, valCaptionBG] of Object.entries(data.select_bg)) {
            val.caption_bg === keyCaptionBG ? selectCaptionBG = 'selected' : selectCaptionBG = '';
            html += `<option value="${keyCaptionBG}" ${selectCaptionBG}>${valCaptionBG} </option>`;
        }

        html += `</select>
    </div>
    </div>
    </div>
    <div class="col">
    <small class="small d-block p-2">${lang.help_caption_bg}</small>
    </div>
    </div>
        
    <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option first-box align-items-center">
    <div class="col">
    <div class="col-xl-6 col-lg-8 col-12 p-2">
    <div class="mb-3">
    <label for="selectSelectorBg${val.id}"
    class="form-label">${lang.lbl_selector_bg}</label>
    <select id="selectSelectorBg${val.id}" class="form-select"
    name="select_bg">`;
        for (const [keySelectorBG, valSelectorBG] of Object.entries(data.select_bg)) {

            val.select_bg === keySelectorBG ? selectSelectorBG = 'selected' : selectSelectorBG = '';
            html += `<option value="${keySelectorBG}" ${selectSelectorBG}>${valSelectorBG} </option>`;
        }
        html += `</select>
    </div>
    </div>
    </div>
    <div class="col">
    <small class="small d-block p-2">${lang.help_selector_bg}</small>
    </div>
    </div>
    <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option align-items-center">
    <div class="col">
    <div class="col-xl-6 col-lg-8 col-12 p-2">
    <div class="form-check form-switch py-3">
    <input class="form-check-input" name="margin_aktiv" type="checkbox"
    id="checkTopMargin${val.id}" ${val.margin_aktiv ? 'checked' : ''}>
    <label class="form-check-label"
    for="checkTopMargin${val.id}">${lang.lbl_margin_aktiv}</label>
    </div>
    </div>
    </div>
    <div class="col">
    <small class="small d-block p-2">
    ${lang.help_margin_aktiv}
    </small>
    </div>
    </div>
        
    <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option align-items-center">
    <div class="col">
    <div class="col-xl-6 col-lg-8 col-12 p-2">
    <div class="form-check form-switch py-3">
    <input class="form-check-input" name="full_width" type="checkbox"
    id="checkFullWidth${val.id}" ${val.full_width ? 'checked' : ''}>
    <label class="form-check-label"
    for="checkFullWidth${val.id}">${lang.lbl_full_width}</label>
    </div>
    </div>
    </div>
    <div class="col">
    <small class="small d-block p-2">
    ${lang.help_full_width}
    </small>
    </div>
    </div>
       
    <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option align-items-center">
    <div class="col">
    <div class="col-xl-6 col-lg-8 col-12 p-2">
    <div class="form-check form-switch py-3">
    <input class="form-check-input" name="controls" type="checkbox"
    id="checkControls${val.id}" ${val.controls ? 'checked' : ''}>
    <label class="form-check-label"
    for="checkControls${val.id}">${lang.lbl_controls}</label>
    </div>
    </div>
    </div>
    <div class="col">
    <small class="small d-block p-2">
    ${lang.help_controls}
    </small>
    </div>
    </div>
    <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option align-items-center">
    <div class="col">
    <div class="col-xl-6 col-lg-8 col-12 p-2">
    <div class="form-check form-switch py-3">
    <input class="form-check-input" name="indicator" type="checkbox"
    id="checkIndicator${val.id}" ${val.indicator ? 'checked' : ''}>
    <label class="form-check-label"
    for="checkIndicator${val.id}">${lang.lbl_indicator}</label>
    </div>
    </div>
    </div>
    <div class="col">
    <small class="small d-block p-2">
    ${lang.help_indicator}
    </small>
    </div>
    </div>
    <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option align-items-center">
    <div class="col">
    <div class="col-xl-6 col-lg-8 col-12 p-2">
    <div class="form-check form-switch py-3">
    <input class="form-check-input" name="data_autoplay" type="checkbox"
    id="checkAutoplay${val.id}" ${val.data_autoplay ? 'checked' : ''}>
    <label class="form-check-label"
    for="checkAutoplay${val.id}">${lang.lbl_autoplay}</label>
    </div>
    </div>
    </div>
    <div class="col">
    <small class="small d-block p-2">
    ${lang.help_autoplay}
    </small>
    </div>
    </div>
    </form>
    </div>
     </div>
    <div class="collapse" id="sliderSettings${val.id}" data-bs-parent="#theme-carousel-data"> 
        <div class="add-wrapper">
        <button onclick="add_carousel_slider(this, '${val.id}')" class="btn btn-blue btn-sm ms-1 my-2">
        <i class="fa fa-plus "></i>&nbsp; ${lang.btn_add_slider} 
        </button>
        
        </div> `;
        html += render_slider_items(val.slider, lang, val.id, data);
        html += ` </div>
    </div>
    </div>`;
    }
    html += '</div>';
    let tempId = document.getElementById("theme-carousel-data");
    tempId.insertAdjacentHTML('afterbegin', html);

    let addBtnButton = document.querySelectorAll(".btnAddButton");
    if (addBtnButton) {
        let btnAddNode = Array.prototype.slice.call(addBtnButton, 0);
        btnAddNode.forEach(function (btnAddNode) {
            btnAddNode.addEventListener("click", function (e) {
                let nextBox = btnAddNode.nextElementSibling.lastElementChild;
                load_color_pickr(nextBox);
            });
        });
    }
    load_color_pickr();
}


function render_slider_items(slider, lang, id, record, method = '',) {

    let firstSelector = '';
    let secondSelector = '';
    let firstFontFamilySelect = '';
    let secondFontFamilySelect = '';
    let firstFontStyleSelect = '';
    let secondFontStyleSelect = '';
    let firstAnimationSelect = '';
    let firstAniValue = '';
    let firstAniClass = '';
    let secondAnimationSelect = '';
    let secondAniValue = '';
    let secondAniClass = '';

    let showDelBtn = '';
    let showAddBtn = '';
    let defImage = `<img class="img-fluid carousel-image"
     src="${hupa_starter.admin_url}assets/images/hupa-logo.svg"
     alt=""
     width="200">`;
    let html = '';
    if (method !== 'add') {
        html += `<div class="accordion sliderSortable" >`;
    }
    let i = 0;
    for (const [key, val] of Object.entries(slider)) {

        let random = createRandomCode(5);

        let cid = id + '_' + val.id;
        html += `
     <div id="sliderWrapper${cid}" class="sliderWrapper wrapper${id} sort${cid}">
     <div class="accordion-item shadow">
     <form id="sendCarousel${random}" class="sendAjaxCarouselForm" action="#" method="post">
     <input type="hidden" name="method" value="update_slider">
     <input type="hidden" name="id" value="${cid}">
     <h2 class="accordion-header">
     <span class="sliderHandle">
     <i class="sortableArrow fa fa-arrows"></i> `;

        html += `<i data-bs-id="${cid}" data-bs-method="delete_carousel_item" data-bs-type="slider" 
        data-bs-toggle="modal" 
        data-bs-target="#ThemeBSModal"  
        class="text-danger fa fa-trash">
      </i>`;
        html += '<i class ="fa fa-trash hide cursor-default"></i>';

        html += `</span>
     <button onclick="accordion_slider_handle(this, '${cid}');" class="accordion-button collapsed border bg-custom-gray"
     type="button"
     data-bs-toggle="collapse"
     data-bs-target="#collapseSlider${cid}" aria-expanded="false"
     aria-controls="collapseSlider${cid}">
     <b>Carousel:</b>&nbsp;<span class="carouselName">${val.carousel}</span>&nbsp; -> &nbsp; <b>ID:</b>&nbsp; ${val.id}
     </button>
     
     </h2>
     <div id="collapseSlider${cid}" class="accordion-collapse collapse "
     aria-labelledby="collapseSlider${cid}"
     data-bs-parent="#accordionSliderParent">
     <div class="border rounded mt-1 shadow-sm p-4 bg-custom-gray">
     <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option first-box align-items-center">
     <div class="col">
     <div class="col-xl-6 col-lg-8 col-12 p-2">
     <div class="mb-3">
     <div id="imageContainer${random}">`;

        if (val.img) {
            html += `${val.img}`;
            showAddBtn = 'd-none';
            showDelBtn = '';
        } else {
            html += `${defImage}`;
            showAddBtn = '';
            showDelBtn = 'd-none';
        }

        html += `
    </div><!--imageContainer-->
    <button id="btn-add${random}" type="button" onclick="add_slider_img(this, '${random}');" class="${showAddBtn} btn btn-outline-secondary btn-sm d-block mx-auto mt-4">
     <i class="fa fa-image"></i>
     &nbsp; ${lang.btn_select_img}
     </button>
     
     <button id="btn-delete${random}" type="button" onclick="delete_slider_img(this, '${random}');" class="${showDelBtn} btn btn-outline-danger btn-sm d-block mx-auto mt-4">
     <i class="fa fa-trash"></i>
     &nbsp; ${lang.btn_delete_img}
     </button>
     <input id="inputID${random}" type="hidden" name="img_id" value="${val.img_id}">
     
     </div>
     </div>
     </div>
     <div class="col">
     <small class="small d-block p-2">${lang.btn_select_img_help}</small>
     </div>
     </div>

     <div class="row row-cols-1 row-cols-xl-2 py-2 settings-box option align-items-center">
        <div class="col flex-fill p-2">

        <label class="input-color-label d-block mb-3 ms-2">
         <h5><b>Button</b> hinzufügen</h5>
        </label>`;

        html += `<button onclick="add_caption_button(this, '${id}', '${createRandomInteger(5)}', '${random}')" type="button" class="btnAddButton btn btn-blue ms-2 btn-sm">
          <i class="fa fa-link"></i>&nbsp; Button hinzufügen
         </button>`;

        html += `<div id="captionButton${random}" class="ms-2">`;

        for (const [slideBtnKey, slideBtnVal] of Object.entries(val.slide_button)) {
            let btnRandom = createRandomInteger(5);
            html += get_caption_button(id, slideBtnVal, record.selectPages, btnRandom, random);
        }
        html += `</div></div><!--form-wrapper-ende-->
        </div>

     <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option align-items-center">
     <div class="col">
     <div class="col-xl-6 col-lg-8 col-12 p-2">
     <div class="form-check form-switch py-3">
     <input class="form-check-input" name="aktiv"
     type="checkbox"
     id="checkAktiv${cid}" ${val.aktiv ? 'checked' : ''}>
     <label class="form-check-label"
     for="checkAktiv${cid}">${lang.lbl_active}</label>
     </div>
     </div>
     </div>
     <div class="col">
     <small class="small d-block p-2">${lang.help_active}</small>
     </div>
     </div>
     <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option align-items-center">
     <div class="col">
     <div class="col-xl-6 col-lg-8 col-12 p-2">
     <div class="mb-3">
     <label for="inputInterval${cid}"
     class="form-label">${lang.lbl_interval}
     <small class="small">(msec)</small>
     </label>
     <input type="number" value="${val.data_interval}" class="form-control"
     name="data_interval" id="inputInterval${cid}">
     </div>
     </div>
     </div>
     <div class="col">
     <small class="small d-block p-2">${lang.help_interval}
     </small>
     </div>
     </div>
     <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option align-items-center">
     <div class="col">
     <div class="col-12 p-2">
     <div class="mb-3">
     <label for="inputAlt${cid}"
     class="form-label">${lang.lbl_alt}
     </label>
     <input type="text" class="form-control" value="${val.data_alt}" name="data_alt"
     id="inputAlt${cid}">
     </div>
     </div>
     </div>
     <div class="col">
     <small class="small d-block p-2">${lang.help_alt}</small>
     </div>
     </div>

     <div class="row row-cols-1 row-cols-lg-2 py-2 settings-box option align-items-center">
     <div class="col">
     <div class="col-xl-6 col-lg-8 col-12 p-2">
     <div class="form-check form-switch py-3">
     <input class="form-check-input" name="caption_aktiv"
     type="checkbox"
     id="checkCaptionAktiv${cid}" ${val.caption_aktiv ? 'checked' : ''}>
     <label class="form-check-label"
     for="checkCaptionAktiv${cid}">${lang.lbl_caption}</label>
     </div>
     </div>
     </div>
     <div class="col">
     <small class="small d-block p-2">${lang.help_caption}></small>
     </div>
     </div>
     <div class="row row-cols-1 row-cols-lg-2 py-4 settings-box option align-items-center">
     <div class="col">
     <div class="col-xl-6 col-lg-8 col-12 p-2">
     
     <div class="color-select-wrapper align-items-center d-flex mb-2">
      <div data-color="${val.font_color}" class="colorPickers">
       <input id="InputButtonColor${cid}" type="hidden" value="${val.font_color}" name="font_color">
       </div>
       <h6 class="ms-2 mt-1"> <b>Schrift und Symbol</b><samll class="small d-block"> Farbe</samll></h6> 
     </div>
     </div>
     </div>
     <div class="col">
     <small class="small d-block p-2">${lang.help_color}</small>
     </div>
     </div>
     <div class="row settings-box option first-box  align-items-center">
     <h5 class=" px-3 py-3 mb-0 shadow border-tb bg-accordion-gray"><i
     class="font-blue fa fa-header"></i>&nbsp;
     ${lang.h5_headline}
     </h5>
     <div class="col-xl-6 col-lg-12 px-2">
     <div class="my-2 px-2">
     <label for="firstCaptionText${cid}" class="form-label pt-3">
     ${lang.lbl_first_caption}
     </label>
     <textarea name="first_caption" class="form-control"
     id="firstCaptionText${cid}" rows="2">${val.first_caption}</textarea>
     </div>
     </div>
     <div class="d-lg-flex d-md-block p-2">
     <div class="col-xl-2 col-lg-4 col-md-8 col-12 px-2">
     <label for="selectFirstSelector${cid}"
     class="form-label">${lang.lbl_selector}</label>
     <select id="selectFirstSelector${cid}"
     class="form-select"
     name="first_selector">`;
        for (const [keySelector, valSelector] of Object.entries(record.selector)) {
            val.first_selector === keySelector ? firstSelector = 'selected' : firstSelector = '';
            html += `<option value="${keySelector}" ${firstSelector}>${valSelector} </option>`;
        }

        html += `</select>
     </div>
     </div>
     <div class="col-xl-6 col-lg-12 col-12 p-2">
     <div class="my-2 px-2">
     <label for="firstCss${cid}" class="form-label">
     ${lang.lbl_extra_css}
     </label>
     <input type="text" name="first_css" class="form-control" value="${val.first_css}"
     id="firstCss${cid}">
     </div>
     </div>
     <div class="row row-cols-1 row-cols-lg-2 p-2">
     <div class="d-lg-flex col-md-block">
     <div class="col-xl-6 col-lg-12 col-12 p-2">
     <label for="selectFirstFont${cid}"
     class="form-label">${lang.lbl_font_family}</label>
     <select id="selectFirstFont${cid}" class="form-select"
     onchange="font_family_change(this, 'selectFirstStyle${cid}');"
     name="first_font"> `;
        for (const [firstFamKey, firstFamVal] of Object.entries(record.familySelect)) {
            val.first_font == firstFamVal.family ? firstFontFamilySelect = 'selected' : firstFontFamilySelect = '';
            html += `<option value="${firstFamVal.family}" ${firstFontFamilySelect}>${firstFamVal.family} </option>`;
        }

        html += `</select>
     </div>
     <div class="col-xl-6 col-lg-12 col-12 p-2">
     <label for="selectFirstStyle${cid}"
     class="form-label">${lang.lbl_font_style}</label>
     <select id="selectFirstStyle${cid}" class="form-select"
     name="first_style">`;

        for (const [firstStyleKey, firstStyleVal] of Object.entries(val.first_style_select)) {
            val.first_style == firstStyleKey ? firstFontStyleSelect = 'selected' : firstFontStyleSelect = '';
            html += `<option value="${firstStyleKey}" ${firstFontStyleSelect}>${firstStyleVal} </option>`;
        }

        let aniFirstRandom = createRandomCode(5);
        html += `</select>
     </div>
     </div>
     </div>
     <hr>
     <div class="col-lg-6 p-3">
     <div id="first-font-size${cid}" class="p-2">
     <label for="RangeFontFirstSize${cid}"
     class="form-label"><b>${lang.lbl_font_size}
     <span class="show-range-value">${val.first_size}</span>
     (Px)</b></label>
     <input data-container="first-font-size${cid}" type="range"
     name="first_size" class="form-range sizeRange"
     min="10"
     max="100" step="1"
     value="${val.first_size}"
     id="RangeFontFirstSize${cid}">
     </div>
     <div id="first-font-height${cid}" class="p-2">
     <label for="RangeFontFirstHeight${cid}"
     class="form-label"><b>${lang.lbl_font_height}
     <span class="show-range-value">${val.first_height}</span></b>
     </label>
     <input data-container="first-font-height${cid}" type="range"
     class="form-range sizeRange" name="first_height"
     min="0"
     max="5"
     value="${val.first_height}"
     step="0.1" id="RangeFontFirstHeight${cid}">
     </div>
     <div class="col-xl-6 col-lg-12 col-12 p-2">
     <label for="selectFirstAni${cid}"
     class="form-label">${lang.lbl_ani}</label>
     <select id="selectFirstAni${cid}"
     onchange="change_animate_select('${aniFirstRandom}', this)"
     class="form-select"
     name="first_ani">
     <option value="">${lang.lbl_select}
     ...
     </option>`;

        for (const [firstAniKey, firstAniVal] of Object.entries(record.animate)) {

            if (firstAniVal.divider) {
                firstAniClass = " disabled class=\"SelectSeparator\"";
                firstAniValue = firstAniVal.value;
            } else {
                firstAniClass = "";
                firstAniValue = firstAniVal.animate;
            }
            val.first_ani == firstAniVal.animate ? firstAnimationSelect = 'selected' : firstAnimationSelect = '';
            html += `<option value="${firstAniValue}" ${firstAniClass} ${firstAnimationSelect}>${firstAniVal.animate} </option>`;
        }

        html += `</select>
     <span id="ani_preview${aniFirstRandom}"
     class="hide ani_preview ps-2 pt-3 fs-6 d-inline-block text-danger"><b>animate</b></span>
     </div>
     </div>
     </div>
     <div class="row settings-box option first-box  align-items-center">
     <h5 class="px-3 py-3 shadow bg-accordion-gray border-tb"><i
     class="font-blue fa fa-text-width"></i>&nbsp;
     ${lang.h5_baseline}
     </h5>
     <div class="col-xl-6 col-lg-12 px-2">
     <div class="my-2 px-2">
     <label for="secondCaptionText${cid}" class="form-label pt-3">
     ${lang.lbl_baseline_txt}
     </label>
     <textarea name="second_caption" class="form-control"
     id="secondCaptionText${cid}" rows="2">${val.second_caption}</textarea>
     </div>
     </div>
     <div class="d-lg-flex d-md-block">
     <div class="col-xl-6 col-lg-12 col-12 px-2 mb-2">
     <label for="secondCss${cid}" class="form-label pt-3">
     ${lang.lbl_extra_css}
     </label>
     <input type="text" name="second_css" value="${val.second_css}"
     class="form-control" id="secondCss${cid}">
     </div>
     </div>

     <div class="row row-cols-1 row-cols-lg-2 p-2">
     <div class="d-lg-flex col-md-block">
     <div class="col-xl-6 col-lg-12 col-12 p-2">
     <label for="selectSecondFont${cid}"
     class="form-label">${lang.lbl_font_family}</label>
     <select id="selectSecondFont${cid}" class="form-select"
     onchange="font_family_change(this, 'selectSecondStyle${cid}');"
     name="second_font">`;

        for (const [secondFamKey, secondFamVal] of Object.entries(record.familySelect)) {
            val.second_font == secondFamVal.family ? secondFontFamilySelect = 'selected' : secondFontFamilySelect = '';
            html += `<option value="${secondFamVal.family}" ${secondFontFamilySelect}>${secondFamVal.family} </option>`;
        }

        html += `</select>
     </div>
     <div class="col-xl-6 col-lg-12 col-12 p-2">
     <label for="selectSecondStyle${cid}"
     class="form-label">${lang.lbl_font_style}</label>
     <select id="selectSecondStyle${cid}" class="form-select"
     name="second_style">`;

        for (const [secondStyleKey, secondStyleVal] of Object.entries(val.second_style_select)) {
            val.second_style == secondStyleKey ? secondFontStyleSelect = 'selected' : secondFontStyleSelect = '';
            html += `<option value="${secondStyleKey}" ${secondFontStyleSelect}>${secondStyleVal} </option>`;
        }

        let aniSecondRandom = createRandomCode(5);
        html += `</select>
     </div>
     </div>
     </div>
     <hr>
     <div class="col-lg-6 p-3">
     <div id="second-font-size${cid}" class="p-2">
     <label for="RangeFontSecondSize${cid}"
     class="form-label"><b>${lang.lbl_font_size}
     <span class="show-range-value">${val.second_size}</span>
     (Px)</b></label>
     <input data-container="second-font-size${cid}" type="range"
     name="second_size" class="form-range sizeRange"
     min="10"
     max="100" step="1"
     value="${val.second_size}"
     id="RangeFontSecondSize${cid}">
     </div>
     <div id="second-font-height${cid}" class="p-2">
     <label for="RangeFontSecondHeight${cid}"
     class="form-label"><b>${lang.lbl_font_height}
     <span class="show-range-value">${val.second_height}</span></b>
     </label>
     <input data-container="second-font-height${cid}" type="range"
     class="form-range sizeRange" name="second_height"
     min="0"
     max="5"
     value="${val.second_height}"
     step="0.1" id="RangeFontSecondHeight${cid}">
     </div>
     <div class="col-xl-6 col-lg-12 col-12 p-2">
     <label for="selectSecondAni${cid}"
     class="form-label">${lang.lbl_ani}</label>
     <select id="selectSecondAni${cid}"
     class="form-select animateSelect"
     onchange="change_animate_select('${aniSecondRandom}', this)"
     name="second_ani">
     <option value="">${lang.lbl_select}
     ...
     </option>`;

        for (const [secondAniKey, secondAniVal] of Object.entries(record.animate)) {
            if (secondAniVal.divider) {
                secondAniClass = " disabled class=\"SelectSeparator\"";
                secondAniValue = secondAniVal.value;
            } else {
                secondAniClass = "";
                secondAniValue = secondAniVal.animate;
            }
            val.second_ani == secondAniVal.animate ? secondAnimationSelect = 'selected' : secondAnimationSelect = '';
            html += `<option value="${secondAniValue}" ${secondAniClass} ${secondAnimationSelect}>${secondAniVal.animate} </option>`;
        }

        html += `</select>
     <span id="ani_preview${aniSecondRandom}"
     class="hide ani_preview ps-2 pt-3 fs-6 d-inline-block text-danger"><b>animate</b></span>
     </div>
     </div>
     </div>
     </div>
     </div>
     </form>
     </div><!--item-->
     </div>`;
        i++;
    }
    if (method !== 'add') {
        html += `</div>`;
    }

    if (method === 'add') {
        let carouselWrapper = document.querySelector("#sliderSettings" + id + ' .sliderSortable');
        carouselWrapper.insertAdjacentHTML('afterbegin', html);
        return false;
    }
    return html;
}
function get_caption_button(id, data, select, random, formRand) {
    return get_button_template(id, data, select, random, formRand);
}

function add_caption_button(e, id, btnRandom, formRand) {
    btnRandom = createRandomInteger(5);
    let captionButton = e.childNodes[0].parentNode.nextElementSibling;

    let html = get_button_template(id, '', '', btnRandom, formRand);
    captionButton.insertAdjacentHTML('beforeend', html);
    get_page_and_posts_select(btnRandom);

}

function get_button_template(id, data = false, select = false, btnRandom = false, formRand) {

    let html = `
    <div id="btnWrapper${btnRandom}" class="col-12 p-2">
    <hr>
    <h5><i class="fa fa-link"></i> <span class="font-blue">Button</span></h5>
    <hr>
    <h6><i class="font-blue fa fa-caret-down"></i>&nbsp; <b>Button</b> Einstellungen</h6>
       <!-- HOVER -->
   <div class="d-xl-flex d-block flex-wrap">    
   
    <button onclick="toggle_hover_btn(this)" data-bs-toggle="collapse" 
     data-bs-target="#collapseBTNSettings${btnRandom}" type="button" 
     class="showColorPicker btn btn-outline-success btn-sm me-1 mt-2 d-md-block">
    <i class="fa fa-caret-right"></i>&nbsp; Button Einstellungen
    </button>
    
   <button onclick="toggle_hover_btn(this)" data-bs-toggle="collapse" 
   data-bs-target="#collapseHoverSettings${btnRandom}" type="button" 
   class="showColorPicker btn btn-blue-outline btn-sm me-1 mt-2">
    <i class="fa fa-caret-right"></i>&nbsp; Button Farbeinstellungen
    </button>

    <button onclick="delete_slider_button(this);" type="button" class="btn btn-outline-danger ms-auto me-1 mx-1 btn-sm mt-2">
    <i class="fa fa-trash-o"></i> Button löschen</button>
    </div>
    <div id="btnParrent${btnRandom}">
    <div class="collapse mb-3" data-bs-parent="#btnParrent${btnRandom}" id="collapseHoverSettings${btnRandom}">
    <hr>
    <h6 class="font-blue"><i class="fa fa-paint-brush"></i> <b>Button</b> Color</h6>
    
    <div class="color-select-wrapper d-flex mb-2">
      <div data-color="${data && data.button_color ? data.button_color : '#ffffff'}" data-id="${formRand}" class="colorPickers">
       <input id="InputButtonColor${btnRandom}" type="hidden" value="${data && data.button_color ? data.button_color : '#ffffff'}" name="button_color_${btnRandom}">
       </div>
       <h6 class="ms-2 mt-1"> <b>Schrift </b>Farbe</h6> 
     </div>
    
    <!--Border Color-->
    <div class="color-select-wrapper d-flex mb-2">
      <div data-color="${data && data.border_color ? data.border_color : '#ffffff'}"  data-id="${formRand}" class="colorPickers">
       <input id="InputBorderColor${btnRandom}" type="hidden" value="${data && data.border_color ? data.border_color : '#ffffff'}" name="border_color_${btnRandom}">
       </div>
       <h6 class="ms-2 mt-1"> <b>Border </b>Farbe</h6> 
     </div> 
    
    <!--Background-Color-->
     <div class="color-select-wrapper d-flex mb-2">
       <div data-color="${data && data.bg_color ? data.bg_color : '#ffffff00'}"  data-id="${formRand}" class="colorPickers">
         <input id="InputBGButtonColor${btnRandom}" type="hidden" value="${data && data.bg_color ? data.bg_color : '#ffffff00'}" name="button_bg_color_${btnRandom}">
         </div>
         <h6 class="ms-2 mt-1"> <b>Hintergrund </b>Farbe</h6> 
     </div>
    <hr>
    <h6 class="font-blue"><i class="fa fa-paint-brush"></i> <b>Hover</b> Color</h6>
    <div class="color-select-wrapper d-flex mb-2">
       <div data-color="${data && data.hover_color ? data.hover_color : '#3c434a'}"  data-id="${formRand}" class="colorPickers">
         <input id="inputColorHover${btnRandom}" type="hidden" value="${data && data.hover_color ? data.hover_color : '#3c434a'}" name="color_hover_${btnRandom}">
         </div>
         <h6 class="ms-2 mt-1"> <b>Font Color </b>Hover</h6> 
     </div>
    
     <div class="color-select-wrapper d-flex mb-2">
       <div data-color="${data && data.hover_border ? data.hover_border : '#ffffff'}"  data-id="${formRand}" class="colorPickers">
         <input id="inputBorderHover${btnRandom}" type="hidden" value="${data && data.hover_border ? data.hover_border : '#ffffff'}" name="border_hover_${btnRandom}">
         </div>
         <h6 class="ms-2 mt-1"><b>Border </b>Hover</h6>
     </div>
     
      <div class="color-select-wrapper d-flex mb-2">
       <div data-color="${data && data.bg_hover ? data.bg_hover : '#ffffff'}"  data-id="${formRand}" class="colorPickers">
         <input id="inputBGHover${btnRandom}" type="hidden" value="${data && data.bg_hover ? data.bg_hover : '#ffffff'}" name="bg_hover_${btnRandom}">
         </div>
         <h6 class="ms-2 mt-1"><b>Background </b>Hover</h6> 
     </div>
      
    </div><!--collapse-->

     <div class="collapse" data-bs-parent="#btnParrent${btnRandom}" id="collapseBTNSettings${btnRandom}">
     <hr>
     <h6 class="mt-2"> <b>Button </b>| Icon | Beschriftung | Url</h6> 
    <!--BUTTON TEXT -->
    <div class="mb-0">
    <label for="inputBtnLink${btnRandom}"
    class="form-label">Button Beschriftung
    </label>
    <input type="text" value="${data ? data.btn_text : 'Button Text'}" class="form-control"
    name="btn_text_${btnRandom}"  id="inputBtnLink${btnRandom}">
    <div class="d-flex align-items-center mb-4">
    
    <button type="button" data-bs-toggle="modal" data-bs-id="${btnRandom}" 
    data-bs-target="#dialog-add-icon" data-bs-type="slider" 
    class="btn btn-sm btn-add-slider-icon mt-2 btnSelectIcon${btnRandom} ${data && data.icon_value ? 'd-none' : ''}">Button Icon</button>
    
    <button onclick="delete_slider_icon('${btnRandom}', this);" 
    type="button" class="btn btn-sm btn-outline-danger remove-slider-icon mt-2 btnSelectIcon${btnRandom} ${data && data.icon_value ? '' : 'd-none'}">
    <i class="fa fa-trash-o"></i>&nbsp; Icon löschen
    </button>
        <span id="btn_icon${btnRandom}" class="slider-icon-wrapper d-inline-block mt-2 ms-2">${data && data.icon ? data.icon : ''}</span>
    <input id="inputIcon${btnRandom}" value="${data && data.icon_value ? data.icon_value : ''}" type="hidden" name="btn_icon_${btnRandom}">
    </div>
    </div>
    <!--Select PAGES POSTS -->
     <div class="mb-3">
    <label for="selectUrl${btnRandom}"
    class="form-label">Seite / Beitrag auswählen</label>
    <select onchange="change_select_btn_url('${btnRandom}', this);" style="max-width: 100%" id="selectUrl${btnRandom}" 
    class="form-select" name="select_btn_url_${btnRandom}">`;
    if (select) {
        let x = 1;
        let sel = '';
        let option = '<option value="">auswählen ...</option>';
        for (const [selectKey, selectVal] of Object.entries(select)) {
            if (x === 1 && selectVal.type == 'page') {
                option += '<option value="-" disabled="" class="SelectSeparator">---------Pages-------- </option>';
            }
            if (selectVal.first && selectVal.type == 'post') {
                option += '<option value="-" disabled="" class="SelectSeparator">---------Posts--------- </option>';
            }
            if (data && data.btn_link === `${selectVal.type}#${selectVal.id}`) {
                sel = 'selected';
            } else {
                sel = '';
            }
            option += `<option value="${selectVal.type}#${selectVal.id}" ${sel}>${selectVal.name}</option>`;
            x++;
        }
        html += option;
    }
    html += `</select>
    </div>
    
    <!--Individuelle URL-->
    <div class="mb-3">
    <label for="inputBtnURL${btnRandom}"
    class="form-label">URL:
    </label>
    <input onclick="btn_link_change(this);" type="url" 
    value="${data && data.if_url ? data.btn_link : ''}" class="form-control"
    name="url_${btnRandom}" id="inputBtnURL${btnRandom}" ${data && data.if_url ? '' : 'disabled'}>
    </div>
   <div class="form-check form-switch mt-3 pb-3">
    <input onclick="this.blur();" class="form-check-input " name="check_target_${btnRandom}"
    type="checkbox"
    id="checkUrlTarget${btnRandom}" ${data && data.btn_target ? 'checked' : ''}>
    <label class="form-check-label"
    for="checkUrlTarget${btnRandom}">Link im neuen Fenster öffnen</label>
    </div>
    </div>
    </div>
    </div>
    `;
    return html;
}

function change_select_btn_url(id, e) {

    e.blur();
    let inputUrl = document.getElementById('inputBtnURL' + id);
    if (e.value) {
        inputUrl.setAttribute('disabled', 'disabled');
        inputUrl.classList.remove('is-invalid');
    } else {
        inputUrl.removeAttribute('disabled');
        inputUrl.classList.add('is-invalid');
    }
}

function btn_link_change(e) {
    e.addEventListener("keyup", function (event) {
        let res = e.value.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
        if (e.value && res) {
            e.classList.remove('is-invalid');
        } else {
            e.classList.add('is-invalid');
        }
    });
}

let i = 1;

function delete_slider_button(e) {
    let form = e.childNodes[0].parentNode.form;
    let btnWrapper = e.parentNode.parentNode;
    btnWrapper.remove();
    send_xhr_carousel_data(form);
}

function change_bg_color(e, id) {
    e.blur();
    let btnBgColor = document.getElementById(id);
    if (e.checked) {
        btnBgColor.classList.add('d-none');
    } else {
        btnBgColor.classList.remove('d-none');
    }
}

let iconModal = document.getElementById('dialog-add-icon');
if (iconModal) {
    iconModal.addEventListener('show.bs.modal', function (event) {
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
        formData.append('formId', formId);
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
                        html += `<div onclick="set_select_slide_icon(this, '${icons.code}', '${icons.icon}', '${data.formId}');"
                              data-bs-dismiss="modal"  class="info-icon-item" title="${icons.code} | ${icons.title}">`;
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


function set_select_slide_icon(e, iconCode, icon, formId) {
    let iconContainer = document.getElementById('btn_icon' + formId);
    let iconInput = document.getElementById('inputIcon' + formId);
    iconInput.value = icon + '#' + iconCode;
    iconContainer.innerHTML = `<i  class="${icon}"></i>`;
    let iconButton = document.querySelectorAll('.btnSelectIcon' + formId);
    let formNodes = Array.prototype.slice.call(iconButton, 0);
    formNodes.forEach(function (formNodes) {

        formNodes.classList.toggle('d-none');
    });
    send_xhr_carousel_data(iconInput.form);
}

function delete_slider_icon(id, e) {
    let iconContainer = document.getElementById('btn_icon' + id);
    document.getElementById('inputIcon' + id).value = '';
    iconContainer.innerHTML = '';
    let iconButton = document.querySelectorAll('.btnSelectIcon' + id);
    let formNodes = Array.prototype.slice.call(iconButton, 0);
    formNodes.forEach(function (formNodes) {
        formNodes.classList.toggle('d-none');
    });

    let form = e.childNodes[0].parentNode.form;
    send_xhr_carousel_data(form);
}

function get_page_and_posts_select(formId) {
    let xhr = new XMLHttpRequest();
    let formData = new FormData();
    xhr.open('POST', theme_ajax_obj.ajax_url, true);
    formData.append('_ajax_nonce', theme_ajax_obj.nonce);
    formData.append('action', 'HupaStarterHandle');
    formData.append('method', 'get_page_site_select');
    formData.append('type', 'page_site');
    formData.append('formId', formId);
    xhr.send(formData);
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let data = JSON.parse(this.responseText);
            if (data.status) {
                let i = 1;
                let html = '<option value="">auswählen ...</option>';
                for (const [key, val] of Object.entries(data.record)) {
                    if (i === 1 && val.type == 'page') {
                        html += '<option value="-" disabled="" class="SelectSeparator">---------Pages-------- </option>';
                    }
                    if (val.first && val.type == 'post') {
                        html += '<option value="-" disabled="" class="SelectSeparator">---------Posts--------- </option>';
                    }
                    html += `<option value="${val.type}#${val.id}">${val.name}</option>`;
                    i++;
                }
                document.getElementById('selectUrl' + data.formId).innerHTML = html;
                let inputUrl = document.getElementById('inputBtnURL' + data.formId);
                inputUrl.removeAttribute('disabled');
                inputUrl.classList.add('is-invalid');
            }
        }
    }
}

function toggle_hover_btn(e) {
    e.classList.toggle('active');
}



