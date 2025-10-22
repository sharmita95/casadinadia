<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post-inner">

        <?php if ('' !== get_the_post_thumbnail()) : ?>
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('rehomes-featured-image-full'); ?>
                </a>
            </div><!-- .post-thumbnail -->
        <?php endif; ?>
        <div class="post-content-wrap">
            <header class="entry-header">

                <?php if ('post' === get_post_type()) : ?>
                    <div class="entry-categories">
                        <?php
                        rehomes_cat_links();
                        ?>
                    </div><!-- .entry-meta -->
                <?php endif; ?>

                <?php
                if (is_single()) {
                } elseif (is_front_page() && is_home()) {
                    the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                } else {
                    the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
                }
                ?>

                <?php if (!is_single()){?>
                    <div class="entry-meta">
                        <?php
                        rehomes_entry_meta();
                        ?>
                    </div>
                <?php }?>

            </header><!-- .entry-header -->

            <div class="entry-content">
                <?php
                /* translators: %s: Name of current post */

                the_content(
                    sprintf(
                        esc_html__('Read more ', 'rehomes') . '<span class="screen-reader-text"> "%s"</span>',
                        get_the_title()
                    )
                );

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'rehomes'),
                    'after' => '</div>',
                    'link_before' => '<span class="page-number">',
                    'link_after' => '</span>',
                ));

                ?>

            </div><!-- .entry-content -->



            <?php
            if (is_single()) {
                rehomes_entry_footer();
            }
            ?>
        </div>
    </div>

</article><!-- #post-## -->