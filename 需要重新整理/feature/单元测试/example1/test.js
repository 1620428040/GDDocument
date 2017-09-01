(function testAddThreeToNumber (){
    var a = 5,
        valueExpected= 8;
    
    if (addThreeToNumber (a) === valueExpected) {
        console.log("Passed!");
    } else {
        console.log("Failed!");
    }
}());