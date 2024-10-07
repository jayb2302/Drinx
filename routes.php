<?php

require_once __DIR__.'/router.php';

// ##################################################
// ##################################################
// ##################################################

// Static GET
// In the URL -> http://localhost
// The output -> Index
get('/', 'views/index.php');

// Dynamic GET. Example with 1 variable
// The $id will be available in user.php
get('/user/$id', 'views/user');

// Dynamic GET. Example with 2 variables
// The $name will be available in full_name.php
// The $last_name will be available in full_name.php
// In the browser point to: localhost/user/X/Y
get('/user/$name/$last_name', 'views/full_name.php');
// Dynamic GET for single cocktail view
get('/cocktail/$id', 'views/cocktail.php');
// Route for listing all cocktails
get('/cocktails', 'views/cocktails/index.php');
// Dynamic GET for user profile with $id
get('/user/$id', 'views/user.php');

// Route for adding a new cocktail
get('/cocktails/add', 'views/cocktails/add.php');

// Route for processing new cocktail form (POST)
post('/cocktails/add', 'views/cocktails/create.php');

// Route for editing an existing cocktail
get('/cocktails/edit/$id', 'views/cocktails/edit.php');

// Route for processing cocktail update (POST)
post('/cocktails/edit/$id', 'views/cocktails/update.php');

// Route for deleting a cocktail
post('/cocktails/delete/$id', 'views/cocktails/delete.php');
// Dynamic GET. Example with 2 variables with static
// In the URL -> http://localhost/product/shoes/color/blue
// The $type will be available in product.php
// The $color will be available in product.php
get('/product/$type/color/$color', 'product.php');

// A route with a callback
get('/callback', function(){
  echo 'Callback executed';
});

// A route with a callback passing a variable
// To run this route, in the browser type:
// http://localhost/user/A
get('/callback/$name', function($name){
  echo "Callback executed. The name is $name";
});

// Route where the query string happends right after a forward slash
get('/product', '');

// A route with a callback passing 2 variables
// To run this route, in the browser type:
// http://localhost/callback/A/B
get('/callback/$name/$last_name', function($name, $last_name){
  echo "Callback executed. The full name is $name $last_name";
});

// ##################################################
// ##################################################
// ##################################################
// Route that will use POST data
post('/user', '/api/save_user');



// ##################################################
// ##################################################
// ##################################################
// any can be used for GETs or POSTs

// For GET or POST
// The 404.php which is inside the views folder will be called
// The 404.php has access to $_GET and $_POST
any('/404','views/404.php');
