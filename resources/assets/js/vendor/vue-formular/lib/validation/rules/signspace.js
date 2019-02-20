module.exports = function(that) {
  // return /^[0-9]+( [0-9]+)*$/.test(that.value.trim());

    var re = /^[A-Za-z0-9 ]*$/;
    return re.test(that.value);
}
