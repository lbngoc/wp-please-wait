<?php
class WpPleaseWait_SettingsPage
{
    const CURRENT_VERSION = '1.0.3';
    const GITHUB_URL = 'https://github.com/lbngoc/wp-please-wait'; // null|string
    const PLUGIN_URL = 'https://wordpress.org/support/plugin/wp-pleasewait'; // null|string
    const DEMO_URL   = 'http://pathgather.github.io/please-wait';

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private static $instance;

    private $default_options = array(
        'use_cdn'             => false,
        'bg_color'            => '#f46d3b',
        'text_color'          => '#eeeeee',
        'loading_template'    => '<div class="pg-loading-inner">
      <div class="pg-loading-center-outer">
        <div class="pg-loading-center-middle">
            %s
      </div></div></div>',
        'spinner_styles'      => array(
            '1-rotating-plane'  => '<div class="sk-rotating-plane"></div>',
            '2-double-bounce'   => '<div class="sk-double-bounce">
          <div class="sk-child sk-double-bounce1"></div>
          <div class="sk-child sk-double-bounce2"></div>
        </div>',
            '3-wave'            => '<div class="sk-wave">
          <div class="sk-rect sk-rect1"></div>
          <div class="sk-rect sk-rect2"></div>
          <div class="sk-rect sk-rect3"></div>
          <div class="sk-rect sk-rect4"></div>
          <div class="sk-rect sk-rect5"></div>
        </div>',
            '4-wandering-cubes' => '<div class="sk-wandering-cubes">
          <div class="sk-cube sk-cube1"></div>
          <div class="sk-cube sk-cube2"></div>
        </div>',
            '5-pulse'           => ' <div class="sk-spinner sk-spinner-pulse"></div>',
            '6-chasing-dots'    => '<div class="sk-chasing-dots">
          <div class="sk-child sk-dot1"></div>
          <div class="sk-child sk-dot2"></div>
        </div>',
            '7-three-bounce'    => '<div class="sk-three-bounce">
          <div class="sk-child sk-bounce1"></div>
          <div class="sk-child sk-bounce2"></div>
          <div class="sk-child sk-bounce3"></div>
        </div>',
            '8-circle'          => '<div class="sk-circle">
          <div class="sk-circle1 sk-child"></div>
          <div class="sk-circle2 sk-child"></div>
          <div class="sk-circle3 sk-child"></div>
          <div class="sk-circle4 sk-child"></div>
          <div class="sk-circle5 sk-child"></div>
          <div class="sk-circle6 sk-child"></div>
          <div class="sk-circle7 sk-child"></div>
          <div class="sk-circle8 sk-child"></div>
          <div class="sk-circle9 sk-child"></div>
          <div class="sk-circle10 sk-child"></div>
          <div class="sk-circle11 sk-child"></div>
          <div class="sk-circle12 sk-child"></div>
        </div>',
            '9-cube-grid'       => '<div class="sk-cube-grid">
          <div class="sk-cube sk-cube1"></div>
          <div class="sk-cube sk-cube2"></div>
          <div class="sk-cube sk-cube3"></div>
          <div class="sk-cube sk-cube4"></div>
          <div class="sk-cube sk-cube5"></div>
          <div class="sk-cube sk-cube6"></div>
          <div class="sk-cube sk-cube7"></div>
          <div class="sk-cube sk-cube8"></div>
          <div class="sk-cube sk-cube9"></div>
        </div>',
            '10-fading-circle'  => '<div class="sk-fading-circle">
          <div class="sk-circle1 sk-circle"></div>
          <div class="sk-circle2 sk-circle"></div>
          <div class="sk-circle3 sk-circle"></div>
          <div class="sk-circle4 sk-circle"></div>
          <div class="sk-circle5 sk-circle"></div>
          <div class="sk-circle6 sk-circle"></div>
          <div class="sk-circle7 sk-circle"></div>
          <div class="sk-circle8 sk-circle"></div>
          <div class="sk-circle9 sk-circle"></div>
          <div class="sk-circle10 sk-circle"></div>
          <div class="sk-circle11 sk-circle"></div>
          <div class="sk-circle12 sk-circle"></div>
        </div>',
            '11-folding-cube'   => '<div class="sk-folding-cube">
          <div class="sk-cube1 sk-cube"></div>
          <div class="sk-cube2 sk-cube"></div>
          <div class="sk-cube4 sk-cube"></div>
          <div class="sk-cube3 sk-cube"></div>
        </div>',
        ),
        'custom_message_poss' => array(
            'above' => 'Above the spinner',
            'below' => 'Below the spinner',
        ),
        'spinner_style'       => '3-wave',
        'spinner_scale'       => 1,
        'timeout'             => 10,
        'allow_tags'          => '<p><a><strong><em><span><img>',
    );

