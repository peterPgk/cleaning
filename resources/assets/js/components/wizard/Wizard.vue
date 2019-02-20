<style scoped>
    .gritcode-wizard {
        overflow: hidden;
        display: table;
        table-layout: fixed;
        width: 100%;
        font-size: 0.8rem;
    }

    @media (min-width: 544px) {
        .gritcode-wizard {
            font-size: 0.9rem;
        }
    }
    @media (min-width: 768px) {
        .gritcode-wizard {
            font-size: 1.2rem;
        }
    }

</style>
<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Wizard Component</div>

                    <div class="panel-body">
                        <div class="gritcode-wizard">
                            <slot name="head"></slot>
                        </div>
                        <div class="forms">
                            <slot name="form"></slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        props: {
            /**
             * This holds in which step we are
             */
            currentIndex: {
                type: Number,
                default: 0,
            },
        },

        data() {
            return {
                countItems: 0,

            }
        },

        methods: {
//            changeCurrentIndex(index) {
//                // change currentIndex
//                // if previous step is valid
//                // if previousDisabled is not set on the current step
//                if (this.$children[this.currentIndex].disablePrevious && this.currentIndex > index) return false;
//                if (this.$children[index - 1] && this.$children[index - 1].valid || index < this.currentIndex) {
//                    this.currentIndex = index;
//                    return true;
//                }
//                return false;
//            },
        },

        ready() {
            // get all steps
            this.countItems = this.$children.length;

            // set index for each wiard-step component
            this.$children.forEach((item, index) => {
                item.index = index;
            })
        }
    }
</script>

