<?php
/**
 * Route map: URI segment => [ControllerClass, method]
 * The front controller (index.php) resolves these.
 */
return [
    ''           => ['AuthController',      'showLogin'],
    'login'      => ['AuthController',      'showLogin'],
    'auth'       => ['AuthController',      'login'],
    'logout'     => ['AuthController',      'logout'],
    'dashboard'  => ['DashboardController', 'index'],
    'trip'          => ['TripController',    'showForm'],
    'save-trip'     => ['TripController',    'saveTrip'],
    'my-trips'      => ['DriverController',  'showTrips'],
    'settings'      => ['DriverController',  'showSettings'],
    'save-settings' => ['DriverController',  'saveSettings'],
];
