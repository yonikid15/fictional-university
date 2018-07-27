<?php
    require get_theme_file_path('./includes/search-route.php');
    require get_theme_file_path( '/includes/like-route.php' );
    
    // This function is used to add raw JSON data response data, when making a request to the WP REST API.
    function university_custom_rest() {
        register_rest_field( 'post', 'authorName', array(
            'get_callback' => function() { return get_the_author(); }
        ));

        register_rest_field( 'note', 'userNoteCount', array(
            'get_callback' => function() { 
                return count_user_posts( get_current_user_id(), 'note' ); 
            }
        ));
    }

    add_action( 'rest_api_init', 'university_custom_rest' );

    function pageBanner( $args = null ) { 
        if( !$args[ 'title' ] ) {
            $args[ 'title' ] = get_the_title();
        }

        if( !$args[ 'subtitle' ] ) {
            $args[ 'subtitle' ] = get_field( 'page_banner_subtitle' );
        }

        if( !$args[ 'image' ] ) {
            if( get_field( 'page_banner_background_image' ) ) {
                $args[ 'image' ] = get_field( 'page_banner_background_image' )[ 'sizes' ][ 'pageBanner' ];
            } else {
                $args[ 'image' ] = get_theme_file_uri( './images/ocean.jpg' );
            }
        }
        ?>
        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo $args[ 'image' ]; ?>);"></div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php echo $args[ 'title' ] ?></h1>
                <div class="page-banner__intro">
                    <p><?php echo $args['subtitle'] ?></p>
                </div>
            </div>  
        </div>
    <?php }

    /* 
    
        wp_enqueue_script() and wp_enqueue_style() are built in WP functions, which purpose is to enqueue a script/stylesheet. It registers the script or style if $src provided, and enqueues it.

        Arguments: 

        1. $handle : Name of the script/stylesheet (should be unique)
        2. $src : FULL url of the script or path, relative to root directory.
        3. $deps : An array of registered script/stylesheet handles this script/stylesheet depends on.
        4. $ver : The script/stylesheet version number, which is added to the URL as a query string for CACHE BUSTING purposes.

        wp_enqueue_script() 

        5th argument. $in_footer : Whether to enqueue the script before </body> instead of in the <head>. Default = false

        wp_enqueue_style() 

        5th argument. $media : The media for which this stylesheet has been defined. 
    
    */
    function university_files() {
        wp_enqueue_script( 'googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyASshKQYvSbci0JCzAqXQIEvrj6sGN1f7w', null, microtime(), true );
        wp_enqueue_script( 'main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), null, microtime(), true );
        wp_enqueue_style( 'font_awesome',  '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_style( 'university_main_styles', get_stylesheet_uri(), null, microtime());
        wp_enqueue_style( 'google_fonts',  '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        wp_localize_script( "main-university-js", "universityData", array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce( 'wp_rest' )
        ));
    }
    

    /* 

        An Action is a PHP function that is executed at specific points throughout the WordPress Core. 

        You can create a CUSTOM action using the Action API to add or remove code from an existing Action by specifying any exisiting Hook (eg. wp_enqueue_scripts). This process is called "hooking"

        add_action() is a built in WP function, which hooks a function on to a specific function.

        Arguments: 
        
            1. $tag : The name of the action to which the $function_to_add (second argument) is hooked, should be placed here. It is required and takes a string. 

            2. $function_to_add : The name of the function you wish to be called. This is also required.

            Two more optional arguments are $priority and $accepted_args. 
            
                3. $priority is used to specify the order in which the functions associated with the action are executed.

                4. $accepted_args is the number of arguments the function accepts.

    */
    add_action( 'wp_enqueue_scripts', 'university_files' );


    function university_features() {
        /* 
            add_theme_support() registers theme support for a given feature. It takes a single argument, which is the feature being added. 

            Must be called in the theme's functions.php file to work. If attached to a hook, it must be 'after_setup_theme'. The 'init' hook may be too late for some features.

            Arguments: 

            1. $feature (required) : The feature being added. Common ones: 'post-formats', 'post-thumbnails', 'html5', 'custom-logo', etc.

            2. $args (optional) : extra arguments to pass along with certain features.
        */
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_image_size( 'professorLandscape', 400, 260, true );
        add_image_size( 'professorPortrait', 480, 650, true );
        add_image_size( 'pageBanner', 1500, 350, true );
    }

    add_action( 'after_setup_theme', 'university_features');

    function university_adjust_queries($query) {
        if( !is_admin() && is_post_type_archive( 'event' ) && $query->is_main_query()) {
            $today = date('Ymd');
            $query->set( 'meta_key', 'event_date' );
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'order', 'ASC' );
            $query->set( 'meta_query', array( 
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              ) );

        }

        if( !is_admin() && is_post_type_archive( 'program' ) && $query->is_main_query()) {
            $query->set( 'orderby', 'title' );
            $query->set( 'order', 'ASC' );
            $query->set( 'posts_per_page', -1 );
        }

        if( !is_admin() && is_post_type_archive( 'campus' ) && $query->is_main_query()) {
            $query->set( 'posts_per_page', -1 );
        }

    }

    add_action( 'pre_get_posts', 'university_adjust_queries' );

    function universityMapKey( $api ) {
        $api['key'] = 'AIzaSyASshKQYvSbci0JCzAqXQIEvrj6sGN1f7w';
        return $api;
    }

    add_filter('acf/fields/google_map/api', 'universityMapKey');


    // Redirect subscriber accounts out of admin and onto hompage
    add_action( 'admin_init', 'redirectSubsToFrontEnd');

    function redirectSubsToFrontEnd() {
        $ourCurrentUser = wp_get_current_user();

        if( count( $ourCurrentUser->roles ) == 1 && $ourCurrentUser->roles[0] == 'subscriber' ) {
            wp_redirect( site_url( '/' ) );
            exit;
        }
    }

    // Remove/Hide admin bar for subscribers of site
    add_action( 'wp_loaded', 'noSubsAdminBar');

    function noSubsAdminBar() {
        $ourCurrentUser = wp_get_current_user();

        if( count( $ourCurrentUser->roles ) == 1 && $ourCurrentUser->roles[0] == 'subscriber' ) {
            show_admin_bar( false );
        }
    }

    // Customize Login Screen
    add_filter( 'login_headerurl', 'ourHeaderUrl' );

    function ourHeaderUrl() {
        return esc_url( site_url( '/' ) );
    }

    function loginLogoUrlTitle() {
        return get_bloginfo( 'name' );
    }
    add_filter( 'login_headertitle', 'loginLogoUrlTitle' );

    // Adds Our own custom CSS to the wordpress login page, this is because the CSS getting added in the wp_enqueue_scripts action only effects the front-end of the website. It does not effect the login or registration pages, therefore we need the action below to add our CSS styles again

    add_action( 'login_enqueue_scripts', 'ourLoginCSS' );

    function ourLoginCSS() {
        wp_enqueue_style( 'university_main_styles', get_stylesheet_uri(), null, microtime());
        wp_enqueue_style( 'google_fonts',  '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    }


    // Force note posts to be private
    add_filter( 'wp_insert_post_data', 'makeNotePrivate', 10, 2);

    function makeNotePrivate( $data, $postArr) {
        // strips possible html from subscribers posts
        if( $data['post_type'] == 'note' ) {
            // prevent users from making more than 5 posts
            if( count_user_posts( get_current_user_id(), 'note' ) > 4 && !$postArr['ID'] ) {
                die( "You have reached your note limit." );
            }

            $data['post_content'] = sanitize_textarea_field( $data['post_content'] );
            $data['post_title'] = sanitize_text_field( $data['post_title'] );
        }

        if( $data['post_type'] == 'note' && $data['post_status'] != 'trash' ) {
            $data['post_status'] = "private";
        }
        
        return $data;
    }
?>
