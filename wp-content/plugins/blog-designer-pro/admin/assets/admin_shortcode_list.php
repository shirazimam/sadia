<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $wpdb;
$paged = filter_input(INPUT_GET, 'paged') ? absint(filter_input(INPUT_GET, 'paged')) : 1;
if (!is_numeric($paged))
    $paged = 1;
$limit = 10; // number of rows in page
$user = get_current_user_id();
$screen = get_current_screen();
$screen_option = $screen->get_option('per_page', 'option');
$limit = get_user_meta($user, $screen_option, true);
if (empty($limit) || $limit < 1) {
    // get the default value if none is set
    $limit = $screen->get_option('per_page', 'default');
}
$offset = ( $paged - 1 ) * $limit;

$where = '';
$search = '';
if (isset($_REQUEST['s']) && $_REQUEST['s'] != '') {
    $search = $_REQUEST['s'];
    $where = "WHERE shortcode_name LIKE '%$search%'";
}

if (isset($_POST['btnSearchShortcode']) || ( isset($_POST['s']) && $_POST['s'] != '' )) {
    $delete_action = '';
    if (isset($_POST['take_action']) && isset($_POST['bdp-action-top'])) {
        $delete_action = 'multiple_delete';
    }
    ?>
    <script type="text/javascript">
        var paged = '<?php echo $paged; ?>';
        var s = ['<?php echo $search; ?>'];
        var action = ['<?php echo $delete_action; ?>'];
        window.location = "?page=layouts&paged=" + paged + "&s=" + s + "&action=" + action;
    </script>
    <?php
}
$ord = 0;
if (isset($_REQUEST['orderby']) && $_REQUEST['order'] == 0) {
    $order_by = "desc";
    $ord = 1;
    $order_field = "shortcode_name";
} else if (isset($_REQUEST['orderby']) && $_REQUEST['order'] == 1) {
    $order_by = "asc";
    $ord = 0;
    $order_field = "shortcode_name";
} else {
    $order_by = "desc";
    $order_field = "bdid";
}


$total = $wpdb->get_var('SELECT COUNT(`bdid`) FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes ' . $where);
$num_of_pages = ceil($total / $limit);

$next_page = (int) $paged + 1;

if ($next_page > $num_of_pages)
    $next_page = $num_of_pages;

$prev_page = (int) $paged - 1;

if ($prev_page < 1)
    $prev_page = 1;

