<?php
/*
 * Widget for Blog Designer Pro suppprt
 */

if (!defined('ABSPATH'))
    exit;


add_action('widgets_init', 'register_blogdesigner_widget');

function register_blogdesigner_widget() {
    register_widget('BDP_Widget_BlogDesignerPro');
}

class BDP_Widget_BlogDesignerPro extends WP_Widget {

    public function __construct() {
        parent::__construct('blog_designer_pro_widget', __('Blog Designer PRO', BLOGDESIGNERPRO_TEXTDOMAIN), array('classname' => 'widgte_blog_designer_pro_shortcode_list', 'description' => __('Show blogs of Blog Designer Pro. Please use this widget only for full width container area.', BLOGDESIGNERPRO_TEXTDOMAIN)));
        $this->alt_option_name = 'widgte_blog_designer_pro_shortcode_list';

        add_action('save_post', array($this, 'flush_widgte_blog_designer_pro_shortcode_list'));
        add_action('deleted_post', array($this, 'flush_widgte_blog_designer_pro_shortcode_list'));
        add_action('switch_theme', array($this, 'flush_widgte_blog_designer_pro_shortcode_list'));
    }

    public function widget($args, $instance) {
        $blog_designer_pro_shortcode_list = (isset($instance['blog_designer_pro_shortcode_list']) && $instance['blog_designer_pro_shortcode_list'] != '' ) ? (int) ($instance['blog_designer_pro_shortcode_list']) : '';
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $before_widget = $args['before_widget'];
        $after_widget = $args['after_widget'];

        if ($blog_designer_pro_shortcode_list) {
            $bdp_settings = bdp_get_shortcode_settings($blog_designer_pro_shortcode_list);
            $shortcode_id = $blog_designer_pro_shortcode_list;
            $bdp_theme = $bdp_settings['template_name'];
            $style_name = 'bdp-' . $bdp_theme . '-template-css';
            wp_enqueue_style($style_name);
            wp_enqueue_style('bdp-basic-tools');
            wp_enqueue_style('bdp-front-css');
            $template_titlefontface = (isset($bdp_settings['template_titlefontface']) && $bdp_settings['template_titlefontface'] != '') ? $bdp_settings['template_titlefontface'] : "";
            $load_goog_font_blog = array();
            if (isset($bdp_settings['template_titlefontface_font_type']) && $bdp_settings['template_titlefontface_font_type'] == 'Google Fonts') {
                $load_goog_font_blog[] = $template_titlefontface;
            }
            $column_setting = (isset($bdp_settings['column_setting']) && $bdp_settings['column_setting'] != '') ? $bdp_settings['column_setting'] : 2;
            $background = (isset($bdp_settings['template_bgcolor']) && $bdp_settings['template_bgcolor'] != '') ? $bdp_settings['template_bgcolor'] : "";
            $template_bghovercolor = (isset($bdp_settings['template_bghovercolor']) && $bdp_settings['template_bghovercolor'] != '') ? $bdp_settings['template_bghovercolor'] : "";
            $displaydate_backcolor = (isset($bdp_settings['displaydate_backcolor']) && $bdp_settings['displaydate_backcolor'] != '') ? $bdp_settings['displaydate_backcolor'] : "";
            $templatecolor = (isset($bdp_settings['template_color']) && $bdp_settings['template_color'] != '') ? $bdp_settings['template_color'] : "inherit";
            $color = (isset($bdp_settings['template_ftcolor']) && $bdp_settings['template_ftcolor'] != '') ? $bdp_settings['template_ftcolor'] : "inherit";
            $grid_hoverback_color = (isset($bdp_settings['grid_hoverback_color']) && $bdp_settings['grid_hoverback_color'] != '') ? $bdp_settings['grid_hoverback_color'] : "";
            $linkhovercolor = (isset($bdp_settings['template_fthovercolor']) && $bdp_settings['template_fthovercolor'] != '') ? $bdp_settings['template_fthovercolor'] : "";
            $loader_color = (isset($bdp_settings['loader_color']) && $bdp_settings['loader_color'] != '') ? $bdp_settings['loader_color'] : "";
            $loadmore_button_color = (isset($bdp_settings['loadmore_button_color']) && $bdp_settings['loadmore_button_color'] != '') ? $bdp_settings['loadmore_button_color'] : "#ffffff";
            $loadmore_button_bg_color = (isset($bdp_settings['loadmore_button_bg_color']) && $bdp_settings['loadmore_button_bg_color'] != '') ? $bdp_settings['loadmore_button_bg_color'] : "#444444";
            $title_alignment = (isset($bdp_settings['template_title_alignment']) && $bdp_settings['template_title_alignment'] != '') ? $bdp_settings['template_title_alignment'] : "";
            $titlecolor = (isset($bdp_settings['template_titlecolor']) && $bdp_settings['template_titlecolor'] != '') ? $bdp_settings['template_titlecolor'] : "";
            $titlehovercolor = (isset($bdp_settings['template_titlehovercolor']) && $bdp_settings['template_titlehovercolor'] != '') ? $bdp_settings['template_titlehovercolor'] : "";
            $contentcolor = (isset($bdp_settings['template_contentcolor']) && $bdp_settings['template_contentcolor'] != '') ? $bdp_settings['template_contentcolor'] : "";
            $readmorecolor = (isset($bdp_settings['template_readmorecolor']) && $bdp_settings['template_readmorecolor'] != '') ? $bdp_settings['template_readmorecolor'] : "";
            $readmorehovercolor = (isset($bdp_settings['template_readmorehovercolor']) && $bdp_settings['template_readmorehovercolor'] != '') ? $bdp_settings['template_readmorehovercolor'] : "";
            $readmorebackcolor = (isset($bdp_settings['template_readmorebackcolor']) && $bdp_settings['template_readmorebackcolor'] != '') ? $bdp_settings['template_readmorebackcolor'] : "";
            $readmorebutton_on = (isset($bdp_settings['read_more_on']) && $bdp_settings['read_more_on'] != '') ? $bdp_settings['read_more_on'] : 2;
            
            /**
             * read more button font style setting
             */

            $readmore_font_family = (isset($bdp_settings['readmore_font_family']) && $bdp_settings['readmore_font_family'] != '') ? $bdp_settings['readmore_font_family'] : '';
            if (isset($bdp_settings['readmore_font_family_font_type']) && $bdp_settings['readmore_font_family_font_type'] == 'Google Fonts') {
                $load_goog_font_blog[] = $readmore_font_family;
            }
            $readmore_fontsize = (isset($bdp_settings['readmore_fontsize']) && $bdp_settings['readmore_fontsize'] != '') ? $bdp_settings['readmore_fontsize'] : 16;
            $readmore_font_weight = isset($bdp_settings['readmore_font_weight']) ? $bdp_settings['readmore_font_weight'] : '';
            $readmore_font_line_height = isset($bdp_settings['readmore_font_line_height']) ? $bdp_settings['readmore_font_line_height'] : '';
            $readmore_font_italic = isset($bdp_settings['readmore_font_italic']) ? $bdp_settings['readmore_font_italic'] : 0;
            $readmore_font_text_transform = isset($bdp_settings['readmore_font_text_transform']) ? $bdp_settings['readmore_font_text_transform'] : 'none';
            $readmore_font_text_decoration = isset($bdp_settings['readmore_font_text_decoration']) ? $bdp_settings['readmore_font_text_decoration'] : 'none';
            $readmore_font_letter_spacing = isset($bdp_settings['readmore_font_letter_spacing']) ? $bdp_settings['readmore_font_letter_spacing'] : 0;
            $readmorebuttonborderradius = (isset($bdp_settings['readmore_button_border_radius']) && $bdp_settings['readmore_button_border_radius'] != '') ? $bdp_settings['readmore_button_border_radius'] : "";
            $readmorebuttonalignment = (isset($bdp_settings['readmore_button_alignment']) && $bdp_settings['readmore_button_alignment'] != '') ? $bdp_settings['readmore_button_alignment'] : "";
            $readmore_button_paddingleft = (isset($bdp_settings['readmore_button_paddingleft']) && $bdp_settings['readmore_button_paddingleft'] != '') ? $bdp_settings['readmore_button_paddingleft'] : "10";
            $readmore_button_paddingright = (isset($bdp_settings['readmore_button_paddingright']) && $bdp_settings['readmore_button_paddingright'] != '') ? $bdp_settings['readmore_button_paddingright'] : "10";
            $readmore_button_paddingtop = (isset($bdp_settings['readmore_button_paddingtop']) && $bdp_settings['readmore_button_paddingtop'] != '') ? $bdp_settings['readmore_button_paddingtop'] : "10";
            $readmore_button_paddingbottom = (isset($bdp_settings['readmore_button_paddingbottom']) && $bdp_settings['readmore_button_paddingbottom'] != '') ? $bdp_settings['readmore_button_paddingbottom'] : "10";
            $readmore_button_marginleft = (isset($bdp_settings['readmore_button_marginleft']) && $bdp_settings['readmore_button_marginleft'] != '') ? $bdp_settings['readmore_button_marginleft'] : "";
            $readmore_button_marginright = (isset($bdp_settings['readmore_button_marginright']) && $bdp_settings['readmore_button_marginright'] != '') ? $bdp_settings['readmore_button_marginright'] : "";
            $readmore_button_margintop = (isset($bdp_settings['readmore_button_margintop']) && $bdp_settings['readmore_button_margintop'] != '') ? $bdp_settings['readmore_button_margintop'] : "";
            $readmore_button_marginbottom = (isset($bdp_settings['readmore_button_marginbottom']) && $bdp_settings['readmore_button_marginbottom'] != '') ? $bdp_settings['readmore_button_marginbottom'] : "";
            $read_more_button_border_style = (isset($bdp_settings['read_more_button_border_style']) && $bdp_settings['read_more_button_border_style'] != '') ? $bdp_settings['read_more_button_border_style'] : "";
            $bdp_readmore_button_borderleft = (isset($bdp_settings['bdp_readmore_button_borderleft']) && $bdp_settings['bdp_readmore_button_borderleft'] != '') ? $bdp_settings['bdp_readmore_button_borderleft'] : "";
            $bdp_readmore_button_borderright = (isset($bdp_settings['bdp_readmore_button_borderright']) && $bdp_settings['bdp_readmore_button_borderright'] != '') ? $bdp_settings['bdp_readmore_button_borderright'] : "";
            $bdp_readmore_button_bordertop = (isset($bdp_settings['bdp_readmore_button_bordertop']) && $bdp_settings['bdp_readmore_button_bordertop'] != '') ? $bdp_settings['bdp_readmore_button_bordertop'] : "";
            $bdp_readmore_button_borderbottom = (isset($bdp_settings['bdp_readmore_button_borderbottom']) && $bdp_settings['bdp_readmore_button_borderbottom'] != '') ? $bdp_settings['bdp_readmore_button_borderbottom'] : "";
            $bdp_readmore_button_borderleftcolor = (isset($bdp_settings['bdp_readmore_button_borderleftcolor']) && $bdp_settings['bdp_readmore_button_borderleftcolor'] != '') ? $bdp_settings['bdp_readmore_button_borderleftcolor'] : "";
            $bdp_readmore_button_borderrightcolor = (isset($bdp_settings['bdp_readmore_button_borderrightcolor']) && $bdp_settings['bdp_readmore_button_borderrightcolor'] != '') ? $bdp_settings['bdp_readmore_button_borderrightcolor'] : "";
            $bdp_readmore_button_bordertopcolor = (isset($bdp_settings['bdp_readmore_button_bordertopcolor']) && $bdp_settings['bdp_readmore_button_bordertopcolor'] != '') ? $bdp_settings['bdp_readmore_button_bordertopcolor'] : "";
            $bdp_readmore_button_borderbottomcolor = (isset($bdp_settings['bdp_readmore_button_borderbottomcolor']) && $bdp_settings['bdp_readmore_button_borderbottomcolor'] != '') ? $bdp_settings['bdp_readmore_button_borderbottomcolor'] : "";
            $readmore_button_hover_border_radius = (isset($bdp_settings['readmore_button_hover_border_radius']) && $bdp_settings['readmore_button_hover_border_radius'] != '') ? $bdp_settings['readmore_button_hover_border_radius'] : "";
            $read_more_button_hover_border_style = (isset($bdp_settings['read_more_button_hover_border_style']) && $bdp_settings['read_more_button_hover_border_style'] != '') ? $bdp_settings['read_more_button_hover_border_style'] : "";
            $bdp_readmore_button_hover_borderleft = (isset($bdp_settings['bdp_readmore_button_hover_borderleft']) && $bdp_settings['bdp_readmore_button_hover_borderleft'] != '') ? $bdp_settings['bdp_readmore_button_hover_borderleft'] : "";
            $bdp_readmore_button_hover_borderright = (isset($bdp_settings['bdp_readmore_button_hover_borderright']) && $bdp_settings['bdp_readmore_button_hover_borderright'] != '') ? $bdp_settings['bdp_readmore_button_hover_borderright'] : "";
            $bdp_readmore_button_hover_bordertop = (isset($bdp_settings['bdp_readmore_button_hover_bordertop']) && $bdp_settings['bdp_readmore_button_hover_bordertop'] != '') ? $bdp_settings['bdp_readmore_button_hover_bordertop'] : "";
            $bdp_readmore_button_hover_borderbottom = (isset($bdp_settings['bdp_readmore_button_hover_borderbottom']) && $bdp_settings['bdp_readmore_button_hover_borderbottom'] != '') ? $bdp_settings['bdp_readmore_button_hover_borderbottom'] : "";
            $bdp_readmore_button_hover_borderleftcolor = (isset($bdp_settings['bdp_readmore_button_hover_borderleftcolor']) && $bdp_settings['bdp_readmore_button_hover_borderleftcolor'] != '') ? $bdp_settings['bdp_readmore_button_hover_borderleftcolor'] : "";
            $bdp_readmore_button_hover_borderrightcolor = (isset($bdp_settings['bdp_readmore_button_hover_borderrightcolor']) && $bdp_settings['bdp_readmore_button_hover_borderrightcolor'] != '') ? $bdp_settings['bdp_readmore_button_borderrightcolor'] : "";
            $bdp_readmore_button_hover_bordertopcolor = (isset($bdp_settings['bdp_readmore_button_hover_bordertopcolor']) && $bdp_settings['bdp_readmore_button_bordertopcolor'] != '') ? $bdp_settings['bdp_readmore_button_hover_bordertopcolor'] : "";
            $bdp_readmore_button_hover_borderbottomcolor = (isset($bdp_settings['bdp_readmore_button_hover_borderbottomcolor']) && $bdp_settings['bdp_readmore_button_hover_borderbottomcolor'] != '') ? $bdp_settings['bdp_readmore_button_hover_borderbottomcolor'] : "";
            $readmorehoverbackcolor = (isset($bdp_settings['template_readmore_hover_backcolor']) && $bdp_settings['template_readmore_hover_backcolor'] != '') ? $bdp_settings['template_readmore_hover_backcolor'] : "";
            $alterbackground = (isset($bdp_settings['template_alterbgcolor']) && $bdp_settings['template_alterbgcolor'] != '') ? $bdp_settings['template_alterbgcolor'] : "";
            $titlebackcolor = (isset($bdp_settings['template_titlebackcolor']) && $bdp_settings['template_titlebackcolor'] != '') ? $bdp_settings['template_titlebackcolor'] : "";
            $social_icon_style = (isset($bdp_settings['social_icon_style']) && $bdp_settings['social_icon_style'] != '') ? $bdp_settings['social_icon_style'] : 0;
            $social_style = (isset($bdp_settings['social_style']) && $bdp_settings['social_style'] != '') ? $bdp_settings['social_style'] : '';
            $template_alternativebackground = (isset($bdp_settings['template_alternativebackground']) && $bdp_settings['template_alternativebackground'] != '') ? $bdp_settings['template_alternativebackground'] : 0;
            $firstletter_fontsize = (isset($bdp_settings['firstletter_fontsize']) && $bdp_settings['firstletter_fontsize'] != '') ? $bdp_settings['firstletter_fontsize'] : '';
            $firstletter_font_family = (isset($bdp_settings['firstletter_font_family']) && $bdp_settings['firstletter_font_family'] != '') ? $bdp_settings['firstletter_font_family'] : "";
            if (isset($bdp_settings['firstletter_font_family_font_type']) && $bdp_settings['firstletter_font_family_font_type'] == 'Google Fonts') {
                $load_goog_font_blog[] = $firstletter_font_family;
            }
            $firstletter_contentcolor = (isset($bdp_settings['firstletter_contentcolor']) && $bdp_settings['firstletter_contentcolor'] != '') ? $bdp_settings['firstletter_contentcolor'] : "";
            $firstletter_big = (isset($bdp_settings['firstletter_big']) && $bdp_settings['firstletter_big'] != '') ? $bdp_settings['firstletter_big'] : "";
            $template_titlefontsize = (isset($bdp_settings['template_titlefontsize']) && $bdp_settings['template_titlefontsize'] != '') ? $bdp_settings['template_titlefontsize'] : "";
            $content_fontsize = (isset($bdp_settings['content_fontsize']) && $bdp_settings['content_fontsize'] != '') ? $bdp_settings['content_fontsize'] : "inherit";
            $content_font_family = (isset($bdp_settings['content_font_family']) && $bdp_settings['content_font_family'] != '') ? $bdp_settings['content_font_family'] : '';
            if (isset($bdp_settings['content_font_family_font_type']) && $bdp_settings['content_font_family_font_type'] == 'Google Fonts') {
                $load_goog_font_blog[] = $content_font_family;
            }
            $grid_col_space = (isset($bdp_settings['grid_col_space']) && $bdp_settings['grid_col_space'] != '') ? $bdp_settings['grid_col_space'] : 10;
            $template_alternative_color = (isset($bdp_settings['template_alternative_color']) && $bdp_settings['template_alternative_color'] != '') ? $bdp_settings['template_alternative_color'] : "";
            $story_startup_background = (isset($bdp_settings['story_startup_background']) && $bdp_settings['story_startup_background'] != '') ? $bdp_settings['story_startup_background'] : "";
            $story_startup_text_color = (isset($bdp_settings['story_startup_text_color']) && $bdp_settings['story_startup_text_color'] != '') ? $bdp_settings['story_startup_text_color'] : "";
            $story_ending_background = (isset($bdp_settings['story_ending_background']) && $bdp_settings['story_ending_background'] != '') ? $bdp_settings['story_ending_background'] : "";
            $story_ending_text_color = (isset($bdp_settings['story_ending_text_color']) && $bdp_settings['story_ending_text_color'] != '') ? $bdp_settings['story_ending_text_color'] : "";

            $story_startup_border_color = (isset($bdp_settings['story_startup_border_color']) && $bdp_settings['story_ending_text_color'] != '') ? $bdp_settings['story_startup_border_color'] : "";

            /**
             * Post Title Font style Setting
             */
            $template_title_font_weight = isset($bdp_settings['template_title_font_weight']) ? $bdp_settings['template_title_font_weight'] : '';
            $template_title_font_line_height = isset($bdp_settings['template_title_font_line_height']) ? $bdp_settings['template_title_font_line_height'] : '';
            $template_title_font_italic = isset($bdp_settings['template_title_font_italic']) ? $bdp_settings['template_title_font_italic'] : '';
            $template_title_font_text_transform = isset($bdp_settings['template_title_font_text_transform']) ? $bdp_settings['template_title_font_text_transform'] : 'none';
            $template_title_font_text_decoration = isset($bdp_settings['template_title_font_text_decoration']) ? $bdp_settings['template_title_font_text_decoration'] : 'none';
            $template_title_font_letter_spacing = isset($bdp_settings['template_title_font_letter_spacing']) ? $bdp_settings['template_title_font_letter_spacing'] : '0';

            /**
             * Content Font style Setting
             */
            $content_font_weight = isset($bdp_settings['content_font_weight']) ? $bdp_settings['content_font_weight'] : '';
            $content_font_line_height = isset($bdp_settings['content_font_line_height']) ? $bdp_settings['content_font_line_height'] : '';
            $content_font_italic = isset($bdp_settings['content_font_italic']) ? $bdp_settings['content_font_italic'] : '';
            $content_font_text_transform = isset($bdp_settings['content_font_text_transform']) ? $bdp_settings['content_font_text_transform'] : 'none';
            $content_font_text_decoration = isset($bdp_settings['content_font_text_decoration']) ? $bdp_settings['content_font_text_decoration'] : 'none';
            $content_font_letter_spacing = isset($bdp_settings['content_font_letter_spacing']) ? $bdp_settings['content_font_letter_spacing'] : '0';

            $author_bgcolor = (isset($bdp_settings['author_bgcolor']) && $bdp_settings['author_bgcolor'] != '') ? $bdp_settings['author_bgcolor'] : "";
            /**
             * Author Title Setting
             */
            $author_titlecolor = (isset($bdp_settings['author_titlecolor']) && $bdp_settings['author_titlecolor'] != '') ? $bdp_settings['author_titlecolor'] : "";
            $authorTitleSize = (isset($bdp_settings['author_title_fontsize']) && $bdp_settings['author_title_fontsize'] != '') ? $bdp_settings['author_title_fontsize'] : "";
            $authorTitleFace = (isset($bdp_settings['author_title_fontface']) && $bdp_settings['author_title_fontface'] != '') ? $bdp_settings['author_title_fontface'] : "";
            if (isset($bdp_settings['author_title_fontface_font_type']) && $bdp_settings['author_title_fontface_font_type'] == 'Google Fonts') {
                $load_goog_font_blog[] = $authorTitleFace;
            }
            $author_title_font_weight = isset($bdp_settings['author_title_font_weight']) ? $bdp_settings['author_title_font_weight'] : '';
            $author_title_font_line_height = isset($bdp_settings['author_title_font_line_height']) ? $bdp_settings['author_title_font_line_height'] : '';
            $auhtor_title_font_italic = isset($bdp_settings['auhtor_title_font_italic']) ? $bdp_settings['auhtor_title_font_italic'] : '';
            $author_title_font_text_transform = isset($bdp_settings['author_title_font_text_transform']) ? $bdp_settings['author_title_font_text_transform'] : 'none';
            $author_title_font_text_decoration = isset($bdp_settings['author_title_font_text_decoration']) ? $bdp_settings['author_title_font_text_decoration'] : 'none';
            $author_title_font_letter_spacing = isset($bdp_settings['auhtor_title_font_letter_spacing']) ? $bdp_settings['auhtor_title_font_letter_spacing'] : '0';


            /**
             * Author Content Font style Setting
             */
            $author_content_color = (isset($bdp_settings['author_content_color']) && $bdp_settings['author_content_color'] != '') ? $bdp_settings['author_content_color'] : "";
            $author_content_fontsize = (isset($bdp_settings['author_content_fontsize']) && $bdp_settings['author_content_fontsize'] != '') ? $bdp_settings['author_content_fontsize'] : "";
            $author_content_fontface = (isset($bdp_settings['author_content_fontface']) && $bdp_settings['author_content_fontface'] != '') ? $bdp_settings['author_content_fontface'] : "";
            if (isset($bdp_settings['author_content_fontface_font_type']) && $bdp_settings['author_content_fontface_font_type'] == 'Google Fonts') {
                $load_goog_font_blog[] = $author_content_fontface;
            }
            $author_content_font_weight = isset($bdp_settings['author_content_font_weight']) ? $bdp_settings['author_content_font_weight'] : '';
            $author_content_font_line_height = isset($bdp_settings['author_content_font_line_height']) ? $bdp_settings['author_content_font_line_height'] : '';
            $auhtor_content_font_italic = isset($bdp_settings['auhtor_content_font_italic']) ? $bdp_settings['auhtor_content_font_italic'] : '';
            $author_content_font_text_transform = isset($bdp_settings['author_content_font_text_transform']) ? $bdp_settings['author_content_font_text_transform'] : 'none';
            $author_content_font_text_decoration = isset($bdp_settings['author_content_font_text_decoration']) ? $bdp_settings['author_content_font_text_decoration'] : 'none';
            $auhtor_content_font_letter_spacing = isset($bdp_settings['auhtor_title_font_letterauhtor_content_font_letter_spacing_spacing']) ? $bdp_settings['auhtor_content_font_letter_spacing'] : '0';


            /**
             *  Custom Read More Setting
             */
            $beforeloop_Readmoretext = isset($bdp_settings['beforeloop_Readmoretext']) ? $bdp_settings['beforeloop_Readmoretext'] : '';
            $beforeloop_readmorecolor = isset($bdp_settings['beforeloop_readmorecolor']) ? $bdp_settings['beforeloop_readmorecolor'] : '';
            $beforeloop_readmorebackcolor = isset($bdp_settings['beforeloop_readmorebackcolor']) ? $bdp_settings['beforeloop_readmorebackcolor'] : '';
            $beforeloop_readmorehovercolor = isset($bdp_settings['beforeloop_readmorehovercolor']) ? $bdp_settings['beforeloop_readmorehovercolor'] : '';
            $beforeloop_readmorehoverbackcolor = isset($bdp_settings['beforeloop_readmorehoverbackcolor']) ? $bdp_settings['beforeloop_readmorehoverbackcolor'] : '';
            $beforeloop_titlefontface = (isset($bdp_settings['beforeloop_titlefontface']) && $bdp_settings['beforeloop_titlefontface'] != '') ? $bdp_settings['beforeloop_titlefontface'] : '';
            if (isset($bdp_settings['beforeloop_titlefontface_font_type']) && $bdp_settings['beforeloop_titlefontface_font_type'] == 'Google Fonts') {
                $load_goog_font_blog[] = $beforeloop_titlefontface;
            }
            $beforeloop_titlefontsize = (isset($bdp_settings['beforeloop_titlefontsize']) && $bdp_settings['beforeloop_titlefontsize'] != '') ? $bdp_settings['beforeloop_titlefontsize'] : "inherit";
            $beforeloop_title_font_weight = isset($bdp_settings['beforeloop_title_font_weight']) ? $bdp_settings['beforeloop_title_font_weight'] : '';
            $beforeloop_title_font_line_height = isset($bdp_settings['beforeloop_title_font_line_height']) ? $bdp_settings['beforeloop_title_font_line_height'] : '';
            $beforeloop_title_font_italic = isset($bdp_settings['beforeloop_title_font_italic']) ? $bdp_settings['beforeloop_title_font_italic'] : '';
            $beforeloop_title_font_text_transform = isset($bdp_settings['beforeloop_title_font_text_transform']) ? $bdp_settings['beforeloop_title_font_text_transform'] : 'none';
            $beforeloop_title_font_text_decoration = isset($bdp_settings['beforeloop_title_font_text_decoration']) ? $bdp_settings['beforeloop_title_font_text_decoration'] : 'none';
            $beforeloop_title_font_letter_spacing = isset($bdp_settings['beforeloop_title_font_letter_spacing']) ? $bdp_settings['beforeloop_title_font_letter_spacing'] : '0';
            
            /**
             *  Woocommerce Star Rating
             */
            
            $bdp_star_rating_bg_color = isset($bdp_settings['bdp_star_rating_bg_color']) ? $bdp_settings['bdp_star_rating_bg_color'] : '';
            $bdp_star_rating_color = isset($bdp_settings['bdp_star_rating_color']) ? $bdp_settings['bdp_star_rating_color'] : '';
            $bdp_star_rating_alignment = isset($bdp_settings['bdp_star_rating_alignment']) ? $bdp_settings['bdp_star_rating_alignment'] : 'left';
            $bdp_star_rating_paddingleft = isset($bdp_settings['bdp_star_rating_paddingleft']) ? $bdp_settings['bdp_star_rating_paddingleft'] : '10';
            $bdp_star_rating_paddingright = isset($bdp_settings['bdp_star_rating_paddingright']) ? $bdp_settings['bdp_star_rating_paddingright'] : '10';
            $bdp_star_rating_paddingtop = isset($bdp_settings['bdp_star_rating_paddingtop']) ? $bdp_settings['bdp_star_rating_paddingtop'] : '10';
            $bdp_star_rating_paddingbottom = isset($bdp_settings['bdp_star_rating_paddingbottom']) ? $bdp_settings['bdp_star_rating_paddingbottom'] : '10';
            $bdp_star_rating_marginleft = isset($bdp_settings['bdp_star_rating_marginleft']) ? $bdp_settings['bdp_star_rating_marginleft'] : '10';
            $bdp_star_rating_marginright = isset($bdp_settings['bdp_star_rating_marginright']) ? $bdp_settings['bdp_star_rating_marginright'] : '10';
            $bdp_star_rating_margintop = isset($bdp_settings['bdp_star_rating_margintop']) ? $bdp_settings['bdp_star_rating_margintop'] : '10';
            $bdp_star_rating_marginbottom = isset($bdp_settings['bdp_star_rating_marginbottom']) ? $bdp_settings['bdp_star_rating_marginbottom'] : '10';
            /**
             * Woocommerce sale Tag
             */

            $bdp_sale_tagtextcolor = isset($bdp_settings['bdp_sale_tagtextcolor']) ? $bdp_settings['bdp_sale_tagtextcolor'] : '';
            $bdp_sale_tag_angle = isset($bdp_settings['bdp_sale_tag_angle']) ? $bdp_settings['bdp_sale_tag_angle'] : '';
            $bdp_sale_tag_border_radius = isset($bdp_settings['bdp_sale_tag_border_radius']) ? $bdp_settings['bdp_sale_tag_border_radius'] : '';
            $bdp_sale_tagbgcolor = isset($bdp_settings['bdp_sale_tagbgcolor']) ? $bdp_settings['bdp_sale_tagbgcolor'] : '';
            $bdp_sale_tagtext_alignment = isset($bdp_settings['bdp_sale_tagtext_alignment']) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top';
            $bdp_sale_tagtext_marginleft = isset($bdp_settings['bdp_sale_tagtext_marginleft']) ? $bdp_settings['bdp_sale_tagtext_marginleft'] : '5';
            $bdp_sale_tagtext_marginright = isset($bdp_settings['bdp_sale_tagtext_marginright']) ? $bdp_settings['bdp_sale_tagtext_marginright'] : '5';
            $bdp_sale_tagtext_margintop = isset($bdp_settings['bdp_sale_tagtext_margintop']) ? $bdp_settings['bdp_sale_tagtext_margintop'] : '5';
            $bdp_sale_tagtext_marginbottom = isset($bdp_settings['bdp_sale_tagtext_marginbottom']) ? $bdp_settings['bdp_sale_tagtext_marginbottom'] : '5';
            $bdp_sale_tagtext_paddingleft = isset($bdp_settings['bdp_sale_tagtext_paddingleft']) ? $bdp_settings['bdp_sale_tagtext_paddingleft'] : '5';
            $bdp_sale_tagtext_paddingright = isset($bdp_settings['bdp_sale_tagtext_paddingright']) ? $bdp_settings['bdp_sale_tagtext_paddingright'] : '5';
            $bdp_sale_tagtext_paddingtop = isset($bdp_settings['bdp_sale_tagtext_paddingtop']) ? $bdp_settings['bdp_sale_tagtext_paddingtop'] : '5';
            $bdp_sale_tagtext_paddingbottom = isset($bdp_settings['bdp_sale_tagtext_paddingbottom']) ? $bdp_settings['bdp_sale_tagtext_paddingbottom'] : '5';
            $bdp_sale_tagfontface = (isset($bdp_settings['bdp_sale_tagfontface']) && $bdp_settings['bdp_sale_tagfontface'] != '') ? $bdp_settings['bdp_sale_tagfontface'] : '';
            if (isset($bdp_settings['bdp_sale_tagfontface_font_type']) && $bdp_settings['bdp_sale_tagfontface_font_type'] == 'Google Fonts') {
                $load_goog_font_blog[] = $bdp_sale_tagfontface;
            }
            $bdp_sale_tagfontsize = (isset($bdp_settings['bdp_sale_tagfontsize']) && $bdp_settings['bdp_sale_tagfontsize'] != '') ? $bdp_settings['bdp_sale_tagfontsize'] : "inherit";
            $bdp_sale_tag_font_weight = isset($bdp_settings['bdp_sale_tag_font_weight']) ? $bdp_settings['bdp_sale_tag_font_weight'] : '';
            $bdp_sale_tag_font_line_height = isset($bdp_settings['bdp_sale_tag_font_line_height']) ? $bdp_settings['bdp_sale_tag_font_line_height'] : '';
            $bdp_sale_tag_font_italic = isset($bdp_settings['bdp_sale_tag_font_italic']) ? $bdp_settings['bdp_sale_tag_font_italic'] : '';
            $bdp_sale_tag_font_text_transform = isset($bdp_settings['bdp_sale_tag_font_text_transform']) ? $bdp_settings['bdp_sale_tag_font_text_transform'] : 'none';
            $bdp_sale_tag_font_text_decoration = isset($bdp_settings['bdp_sale_tag_font_text_decoration']) ? $bdp_settings['bdp_sale_tag_font_text_decoration'] : 'none';
            $bdp_sale_tag_font_letter_spacing = isset($bdp_settings['bdp_sale_tag_font_letter_spacing']) ? $bdp_settings['bdp_sale_tag_font_letter_spacing'] : '0';
            
            /**
             * Woocommerce price text 
             */

            $bdp_pricetextcolor = isset($bdp_settings['bdp_pricetextcolor']) ? $bdp_settings['bdp_pricetextcolor'] : '#444444';
            $bdp_pricetext_alignment = isset($bdp_settings['bdp_pricetext_alignment']) ? $bdp_settings['bdp_pricetext_alignment'] : 'left';
            $bdp_pricetext_paddingleft = isset($bdp_settings['bdp_pricetext_paddingleft']) ? $bdp_settings['bdp_pricetext_paddingleft'] : '10';
            $bdp_pricetext_paddingright = isset($bdp_settings['bdp_pricetext_paddingright']) ? $bdp_settings['bdp_pricetext_paddingright'] : '10';
            $bdp_pricetext_paddingtop = isset($bdp_settings['bdp_pricetext_paddingtop']) ? $bdp_settings['bdp_pricetext_paddingtop'] : '10';
            $bdp_pricetext_paddingbottom = isset($bdp_settings['bdp_pricetext_paddingbottom']) ? $bdp_settings['bdp_pricetext_paddingbottom'] : '10';
            $bdp_pricetext_marginleft = isset($bdp_settings['bdp_pricetext_marginleft']) ? $bdp_settings['bdp_pricetext_marginleft'] : '10';
            $bdp_pricetext_marginright = isset($bdp_settings['bdp_pricetext_marginright']) ? $bdp_settings['bdp_pricetext_marginright'] : '10';
            $bdp_pricetext_margintop = isset($bdp_settings['bdp_pricetext_margintop']) ? $bdp_settings['bdp_pricetext_margintop'] : '10';
            $bdp_pricetext_marginbottom = isset($bdp_settings['bdp_pricetext_marginbottom']) ? $bdp_settings['bdp_pricetext_marginbottom'] : '10';
            $bdp_pricefontface = (isset($bdp_settings['bdp_pricefontface']) && $bdp_settings['bdp_pricefontface'] != '') ? $bdp_settings['bdp_pricefontface'] : '';
            if (isset($bdp_settings['bdp_pricefontface_font_type']) && $bdp_settings['bdp_pricefontface_font_type'] == 'Google Fonts') {
                $load_goog_font_blog[] = $bdp_pricefontface;
            }
            $bdp_pricefontsize = (isset($bdp_settings['bdp_pricefontsize']) && $bdp_settings['bdp_pricefontsize'] != '') ? $bdp_settings['bdp_pricefontsize'] : "inherit";
            $bdp_price_font_weight = isset($bdp_settings['bdp_price_font_weight']) ? $bdp_settings['bdp_price_font_weight'] : '';
            $bdp_price_font_line_height = isset($bdp_settings['bdp_price_font_line_height']) ? $bdp_settings['bdp_price_font_line_height'] : '';
            $bdp_price_font_italic = isset($bdp_settings['bdp_price_font_italic']) ? $bdp_settings['bdp_price_font_italic'] : '';
            $bdp_price_font_text_transform = isset($bdp_settings['bdp_price_font_text_transform']) ? $bdp_settings['bdp_price_font_text_transform'] : 'none';
            $bdp_price_font_text_decoration = isset($bdp_settings['bdp_price_font_text_decoration']) ? $bdp_settings['bdp_price_font_text_decoration'] : 'none';

            $bdp_price_font_letter_spacing = isset($bdp_settings['bdp_price_font_letter_spacing']) ? $bdp_settings['bdp_price_font_letter_spacing'] : '0';


             /**
             * Add To Cart Button 
             */
            $bdp_addtocart_textcolor = isset($bdp_settings['bdp_addtocart_textcolor']) ? $bdp_settings['bdp_addtocart_textcolor'] : '';
            $bdp_addtocart_backgroundcolor = isset($bdp_settings['bdp_addtocart_backgroundcolor']) ? $bdp_settings['bdp_addtocart_backgroundcolor'] : '';
            $bdp_addtocart_text_hover_color = isset($bdp_settings['bdp_addtocart_text_hover_color']) ? $bdp_settings['bdp_addtocart_text_hover_color'] : '';
            $bdp_addtocart_hover_backgroundcolor = isset($bdp_settings['bdp_addtocart_hover_backgroundcolor']) ? $bdp_settings['bdp_addtocart_hover_backgroundcolor'] : '';
            $bdp_addtocartbutton_borderleft = isset($bdp_settings['bdp_addtocartbutton_borderleft']) ? $bdp_settings['bdp_addtocartbutton_borderleft'] : '';
            $bdp_addtocartbutton_borderleftcolor = isset($bdp_settings['bdp_addtocartbutton_borderleftcolor']) ? $bdp_settings['bdp_addtocartbutton_borderleftcolor'] : '';
            $bdp_addtocartbutton_borderright = isset($bdp_settings['bdp_addtocartbutton_borderright']) ? $bdp_settings['bdp_addtocartbutton_borderright'] : '';
            $bdp_addtocartbutton_borderrightcolor = isset($bdp_settings['bdp_addtocartbutton_borderrightcolor']) ? $bdp_settings['bdp_addtocartbutton_borderrightcolor'] : '';
            $bdp_addtocartbutton_bordertop = isset($bdp_settings['bdp_addtocartbutton_bordertop']) ? $bdp_settings['bdp_addtocartbutton_bordertop'] : '';
            $bdp_addtocartbutton_bordertopcolor = isset($bdp_settings['bdp_addtocartbutton_bordertopcolor']) ? $bdp_settings['bdp_addtocartbutton_bordertopcolor'] : '';
            $bdp_addtocartbutton_borderbuttom = isset($bdp_settings['bdp_addtocartbutton_borderbuttom']) ? $bdp_settings['bdp_addtocartbutton_borderbuttom'] : '';
            $bdp_addtocartbutton_borderbottomcolor = isset($bdp_settings['bdp_addtocartbutton_borderbottomcolor']) ? $bdp_settings['bdp_addtocartbutton_borderbottomcolor'] : '';
            $display_addtocart_button_border_radius = isset($bdp_settings['display_addtocart_button_border_radius']) ? $bdp_settings['display_addtocart_button_border_radius'] : '';
            $bdp_addtocartbutton_padding_leftright = isset($bdp_settings['bdp_addtocartbutton_padding_leftright']) ? $bdp_settings['bdp_addtocartbutton_padding_leftright'] : '';
            $bdp_addtocartbutton_padding_topbottom = isset($bdp_settings['bdp_addtocartbutton_padding_topbottom']) ? $bdp_settings['bdp_addtocartbutton_padding_topbottom'] : '';
            $bdp_addtocartbutton_margin_leftright = isset($bdp_settings['bdp_addtocartbutton_margin_leftright']) ? $bdp_settings['bdp_addtocartbutton_margin_leftright'] : '';
            $bdp_addtocartbutton_margin_topbottom = isset($bdp_settings['bdp_addtocartbutton_margin_topbottom']) ? $bdp_settings['bdp_addtocartbutton_margin_topbottom'] : '';
            $bdp_addtocartbutton_alignment = isset($bdp_settings['bdp_addtocartbutton_alignment']) ? $bdp_settings['bdp_addtocartbutton_alignment'] : 'left';

            $bdp_addtocartbutton_hover_borderleft = isset($bdp_settings['bdp_addtocartbutton_hover_borderleft']) ? $bdp_settings['bdp_addtocartbutton_hover_borderleft'] : '';
            $bdp_addtocartbutton_hover_borderleftcolor = isset($bdp_settings['bdp_addtocartbutton_hover_borderleftcolor']) ? $bdp_settings['bdp_addtocartbutton_hover_borderleftcolor'] : '';
            $bdp_addtocartbutton_hover_borderright = isset($bdp_settings['bdp_addtocartbutton_hover_borderright']) ? $bdp_settings['bdp_addtocartbutton_hover_borderright'] : '';
            $bdp_addtocartbutton_hover_borderrightcolor = isset($bdp_settings['bdp_addtocartbutton_hover_borderrightcolor']) ? $bdp_settings['bdp_addtocartbutton_hover_borderrightcolor'] : '';
            $bdp_addtocartbutton_hover_bordertop = isset($bdp_settings['bdp_addtocartbutton_hover_bordertop']) ? $bdp_settings['bdp_addtocartbutton_hover_bordertop'] : '';
            $bdp_addtocartbutton_hover_bordertopcolor = isset($bdp_settings['bdp_addtocartbutton_hover_bordertopcolor']) ? $bdp_settings['bdp_addtocartbutton_hover_bordertopcolor'] : '';
            $bdp_addtocartbutton_hover_borderbuttom = isset($bdp_settings['bdp_addtocartbutton_hover_borderbuttom']) ? $bdp_settings['bdp_addtocartbutton_hover_borderbuttom'] : '';
            $bdp_addtocartbutton_hover_borderbottomcolor = isset($bdp_settings['bdp_addtocartbutton_hover_borderbottomcolor']) ? $bdp_settings['bdp_addtocartbutton_hover_borderbottomcolor'] : '';
            $display_addtocart_button_border_hover_radius = isset($bdp_settings['display_addtocart_button_border_hover_radius']) ? $bdp_settings['display_addtocart_button_border_hover_radius'] : '0';

            $bdp_addtocart_button_top_box_shadow = isset($bdp_settings['bdp_addtocart_button_top_box_shadow']) ? $bdp_settings['bdp_addtocart_button_top_box_shadow'] : '';
            $bdp_addtocart_button_top_box_shadow = isset($bdp_settings['bdp_addtocart_button_top_box_shadow']) ? $bdp_settings['bdp_addtocart_button_top_box_shadow'] : '';
            $bdp_addtocart_button_right_box_shadow = isset($bdp_settings['bdp_addtocart_button_right_box_shadow']) ? $bdp_settings['bdp_addtocart_button_right_box_shadow'] : '';
            $bdp_addtocart_button_bottom_box_shadow = isset($bdp_settings['bdp_addtocart_button_bottom_box_shadow']) ? $bdp_settings['bdp_addtocart_button_bottom_box_shadow'] : '';
            $bdp_addtocart_button_left_box_shadow = isset($bdp_settings['bdp_addtocart_button_left_box_shadow']) ? $bdp_settings['bdp_addtocart_button_left_box_shadow'] : '';
            $bdp_addtocart_button_box_shadow_color = isset($bdp_settings['bdp_addtocart_button_box_shadow_color']) ? $bdp_settings['bdp_addtocart_button_box_shadow_color'] : '';
            
            $bdp_addtocart_button_hover_top_box_shadow = isset($bdp_settings['bdp_addtocart_button_hover_top_box_shadow']) ? $bdp_settings['bdp_addtocart_button_hover_top_box_shadow'] : '';
            $bdp_addtocart_button_hover_right_box_shadow = isset($bdp_settings['bdp_addtocart_button_hover_right_box_shadow']) ? $bdp_settings['bdp_addtocart_button_hover_right_box_shadow'] : '';
            $bdp_addtocart_button_hover_bottom_box_shadow = isset($bdp_settings['bdp_addtocart_button_hover_bottom_box_shadow']) ? $bdp_settings['bdp_addtocart_button_hover_bottom_box_shadow'] : '';
            $bdp_addtocart_button_hover_left_box_shadow = isset($bdp_settings['bdp_addtocart_button_hover_left_box_shadow']) ? $bdp_settings['bdp_addtocart_button_hover_left_box_shadow'] : '';
            $bdp_addtocart_button_hover_box_shadow_color = isset($bdp_settings['bdp_addtocart_button_hover_box_shadow_color']) ? $bdp_settings['bdp_addtocart_button_hover_box_shadow_color'] : '';
            if (isset($bdp_settings['bdp_addtocart_button_fontface_font_type']) && $bdp_settings['bdp_addtocart_button_fontface_font_type'] == 'Google Fonts') {
                $load_goog_font_blog[] = $bdp_addtocart_button_fontface;
            }
            $bdp_addtocart_button_fontsize = (isset($bdp_settings['bdp_addtocart_button_fontsize']) && $bdp_settings['bdp_addtocart_button_fontsize'] != '') ? $bdp_settings['bdp_addtocart_button_fontsize'] : "inherit";
            $bdp_addtocart_button_font_weight = isset($bdp_settings['bdp_addtocart_button_font_weight']) ? $bdp_settings['bdp_addtocart_button_font_weight'] : '';
            $bdp_addtocart_button_font_italic = isset($bdp_settings['bdp_addtocart_button_font_italic']) ? $bdp_settings['bdp_addtocart_button_font_italic'] : '';
            $bdp_addtocart_button_letter_spacing = isset($bdp_settings['bdp_addtocart_button_letter_spacing']) ? $bdp_settings['bdp_addtocart_button_letter_spacing'] : '0';

            $display_addtocart_button_line_height = isset($bdp_settings['display_addtocart_button_line_height']) ? $bdp_settings['display_addtocart_button_line_height'] : '1.5';
            $bdp_addtocart_button_font_text_transform = isset($bdp_settings['bdp_addtocart_button_font_text_transform']) ? $bdp_settings['bdp_addtocart_button_font_text_transform'] : 'none';
            $bdp_addtocart_button_font_text_decoration = isset($bdp_settings['bdp_addtocart_button_font_text_decoration']) ? $bdp_settings['bdp_addtocart_button_font_text_decoration'] : 'none';

             /**
             * Add To Whishlist Button 
             */
            $bdp_wishlist_textcolor = isset($bdp_settings['bdp_wishlist_textcolor']) ? $bdp_settings['bdp_wishlist_textcolor'] : '';
            $bdp_wishlist_backgroundcolor = isset($bdp_settings['bdp_wishlist_backgroundcolor']) ? $bdp_settings['bdp_wishlist_backgroundcolor'] : '';
            $bdp_wishlist_text_hover_color = isset($bdp_settings['bdp_wishlist_text_hover_color']) ? $bdp_settings['bdp_wishlist_text_hover_color'] : '';
            $bdp_wishlist_hover_backgroundcolor = isset($bdp_settings['bdp_wishlist_hover_backgroundcolor']) ? $bdp_settings['bdp_wishlist_hover_backgroundcolor'] : '';
            $bdp_wishlistbutton_borderleft = isset($bdp_settings['bdp_wishlistbutton_borderleft']) ? $bdp_settings['bdp_wishlistbutton_borderleft'] : '';
            $bdp_wishlistbutton_borderleftcolor = isset($bdp_settings['bdp_wishlistbutton_borderleftcolor']) ? $bdp_settings['bdp_wishlistbutton_borderleftcolor'] : '';
            $bdp_wishlistbutton_borderright = isset($bdp_settings['bdp_wishlistbutton_borderright']) ? $bdp_settings['bdp_wishlistbutton_borderright'] : '';
            $bdp_wishlistbutton_borderrightcolor = isset($bdp_settings['bdp_wishlistbutton_borderrightcolor']) ? $bdp_settings['bdp_wishlistbutton_borderrightcolor'] : '';
            $bdp_wishlistbutton_bordertop = isset($bdp_settings['bdp_wishlistbutton_bordertop']) ? $bdp_settings['bdp_wishlistbutton_bordertop'] : '';
            $bdp_wishlistbutton_bordertopcolor = isset($bdp_settings['bdp_wishlistbutton_bordertopcolor']) ? $bdp_settings['bdp_wishlistbutton_bordertopcolor'] : '';
            $bdp_wishlistbutton_borderbuttom = isset($bdp_settings['bdp_wishlistbutton_borderbuttom']) ? $bdp_settings['bdp_wishlistbutton_borderbuttom'] : '';
            $bdp_wishlistbutton_borderbottomcolor = isset($bdp_settings['bdp_wishlistbutton_borderbottomcolor']) ? $bdp_settings['bdp_wishlistbutton_borderbottomcolor'] : '';
            $display_wishlist_button_border_radius = isset($bdp_settings['display_wishlist_button_border_radius']) ? $bdp_settings['display_wishlist_button_border_radius'] : '';
            $bdp_wishlistbutton_padding_leftright = isset($bdp_settings['bdp_wishlistbutton_padding_leftright']) ? $bdp_settings['bdp_wishlistbutton_padding_leftright'] : '';
            $bdp_wishlistbutton_padding_topbottom = isset($bdp_settings['bdp_wishlistbutton_padding_topbottom']) ? $bdp_settings['bdp_wishlistbutton_padding_topbottom'] : '';
            $bdp_wishlistbutton_margin_leftright = isset($bdp_settings['bdp_wishlistbutton_margin_leftright']) ? $bdp_settings['bdp_wishlistbutton_margin_leftright'] : '';
            $bdp_wishlistbutton_margin_topbottom = isset($bdp_settings['bdp_wishlistbutton_margin_topbottom']) ? $bdp_settings['bdp_wishlistbutton_margin_topbottom'] : '';
            $bdp_wishlistbutton_alignment = isset($bdp_settings['bdp_wishlistbutton_alignment']) ? $bdp_settings['bdp_wishlistbutton_alignment'] : 'left';
            $bdp_cart_wishlistbutton_alignment = isset($bdp_settings['bdp_cart_wishlistbutton_alignment']) ? $bdp_settings['bdp_cart_wishlistbutton_alignment'] : 'left';
            $bdp_wishlistbutton_hover_borderleft = isset($bdp_settings['bdp_wishlistbutton_hover_borderleft']) ? $bdp_settings['bdp_wishlistbutton_hover_borderleft'] : '';
            $bdp_wishlistbutton_hover_borderleftcolor = isset($bdp_settings['bdp_wishlistbutton_hover_borderleftcolor']) ? $bdp_settings['bdp_wishlistbutton_hover_borderleftcolor'] : '';
            $bdp_wishlistbutton_hover_borderright = isset($bdp_settings['bdp_wishlistbutton_hover_borderright']) ? $bdp_settings['bdp_wishlistbutton_hover_borderright'] : '';
            $bdp_wishlistbutton_hover_borderrightcolor = isset($bdp_settings['bdp_wishlistbutton_hover_borderrightcolor']) ? $bdp_settings['bdp_wishlistbutton_hover_borderrightcolor'] : '';
            $bdp_wishlistbutton_hover_bordertop = isset($bdp_settings['bdp_wishlistbutton_hover_bordertop']) ? $bdp_settings['bdp_wishlistbutton_hover_bordertop'] : '';
            $bdp_wishlistbutton_hover_bordertopcolor = isset($bdp_settings['bdp_wishlistbutton_hover_bordertopcolor']) ? $bdp_settings['bdp_wishlistbutton_hover_bordertopcolor'] : '';
            $bdp_wishlistbutton_hover_borderbuttom = isset($bdp_settings['bdp_wishlistbutton_hover_borderbuttom']) ? $bdp_settings['bdp_wishlistbutton_hover_borderbuttom'] : '';
            $bdp_wishlistbutton_hover_borderbottomcolor = isset($bdp_settings['bdp_wishlistbutton_hover_borderbottomcolor']) ? $bdp_settings['bdp_wishlistbutton_hover_borderbottomcolor'] : '';
            $display_wishlist_button_border_hover_radius = isset($bdp_settings['display_wishlist_button_border_hover_radius']) ? $bdp_settings['display_wishlist_button_border_hover_radius'] : '0';
            $bdp_wishlist_button_top_box_shadow = isset($bdp_settings['bdp_wishlist_button_top_box_shadow']) ? $bdp_settings['bdp_wishlist_button_top_box_shadow'] : '';
            $bdp_wishlist_button_right_box_shadow = isset($bdp_settings['bdp_wishlist_button_right_box_shadow']) ? $bdp_settings['bdp_wishlist_button_right_box_shadow'] : '';
            $bdp_wishlist_button_bottom_box_shadow = isset($bdp_settings['bdp_wishlist_button_bottom_box_shadow']) ? $bdp_settings['bdp_wishlist_button_bottom_box_shadow'] : '';
            $bdp_wishlist_button_left_box_shadow = isset($bdp_settings['bdp_wishlist_button_left_box_shadow']) ? $bdp_settings['bdp_wishlist_button_left_box_shadow'] : '';
            $bdp_wishlist_button_box_shadow_color = isset($bdp_settings['bdp_wishlist_button_box_shadow_color']) ? $bdp_settings['bdp_wishlist_button_box_shadow_color'] : '';
            $bdp_wishlist_button_hover_top_box_shadow = isset($bdp_settings['bdp_wishlist_button_hover_top_box_shadow']) ? $bdp_settings['bdp_wishlist_button_hover_top_box_shadow'] : '';
            $bdp_wishlist_button_hover_right_box_shadow = isset($bdp_settings['bdp_wishlist_button_hover_right_box_shadow']) ? $bdp_settings['bdp_wishlist_button_hover_right_box_shadow'] : '';
            $bdp_wishlist_button_hover_bottom_box_shadow = isset($bdp_settings['bdp_wishlist_button_hover_bottom_box_shadow']) ? $bdp_settings['bdp_wishlist_button_hover_bottom_box_shadow'] : '';
            $bdp_wishlist_button_hover_left_box_shadow = isset($bdp_settings['bdp_wishlist_button_hover_left_box_shadow']) ? $bdp_settings['bdp_wishlist_button_hover_left_box_shadow'] : '';
            $bdp_wishlist_button_hover_box_shadow_color = isset($bdp_settings['bdp_wishlist_button_hover_box_shadow_color']) ? $bdp_settings['bdp_wishlist_button_hover_box_shadow_color'] : '';
            $bdp_wishlistbutton_on = isset($bdp_settings['bdp_wishlistbutton_on']) ? $bdp_settings['bdp_wishlistbutton_on'] : '1';
            $display_wishlist_button_line_height = isset($bdp_settings['display_wishlist_button_line_height']) ? $bdp_settings['display_wishlist_button_line_height'] : '1.5';
            $bdp_addtowishlist_button_font_text_transform = isset($bdp_settings['bdp_addtowishlist_button_font_text_transform']) ? $bdp_settings['bdp_addtowishlist_button_font_text_transform'] : 'none';
            $bdp_addtowishlist_button_font_text_decoration = isset($bdp_settings['bdp_addtowishlist_button_font_text_decoration']) ? $bdp_settings['bdp_addtowishlist_button_font_text_decoration'] : 'none';
           
           /** Pagination  */
            $pagination_text_color = isset($bdp_settings['pagination_text_color']) ? $bdp_settings['pagination_text_color'] : '#fff';
            $pagination_background_color = isset($bdp_settings['pagination_background_color']) ? $bdp_settings['pagination_background_color'] : '#777';
            $pagination_text_hover_color = isset($bdp_settings['pagination_text_hover_color']) ? $bdp_settings['pagination_text_hover_color'] : '';
            $pagination_background_hover_color = isset($bdp_settings['pagination_background_hover_color']) ? $bdp_settings['pagination_background_hover_color'] : '';
            $pagination_text_active_color = isset($bdp_settings['pagination_text_active_color']) ? $bdp_settings['pagination_text_active_color'] : '';
            $pagination_active_background_color = isset($bdp_settings['pagination_active_background_color']) ? $bdp_settings['pagination_active_background_color'] : '';
            $pagination_border_color = isset($bdp_settings['pagination_border_color']) ? $bdp_settings['pagination_border_color'] : '#b2b2b2';
            $pagination_active_border_color = isset($bdp_settings['pagination_active_border_color']) ? $bdp_settings['pagination_active_border_color'] : '#007acc';
            
/**
             * Slider Image height
             */
            $slider_image_height = isset($bdp_settings['media_custom_height']) ? $bdp_settings['media_custom_height'] : '';
            include(WP_PLUGIN_DIR . '/blog-designer-pro/css/layout_dynamic_style.php');
        }
        echo $before_widget;
        echo '<h2 class="widget-title">'.$title.'</h2>';
        echo do_shortcode('[wp_blog_designer id=' . "$blog_designer_pro_shortcode_list" . ']');
        echo $after_widget;
    }

