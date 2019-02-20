var validator = {
    between: require('../../validation/rules/between'),
    digits: require('../../validation/rules/digits'),
    email: require('../../validation/rules/email'),
    greaterThan: require('../../validation/rules/greater-than'),
    smallerThan: require('../../validation/rules/smaller-than'),
    equivalent: require('../../validation/rules/equivalent'),
    integer: require('../../validation/rules/integer'),
    max: require('../../validation/rules/max'),
    min: require('../../validation/rules/min'),
    number: require('../../validation/rules/number'),
    requiredIf: require('../../validation/rules/required-if'),
    requiredAndShownIf: require('../../validation/rules/required-if-and-shown'),
    required: require('../../validation/rules/required'),
    url: require('../../validation/rules/url'),
    date: require('../../validation/rules/date'),
    daterange: require('../../validation/rules/daterange'),
    timerange: require('../../validation/rules/timerange'),
    len: require('../../validation/rules/len'),
    maxLen: require('../../validation/rules/max-len'),
    letters: require('../../validation/rules/letters'),
    numspace: require('../../validation/rules/numspace'),
    signspace: require('../../validation/rules/signspace'),
    price: require('../../validation/rules/price')
}

var merge = require('merge');

function shouldShow(that, rule) {
    return !that.pristine || ['greaterThan', 'smallerThan'].indexOf(rule) > -1;
}

module.exports = function () {
    var formError;
    var isValid;

    validator = merge.recursive(validator, this.getForm().options.customRules);

    for (var rule in this.rules) {

        if (validator[rule]) {

            isValid = (!this.value && rule != 'required' && rule != 'requiredIf' && rule != 'requiredAndShownIf') || validator[rule](this);

            formError = {
                name: this.name,
                rule: rule,
                show: shouldShow(this, rule)
            };

            if (isValid) {
                this.errors.$remove(rule);

                if (this.inForm()) this.removeFormError(formError);

            } else {

                if (shouldShow(this, rule)) {
                    if (this.errors.indexOf(rule) == -1)
                        this.errors.push(rule);
                }
                if (this.inForm()) {
                    this.addFormError(formError, !this.pristine, rule);
                }
            }

        }

    }

    if (this.errors.length) this.hadErrors = true;
}
