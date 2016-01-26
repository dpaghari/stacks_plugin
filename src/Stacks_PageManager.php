<?php
class Stacks_PageManager {
  protected $stackArr;
  protected $post_id;
  protected $pageStacks;
  public function __construct($post_id) {
    $this->post_id = $post_id;
    $this->getPageStacks();
    $this->displayHTML();
    //$this->replacePlaceholders();
  }

  public function getPostID() {
    return $this->post_id;
  }

  private function getPageStacks() {
    $stack = array();
    $numBands = get_post_meta($this->post_id, 'numBands', true);
    // var_dump($numBands);
    for($i = 0; $i <= $numBands; $i++) {
      $singleBand = get_post_meta($this->post_id, 'band_id' . $i, true);
      // var_dump($singleBand);
      $stack[] = $singleBand;
    }
    // var_dump($stack);
    $this->pageStacks = $stack;
  }

  private function displayHTML() {
    $temp = array();
    $final = array();
    $stack = $this->pageStacks;
    foreach($stack as $s){
        $temp = TRStacks::initModules($s);
        $final = array_merge($final, $temp);
    }
    //var_dump($final);
    foreach($final as $f) {
      echo $f;
    }
  }

  private function replacePlaceholders() {
    // $jsonFile = STACKS_DIR . '/json/' . $this->post_id . '.json';
    // $f = fopen($jsonFile, 'r');
    // // die(var_dump($f));
    // $tempArr = array();
    // if ($f) {
    // while (($line = fgets($f)) !== false) {
    //     // process the line read.
    //     $tempArr[] = $line;
    // }
    // fclose($f);
    // } else {
    // // error opening the file.
    // }
    // //die(var_dump($tempArr));
    // wp_enqueue_script( 'stacks-replace-script', plugins_url('tr-stacks/js/replacePH.js'), array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable' ), '1.0', true);
    // $dataForJS = array(
    //   'jsonArr' => $tempArr,
    //   //'numBands' => $numberOfBands,
    //   //'stack' => $stack
    // );
    // wp_localize_script( 'stacks-replace-script', 'phpVars', $dataForJS);
  }

  public function getJSONData() {
    $jsonFile = STACKS_DIR . '/json/' . $this->post_id . '.json';
    $f = fopen($jsonFile, 'r');
    // die(var_dump($f));
    $tempArr = array();
    if ($f) {
    while (($line = fgets($f)) !== false) {
        // process the line read.
        $line = str_replace("\\", "", $line);
        $tempArr[] = $line;
    }
    fclose($f);
    } else {
    // error opening the file.
    }
    return $tempArr;
  }

}
?>
