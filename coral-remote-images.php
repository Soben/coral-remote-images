<?php

/*
  Plugin Name: Coral Remote Images
  Plugin URI: https://poutine.dev/
  Description: Save space and download time!  Leave your uploaded images where they’re at, in /wp-content, during development on your local.
  Version: 2.0
  Author: Poutine
  Author URI: https://poutine.dev
*/

namespace Poutine\Coral;

require_once('library/Module.php');

// Supports: Media, ACF, The_Content

class RemoteImages implements Module
{

  const OPTIONS_FIELD_NAME = 'coral_remote_images_live';
  const OPTIONS_GROUP = 'coral-remote-images';

  protected $active = false;
  protected $liveURL = null;
  protected $liveURLUploadsBase = null;
  protected $currentURL = null;
  protected $currentURLReplaceArray = array();
  protected $currentURLUploadsBase = null;

  public function __construct () {
    // Do any prep work as needed.
    add_action('admin_menu', array($this, 'registerMenuPages'));
    add_action('admin_init', array($this, 'admin_init'));
    add_action('init', array($this, 'init'));
  } // function

  public function admin_init()
  {
    $this->registerSettingsGroup();
  }

  function renderAdminPage()
  {
    require_once(__DIR__ . '/templates/options.php');
  }

  public function registerMenuPages()
  {
    add_options_page(
      'Coral Remote Image Settings',
      'Remote Images',
      'manage_options',
      __FILE__,
      array($this, 'renderAdminPage')
    );
  }

  function registerSettingsGroup()
  {
    register_setting( self::OPTIONS_GROUP, self::OPTIONS_FIELD_NAME );
  }

  public function init()
  {
    $this->_getCurrentURL();
    $this->_getLiveURL();
    $this->_confirmURLSettings();
    if ( !$this->active ) return;

    $processFunction = array ( $this, 'processField' );
    $priority = apply_filters('coral_remoteimage_priority', 10);

    if ( !is_admin() ) {
      if ( defined('CORAL_REMOTEIMAGES_BUFFER') && CORAL_REMOTEIMAGES_BUFFER ) {
        add_action('wp_head', array($this, 'buffer_start'));
        add_action('wp_footer', array($this, 'buffer_end'));
      }

      // WP
      add_filter( 'wp_get_attachment', $processFunction, $priority, 1 );
      add_filter( 'wp_get_attachment_link', $processFunction, $priority, 1 );
      add_filter( 'wp_get_attachment_image_attributes', $processFunction, $priority, 1 );
      add_filter( 'wp_get_attachment_metadata', $processFunction, $priority, 1 );
      add_filter( 'wp_get_attachment_thumb_url', $processFunction, $priority, 1 );
      add_filter( 'wp_get_attachment_url', $processFunction, $priority, 1 );

      // Loop, etc.
      add_filter( 'attachment_link', $processFunction, $priority, 1 );
      add_filter( 'get_attached_file', $processFunction, $priority, 1 );
      add_filter( 'get_image_tag', $processFunction, $priority, 1 );
      add_filter( 'get_the_guid', $processFunction, $priority, 1 );
      add_filter( 'next_image_link', $processFunction, $priority, 1 );
      add_filter( 'post_thumbnail_html', $processFunction, $priority, 1);
      add_filter( 'prev_image_link', $processFunction, $priority, 1 );
      add_filter( 'the_content', $processFunction, $priority, 1 );

      // ACF
      add_filter( 'acf/format_value_for_api', $processFunction, $priority, 1 );
    }
  } // function

  public function buffer_start ()
  {
    ob_start();
  } // function

  public function buffer_end ()
  {
    $contents = ob_get_contents();
    ob_clean();

    echo $this->processField($contents);
  } // function

  public function stringReplace ( $link )
  {
    $link = apply_filters('coral_remoteimage_string_replace', str_replace($this->currentURLUploadsBase, $this->liveURLUploadsBase, $link));

    return $link;
  } // function

  public function processField( $incomingData )
  {
    if ( is_numeric($incomingData) ) return $incomingData;
    if ( is_string($incomingData) ) return $this->stringReplace($incomingData);

    if ( is_array($incomingData) || is_object($incomingData) ) {
      foreach ( $incomingData as &$item ) {
        $item = $this->processField($item);
      }
    }

    return $incomingData;
  } // function

  private function _getLiveURL()
  {
    // "constant" will always override all other potential settings here. We'll try to do everything we can to determine if we're active or not.  
    if ( defined('CORAL_REMOTEIMAGES_PROD_URL') ) {
      $this->liveURL = trailingslashit(CORAL_REMOTEIMAGES_PROD_URL);
      return;
    }

    $optionURL = get_option(self::OPTIONS_FIELD_NAME);
    if ($optionURL && $optionURL != '') {
      $this->liveURL = trailingslashit($optionURL);
      return;
    }

    // try to see if we can figure it out ourselves.
    $urlFromOptionsTable = $this->_getOptionsTableSiteURl();
    if ( $urlFromOptionsTable && $this->currentURL != $urlFromOptionsTable ) {
      $this->liveURL = trailingslashit($urlFromOptionsTable);
    }

    $this->liveURL = trailingslashit(apply_filters('coral_remoteimage_live_url', $this->liveURL));
  } // function

  private function _confirmURLSettings()
  {
    if ( !$this->liveURL ) return;
    if ( $this->liveURL == $this->currentURL ) return;

    // Store both http:// and https:// versions, just in case redirect, etc.
    $this->currentURLReplaceArray = array($this->currentURL);
    $this->currentURLReplaceArray[] = str_replace(array('https:', 'http:'), array('http:', 'https:'), $this->currentURL);

    // Let's set up the Uploads path. Assuming Live is the same as Dev.
    $upload_data = wp_upload_dir();
    $this->currentURLUploadsBase = $upload_data['baseurl'];
    $this->liveURLUploadsBase = str_replace($this->currentURLReplaceArray, $this->liveURL, $this->currentURLUploadsBase);

    $this->active = true;
  } // function

  private function _getOptionsTableSiteURl()
  {
    global $wpdb;

    $query = "SELECT `option_value` FROM {$wpdb->options} WHERE `option_name` = 'siteurl' LIMIT 1";
    $originalSiteURL = $wpdb->get_var($query);

    if ( $originalSiteURL ) {
      return trailingslashit($originalSiteURL);
    }
    
    return false;
  }

  private function _getCurrentURL()
  {
    if ( defined('WP_SITEURL') ) {
      $this->currentURL = WP_SITEURL;
      return;
    }

    $this->currentURL = get_bloginfo('wpurl');
  }

} // class

$coralRemoteImages = new RemoteImages();