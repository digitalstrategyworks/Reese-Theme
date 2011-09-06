$(document).ready(function(){
  // Reset Font Size
  var originalFontSize = $('#leftContent p').css('font-size');
    $(".resetFont").click(function(){
    $('#leftContent p').css('font-size', originalFontSize);
  });
  // Increase Font Size
  $(".increaseFont").click(function(){
    var currentFontSize = $('#leftContent p').css('font-size');
    var currentFontSizeNum = parseFloat(currentFontSize, 10);
    var newFontSize = currentFontSizeNum*1.1;
    $('#leftContent p').css('font-size', newFontSize);
    return false;
  });
  // Decrease Font Size
  $(".decreaseFont").click(function(){
    var currentFontSize = $('#leftContent p').css('font-size');
    var currentFontSizeNum = parseFloat(currentFontSize, 10);
    var newFontSize = currentFontSizeNum*0.9;
    $('#leftContent p').css('font-size', newFontSize);
    return false;
  });
});