    /**
     * Start up
     */
    public function __construct()
    {
        $this->options = get_option('wppleasewait_settings');
        $this->default_options['hook_name'] = $this->get_hook_name();
        if (is_admin()) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
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

    public function get_assets_url($path)
    {
        return plugins_url($path, __FILE__);
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
                    <?php
                        // This prints out all hidden setting fields
                        settings_fields('wppleasewait');
                        do_settings_sections('wppleasewait-setting-admin');
                    ?>
                      <hr/>
                      <p class="submit">
                        <?php
                            submit_button("Reset to defaults", 'secondary', 'reset', false);
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
                              <li class="dashicons-before dashicons-admin-links" style="color: #82878c">
                                  <a href="<?php echo self::DEMO_URL; ?>" style="text-decoration: none" target="_blank">Open PleaseWait Demo</a>
                              </li>
                              <?php if (self::GITHUB_URL !== null): ?>
                              <li class="dashicons-before dashicons-flag" style="color: #82878c">
                                  <a href="<?php echo self::GITHUB_URL; ?>/issues/new" style="text-decoration: none" target="_blank">Report Bug and Issues</a>
                              </li>
                              <?php endif;?>
                              <?php if (self::PLUGIN_URL !== null): ?>
                              <li class="dashicons-before dashicons-wordpress" style="color: #82878c">
                                  <a href="<?php echo self::PLUGIN_URL; ?>" style="text-decoration: none" target="_blank">Plugin Page in WP</a>
                              </li>
                              <li class="dashicons-before dashicons-star-filled" style="color: #82878c">
                                  <a href="<?php echo self::PLUGIN_URL; ?>/reviews/?rate=5#new-post" style="text-decoration: none" target="_blank">
                                  Rate to this plugin</a>
                              </li>
                              <?php endif;?>
                          </ul>
                          <hr>
                          <p style="text-align: center">Plugin was created by <a href="https://ngoclb.com/project/wp-please-wait" style="text-decoration: none" target="_blank">Ngoc Luong</a></p>
                          <p style="text-align: center"><a href='https://ko-fi.com/L3L4B8JX' target='_blank'><img height='36' style='border:0px;height:36px;' src='<?php echo $this->get_assets_url('assets/kofi2.png'); ?>' border='0' alt='Buy Me a Coffee at ko-fi.com' /></a></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <style type="text/css">.stuffbox { padding: 0 20px }.full-width{width:100%}.pull-right{float: right;}</style>
        <script type="text/javascript">(function($){
          $(document).ready(function() {
            $('.wp-color-picker').wpColorPicker()
          });
        })(jQuery);</script>
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
            'use_cdn',
            'Use CDN',
            array($this, 'use_cdn_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'hook_name',
            'Hook Name',
            array($this, 'hook_name_callback'),
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
            'spinner_scale',
            'Spinner Scale',
            array($this, 'spinner_scale_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );
        add_settings_field(
            'timeout',
            'Max display time',
            array($this, 'timeout_callback'),
            'wppleasewait-setting-admin',
            'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        if (isset($_POST['reset'])) {
            return array_merge(array(
                'use_cdn'            => '',
                'hook_name'          => '',
                'bg_color'           => '',
                'text_color'         => '',
                'custom_message'     => '',
                'custom_message_pos' => '',
                'spinner_style'      => '',
                'spinner_scale'      => '',
                'timeout'            => '',
            ), $this->default_options);
        }
        $new_input = array();

        if (isset($input['use_cdn'])) {
            $new_input['use_cdn'] = $input['use_cdn'] === 'yes';
        }

        if (isset($input['spinner_style'])) {
            $new_input['spinner_style'] = sanitize_text_field($input['spinner_style']);
        }

        if (isset($input['spinner_scale'])) {
            $new_input['spinner_scale'] = absint($input['spinner_scale']);
        }

        if (isset($input['timeout'])) {
            $new_input['timeout'] = absint($input['timeout']);
        }

        if (isset($input['hook_name'])) {
            $new_input['hook_name'] = sanitize_text_field($input['hook_name']);
        }

        if (isset($input['bg_color'])) {
            if (preg_match('/^#[a-f0-9]{6}$/i', $input['bg_color'])) {
                $new_input['bg_color'] = sanitize_text_field($input['bg_color']);
            }
        }

        if (isset($input['text_color'])) {
            if (preg_match('/^#[a-f0-9]{6}$/i', $input['text_color'])) {
                $new_input['text_color'] = sanitize_text_field($input['text_color']);
            }
        }

        if (isset($input['custom_message'])) {
            $new_input['custom_message'] = strip_tags($input['custom_message'], $this->default_options['allow_tags']);
        }

        if (isset($input['custom_message_pos'])) {
            $new_input['custom_message_pos'] = sanitize_text_field($input['custom_message_pos']);
        }

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
     * Get the use_cdn option and print its input control
     */
    public function use_cdn_callback()
    {
        printf(
            '<input type="checkbox" id="use_cdn" name="wppleasewait_settings[use_cdn]" value="yes" %s />',
            checked($this->options['use_cdn'], true, false)
        );
        print '<span class="description">Check if you want to load assets from CDN</span>';
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
        print '<p class="description">Determine where to output loading screen script.<br/>Recommend for this theme: <code>' . $rcm_hook_name . '</code></p>';
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
        printf('<p class="description">You could display random message on each loading screen by enter multiple message here, one message per line.<br/>Allow HTML tags: <code>%s</code></p>', $allow_tags);
    }

    /**
     * Get the custom_message_pos option and print its input control
     */
    public function custom_message_pos_callback()
    {
        $value = isset($this->options['custom_message_pos']) ? $this->options['custom_message_pos'] : $this->default_options['custom_message_pos'];
        print('<select type="text" id="custom_message_pos" name="wppleasewait_settings[custom_message_pos]">');
        foreach ($this->default_options['custom_message_poss'] as $key => $desc) {
            printf('<option value="%s" %s>%s</option>',
                $key,
                selected($key, $value, false),
                $desc
            );
        }
        print('</select>');
        // printf('<p class="description">Loader use SpinKit, see <a href="%s" target="_blank">demo here</a>.</p>', 'http://tobiasahlin.com/spinkit/');
    }

    /**
     * Get the spinner_style option and print its input control
     */
    public function spinner_style_callback()
    {
        $value = isset($this->options['spinner_style']) ? $this->options['spinner_style'] : $this->default_options['spinner_style'];
        print('<select type="text" id="spinner_style" name="wppleasewait_settings[spinner_style]">');
        foreach (array_keys($this->default_options['spinner_styles']) as $spinner_style) {
            printf('<option value="%s" %s>%s</option>',
                $spinner_style,
                selected($spinner_style, $value, false),
                $spinner_style
            );
        }
        print('</select>');
        printf('<p class="description">Loader use SpinKit, see <a href="%s" target="_blank">demo here</a>.</p>', 'http://tobiasahlin.com/spinkit/');
    }

    /**
     * Get the spinner_scale option and print its input control
     */
    public function spinner_scale_callback()
    {
        printf(
            '<input type="text" id="spinner_scale" name="wppleasewait_settings[spinner_scale]" value="%s" />',
            isset($this->options['spinner_scale']) ? esc_attr($this->options['spinner_scale']) : $this->default_options['spinner_scale']
        );
        printf('<p class="description">Zoom spinner or not, default: <code>%s</code></p>', $this->default_options['spinner_scale']);
    }

    /**
     * Get the timeout option and print its input control
     */
    public function timeout_callback()
    {
        printf(
            '<input type="text" id="timeout" name="wppleasewait_settings[timeout]" value="%s" /> (seconds)',
            isset($this->options['timeout']) ? esc_attr($this->options['timeout']) : $this->default_options['timeout']
        );
        printf('<p class="description">Maximum timeout to display loading screen, set <code>0</code> to disable, default: <code>%s</code></p>', $this->default_options['timeout']);
    }

    /**
     * Get the user settings or load default
     */
    public function get_options()
    {
        $options = $this->options ? $this->options : array();
        $message = isset($options['custom_message']) ? trim($options['custom_message']) : '';
        if (!empty($message)) {
            $message = explode("\n", $message);
            shuffle($message);
            $message = reset($message);
        }
        $tpl_spinner = '<div class="pg-loading-html"></div>';
        $tpl_loading = '<div class="loading-message">' . $message . '</div>';
        $tpl_loading = isset($options['custom_message_pos']) && $options['custom_message_pos'] === 'below'
        ? $tpl_spinner . $tpl_loading
        : $tpl_loading . $tpl_spinner;
        $options['loading_template'] = sprintf($this->default_options['loading_template'], $tpl_loading);
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
        if ('genesis' === get_template()) {
            $hook_name  = 'genesis_before';
            // $after_hook = 'genesis_after';
        } else if (class_exists('Roots\\Sage\\Assets')) {
            $hook_name = 'get_header';
        } else if (defined('THEME_NAME') && THEME_NAME === 'betheme') {
            $hook_name = 'mfn_hook_top';
        }
        return $hook_name;
    }

}
