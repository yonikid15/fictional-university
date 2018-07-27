<?php 

    get_header();
    // while we have any wordpress posts left, keep iterating
    while( have_posts() ) {
        // the_post(); returns the post in order. Each time its called it grabs the next post in the stack, until it reaches the end.
        the_post(); 
        pageBanner();
    ?>
        
        <div class="container container--narrow page-section">
            <?php
                $theParent = wp_get_post_parent_id( get_the_ID() );
                if( $theParent ) {
            ?>

                <div class="metabox metabox--position-up metabox--with-home-link">
                    <p><a class="metabox__blog-home-link" href="<?php echo get_permalink( $theParent ) ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title( $theParent ) ?></a> <span class="metabox__main"><?php the_title() ?></span></p>
                </div>
            
            <?php 
                } 
            ?>
            
            <?php 
                $testArray = get_pages( array( 
                    'child_of' => get_the_ID()
                ));

                if (  $theParent || $testArray ) { ?>
             <div class="page-links">
                <h2 class="page-links__title"><a href="<?php echo get_permalink( $theParent ) ?>"><?php echo get_the_title( $theParent ) ?></a></h2>
                <ul class="min-list">
                    <?php

                        if ($theParent) {
                            $findChildrenOf = $theParent;
                        } else {
                            $findChildrenOf = get_the_ID();
                        }

                        $childrenPages = array( 
                            'title_li' => null,
                            'child_of' => $findChildrenOf,
                            'sort_column' => 'menu_order'
                        );

                        wp_list_pages( $childrenPages );
                    ?>

                </ul>
            </div>
            <?php  } ?>

            <div class="generic-content">
                <?php the_content(); ?>
            </div>

        </div>

    <?php }

    get_footer();
?>