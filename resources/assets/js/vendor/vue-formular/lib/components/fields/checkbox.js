var merge = require('merge');
var Field = require('./field');

module.exports = function() {
  return merge.recursive(Field(), {
    props: {
      checked: {
        type: Boolean,
        default:undefined
      },
        name: {
            type:[String, Number],
            required:false
        },
    },
    ready: function() {
      if (typeof this.checked=='undefined') {
        this.value = false;
        this.dirty = true;
      }
    },
    computed: {
      value: {
        get:function() {
          return this.checked;
        },
        set:function(val) {
          this.checked= val!=0;
        }
      }
    },
    methods: {
      reset: function() {
        this.wasReset = true;        
        this.checked = undefined;
      }
    },
    data: function() {
      return {
        initialValue: this.checked,
        fieldType:'checkbox',
      }
    }
});
}

