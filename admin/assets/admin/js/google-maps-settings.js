document.addEventListener('DOMContentLoaded', function () {

    let clrPickrContainer = document.querySelectorAll('.colorPickers');
    if (clrPickrContainer) {
        let colorNode = Array.prototype.slice.call(clrPickrContainer, 0);
        colorNode.forEach(function (colorNode) {
            let setColor = colorNode.getAttribute('data-color');
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
                send_xhr_map_form_data(colorInput.form);
            }).on('cancel', (instance) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = instance._lastColor.toHEXA().toString(0);
                send_xhr_map_form_data(colorInput.form);
                pickr.hide();
            }).on('swatchselect', (color, instance) => {
                let colorInput = colorNode.childNodes[1];
                colorInput.value = color.toHEXA().toString(0);
                send_xhr_map_form_data(colorInput.form);
            });
        });
    }

    let mapPLatzHalterImg = document.getElementById("btn-change-map-img");
    if (mapPLatzHalterImg) {
        let mediaFrame,
        deleteImgBtn = document.getElementById('btn-delete-map-img'),
        imgSrc = document.getElementById('mapPhImage'),
        imgInput = document.getElementById('map_img_input');
        mapPLatzHalterImg.addEventListener("click", function (e) {

            if (mediaFrame) {
                mediaFrame.open();
                return;
            }
            mediaFrame = wp.media({
                title: 'Google Maps Platzhalter Bild',
                button: {
                    text: 'Bild auswählen'
                },
                multiple: false
            });

            mediaFrame.on('select', function () {
                let attachment = mediaFrame.state().get('selection').first().toJSON();
                imgSrc.src = attachment.url;
                imgInput.value = attachment.id;
                deleteImgBtn.classList.remove('d-none');
                send_xhr_map_form_data(imgInput.form);
            });
            mediaFrame.open();
        });

        deleteImgBtn.addEventListener("click", function (e) {
            imgSrc.src = hupa_starter.admin_url + 'assets/images/blind-karte.svg';
            deleteImgBtn.classList.add('d-none');
            imgInput.value = '';
            send_xhr_map_form_data(imgInput.form);
        });
    }


    /**=========================================
    ========== AJAX FORMS AUTO SAVE  ==========
    ===========================================
    */

    let themeSendMapsFormTimeout;
    let themeSendMapFormular = document.querySelectorAll(".sendAjaxGMapsThemeForm:not([type='button'])");
    if (themeSendMapFormular) {
        let formNodes = Array.prototype.slice.call(themeSendMapFormular, 0);
        formNodes.forEach(function (formNodes) {
            formNodes.addEventListener("keyup", form_input_ajax_handle, {passive: true});
            formNodes.addEventListener('touchstart', form_input_ajax_handle, {passive: true});
            formNodes.addEventListener('change', form_input_ajax_handle, {passive: true});

            function form_input_ajax_handle(e) {
                let spinner = Array.prototype.slice.call(ajaxSpinner, 0);
                spinner.forEach(function (spinner) {
                    spinner.innerHTML = '<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...';
                });
                clearTimeout(themeSendMapsFormTimeout);
                themeSendMapsFormTimeout = setTimeout(function () {
                    send_xhr_map_form_data(formNodes);
                }, 1000);
            }
        });
    }

    /**======================================
    ========== AJAX DATEN SENDEN  ==========
    ========================================
    */

    function send_xhr_map_form_data(data, is_formular = true) {

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
            }
        }
    }

});