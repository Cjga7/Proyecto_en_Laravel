const mix = require('laravel-mix');
const lodash = require("lodash");
const WebpackRTLPlugin = require('webpack-rtl-plugin');
const folder = {
    src: "resources/", // source files
    dist: "public/", // build files
    dist_assets: "public/assets/" //build assets files
};

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

    var third_party_assets = {
        css_js: [
            {"name": "jquery", "assets": ["./node_modules/jquery/dist/jquery.min.js"]},
            {"name": "bootstrap", "assets": ["./node_modules/bootstrap/dist/js/bootstrap.bundle.js"]},
            {"name": "metismenu", "assets": ["./node_modules/metismenu/dist/metisMenu.js"]},
            {"name": "simplebar", "assets": ["./node_modules/simplebar/dist/simplebar.js"]},
            {"name": "node-waves", "assets": ["./node_modules/node-waves/dist/waves.js"]},
            {"name": "waypoints", "assets": ["./node_modules/waypoints/lib/jquery.waypoints.min.js"]},
            {"name": "select2", "assets": ["./node_modules/select2/dist/js/select2.min.js", "./node_modules/select2/dist/css/select2.min.css"]},
            {"name": "chart-js", "assets": ["./node_modules/chart.js/dist/Chart.bundle.min.js"]},
            {"name": "apexcharts", "assets": ["./node_modules/apexcharts/dist/apexcharts.min.js"]},
        
            {
                "name": "datatables", "assets": ["./node_modules/datatables.net/js/jquery.dataTables.min.js",
                    "./node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js",
                    "./node_modules/datatables.net-responsive/js/dataTables.responsive.min.js",
                    "./node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js",
                    "./node_modules/datatables.net-buttons/js/dataTables.buttons.min.js",
                    "./node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js",
                    "./node_modules/datatables.net-buttons/js/buttons.html5.min.js",
                    "./node_modules/datatables.net-buttons/js/buttons.flash.min.js",
                    "./node_modules/datatables.net-buttons/js/buttons.print.min.js",
                    "./node_modules/datatables.net-buttons/js/buttons.colVis.min.js",
                    "./node_modules/datatables.net-keytable/js/dataTables.keyTable.min.js",
                    "./node_modules/datatables.net-select/js/dataTables.select.min.js",
                    "./node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css",
                    "./node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css",
                    "./node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.css",
                    "./node_modules/datatables.net-select-bs4/css/select.bootstrap4.css"]
            },
            {"name": "pdfmake", "assets": ["./node_modules/pdfmake/build/pdfmake.min.js", "./node_modules/pdfmake/build/vfs_fonts.js"]},
            {"name": "jszip", "assets": ["./node_modules/jszip/dist/jszip.min.js"]},
            {"name": "curiosityx", "assets": ["./node_modules/@curiosityx/bootstrap-session-timeout/index.js"]},
            {"name": "bootstrap-datepicker", "assets": ["./node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js","./node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"]},
            {"name": "gmaps", "assets": ["./node_modules/gmaps/gmaps.min.js"]},
            {"name": "leaflet", "assets": ["./node_modules/leaflet/dist/leaflet.js", "./node_modules/leaflet/dist/leaflet.css"]},
            {"name": "flot-curvedlines", "assets": ["./node_modules/flot.curvedlines/flot/jquery.flot.min.js"]},
            {"name": "owl-carousel", "assets": ["./node_modules/owl.carousel/dist/owl.carousel.min.js", "./node_modules/owl.carousel/dist/assets/owl.carousel.min.css","./node_modules/owl.carousel/dist/assets/owl.theme.default.min.css"]},
            {"name": "toastr", "assets": ["./node_modules/toastr/build/toastr.min.js", "./node_modules/toastr/build/toastr.min.css"]}, 
            {"name": "rwd-table", "assets": ["./node_modules/admin-resources/rwd-table/rwd-table.min.js", "./node_modules/admin-resources/rwd-table/rwd-table.min.css"]},          
            {"name": "bootstrap-editable", "assets": ["./node_modules/bootstrap-editable/js/index.js", "./node_modules/bootstrap-editable/css/bootstrap-editable.css"]},        
            {"name": "ion-rangeslider", "assets": ["./node_modules/ion-rangeslider/js/ion.rangeSlider.min.js", "./node_modules/ion-rangeslider/css/ion.rangeSlider.min.css"] },
            {"name": "sweetalert2", "assets": ["./node_modules/sweetalert2/dist/sweetalert2.min.js", "./node_modules/sweetalert2/dist/sweetalert2.min.css"]},
            {"name": "jquery-sparkline", "assets": ["./node_modules/jquery-sparkline/jquery.sparkline.min.js"]},
            {"name": "jquery-knob", "assets": ["./node_modules/jquery-knob/dist/jquery.knob.min.js"]},
            {"name": "moment", "assets": ["./node_modules/moment/min/moment.min.js"]},
            {
                "name": "flot-charts", "assets": ["./node_modules/flot-charts/jquery.flot.js",
                    "./node_modules/flot-charts/jquery.flot.time.js",
                    "./node_modules/flot-charts/jquery.flot.resize.js",
                    "./node_modules/flot-charts/jquery.flot.pie.js",
                    "./node_modules/flot-charts/jquery.flot.selection.js",
                    "./node_modules/flot-charts/jquery.flot.stack.js",
                    "./node_modules/flot-charts/jquery.flot.crosshair.js",
                    "./node_modules/jquery.flot.tooltip/js/jquery.flot.tooltip.min.js"]
            },
            {
                "name": "fullcalendar", "assets": [
                    "./node_modules/@fullcalendar/core/main.min.js",
                    "./node_modules/@fullcalendar/bootstrap/main.min.js",
                    "./node_modules/@fullcalendar/daygrid/main.min.js",
                    "./node_modules/@fullcalendar/timegrid/main.min.js",
                    "./node_modules/@fullcalendar/interaction/main.min.js",
                    "./node_modules/@fullcalendar/core/main.min.css",
                    "./node_modules/@fullcalendar/bootstrap/main.min.css",
                    "./node_modules/@fullcalendar/daygrid/main.min.css",
                    "./node_modules/@fullcalendar/timegrid/main.min.css"]
            },
            {
                "name": "jquery-vectormap", "assets": ["./node_modules/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js",
                    "./node_modules/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js",
                    "./node_modules/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js",
                    "./node_modules/admin-resources/jquery.vectormap/maps/jquery-jvectormap-au-mill-en.js",
                    "./node_modules/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-il-chicago-mill-en.js",
                    "./node_modules/admin-resources/jquery.vectormap/maps/jquery-jvectormap-in-mill-en.js",
                    "./node_modules/admin-resources/jquery.vectormap/maps/jquery-jvectormap-uk-mill-en.js",
                    "./node_modules/admin-resources/jquery.vectormap/maps/jquery-jvectormap-ca-lcc-en.js",
                    "./node_modules/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css"]
            },
            {"name": "magnific-popup", "assets": ["./node_modules/magnific-popup/dist/jquery.magnific-popup.min.js", "./node_modules/magnific-popup/dist/magnific-popup.css"]},
            {"name": "parsleyjs", "assets": ["./node_modules/parsleyjs/dist/parsley.min.js"]},
            {"name": "bootstrap-touchspin", "assets": ["./node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js", "./node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css"] },
            {"name": "bootstrap-maxlength", "assets": ["./node_modules/bootstrap-maxlength/dist/bootstrap-maxlength.min.js"]},
            {"name": "dropzone", "assets": ["./node_modules/dropzone/dist/min/dropzone.min.js", "./node_modules/dropzone/dist/min/dropzone.min.css"]},            
            {"name": "jquery-countdown", "assets": ["./node_modules/jquery-countdown/dist/jquery.countdown.min.js"]},
            {"name": "jquery-easing", "assets": ["./node_modules/jquery.easing/jquery.easing.min.js"]},
            {"name": "jquery-repeater", "assets": ["./node_modules/jquery.repeater/jquery.repeater.min.js"]},
            {"name": "jquery-counterup", "assets": ["./node_modules/jquery.counterup/jquery.counterup.min.js"]},
            {"name": "jquery-flot-tooltip", "assets": ["./node_modules/jquery.flot.tooltip/js/jquery.flot.tooltip.min.js"]},
            {"name": "jquery-bar-rating", "assets": [
                "./node_modules/jquery-bar-rating/dist/jquery.barrating.min.js", 
                "./node_modules/jquery-bar-rating/dist/themes/bars-1to10.css",
                "./node_modules/jquery-bar-rating/dist/themes/bars-horizontal.css",
                "./node_modules/jquery-bar-rating/dist/themes/bars-movie.css",
                "./node_modules/jquery-bar-rating/dist/themes/bars-pill.css",
                "./node_modules/jquery-bar-rating/dist/themes/bars-reversed.css",
                "./node_modules/jquery-bar-rating/dist/themes/bars-square.css",
                "./node_modules/jquery-bar-rating/dist/themes/css-stars.css",
                "./node_modules/jquery-bar-rating/dist/themes/fontawesome-stars-o.css",
                "./node_modules/jquery-bar-rating/dist/themes/fontawesome-stars.css"]
            },
            {"name": "jquery-ui-dist", "assets": ["./node_modules/jquery-ui-dist/jquery-ui.min.js"]},
            {"name": "inputmask", "assets": ["./node_modules/inputmask/dist/min/jquery.inputmask.bundle.min.js"]},
            {"name": "ckeditor", "assets": ["./node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"]},
            {"name": "table-edits", "assets": ["./node_modules/table-edits/build/table-edits.min.js"]},
            {"name": "flot-curvedLines", "assets": ["./node_modules/flot.curvedlines/curvedLines.js"]},
            {"name": "spectrum-colorpicker", "assets": ["./node_modules/spectrum-colorpicker2/dist/spectrum.min.js", "./node_modules/spectrum-colorpicker2/dist/spectrum.min.css"]},
            {"name": "jquery-steps", "assets": ["./node_modules/jquery-steps/build/jquery.steps.min.js"]},
            {"name": "datepicker", "assets": ["./node_modules/@chenfengyuan/datepicker/dist/datepicker.min.js","./node_modules/@chenfengyuan/datepicker/dist/datepicker.min.css"]},
        ]
    };

	//copying third party assets
    lodash(third_party_assets).forEach(function (assets, type) {
        if (type == "css_js") {
            lodash(assets).forEach(function (plugin) {
                var name = plugin['name'],
                    assetlist = plugin['assets'],
                    css = [],
                    js = [];
                lodash(assetlist).forEach(function (asset) {
                    var ass = asset.split(',');
					for (let i = 0; i < ass.length; ++i) {
                    	if(ass[i].substr(ass[i].length - 3)  == ".js") {
                    		js.push(ass[i]);
                    	} else {
                    		css.push(ass[i]);
                    	}
                	};
                });
            	if(js.length > 0){
            		mix.combine(js, folder.dist_assets + "/libs/" + name + "/" + name + ".min.js");
            	}
            	if(css.length > 0){
            		mix.combine(css, folder.dist_assets + "/libs/" + name + "/" + name + ".min.css");
            	}
            });
        }
    });
    
    mix.copyDirectory("./node_modules/tinymce", folder.dist_assets + "/libs/tinymce");
    mix.copyDirectory("./node_modules/leaflet/dist/images", folder.dist_assets + "/libs/leaflet/images");
    mix.copyDirectory("./node_modules/bootstrap-editable/img", folder.dist_assets + "/libs/img");

    // copy all fonts
    var out = folder.dist_assets + "fonts";
    mix.copyDirectory(folder.src + "fonts", out);

    // copy all images 
    var out = folder.dist_assets + "images";
    mix.copyDirectory(folder.src + "images", out);

    mix.sass('resources/scss/bootstrap.scss', folder.dist_assets + "css").minify(folder.dist_assets + "css/bootstrap.css");
    //mix.sass('resources/scss/bootstrap-dark.scss', folder.dist_assets + "css").minify(folder.dist_assets + "css/bootstrap-dark.css");
    mix.sass('resources/scss/icons.scss', folder.dist_assets + "css").options({processCssUrls: false}).minify(folder.dist_assets + "css/icons.css");
    mix.sass('resources/scss/app.scss', folder.dist_assets + "css").options({processCssUrls: false}).minify(folder.dist_assets + "css/app.css");
    //mix.sass('resources/scss/app-dark.scss', folder.dist_assets + "css").options({processCssUrls: false}).minify(folder.dist_assets + "css/app-dark.css");

    mix.webpackConfig({
        plugins: [
            new WebpackRTLPlugin()
        ],
        stats: {
            children: true,
        },
    });

    //copying demo pages related assets
    var app_pages_assets = {
        js: [
            folder.src + "js/pages/alerts.init.js",
            folder.src + "js/pages/apexcharts.init.js",
            folder.src + "js/pages/auth-carousel.init.js",
            folder.src + "js/pages/bootstrap-toasts.init.js",
            folder.src + "js/pages/calendar.init.js",
            folder.src + "js/pages/chartjs.init.js",
            folder.src + "js/pages/coming-soon.init.js",
            folder.src + "js/pages/dashboard.init.js",
            folder.src + "js/pages/datatables.init.js",
            folder.src + "js/pages/ecommerce-add-product.init.js",
            folder.src + "js/pages/ecommerce-cart.init.js",
            folder.src + "js/pages/ecommerce-datatables.init.js",
            folder.src + "js/pages/email-editor.init.js",
            folder.src + "js/pages/flot.init.js",
            folder.src + "js/pages/fontawesome.init.js",
            folder.src + "js/pages/form-advanced.init.js",
            folder.src + "js/pages/form-editor.init.js",
            folder.src + "js/pages/form-mask.init.js",
            folder.src + "js/pages/form-repeater.int.js",
            folder.src + "js/pages/form-validation.init.js",
            folder.src + "js/pages/form-wizard.init.js",
            folder.src + "js/pages/form-xeditable.init.js",
            folder.src + "js/pages/file-manager.init.js",
            folder.src + "js/pages/gmaps.init.js",
            folder.src + "js/pages/jquery-knob.init.js",
            // folder.src + "js/pages/leaflet-us-states.js",
            folder.src + "js/pages/leaflet-map.init.js",
            folder.src + "js/pages/lightbox.init.js",
            folder.src + "js/pages/materialdesign.init.js",
            folder.src + "js/pages/modal.init.js",
            folder.src + "js/pages/product-filter-range.init.js",
            folder.src + "js/pages/range-sliders.init.js",
            folder.src + "js/pages/rating-init.js",
            folder.src + "js/pages/session-timeout.init.js",
            folder.src + "js/pages/sparklines.init.js",
            folder.src + "js/pages/sweet-alerts.init.js",
            folder.src + "js/pages/timeline.init.js",
            folder.src + "js/pages/toastr.init.js",
            folder.src + "js/pages/table-editable.init.js",
            folder.src + "js/pages/table-responsive.init.js",
            folder.src + "js/pages/vector-maps.init.js"
        ]
    };

    var out = folder.dist_assets + "js/";
    lodash(app_pages_assets).forEach(function (assets, type) {
		for (let i = 0; i < assets.length; ++i) {
        	mix.js(assets[i], out + "pages");
    	};
    });

    mix.combine('resources/js/app.js', folder.dist_assets + "js/app.min.js");
    mix.combine('./node_modules/flatpickr/dist/flatpickr.min.css', folder.dist_assets + "libs/flatpickr/flatpickr.min.css");
    mix.combine('./node_modules/flatpickr/dist/flatpickr.min.js', folder.dist_assets + "libs/flatpickr/flatpickr.min.js");
    mix.combine('resources/js/pages/leaflet-us-states.js', folder.dist_assets + "js/pages/leaflet-us-states.js");