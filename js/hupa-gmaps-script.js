// GoogleMaps Ausgabe Funktion

let gmaps_container = document.querySelector(".hupa-gmaps-container");

function hupa_gmaps_data() {
    // AJAX SEND FUNKTION
    let xhr = new XMLHttpRequest();
    let formData = new FormData();
    xhr.open('POST', theme_ajax_obj.ajax_url, true);
    formData.append('_ajax_nonce', theme_ajax_obj.nonce);
    formData.append('action', 'HupaStarterNoAdmin');
    formData.append('method', 'get_gmaps_data');
    xhr.send(formData);
    //Response
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let data = JSON.parse(this.responseText);
            if (!gmaps_container) {
                return false;
            }

            let infowindow = new google.maps.InfoWindow();
            //let geocoder = new google.maps.Geocoder();
            let output = [];
            let map_container = document.getElementsByClassName('hupa-gmaps-container');
            let map_type_ids = ['roadmap'];

            //Farbshema anlegen
            if (data.farbshema_aktiv && data.farbshema_aktiv != false) {

                //console.log('Benutzerdefiniertes Farbschema wird geladen.');

                let farbshema = data.farbshema.replace(/&#34;/g, '"');
                farbshema = farbshema.replace(/&#39;/g, '"');
                farbshema = JSON.parse(farbshema);
                let custom_style = new google.maps.StyledMapType(farbshema, {name: 'HUPA-Map'});
                map_type_ids = ['styled_map'];
            }

            //Karte definieren
            let hupamap;
            hupamap = new google.maps.Map(map_container[0], {
                center: {lat: 52.130958, lng: 11.616186},
                zoom: 15,
                streetViewControl: false,
                mapTypeControlOptions: {
                    mapTypeIds: map_type_ids
                }
            });

            if (data.farbshema_aktiv && data.farbshema_aktiv != false) {
                hupamap.mapTypes.set('styled_map', custom_style);
                hupamap.setMapTypeId('styled_map');
            }

            //StandardPin Icon Style
            let stdPinImg = data.std_pin_img;

            if (stdPinImg == false || stdPinImg == '') {
                stdPinImg = get_hupa_option.admin_url + 'assets/images/map-pin.png';
            }

            let stdIcon = {
                url: stdPinImg,
                scaledSize: new google.maps.Size(data.std_pin_width, data.std_pin_height),
            }

            //Marker hinzufügen
            for (const [key, value] of Object.entries(data.pins)) {

                let lat = Number(value.coords.split(',')[0]);
                let lng = Number(value.coords.split(',')[1]);
                let pinadress = {lat: lat, lng: lng};
                let textbox = value.info_text.replace(/\n/g, "<br/>");
                let pinicon = stdIcon;

                if (value.custom_pin_aktiv == true) {
                    pinicon = {
                        url: value.img_url,
                        scaledSize: new google.maps.Size(value.custom_width, value.custom_height),
                    }
                }

                let marker = new google.maps.Marker({
                    map: hupamap,
                    position: pinadress,
                    icon: pinicon,
                    loc: textbox,
                });

                output.push(marker);

                if (textbox != '') {
                    google.maps.event.addListener(marker, 'click', function () {
                        infowindow.close(); // Close previously opened infowindow
                        infowindow.setContent('<div class="infowindow"><p>' + this.loc + '</p></div>');
                        infowindow.open({
                            anchor: this,
                            hupamap,
                            shouldFocus: false,
                        });
                    });
                }
            }

            if (output.length < 1) {
                let bounds = new google.maps.LatLngBounds();
                for (let j = 0; j < output.length; j++) {
                    if (output[j].getVisible()) {
                        bounds.extend(output[j].getPosition());
                    }
                }
                hupamap.fitBounds(bounds);
            } else {
                hupamap.setCenter(output[0].position);
            }
        }
    }
}

let saveSession;
window.addEventListener("load", function (event) {
    let api_key = window.atob(get_hupa_option.key);
    saveSession = sessionStorage.getItem("gmaps");
    if (!get_hupa_option.ds_maps || saveSession || get_hupa_option.gmaps == '1' ) {
        injectGoogleMapsApiScript({
            key: api_key,
            callback: 'hupa_gmaps_data',
        });
    }

    // Button Click Funktion
    let clickGoogleMapsDsBtn = document.querySelectorAll(".hupa-gmaps-btn");
    if (clickGoogleMapsDsBtn) {
        let btnGmapsNodes = Array.prototype.slice.call(clickGoogleMapsDsBtn, 0);
        btnGmapsNodes.forEach(function (btnGmapsNodes) {
            btnGmapsNodes.addEventListener("click", function (e) {
                btnGmapsNodes.blur();
                let checkInput = btnGmapsNodes.parentNode;
                let checkBox = checkInput.querySelector('.api-karte-check input');
                if (checkBox.checked) {
                    sessionStorage.setItem('gmaps', true);
                    let xhr = new XMLHttpRequest();
                    let formData = new FormData();
                    xhr.open('POST', theme_ajax_obj.ajax_url, true);
                    formData.append('_ajax_nonce', theme_ajax_obj.nonce);
                    formData.append('action', 'HupaStarterNoAdmin');
                    formData.append('method', 'set_gmaps_session');
                    formData.append('status', true);
                    xhr.send(formData);
                     injectGoogleMapsApiScript({
                         key: api_key,
                         callback: 'hupa_gmaps_data',
                     });
                }
            });
        });
    }
});//documentReady


let googleMapsScriptIsInjected = false;
const injectGoogleMapsApiScript = (options = {}) => {
    if (googleMapsScriptIsInjected) {
        //throw new Error('Google Maps Api is already loaded.');
        //  console.log('Google Maps Api is already loaded.');
        return false;
    }

    const optionsQuery = Object.keys(options)
        .map(k => `${encodeURIComponent(k)}=${encodeURIComponent(options[k])}`)
        .join('&');

    const url = `https://maps.googleapis.com/maps/api/js?${optionsQuery}`;
    const script = document.createElement('script');
    script.setAttribute('src', url);
    script.setAttribute('async', '');
    script.setAttribute('defer', '');
    document.head.appendChild(script);
    googleMapsScriptIsInjected = true;
};
