<?php
class WpPleaseWait_SettingsPage
{
    const CURRENT_VERSION    = '2.2.3';
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

    const MSG_POS_ABOVE = 'above';
    const MSG_POS_BELOW = 'below';

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
        'custom_message_pos'  => self::MSG_POS_ABOVE,
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
        $this->default_options['hook_name'] = $this->get_hook_name();
        $this->get_options();
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
            <h1>WP PleaseWait <small style="font-size: 75%"><?php echo self::CURRENT_VERSION; ?></small></h1>
            <div id="poststuff">
                <div id="post-body" class="columns-2">
                    <div id="post-body-content">
                        <div class="stuffbox">
                            <form method="post" action="options.php">
                                <p class="submit">
                                    <?php
                                    submit_button(__('Reset to defaults', 'wp-pleasewait'), 'secondary', null, false, ['name' => 'reset']);
                                    submit_button(__('Save changes', 'wp-pleasewait'), 'primary pull-right', 'submit', false);
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
                                    submit_button(__('Reset to defaults', 'wp-pleasewait'), 'secondary', null, false, ['name' => 'reset']);
                                    submit_button(__('Save changes', 'wp-pleasewait'), 'primary pull-right', 'submit', false);
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
                                                <a href="<?php echo self::PLUGIN_URL; ?>" style="text-decoration: none" target="_blank">
                                                    <?php echo __('FAQs & Support', 'wp-pleasewait'); ?>
                                                </a>
                                            </li>
                                            <li class="dashicons-before dashicons-star-filled" style="color: #82878c">
                                                <a href="<?php echo self::PLUGIN_URL; ?>/reviews/?rate=5#new-post" style="text-decoration: none" target="_blank">
                                                    <?php echo __('Rate this plugin', 'wp-pleasewait'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (self::GITHUB_URL !== null) : ?>
                                            <li class="dashicons-before dashicons-flag" style="color: #82878c">
                                                <a href="<?php echo self::GITHUB_URL; ?>/issues/new" style="text-decoration: none" target="_blank">
                                                    <?php echo __('Report an issue', 'wp-pleasewait'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li class="dashicons-before dashicons-admin-links" style="color: #82878c">
                                            <a href="<?php echo self::AUTHOR_URL; ?>" style="text-decoration: none" target="_blank">
                                                <?php echo __('Visit author website', 'wp-pleasewait'); ?>
                                            </a>
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

            .sk-flow {
                align-items: center
            }

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
                        if (!window.confirm("<?php echo esc_attr__('Are you sure you want to reset all settings?', 'wp-pleasewait'); ?>")) {
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
            __('Auto Mode', 'wp-pleasewait'),
            array($this, 'auto_mode_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'hook_name',
            __('Hook Name', 'wp-pleasewait'),
            array($this, 'hook_name_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id',
            array('class' => 'hook_name ' . ($this->options['auto_mode'] ? 'hidden' : ''))
        );

        add_settings_field(
            'test_mode',
            __('Testing Mode', 'wp-pleasewait'),
            array($this, 'test_mode_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'use_cdn',
            __('Use CDN', 'wp-pleasewait'),
            array($this, 'use_cdn_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'bg_color',
            __('Background Color', 'wp-pleasewait'),
            array($this, 'bg_color_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );
        add_settings_field(
            'text_color',
            __('Text Color', 'wp-pleasewait'),
            array($this, 'text_color_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'custom_message',
            __('Message Text', 'wp-pleasewait'),
            array($this, 'custom_message_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'custom_message_pos',
            __('Message Position', 'wp-pleasewait'),
            array($this, 'custom_message_pos_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'spinner_style',
            __('Spinner Style', 'wp-pleasewait'),
            array($this, 'spinner_style_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'sk_size',
            __('Spinner Size', 'wp-pleasewait'),
            array($this, 'spinner_size_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'timeout',
            __('Max Display Time', 'wp-pleasewait'),
            array($this, 'timeout_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'delay',
            __('Disappearance Delay Time', 'wp-pleasewait'),
            array($this, 'delay_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'display_scopes',
            __('Display Scopes', 'wp-pleasewait'),
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
            if (array_key_exists($value, self::SPINNER_STYLES)) {
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
                function (&$value, $key) {
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
                }
            );
            if (array_key_exists(self::SCOPE_INCLUDES_ID, $new_input['display_scopes'])) {
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
        printf(
            '<input type="checkbox" id="auto_mode" name="wppleasewait_settings[auto_mode]" value="yes" %s />',
            checked($this->options['auto_mode'], true, false)
        );
        printf(
            '<label for="auto_mode"><p class="description">%s</p></label>',
            sprintf(__('Auto add plugin scripts after %s tag', 'wp-pleasewait'), '<code>&lt;body&gt;</code>')
        );
    }

    /**
     * Get the hook_name option and print its input control
     */
    public function hook_name_callback()
    {
        $rcm_hook_name = $this->default_options['hook_name'];
        printf(
            '<input type="text" id="hook_name" name="wppleasewait_settings[hook_name]" value="%s" />',
            esc_attr($this->options['hook_name'])
        );
        printf(
            '<p class="description">%s<br/>%s</p>',
            sprintf(__('Determine where to output loading screen script, default: %s', 'wp-pleasewait'), "<code>{$rcm_hook_name}</code>"),
            sprintf(__('You should leave this field as default value if you don\'t know about %s', 'wp-pleasewait'), '<a href="https://codex.wordpress.org/Plugin_API/Action_Reference" target="_blank">WP Action Hook</a>')
        );
    }

    /**
     * Get the test_mode option and print its input control
     */
    public function test_mode_callback()
    {
        printf(
            '<input type="checkbox" id="test_mode" name="wppleasewait_settings[test_mode]" value="yes" %s />',
            checked($this->options['test_mode'], true, false)
        );
        printf(
            '<label for="test_mode"><p class="description">%s</p></label>',
            __('Force show loading screen for admin user', 'wp-pleasewait')
        );
    }

    /**
     * Get the use_cdn option and print its input control
     */
    public function use_cdn_callback()
    {
        printf(
            '<input type="checkbox" id="use_cdn" name="wppleasewait_settings[use_cdn]" value="yes" %s />',
            checked($this->options['use_cdn'], true, false)
        );
        printf(
            '<label for="use_cdn"><p class="description">%s</p></label>',
            __('Load plugin assets from CDN instead of your server', 'wp-pleasewait')
        );
    }

    /**
     * Get the bg_color option and print its input control
     */
    public function bg_color_callback()
    {
        printf(
            '<input type="text" id="bg_color" class="wp-color-picker" name="wppleasewait_settings[bg_color]" value="%s" />',
            esc_attr($this->options['bg_color'])
        );
    }

    /**
     * Get the text_color option and print its input control
     */
    public function text_color_callback()
    {
        printf(
            '<input type="text" id="text_color" class="wp-color-picker" name="wppleasewait_settings[text_color]" value="%s" />',
            esc_attr($this->options['text_color'])
        );
    }

    /**
     * Get the custom_message option and print its input control
     */
    public function custom_message_callback()
    {
        printf(
            '<textarea rows="3" id="custom_message" class="full-width" name="wppleasewait_settings[custom_message]" />%s</textarea>',
            esc_attr($this->options['custom_message'])
        );
        $allow_tags = htmlentities($this->default_options['allow_tags']);
        printf(
            '<p class="description">%s<br/>%s<br/>%s</p>',
            __('You could display random message on each loading screen by enter multiple message here, one message per line', 'wp-pleasewait'),
            sprintf(
                __('Allowed HTML tags: %s', 'wp-pleasewait'),
                "<code>{$allow_tags}</code>"
            ),
            sprintf(
                __('For customize message, you can use filter %s', 'wp-pleasewait'),
                '<code>wp_pleasewait_message</code>'
            )
        );
    }

    /**
     * Get the custom_message_pos option and print its input control
     */
    public function custom_message_pos_callback()
    {
        $value = $this->options['custom_message_pos'];
        print('<select type="text" id="custom_message_pos" name="wppleasewait_settings[custom_message_pos]">');
        $positions = [
            self::MSG_POS_ABOVE => __('Above the spinner', 'wp-pleasewait'),
            self::MSG_POS_BELOW => __('Below the spinner', 'wp-pleasewait'),
        ];
        foreach ($positions as $key => $desc) {
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
        $value = $this->options['spinner_style'];
        print('<select type="text" id="spinner_style" name="wppleasewait_settings[spinner_style]">');
        $spinner_styles = [
            self::SK_NONE        => __('None', 'wp-pleasewait'),
            self::SK_PLANE       => __('Plane', 'wp-pleasewait'),
            self::SK_CHASE       => __('Chase', 'wp-pleasewait'),
            self::SK_BOUNCE      => __('Bounce', 'wp-pleasewait'),
            self::SK_WAVE        => __('Wave', 'wp-pleasewait'),
            self::SK_PULSE       => __('Pulse', 'wp-pleasewait'),
            self::SK_FLOW        => __('Flow', 'wp-pleasewait'),
            self::SK_SWING       => __('Swing', 'wp-pleasewait'),
            self::SK_CIRCLE      => __('Circle', 'wp-pleasewait'),
            self::SK_CIRCLE_FADE => __('Circle Fade', 'wp-pleasewait'),
            self::SK_GRID        => __('Grid', 'wp-pleasewait'),
            self::SK_FOLD        => __('Fold', 'wp-pleasewait'),
            self::SK_WANDER      => __('Wander', 'wp-pleasewait')
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
        printf(
            '<p class="description">%s</p>',
            sprintf(
                __('Style of the spinner element, default: %s', 'wp-pleasewait'),
                "<code>{$spinner_styles[$this->default_options['spinner_style']]}</code>"
            )
        );
    }

    /**
     * Get the spinner_scale option and print its input control
     */
    public function spinner_size_callback()
    {
        printf(
            '<input type="number" id="sk_size" min="10" step="1" name="wppleasewait_settings[sk_size]" value="%s" />',
            esc_attr($this->options['sk_size'])
        );
        printf(
            '<p class="description">%s</p>',
            sprintf(
                __('Size of the spinner element (in pixel), default: %s', 'wp-pleasewait'),
                "<code>{$this->default_options['sk_size']}</code>"
            )
        );
    }

    /**
     * Get the timeout option and print its input control
     */
    public function timeout_callback()
    {
        printf(
            '<input type="number" id="timeout" min="0" step="1" name="wppleasewait_settings[timeout]" value="%s" /> (%s)',
            esc_attr($this->options['timeout']),
            __('seconds', 'wp-pleasewait')
        );
        printf(
            '<p class="description">%s</p>',
            sprintf(
                __('Maximum timeout to display loading screen, set %s to disable, default: %s', 'wp-pleasewait'),
                '<code>0</code>',
                "<code>{$this->default_options['timeout']}</code>"
            )
        );
    }

    /**
     * Get the timeout option and print its input control
     */
    public function delay_callback()
    {
        printf(
            '<input type="number" id="delay" min="0" step="10" name="wppleasewait_settings[delay]" value="%s" /> (%s)',
            esc_attr($this->options['delay']),
            __('milliseconds', 'wp-pleasewait')
        );
        printf(
            '<p class="description">%s</p>',
            sprintf(
                __('Delay timeout to hide loading screen after page loaded, set %s to disable, default: %s', 'wp-pleasewait'),
                '<code>0</code>',
                "<code>{$this->default_options['delay']}</code>"
            )
        );
    }

    /**
     * Get the timeout option and print its input control
     */
    public function display_scopes_callback()
    {
        $current = $this->options['display_scopes'];
        $is_enable_all = empty($current) || isset($current[self::SCOPE_ALL_PAGE]);
        // print_r($current);die;
        $scopes = [
            self::SCOPE_ALL_PAGE     => __('Enable PleaseWait screen on the whole website', 'wp-pleasewait'),
            self::SCOPE_FRONT_PAGE   => __('Front page', 'wp-pleasewait'),
            self::SCOPE_HOME_PAGE    => __('Home page', 'wp-pleasewait'),
            self::SCOPE_ARCHIVE_PAGE => __('Archive page', 'wp-pleasewait'),
            self::SCOPE_SEARCH_PAGE  => __('Search results page', 'wp-pleasewait'),
            self::SCOPE_SINGLE_PAGE  => __('Single page', 'wp-pleasewait'),
            self::SCOPE_SINGLE_POST  => __('Single post', 'wp-pleasewait'),
            self::SCOPE_INCLUDES_ID  => __('Specific post (or page, custom post type) IDs', 'wp-pleasewait'),
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
                printf(
                    '<p class="description">%s<br/>%s</p>',
                    sprintf(__('You could enable loading screen on some specify posts only by their IDs, separated by a comma. e.g. %s', 'wp-pleasewait'), '<code>1, 2, 3</code>'),
                    sprintf(__('For advanced usage, you can use filter %s', 'wp-pleasewait'), '<code>wp_pleasewait_enable</code>')
                );
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
        if (!$this->options) {
            $options = get_option('wppleasewait_settings', array());
            if (isset($options['spinner_scale'])) { // Migrate with older version
                $options['sk_size'] = floatval($options['spinner_scale']) * $this->default_options['sk_size'];
                unset($options['spinner_scale']);
            }
            $this->options = array_merge($this->default_options, $options);
        }
        return $this->options;
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
