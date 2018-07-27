<?php 

    get_header();

    // while we have any wordpress posts left, keep iterating
    while( have_posts() ) {
        // the_post(); returns the post in order. Each time its called it grabs the next post in the stack, until it reaches the end.
        the_post(); 
        pageBanner();    
    ?>

        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?php echo site_url('/blog'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back Home</a> <span class="metabox__main">Posted by <?php the_author_posts_link() ?> on <?php the_time( 'n.j.y' ) ?> in <?php echo get_the_category_list( ', ' ) ?></span></p>
            </div>
            <div class="generic-content">
                <?php 
                    the_content();
                ?>
            </div>
        </div>  

    <?php }

    get_footer();
?>
