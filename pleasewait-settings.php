<?php
class WpPleaseWait_SettingsPage
{
    const CURRENT_VERSION    = '2.2.0';
    const SPINKIT_VERSION    = '2.0.1'; // https: //github.com/tobiasahlin/SpinKit
    const PLEASEWAIT_VERSION = '0.0.5'; // https: //github.com/Pathgather/please-wait
    const GITHUB_URL = 'https://github.com/lbngoc/wp-please-wait'; // null|string
    const PLUGIN_URL = 'https://wordpress.org/support/plugin/wp-pleasewait'; // null|string
    const DEMO_URL   = 'http://pathgather.github.io/please-wait';
    const AUTHOR_URL = 'https://ngoclb.com/project/wp-please-wait';

    // Display scopes
    const SCOPE_ALL_PAGE          = 'SCOPE_ALL_PAGE';
    const SCOPE_FRONT_PAGE        = 'SCOPE_FRONT_PAGE';
    const SCOPE_HOME_PAGE         = 'SCOPE_HOME_PAGE';
    const SCOPE_ARCHIVE_PAGE      = 'SCOPE_ARCHIVE_PAGE';
    const SCOPE_SEARCH_PAGE       = 'SCOPE_SEARCH_PAGE';
    const SCOPE_SINGLE_PAGE       = 'SCOPE_SINGLE_PAGE';
    const SCOPE_SINGLE_POST       = 'SCOPE_SINGLE_POST';
    const SCOPE_INCLUDES_ID       = 'SCOPE_INCLUDES_ID';
    const SCOPE_INCLUDES_ID_VALUE = 'SCOPE_INCLUDES_ID_VALUE';

    // Spinner styles
    const SK_NONE        = '0-no-spinner';
    const SK_PLANE       = '1-rotating-plane';
    const SK_BOUNCE      = '2-double-bounce';
    const SK_WAVE        = '3-wave';
    const SK_WANDER      = '4-wandering-cubes';
    const SK_PULSE       = '5-pulse';
    const SK_SWING       = '6-chasing-dots';
    const SK_FLOW        = '7-three-bounce';
    const SK_CIRCLE      = '8-circle';
    const SK_GRID        = '9-cube-grid';
    const SK_CIRCLE_FADE = '10-fading-circle';
    const SK_FOLD        = '11-folding-cube';
    const SK_CHASE       = '12-chase';
    const SPINNER_STYLES = array(
        self::SK_NONE => '',
        self::SK_PLANE => '<div class="sk-plane"></div>',
        self::SK_CHASE => '<div class="sk-chase">
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
        </div>',
        self::SK_BOUNCE => '<div class="sk-bounce">
            <div class="sk-bounce-dot"></div>
            <div class="sk-bounce-dot"></div>
        </div>',
        self::SK_WAVE => '<div class="sk-wave">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
        </div>',
        self::SK_PULSE => '<div class="sk-pulse"></div>',
        self::SK_FLOW => '<div class="sk-flow">
            <div class="sk-flow-dot"></div>
            <div class="sk-flow-dot"></div>
            <div class="sk-flow-dot"></div>
        </div>',
        self::SK_SWING => '<div class="sk-swing">
            <div class="sk-swing-dot"></div>
            <div class="sk-swing-dot"></div>
        </div>',
        self::SK_CIRCLE => '<div class="sk-circle">
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
        </div>',
        self::SK_CIRCLE_FADE => '<div class="sk-circle-fade">
            <div class="sk-circle-fade-dot"></div>
            <div class="sk-circle-fade-dot"></div>
            <div class="sk-circle-fade-dot"></div>
            <div class="sk-circle-fade-dot"></div>
            <div class="sk-circle-fade-dot"></div>
            <div class="sk-circle-fade-dot"></div>
            <div class="sk-circle-fade-dot"></div>
            <div class="sk-circle-fade-dot"></div>
            <div class="sk-circle-fade-dot"></div>
            <div class="sk-circle-fade-dot"></div>
            <div class="sk-circle-fade-dot"></div>
            <div class="sk-circle-fade-dot"></div>
        </div>',
        self::SK_GRID => '<div class="sk-grid">
            <div class="sk-grid-cube"></div>
            <div class="sk-grid-cube"></div>
            <div class="sk-grid-cube"></div>
            <div class="sk-grid-cube"></div>
            <div class="sk-grid-cube"></div>
            <div class="sk-grid-cube"></div>
            <div class="sk-grid-cube"></div>
            <div class="sk-grid-cube"></div>
            <div class="sk-grid-cube"></div>
        </div>',
        self::SK_FOLD => '<div class="sk-fold">
            <div class="sk-fold-cube"></div>
            <div class="sk-fold-cube"></div>
            <div class="sk-fold-cube"></div>
            <div class="sk-fold-cube"></div>
        </div>',
        self::SK_WANDER => '<div class="sk-wander">
            <div class="sk-wander-cube"></div>
            <div class="sk-wander-cube"></div>
            <div class="sk-wander-cube"></div>
        </div>'
    );

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private static $instance;

