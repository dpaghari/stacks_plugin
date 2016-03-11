var stack = phpVars.stack;
var jsonData = phpVars.jsonData;
var json_dir = phpVars.themeDir;
var current_page_id = phpVars.post_id;

var numInputs = [];
jQuery( function ($) {

  function retrieveJSON() {
    var json_dir_url = json_dir + current_page_id + '.json';
    console.log(json_dir_url);
    return $.ajax({
      url: json_dir_url,
      type: 'GET',
      data: {
        //action: 'get_all_modules'
      },
      dataType: 'json',
      cache: false
    });
  }
  $.each( stack, function (i, el) {
    numInputs[i] = getNumInputsFromModule(el);
    changeStacksToInputs(numInputs[i], i, el);
  });

  function getNumInputsFromModule(layer) {
    var inputs = $(layer).find('.c-input');
    return inputs.length;
  }

  function changeStacksToInputs(numInputs, index, el) {
    var layerOptions = $('.layerOptions');
    var left = $('.left');
    var numStacks = $('.layerOptions').length;
    var cInputLabels = $(el).find('.c-input');
    var labels = [];

    $.each(cInputLabels, function (i, e) {
      labels[i] = $(e).prop('tagName');
    });

    var theJSON = retrieveJSON();
    theJSON.then(function(res) {
      for(var i = 0; i < numInputs; i++){
        if(jsonData.length > 0){
          if(labels[i] == "P")
          var inputHTML = '<textarea name="c_input[]">' + res[i] + '</textarea><br>';
          else
          var inputHTML = '<input name="c_input[]" type="text" value="' + res[i] + '"/><br>';
        }
        else {
          if(labels[i] == "P")
          var inputHTML = '<textarea name="c_input[]"></textarea><br>';
          else
          var inputHTML = "<input name='c_input[]' type='text' value=''/><br>";
        }
        $(left[index]).append(labels[i] + ": " + inputHTML);
      }
    });
  }
});
