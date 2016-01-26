<?php

function build_custom_metabox() {
  add_meta_box(
    "trstacks_meta",
    "Stacks Manager",
    "trstacks_meta_callback",
    "landing_page"
  );
}
add_action( 'add_meta_boxes', 'build_custom_metabox' );

// Create metabox html and initialize nonce(number used once)
function trstacks_meta_callback ( $post ) {
?>
  <div class="field-wrapper">
    <button id="addBtn">Add Band</button>
    <button id="saveBtn">Save Stacks</button>
    <form action="" method="post">
    <ul id="sortable">
    <?php
      $moduleArr = array_diff(scandir(STACKS_DIR . '\modules'), array('..', '.'));
      $numberOfBands = (int)get_post_meta( $post->ID, 'numBands', true );
      echo 'numBands ' . $numberOfBands;
      $stack = array();
      for($i = 0; $i < $numberOfBands; $i++) {
        $singleBand = get_post_meta($post->ID, 'band_id' . $i, true);
        $stack[] = $singleBand;
      }
      $temp = array();
      $final = array();
      foreach($stack as $s){
          $temp = TRStacks::initModules($s);
          $final = array_merge($final, $temp);
      }
      $iter = 0;
      //getInputs($post->ID, $final);
      foreach($final as $f){
      ?>
        <li><div class="meta-row" id="row">
          <div class="left">
          <button class="delBtn">Delete Band</button>
          <div class="meta-th">
            <label for="band-type" class="band-type">Band Type:</label>
          </div>
          <div class="meta-td">
         <select class="bandType" name="band_id" id="band_id" value="<?='test'?>">
           <?php
           foreach($moduleArr as $m){
             if($m == $stack[$iter]) {?>
             <option name="<?=$m;?>" value="<?=$m;?>" selected="selected"><?=$m?></option>
      <?php  }
             else { ?>
             <option name="<?=$m;?>" value="<?=$m;?>"><?=$m?></option>
             <?php }
         }?>
         </select>
          </div>
          </div>
          <div class="layerOptions right">
            <?php

            ?>
            <?=$f?>
          </div>
        </div></li>
      <?php
      $iter++;
      } ?>

    </ul>
    </form>
  </div>
<?php
}

function getInputs($post_id, $stack = array()) {
  global $pagenow, $typenow;
  if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow == 'landing_page' ) {

  wp_enqueue_script( 'stacks-input-script', plugins_url('tr-stacks/js/analyzeStack.js'), array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable' ), '1.0', true);
  $jsonFile = STACKS_DIR . '/json/' . $post_id . '.json';
  if(file_exists($jsonFile)){
  $f = fopen($jsonFile, 'r');
  $tempArr = array();
  if ($f) {
  while (($line = fgets($f)) !== false) {
      // process the line read.
      $tempArr[] = $line;
  }
  fclose($f);
  } else {
  // error opening the file.
  }
  }

  //die(var_dump($tempArr));

  $dataForJS = array(
    //'security' => wp_create_nonce( 'wp-js' ),
    'jsonData' => $tempArr,
    'stack' => $stack
  );
  wp_localize_script( 'stacks-input-script', 'phpVars', $dataForJS);

  }

}
?>
