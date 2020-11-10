<?php

/*
Plugin Name:  WP PleaseWait
Text Domain:  wp-pleasewait
Domain Path: /languages/
Version:      2.2.3
Description:  Just an awesome splash screen for your website (or PWA), support 12+ spinner styles and many customizable things - message text, spinner size, background, text color...
Plugin URI:   https://ngoclb.com/project/wp-please-wait
Author:       Ngoc L.B.
Author URI:   https://ngoclb.com/
License:      MIT License
*/

if (!defined('ABSPATH')) {
    die();
}

require_once 'pleasewait-settings.php';

class WpPleaseWait
{

    private static $instance;
    private $options;

    public function __construct()
    {
        $this->options = WpPleaseWait_SettingsPage::getInstance()->get_options();
        // print_r($this->options); die;
        add_action('wp', array($this, 'load_actions'), 1, 0);
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
    }

    public function uninstall()
    {
        WpPleaseWait_SettingsPage::getInstance()->clear_settings();
    }

    /**
     * Singleton instance
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Load translations
     */
    function load_plugin_textdomain() {
        load_plugin_textdomain('wp-pleasewait', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Check if user enable/disable show PleaseWait screen in this page
     */
    function is_pleasewait_screen_enabled()
    {
        if (is_admin()) return false;
        $is_enable = empty($this->options) || empty($this->options['display_scopes']);
        if (!$is_enable) {
            $scopes = $this->options['display_scopes'];
            foreach ($scopes as $scope => $value) {
                switch ($scope) {
                    case WpPleaseWait_SettingsPage::SCOPE_FRONT_PAGE:
                        $is_enable = is_front_page();
                        // printf('is_front_page: %d', $is_enable);
                        break;
                    case WpPleaseWait_SettingsPage::SCOPE_HOME_PAGE:
                        $is_enable = is_home();
                        // printf('is_home: %d', $is_enable);
                        break;
                    case WpPleaseWait_SettingsPage::SCOPE_ARCHIVE_PAGE:
                        $is_enable = is_archive();
                        // printf('is_archive: %d', $is_enable);
                        break;
                    case WpPleaseWait_SettingsPage::SCOPE_SEARCH_PAGE:
                        $is_enable = is_search();
                        // printf('is_search: %d', $is_enable);
                        break;
                    case WpPleaseWait_SettingsPage::SCOPE_SINGLE_PAGE:
                        $is_enable = is_page();
                        // printf('is_page: %d', $is_enable);
                        break;
                    case WpPleaseWait_SettingsPage::SCOPE_SINGLE_POST:
                        $is_enable = is_single();
                        // printf('is_single: %d', $is_enable);
                        break;
                    case WpPleaseWait_SettingsPage::SCOPE_INCLUDES_ID:
                        $ids = isset($scopes[WpPleaseWait_SettingsPage::SCOPE_INCLUDES_ID_VALUE]) ? explode(', ', $scopes[WpPleaseWait_SettingsPage::SCOPE_INCLUDES_ID_VALUE]) : array();
                        if ($queried_id = get_queried_object_id()) {
                            $is_enable = in_array($queried_id, $ids);
                        }
                        // printf('is_include: %d %d %s', $is_enable, $queried_id, json_encode($ids));
                        break;
                }
                if ($is_enable) break;
            }
        }
        return apply_filters('wp_pleasewait_enable', $is_enable);
    }

    function load_actions()
    {
        if (!$this->is_pleasewait_screen_enabled()) return;
        // Hook
        add_action('wp_enqueue_scripts', array($this, 'add_styles_scripts'));
        if ($this->options['auto_mode']) {
            add_action('wp_head', array($this, 'add_inline_styles'), 999);
            add_action('wp_footer', array($this, 'add_inline_scripts'), 999);
        } else {
            $hook_name = $this->options['hook_name'];
            add_action($hook_name, array($this, 'add_inline_styles'));
            add_action($hook_name, array($this, 'add_inline_scripts'));
        }
    }

    function add_styles_scripts()
    {
        wp_enqueue_style(
            'spinkit',
            ($this->options['use_cdn'] ? 'https://cdnjs.cloudflare.com/ajax/libs/spinkit/' . WpPleaseWait_SettingsPage::SPINKIT_VERSION : WpPleaseWait_SettingsPage::getInstance()->get_assets_url('assets'))
                . '/spinkit.min.css',
            array(),
            WpPleaseWait_SettingsPage::SPINKIT_VERSION
        );
        wp_enqueue_style(
            'please-wait-style',
            ($this->options['use_cdn'] ? 'https://cdnjs.cloudflare.com/ajax/libs/please-wait/' . WpPleaseWait_SettingsPage::PLEASEWAIT_VERSION : WpPleaseWait_SettingsPage::getInstance()->get_assets_url('assets'))
                . '/please-wait.min.css',
            array(),
            WpPleaseWait_SettingsPage::PLEASEWAIT_VERSION
        );
        wp_enqueue_script(
            'please-wait-js',
            ($this->options['use_cdn'] ? 'https://cdnjs.cloudflare.com/ajax/libs/please-wait/0.0.5' : WpPleaseWait_SettingsPage::getInstance()->get_assets_url('assets'))
                . '/please-wait.min.js',
            array(),
            WpPleaseWait_SettingsPage::PLEASEWAIT_VERSION,
            false
        );
    }

    function load_filters()
    {
        // add_filter('script_loader_tag', array($this, 'add_data_attribute'), 10, 2);
        // add_filter('style_loader_tag', array($this, 'add_data_attribute'), 10, 2);
    }

    function add_data_attribute($tag, $handle)
    {
        if ($handle === 'please-wait-style') {
            return str_replace(' href', ' data-no-async="1" data-no-optimize="1" href', $tag);
        } else if ($handle === 'please-wait-js') {
            return str_replace(' src', ' data-no-defer="1" data-no-optimize="1" src', $tag);
        }

        return $tag;
    }

    function combine_to_oneline($html_css_js, $rm_trailing_space = true)
    {
        $one_line = str_replace(array("\r\n", "\r", "\n", "\t"), "  ", $html_css_js);
        if ($rm_trailing_space) {
            while (strpos($one_line, "  ") > -1) {
                $one_line = str_replace("  ", " ", $one_line);
            }
        }
        return $one_line;
    }

    function get_plugin_info()
    {
        $ver = WpPleaseWait_SettingsPage::CURRENT_VERSION;
        $url = WpPleaseWait_SettingsPage::GITHUB_URL;
        return "<!-- Generated by WP PleaseWait v${ver} - ${url} -->\n";
    }

    function get_spinner_style_html()
    {
        return WpPleaseWait_SettingsPage::SPINNER_STYLES[$this->options['spinner_style']];
    }

    function get_loading_template()
    {
        $message = isset($this->options['custom_message']) ? trim($this->options['custom_message']) : '';
        if (!empty($message)) {
            $message = explode("\n", $message);
            shuffle($message);
            $message = reset($message);
        }
        $message = apply_filters('wp_pleasewait_message', $message);
        $tpl_spinner = '<div class="pg-loading-html"></div>';
        $tpl_loading = '<div class="loading-message">' . $message . '</div>';
        $tpl_loading = isset($this->options['custom_message_pos']) && $this->options['custom_message_pos'] === 'below'
            ? $tpl_spinner . $tpl_loading
            : $tpl_loading . $tpl_spinner;

        return sprintf($this->options['loading_template'], $tpl_loading);
    }

    function add_inline_styles()
    {
        $sk_size = $this->options['sk_size'];
        $text_color = $this->options['text_color'];
        // $bg_color = $this->options['bg_color'];
        $css = <<<CSS
    :root{--sk-size:${sk_size}px;--sk-color:${text_color}}
    html.pg-loading {
      overflow: hidden;
    }
    html:not(.pg-loading):not(.pg-loaded) body {
      opacity: 0!important;
      visibility: hidden;
    }
    .pg-loading-screen .sk-flow { align-items: center }
    .pg-loading-screen .pg-loading-html { margin-top: 0 }
    .pg-loading-screen .pg-loading-html > div { margin: 0 auto}
    .pg-loading-screen .loading-message { color: {$text_color} }
CSS;
        // echo $this->get_plugin_info();
        echo sprintf("<style type='text/css'>%s</style>", $this->combine_to_oneline($css));
        // Start get document source code
        if ($this->options['auto_mode']) {
            ob_start();
        }
    }

    function add_inline_scripts()
    {
        $template = addslashes_gpc($this->combine_to_oneline($this->get_loading_template()));
        $spinner_style = addslashes_gpc($this->combine_to_oneline($this->get_spinner_style_html()));
        $delayMs = $this->options['delay'];
        $timeoutMs = $this->options['timeout'];
        $isTestMode = $this->options['test_mode'] && current_user_can('administrator') ? 'true' : 'false';
        $js = <<<JS
  var rootelem = document.querySelector('html'),
    isTestMode = ${isTestMode};
  if (window.pleaseWait) {
    loadingScreen = pleaseWait({
      logo: false,
      template: "{$template}",
      backgroundColor: "{$this->options['bg_color']}",
      loadingHtml: "{$spinner_style}",
      onLoadedCallback: function() { setTimeout(function(){rootelem.className = rootelem.className.replace('pg-loading', 'pg-loaded').trim();} , 200); }
    });
    rootelem.className += ' pg-loading';
    function hideLoadingScreen() { !isTestMode && loadingScreen.finish(); }
    document.addEventListener("DOMContentLoaded", function() { setTimeout(hideLoadingScreen, {$delayMs}) });
    !!(${timeoutMs}) && setTimeout(hideLoadingScreen, {$timeoutMs}*1000);
  } else { rootelem.className += ' pg-loaded no-pleasewaitjs'; }
JS;
        $plugin_info = $this->get_plugin_info();
        $js_code = sprintf("<script type='text/javascript'>%s</script>", $this->combine_to_oneline($js));
        if ($this->options['auto_mode']) {
            $content = ob_get_clean();
            $content = preg_replace('/<body([^>]*)>/i', '<body$1>' . "\n{$plugin_info}{$js_code}", $content);
            echo $content;
        } else {
            echo $plugin_info;
            echo $js_code;
        }
    }
}

WpPleaseWait::getInstance();
register_uninstall_hook(plugin_basename(__FILE__), array('WpPleaseWait', 'uninstall'));