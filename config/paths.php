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
} else {
    $url = 'http://uncode.in/hmen/';
}
//echo $HTTP_HOST;
//exit;

define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);

define('WEBSITE_PATH', 'http://www.hmen.in/');
define('WEBSITE_TOC_PATH', 'http://www.hmen.in/toc.html');
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

define('QUETIONS_ICON_PATH', 'questions/icons/');
define('QUETIONS_ICON_URL_PATH', $url . 'img/questions/icons/');

define('BANNER_IMAGE_PATH', 'banners/');

define('USER_PROFILE_PATH', 'profile_picture/');
define('VENDOR_AGREEMENT_PATH', 'vendors/agreements/');
define('VENDOR_IDPROOF_PATH', 'vendors/idproof/');

define('GST_TAX', 18);
define('REFERRAL_COMISSION', 100.00);
define('EMAIL_FROM_NAME', 'Uncode Lab');
define('EMAIL_FROM_EMAIL_ADDRESS', 'uncodelab@gmail.com');


// Msg Title for Referral
define('MSG_TYPE_REFERRAL', 'REFERRAL');
define('MSG_TYPE_CASHBACK', 'CASHBACK');
define('MSG_TYPE_OFFER', 'OFFER');
define('MSG_TYPE_OTHER', 'OTHER');
define('MSG_TITLE_REFERRAL', 'Rewarded for refer');



define('PAGINATION_LIMIT', 10);


