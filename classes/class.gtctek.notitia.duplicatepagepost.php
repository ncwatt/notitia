<?php
    class GTCTEK_Notitia_DuplicatePagePost {
        private static $initiated = false;

        public static function init() {
            if ( ! self::$initiated ) {
                self::init_hooks();
            }
        }

        private static function init_hooks() {
            add_filter( 'post_row_actions', [ self::class, 'add_duplicate_link' ], 10, 2 );
            add_filter( 'page_row_actions', [ self::class, 'add_duplicate_link' ], 10, 2 );
            add_action( 'admin_action_gtctek_notitia_duplicate_post', [ self::class, 'duplicate_post' ] );
            self::$initiated = true;
        }

        public static function add_duplicate_link( $actions, $post ) {
            if ( current_user_can( 'edit_posts' ) ) {
                $url = wp_nonce_url(
                    admin_url( 'admin.php?action=gtctek_notitia_duplicate_post&post=' . $post->ID ),
                    basename( __FILE__ ),
                    'gtctek_notitia_duplicate_nonce'
                );
                $actions[ 'duplicate' ] = '<a href="' . esc_url( $url ) . '" title="Duplicate this item">Duplicate</a>';
            }
            return $actions;
        }

        public static function duplicate_post() {
            // Verify nonce
            if ( ( ! isset( $_GET[ 'gtctek_notitia_duplicate_nonce' ] ) ) || ( ! wp_verify_nonce( $_GET[ 'gtctek_notitia_duplicate_nonce' ], basename( __FILE__ ) ) ) ) {
                wp_die( 'Security check failed' );
            }

            $post_id = absint( $_GET[ 'post' ] );
            $post = get_post( $post_id );

            if ( ( ! $post ) || ( ! current_user_can( 'edit_posts' ) ) ) {
                wp_die( 'Invalid post or insufficient permissions' );
            }

            // Create the duplicate
            $new_post = array(
                'post_title' => $post->post_title . ' (Copy)',
                'post_content' => $post->post_content,
                'post_status' => 'draft',
                'post_type' => $post->post_type,
                'post_author' => get_current_user_id()
            );

            $new_post_id = wp_insert_post( $new_post );

            // Copy taxonomies
            $taxonomies = get_object_taxonomies( $post->post_type );
            foreach ( $taxonomies as $taxonomy ) {
                $terms = wp_get_object_terms( $post_id, $taxonomy, array('fields' => 'ids' ) );
                wp_set_object_terms( $new_post_id, $terms, $taxonomy );
            }

            // Copy meta
            $meta = get_post_meta( $post_id );
            foreach ( $meta as $key => $values ) {
                foreach ( $values as $value ) {
                    // Skip protected or system keys
                    if ( in_array( $key, array( '_edit_lock', '_edit_last' ) ) ) continue;

                    // Remove existing meta to avoid duplicates
                    delete_post_meta( $new_post_id, $key );

                    // Add or update each value
                    foreach ( $values as $value ) {
                        update_post_meta( $new_post_id, $key, maybe_unserialize( $value ) );
                    }
                }
            }

            // Redirect to edit screen
            wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
            exit;
        }
    }
?>