    public function form($instance) {
        $blog_designer_pro_shortcode_list = isset($instance['blog_designer_pro_shortcode_list']) ? $instance['blog_designer_pro_shortcode_list'] : '';
        $title = !empty($instance['title']) ? $instance['title'] : '';
        ?>
        <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title', 'kosmic'); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
        <p>
            <label for="blog_designer_pro_shortcode_list"><?php _e('Select Blog Layout', BLOGDESIGNERPRO_TEXTDOMAIN) ?>:</label>
            <select name="<?php echo $this->get_field_name('blog_designer_pro_shortcode_list'); ?>" class="blog_designer_pro_shortcode_list" id="blog_designer_pro_shortcode_list" style="width: 100%;">
                <option value="">-- <?php _e('Select Blog Layout', BLOGDESIGNERPRO_TEXTDOMAIN); ?> --</option>
                <?php
                global $wpdb;
                $shortcodes = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'blog_designer_pro_shortcodes ');
                if ($shortcodes) {
                    foreach ($shortcodes as $shortcode) {
                        $shortcode_name = $shortcode->shortcode_name;
                        ?>
                        <option value="<?php echo $shortcode->bdid; ?>" <?php
                        if ($blog_designer_pro_shortcode_list == $shortcode->bdid) {
                            echo 'selected=selected';
                        }
                        ?>><?php
                            if ($shortcode_name) {
                                echo $shortcode_name;
                            } else {
                                _e('No title', BLOGDESIGNERPRO_TEXTDOMAIN);
                            }
                            ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['blog_designer_pro_shortcode_list'] = (!empty($new_instance['blog_designer_pro_shortcode_list']) ) ? (int) ($new_instance['blog_designer_pro_shortcode_list']) : '';

        $alloptions = wp_cache_get('alloptions', 'options');
        if (isset($alloptions['widgte_blog_designer_pro_shortcode_list']))
            delete_option('widgte_blog_designer_pro_shortcode_list');

        return $instance;
    }

    public function flush_widgte_blog_designer_pro_shortcode_list() {
        wp_cache_delete('widgte_blog_designer_pro_shortcode_list', 'widget');
    }

}
