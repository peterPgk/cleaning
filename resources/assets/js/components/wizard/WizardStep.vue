<style scoped>
    .gritcode-wizard .wizard-step {
        display: none;
        width: auto;
        border: 0;
        text-align: center;
        position: relative;
        cursor: pointer;
    }
    .gritcode-wizard .wizard-step.active {
        display: table-cell;
    }
    .gritcode-wizard .wizard-step .wizard-icon {
        display: table;
        width: 3em;
        height: 3em;
        background-color: #eaeaea;
        text-align: center;
        color: #a9a9a9;
        border: 2px solid #d9d9d9;
        position: relative;
        z-index: 1;
        -webkit-border-radius: 50%;
        border-radius: 50%;
        z-index: 22;
        margin: auto;
        margin-bottom: 1em;
    }
    .gritcode-wizard .wizard-step .wizard-icon .icon-number,
    .gritcode-wizard .wizard-step .wizard-icon .icon-icon {
        display: table-cell;
        vertical-align: middle;
    }
    .gritcode-wizard .wizard-step .wizard-icon .icon-number {
        line-height: 1em;
    }
    .gritcode-wizard .wizard-step .title {
        font-size: 1.1em;
        color: #464646;
    }
    .gritcode-wizard .wizard-step .description,
    .gritcode-wizard .wizard-step .step-info {
        font-size: 0.8em;
        color: #a8a8a8;
    }
    .gritcode-wizard .wizard-step .description {
        margin-bottom: 2em;
    }
    .gritcode-wizard .wizard-step .wizard-progress, .gritcode-wizard .wizard-step .wizard-progress-value {
        position: absolute;
        bottom: 2em;
        left: 0;
        width: 100%;
        height: 2px;
        background: #d9d9d9;
        z-index: 10;
    }
    .gritcode-wizard .wizard-step .wizard-progress-value {
        top: 0;
        left: 0;
        width: 0;
        background: #5cb85c;
        z-index: 11;
        padding: 0;
        transition: 0.45s width ease;
    }
    .gritcode-wizard .wizard-step .step-info {
        text-align: right;
    }
    .gritcode-wizard .wizard-step.active .wizard-icon, .gritcode-wizard .wizard-step.previous .wizard-icon {
        border-color: #5cb85c;
        color: #5cb85c;
    }
    .gritcode-wizard .wizard-step.active .icon, .gritcode-wizard .wizard-step.previous .icon {
        fill: #5cb85c;
    }
    .gritcode-wizard .wizard-step:last-child .wizard-progress-value {
        width: 100% !important;
    }
    @media (min-width: 544px) {
        .gritcode-wizard .wizard-step {
            display: table-cell;
        }
        .gritcode-wizard .wizard-step .description {
            margin-bottom: 0;
        }
        .gritcode-wizard .wizard-step .wizard-progress, .gritcode-wizard .wizard-step .wizard-progress-value {
            top: 1.45em;
            left: 49%;
        }
        .gritcode-wizard .wizard-step .wizard-progress-value {
            top: 0;
            left: 0;
            padding: 0 0.75em;
        }
        .gritcode-wizard .wizard-step .step-info {
            display: none;
        }
        .gritcode-wizard .wizard-step:last-child .wizard-progress {
            display: none;
        }
        .gritcode-wizard .wizard-step:last-child .wizard-progress-value {
            width: 100% !important;
        }
    }

</style>

<template>
    <div v-bind:class="{'wizard-step': true, 'active': isActive, 'previous' : isPrevious, 'next' : isNext}" v-on:click.prevent="changeCurrentIndex()">
        <div class="wizard-progress">
            <div class="wizard-progress-value"></div>
        </div>
        <div class="wizard-icon">
            <!--<div class="icon-icon"><vs-icon :name="icon" v-if="icon"></vs-icon></div>-->
            <!--<div class="icon-icon"><i class="glyphicon glyphicon-user" aria-hidden="true" v-if="icon"></i></div>-->
            <div class="icon-number" v-if="!icon">{{iconNumber || index +1}}</div>
        </div>
        <div class="wizard-content">
            <div class="title">{{title}}</div>
            <div class="description">{{description}}</div>
        </div>
        <div class="step-info">
            Step {{index+1}}/{{$parent.countItems}}
        </div>
    </div>
    <!--<slot slot="form"></slot>-->

</template>

<script>


    export default {
        data() {
            return {
                index: null,
                active: false,
            }
        },

        computed: {
            isActive() {
                return (this.$parent.currentIndex === this.index)
            },
            isPrevious() {
                // every step before current index
                return this.$parent.currentIndex > this.index;
            },
            isNext() {
                // everything after current index
                return this.$parent.currentIndex < this.index;
            }
        },
//
        props: {
            link: {
                type: String,
                default: '',
            },
            icon: {
                type: String,
                default: false,
            },
            iconNumber: {
                type: String,
                default: false,
            },
            title: {
                type: String,
                default: false,
            },
            description: {
                type: String,
                default: false,
            },
            progress: {
                type: Number,
                default: 0,
            },
            valid: {
                type: Boolean,
                default: false,
            },
            disablePrevious: {
                type: Boolean,
                default: false,
            },
        },

        methods: {
            changeCurrentIndex() {
                if (this.$parent.changeCurrentIndex(this.index) && this.link) {
                    // redirect user to the new location
                    this.changeLocation(this.$router, this.link);
                }
            },

            changeLocation(router, link) {
                if (!link) return;
                if (router) {
                    router.go(link)
                } else {
                    window.location.href = link
                }
            }
        },

        watch: {
            progress(val) {
                console.log( val, 'progress' );
                this._progressBar.style.width = val + '%';
                this.valid = val === 100;
            },

            valid(val) {
                if (val) {
                    this.progress = 100;
                }
            }
        },
//
//        components: {
////            vsIcon,
//        },
//
        ready() {
//            console.log( this.$el, 'ready' );
            this._progressBar = this.$el.querySelector('.wizard-progress-value')
        }
    }
</script>