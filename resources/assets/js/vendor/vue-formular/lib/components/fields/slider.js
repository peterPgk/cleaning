//from bootstrap-slider

var merge = require('merge');
var Field = require('./field');

module.exports = function (vm) {
    return merge.recursive(Field(), {
        props: {
            min: {
                type: Number,
                required: true
            },
            max: {
                type: Number,
                required: true
            },
            ticks: {
                type: Array,
                required: false
            },
            ticksLabels: {
                type: Array,
                required: false
            },
            step : {
                type : Number,
                required : false,
                default : 1
            },
            tooltip : {
                type : Boolean,
                required : false,
                default : true
            },
            changeEventName : {
                type : String,
                required : false,
                default : "slide-change"
            },
            updateValue: {
                type: Boolean,
                required: false,
                default: true
            }
        },
        data () {
            return {
                fieldType:'slider'
            }
        },
        ready: function () {

            vm.nextTick(() => {
                let sliderElement = $("#" + this.name);

                var options = {
                    value  : this.value,
                    ticks: this.ticks,
                    ticks_labels: this.ticksLabels,
                    min: this.min,
                    max: this.max,
                    step: this.step,
                    tooltip: this.tooltip ? "show" : 'hide'
                };

                sliderElement.slider(options);

                sliderElement.on("slide, change", (event) => {

                    this.$dispatch(this.changeEventName, event.value.hasOwnProperty('newValue') ? event.value.newValue : event.value)
                });

                if( this.updateValue ) {
                    sliderElement.on("slideStop", (event) => {
                        this.value = event.value;
                    })
                }
            });
        }
    });
};