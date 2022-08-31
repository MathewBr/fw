<?php

use fw\Router;
/*
* default if not specified 'controller' => 'Main', 'action' => 'index'
*/

//***here can be custom rules***
Router::addRoute('^product/(?P<alias>[a-z0-9-]+)/?$', ['controller' => 'Product', 'action' => 'view']);

//***default rules***
//for admin
Router::addRoute('^admin$', ['controller' => 'Main', 'action' => 'index', 'prefix' => 'admin']);
Router::addRoute('^admin/?(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$', ['prefix' => 'admin']);
//for users
Router::addRoute('^$', ['controller' => 'Main', 'action' => 'index']); //rule for empty query = for main page
Router::addRoute('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$'); //uri page/some -> [controller=> page, action => some] if match is found in preg_match()

