/**
 * Created by User on 9.1.2017 г..
 */
module.exports = function (timeout) {

    let time = +timeout || 100;

    setTimeout(() => {
        let $err = $('.has-error').first();
        $('html, body').animate({
            scrollTop: ($err.offset().top - 300)
        }, 600, function () {
            // $el.focus()
        })
    }, time)
}