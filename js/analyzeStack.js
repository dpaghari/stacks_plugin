var stack = phpVars.stack;
var jsonData = phpVars.jsonData;
console.log(jsonData);
var numInputs = [];
jQuery( function ($) {
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
    //console.log(jsonData);
    for(i = 0; i < numInputs; i++){
      if(jsonData.length > 0){
        var poppedEl = jsonData.shift();
        poppedEl = poppedEl.replace(/\\/g, "");
        console.log(poppedEl);
        if(labels[i] == "P")
        var inputHTML = '<textarea name="c_input[]">' + poppedEl + '</textarea><br>';
        else
        var inputHTML = '<input name="c_input[]" type="text" value="' + poppedEl + '"/><br>';
      }
      else {
        if(labels[i] == "P")
        var inputHTML = '<textarea name="c_input[]"></textarea><br>';
        else
        var inputHTML = "<input name='c_input[]' type='text' value=''/><br>";
      }
      $(left[index]).append(labels[i] + ": " + inputHTML);
    }
  }
});
