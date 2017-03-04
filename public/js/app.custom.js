
function dd() {
    for (var a in arguments) {
        console.log(arguments[a]);
    }
}


function numberValid(value, min, max, minlength, maxLength) {
    if (min !== null && value < min) {
        return false;
    }
    if (max !== null && value > max) {
        return false;
    }
    if (minlength !== null && value.length < minlength) {
        return false;
    }

    return maxLength === null || value.length < maxLength;
}
