<?php
    class GTCTEK_Notitia_Core {
        private static $initiated = false;

        public static function init() {
            if ( ! self::$initiated ) {
                self::init_hooks();
            }
        }

        private static function init_hooks() {
            if ( is_admin() ) {
                add_action( 'wp_head', [ self::class, 'add_meta_viewport' ] );
                add_action( 'admin_enqueue_scripts', [ self::class, 'add_bootstrap' ] );
            }

            self::$initiated = true;
        }

        public static function add_meta_viewport() {
            echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
        }

        public static function add_bootstrap() {
            wp_enqueue_style( 'gtctek-notitia-bootstrap', GTCTEK_NOTITIA__PLUGIN_URI . 'assets/css/bootstrap.min.css', '', '5.3.3', 'all' );
            wp_enqueue_script( 'gtctek-notitia-bootstrap', GTCTEK_NOTITIA__PLUGIN_URI . 'assets/js/bootstrap.min.js', '', '5.3.3', 'all' );
        }
    }
?>