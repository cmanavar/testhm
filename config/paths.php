<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Use the DS to separate the directories in other defines
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */
/**
 * The full path to the directory which holds "src", WITHOUT a trailing DS.
 */
define('ROOT', dirname(__DIR__));

/**
 * The actual directory name for the application directory. Normally
 * named 'src'.
 */
define('APP_DIR', 'src');

/**
 * Path to the application's directory.
 */
define('APP', ROOT . DS . APP_DIR . DS);

/**
 * Path to the config directory.
 */
define('CONFIG', ROOT . DS . 'config' . DS);

/**
 * File path to the webroot directory.
 */
define('WWW_ROOT', ROOT . DS . 'webroot' . DS);

/**
 * Path to the tests directory.
 */
define('TESTS', ROOT . DS . 'tests' . DS);

/**
 * Path to the temporary files directory.
 */
define('TMP', ROOT . DS . 'tmp' . DS);

/**
 * Path to the logs directory.
 */
define('LOGS', ROOT . DS . 'logs' . DS);

/**
 * Path to the cache files directory. It can be shared between hosts in a multi-server setup.
 */
define('CACHE', TMP . 'cache' . DS);

/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 * CakePHP should always be installed with composer, so look there.
 */
define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');

/**
 * Path to the cake directory.
 */
$HTTP_HOST = $_SERVER['HTTP_HOST'];
if ($HTTP_HOST == 'localhost') {
    $url = 'http://localhost/hmen/';
} elseif ($HTTP_HOST == 'hmen.in') {
    $url = 'http://hmen.in/admin/';
} else {
    $url = 'http://uncode.in/hmen/';
}
//echo $HTTP_HOST;
//exit;

define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);

define('WEBSITE_PATH', 'http://hmen.in/');
define('WEBSITE_TOC_PATH', 'http://hmen.in/terms-of-use.html');
define('EMAIL_FOOTER_TEXT', '218 Devashish Business Park, Bodakdev, Ahmedabad-380054, India');
define('SOCIAL_MEDIA_LINK_FB', 'https://www.facebook.com/hmenahmedabad/');
define('SOCIAL_MEDIA_LINK_TW', '#');
define('SOCIAL_MEDIA_LINK_IN', 'https://www.instagram.com/hmenahmedabad/');

define('APP_PATH', $url);
define('IMAGE_URL_PATH', $url . 'img/');

define('SERVICE_CATEGORY_ICON_PATH', 'categories/icon/');
define('SERVICE_CATEGORY_BANNER_PATH', 'categories/banner/');
define('SERVICE_CATEGORY_SQUARE_BANNER_PATH', 'categories/square/');

define('SERVICE_BANNER_PATH', 'services/banner/');
define('SERVICE_SQUARE_BANNER_PATH', 'services/square/');
define('SERVICE_ICON_PATH', 'services/icons/');

define('QUETIONS_ICON_PATH', 'questions/icons/');
define('QUETIONS_ICON_URL_PATH', $url . 'img/questions/icons/');

define('BANNER_IMAGE_PATH', 'banners/');

define('USER_PROFILE_PATH', 'profile_picture/');
define('VENDOR_AGREEMENT_PATH', 'vendors/agreements/');
define('VENDOR_IDPROOF_PATH', 'vendors/idproof/');


define('COMPANY_GST_NUMBER', '24DDVPP8257B1Z3');
define('GST_TAX', 5);
define('VISITING_CHARGE', 100.00);
define('REFERRAL_COMISSION', 100.00);
define('GREEN_CASH_REWERDS_AMOUNT', 100.00);
define('EMAIL_FROM_NAME', 'H-MEN');
define('EMAIL_FROM_EMAIL_ADDRESS', 'donotreply@hmen.in');

