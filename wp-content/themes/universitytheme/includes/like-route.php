<?php 

add_action( 'rest_api_init', 'universityLikeRoutes' );

function universityLikeRoutes() {
    /* 
        param1: Namespace
        param2: Name of Route or URL
        param3: Array( methods => CRUD operation..., callback => callback function after response or error is received...)
    */

    register_rest_route( 'university/v1', 'manageLike' , array(
        'methods' => 'POST',
        'callback' => 'createLike'
    ));

    register_rest_route( 'university/v1', 'manageLike' , array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ));
}

function createLike( $data ) {
    if( is_user_logged_in() ) {
        $professor_id = sanitize_text_field( $data['professor_id'] );

        $existQuery = new WP_Query( array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => $professor_id
                )
            )
        ));

        if( $existQuery->found_posts == 0 && get_post_type( $professor_id ) == 'professor' ) {

            return wp_insert_post( array(
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => 'second test',
                'meta_input' => array(
                    'liked_professor_id' => $professor_id
                )
            )); 

        } else {

            die("Invalid professor id");

        }
    } else {

        die("Only logged in users can create a like.");

    }

    
}

function deleteLike( $data ) {
    $like_id = sanitize_text_field( $data[ 'like' ] );

    if( get_current_user_id() == get_post_field( 'post_author', $like_id ) && get_post_type( $like_id ) == 'like' )  {
        wp_delete_post( $like_id, true );
        return 'Congrats, Like deleted';
    } else {
        die("You do not have permission to delete that.");
    }
   
}
