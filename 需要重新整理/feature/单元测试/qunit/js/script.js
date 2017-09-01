function convertFromCelsiusToFahrenheit(c){
    var f = c * (9/5) + 32;
    return f;
}

function convertFromFahrenheitToCelsius(f){
    var c = (f - 32) * (5/9);
    return c;
}