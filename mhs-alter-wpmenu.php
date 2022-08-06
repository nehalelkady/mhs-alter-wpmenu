<?php
/*
Plugin Name: MHS Alter WP Menu
Description: Plugin to alter the default WP Menu. Allows you to create a store customer role with the rights to read, edit, and delete posts.
Version: 1.0
Author: Nehal Hussein
License: GPLv2 or later
*/

// Exit plugin execution if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  die;
}

/*
* Register Store Customer role when activating the plugin
*/
function mhs_add_role() {
  $mhs_capabilities = array('read' => true, 'edit_posts' => true, 'delete_posts' => true);
  add_role( 'store_customer', 'Store Customer', $mhs_capabilities );
}
register_activation_hook( __FILE__, 'mhs_add_role' );

/*
* Remove Store Customer role when deactivating the plugin
*/
function mhs_remove_role() {
       remove_role( 'store_customer');
}
register_deactivation_hook( __FILE__, 'mhs_remove_role' );

/*
* Alters the default WP Menu based on the user role
* If current user is Store Customer, it removes all menu items except the dashboard.
* If current user has admin permissions, it removes plugins, appearance, users and tools from WP Menu.
*/
function mhs_alter_wpmenu() {

  // If current user is admin, remove plugins, appearance, users and tools from WP Menu.
  if ( current_user_can( 'manage_options' ) ) {
    remove_menu_page( "plugins.php" );
    remove_menu_page( "themes.php" );
    remove_menu_page( "users.php" );
    remove_menu_page( "tools.php" );
  }

  global $current_user;

  $current_user_role = $current_user->roles[ 0 ];

  // If current user is Store Customer, remove all menu items except the dashboard.
  if ( 'store_customer' == $current_user_role ){
    foreach ( $GLOBALS['menu'] as $menu ) {
      if ( $menu[2] !== "index.php" ){
        remove_menu_page( $menu[2] );
      }
    }
  }
}
add_action( 'admin_menu', 'mhs_alter_wpmenu' );
