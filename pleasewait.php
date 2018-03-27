<?php

/*
Plugin Name:  WP PleaseWait
Plugin URI:   https://ngoclb.com/project/wp-please-wait
Description:  Add PleaseWait loading screen to currrent theme
Version:      1.0.0
Author:       Ngoc LB
Author URI:   https://ngoclb.com/
License:      MIT License
*/

if ( ! defined( 'ABSPATH' ) ) {
  die();
}

require 'pleasewait-settings.php';

class WpPleaseWait {

  private static $instance;
  private $options;

  public function __construct()	{
    $this->options = WpPleaseWait_SettingsPage::getInstance()->get_options();
    $this->load_actions();
  }

  /**
   * Singleton instance
   */
  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  // Support geneis framework by default
  function load_actions() {
    $hook_name = 'wp_footer';
    if ( 'genesis' === get_template() ) {
      $hook_name = 'genesis_before';
      $after_hook  = 'genesis_after';
    } else if ($this->options['hook_name']) {
      $hook_name = $this->options['hook_name'];
    }
    // Hook
    add_action( 'wp_enqueue_scripts', array( $this, 'add_styles_scripts' ) );
    add_action( 'wp_head', array( $this, 'add_inline_styles' ) );
    add_action( $hook_name, array( $this, 'add_inline_scripts' ) );
  }

  function add_styles_scripts() {
    wp_enqueue_style( 'spinkit', 'https://cdnjs.cloudflare.com/ajax/libs/spinkit/1.2.5/spinkit.min.css' );
    wp_enqueue_style( 'please-wait-css', 'https://cdnjs.cloudflare.com/ajax/libs/please-wait/0.0.5/please-wait.min.css' );
    wp_enqueue_script( 'please-wait-js', 'https://cdnjs.cloudflare.com/ajax/libs/please-wait/0.0.5/please-wait.min.js', array(), '0.0.5', false );
  }

  function add_inline_styles() {
    $scale_value = $this->options['spinner_scale'];
    $text_color = $this->options['text_color'];
    $css = <<<CSS
    html.pg-loading {
      overflow: hidden;
    }
    body > .inner {
      display: none;
    }
    body.pg-loaded > .inner {
      display: block;
    }
    .pg-loading-screen .pg-loading-html { margin-top:0 }
    .pg-loading-html {
      transform: scale({$scale_value});
      -webkit-transform: scale({$scale_value});
    }
    .pg-loading-html .sk-rect,
    .pg-loading-html .sk-cube,
    .pg-loading-html .sk-circle,
    .pg-loading-html .sk-child { background: {$text_color}}
    .loading-message { color: {$text_color} }
CSS;
    echo sprintf("<style type='text/css'>%s</style>", $css);
  }

  function add_inline_scripts() {
    $js = <<<JS
var rootelem = document.querySelector('html'),
  loading_screen = pleaseWait({
  logo: false,
  template: `{$this->options['loading_template']}`,
  backgroundColor: '{$this->options['bg_color']}',
  loadingHtml: `{$this->options['spinner_styles'][$this->options['spinner_style']]}`,
  onLoadedCallback: function() { setTimeout(function(){rootelem.className = rootelem.className.replace('pg-loading', '').trim();} , 200); }
});
rootelem.className += ' pg-loading';
function hide_loading_screen() {loading_screen.finish();}
document.addEventListener("DOMContentLoaded", hide_loading_screen);
JS;
    if ($this->options['timeout']) {
      $js .= "setTimeout(hide_loading_screen, {$this->options['timeout']}*1000);";
    }
    echo sprintf("<script type='text/javascript'>%s</script>", $js);
  }
}

WpPleaseWait::getInstance();