    private $default_options = array(
        'auto_mode'           => true,
        'test_mode'           => false,
        'use_cdn'             => false,
        'bg_color'            => '#f46d3b',
        'text_color'          => '#eeeeee',
        'loading_template'    => '<div class="pg-loading-inner">
            <div class="pg-loading-center-outer">
                <div class="pg-loading-center-middle">
                    %s
            </div></div></div>',
        'custom_message'      => '<p style="font-size: xx-large; font-style: italic; font-family: cursive">Welcome</p>',
        'custom_message_pos'  => 'above',
        'custom_message_pos_list' => array(
            'above' => 'Above the spinner',
            'below' => 'Below the spinner',
        ),
        'spinner_style'       => self::SK_WAVE,
        'spinner_scale'       => 1,  // hidden since 2.1.0
        'sk_size'             => 40, // px
        'timeout'             => 10, // s
        'delay'               => 100, //ms
        'allow_tags'          => '<h1><h2><h3><p><a><strong><em><span><img>',
        'display_scopes'       => []
    );

    /**
     * Start up
     */
    public function __construct()
    {
        $this->options = get_option('wppleasewait_settings');
        // print_r($this->options); die;
        $this->default_options['hook_name'] = $this->get_hook_name();
        if (is_admin()) {
            add_action('admin_enqueue_scripts', array($this, 'load_admin_assets'), 9);
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
        }
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
     * Remove all settings in database
     */
    public function clear_settings()
    {
        delete_option('wppleasewait_settings');
    }

    /**
     * Assets file for WP Pleasewait Settings
     */
    public function load_admin_assets()
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('spinkit-css', $this->get_assets_url('assets') . '/spinkit.min.css');
    }

    /**
     * Get assets URL
     */
    public function get_assets_url($path)
    {
        return plugins_url($path, __FILE__);
    }

