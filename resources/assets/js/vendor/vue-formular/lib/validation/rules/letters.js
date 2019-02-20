module.exports = function(that) {
  return /[a-zA-Z ]+$/.test(that.value.trim());
}
