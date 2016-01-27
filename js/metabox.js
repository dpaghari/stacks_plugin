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
      var modArr = modules.data;
      var newBand = '<li><div class="meta-row" id="row' + stacks_data.bandNum + '">' +
        '<div class="left">' +
        '<button class="delBtn">Delete Band</button>' +
        '<div class="meta-th">' +
          '<label for="band-type" class="band-type">Band Type:</label>' +
        '</div>' +
        '<div class="meta-td">' +
        '<select class="bandType" name="band_id[]" id="band_id' + stacks_data.bandNum + '">';
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
  }

  function addLayerOptions ( htmlResponse, stackLayer ) {
    $(stackLayer).html( htmlResponse );
  }

  function retrieveMarkup ( bandType, stackLayer ) {
      return $.ajax({
        url: ajaxurl,
        method: 'GET',
        data: {
          bandType: bandType,
          action: 'get_module'
        },
        dataType: 'json'
      });
  }

  function getNumInputsFromModule(layer) {
    var inputs = $(layer).find('.c-input');
    return inputs.length;
  }

  function changeStacksToInputs(numInputs, layer) {
    var layerOptions = $('.layerOptions');
    var left = $('.left');
    var numStacks = $('.layerOptions').length;
    var cInputLabels = $(layer).find('.c-input');
    var labels = [];
    var inputVals = [];

    $.each(cInputLabels, function (i, e) {
      labels[i] = $(e).prop('tagName');
      inputVals[i] = $(e).prop('innerHTML');
    });
    //console.log(jsonData);
    for(i = 0; i < numInputs; i++){
      if(labels[i] == "P")
        var inputHTML = '<textarea name="c_input[]">' + inputVals[i] + '</textarea><br>';
      else
        var inputHTML = "<input name='c_input[]' type='text' value='" + inputVals[i] + "'/><br>";

      $(layer.parent()).find(left).append(labels[i] + ": " + inputHTML);
    }
  }

  // User Input Handlers
  $( document ).on( 'click', '#addBtn', function ( e ) {
    e.preventDefault();
    var modules = retrieveModules();
    modules.then(function(res) {
      stacks_data.modules = res;
      create_new_band(stacks_data.modules);
    });
  });
  $( document ).on( 'click', '.delBtn', function ( e ) {
    e.preventDefault();
    $(this).closest( '.meta-row' ).remove();
    stacks_data.bandNum--;
  });
  $( document ).on( 'change', '.bandType', function () {
    var value = $( this ).val();
    var layer = $( this ).closest( '.meta-row' ).find( '.layerOptions' );
    var markup = retrieveMarkup(value);
    markup.then(function(res) {
      addLayerOptions(res.data, layer);
      var numInputs = getNumInputsFromModule(layer);
      changeStacksToInputs(numInputs, layer);
    });

  });

  $( document ).on( 'click', 'input#publish', function ( e ) {
     if(stacks_data.bandNum != stacks_data.oldNum){
       var hiddenInput = "<input type='hidden' name='numBands' value='" + stacks_data.bandNum + "'>";
       $('#sortable').append(hiddenInput);
     }
  });
});
