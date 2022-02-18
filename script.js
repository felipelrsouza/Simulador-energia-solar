$(document).ready(function() {

    document.querySelector(".h-page-title__outer.style-432-outer.style-local-15-h28-outer").innerHTML = null
    document.title = 'Simulação de sistema solar - BELUGA';

});

function resize() {
    var top = document.querySelector(".h-section.h-hero.d-flex.align-items-lg-center.align-items-md-center.align-items-center.style-415.style-local-15-h22.position-relative");
    if ($(window).width() < 751) {
        top.style.height = '90px';

    } else {
        top.style.height = '125px';
    }
}

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