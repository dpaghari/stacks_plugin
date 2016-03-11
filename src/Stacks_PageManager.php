<?php
class Stacks_PageManager {
  protected $stackArr;
  protected $post_id;
  protected $pageStacks;
  public function __construct($post_id) {
    $this->post_id = $post_id;
    $this->getPageStacks();
    $this->displayHTML();
  }

  public function getPostID() {
    return $this->post_id;
  }

  private function getPageStacks() {
    $stack = array();
    $numBands = get_post_meta($this->post_id, 'numBands', true);
    for($i = 0; $i <= $numBands; $i++) {
      $singleBand = get_post_meta($this->post_id, 'band_id' . $i, true);
      $stack[] = $singleBand;
    }
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
    foreach($final as $f) {
      echo $f;
    }
  }
  public function getJSONData() {
    $jsonFilePath = get_template_directory_uri() . '/json/' . $this->post_id . '.json';
    return $jsonFilePath;

    // $f = fopen($jsonFile, 'r');
    // // die(var_dump($f));
    // $tempArr = array();
    // if ($f) {
    // while (($line = fgets($f)) !== false) {
    //     // process the line read.
    //     $line = str_replace("\\", "", $line);
    //     $tempArr[] = $line;
    // }
    // fclose($f);
    // } else {
    // // error opening the file.
    // }
    // return $tempArr;
  }

}
?>