    /**
     * Get dashboard primary color
     */
    private function get_admin_color_scheme($index = 0)
    {
        global $_wp_admin_css_colors;
        $current_color = get_user_option('admin_color');
        $color = $_wp_admin_css_colors[$current_color]->colors[$index];
        return $color;
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'WP PleaseWait',
            'WP PleaseWait',
            'manage_options',
            'wppleasewait-setting-admin',
            array($this, 'create_admin_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
?>
        <div class="wrap">
            <h1>WP PleaseWait Settings <small style="float: right; font-size: 50%"><?php echo self::CURRENT_VERSION; ?></small></h1>
            <div id="poststuff">
                <div id="post-body" class="columns-2">
                    <div id="post-body-content">
                        <div class="stuffbox">
                            <form method="post" action="options.php">
                                <p class="submit">
                                    <?php
                                    submit_button("Reset to defaults", 'secondary', null, false, ['name' => 'reset']);
                                    submit_button("Save changes", 'primary pull-right', 'submit', false);
                                    ?>
                                </p>
                                <hr />
                                <?php
                                // This prints out all hidden setting fields
                                settings_fields('wppleasewait');
                                do_settings_sections('wppleasewait-setting-admin');
                                ?>
                                <hr />
                                <p class="submit">
                                    <?php
                                    submit_button("Reset to defaults", 'secondary', null, false, ['name' => 'reset']);
                                    submit_button("Save changes", 'primary pull-right', 'submit', false);
                                    ?>
                                </p>
                            </form>
                        </div>
                    </div>
                    <div id="postbox-container-1" class="postbox-container">
                        <div class="postbox">
                            <h2>Information</h2>
                            <div class="inside">
                                <div class="main">
                                    <ul>
                                        <?php if (self::PLUGIN_URL !== null) : ?>
                                            <li class="dashicons-before dashicons-wordpress" style="color: #82878c">
                                                <a href="<?php echo self::PLUGIN_URL; ?>" style="text-decoration: none" target="_blank">FAQs & Support</a>
                                            </li>
                                            <li class="dashicons-before dashicons-star-filled" style="color: #82878c">
                                                <a href="<?php echo self::PLUGIN_URL; ?>/reviews/?rate=5#new-post" style="text-decoration: none" target="_blank">
                                                    Rate this plugin</a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (self::GITHUB_URL !== null) : ?>
                                            <li class="dashicons-before dashicons-flag" style="color: #82878c">
                                                <a href="<?php echo self::GITHUB_URL; ?>/issues/new" style="text-decoration: none" target="_blank">Report an issue</a>
                                            </li>
                                        <?php endif; ?>
                                        <li class="dashicons-before dashicons-admin-links" style="color: #82878c">
                                            <a href="<?php echo self::AUTHOR_URL; ?>" style="text-decoration: none" target="_blank">Visit author website</a>
                                        </li>
                                    </ul>
                                    <p style="text-align: center"><a href='https://ko-fi.com/L3L4B8JX' style='display: flex; justify-content: center' target='_blank'><img height='36' style='border:0px;height:36px;' src='<?php echo $this->get_assets_url('assets/kofi2.png'); ?>' border='0' alt='Buy Me a Coffee at ko-fi.com' /></a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style type="text/css">
            :root {
                --sk-size: 24px;
                --sk-color: <?php
                            echo $this->get_admin_color_scheme(2); ?>
            }

            .sk-flow { align-items: center }

            .stuffbox {
                padding: 0 20px
            }

            .full-width {
                width: 100%
            }

            .pull-right {
                float: right;
            }

            label p.description {
                display: inline;
            }

            .form-table td .td {
                padding: 4px 0 6px 0;
                margin-bottom: 0;
            }

            .form-table td .td label {
                vertical-align: initial;
            }

            #preview-html {
                display: inline-block;
                vertical-align: middle;
                margin-left: 50px;
            }
        </style>
        <script type="text/javascript">
            (function($) {
                $(document).ready(function() {
                    $.fn.wpColorPicker && $('.wp-color-picker').wpColorPicker();
                    $('.button[name="reset"]').click(function(e) {
                        if (!window.confirm('Are you sure you want to reset all settings?')) {
                            e.preventDefault();
                        }
                    });
                    $('input#auto_mode').change(function(e) {
                        if ($(e.target).is(':checked')) {
                            $('tr.hook_name').fadeOut(500);
                        } else {
                            $('tr.hook_name').fadeIn(500);
                        }
                    });
                    $('select#spinner_style').change(function(e) {
                        if (spinkitStyles) {
                            let html = spinkitStyles[$(e.target).val()];
                            (html !== undefined) && $('#preview-html').html(html);
                        }
                    });
                    $('input#display_scopes_SCOPE_ALL_PAGE').change(function(e) {
                        let $otherItems = $(e.target).closest('td').find('.td:not(:nth-child(1))');
                        // console.log($otherItems)
                        if ($(e.target).is(':checked')) {
                            $otherItems.fadeOut(500).removeAttr('style');
                        } else {
                            $otherItems.fadeIn(500);
                        }
                    });
                    $('input#display_scopes_SCOPE_INCLUDES_ID').change(function(e) {
                        if ($(e.target).is(':checked')) {
                            $('input#display_scopes_SCOPE_INCLUDES_ID_VALUE').removeAttr('readonly')
                        } else {
                            $('input#display_scopes_SCOPE_INCLUDES_ID_VALUE').attr('readonly', '');
                        }
                    });
                });
            })(jQuery);
            var spinkitStyles = <?php echo WpPleaseWait::getInstance()->combine_to_oneline(json_encode(self::SPINNER_STYLES)); ?>
        </script>
<?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'wppleasewait', // Option group
            'wppleasewait_settings', // Option name
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            '', // Title
            array($this, 'print_section_info'), // Callback
            'wppleasewait-setting-admin' // Page
        );

