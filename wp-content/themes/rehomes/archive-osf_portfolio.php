<?php get_header();
$array = OSF_Custom_Post_Type_Portfolio::getInstance()->get_all_meta_field_value();
?>
    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

                <div class="search-project clear">
                    <div class="inner">
                        <form action="<?php echo get_post_type_archive_link('osf_portfolio'); ?>" method="get">
                            <div class="d-flex justify-content-center project-group">
                                <?php if (!portfolio_get_option('hide_status')): ?>
                                    <div class="project-inner">
                                        <label><?php echo esc_html__('Project Status', 'rehomes'); ?></label>
                                        <select name="osf_portfolio_status">
                                            <option value=""><?php echo esc_html__('Any', 'rehomes'); ?></option>
                                            <?php
                                            foreach ($array['osf_portfolio_status'] as $item) {
                                                ?>
                                                <option value="<?php echo esc_attr($item); ?>" <?php echo esc_attr(isset($_GET['osf_portfolio_status']) && $item == $_GET['osf_portfolio_status'] ? 'selected' : ''); ?>><?php echo esc_html($item); ?></option>
                                                <?php
                                            }

                                            ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <?php if (!portfolio_get_option('hide_type')): ?>
                                    <div class="project-inner">
                                        <label><?php echo esc_html__('Project Type', 'rehomes'); ?></label>
                                        <select name="osf_portfolio_type">
                                            <option value=""><?php echo esc_html__('Any', 'rehomes'); ?></option>
                                            <?php
                                            foreach ($array['osf_portfolio_type'] as $item) {
                                                ?>
                                                <option value="<?php echo esc_attr($item); ?>" <?php echo esc_attr(isset($_GET['osf_portfolio_type']) && $item == $_GET['osf_portfolio_type'] ? 'selected' : ''); ?>><?php echo esc_html($item); ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <?php if (!portfolio_get_option('hide_location')): ?>
                                    <div class="project-inner">
                                        <label><?php echo esc_html__('Location', 'rehomes'); ?></label>
                                        <select name="osf_portfolio_location">
                                            <option value=""><?php echo esc_html__('Any', 'rehomes'); ?></option>
                                            <?php
                                            foreach ($array['osf_portfolio_location'] as $item) {
                                                ?>
                                                <option value="<?php echo esc_attr($item); ?>" <?php echo esc_attr(isset($_GET['osf_portfolio_location']) && $item == $_GET['osf_portfolio_location'] ? 'selected' : ''); ?>><?php echo esc_html($item); ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <?php
                                if (!portfolio_get_option('hide_budget')): ?>
                                    <div class="project-inner">
                                        <label><?php echo esc_html__('Budget', 'rehomes'); ?></label>
                                        <select name="osf_portfolio_budget">
                                            <option value=""><?php echo esc_html__('Any', 'rehomes'); ?></option>
                                            <?php
                                            $symbol = !empty(get_option('osf_portfolio_archive')['currency_symbol']) ? get_option('osf_portfolio_archive')['currency_symbol'] : '$';
                                            $range  = portfolio_get_option('range');
                                            if ($range) {
                                                foreach ($range as $k => $number) {
                                                    $min = $number['min'];
                                                    $max = $number['max'];
                                                    if (!$min && !$max) {
                                                        continue;
                                                    }
                                                    $text_min = $min ? $symbol . $min : '';
                                                    $text_min = $max ? $text_min : '> ' . $text_min;
                                                    $text_max = $max ? $symbol . $max : '';
                                                    $text_max = $min ? $text_max : '< ' . $text_max;

                                                    $text_min_max = $text_min && $text_max ? $text_min . ' - ' : $text_min;
                                                    $text_min_max = $text_max ? $text_min_max . $text_max : $text_min_max;

                                                    ?>
                                                    <option value="<?php echo esc_attr($min . '|' . $max); ?>" <?php echo isset($_GET['osf_portfolio_budget']) && ($min . '|' . $max) == $_GET['osf_portfolio_budget'] ? 'selected' : ''; ?>><?php echo esc_html($text_min_max); ?></option>
                                                    <?php
                                                }
                                            } else {
                                                foreach ($arr = range(0, floatval($array['osf_portfolio_price_max']), floatval($array['osf_portfolio_price_max']) / 10) as $k => $number) {
                                                    if ($k > 0) {
                                                        ?>
                                                        <option value="<?php echo esc_attr($arr[$k - 1] . '|' . $number); ?>" <?php echo isset($_GET['osf_portfolio_budget']) && ($arr[$k - 1] . '|' . $number) == $_GET['osf_portfolio_budget'] ? 'selected' : ''; ?>><?php echo esc_html($symbol . $arr[$k - 1] . ' - ' . $symbol . $number); ?></option>
                                                        <?php
                                                    }
                                                }
                                            }

                                            ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <div class="project-inner align-self-end">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <?php echo esc_html__('Search', 'rehomes'); ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <?php if (have_posts()) :
                    $column = get_theme_mod('osf_portfolio_archive_column', 3);

                    $style = get_theme_mod('osf_portfolio_archive_style', 'default1');
                    if ($style == 'list') {
                        $column = 1;
                    }
                    $column_tablet = $column >= 3 ? 2 : 1;
                    echo '<div class="row isotope-grid elementor-portfolio-style-' . esc_attr($style) . '" data-elementor-columns="' . esc_attr($column) . '" data-elementor-columns-tablet="' . esc_attr($column_tablet) . '" data-elementor-columns-mobile="1">';

                    global $portfolio_style, $wp_query, $post;
                    $portfolio_style = $style;

                    $paged          = $wp_query->query_vars['paged'] == 0 ? 0 : $wp_query->query_vars['paged'] - 1;
                    $posts_per_page = $wp_query->query_vars['posts_per_page'];
                    while (have_posts()) : the_post();

                        $count                = ($posts_per_page * $paged) + $wp_query->current_post + 1;
                        $post->current_nubmer = $count;
                        ?>
                        <div class="column-item portfolio-entries">
                            <?php
                            if ($style == 'default1') {
                                get_template_part('template-parts/portfolio/content', '1');
                            } elseif ($style == 'list') {
                                get_template_part('template-parts/portfolio/content-list', '1');
                            } else {
                                get_template_part('template-parts/portfolio/content');
                            }
                            ?>
                        </div>
                    <?php
                    endwhile;
                    echo '</div>';
                else :
                    get_template_part('template-parts/post/content', 'none');

                endif; ?>

            </main><!-- #main -->
            <?php
            the_posts_pagination(array(
                'prev_text'          => '<span class="opal-icon-chevron-left"></span><span class="screen-reader-text">' . esc_html__('Previous page', 'rehomes') . '</span>',
                'next_text'          => '<span class="screen-reader-text">' . esc_html__('Next page', 'rehomes') . '</span><span class="opal-icon-chevron-right"></span>',
                'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__('Page', 'rehomes') . ' </span>',
            ));
            ?>
        </div><!-- #primary -->
    </div><!-- .wrap -->

<?php get_footer();
