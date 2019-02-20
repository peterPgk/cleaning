<template xmlns:v-el="http://www.w3.org/1999/xhtml">
    <div class="panel {{panelType}}">
        <div :class="['panel-heading', 'accordion-toggle']" @click.prevent="toggle()">
            <slot name="title">
                <div>
                    <div class="wizard-icon">
                        <div class="icon-icon"><i class="fa fa-{{ icon }}" v-if="icon"></i></div>
                        <div class="icon-number" v-if="!icon">{{iconNumber || index}}</div>
                    </div>
                </div>

                <div class="wizard-content">
                    <div class="title">{{title}}</div>
                    <div class="description">{{description}}</div>
                </div>
            </slot>
        </div>
        <div class="panel-collapse"
             v-el:panel
             v-show="isOpen"
             transition="collapse"
        >
            <div class="panel-body">
                <slot></slot>

                <div class="spinner" v-if="loading">
                <!--<div class="spinner" v-if="isSending">-->
                    <scale-loader :loading="loading" :color="color" :size="loaderSize" class="text-center"></scale-loader>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import { coerce } from '../../utils/utils'
    import ScaleLoader from 'vue-spinner/src/ScaleLoader.vue'

    export default {
        data() {
            return {
                index: null,
                loaderSize: "25px"
            }
        },

        components: {
            ScaleLoader
        },

        vuex: {
            getters: {
                currentStep: state => state.currentStep,
            },
            actions: {}
        },

        props: {
            title: {
                type: String
            },
            description: {
                type: String
            },
            icon: {
                type: String,
                default: false,
            },
            iconNumber: {
                type: String,
                default: false,
            },
            isOpen: {
                type: Boolean,
                coerce: coerce.boolean,
                default: false
            },
            valid: {
                type: Boolean,
                default: null
            },
            type: {
                type: String,
                default: 'default'
            },
            locked: {
                type: Boolean,
                default: false
            },
            loading: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            inAccordion () {
                return this.$parent && this.$parent._isAccordion
            },

            isCurrentStep () {
                return (this.index === this.currentStep);
            },

            /**
             * Defines panel color
             * Most weight has current validation (current form validation)
             * Then outside validation (we can pass if this panel is valid or not)
             * Finally property binding
             *
             * @returns {string}
             */
            panelType () {

                if( _.isNil(this.valid) ) {
                    return 'panel-' + (this.type || 'default')
                }
                return 'panel-' + (this.valid ? 'success' : 'danger');
            },
        },
        methods: {
            toggle () {

                if( this.locked )
                    return;

                //TODO: да се отваря само при valid=true = за сега ако го направя не е реактивно
//                if( this.isCurrentStep || this.valid !== null ) {
                if( this.isCurrentStep || !_.isNil(this.valid) ) {

                    this.isOpen = !this.isOpen;
                    this.$dispatch('isOpenEvent', this)
                }
            },
        },

        events: {
            /**
             *
             */
            "wizard::children_init" () {
                //Ако се използва computed пропъртито, връща undefined
//                this.isOpen = this.isCurrentStep;
                this.isOpen = this.index === this.currentStep;
            }
        },

        transitions: {
            collapse: {
                afterEnter (el) {
                    el.style.maxHeight = '';
                    el.style.overflow = '';
                },
                beforeLeave (el) {
                    el.style.maxHeight = el.offsetHeight + 'px';
                    el.style.overflow = 'hidden';
                    // Recalculate DOM before the class gets added.
                    return el.offsetHeight;
                }
            }
        },

    }
</script>

<style lang="scss" scoped>

    .accordion-toggle {
        cursor: pointer;
        display: table;
        width: 100%;
        text-aligh: left;

        & > div {
            display: table-cell;
            vertical-align: middle;
            padding: 0 2em 0 0;

            &:first-child {
                 width: 3em;
             }
        }

        .wizard-icon {
            display: table;
            width: 3em;
            height: 3em;
            background-color: #eaeaea;
            text-align: center;
            color: #a9a9a9;
            border: 2px solid #d9d9d9;
            position: relative;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            z-index: 2;
            margin: auto;

            .icon-number,
            .icon-icon {
                display: table-cell;
                vertical-align: middle;
            }

            .icon-number {
                line-height: 1em;
                font-weight: bold;
            }
        }

        .title {
            font-size: 1.1em;
            color: #464646;
            text-align: center;
        }

        .description {
            font-size: 0.8em;
            color: #a8a8a8;
        }

    }

    .panel {

        .panel-body {
            position: relative;
        }

        &.panel-success,
         .active,
         .previous {

            .wizard-icon {
                border-color: #5cb85c;
                color: #5cb85c;
            }
            .icon {
                fill: #5cb85c;
            }

         }

        &.panel-danger,
         .error {

            .wizard-icon {
                border-color: #d9534f;
                color: #d9534f;
            }
            .icon {
                fill: #d9534f;
            }

        }
    }

    .spinner {
        position: absolute;
        left: 0;
        top: 0;
        display: flex;
        display: -webkit-flex;
        flex: 0 1 auto;
        /*webkit-flex: 0 1 auto;*/
        flex-flow: row wrap;
        /*-webkit-flex-flow: row wrap;*/
        width: 100%;
        height: 100%;
        /*webkit-align-items: center;*/
        align-items: center;
        /*webkit-justify-content: center;*/
        justify-content: center;
        background: rgba(255,255,255,0.7);
        z-index: 1000;
    }

    .collapse-transition {
        transition: max-height .5s ease;
    }
    .collapse-enter, .collapse-leave {
        max-height: 0!important;
    }

</style>
