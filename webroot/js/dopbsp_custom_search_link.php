<?php
/*
  Plugin Name: Pinpoint Booking System Custom Search Link
  Version: 12.1.1
  Plugin URI: #
  Description: Pinpoint Booking System Custom Search Link
  Author: Mahendra
  Author URI: #
 */
add_shortcode('dopbsp_custom_search_link', 'dopbsp_custom_search_link_fn');

function dopbsp_custom_search_link_fn($atts) {
    ob_start();
    // echo '<pre>';
    // print_r($_GET);
    // echo '</pre>';
    global $DOPBSP;
    $lang = get_bloginfo("language");
    $settings_search = $args['settings_search'];

    $id = $atts['id'];

    $viewss = $atts['viewss'];
    $hours = json_decode($settings_search->hours_definitions);

    $args = array(
        'name' => 'destination-package'
    );
    $output = 'objects'; // or names
    $taxonomies = get_terms('destination-package');
    // echo "<pre style='display:none;'>";
    // print_r($taxonomies);
    // echo "</pre>";
    $addr = '<option value="">ALL Destinations</option>';
    $persion = '<option value="">ALL People</option>';
    global $wpdb;
    $tbl1 = $wpdb->prefix . "dopbsp_calendars";
    $res1 = $wpdb->get_results("select DISTINCT address From $tbl1");
    if ($res1) {

        foreach ($res1 as $k => $v) {
            $addr .= '<option value="' . $v->address . '">' . $v->address . '</option>';
        }
    }
    global $wpdb;
    $tbl2 = $wpdb->prefix . "dopbsp_extras";
    $res2 = $wpdb->get_results("select * From $tbl2");
    if ($res2) {
        foreach ($res2 as $k => $v) {
            $persion .= '<option value="' . $v->id . '">' . $v->name . '</option>';
        }
    }
    $tbl3 = $wpdb->prefix . 'postmeta';
    $res3 = $wpdb->get_results("select max(cast(meta_value as unsigned)) as max_r from $tbl3 where meta_key = 'metabox_package_price'");
    $max_r = 10000;
    if ($res3) {
        $max_r = $res3[0]->max_r;
    }
    ?>
    <link rel="stylesheet" type="text/css" href="">
    <style>
        .wpb_tabs .wpb_tabs_nav li {
            width:auto;
            margin: 0 !important;
        }
        .wpb_tabs .wpb_tabs_nav li a{
            border: 0 !important;
        }

        .nicdark_bg_greydark2, .nicdark_bg_greydark2_hover:hover {
            background-color: #4a515b !important;
        }
        .wpb_tabs_nav li:nth-child(1) a.title {
            background: #14b9d5 none repeat scroll 0 0;
        }
        .vc_tta.vc_tta-spacing-1 .vc_tta-tab {
            margin: 0 !important;
        }
        .vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab:nth-child(2) > a {
            background-color: #1bbc9b;
            color: #ffffff;
            border-color:#1bbc9b; 
            border-radius: 0;
        }
        .vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab:nth-child(1) > a {
            background-color: #14b9d5;
            color: #ffffff;
            border-color: #14b9d5;
            border-radius: 0;
        }
        .vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab.vc_active > a {
            background-color: #434a54 !important;
        }
        .wpb_tabs_nav .ui-state-active a.title {
            background-color: #434a54 !important;
            color: #b7b7b7 !important;
        }
        .wpb_content_element.wpb_tabs .wpb_tour_tabs_wrapper .wpb_tab,
        .vc_tta-color-grey.vc_tta-style-classic.vc_tta-tabs .vc_tta-panels {
            background-color: #434a54 !important;
            border-width: 0;
            padding: 20px 25px;
        }
        .vc_tta-color-grey.vc_tta-style-classic.vc_tta-tabs .vc_tta-panels, 
        .vc_tta-color-grey.vc_tta-style-classic.vc_tta-tabs .vc_tta-panels::before, 
        .vc_tta-color-grey.vc_tta-style-classic.vc_tta-tabs .vc_tta-panels::after {
            border-color: #434a54;
        }
        .ui-datepicker,
        .ui-tabs > div.wpb_tab:nth-child(3) > .nicdark_advanced_search > #nicdark_advanced_search_keyword,
        .ui-tabs > div.wpb_tab:nth-child(2) > .nicdark_advanced_search > div:nth-child(2),
        .ui-tabs > div.wpb_tab:nth-child(2) > .nicdark_advanced_search > div:nth-child(3),
        .ui-tabs > div.wpb_tab:nth-child(2) > .nicdark_advanced_search > div:nth-child(4),
        .ui-tabs > div.wpb_tab:nth-child(2) > .nicdark_advanced_search > div:nth-child(5) {
            display:none;
        }
        .dopbsp-grid {
            padding-left: 0;
        }
        .dopbsp-grid li {
            list-style: none;
        }
        /*#DOPBSPSearch-results<?php //echo $id;   ?> .dopbsp-grid {
        text-align: center;
    } */  
        #DOPBSPSearch-results<?php echo $id; ?> .dopbsp-grid li {
            margin: 0 5px 10px 5px;
            text-align: left;
            float:none;
            vertical-align:top;
        }
        .dopbsp-grid .nicdark_textevidence.nicdark_bg_greydark {
            background:#14b9d5;
        }
        .dopbsp-grid .nicdark_btn.medium.custom_bg {
            background:#14b9d5;   
            color:#ffffff;
        }
        .dopbsp-grid .nicdark_textevidence.nicdark_bg_greydark h4 {
            margin: 0;
            padding: 20px;
        }
        .vc_tta-panels > div:nth-child(2) #nicdark_advanced_search_keyword {
            display: none;
        }
        .vc_tta-panels > div:nth-child(2) #nicdark_advanced_search_prices,
        .ui-tabs > div.wpb_tab:nth-child(3) #nicdark_advanced_search_prices {
            width: 50%;
        }
        .DOPBSPSearch-view {
            padding: 0;
        }
        .DOPBSPSearch-view li {
            background-color: #c9c9c9;
            background-image: url("<?php echo site_url(); ?>/wp-content/plugins/dopbsp/templates/default/images/sprite.png");
            cursor: pointer;
            float: right;
            height: 30px;
            list-style: outside none none;
            margin: 0 5px 0 0;
            transition: background-color 600ms linear 0s;
            width: 30px;
        }
        .DOPBSPSearch-view li.dopbsp-view-list {
            background-position: -60px -80px;
        }
        .DOPBSPSearch-view li.dopbsp-view-grid {
            background-position: -90px -80px;
        }
        .DOPBSPSearch-view li.dopbsp-view-map {
            background-position: -120px -80px;
        }
        .DOPBSPSearch-view li:hover, .DOPBSPSearch-view li.dopbsp-selected {
            background-color: #ff6300;
        }
        .DOPBSPSearch-view {
            text-align: right;
        }
        .DOPBSPSearch-view::after {
            clear: both;
            content: "";
            display: table;
        }
        .dopbsp-pagination {
            float: right;
            list-style: outside none none;
            margin: -15px 0;
            overflow: hidden;
            padding: 0;
        }
        .dopbsp-pagination li:hover, 
        .dopbsp-pagination li.dopbsp-selected {
            background-color: #ff6300;
            border: 1px solid #ff6300;
            color: #ffffff;
        }
        .dopbsp-pagination li {
            padding: 8px 15px;
        }
        .DOPBSPSearch-results .dopbsp-map{
            height: 500px;
            margin: 16px 0 40px 0;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox{
            background-color: #ffffff;
            -webkit-box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.5);
            box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.5);
            -webkit-box-sizing: content-box; 
            -moz-box-sizing: content-box;
            box-sizing: content-box;
            /*height: 100px;*/
            padding: 10px;
            width: 360px !important;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox img{
            background: #9f9f9f;
            border-radius: 0;
            box-shadow: none;
            -webkit-transition: background-color 600ms linear;
            -moz-transition: background-color 600ms linear;
            -o-transition: background-color 600ms linear;
            transition: background-color 600ms linear;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox img:hover{
            background: #ff6300;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations{
            list-style: none;
            /*height: 100px;*/
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li{
            /*height: 100px;*/
            margin: 0 0 10px 0;
            overflow: hidden;
            width: 350px;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li:first-child{
            -webkit-transition: margin 100ms linear;
            -moz-transition: margin 100ms linear;
            -o-transition: margin 100ms linear;
            transition: margin 100ms linear;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li:last-child{
            margin: 0;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li .dopbsp-image{
            float: left;
            height: 100px;
            margin: 0;
            padding: 0;
            width: 100px;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li .dopbsp-image a{
            background-color: #858585;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: auto 100%;
            border: none;
            display: block;
            height: 100px;
            margin: 0;
            overflow: hidden;
            padding: 0;
            text-decoration: none;
            width: 100px;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li .dopbsp-image img{
            border: none;
            border-radius: 0;
            box-shadow: none;
            height: 100px;
            max-width: none;
            visibility: hidden;
            width: auto;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li .dopbsp-content{

            margin: 0 0 0 110px;
            overflow: hidden;
            position: relative;
            width: 235px;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li .dopbsp-content h3{
            margin: 0;
            padding: 0;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li .dopbsp-content h3 a{
            color: #464646;
            display: block;
            font-family: 'Open Sans', sans-serif !important;
            font-size: 18px;
            font-weight: 600;
            line-height: 20px;
            margin: 0;
            padding: 0;
            text-decoration: none;
            -webkit-transition: color 600ms linear;
            -moz-transition: color 600ms linear;
            -o-transition: color 600ms linear;
            transition: color 600ms linear;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li .dopbsp-content h3 a:hover{
            color: #ff6300;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li .dopbsp-content .dopbsp-address{
            color: #666666;
            font-family: 'Open Sans', sans-serif !important;
            font-size: 13px;
            font-style: italic;
            font-weight: 300;
            line-height: 20px;
            margin: 0;
            padding: 0;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li .dopbsp-content .dopbsp-price-wrapper{
            color: #666666;
            font-family: 'Open Sans', sans-serif !important;
            font-size: 13px;
            font-weight: 300;
            height: 40px;
            line-height: 40px;
            margin: 0;
            padding: 0;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-locations li .dopbsp-content .dopbsp-price-wrapper .dopbsp-price{
            color: #ff6300;
            font-weight: 600;
            display: block;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-navigation{
            bottom: 10px;
            height: 31px;
            position: absolute;
            right: 10px;
            width: 15px;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-navigation .dopbsp-prev,
        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-navigation .dopbsp-next{
            background-color: #9f9f9f;
            background-image: url('../images/sprite.png');
            height: 15px;
            position: absolute;
            text-decoration: none;
            width: 15px;
            -webkit-transition: background-color 600ms linear;
            -moz-transition: background-color 600ms linear;
            -o-transition: background-color 600ms linear;
            transition: background-color 600ms linear;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-navigation .dopbsp-prev{
            background-position: -150px -80px;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-navigation .dopbsp-next{
            background-position: -150px -95px;
            margin: 16px 0 0 0;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-navigation .dopbsp-prev:hover,
        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-navigation .dopbsp-next:hover{
            background-color: #ff6300;
        }

        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-navigation .dopbsp-prev.dopbsp-disabled,
        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-navigation .dopbsp-next.dopbsp-disabled,
        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-navigation .dopbsp-prev.dopbsp-disabled:hover,
        .DOPBSPSearch-results .dopbsp-map .dopbsp-infobox .dopbsp-navigation .dopbsp-next.dopbsp-disabled:hover{
            background-color: #9f9f9f;
            opacity: 0.2;
        }

        .DOPBSPSearch-results .dopbsp-map .gm-style img,
        .DOPBSPSearch-results .dopbsp-map .gmnoprint img{
            border-radius: 0 !important;
            box-shadow: none !important;
            max-width: none !important; 
        }
        .dopbsp-map .fleft {
            float: none;
            height: auto;
        }
        .dopbsp-loader{
            background: url('<?php echo site_url(); ?>/wp-content/plugins/dopbsp/templates/default/images/loader.gif') no-repeat scroll center center; 
            border: 1px solid #cccccc;
            height: 38px;
            margin-bottom: 25px;
        }
        .r_details_btn {
            bottom: 0;
            color: #ffffff;
            position: absolute;
            right: 0;
            z-index: 999;
            padding:5px 10px;
            background: #14b9d5;
        }
        /*
         * End map.
         */
    </style>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js"></script>
    <script type="text/javascript" src="<?php echo site_url(); ?>/wp-content/plugins/dopbsp_custom_search/infobox_packed.js"></script>
    <script>
        var $ = jQuery;
        var pluginURL = '<?php echo site_url(); ?>/wp-content/plugins/dopbsp/';
        (function ($) {
            $(document).ready(function () {
                $('select[name="destination-package"] option[value="<?php echo $_GET["destination-package"]; ?>"]').attr('selected', 'selected');
                $(".nicdark_advanced_search input[name='date_from']").val('<?php echo $_GET["date_from"]; ?>');
                $(".nicdark_advanced_search input[name='date_to']").val('<?php echo $_GET["date_to"]; ?>');
                // $('select[name="person-package"]').html('<?php echo $persion; ?>');
                var ID = '<?php echo $id; ?>';
                var ajaxURL = "<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
                var ajaxRequestInProgress = undefined;
                $('.DOPBSPSearch-view li').on('click', function () {
                    var view = $(this).attr('data-value');
                    $(this).siblings().removeClass('dopbsp-selected');
                    $(this).addClass('dopbsp-selected');
                    $(".dopbsp-loader").show();
                    $('.rtotal').parent().hide();
                    var $checkIn = $(".nicdark_advanced_search input[name='date_from']").val(),
                            $checkOut = $(".nicdark_advanced_search input[name='date_to']").val(),
                            $startHour = $("#DOPBSPSearch-start-hour" + ID).val(),
                            $endHour = $("#DOPBSPSearch-end-hour" + ID).val(),
                            $address = $("#nicdark_autocomplete").val(),
                            $posttype = $("input[name='posttype']").val(),
                            checkIn = $checkIn === undefined ? "" : $checkIn,
                            address = $address === undefined ? "" : $address,
                            posttype = $posttype === undefined ? "" : $posttype,
                            checkOut = $checkOut === undefined || $checkOut === "" ? checkIn : $checkOut,
                            startHour = $startHour === undefined ? "" : $startHour,
                            endHour = $endHour === undefined || $endHour === "" ? startHour : $endHour;

                    page = 1;

                    if (ajaxRequestInProgress !== undefined) {
                        ajaxRequestInProgress.abort();
                    }
                    $("#DOPBSPSearch-results-loader" + ID).removeClass("DOPBSPSearch-hidden");
                    $("#DOPBSPSearch-results" + ID).html("");

                    ajaxRequestInProgress = $.post(ajaxURL, {action: "custom_dopbsp_search_results_get_link",
                        dopbsp_frontend_ajax_request: true,
                        id: ID,
                        check_in: checkIn,
                        check_out: checkOut,
                        start_hour: startHour,
                        end_hour: endHour,
                        address: address,
                        posttype: posttype,
                        no_items: $("#DOPBSPSearch-no-items" + ID).val() === undefined ? "" : $("#DOPBSPSearch-no-items" + ID).val(),
                        price_min: '',
                        price_max: '',
                        sort_by: $("#DOPBSPSearch-sort-by" + ID).val() === undefined ? "price" : $("#DOPBSPSearch-sort-by" + ID).val(),
                        sort_direction: $("#DOPBSPSearch-sort-direction-value" + ID).val() === undefined ? "ASC" : $("#DOPBSPSearch-sort-direction-value" + ID).val(),
                        view: view,
                        results: 10,
                        page: page}, function (data) {
                        data = $.trim(data);

                        switch (view) {
                            case "map":
                                google.maps.event.addDomListener(window, 'load', initialize(data, ID));
                                $(".dopbsp-loader").hide();
                                var rt = $(document).find('#rtotal_res').val();
                                $('.rtotal').text(rt);
                                $('.rtotal').parent().show();
                                break;
                            default:
                                $(".dopbsp-loader").hide();

                                $("#DOPBSPSearch-results" + ID).html(data);
                                var rt = $(document).find('#rtotal_res').val();
                                $('.rtotal').text(rt);
                                $('.rtotal').parent().show();

                        }
                    });
                });
                var $checkIn = $(".nicdark_advanced_search input[name='date_from']").val(),
                        $checkOut = $(".nicdark_advanced_search input[name='date_to']").val(),
                        $startHour = $("#DOPBSPSearch-start-hour" + ID).val(),
                        $endHour = $("#DOPBSPSearch-end-hour" + ID).val(),
                        $address = $("#nicdark_autocomplete").val(),
                        $posttype = $("input[name='posttype']").val(),
                        checkIn = $checkIn === undefined ? "" : $checkIn,
                        address = $address === undefined ? "" : $address,
                        posttype = $posttype === undefined ? "" : $posttype,
                        checkOut = $checkOut === undefined || $checkOut === "" ? checkIn : $checkOut,
                        startHour = $startHour === undefined ? "" : $startHour,
                        endHour = $endHour === undefined || $endHour === "" ? startHour : $endHour;

                page = 1;

                if (ajaxRequestInProgress !== undefined) {
                    ajaxRequestInProgress.abort();
                }
                $("#DOPBSPSearch-results-loader" + ID).removeClass("DOPBSPSearch-hidden");
                $("#DOPBSPSearch-results" + ID).html("");

                ajaxRequestInProgress = $.post(ajaxURL, {action: "custom_dopbsp_search_results_get_link",
                    dopbsp_frontend_ajax_request: true,
                    id: ID,
                    check_in: checkIn,
                    check_out: checkOut,
                    start_hour: startHour,
                    end_hour: endHour,
                    address: address,
                    posttype: posttype,
                    no_items: $("#DOPBSPSearch-no-items" + ID).val() === undefined ? "" : $("#DOPBSPSearch-no-items" + ID).val(),
                    price_min: '',
                    price_max: '',
                    sort_by: $("#DOPBSPSearch-sort-by" + ID).val() === undefined ? "price" : $("#DOPBSPSearch-sort-by" + ID).val(),
                    sort_direction: $("#DOPBSPSearch-sort-direction-value" + ID).val() === undefined ? "ASC" : $("#DOPBSPSearch-sort-direction-value" + ID).val(),
                    view: '<?php echo $viewss ?>',
                    results: 10,
                    page: page}, function (data) {
                    data = $.trim(data);
                    switch ('<?php echo $viewss ?>') {
                        case "map":
                            google.maps.event.addDomListener(window, 'load', initialize(data, ID));
                            $('.nicdark_preloader').hide();
                            $(".dopbsp-loader").hide();
                            var rt = $(document).find('#rtotal_res').val();
                            $('.rtotal').text(rt);
                            $('.rtotal').parent().show();
                            break;
                        default:
                            $("#DOPBSPSearch-results" + ID).html(data);
                            $('.nicdark_preloader').hide();
                            $(".dopbsp-loader").hide();
                            var rt = $(document).find('#rtotal_res').val();
                            $('.rtotal').text(rt);
                            $('.rtotal').parent().show();
                    }
                });
                // 

                $(".ui-tabs > div:nth-child(2) .nicdark_btn_filter").bind("click", function (e) {
                    e.preventDefault();
                    $(".dopbsp-loader").show();
                    $('.rtotal').parent().hide();
                    var view = $('.DOPBSPSearch-view li.dopbsp-selected').attr('data-value');
                    var ajaxRequestInProgress = undefined;
                    var p1 = $(".ui-tabs > div:nth-child(2) input[name='price_from_to']").val().replace(/\$\ /g, "");
                    var p2 = p1.split("-");
                    var $checkIn = $(".ui-tabs > div:nth-child(2) input[name='date_from']").val(),
                            $checkOut = $(".ui-tabs > div:nth-child(2) input[name='date_to']").val(),
                            $startHour = $("#DOPBSPSearch-start-hour" + ID).val(),
                            $endHour = $("#DOPBSPSearch-end-hour" + ID).val(),
                            $address = $("#nicdark_autocomplete").val(),
                            $posttype = $("input[name='posttype']").val(),
                            checkIn = $checkIn === undefined ? "" : $checkIn,
                            address = $address === undefined ? "" : $address,
                            posttype = $posttype === undefined ? "" : $posttype,
                            checkOut = $checkOut === undefined || $checkOut === "" ? checkIn : $checkOut,
                            startHour = $startHour === undefined ? "" : $startHour,
                            endHour = $endHour === undefined || $endHour === "" ? startHour : $endHour;

                    page = 1;

                    if (ajaxRequestInProgress !== undefined) {
                        ajaxRequestInProgress.abort();
                    }
                    $("#DOPBSPSearch-results-loader" + ID).removeClass("DOPBSPSearch-hidden");
                    $("#DOPBSPSearch-results" + ID).html("");

                    ajaxRequestInProgress = $.post(ajaxURL, {action: "custom_dopbsp_search_results_get_link",
                        dopbsp_frontend_ajax_request: true,
                        id: ID,
                        check_in: checkIn,
                        check_out: checkOut,
                        start_hour: startHour,
                        end_hour: endHour,
                        address: address,
                        posttype: posttype,
                        no_items: $("#DOPBSPSearch-no-items" + ID).val() === undefined ? "" : $("#DOPBSPSearch-no-items" + ID).val(),
                        price_min: p2[0],
                        price_max: p2[1],
                        sort_by: $("#DOPBSPSearch-sort-by" + ID).val() === undefined ? "price" : $("#DOPBSPSearch-sort-by" + ID).val(),
                        sort_direction: $("#DOPBSPSearch-sort-direction-value" + ID).val() === undefined ? "ASC" : $("#DOPBSPSearch-sort-direction-value" + ID).val(),
                        view: view,
                        results: 10,
                        page: page}, function (data) {
                        data = $.trim(data);

                        switch (view) {
                            case "map":
                                google.maps.event.addDomListener(window, 'load', initialize(data, ID));
                                $(".dopbsp-loader").hide();
                                var rt = $(document).find('#rtotal_res').val();
                                $('.rtotal').text(rt);
                                $('.rtotal').parent().show();
                                break;
                            default:
                                $(".dopbsp-loader").hide();
                                $("#DOPBSPSearch-results" + ID).html(data);
                                var rt = $(document).find('#rtotal_res').val();
                                $('.rtotal').text(rt);
                                $('.rtotal').parent().show();
                        }
                    });
                });
                $(".ui-tabs > div:nth-child(3) .nicdark_btn_filter").bind("click", function (e) {
                    e.preventDefault();
                    $(".dopbsp-loader").show();
                    $('.rtotal').parent().hide();
                    var view = $('.DOPBSPSearch-view li.dopbsp-selected').attr('data-value');
                    var ID = '<?php echo $id; ?>';
                    var ajaxRequestInProgress = undefined;
                    var p1 = $(".ui-tabs > div:nth-child(3) input[name='price_from_to']").val().replace(/\$\ /g, "");
                    var p2 = p1.split("-");
                    var $checkIn = $(".ui-tabs > div:nth-child(3) input[name=date_from]").val(),
                            $checkOut = $(".ui-tabs > div:nth-child(3) input[name=date_to]").val(),
                            $startHour = $("#DOPBSPSearch-start-hour" + ID).val(),
                            $endHour = $("#DOPBSPSearch-end-hour" + ID).val(),
                            $address = $(".ui-tabs > div:nth-child(3) select[name='destination-package']").val(),
                            $typology = $(".ui-tabs > div:nth-child(3) select[name='typology-package']").val(),
                            $duration = $(".ui-tabs > div:nth-child(3) select[name='duration-package']").val(),
                            $posttype = $("input[name='posttype']").val(),
                            $person_package = $(".ui-tabs > div:nth-child(3) select[name='person-package']").val(),
                            checkIn = $checkIn === undefined ? "" : $checkIn,
                            address = $address === undefined ? "" : $address,
                            typology = $typology === undefined ? "" : $typology,
                            duration = $duration === undefined ? "" : $duration,
                            posttype = $posttype === undefined ? "" : $posttype,
                            person_package = $person_package === undefined ? "" : $person_package,
                            checkOut = $checkOut === undefined || $checkOut === "" ? checkIn : $checkOut,
                            startHour = $startHour === undefined ? "" : $startHour,
                            endHour = $endHour === undefined || $endHour === "" ? startHour : $endHour;

                    page = 1;

                    if (ajaxRequestInProgress !== undefined) {
                        ajaxRequestInProgress.abort();
                    }
                    $("#DOPBSPSearch-results-loader" + ID).removeClass("DOPBSPSearch-hidden");
                    $("#DOPBSPSearch-results" + ID).html("");

                    ajaxRequestInProgress = $.post(ajaxURL, {action: "custom_dopbsp_search_results_get_link",
                        dopbsp_frontend_ajax_request: true,
                        id: ID,
                        check_in: checkIn,
                        check_out: checkOut,
                        start_hour: startHour,
                        end_hour: endHour,
                        address: address,
                        typology: typology,
                        duration: duration,
                        posttype: posttype,
                        person_package: person_package,
                        no_items: $("#DOPBSPSearch-no-items" + ID).val() === undefined ? "" : $("#DOPBSPSearch-no-items" + ID).val(),
                        price_min: p2[0],
                        price_max: p2[1],
                        sort_by: $("#DOPBSPSearch-sort-by" + ID).val() === undefined ? "price" : $("#DOPBSPSearch-sort-by" + ID).val(),
                        sort_direction: $("#DOPBSPSearch-sort-direction-value" + ID).val() === undefined ? "ASC" : $("#DOPBSPSearch-sort-direction-value" + ID).val(),
                        view: view,
                        results: 10,
                        page: page}, function (data) {
                        data = $.trim(data);

                        switch (view) {
                            case "map":
                                google.maps.event.addDomListener(window, 'load', initialize(data, ID));
                                $(".dopbsp-loader").hide();
                                var rt = $(document).find('#rtotal_res').val();
                                $('.rtotal').text(rt);
                                $('.rtotal').parent().show();
                                break;
                            default:
                                $(".dopbsp-loader").hide();
                                $("#DOPBSPSearch-results" + ID).html(data);
                                var rt = $(document).find('#rtotal_res').val();
                                $('.rtotal').text(rt);
                                $('.rtotal').parent().show();
                        }
                    });
                });
                // 
                $(document).on("click", ".nicdark_pagination .nicdark_btn", function (e) {
                    e.preventDefault();
                    $(".dopbsp-loader").show();
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');
                    var rr1 = $(this).text();
                    $('.rtotal').parent().hide();
                    var rr = parseInt($('.wpb_tabs_nav li.ui-tabs-active').index()) + 1;
                    // console.log('rr==========='+rr);
                    var view = $('.DOPBSPSearch-view li.dopbsp-selected').attr('data-value');
                    var ajaxRequestInProgress = undefined;
                    var p1 = $(".ui-tabs > div[aria-labelledby='ui-id-" + rr + "'] input[name='price_from_to']").val();
                    // console.log('p1==========='+p1);
                    var p2 = p1.split("-");
                    var $checkIn = $(".ui-tabs > div[aria-labelledby='ui-id-" + rr + "'] input[name='date_from']").val(),
                            $checkOut = $(".ui-tabs > div[aria-labelledby='ui-id-" + rr + "'] input[name='date_to']").val(),
                            $startHour = $("#DOPBSPSearch-start-hour" + ID).val(),
                            $endHour = $("#DOPBSPSearch-end-hour" + ID).val(),
                            $address = $("select[name='destination-package']").val(),
                            $posttype = $("input[name='posttype']").val(),
                            checkIn = $checkIn === undefined ? "" : $checkIn,
                            address = $address === undefined ? "" : $address,
                            posttype = $posttype === undefined ? "" : $posttype,
                            checkOut = $checkOut === undefined || $checkOut === "" ? checkIn : $checkOut,
                            startHour = $startHour === undefined ? "" : $startHour,
                            endHour = $endHour === undefined || $endHour === "" ? startHour : $endHour;

                    page = rr1;

                    if (ajaxRequestInProgress !== undefined) {
                        ajaxRequestInProgress.abort();
                    }
                    $("#DOPBSPSearch-results-loader" + ID).removeClass("DOPBSPSearch-hidden");
                    $("#DOPBSPSearch-results" + ID).html("");

                    ajaxRequestInProgress = $.post(ajaxURL, {action: "custom_dopbsp_search_results_get_link",
                        dopbsp_frontend_ajax_request: true,
                        id: ID,
                        check_in: checkIn,
                        check_out: checkOut,
                        start_hour: startHour,
                        end_hour: endHour,
                        address: address,
                        posttype: posttype,
                        no_items: $("#DOPBSPSearch-no-items" + ID).val() === undefined ? "" : $("#DOPBSPSearch-no-items" + ID).val(),
                        price_min: p2[0],
                        price_max: p2[1],
                        sort_by: $("#DOPBSPSearch-sort-by" + ID).val() === undefined ? "price" : $("#DOPBSPSearch-sort-by" + ID).val(),
                        sort_direction: $("#DOPBSPSearch-sort-direction-value" + ID).val() === undefined ? "ASC" : $("#DOPBSPSearch-sort-direction-value" + ID).val(),
                        view: view,
                        results: 10,
                        page: page}, function (data) {
                        data = $.trim(data);

                        switch (view) {
                            case "map":
                                google.maps.event.addDomListener(window, 'load', initialize(data, ID));
                                $(".dopbsp-loader").hide();
                                var rt = $(document).find('#rtotal_res').val();
                                $('.rtotal').text(rt);
                                $('.rtotal').parent().show();
                                break;
                            default:
                                $(".dopbsp-loader").hide();
                                $("#DOPBSPSearch-results" + ID).html(data);
                                var rt = $(document).find('#rtotal_res').val();
                                $('.rtotal').text(rt);
                                $('.rtotal').parent().show();
                        }
                    });
                });
                // 
                var max_r = parseInt('<?php echo $max_r; ?>');
                $(".nicdark_slider_range").slider({
                    range: true,
                    min: 0,
                    max: max_r,
                    values: [0, max_r],
                    slide: function (event, ui) {
                        $(".nicdark_slider_amount").val("$ " + ui.values[ 0 ] + " - $ " + ui.values[ 1 ]);
                    }
                });
                $(".nicdark_slider_amount").val("$ 0" + " - $ " + max_r);
            });
        })(jQuery);
        function initialize(data, ID) {
            var $ = jQuery;
            var HTML = new Array();
            HTML.push('<div id="DOPBSPSearch-results-map' + ID + '" class="dopbsp-map"></div>');
            HTML.push(data.split(';;;;;')[1]);
            $('#DOPBSPSearch-results-loader' + ID).addClass('DOPBSPSearch-hidden');
            $('#DOPBSPSearch-results' + ID).html(HTML.join(''));
            //console.log(data.split(';;;;;')[0]);
            if (data.split(';;;;;')[0] == '[]') {
                //console.log('--in--');
                HTML = [];
                $('#DOPBSPSearch-results' + ID).empty();
                if ('<?php echo $lang; ?>' == 'de-DE') {
                    $('#DOPBSPSearch-results' + ID).append('<p style="margin-bottom: 30px; margin-top: 15px;">Leider haben wir keine freien Ferienhäuser gefunden. Bitte passen Sie die Filteroptionen an.<p>');
                }
                else {
                    $('#DOPBSPSearch-results' + ID).append('<p style="margin-bottom: 30px; margin-top: 15px;">Sorry, no results found. Please adjust your search and try again.<p>');
                }
                $(".dopbsp-loader").hide();
                $('.rtotal').text('0');
                $('.rtotal').parent().show();
                var st = '<style>#DOPBSPSearch-results-map' + ID + '{display:none;}</style>';
                $('#DOPBSPSearch-results' + ID).append(st);
            }
            locations = JSON.parse(data.split(';;;;;')[0]);
            coordinates1 = JSON.parse(locations[0]['coordinates']);

            var myOptions = {
                center: new google.maps.LatLng(coordinates1[0], coordinates1[1]),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoom: 9
            };
            var map = new google.maps.Map(document.getElementById('DOPBSPSearch-results-map' + ID), myOptions);

            var bounds = new google.maps.LatLngBounds(),
                    coordinates,
                    icon = new google.maps.MarkerImage('<?php echo site_url(); ?>/wp-content/plugins/dopbsp/templates/default/images/marker.png',
                            new google.maps.Size(36, 52),
                            new google.maps.Point(1, 0),
                            new google.maps.Point(18, 52)),
                    i,
                    locations = locations,
                    markers = new Array(),
                    position = new Array(),
                    shadow = new google.maps.MarkerImage(pluginURL + 'templates/default/images/marker.png',
                            new google.maps.Size(36, 52),
                            new google.maps.Point(1, 0),
                            new google.maps.Point(18, 52)),
                    shape = {coord: [0, 0, 36, 0, 36, 52, 0, 52],
                        type: 'poly'};

            for (i = 0; i < locations.length; i++) {
                coordinates = JSON.parse(locations[i]['coordinates']);
                position = new google.maps.LatLng(coordinates[0], coordinates[1]);
                bounds.extend(position);

                markers[i] = new google.maps.Marker({animation: null,
                    clickable: true,
                    draggable: false,
                    icon: icon,
                    map: map,
                    position: position,
                    shadow: shadow,
                    shape: shape});

                markers[i].info = new InfoBox({alignBottom: true,
                    boxClass: 'dopbsp-infobox',
                    closeBoxMargin: '0px',
                    closeBoxURL: pluginURL + 'templates/default/images/close.png',
                    disableAutoPan: false,
                    content: methods_map_POST(locations[i]['calendars'], i, ID),
                    isHidden: false,
                    infoBoxClearance: new google.maps.Size(20, 20),
                    pixelOffset: new google.maps.Size(-190, -60),
                    position: position});

                markers[i].index = i;

                google.maps.event.addListener(markers[i], 'click', function () {
                    var index = this.index;

                    for (i = 0; i < locations.length; i++) {
                        markers[i].info.close();
                    }
                    this.info.open(map, this);

                    setTimeout(function () {
                        methods_events(index, ID);
                    }, 100);
                });
            }
        }
        function methods_map_POST(calendars, index, ID) {
            var HTML = new Array(),
                    i;

            HTML.push('<ul class="dopbsp-locations" id="DOPBSPSearch-locations-' + ID + '-' + index + '">');

            for (i = 0; i < calendars.length; i++) {
                HTML.push('<li>');
                HTML.push('     <div class="dopbsp-image">');
                HTML.push('         <a href="' + calendars[i]['link'] + '" target="_parent" style="background-image: url(' + calendars[i]['image'] + ');">');
                HTML.push('             <img src="' + calendars[i]['image'] + '" alt="' + calendars[i]['name'] + '" title="' + calendars[i]['name'] + '" />');
                HTML.push('         </a>');
                HTML.push('     </div>');
                HTML.push('     <div class="dopbsp-content">');
                HTML.push('         <h3>');
                HTML.push('             <a href="' + calendars[i]['link'] + '" target="_parent">' + calendars[i]['name'] + '</a>');
                HTML.push('         </h3>');
                HTML.push('         <div class="dopbsp-address">' + calendars[i]['text'] + '</div>');
                HTML.push('         <div class="dopbsp-price-wrapper">' + calendars[i]['price'] + '</div>');
                HTML.push('         <a class="r_details_btn" href="' + calendars[i]['link'] + '">DETAILS</a>');
                HTML.push('     </div>');
                HTML.push('</li>');
            }
            HTML.push('</ul>');

            if (calendars.length > 1) {
                HTML.push('<div class="dopbsp-navigation" id="DOPBSPSearch-locations-navigation-' + ID + '-' + index + '">');
                HTML.push('     <a href="javascript:void(0)" class="dopbsp-prev dopbsp-disabled"></a>');
                HTML.push('     <a href="javascript:void(0)" class="dopbsp-next"></a>');
                HTML.push('</div>');
            }

            return HTML.join('');
        }
        function methods_events(i, ID) {
            $('#DOPBSPSearch-locations-navigation-' + ID + '-' + i + ' .dopbsp-prev').unbind('click');
            $('#DOPBSPSearch-locations-navigation-' + ID + '-' + i + ' .dopbsp-prev').bind('click', function () {
                var $this = $(this),
                        id = $this.parent().attr('id').split('DOPBSPSearch-locations-navigation-')[1],
                        $li = $('#DOPBSPSearch-locations-' + id + ' li:first-child');

                if (!$this.hasClass('dopbsp-disabled')) {
                    $('#DOPBSPSearch-locations-navigation-' + ID + '-' + i + ' .dopbsp-next').removeClass('dopbsp-disabled');
                    $li.css('margin-top', parseInt($li.css('margin-top')) + ($li.height() + parseInt($li.css('margin-bottom'))));

                    setTimeout(function () {
                        if (parseInt($li.css('margin-top')) >= 0) {
                            $this.addClass('dopbsp-disabled');
                        }
                    }, 150);
                }
            });

            $('#DOPBSPSearch-locations-navigation-' + ID + '-' + i + ' .dopbsp-next').unbind('click');
            $('#DOPBSPSearch-locations-navigation-' + ID + '-' + i + ' .dopbsp-next').bind('click', function () {
                var $this = $(this),
                        id = $this.parent().attr('id').split('DOPBSPSearch-locations-navigation-')[1],
                        $li = $('#DOPBSPSearch-locations-' + id + ' li:first-child'),
                        locations = methods_map.vars.locations;

                if (!$this.hasClass('dopbsp-disabled')) {
                    $('#DOPBSPSearch-locations-navigation-' + ID + '-' + i + ' .dopbsp-prev').removeClass('dopbsp-disabled');
                    $li.css('margin-top', parseInt($li.css('margin-top')) - ($li.height() + parseInt($li.css('margin-bottom'))));

                    setTimeout(function () {
                        if (-1 * parseInt($li.css('margin-top')) >= ($li.height() + parseInt($li.css('margin-bottom'))) * (locations.length - 1)) {
                            $this.addClass('dopbsp-disabled');
                        }
                    }, 150);
                }
            });
        }
    </script>
    <div>
        <ul class="DOPBSPSearch-view"> 
            <?php
            if ($lang == 'de-DE') {
                echo '<strong style="float: left;">Wir haben <span class="rtotal">0</span> Ferienhäuser gefunden.</strong>';
            } else {
                echo '<strong style="float: left;">We have found <span class="rtotal">0</span> vacation homes.</strong>';
            }
            ?>

            <li class="dopbsp-view-map dopbsp-selected" id="DOPBSPSearch-view-map<?php echo $id; ?>" data-value="map">      
            </li>
            <li class="dopbsp-view-grid " id="DOPBSPSearch-view-grid<?php echo $id; ?>" data-value="grid">     
            </li> 
            <li class="dopbsp-view-list" id="DOPBSPSearch-view-list<?php echo $id; ?>" data-value="list">     
            </li> 
        </ul>
    </div>
    <div class="dopbsp-loader"></div>
    <div id="DOPBSPSearch-results<?php echo $id; ?>" class="DOPBSPSearch-results"></div>
    <?php
    return ob_get_clean();
}

add_action('wp_ajax_custom_dopbsp_search_results_get_link', 'prefix_ajax_custom_dopbsp_search_results_get_link');
add_action('wp_ajax_nopriv_custom_dopbsp_search_results_get_link', 'prefix_ajax_custom_dopbsp_search_results_get_link');

function prefix_ajax_custom_dopbsp_search_results_get_link() {
    global $wpdb;
    global $DOPBSP;

    // ----

    $paged = $_POST['page'];
    $price_min = str_replace('$', '', $_POST['price_min']);
    $price_max = str_replace('$', '', $_POST['price_max']);
    if ($price_min == '') {
        $price_min = 100;
    }
    if ($price_max == '') {
        $price_max = 10000;
    }
    $view = $_POST['view'];

    $qnt_posts_per_page = 15;

    //START PASS ALL PARAMETER
    if (isset($_POST['posttype'])) {
        $posttype = $_POST['posttype'];
    } else {
        $posttype = '';
    }
    if (isset($_POST['keyword'])) {
        $keyword = $_POST['keyword'];
    } else {
        $keyword = '';
    }

    //all taxonomies
    $taxonomy_1 = 'destination-package';
    $taxonomy_2 = 'typology-package';
    $taxonomy_3 = 'duration-package';
    $taxonomy_4 = 'person-package';

    //all terms taxonomies
    $term_taxonomy_1 = $_POST['address'];
    if (isset($_POST['typology'])) {
        $term_taxonomy_2 = $_POST['typology'];
    } else {
        $term_taxonomy_2 = '';
    }
    if (isset($_POST['duration'])) {
        $term_taxonomy_3 = $_POST['duration'];
    } else {
        $term_taxonomy_3 = '';
    }
    if (isset($_POST['person_package'])) {
        $term_taxonomy_4 = $_POST['person_package'];
    } else {
        $term_taxonomy_4 = '';
    }

    //price_from_to and split values
    $price_from = $price_min;
    $price_to = $price_max;

    if ($_POST['check_in'] == '') {
        $date_from = '';
    } else {
        $date_from = date("Y-m-d", strtotime($_POST['check_in']));
    }

    //date to
    $l_date = '';
    if ($_POST['check_out'] == '') {
        $date_to = '';
    } else {
        $date_to = date("Y-m-d", strtotime($_POST['check_out']));
    }
    // ----
    $all_a_ti = array();
    if ($date_from != '' && $date_to != '') {
        $tbl7 = $wpdb->prefix . 'dopbsp_days_available';
        $res7 = $wpdb->get_results("SELECT data FROM $tbl7 WHERE day >= '" . $date_from . "' AND day <= '" . $date_to . "'");
        $ar1 = array();
        if ($res7) {
            foreach ($res7 as $key => $value) {
                if ($ar1) {
                    $v = $value->data;
                    $ar2 = explode(',', $v);
                    $ar1 = array_intersect($ar1, $ar2);
                } else {
                    $v = $value->data;
                    $ar1 = explode(',', $v);
                }
            }
        }
        if (($key = array_search('0', $ar1)) !== false) {
            unset($ar1[$key]);
        }
        $ids = join(',', $ar1);
        $tbl4 = $wpdb->prefix . 'dopbsp_calendars';
        $tbl5 = $wpdb->prefix . 'dopbsp_days';
        $tbl6 = $wpdb->prefix . 'posts';

        $resd = $wpdb->get_results("SELECT DISTINCT(ID) FROM $tbl6 WHERE post_status = 'publish' AND post_type = '" . $posttype . "' AND post_title IN (SELECT DISTINCT(name) FROM $tbl4 WHERE id IN ( $ids ))");

        if ($resd) {
            foreach ($resd as $k2 => $v2) {
                array_push($all_a_ti, $v2->ID);
            }
        }
    } else {
        $resd = true;
    }
    //check qnt taxonimies used in the search
    if (isset($_POST['qnt-taxonomies'])) {
        $qnt_taxonomies = $_POST['qnt-taxonomies'];
    } else {
        $qnt_taxonomies = 0;
    }
    //END PASS ALL PARAMETERS
    //PREPARE THE ARGS FOR THE WP QUERY
    if ($resd) {
        $args = array(
            'post_type' => $posttype,
            's' => $keyword,
            'post__in' => $all_a_ti,
            //pagination
            'posts_per_page' => $qnt_posts_per_page,
            'paged' => $paged,
            'meta_query' => array(
                array(
                    'key' => 'metabox_package_price',
                    'value' => array($price_from, $price_to), /*                     * must works with this format YYYYMMDD */
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN'
                )
            ),
            '' . $taxonomy_1 . '' => '' . $term_taxonomy_1 . '',
            '' . $taxonomy_2 . '' => '' . $term_taxonomy_2 . '',
            '' . $taxonomy_3 . '' => '' . $term_taxonomy_3 . '',
            '' . $taxonomy_4 . '' => '' . $term_taxonomy_4 . ''
        );

        if ($view == 'map') {
            $args['posts_per_page'] = -1;
        }
    } else {
        $args = array();
    }
    //END ARGS FOR WP QUERY

    $the_query = new WP_Query($args);

    $qnt_results_posts = $the_query->found_posts;
    $qnt_pagination = ceil($qnt_results_posts / $qnt_posts_per_page);
    switch ($view) {
        case 'grid':
            custom_frontend_search_results_grid_link(array('calendars' => $the_query,
                'page' => $qnt_pagination,
                'results' => $qnt_results_posts));
            break;
        case 'map':
            $qnt_pagination = ceil($qnt_results_posts / -1);
            locations_link($the_query, $qnt_pagination, $qnt_results_posts);
            break;
        default:
            custom_frontend_search_results_list_link(array('calendars' => $the_query,
                'page' => $qnt_pagination,
                'results' => $qnt_results_posts));
    }
    die();
}

function custom_frontend_search_results_grid_link($args = array()) {
    global $DOPBSP;

    $calendars = $args['calendars'];
    $page = $args['page'];
    $results = $args['results'];
    $c = 0;
    ?>
    <ul class="dopbsp-grid">
        <?php
        if ($calendars->have_posts()) {
            while ($calendars->have_posts()) : $calendars->the_post();
                global $post;
                $id = $post->ID;
                //echo $id;
                $c++;
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'medium');
                ?>
                <li style="max-width: 375px; width: 100%; display:inline-block;" >
                    <div class="grid percentage nicdark_masonry_item nicdark_sizing">
                        <div class="nicdark_archive1 nicdark_bg_white nicdark_border_grey nicdark_sizing ">
                            <!--start image-->
                            <div class="nicdark_focus nicdark_relative nicdark_fadeinout nicdark_overflow">    
                                <?php if (!$image[0]) { ?>
                                    <img class="nicdark_focus nicdark_zoom_image" src="<?php echo site_url(); ?>/wp-content/uploads/2016/01/love-travel-12-1920-300x169.jpg" alt="<?php echo $post->name; ?>" title="<?php echo $post->name; ?>">
                                <?php } else { ?>
                                    <img src="<?php echo $image[0]; ?>" alt="<?php echo $post->name; ?>" title="<?php echo $post->name; ?>" class="nicdark_focus nicdark_zoom_image">
                                <?php } ?>
                                <!--price-->
                                <div class="nicdark_fadeout nicdark_absolute nicdark_height100percentage nicdark_width_percentage100" style="top:0;">  
                                    <a class="nicdark_btn nicdark_bg_white custom_bg left grey medium" href="">
                                        <?php echo get_post_meta($id, 'metabox_package_currency', true); ?>
                                        <?php echo get_post_meta($id, 'metabox_package_price', true); ?>
                                    </a>
                                </div>
                                <!--end price-->
                                <!--start content-->
                                <div class="nicdark_fadein nicdark_filter greydark nicdark_absolute nicdark_height100percentage nicdark_width_percentage100"  style="top: 0px;">
                                    <div class="nicdark_absolute nicdark_display_table nicdark_height100percentage nicdark_width_percentage100">
                                        <div class="nicdark_cell nicdark_vertical_middle">
                                            <a class="nicdark_btn nicdark_border_white white medium" href="<?php echo get_permalink($calendar->post_id); ?>">DETAILS</a>
                                        </div>   
                                    </div>   
                                </div>
                                <!--end content-->
                            </div>
                            <!--end image-->
                            <div class="nicdark_textevidence nicdark_bg_greydark">
                                <h4 class="white nicdark_margin20" style="text-transform: uppercase;">
                                    <?php echo $post->post_title; ?>
                                </h4>
                            </div>
                            <div class="nicdark_margin20">
                                <p>
                                    <strong>
                                    </strong>
                                </p>
                                <p>
                                    <?php
                                    echo get_post_meta($id, 'metabox_package_excerpt', true);
                                    ?>
                                </p>
                                <div class="nicdark_space20"></div>
                                <a class="nicdark_border_grey grey nicdark_btn nicdark_outline medium " href="<?php echo get_permalink($id); ?>" style="margin-bottom: 18px;">DETAILS</a>
                            </div>
                        </div>
                    </div>
                </li>
                <?php
            endwhile;
        } else {
            $lang = get_bloginfo("language");
            if ($lang == 'de-DE') {
                echo '<p style="margin-bottom: 30px;">Leider haben wir keine freien Ferienhäuser gefunden. Bitte passen Sie die Filteroptionen an.<p>';
            } else {
                echo '<p style="margin-bottom: 30px;">Sorry, no results found. Please adjust your search and try again.<p>';
            }
            echo '<input type="hidden" id="rtotal_res" value="0" />';
        }
        wp_reset_postdata();
        ?>
    </ul>
    <?php
    pagination_link(array('no' => $c,
        'page' => $page,
        'results' => $results));
}

function custom_frontend_search_results_list_link($args = array()) {
    global $DOPBSP;

    $calendars = $args['calendars'];
    $page = $args['page'];
    $results = $args['results'];
    $c = 0;
    ?>
    <ul class="dopbsp-grid">

        <?php
        if ($calendars->have_posts()) {
            while ($calendars->have_posts()) : $calendars->the_post();
                global $post;
                $id = $post->ID;
                $c++;
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'medium');
                ?>
                <li>
                    <div class="nicdark_focus nicdark_displaynone_desktop nicdark_displayblock_iphonepotr nicdark_displayblock_iphoneland nicdark_displayblock_ipadpotr nicdark_displayblock_ipadland">
                        <!--start image-->
                        <div class="nicdark_focus nicdark_relative nicdark_fadeinout nicdark_overflow">    
                            <img src="<?php echo $image[0]; ?>" class="nicdark_focus nicdark_zoom_image" alt="">
                            <!--price-->
                            <div class="nicdark_fadeout nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">  
                                <a class="nicdark_btn nicdark_bg_blue left white medium" href="http://www.nicdarkthemes.com/themes/love-travel/wp/demo-travel/packages/polynesia/">
                                    <?php echo get_post_meta($id, 'metabox_package_currency', true); ?>
                                    <?php echo get_post_meta($id, 'metabox_package_price', true); ?>
                                </a>
                            </div>
                            <!--end price-->
                            <!--start content-->
                            <div class="nicdark_fadein nicdark_filter greydark nicdark_absolute nicdark_height100percentage nicdark_width_percentage100">
                                <div class="nicdark_absolute nicdark_display_table nicdark_height100percentage nicdark_width_percentage100">
                                    <div class="nicdark_cell nicdark_vertical_middle">
                                        <a class="nicdark_btn nicdark_border_white white medium" href="<?php echo get_permalink($id); ?>">BOOK</a>
                                    </div>   
                                </div>   
                            </div>
                            <!--end content-->
                        </div>
                        <!--end image-->
                    </div>
                    <div class="grid grid_12 percentage nicdark_masonry_item nicdark_padding10 nicdark_sizing" id="<?php echo $calendar->post_id; ?>" style="">
                        <div class="nicdark_focus nicdark_bg_blue nicdark_relative">
                            <div class="nicdark_displaynone_responsive nicdark_width_percentage30 nicdark_focus">
                                <div class="nicdark_space1"></div>
                            </div>
                            <div class="nicdark_displaynone_responsive nicdark_overflow nicdark_bg_greydark nicdark_width_percentage30 nicdark_absolute_floatnone nicdark_height100percentage nicdark_focus" style="background-image:url(<?php echo $image[0]; ?>); background-size:cover; background-position:center center;">
                            </div>
                            <div class="nicdark_width100_responsive nicdark_width_percentage50 nicdark_focus nicdark_bg_white nicdark_border_grey nicdark_sizing">
                                <div class="nicdark_textevidence nicdark_bg_grey nicdark_borderbottom_grey">
                                    <h4 style="margin-bottom: 20px;" class="grey nicdark_margin20"><?php echo $post->post_title; ?></h4>
                                </div>
                                <div class="nicdark_margin20" style="margin-bottom: 20px;">
                                    <p>
                                        <strong>
                                        </strong>
                                    </p>
                                    <p>
                                        <?php
                                        echo get_post_meta($id, 'metabox_package_excerpt', true);
                                        ?>
                                    </p>
                                    <div class="nicdark_space20"></div>
                                    <a class="nicdark_bg_grey_hover nicdark_tooltip nicdark_transition nicdark_btn_icon nicdark_border_grey small grey nicdark_margin05 nicdark_marginleft0" title=" Asia "><i class="icon-direction"></i></a>
                                    <a class="nicdark_bg_grey_hover nicdark_tooltip nicdark_transition nicdark_btn_icon nicdark_border_grey small grey nicdark_margin05" title=" Luxury "><i class="icon-tree-1"></i></a>
                                    <a class="nicdark_bg_grey_hover nicdark_tooltip nicdark_transition nicdark_btn_icon nicdark_border_grey small grey nicdark_margin05" title=" 4 People "><i class="icon-calendar-2"></i></a>
                                    <a class="nicdark_bg_grey_hover nicdark_tooltip nicdark_transition nicdark_btn_icon nicdark_border_grey small grey nicdark_margin05" title=" 9 - 12 Days "><i class="icon-users-1"></i></a>
                                </div>
                            </div>
                            <div class="nicdark_displaynone_responsive nicdark_width_percentage20 nicdark_height100percentage nicdark_absolute_floatnone right">
                                <div class="nicdark_filter nicdark_display_table nicdark_height100percentage center">

                                    <div class="nicdark_cell nicdark_vertical_middle">
                                        <h1 class="white">
                                            <?php echo get_post_meta($id, 'metabox_package_currency', true); ?>
                                            <?php echo get_post_meta($id, 'metabox_package_price', true); ?>
                                        </h1>
                                        <div class="nicdark_space10"></div>
                                        <!-- <h4 class="white">USD</h4> -->
                                        <div class="nicdark_space20"></div><a class="nicdark_border_white white nicdark_btn nicdark_outline medium " href="<?php echo get_permalink($id); ?>">DETAILS</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <?php
            endwhile;
        }
        else {
            $lang = get_bloginfo("language");
            if ($lang == 'de-DE') {
                echo '<p style="margin-bottom: 30px;">Leider haben wir keine freien Ferienhäuser gefunden. Bitte passen Sie die Filteroptionen an.<p>';
            } else {
                echo '<p style="margin-bottom: 30px;">Sorry, no results found. Please adjust your search and try again.<p>';
            }
            echo '<input type="hidden" id="rtotal_res" value="0" />';
        }
        wp_reset_postdata();
        ?>
    </ul>
    <?php
    pagination_link(array('no' => $c,
        'page' => $page,
        'results' => $results));
}

function pagination_link_map($args = array()) {
    $no = $args['no'];
    $page = $args['page'];
    $results = $args['results'];
    $cur_page = $_POST['page'];
    $html = array();
    
     if ($no > 0) {
        array_push($html, '<hr />');
        array_push($html, '<input type="hidden" id="rtotal_res" value="' . $results . '" />');
    }

    echo implode('', $html);
    
}

function pagination_link($args = array()) {
    $no = $args['no'];
    $page = $args['page'];
    $results = $args['results'];
    $cur_page = $_POST['page'];
    $html = array();
    if ($no > 0) {
        array_push($html, '<hr />');
        array_push($html, '<input type="hidden" id="rtotal_res" value="' . $results . '" />');
        array_push($html, '<div class="nicdark_focus nicdark_pagination center"><div class="nicdark_space30"></div>');

        for ($i = 1; $i <= $page; $i++) {
            if ($i == $cur_page) {
                $cls = 'active';
            } else {
                $cls = '';
            }
            array_push($html, '<div class="nicdark_btn nicdark_margin10 medium nicdark_border_grey center ' . $cls . '">' . $i . '</div>');
        }
        array_push($html, '</div>');
    }

    echo implode('', $html);
}

function locations_link($calendars, $page, $results) {
    global $DOPBSP;
    $i = 0;
    // $calendars1 = array();
    error_reporting(0);
    $locations = array();
    if ($calendars->have_posts()) {
        while ($calendars->have_posts()) : $calendars->the_post();
            global $post;
            $id = $post->ID;
            $i++;
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'medium');

            $calendars1[$i]->image = $image[0];
            $calendars1[$i]->link = get_permalink($id);
            $calendars1[$i]->price = '<span class="dopbsp-price">' . get_post_meta($id, "metabox_package_currency", true) . get_post_meta($id, "metabox_package_price", true) . ' / Week<span>';
            $calendars1[$i]->name = $post->post_title;
            $calendars1[$i]->text = get_post_meta($id, 'metabox_package_excerpt', true);

            /*
             * Construct locations.
             */
            $calendar_found = false;
            $cc = '[' . get_post_meta($id, 'metabox_package_coordinates', true) . ']';
            $calendars1[$i]->coordinates = $cc;
            for ($j = 0; $j < count($locations); $j++) {

                if ($locations[$j]['coordinates'] == $cc) {
                    array_push($locations[$j]['calendars'], $calendars1[$i]);
                    $calendar_found = true;
                }
            }

            if (!$calendar_found) {
                array_push($locations, array('coordinates' => $cc,
                    'calendars' => array(0 => $calendars1[$i])));
            }
        endwhile;
    }
    echo json_encode($locations) . ';;;;;';
    pagination_link_map(array('no' => count($i),
        'page' => $page,
        'results' => $results));
}

add_action('wp_footer', 'change_form_action_link_fn');

function change_form_action_link_fn() {
    $lang = get_bloginfo("language");
    ?>
    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                if ('<?php echo $lang; ?>' == 'de-DE') {
                    var act = $('.nicdark_advanced_search.test').attr('action');
                    var act1 = window.location.href;
                    act1 = act1.replace(/\/$/, '');
                    var act2 = act1 + act;
                    $('.nicdark_advanced_search.test').attr('action', act2);
                }
            });
        })(jQuery);
    </script>
    <?php
}
