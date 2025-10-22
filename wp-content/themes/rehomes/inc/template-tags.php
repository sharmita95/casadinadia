<?php
if (!function_exists('rehomes_entry_meta')) :
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     */
    function rehomes_entry_meta() {
        echo '<span class="posted-author"><span>'. esc_html('By ','rehomes').'</span><a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . get_the_author() . '</a>,</span>';
        echo '<span class="posted-on">' . rehomes_time_link() . '</span>';
    }
endif;


if (!function_exists('rehomes_cat_links')) :
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     */
    function rehomes_cat_links() {
        // Get Categories for posts.
        $categories_list = get_the_category_list(' ');

        if ('post' === get_post_type()) {
            // Make sure there's more than one category before displaying.
            if ($categories_list && rehomes_categorized_blog()) {
                echo '<span class="cat-links"><span class="screen-reader-text">' . esc_html__('Categories', 'rehomes') . '</span>' . wp_kses_post($categories_list) . '</span>';
            }
        }
    }
endif;

if (!function_exists('rehomes_count_comment')) :
    function rehomes_count_comment() {
        echo '<div class="entry-comment" ><i class="opal-icon-comment-lines"></i> ' . get_comments_number() . ' ' . _n("Comment", "Comments", get_comments_number(), "rehomes") . '</div>';
    }
endif;

if (!function_exists('rehomes_time_link')) :
    /**
     * Gets a nicely formatted string for the published date.
     */
    function rehomes_time_link() {
        $time_string = '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">'.'<time class="entry-date published updated" datetime="%1$s">%2$s</time></a>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time></a>';
        }

        $time_string = sprintf($time_string,
            get_the_date(DATE_W3C),
            get_the_date(),
            get_the_modified_date(DATE_W3C),
            get_the_modified_date()
        );

        // Wrap the time string in a link, and preface it with 'Posted on'.
        return $time_string;
    }
endif;

if (!function_exists('rehomes_entry_footer')):
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function rehomes_entry_footer() {

        // Get Tags for posts.
        $tags_list = get_the_tag_list('', ' ');

        // We don't want to output .entry-footer if it will be empty, so make sure its not.

        if ('post' === get_post_type()) {
            if ((rehomes_is_osf_framework_activated() && get_theme_mod('osf_socials')) || $tags_list) {
                echo '<footer class="entry-footer">';
                if ($tags_list) {
                    echo '<div class="cat-tags-links">';
                    if ($tags_list) {
                        echo '<span class="tags-title">' . esc_html__('Tags: ', 'rehomes') . '</span>';
                        echo '<span class="tags-links">' . wp_kses_post($tags_list) . '</span>';
                    }
                    echo '</div>';
                }
                rehomes_social_share();
                echo '</footer> <!-- .entry-footer -->';
            }

        }
    }
endif;


if (!function_exists('rehomes_edit_link')) :
    /**
     * Returns an accessibility-friendly link to edit a post or page.
     *
     * This also gives us a little context about what exactly we're editing
     * (post or page?) so that users understand a bit more where they are in terms
     * of the template hierarchy and their content. Helpful when/if the single-page
     * layout with multiple posts/pages shown gets confusing.
     */
    function rehomes_edit_link() {
        edit_post_link(
            sprintf(
            /* translators: %s: Name of current post */
                esc_html__('Edit', 'rehomes') . '<span class="screen-reader-text"> "%s"</span>',
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;


/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function rehomes_categorized_blog() {
    $category_count = get_transient('rehomes_categories');

    if (false === $category_count) {
        // Create an array of all the categories that are attached to posts.
        $categories = get_categories(array(
            'fields'     => 'ids',
            'hide_empty' => 1,
            // We only need to know if there is more than one category.
            'number'     => 2,
        ));

        // Count the number of categories that are attached to the posts.
        $category_count = count($categories);

        set_transient('rehomes_categories', $category_count);
    }

    // Allow viewing case of 0 or 1 categories in post preview.
    if (is_preview()) {
        return true;
    }

    return $category_count > 1;
}


/**
 * Flush out the transients used in rehomes_categorized_blog.
 */
function rehomes_category_transient_flusher() {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient('rehomes_categories');
}

add_action('edit_category', 'rehomes_category_transient_flusher');
add_action('save_post', 'rehomes_category_transient_flusher');