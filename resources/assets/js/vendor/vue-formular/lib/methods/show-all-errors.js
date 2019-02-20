var navigateToError = require('../helpers/navigate-to-error');

module.exports = function() {

    // Ако се опитаме да вземем първата грешка по този начин,
    //няма гаранция, че тя ще е първата във формата
    // let theFirst = false;

  this.errors.forEach(function(error) {
    var field = this.getField(error.name);
    if (field.errors.indexOf(error.rule)==-1) {
        // if (!theFirst) {
        //     theFirst = field;
        // }

      field.errors.push(error.rule);
      field.hadErrors = true;
    }
    error.show = true;
  }.bind(this));

  // if(theFirst) {
  //     let $el = $(theFirst.$el);
  //     $('html, body').animate({
  //         scrollTop: ($el.offset().top - 300)
  //     }, 600, function () {
  //         $el.focus()
  //     })
  // }

    navigateToError();

}
