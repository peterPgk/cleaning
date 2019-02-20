var merge = require('merge');
var Input = require('./input');

module.exports = function () {
    return merge.recursive(Input(), {
        props: {
            min: {
                type: [Number, String],
                default: ''
            },
            max: {
                type: [Number, String],
                default: ''
            },
            step: {
                type: [Number, String],
                default: 'any'
            }
        },
        data: function () {
            return {
                fieldType: 'number',
            }
        },
        computed: {
            partial () {
                return 'number';
            },
        },
        ready: function () {
            this.$set('rules.number', true);
        }
    });

}
