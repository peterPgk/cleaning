var merge = require('merge');
var Num = require('./num');

module.exports = function () {
    return merge.recursive(Num(), {
        props: {

        },
        data: function () {
            return {
                fieldType: 'number',
            }
        },

        computed: {
            partial () {
                return 'num-placeholder';
            },

            _text () {
                return `${this.value} ${this.label}`
            }
            // minVal () {
            //     return this.value <= this.min;
            // },
            // maxVal () {
            //     return this.value >= this.max;
            // }
        },

        methods: {
            // increment () {
            //     if( !this.maxVal ) {
            //         this.setValue(+this.value+1)
            //     }
            // },
            //
            // decrement () {
            //     if( this.value > 0 && !this.minVal ) {
            //         this.setValue(+this.value-1)
            //     }
            // }
        },

        ready () {

        }
    });

}
