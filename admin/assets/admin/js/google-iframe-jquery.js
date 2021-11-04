jQuery(document).ready(function ($) {

    /**=======================================================
     ================ BTN SEND SUBMIT FORMULAR ================
     ==========================================================
     */
    $(document).on('submit', '.sendAjaxJqueryBtnForm', function (event) {

        $('.modalAction').val('HupaStarterHandle');
        $('.modalNonce').val(theme_ajax_obj.nonce);
        let form_data = $(this).serializeObject();
        send_jquery_iframe_form_data(form_data);
        return false;
    });

    function send_jquery_iframe_form_data(form_data) {
        $.ajax({
            url: theme_ajax_obj.ajax_url,
            type: "POST",
            data: form_data,
            success: function (data) {
                $(".sendAjaxJqueryBtnForm").trigger("reset");
                if (data.status) {
                    let table = $('#TableGoogleIframe').DataTable();
                    table.draw('page');
                    success_message(data.msg);
                } else {
                    if (data.msg) {
                        warning_message(data.msg);
                    }
                }
            },
            error: function (xhr, resp, text) {
                // show error to console
                console.log(xhr, resp, text);
            }
        });
        return false;
    }

    /**====================================================
     ================ BTN ADD I-FRAME MODAL================
     ======================================================*/
    let iframeDeleteModal = document.getElementById('iframeDeleteModal');
    if(iframeDeleteModal) {
        iframeDeleteModal.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id');
            document.querySelector('.btn-delete-iframe').setAttribute('data-id', id);
        })
    }

    /**=================================================
     ================ BTN DELETE I-FRAME================
     ===================================================*/
    $(document).on('click', '.btn-delete-iframe', function (event) {
        $.post(theme_ajax_obj.ajax_url, {
            '_ajax_nonce': theme_ajax_obj.nonce,
            'action': 'HupaStarterHandle',
            method: 'delete_gmaps_iframe',
            id: $(this).attr('data-id'),
        }, function (data) {
            if (data.status) {
                let table = $('#TableGoogleIframe').DataTable();
                table.draw('page');
               success_message(data.msg);
            } else {
                warning_message(data.msg);
            }
        });
    });

    /**====================================================
     ================ BTN ADD I-FRAME MODAL================
     ======================================================*/
    let iframeHandleModal = document.getElementById('addIframeMapsModal')
    if(iframeHandleModal) {
        iframeHandleModal.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget
            let type = button.getAttribute('data-bs-type');
            let modalTitle = iframeHandleModal.querySelector('.modal-title');
            let modalContent = iframeHandleModal.querySelector('.modal-body');
            let modalButton = iframeHandleModal.querySelector('.modal-btn');

            switch (type) {
                case 'insert':
                    modalTitle.innerHTML = '<i class="fa fa-plus"></i> Neue Karte erstellen';
                    modalContent.innerHTML = create_modal_input();
                    modalButton.innerHTML = '<i class="fa fa-plus"></i> Karte erstellen';
                    break;
                case'update':
                    let id = button.getAttribute('data-bs-id');
                    $.post(theme_ajax_obj.ajax_url, {
                        '_ajax_nonce': theme_ajax_obj.nonce,
                        'action': 'HupaStarterHandle',
                        method: 'get_iframe_modal_data',
                        id: id,
                        type: type
                    }, function (data) {
                        if (data.status) {
                            modalContent.innerHTML = create_modal_input(data.record);
                            modalButton.innerHTML = '<i class="fa fa-edit"></i> Änderungen speichern';
                            modalTitle.innerHTML = `<i class="fa fa-edit"></i> Karte "${data.record.bezeichnung}" bearbeiten`;
                        } else {
                            warning_message(data.msg);
                        }
                    });
                    break;
            }
        });
    }

    function create_modal_input(data=false) {
        return `<input type="hidden" name="type" value="${data ? 'update' : 'insert'}"> 
                    <input class="modalAction" type="hidden" name="action"> 
                    <input class="modalNonce" type="hidden" name="_ajax_nonce">
                    <input  type="hidden" name="method" value="gmaps_iframe_handle">
                    <input  type="hidden" name="id" value="${data ? data.id : ''}">    
                    <div class="col-xl-6 col-lg-8 col-12 mb-3">
                       <label for="inputBezeichnung" class="form-label">Karten Bezeichnung</label>
                       <input type="text" name="bezeichnung" class="form-control" value="${data ? data.bezeichnung : ''}" id="inputBezeichnung">
                    </div>
                    <div class="mb-3">
                        <label for="inputKartenCode" class="form-label">Google-Maps I-Frame</label>
                        <textarea class="form-control" name="iframe" id="inputKartenCode" rows="7">${data ? data.iframe : ''}</textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input onclick="this.blur()" class="form-check-input" name="datenschutz" type="checkbox" 
                        role="switch" id="datenschutzChecked" ${data && data.datenschutz == '1' ? 'checked' : ''}>
                        <label class="form-check-label" for="datenschutzChecked">Datenschutz aktiv</label>
                   </div>`;
    }

    /**======================================================
     ================ LOAD IFRAME DATA TABLE ================
     ========================================================
     */

    $('#TableGoogleIframe').DataTable({
        "language": {
            "url": hupa_starter.data_table
        },
        "columns": [
            null,
            null,
            null,
            null,
            {
                "width": "8%"
            },
            {
                "width": "8%"
            }
        ],
        columnDefs: [{
            orderable: false,
            targets: [4, 5]
        },
            {
                targets: [],
                className: 'text-center'
            },
            {
                targets: ['_all'],
                className: 'align-middle'
            }
        ],
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: theme_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'HupaStarterHandle',
                '_ajax_nonce': theme_ajax_obj.nonce,
                method: 'iframe_data_table'
            }
        }
    });

    /**=============================================
     ================ FORM Serialize ================
     ================================================
     */
    $.fn.serializeObject = function () {
        let o = {};
        let a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    /**=========================================
     ========== AJAX RESPONSE MESSAGE ===========
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

});