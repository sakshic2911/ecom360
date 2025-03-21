<?php

/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\ORM\TableRegistry; // Import the TableRegistry class


return static function (RouteBuilder $routes) {
    /*
     * The default class to use for all routes
     *
     * The following route classes are supplied with CakePHP and are appropriate
     * to set as the default:
     *
     * - Route
     * - InflectedRoute
     * - DashedRoute
     *
     * If no call is made to `Router::defaultRouteClass()`, the class used is
     * `Route` (`Cake\Routing\Route\Route`)
     *
     * Note that `Route` does not do any inflections on URLs which will result in
     * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
     * `{action}` markers.
     */
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder) {
       
        $builder->connect('/', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
        $builder->connect('/tickets', ['controller' => 'Tickets', 'action' => 'index']);
        $builder->connect('/add-master-client', ['controller' => 'Client', 'action' => 'addMasterClients']);
        $builder->connect('/add_roles', ['controller' => 'Roles', 'action' => 'add']);
        $builder->connect('/archive-tickets', ['controller' => 'Tickets', 'action' => 'archiveList']);
        $builder->connect('/account-setting', ['controller' => 'Users', 'action' => 'accountSetting']);
        $builder->connect('/change-password', ['controller' => 'Users', 'action' => 'changePassword']);
        $builder->connect('/forgot-password', ['controller' => 'Users', 'action' => 'forgotPassword']);
        $builder->connect('/support-library', ['controller' => 'Support', 'action' => 'supportCategories']);
        $builder->connect('/support-resources', ['controller' => 'Support', 'action' => 'supportResources']);
        $builder->connect('/weekly-meeting', ['controller' => 'Support', 'action' => 'weeklyMeeting']);
        $builder->connect('/faq', ['controller' => 'Support', 'action' => 'faq']);
        $builder->connect('/changeLoginStatus', ['controller' => 'Client', 'action' => 'changeLoginStatus']);
        $builder->connect('/client-archive-tickets', ['controller' => 'Tickets', 'action' => 'clientArchiveList']);
        $builder->connect('/add-internal-staff', ['controller' => 'Users', 'action' => 'addInternalStaff']);
        $builder->connect('/edit-internal-staff', ['controller' => 'InternalStaff', 'action' => 'editInternalStaff']);
        $builder->connect('/edit_permission', ['controller' => 'InternalStaff', 'action' => 'updateIndividualPermission']); 
        $builder->connect('/edit_roles', ['controller' => 'Roles', 'action' => 'edit']);


        /*
         * ...and connect the rest of 'Pages' controller's URLs.
         */
        $builder->connect('/pages/*', 'Pages::display');

        /*
         * Connect catchall routes for all controllers.
         *
         * The `fallbacks` method is a shortcut for
         *
         * ```
         * $builder->connect('/{controller}', ['action' => 'index']);
         * $builder->connect('/{controller}/{action}/*', []);
         * ```
         *
         * You can remove these routes once you've connected the
         * routes you want in your application.
         */
        $builder->fallbacks();
    });

    /// APIS ROUTES
     $routes->scope('/api', function (RouteBuilder $builder) {
       

      });   
};
