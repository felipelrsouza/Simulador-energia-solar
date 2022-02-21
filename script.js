function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode != 46 && (charCode < 48 || charCode > 57)))
        return false;
    return true;
}

function oneDot(input) {
var value = input.value,
    value = value.split(',').join('');

if (value.length > 2) {
  value = value.substring(0, value.length - 2) + ',' + value.substring(value.length - 2, value.length);
}

input.value = value;
}

$(window).on("resize", resize);
resize(); // call once initially

