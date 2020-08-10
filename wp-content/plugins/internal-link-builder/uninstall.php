if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();

delete_option('ilb_maxuse');
delete_option('ilb_casesensitive');
delete_option('ilb_target_blank');
delete_option('ilb_backlink');
