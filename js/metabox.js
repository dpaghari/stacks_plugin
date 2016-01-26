jQuery( function( $ ) {
  var stacks_data = {
    bandNum: phpVars.numBands || 0,
    oldNum: phpVars.numBands || 0,
    modules: []
  };

  $('#sortable').sortable();
  function retrieveModules() {
    return $.ajax({
      url: ajaxurl,
      type: 'GET',
      data: {
        action: 'get_all_modules'
      },
      dataType: 'json',
      cache: false
    });
  }
  function create_new_band (modules) {
      // console.log(modules);
      var modArr = modules.data;
      var newBand = '<li><div class="meta-row" id="row' + stacks_data.bandNum + '">' +
        '<div class="left">' +
        '<button class="delBtn">Delete Band</button>' +
        '<div class="meta-th">' +
          '<label for="band-type" class="band-type">Band Type:</label>' +
        '</div>' +
        '<div class="meta-td">' +
            // '<form action="" method="post">' +
            '<select class="bandType" name="band_id[]" id="band_id' + stacks_data.bandNum + '">';
            //console.log(Object.keys(modArr).length);
            for(var i = 2; i <= Object.keys(modArr).length + 1; i++){
              newBand += '<option name="' + modArr[i] + '" value=' + modArr[i] + '>' + modArr[i] + '</option>';
            }

      newBand += '</select>' +
        '</div>' +
        '</div>' +
        '<div class="layerOptions right"></div>' +
      '</div></li>';

      $('#sortable').append( newBand );
      stacks_data.bandNum++;
      // console.log(stacks_data.bandNum);
  }

  function addLayerOptions ( htmlResponse, stackLayer ) {
    $(stackLayer).html( htmlResponse );
  }

  function retrieveMarkup ( bandType, stackLayer ) {
      $.ajax({
        url: ajaxurl,
        method: 'GET',
        data: {
          bandType: bandType,
          action: 'get_module'
        },
        dataType: 'json'
        }).
        done(function( response ) {
          //console.log('hi');
          addLayerOptions(response.data, stackLayer);
        }).
        fail(function( error ) {
          //console.log(error);
        });
  }

  function saveStacksAJAX () {
    var bandArr = [];
    var selector = 'select#band_id';
    for(var i = 0; i < stacks_data.bandNum; ++i){
      bandArr[i] = $(selector + i).val();
    }

      //console.log('numBands: ' + stacks_data.bandNum);
      //console.log('bandVals: ' + bandArr);
      var data = {
        action: "trstacks_meta_save",
        stackCount : stacks_data.bandNum,
        bandVals: bandArr
      };
      $.post({url: ajaxurl, data, dataType: 'json'
      }).
      done(function(response) {
        //console.log(response.data);
        //console.log('Successfully passed data to trstacks_meta_save function');
      }).
      fail(function(err) {
        //console.log(err);
      });
  }

  // function getNumInputsFromModule(layer) {
  //   //console.log(layer);
  //   //console.log($(layer).find('.c-input'));
  //   var inputs = $(layer).find('.c-input');
  //   //console.log(inputs.length);
  //   return inputs.length;
  // }
  //
  // function changeStacksToInputs(numInputs, /*index,*/ el) {
  //   // console.log(numInputs);
  //   $(el).find('.layerOptions.right').css('background-color', 'red');
  //   var layerOptions = $('.layerOptions');
  //   var numStacks = $('.layerOptions').length;
  //   var inputHTML = "Src: <input name='c-inputs' type='text'/><br>";
  //   //var dom = $(el).html();
  //   //console.log(layerOptions[index]);
  //   for(i = 0; i < numInputs; i++){
  //       $(layerOptions/*[index]*/).append(inputHTML);
  //   }
  //
  // }

  // function saveStacks () {
  //   var htmlForm = "";
  // }

  // User Input Handlers
  $( document ).on( 'click', '#addBtn', function ( e ) {
    e.preventDefault();
    //create_new_band();
    var modules = retrieveModules();
    modules.done(function(res) {
      stacks_data.modules = res;
      create_new_band(stacks_data.modules);
    });
    var value = $( '.bandType' ).last().val();
    //var value = $( this ).closest( '.bandType' ).val();
    var layer = $( '.bandType' ).last().closest( '.meta-row' ).find( '.layerOptions' );
    $(layer).css('background', 'red');
    retrieveMarkup(value, layer);
  });
  $( document ).on( 'click', '.delBtn', function ( e ) {
    e.preventDefault();
    $(this).closest( '.meta-row' ).remove();
    stacks_data.bandNum--;
  });
  $( document ).on( 'change', '.bandType', function () {
    var value = $( this ).val();
    //console.log('Value: ' + value);
    var layer = $( this ).closest( '.meta-row' ).find( '.layerOptions' );
    retrieveMarkup(value, layer);

    //console.log(numInputs);
    //changeStacksToInputs(numInputs, layer);
  });

  $( document ).on( 'click', '#saveBtn', function ( e ) {
    e.preventDefault();
    saveStacksAJAX();
  });

  $( document ).on( 'click', 'input#publish', function ( e ) {
     if(stacks_data.bandNum != stacks_data.oldNum){
       var hiddenInput = "<input type='hidden' name='numBands' value='" + stacks_data.bandNum + "'>";
       $('#sortable').append(hiddenInput);
       console.log(stacks_data.bandNum);
     }
  });
});
