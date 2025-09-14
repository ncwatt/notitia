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
            }

            self::$initiated = true;
        }

        public static function add_meta_viewport() {
            echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
        }
    }
?>