<template xmlns:v-el="http://www.w3.org/1999/xhtml">
    <div class="panel panel-default" :class="[_show]">
        <div :class="['panel-heading', 'accordion-toggle']">
            <slot name="title">
                <div class="wizard-content">
                    <div class="title">{{title}}</div>
                </div>
            </slot>
        </div>
        <div class="panel-collapse"
             v-el:panel
             v-show="isOpen"
        >
            <div class="panel-body">
                <slot></slot>

                <div class="spinner" v-if="loading">
                    <scale-loader :loading="loading" :color="color" :size="loaderSize" class="text-center"></scale-loader>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import WizardStep from '../accordion_wizard/WizardStep'

    export default {

        extends: WizardStep,

        computed: {
            _show () {
                return {
                    'hidden': this.currentStep !== this.index
                }
            },

        }

    }
</script>

<style lang="scss" scoped>

    .hidden {
        display: none;
    }

    .accordion-toggle {
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

        .title {
            font-size: 1.1em;
            color: #464646;
            text-align: center;
        }

    }

    .panel {
        .panel-body {
            position: relative;
        }

    }

    .panel-default {
        /*border-color: #f4f7f9;*/
        border-color: #f5f8fa;

        & > .panel-heading {
                /*background-color: #f4f7f9;*/
                background-color: #f5f8fa;
                font-size: 30px;
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
