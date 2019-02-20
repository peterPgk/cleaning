var merge = require('merge');
var Field = require('./field');

module.exports = function () {
    return merge.recursive(Field(), {
        props: {
            min: {
                type: [Number, String],
                default: 0
            },
            max: {
                type: [Number, String],
                default: 1
            },
            name: {
                type: String,
            }
        },
        data: function () {
            return {
                fieldType: 'number'
            }
        },

        computed: {
            partial () {
                return 'num';
            },
            minVal () {
                return this.value <= this.min;
            },
            maxVal () {
                return this.value >= this.max;
            }
        },

        methods: {
            increment () {
                if( !this.maxVal ) {
                    this.setValue(+this.value+1)
                }
            },

            decrement () {
                if( this.value > 0 && !this.minVal ) {
                    this.setValue(+this.value-1)
                }
            }
        },

        ready () {

        }
    });

}
