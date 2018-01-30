<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Core\Plugin;
use Cake\Routing\Router;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass('Route');

Router::scope('/', function ($routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Users', 'action' => 'login']);
    $routes->connect('/webservices/user/create', ['controller' => 'Users', 'action' => 'addappusers']);
    $routes->connect('/webservices/user/login', ['controller' => 'Users', 'action' => 'appuserslogin']);
    $routes->connect('/webservices/otp/resend', ['controller' => 'Users', 'action' => 'resendOtp']);
    $routes->connect('/webservices/resend/verify_email', ['controller' => 'Users', 'action' => 'resendActivationLinks']);
    $routes->connect('/webservices/forgot_password/link', ['controller' => 'Webservices', 'action' => 'forgorPassword']);
    $routes->connect('/reset/password/*', ['controller' => 'Users', 'action' => 'resetpasswords']);//reset/password/
    $routes->connect('/webservices/change/password', ['controller' => 'Webservices', 'action' => 'changePassword']);
    $routes->connect('/webservices/email/activate/*', ['controller' => 'Users', 'action' => 'verifiedemail']);
    $routes->connect('/webservices/phonenumber/activate', ['controller' => 'Users', 'action' => 'verifiedPhone']);
    $routes->connect('/webservices/update/profile', ['controller' => 'Users', 'action' => 'updateProfile']);
    $routes->connect('/webservices/profile/summary', ['controller' => 'Webservices', 'action' => 'orderSummary']);
    $routes->connect('/webservices/wallets/details', ['controller' => 'Webservices', 'action' => 'walletDetails']);
    $routes->connect('/webservices/vendor/login', ['controller' => 'Vendors', 'action' => 'login']); //P
    $routes->connect('/webservices/services/list', ['controller' => 'Webservices', 'action' => 'homepage']);
    $routes->connect('/webservices/services/lists', ['controller' => 'Webservices', 'action' => 'serviceLists']);
    $routes->connect('/webservices/categories/details/*', ['controller' => 'Webservices', 'action' => 'categoryDetails']);
    $routes->connect('/webservices/categories/list', ['controller' => 'Webservices', 'action' => 'categoryList']);
    $routes->connect('/webservices/service/details/*', ['controller' => 'Webservices', 'action' => 'serviceDetails']);
    $routes->connect('/webservices/service/questions/*', ['controller' => 'Webservices', 'action' => 'getquestionArr']);    
    $routes->connect('/webservices/service/reviews/*', ['controller' => 'Webservices', 'action' => 'serviceReviews']);
    $routes->connect('/webservices/service/getsubquestions', ['controller' => 'Webservices', 'action' => 'getServicesSubQuestions']);
    $routes->connect('/webservices/service/review', ['controller' => 'Webservices', 'action' => 'storeReview']);
    $routes->connect('/webservices/help/details', ['controller' => 'Webservices', 'action' => 'helpDetails']);
    $routes->connect('/webservices/cart/create', ['controller' => 'Webservices', 'action' => 'createCart']);
    $routes->connect('/webservices/cart/detail', ['controller' => 'Webservices', 'action' => 'cartDetails']);
    $routes->connect('/webservices/cart/cancel', ['controller' => 'Webservices', 'action' => 'cartClear']);
    $routes->connect('/webservices/cart/product/add', ['controller' => 'Webservices', 'action' => 'addCartProduct']);
    $routes->connect('/webservices/cart/product/remove', ['controller' => 'Webservices', 'action' => 'removeCartProduct']);
    $routes->connect('/webservices/apply/couponcode', ['controller' => 'Webservices', 'action' => 'applyCouponCode']);
    $routes->connect('/webservices/place/cartorder', ['controller' => 'Webservices', 'action' => 'cartOrderPlaced']);
    $routes->connect('/webservices/orders', ['controller' => 'Webservices', 'action' => 'orderLists']);
    $routes->connect('/webservices/order/detail', ['controller' => 'Webservices', 'action' => 'orderDetails']);
    $routes->connect('/webservices/order/query', ['controller' => 'Webservices', 'action' => 'orderQuery']);
    $routes->connect('/webservices/order/update', ['controller' => 'Webservices', 'action' => 'updateOrder']);
    $routes->connect('/webservices/getcount/unreadmsg', ['controller' => 'Webservices', 'action' => 'counteunreadmsg']);
    $routes->connect('/webservices/messages/list', ['controller' => 'Webservices', 'action' => 'msgList']);
    $routes->connect('/webservices/messages/view', ['controller' => 'Webservices', 'action' => 'msgView']);
    $routes->connect('/webservices/get/cartid', ['controller' => 'Webservices', 'action' => 'getCartId']);
    $routes->connect('/webservices/survey/add', ['controller' => 'Webservices', 'action' => 'surverysubmit']);
    $routes->connect('/webservices/survey/list', ['controller' => 'Webservices', 'action' => 'surverylists']);
    $routes->connect('/webservices/membership/add', ['controller' => 'Webservices', 'action' => 'addMembership']);
    $routes->connect('/webservices/membership/lists', ['controller' => 'Webservices', 'action' => 'listMembership']);
    $routes->connect('/webservices/plan/lists', ['controller' => 'Webservices', 'action' => 'planLists']);
    $routes->connect('/webservices/referenceuser/lists', ['controller' => 'Webservices', 'action' => 'referenceUsers']);
    $routes->connect('/webservices/appoinment/lists', ['controller' => 'Webservices', 'action' => 'appoinmentLists']);
    $routes->connect('/webservices/appoinment/details', ['controller' => 'Webservices', 'action' => 'appoinmentDetails']);
    $routes->connect('/webservices/appoinment/completed', ['controller' => 'Webservices', 'action' => 'appoinmentCompleted']);
    $routes->connect('/webservices/appoinment/declined', ['controller' => 'Webservices', 'action' => 'appoinmentDeclined']);
    $routes->connect('/test', ['controller' => 'Webservices', 'action' => 'testNotifications']);
    
    
    
    
    


    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
//    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `InflectedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'InflectedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'InflectedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('InflectedRoute');
});


/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
