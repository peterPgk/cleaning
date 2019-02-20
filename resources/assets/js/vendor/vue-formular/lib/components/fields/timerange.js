var merge = require('merge');
var Field = require('./field');

module.exports = function() {
    return merge.recursive(Field(), {
        data:function() {
            return {
                fieldType:'timerange',
                times: getTimes(),
                from:'',
                to:''
            }
        },
        ready: function() {
            this.$set('rules.timerange', true);
            this.$set('messages.timerange', 'Invalid time range');
        },
        computed: {
            value: function() {
                if (!this.from || !this.to) return '';
                return this.from + '-' + this.to;
            }
        }
    });

}

function getTimes() {
    var times = [];
    for (var i=0; i<24; i++) {
        for (var j=0; j<60; j = j+30) {
            times.push(('0' + i).slice(-2) + ":" + ('0' + j).slice(-2));
        }
    }

    return times;
}