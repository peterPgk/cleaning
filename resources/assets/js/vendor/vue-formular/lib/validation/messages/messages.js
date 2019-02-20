module.exports = function () {
    return {
        required: 'The :field field is required',
        number: 'The :field field must be a number',
        integer: 'The :field field must be an integer',
        digits: 'The :field field must have digits only',
        letters: 'The :field field must have letters only',
        numspace: 'The field :field must contain numbers and space only',
        signspace: 'The field :field must contain numbers, letters and space only',
        price: 'Please enter a number',
        email: 'The :field field must be a valid email address',
        date: 'The :field field must be a valid date',
        daterange: 'The :field field must be a valid date range',
        between: {
            string: 'The field :field must contain between {0} and {1} characters',
            number: 'The field :field must be a number between {0} and {1}',
            date: 'The field :field must be a date between {0} and {1}'
        },
        min: {
            string: 'The :field field must contain at least {0} characters',
            number: 'The :field field must be equal to or greater than {0}',
            date: 'The field :field must be a date equal to or greater than {0}'
        },
        len: 'The :field field must contain at least {0} characters',
        max: {
            string: 'The field :field must contain no more than {0} characters',
            number: 'The field :field must be equal to or smaller than {0}',
            date: 'The field :field must be a date equal to or smaller than {0}'
        },
        maxLen: 'The field :field must contain no more than {0} characters',
        remote: 'Remote Error',
        requiredIf: ':field field is required',
        requiredAndShownIf: ':field field is required',
        url: 'Please enter a valid URL',
        greaterThan: 'The field :field must be greater than :relatedField',
        smallerThan: 'The field :field must be smaller than :relatedField',
        equivalent: 'The field :field and :relatedField must be the same',
    }
}
