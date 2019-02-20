<template xmlns:v-el="http://www.w3.org/1999/xhtml">
    <div>
        <div class="panel panel-default" v-show="_show" transition="slide">
            <!--<div class="panel panel-default" v-if="_show" >-->
            <div :class="['panel-heading', 'accordion-toggle']">
                <slot name="title">
                    <div class="wizard-content">
                        <!-- Kris -->
                        <div class="stepcounter" v-if="showCounter">step <b>{{ currentStep - 1 }}</b> of <b>{{ _allItems }}</b></div>
                        <div class="title">{{ title }}</div>
                        <p class="description">{{ description }}</p>
                    </div>
                </slot>
            </div>
            <div class="panel-collapse"
                 v-el:panel
            >
                <!--v-show="isOpen"-->
                <div class="panel-body">
                    <slot></slot>

                    <div class="spinner" v-if="loading">
                        <scale-loader :loading="loading" :color="color" :size="loaderSize"
                                      class="text-center"></scale-loader>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import WizardStep from '../accordion_wizard/WizardStep'

    export default {

        extends: WizardStep,

        props: {
            animationDuration: {
                type: Number,
                default: 500
            },

            /**
             * Да покажем ли слайдера в конкретната стъпка
             */
            forceHideCounter: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            /**
             * Дали да покажем самата стъпка
             */
            _show () {
                return this.currentStep === this.index;
            },

            /**
             * Дали имаме последна стъпка в слайдера.
             * Използва се за коунтъра на стъпките
             */
            _hasFinal () {
                return this.$parent.hasFinal
            },

            /**
             * Колко са стъпките
             * Променя се няколко пъти заданието :(
             */
            _allItems () {
                return this._forceCountSteps || (this._hasFinal ? (this.$parent.countItems - 1): this.$parent.countItems);
            },

            /**
             * Да покажем ли слайдера на конкретната стъпка
             * Може изрично да го скрием, или вземаме натройката от Wizard компонента
             *
             * Понеже измислиха да има стъпка 0 и в случая тя няма да се показва,
             * като насточща стъпка се задава currentStep - 1, и заради това трябва да се промени
             * (this.index > this._allItems) да стане (this.index >= this._allItems)
             */
            showCounter () {
//                return !this.forceHideCounter && (this.$parent.showCounter && !(this.index < this._allItems));
//                return (this.$parent.showCounter && !(this.index >= this._allItems));
                return !this.forceHideCounter && (this.$parent.showCounter && (this.index - 1 <= this._allItems));
            },

            /**
             *
             * @returns {boolean|computed._forceCountSteps}
             * @private
             */
            _forceCountSteps () {
                return this.$parent._forceCountSteps !== 0 && this.$parent._forceCountSteps
            },


            /**
             * Определяме дали се връщаме на предна стъпка или продължаваме
             * @returns {boolean}
             */
            isReturn () {
                return this.index > this.currentStep
            },


        },

        transitions: {
            slide: {
//                beforeEnter: function (el) {
//                    $(el).css('pointerEvents', 'auto')
//                },

                enter: function (el, done) {
                    // element is already inserted into the DOM
                    // call done when animation finishes.
                    $(el).animate({ opacity: 1, marginLeft: '0' }, this.animationDuration, done)
                },

                leave: function (el, done) {

                    $(el).animate({ opacity: 0, marginLeft: ( this.isReturn ) ? '200px' : '-200px' }, this.animationDuration, done)
                },
//
//                afterLeave: function (el) {
//                    $(el).css('pointerEvents', 'none')
//                }
            }
        }

    }
</script>

<style lang="scss" scoped>

    .slide-enter {
        margin-left: 200px;
    }

    .accordion-toggle {
        display: table;
        width: 100%;
        text-aligh: left;

    & > div {
            display: table-cell;
            vertical-align: middle;
            padding: 0 2em 0 0;

        }

    .title {
        font-size: 1.1em;
        color: #464646;
        text-align: center;
    }

    }

    .panel {
        width: 100%;
        background-color: #f4f7f9;
        border: 0 #f4f7f9;
        padding-bottom: 80px;
        position: absolute;

    .panel-body {
        position: relative;
    }

    }

    .panel-default {

    & > .panel-heading {
            background-color: #f4f7f9;

    .title {
        font-size: 30px;
        font-weight: bold;
        text-align: left;
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
        flex-flow: row wrap;
        width: 100%;
        height: 100%;
        align-items: center;
        justify-content: center;
        background: rgba(244, 247, 249, 0.7);
        z-index: 1000;
    }

</style>