        add_settings_field(
            'auto_mode',
            'Auto Mode',
            array($this, 'auto_mode_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'hook_name',
            'Hook Name',
            array($this, 'hook_name_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id',
            array('class' => 'hook_name ' . ($this->options['auto_mode'] ? 'hidden' : ''))
        );

        add_settings_field(
            'test_mode',
            'Testing Mode',
            array($this, 'test_mode_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'use_cdn',
            'Use CDN',
            array($this, 'use_cdn_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'bg_color',
            'Background Color',
            array($this, 'bg_color_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );
        add_settings_field(
            'text_color',
            'Text Color',
            array($this, 'text_color_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'custom_message',
            'Message Text',
            array($this, 'custom_message_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'custom_message_pos',
            'Message Position',
            array($this, 'custom_message_pos_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'spinner_style',
            'Spinner Style',
            array($this, 'spinner_style_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'sk_size',
            'Spinner Size',
            array($this, 'spinner_size_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'timeout',
            'Max Display Time',
            array($this, 'timeout_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'delay',
            'Disappearance Delay Time',
            array($this, 'delay_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'display_scopes',
            'Display Scopes',
            array($this, 'display_scopes_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );
    }

    private function is_valid_color($color)
    {
        if (empty($color)) {
            return false;
        } else if ($color[0] === "#") {
            $color = substr($color, 1);
            return in_array(strlen($color), [3, 4, 6, 8]) && ctype_xdigit($color);
        } else {
            return preg_match("/^(rgb|hsl)a?\((\d+%?(deg|rad|grad|turn)?[,\s]+){2,3}[\s\/]*[\d\.]+%?\)$/i", $color);
        }
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        // print_r($input);die;
        if (isset($_POST['reset'])) {
            // $this->clear_settings();
            return $this->default_options;
        }
        $new_input = array();

        if (isset($input['auto_mode'])) { // checked
            $new_input['auto_mode'] = ($input['auto_mode'] === 'yes');
        } else {
            $new_input['auto_mode'] = false;
        }

        if (isset($input['test_mode'])) { // checked
            $new_input['test_mode'] = ($input['test_mode'] === 'yes');
        } else {
            $new_input['test_mode'] = false;
        }

        if (isset($input['use_cdn'])) {
            $new_input['use_cdn'] = ($input['use_cdn'] === 'yes');
        } else {
            $new_input['use_cdn'] = false;
        }

        if (isset($input['spinner_style'])) {
            $value = sanitize_text_field($input['spinner_style']);
            if (isset(self::SPINNER_STYLES[$value])) {
                $new_input['spinner_style'] = $value;
            }
        }

        if (isset($input['spinner_scale'])) {
            $new_input['spinner_scale'] = floatval($input['spinner_scale']);
        }

        if (isset($input['sk_size'])) {
            $new_input['sk_size'] = intval($input['sk_size']);
        }

        if (isset($input['timeout'])) {
            $new_input['timeout'] = absint($input['timeout']);
        }

        if (isset($input['delay'])) {
            $new_input['delay'] = absint($input['delay']);
        }

        if (isset($input['hook_name'])) {
            $new_input['hook_name'] = sanitize_text_field($input['hook_name']);
        }

        if (isset($input['bg_color'])) {
            if ($this->is_valid_color($input['text_color'])) {
                $new_input['bg_color'] = sanitize_text_field($input['bg_color']);
            }
        }

        if (isset($input['text_color'])) {
            if ($this->is_valid_color($input['text_color'])) {
                $new_input['text_color'] = sanitize_text_field($input['text_color']);
            }
        }

        if (isset($input['custom_message'])) {
            $new_input['custom_message'] = strip_tags($input['custom_message'], $this->default_options['allow_tags']);
        }

        if (isset($input['custom_message_pos'])) {
            $new_input['custom_message_pos'] = sanitize_text_field($input['custom_message_pos']);
        }

        if (isset($input['display_scopes'])) {
            $new_input['display_scopes'] = $input['display_scopes'];
            array_walk(
                $new_input['display_scopes'],
                function(&$value, $key) {
                    if ($key === self::SCOPE_INCLUDES_ID_VALUE) {
                        $re = '/(\d+)(,\s*\d+)*/m';
                        $new_value = [];
                        preg_match_all($re, $value, $matches);
                        if (!empty($matches[0])) {
                            foreach ($matches[0] as $matched) {
                                $new_value = array_merge($new_value, array_map('trim', explode(',', $matched)));
                            }
                        }
                        $value = implode(', ', array_unique($new_value));
                    } else {
                        $value = ($value === 'yes');
                    }
                });
            if ($new_input['display_scopes'][self::SCOPE_INCLUDES_ID]) {
                if (empty($new_input['display_scopes'][self::SCOPE_INCLUDES_ID_VALUE])) {
                    unset($new_input['display_scopes'][self::SCOPE_INCLUDES_ID]);
                }
            }
        }
        // print_r($new_input); die;
        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        // print 'Enter your settings below:';
    }

    /**
     * Get the auto_mode option and print its input control
     */
    public function auto_mode_callback()
    {
        $current = isset($this->options['auto_mode']) ? $this->options['auto_mode'] : $this->default_options['auto_mode'];
        printf(
            '<input type="checkbox" id="auto_mode" name="wppleasewait_settings[auto_mode]" value="yes" %s />',
            checked($current, true, false)
        );
        print '<label for="auto_mode"><p class="description">Auto add plugin scripts after <code>&lt;body&gt;</code> tag.</p></label>';
    }

    /**
     * Get the test_mode option and print its input control
     */
    public function test_mode_callback()
    {
        $current = isset($this->options['test_mode']) ? $this->options['test_mode'] : $this->default_options['test_mode'];
        printf(
            '<input type="checkbox" id="test_mode" name="wppleasewait_settings[test_mode]" value="yes" %s />',
            checked($current, true, false)
        );
        print '<label for="test_mode"><p class="description">Force show loading screen for admin user.</p></label>';
    }

    /**
     * Get the use_cdn option and print its input control
     */
    public function use_cdn_callback()
    {
        $current = isset($this->options['use_cdn']) ? $this->options['use_cdn'] : $this->default_options['use_cdn'];
        printf(
            '<input type="checkbox" id="use_cdn" name="wppleasewait_settings[use_cdn]" value="yes" %s />',
            checked($current, true, false)
        );
        print '<label for="use_cdn"><p class="description">Load plugin assets from CDN instead of your server.</p></label>';
    }

    /**
     * Get the hook_name option and print its input control
     */
    public function hook_name_callback()
    {
        $rcm_hook_name = $this->default_options['hook_name'];
        printf(
            '<input type="text" id="hook_name" name="wppleasewait_settings[hook_name]" value="%s" />',
            isset($this->options['hook_name']) ? esc_attr($this->options['hook_name']) : $rcm_hook_name
        );
        print '<p class="description">Determine where to output loading screen script, default: <code>' . $rcm_hook_name . '</code><br/>You should leave this field as default value if you don\'t know about <a href="https://codex.wordpress.org/Plugin_API/Action_Reference" target="_blank">WP Action Hook</a>.</p>';
    }

    /**
     * Get the bg_color option and print its input control
     */
    public function bg_color_callback()
    {
        printf(
            '<input type="text" id="bg_color" class="wp-color-picker" name="wppleasewait_settings[bg_color]" value="%s" />',
            isset($this->options['bg_color']) ? esc_attr($this->options['bg_color']) : $this->default_options['bg_color']
        );
    }

    /**
     * Get the text_color option and print its input control
     */
    public function text_color_callback()
    {
        printf(
            '<input type="text" id="text_color" class="wp-color-picker" name="wppleasewait_settings[text_color]" value="%s" />',
            isset($this->options['text_color']) ? esc_attr($this->options['text_color']) : $this->default_options['text_color']
        );
    }

    /**
     * Get the custom_message option and print its input control
     */
    public function custom_message_callback()
    {
        printf(
            '<textarea rows="3" id="custom_message" class="full-width" name="wppleasewait_settings[custom_message]" />%s</textarea>',
            isset($this->options['custom_message']) ? esc_attr($this->options['custom_message']) : $this->default_options['custom_message']
        );
        $allow_tags = htmlentities($this->default_options['allow_tags']);
        printf('<p class="description">You could display random message on each loading screen by enter multiple message here, one message per line.<br/>For customize message, you can use filter <code>wp_pleasewait_message</code><br/>Allow HTML tags: <code>%s</code></p>', $allow_tags);
    }

    /**
     * Get the custom_message_pos option and print its input control
     */
    public function custom_message_pos_callback()
    {
        $value = isset($this->options['custom_message_pos']) ? $this->options['custom_message_pos'] : $this->default_options['custom_message_pos'];
        print('<select type="text" id="custom_message_pos" name="wppleasewait_settings[custom_message_pos]">');
        foreach ($this->default_options['custom_message_pos_list'] as $key => $desc) {
            printf(
                '<option value="%s" %s>%s</option>',
                $key,
                selected($key, $value, false),
                $desc
            );
        }
        print('</select>');
    }

    /**
     * Get the spinner_style option and print its input control
     */
    public function spinner_style_callback()
    {
        $value = isset($this->options['spinner_style']) ? $this->options['spinner_style'] : $this->default_options['spinner_style'];
        print('<select type="text" id="spinner_style" name="wppleasewait_settings[spinner_style]">');
        $spinner_styles = [
            self::SK_NONE        => 'None',
            self::SK_PLANE       => 'Plane',
            self::SK_CHASE       => 'Chase',
            self::SK_BOUNCE      => 'Bounce',
            self::SK_WAVE        => 'Wave',
            self::SK_PULSE       => 'Pulse',
            self::SK_FLOW        => 'Flow',
            self::SK_SWING       => 'Swing',
            self::SK_CIRCLE      => 'Circle',
            self::SK_CIRCLE_FADE => 'Circle Fade',
            self::SK_GRID        => 'Grid',
            self::SK_FOLD        => 'Fold',
            self::SK_WANDER      => 'Wander'
        ];
        foreach ($spinner_styles as $sk_id => $sk_name) {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                $sk_id,
                selected($sk_id, $value, false),
                $sk_name
            );
        }
        print('</select>');
        printf('<div id="preview-html">%s</div>', self::SPINNER_STYLES[$value]);
        printf('<p class="description">Loader use SpinKit, see more details <a href="%s" target="_blank">at here</a>.</p>', 'http://tobiasahlin.com/spinkit/');
    }

    /**
     * Get the spinner_scale option and print its input control
     */
    public function spinner_size_callback()
    {
        printf(
            '<input type="number" id="sk_size" min="10" step="1" name="wppleasewait_settings[sk_size]" value="%s" />',
            isset($this->options['sk_size']) ? esc_attr($this->options['sk_size']) : $this->default_options['sk_size']
        );
        printf('<p class="description">Spinner size (px), default: <code>%s</code></p>', $this->default_options['sk_size']);
    }

    /**
     * Get the timeout option and print its input control
     */
    public function timeout_callback()
    {
        printf(
            '<input type="number" id="timeout" min="0" step="1" name="wppleasewait_settings[timeout]" value="%s" /> (seconds)',
            isset($this->options['timeout']) ? esc_attr($this->options['timeout']) : $this->default_options['timeout']
        );
        printf('<p class="description">Maximum timeout to display loading screen, set <code>0</code> to disable, default: <code>%s</code></p>', $this->default_options['timeout']);
    }

    /**
     * Get the timeout option and print its input control
     */
    public function delay_callback()
    {
        printf(
            '<input type="number" id="delay" min="0" step="10" name="wppleasewait_settings[delay]" value="%s" /> (milliseconds)',
            isset($this->options['delay']) ? esc_attr($this->options['delay']) : $this->default_options['delay']
        );
        printf('<p class="description">Delay timeout to hide loading screen after finish, set <code>0</code> to disable, default: <code>%s</code></p>', $this->default_options['delay']);
    }

    /**
     * Get the timeout option and print its input control
     */
    public function display_scopes_callback()
    {
        $current = isset($this->options['display_scopes']) ? $this->options['display_scopes'] : $this->default_options['display_scopes'];
        $is_enable_all = empty($current) || isset($current[self::SCOPE_ALL_PAGE]);
        // print_r($current);die;
        $scopes = [
            self::SCOPE_ALL_PAGE     => 'Enable PleaseWait screen on the whole website',
            self::SCOPE_FRONT_PAGE   => 'Front page',
            self::SCOPE_HOME_PAGE    => 'Home page',
            self::SCOPE_ARCHIVE_PAGE => 'Archive page',
            self::SCOPE_SEARCH_PAGE  => 'Search results page',
            self::SCOPE_SINGLE_PAGE  => 'Single page',
            self::SCOPE_SINGLE_POST  => 'Single post',
            self::SCOPE_INCLUDES_ID  => 'Specific post (or page, custom post type) IDs',
        ];
        foreach ($scopes as $key => $scope) {
            printf('<div id="container-display_scopes_%s" class="%s">', $key, $key !== self::SCOPE_ALL_PAGE && $is_enable_all ? 'td hidden' : 'td');
            printf(
                '<input type="checkbox" id="display_scopes_%1$s" name="wppleasewait_settings[display_scopes][%1$s]" value="yes" %2$s />',
                $key,
                $key !== self::SCOPE_ALL_PAGE
                    ? checked(isset($current[$key]), true, false)
                    : checked($is_enable_all, true, false)
            );
            printf('<label for="display_scopes_%1$s"><p class="description">%2$s</p></label>', $key, $scope);
            if ($key === self::SCOPE_INCLUDES_ID) {
                $sub_key = self::SCOPE_INCLUDES_ID_VALUE;
                printf('<div id="container-display_scopes_%s" class="td">', $sub_key);
                printf(
                    '<input type="text" id="display_scopes_%1$s" name="wppleasewait_settings[display_scopes][%1$s]" value="%2$s" style="min-width: 50%%" %3$s/>',
                    $sub_key,
                    isset($current[$sub_key]) ? $current[$sub_key] : '',
                    readonly(isset($current[$key]), false, false)
                );
                print('<p class="description">You could enable loading screen on some specify posts only by their IDs, separated by a comma, ex. <code>1, 2, 3</code>.<br/>For advanced usage, you can use filter <code>wp_pleasewait_enable</code></p>');
                print('</div>');
            }
            print('</div>');
        }
    }

    /**
     * Get the user settings or load default
     */
    public function get_options()
    {
        $options = $this->options ? $this->options : array();
        if (isset($options['spinner_scale'])) { // Migrate with older version
            $options['sk_size'] = floatval($options['spinner_scale']) * $this->default_options['sk_size'];
            unset($options['spinner_scale']);
        }
        return array_merge($this->default_options, $options);
    }

    /**
     * Get best place hook_name to output the script in HTML output
     *
     * @return string
     */
    public function get_hook_name()
    {
        $hook_name = 'wp_footer';
        $theme = get_template();
        if ('genesis' === $theme) {
            $hook_name  = 'genesis_before';
        } else if ('betheme' === $theme) {
            $hook_name = 'mfn_hook_top';
        } else if ('avada' === $theme) {
            $hook_name = 'avada_before_body_content';
        } else if (class_exists('Roots\\Sage\\Assets')) {
            $hook_name = 'get_header';
        }

        return $hook_name;
    }
}
