<template>
    <li :class="computedClass">
        <a :class="headerClass" @click="toggle">
            <slot name="header"></slot>
        </a>
        <div v-if="isOpened" :transition="cTransition" :class="bodyClass">
            <slot></slot>
        </div>
    </li>
</template>

<script>
    export default {
        mixins: [
            require("vue-mixins/isOpened"),
            require("vue-mixins/class"),
            require("vue-mixins/transition")
        ],
        props: {
            "stayOpen": {
                type: Boolean
               , "default": false
            },
            val: {
                twoWay: true
            }
        },
        computed: {
            mergeClass: function() {
                var tmp;
                tmp = [this.$parent.itemClass];
                if (this.isOpened) {
                    tmp.push("active");
                }
                return tmp;
            },
            headerClass: function() {
                var tmp;
                tmp = [this.$parent.headerClass];
                if (this.isOpened) {
                    tmp.push("active");
                }
                return tmp;
            },
            bodyClass: function() {
                return [this.$parent.bodyClass];
            },
            cTransition: function() {
                var name;
                name = this.transition;
                if (name == null) {
                    name = this.$parent.transition;
                }
                if (name == null) {
                    name = "default";
                }
                this.processTransition(name, this.$parent.$parent);
                return name;
            }
        },
        data: function() {
            return {
                isCollapsibleItem: true
            };
        },
        methods: {
            show: function() {
                return this.setOpened();
            },
            hide: function() {
                return this.setClosed();
            },
            open: function() {
                this.show();
                if (this.$parent.accordion) {
                    return this.$parent.closeAll(this);
                }
            },
            close: function(scroll) {
                var top;
                if (this.opened) {
                    if (scroll && !this.$parent.noScroll) {
                        top = this.$el.children[1].getBoundingClientRect().top;
                        if (top < 0) {
                            this.$parent.scrollTransition(top);
                        }
                    }
                    return this.hide();
                }
            },
            toggle: function(e) {
                if (e != null) {
                    if (e.defaultPrevented) {
                        return;
                    }
                    e.preventDefault();
                }
                if (this.opened) {
                    return this.close();
                } else {
                    return this.open();
                }
            }
        }
    };
</script>