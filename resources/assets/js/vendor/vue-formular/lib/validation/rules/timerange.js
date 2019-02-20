module.exports = function (that) {

    if (!that.value)
        return true;

    var pieces = that.value.split('-');
    pieces = pieces.map(function (piece) {
        return parseInt(piece.replace(':', ''));
    });
    return pieces[1] > pieces[0];

}
