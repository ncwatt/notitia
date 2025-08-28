<?php
// If uninstall not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' )) {
    exit;
}

// Run uninstall tasks for the current site
// Example: Delete a custom option
delete_option('your_plugin_option_name');

// If part of a multisite network, run uninstall tasks for each site
if ( is_multisite() ) {
    $sites = get_sites();
    foreach ( $sites as $site ) {
        switch_to_blog( $site->blog_id );
        // Example: Delete a custom option
        delete_option( 'your_plugin_option_name' );
        restore_current_blog();
    }
}
