<template>
    <ul class="computedClass">
        <slot></slot>
    </ul>
</template>

<script>

    export default {
        mixins: [
            require("vue-mixins/class"),
            require("vue-mixins/transition")
        ],
        props: {

            "transition": {
                type: String
            },
            "accordion": {
                type: Boolean,
                "default": false
            },
            "class": {
                "default": function() {
                    return ["collapsible"];
                }
            },
            "itemClass": {
                type: String,
                "default": "collapsible-item"
            },
            "headerClass": {
                type: String,
                "default": "collapsible-header"
            },
            "bodyClass": {
                type: String,
                "default": "collapsible-body"
            },
            "noScroll": {
                type: Boolean,
                "default": false
            },
            "scrollTransition": {
                type: Function,
                "default": function(top) {
                    return typeof window !== "undefined" && window !== null ? typeof window.scrollBy === "function" ? window.scrollBy(0, top) : void 0 : void 0;
                }
            }
        },
        methods: {
            closeAll: function(sender) {
                var beforeSender, child, i, index, len, ref, results;
                beforeSender = false;
                ref = this.$children;
                results = [];
                for (index = i = 0, len = ref.length; i < len; index = ++i) {
                    child = ref[index];
                    if (sender === child) {
                        beforeSender = true;
                        continue;
                    }
                    if (child.isCollapsibleItem && !child.stayOpen) {
                        results.push(child.close(!beforeSender));
                    } else {
                        results.push(void 0);
                    }
                }
                return results;
            }
        },

    };

</script>