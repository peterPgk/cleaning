export const coerce = {
    // Convert a string to booleam. Otherwise, return the value without modification, so if is not boolean, Vue throw a warning.
    boolean: val => (typeof val === 'string' ? (val === 'false' || val === 'null' || val === 'undefined' ? false : val === 'true' ? true : val) : val),
    // Attempt to convert a string value to a Number. Otherwise, return 0.
    number: (val, alt = null) => (typeof val === 'number' ? val : val === undefined || val === null || isNaN(Number(val)) ? alt : Number(val)),
    // Attempt to convert to string any value, except for null or undefined.
    string: val => (val === undefined || val === null ? '' : val + ''),
    // Pattern accept RegExp, function, or string (converted to RegExp). Otherwise return null.
    pattern: val => (val instanceof Function || val instanceof RegExp ? val : typeof val === 'string' ? new RegExp(val) : null)
}

export const cleanJson = function (resourse) {
    if( typeof resourse === 'string' ) {
        try {
            return JSON.parse(resourse)
        }
        catch(e) {
            return resourse
        }
    }

    return resourse
}