// Msg Title for Referral
define('MSG_TYPE_REFERRAL', 'REFERRAL');
define('MSG_TYPE_CASHBACK', 'MEMBERSHIP_CASHBACK');
define('MSG_TYPE_OFFER', 'OFFER');
define('MSG_TYPE_ORDER', 'ORDER');
define('MSG_TYPE_OTHER', 'OTHER');
define('MSG_TYPE_GREEN_CASH', 'GREENCASH');
define('MSG_TITLE_REFERRAL', 'Rewarded for refer');
define('MSG_TITLE_ORDER_PLACED', 'Order Placed');
define('MSG_TITLE_ORDER_ASSIGN', 'Order Assigned');
define('MSG_TITLE_ORDER_UPDATED', 'Order Updated');
define('MSG_TITLE_ORDER_CANCELLED', 'Order Cancelled');
define('MSG_TITLE_ORDER_COMPLETED', 'Order Completed');
define('MSG_TITLE_ORDER_CASHBACK', 'Order Cashback');
define('MSG_TITLE_CASHBACK_PLAN', 'Membership Plan Cashback');
define('MSG_TITLE_REFER_MAMBERSHIP', 'Rewarded for refer');
define('MSG_TITLE_CLOSE_LAST_ORDERS', 'CLOSE PREVIOUS ORDERS');


define('RUBIES_PLAN_ID', 1);
define('SAPPHIRES_PLAN_ID', 2);
define('BOOM_AC_PLAN_ID', 3);
define('ZOOM_RO_PLAN_ID', 4);

define('RUBIES_DISCOUNT_RATE', 15);
define('SAPPHIRES_DISCOUNT_RATE', 20);

define('RUBIES_AC_SERVICE_NAME', 'Rubies AC Service');
define('RUBIES_RO_SERVICE_NAME', 'Rubies RO Service');

define('SAPPHIRES_AC_SERVICE_NAME', 'Sapphires AC Service');
define('SAPPHIRES_RO_SERVICE_NAME', 'Sapphires RO Service');

if ($HTTP_HOST == 'localhost') {
    define('BOOM_AC_SERVICE_ID', 11);
    define('BOOM_AC_SERVICE_NAME', 'BOOM AC SERVICE');
    define('BOOM_AC_SERVICE_IMAGE', '201802091504375a7d6.png');
    define('ZOOM_RO_SERVICE_ID', 13);
    define('ZOOM_RO_SERVICE_NAME', 'ZOOM RO SERVICE');
    define('ZOOM_RO_SERVICE_IMAGE', '201802091507205a7d6.png');
} elseif ($HTTP_HOST == 'hmen.in') {
    define('BOOM_AC_SERVICE_ID', 9);
    define('BOOM_AC_SERVICE_NAME', 'BOOM AC SERVICE');
    define('BOOM_AC_SERVICE_IMAGE', '201802181823165a897.png');
    define('ZOOM_RO_SERVICE_ID', 13);
    define('ZOOM_RO_SERVICE_NAME', 'ZOOM RO SERVICE');
    define('ZOOM_RO_SERVICE_IMAGE', '201802181913135a898.png');
} else {
    define('BOOM_AC_SERVICE_ID', 9);
    define('BOOM_AC_SERVICE_NAME', 'BOOM AC SERVICE');
    define('BOOM_AC_SERVICE_IMAGE', '201801301556455a704.png');
    define('ZOOM_RO_SERVICE_ID', 13);
    define('ZOOM_RO_SERVICE_NAME', 'ZOOM RO SERVICE');
    define('ZOOM_RO_SERVICE_IMAGE', '201801301613435a704.png');
}

define('VENDOR_CREDIT_COMMISSION', 20);

define('PAGINATION_LIMIT', 10);

define('COMPANY_NAME_EXCEL', 'H-MEN');
define('ADDRESS_1_EXCEL', '218,Devashish Business park, MochaCafe Building,');
define('ADDRESS_2_EXCEL', 'Opp. S.G Highway Bodakdev, Ahmedabad');
define('MOBILE_PHONE_EXCEL', '7096460460');

// RAZORPAY DETAILS:
define('RAZORPAY_KEY_ID', 'rzp_test_zNXT8SF5EYUl5S');
define('RAZORPAY_KEY_SECRET', 'kpV8ky5ehex7bsX5qhLjb36Y');