//Get the shortcode information
$shortcodes = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes ' . $where . ' order by ' . $order_field . ' ' . $order_by . ' limit ' . $offset . ', ' . $limit);
?>
<div class="bdp-admin wrap bdp-shortcode-list">
    <?php
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['page']) && $_GET['page'] == 'layouts' && wp_verify_nonce($_GET['_wpnonce'])) {
        ?>
        <div class="updated">
            <p>
                <?php _e('Layout deleted successfully.', BLOGDESIGNERPRO_TEXTDOMAIN); ?>
            </p>
        </div>
        <?php
    }
    if (isset($_POST['bdp-action-top']) && esc_html($_POST['bdp-action-top']) == 'delete_pr') {
        if (isset($_POST['chk_remove_all']) && !empty($_POST['chk_remove_all'])) {
            ?>
            <div class="updated">
                <p>
                    <?php _e('Layouts are deleted successfully.', BLOGDESIGNERPRO_TEXTDOMAIN); ?>
                </p>
            </div>
            <?php
        }
    }
    if (isset($_GET['msg']) || ( isset($_GET['action'])) && $_GET['action'] == 'multiple_delete') {
        ?>
        <div class="updated">
            <p>
                <?php
                if (isset($_GET['action']) && $_GET['action'] == 'multiple_delete') {
                    _e('Layouts are deleted successfully.', BLOGDESIGNERPRO_TEXTDOMAIN);
                }
                if (isset($_GET['msg']) && $_GET['msg'] == 'added') {
                    _e('Designer Settings Added.', BLOGDESIGNERPRO_TEXTDOMAIN);
                }
                if (isset($_GET['msg']) && $_GET['msg'] == 'updated') {
                    _e('Designer Settings updated.', BLOGDESIGNERPRO_TEXTDOMAIN);
                }
                ?>
            </p>
        </div>
    <?php } ?>

    <!-- Create new Shortcode button -->
    <h1>
        <?php _e('Layouts', BLOGDESIGNERPRO_TEXTDOMAIN); ?>
        <a class="page-title-action" href="?page=add_shortcode">
            <?php _e('Create New Layout', BLOGDESIGNERPRO_TEXTDOMAIN); ?>
        </a>
    </h1>
    <form method="post">
        <ul class="subsubsub">
            <li class="all">
                <a class="current" href="?page=layouts"><?php _e('All', BLOGDESIGNERPRO_TEXTDOMAIN); ?>
                    <span class="count">(<?php
                        if ($total > 0) {
                            echo $total;
                        } else {
                            echo '0';
                        }
                        ?>)
                    </span>
                </a>
            </li>
        </ul>
        <p class="search-box">
            <input id="shortcode-search-input" type="search" value="<?php echo $search; ?>" name="s">
            <input id="search-submit" class="button" type="submit" name="btnSearchShortcode" value="<?php esc_attr_e('Search Layout', BLOGDESIGNERPRO_TEXTDOMAIN) ?>">
        </p>
        <div class="tablenav top">
            <select name="bdp-action-top">
                <option selected="selected" value="none"><?php _e('Bulk Actions', BLOGDESIGNERPRO_TEXTDOMAIN); ?></option>
                <option value="delete_pr"><?php _e('Delete Permanently', BLOGDESIGNERPRO_TEXTDOMAIN); ?></option>
                <option value="bdp_export"><?php _e('Export Layout', BLOGDESIGNERPRO_TEXTDOMAIN); ?></option>
            </select>
            <input id="take_action" name="take_action" class="button action" type="submit" value="<?php esc_attr_e('Apply', BLOGDESIGNERPRO_TEXTDOMAIN); ?>" >
            <div class="tablenav-pages" <?php
            if ((int) $num_of_pages <= 1) {
                echo 'style="display:none;"';
            }
            ?>>
                <span class="displaying-num"><?php echo number_format_i18n($total) . ' ' . sprintf(_n('item', 'items', $total), number_format_i18n($total)); ?></span>
                <span class="pagination-links">
                    <?php if ($paged == '1') { ?>
                        <span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
                        <span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>
                    <?php } else { ?>
                        <a class="first-page" href="<?php echo '?page=layouts&paged=1&s=' . $search; ?>" title="<?php esc_attr_e('Go to the first page', BLOGDESIGNERPRO_TEXTDOMAIN); ?>">&laquo;</a>
                        <a class="prev-page" href="<?php echo '?page=layouts&paged=' . $prev_page . '&s=' . $search; ?>" title="<?php esc_attr_e('Go to the previous page', BLOGDESIGNERPRO_TEXTDOMAIN); ?>">&lsaquo;</a>
                    <?php } ?>
                    <span class="paging-input">
                        <span class="total-pages"><?php echo $paged; ?></span>
                        <?php _e('of', BLOGDESIGNERPRO_TEXTDOMAIN); ?>
                        <span class="total-pages"><?php echo $num_of_pages; ?></span>
                    </span>
                    <?php if ($paged == $num_of_pages) { ?>
                        <span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>
                        <span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>
                    <?php } else { ?>
                        <a class="next-page " href="<?php echo '?page=layouts&paged=' . $next_page . '&s=' . $search; ?>" title="<?php esc_attr_e('Go to the next page', BLOGDESIGNERPRO_TEXTDOMAIN); ?>">&rsaquo;</a>
                        <a class="last-page " href="<?php echo '?page=layouts&paged=' . $num_of_pages . '&s=' . $search; ?>" title="<?php esc_attr_e('Go to the last page', BLOGDESIGNERPRO_TEXTDOMAIN); ?>">&raquo;</a>
                    <?php } ?>
                </span>
            </div>
        </div>
        <table class="wp-list-table widefat fixed striped bdp-sliders-list bdp-table bdp-sliders-list bdp-table">
            <thead>
                <tr>
                    <td class="manage-column column-cb check-column" id="cb"><input type="checkbox" name="delete-all-shortcodes-1" id="delete-all-shortcodes-1" value="0"></td>
                    <th class="manage-column column-shortcode_name column-primary column-title sorted <?php echo $order_by; ?>" scope="col" id="shortcode_name">
                        <a href="?page=layouts&orderby=shortcode_name&order=<?php echo $ord; ?>">
                            <span><?php _e('Layout Name', BLOGDESIGNERPRO_TEXTDOMAIN); ?></span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th class="manage-column column-template-name" id="template_name"><?php _e('Template Name', BLOGDESIGNERPRO_TEXTDOMAIN); ?></th>
                    <th class="manage-column column-shortcode_tag" id="shortcode_tag"><?php _e('Shortcode', BLOGDESIGNERPRO_TEXTDOMAIN); ?></th>
                    <th class="manage-column column-categories" id="shortcode_categories" ><?php _e('Categories', BLOGDESIGNERPRO_TEXTDOMAIN); ?></th>
                    <th class="manage-column column-tags" id="shortcode_tags" ><?php _e('Tags', BLOGDESIGNERPRO_TEXTDOMAIN); ?></th>
                </tr>
            </thead>
            <tbody id="the_list">
                <?php
                if (!$shortcodes) {
                    echo '<tr>';
                    echo '<td colspan="5" style="text-align: center;">';
                    _e('No Layout found.', BLOGDESIGNERPRO_TEXTDOMAIN);
                    echo '</td>';
                    echo '</tr>';
                } else {
                    $shortcode_cnt = 0;
                    foreach ($shortcodes as $shortcode) {
                        $allsettings = $shortcode->bdsettings;
                        if (is_serialized($allsettings)) {
                            $bdp_settings = unserialize($allsettings);
                        }
                        $cat = '—';
                        $tag = '—';
                        if (isset($bdp_settings['custom_post_type']) && $bdp_settings['custom_post_type'] == 'post') {
                            if (isset($bdp_settings['template_tags']) && !empty($bdp_settings['template_tags'])) {
                                $tags = $bdp_settings['template_tags'];
                                $tag = array();
                                foreach ($tags as $t) {
                                    $tag_name = get_tag($t);
                                    $tag[] = $tag_name->name;
                                }
                                $tag = join(', ', $tag);
                            }
                            if (isset($bdp_settings['template_category']) && !empty($bdp_settings['template_category'])) {
                                $categories = $bdp_settings['template_category'];
                                $cat = array();
                                foreach ($categories as $t) {
                                    $cat[] = get_cat_name($t);
                                }
                                $cat = join(', ', $cat);
                            }
                        } else {
                            $custom_post = $bdp_settings['custom_post_type'];
                            $taxonomy_names = get_object_taxonomies($custom_post);
                            if (!empty($taxonomy_names)) {
                                foreach ($taxonomy_names as $taxonomy_name) {
                                    $custom_cat = $taxonomy_name . '_terms';
                                    if (isset($bdp_settings[$custom_cat]) && is_array($bdp_settings[$custom_cat])) {
                                        $cat = $bdp_settings[$custom_cat];
                                    }
                                }
                                if (!empty($cat) && is_array($cat)) {
                                    $cat = join(', ', $cat);
                                }
                            }
                        }
                        $shortcode_name = $shortcode->shortcode_name;
                        $shortcode_cnt++;

                        echo '<tr>';
                        ?>
                    <th class="check-column">
                        <input type="checkbox" class="chk_remove_all" name="chk_remove_all[]" id="chk_remove_all" value="<?php echo $shortcode->bdid; ?>">
                    </th>
                    <td class="title column-title column-primary">
                        <strong><a href="<?php echo '?page=add_shortcode&action=edit&id=' . $shortcode->bdid; ?>"><?php
                                if (!empty($shortcode_name)) {
                                    echo $shortcode_name;
                                } else {
                                    echo '(' . __('no title', BLOGDESIGNERPRO_TEXTDOMAIN) . ')';
                                }
                                ?></a>
                        </strong>
                        <div class="row-actions">

                            <span class="edit">
                                <a title="<?php esc_attr_e('Edit this item', BLOGDESIGNERPRO_TEXTDOMAIN); ?>" href="<?php echo '?page=add_shortcode&action=edit&id=' . $shortcode->bdid; ?>"><?php _e('Edit', BLOGDESIGNERPRO_TEXTDOMAIN); ?></a>
                                |
                            </span>
                            <span class="duplicate">
                                <a title="<?php esc_attr_e('Duplicate this item', BLOGDESIGNERPRO_TEXTDOMAIN); ?>" href="<?php echo esc_url(add_query_arg('action', 'duplicate_post_in_edit', admin_url('admin.php?layout=' . $shortcode->bdid))); ?>"><?php _e('Duplicate', BLOGDESIGNERPRO_TEXTDOMAIN); ?></a>
                                |
                            </span>
                            <span class="delete">
                                <a title="<?php esc_attr_e('Delete this item', BLOGDESIGNERPRO_TEXTDOMAIN); ?>" href="<?php echo wp_nonce_url('?page=layouts&action=delete&id=' . $shortcode->bdid); ?>" onclick="return confirm('Do you want to delete this layout?');"><?php _e('Delete', BLOGDESIGNERPRO_TEXTDOMAIN); ?></a>
                            </span>

                            <?php
                            $bdp_setting = unserialize($shortcode->bdsettings);
                            if (!empty($bdp_setting['blog_page_display'])) {
                                ?>
                                |
                                <span class="view">
                                    <a title="<?php esc_attr_e('View this item', BLOGDESIGNERPRO_TEXTDOMAIN); ?>" href="<?php echo get_the_permalink($bdp_setting['blog_page_display']); ?>" target="_blank"><?php _e('View', BLOGDESIGNERPRO_TEXTDOMAIN); ?></a>
                                </span>
                                <?php
                            }
                            ?>

                        </div>
                        <button class="toggle-row" type="button">
                            <span class="screen-reader-text">Show more details</span>
                        </button>
                    </td>
                    <td class="column-template-name" data-colname="<?php _e('Template Name', BLOGDESIGNERPRO_TEXTDOMAIN); ?>"><?php
                        if (isset($bdp_settings['template_name'])) {
                            echo str_replace('_', '-', $bdp_settings['template_name']);
                        }
                        ?></td>
                    <td class="column-shortcode_tag" data-colname="<?php _e('Shortcode', BLOGDESIGNERPRO_TEXTDOMAIN); ?>">
                        <input type="text" readonly="" onclick="this.select()" class="copy_shortcode" title="<?php esc_attr_e('Copy Shortcode', BLOGDESIGNERPRO_TEXTDOMAIN); ?>" value='[wp_blog_designer id="<?php echo $shortcode->bdid; ?>"]' />
                    </td>
                    <?php
                    echo '<td class="categories column-categories" data-colname="' . __('Categories', BLOGDESIGNERPRO_TEXTDOMAIN) . '">' . $cat . '</td>';
                    echo '<td class="tags column-tags" data-colname="' . __('Tags', BLOGDESIGNERPRO_TEXTDOMAIN) . '">' . $tag . '</td>';
                    echo '</tr>';
                }
            }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="manage-column check-column"><input type="checkbox" name="delete-all-shortcodes-2" id="delete-all-shortcodes-2" value="0"></td>
                    <td class="manage-column column-shortcode_name"><?php _e('Layout Name', BLOGDESIGNERPRO_TEXTDOMAIN); ?></td>
                    <th class="manage-column column-template-name"><?php _e('Template Name', BLOGDESIGNERPRO_TEXTDOMAIN); ?></th>
                    <td class="manage-column column-shortcode_tag" ><?php _e('Shortcode', BLOGDESIGNERPRO_TEXTDOMAIN); ?></td>
                    <td class="manage-column column-categories"><?php _e('Categories', BLOGDESIGNERPRO_TEXTDOMAIN); ?></td>
                    <td class="manage-column column-tags"><?php _e('Tags', BLOGDESIGNERPRO_TEXTDOMAIN); ?></td>
                </tr>
            </tfoot>
        </table>
        <div class="bottom-delete-form">
            <select name="bdp-action-top2">
                <option selected="selected" value="none"><?php _e('Bulk Actions', BLOGDESIGNERPRO_TEXTDOMAIN); ?></option>
                <option value="delete_pr"><?php _e('Delete Permanently', BLOGDESIGNERPRO_TEXTDOMAIN); ?></option>
                <option value="bdp_export"><?php _e('Export Layout', BLOGDESIGNERPRO_TEXTDOMAIN); ?></option>
            </select>
            <input id="take_action" name="take_action" class="button action" type="submit" value="<?php esc_attr_e('Apply', BLOGDESIGNERPRO_TEXTDOMAIN); ?>" >
            <?php if ($shortcodes) { ?>
                <div class="tablenav bottom">
                    <div class="tablenav-pages" <?php
                    if ((int) $num_of_pages <= 1) {
                        echo 'style="display:none;"';
                    }
                    ?>>
                        <span class="displaying-num"><?php echo number_format_i18n($total) . ' ' . sprintf(_n('item', 'items', $total), number_format_i18n($total)); ?></span>
                        <span class="pagination-links">
                            <?php if ($paged == '1') { ?>
                                <span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
                                <span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>
                            <?php } else { ?>
                                <a class="first-page" href="<?php echo '?page=layouts&paged=1&s=' . $search; ?>" title="<?php esc_attr_e('Go to the first page', BLOGDESIGNERPRO_TEXTDOMAIN); ?>">&laquo;</a>
                                <a class="prev-page" href="<?php echo '?page=layouts&paged=' . $prev_page . '&s=' . $search; ?>" title="<?php esc_attr_e('Go to the previous page', BLOGDESIGNERPRO_TEXTDOMAIN); ?>">&lsaquo;</a>
                            <?php } ?>
                            <span class="paging-input">
                                <span class="total-pages"><?php echo $paged; ?></span>
                                <?php _e('of', BLOGDESIGNERPRO_TEXTDOMAIN); ?>
                                <span class="total-pages"><?php echo $num_of_pages; ?></span>
                            </span>
                            <?php if ($paged == $num_of_pages) { ?>
                                <span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>
                                <span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>
                            <?php } else { ?>
                                <a class="next-page " href="<?php echo '?page=layouts&paged=' . $next_page . '&s=' . $search; ?>" title="<?php esc_attr_e('Go to the next page', BLOGDESIGNERPRO_TEXTDOMAIN); ?>">&rsaquo;</a>
                                <a class="last-page " href="<?php echo '?page=layouts&paged=' . $num_of_pages . '&s=' . $search; ?>" title="<?php esc_attr_e('Go to the last page', BLOGDESIGNERPRO_TEXTDOMAIN); ?>">&raquo;</a>
                            <?php } ?>
                        </span>
                    </div>
                </div>
            <?php } ?>
        </div>
    </form>
</div>