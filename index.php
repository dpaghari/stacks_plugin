<?php
/**
 * Plugin Name: Stacks Plugin
 * Description: This plugin adds an easy to use template creator for landing pages
 * Version: 1.0.0
 * Author: Tightrope Interactive(DP)
 */

define( "STACKS_DIR", dirname(__FILE__) );
define( "PLUGINS_URL", plugin_dir_url(__FILE__) );

require_once STACKS_DIR . '/src/build-metabox.php';
require_once STACKS_DIR . '/src/Stacks_PageManager.php';

class TRStacks {
  private static $lastSaved;
  private static $classPaths = array(
      "Stacks_PageManager" => array('path' => '/src/Stacks_PageManager.php')
  );
  function stacks_admin_enqueue_scripts($post) {
      global $pagenow, $typenow;
      $pluginDir = PLUGINS_URL;

      if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow == 'landing_page' ) {
          $numberOfBands = get_post_meta( get_the_ID(), 'numBands', true );
          wp_enqueue_style( 'stacks-admin-css', plugins_url( 'css/metabox.css', __FILE__ ) );
          wp_enqueue_script( 'stacks-metabox-script', plugins_url( 'js/metabox.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable' ), '1.0', true);
          $dataForJS = array(
            'security' => wp_create_nonce( 'wp-modules' ),
            'pluginDir' => $pluginDir,
            'numBands' => $numberOfBands
          );
          wp_localize_script( 'stacks-metabox-script', 'phpVars', $dataForJS);
      }
  }
  public static function makeDocumentation() {
    $doc = add_menu_page(
      'Stacks Documentation',
      'Stacks Docs',
      'manage_options',
      'tr-stacks',
      array( 'TRStacks', 'documentationContents' ),
      plugins_url( 'tr-stacks' ).'/assets/img/stacksonstacks.jpg'
    );
  }

  public static function init () {
    add_action( 'admin_menu', array('TRStacks', 'makeDocumentation') );
    add_action( 'admin_enqueue_scripts', array('TRStacks', 'stacks_admin_enqueue_scripts') );
    add_action( 'wp_ajax_get_module', array( __CLASS__ , 'retrieveModule') );
    add_action( 'wp_ajax_get_all_modules', array( __CLASS__ , 'retrieveAllModules') );
    add_action( 'wp_ajax_trstacks_meta_save', array( __CLASS__ , 'trstacks_meta_save') );
    add_action( 'save_post', array( __CLASS__ , 'saveFromForm') );
  }

  public function retrieveModule () {
    exit(json_encode(array("data" => file_get_contents(PLUGINS_URL . '/modules/' . $_GET['bandType'] ))));
  }

  public function initModules( $module ) {
    $initModules = array();
      if(!empty($module))
      $initModules[] = file_get_contents(PLUGINS_URL . 'modules/' . $module );
    return $initModules;
  }

  public function retrieveAllModules () {
      $dir = STACKS_DIR . '\modules';
      $retrievedModules = array();
      $retrievedModules = array_diff(scandir($dir), array('..', '.'));
      exit( json_encode(array("data" => $retrievedModules)  ) );
  }


  function saveFromForm( $post_id ) {
    $numBandsSaved = (int)get_post_meta( $post_id, 'numBands', true );
    $cInputs = array();
    $lineNum = 0;
    $jsonPath =  get_template_directory() . '/json';
    if(!is_dir($jsonPath)) mkdir($jsonPath);

    $fh = fopen($jsonPath . '/' . $post_id . '.json', 'a');
      // die(var_dump($fh));
      fwrite($fh, '{' . "\n");

        if(!empty($_POST['c_input'])) {
          $cInputs = array_values($_POST['c_input']);
          $numCInputs = count($cInputs);
          $numberofInputs = count($_POST['c_input']);
          foreach($_POST['c_input'] as $index => $input)  {

            if($input != ""){
              $inputStr = '"' . json_encode($lineNum) . '"' . " : " . json_encode($input);

              if($index == $numberofInputs - 1)
              fwrite($fh, $inputStr . "\n");
              else
              fwrite($fh, $inputStr . ",\n");
            }
            $lineNum++;
          }
        }
        fwrite($fh, '}' . "\n");
        fclose($fh);
        update_post_meta($post_id, 'jsonURL', $jsonPath );
    // Save newly added Bands/Layers
    $bArr = array();
    if(!empty($_POST['band_id'])) {
        $bArr = $_POST['band_id'];
        $iter = $numBandsSaved;
        foreach($bArr as $b) {
          $bandNum = 'band_id' . $iter;
          $old = get_post_meta($post_id, $bandNum, true);
          $new = $b;
          if($new && $new != $old)
            update_post_meta( $post_id, $bandNum, $new , $old);
          $iter++;
        }

        $newNum = $iter;
        if($newNum != $numBandsSaved)
        update_post_meta( $post_id, 'numBands', $newNum, $numBandsSaved );
    }
  }


  // Save post meta
  function trstacks_meta_save ( $post_id ) {
      //wp_nonce_field( basename( __FILE__ ), 'stacks_bands_nonce' );

      //$meta = get_post_meta($post_id);
      $numBandsSaved = isset( $_POST['stackCount'] ) ? $_POST['stackCount'] : 0;
      $bandArr = array();
      $bandArr = isset( $_POST['bandVals'] ) ? $_POST['bandVals'] : false;
      // Checks save status
      $is_autosave = wp_is_post_autosave( $post_id );
      $is_revision = wp_is_post_revision( $post_id );
      $is_valid_nonce = ( isset( $_POST[ 'stacks_bands_nonce' ] ) && wp_verify_nonce( $_POST[ 'stacks_bands_nonce' ],  basename( __FILE__ ) ) ) ? 'true' : 'false';

      // Exits script depending on save status
      //if ( $is_autosave || $is_revision || !$is_valid_nonce )
      //  return;

      //for($i = 0; $i < $numBandsSaved; $i++) {
        //$bandNum = 'band_id' . $i;
        //update_post_meta( $post_id, $bandNum, $bandArr[$i] );
      //}
      //exit(json_encode(array("data" => $_POST['stackCount'])));
  }

  public static function documentationContents() { ?>
    <div class="wrapper">
      <h1>Stacks Documentation</h1>
      <ul>
        <li>
          <p>Alriiiiight!</p>
          <img src=<?=plugins_url( 'tr-stacks' ).'/assets/img/alright.gif'?>>
        </li>
      </ul>
    </div>
<?php }

}

// Instantiate the plugin
TRStacks::init();
?>
