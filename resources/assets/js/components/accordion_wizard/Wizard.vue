<template>
    <div class="panel-group {{topClass}}">
        <slot></slot>
    </div>
</template>

<script type="text/babel">

    import {coerce} from '../../utils/utils'

    export default {

        data() {
            return {
                countItems: 0
            }
        },

        props: {
            type: {
                type: String,
                default: null
            },
            oneAtAtime: {
                type: Boolean,
                coerce: coerce.boolean,
                default: false
            },
            topClass: {
                type: String,
                default: 'wizard'
            },
            /**
             * Дали да се показва каунтър на стъпките
             */
            showCounter: {
                type: Boolean,
                default: false
            },
            /**
             * Ново 20
             * Да може да зададем колко стъпки да показва слайдра
             */
            forceCountSteps: {
                type: Number,
                default: 0
            },
            /**
             * Има ли последна стъпка  - wizard final step -
             * за брояча на стъпките, дали да я брои или не
             */
            hasFinal: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            _forceCountSteps () {
                return this.forceCountSteps !== 0 && this.forceCountSteps;
            }
        },

        created () {
            this._isAccordion = true
            this.$on('isOpenEvent', (child) => {
                if (this.oneAtAtime) {
                    this.$children.forEach((item) => {
                        if (child !== item) {
                            item.isOpen = false
                        }
                    })
                }

                this.$dispatch('wizard::toggle', child )
            })

            return true;
        },

        ready() {
            // get all wizard steps
            this.countItems = this.$children.length;

            // set index for each wizard-step component
            this.$children.forEach((item, index) => {
                item.index = index + 1;

            })

            this.$broadcast('wizard::children_init')

        }
    }
</script>

<style lang="scss" scoped>

</style>