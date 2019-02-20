<template>
    <div class="week-slots">

        <vf-form
                ajax
                action="/collect/step3"
                method="POST"
                v-ref:weekslots
        >


            <div class="row">
                <weekday
                        v-for="day in days"

                        :day="day"
                        :max-num="_maxNum"
                ></weekday>
            </div>

            <hr>

            <slot></slot>

            <button-container>
                <vf-submit text="Next"></vf-submit>
            </button-container>

            <!--<input type="number" min="0" :max="_max" v-model="day.perDay" class="form-control">-->

        </vf-form>
    </div>
</template>

<script type="text/babel">

    import ButtonContainer from '../components/accordion_wizard/ButtonContainer'

    export default {

        components: {
            ButtonContainer,
            'weekday': {
                template: `
                <div class="row">
                   <div class="col-xs-3"> {{ dayName }}</div>

                   <div class="col-xs-3">

                        <div class="onoffswitch">
                            <input type="checkbox" class="onoffswitch-checkbox" :id="name" v-model="day.selected">
                            <label class="onoffswitch-label" :for="name">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>

                   </div>

                   <div class="col-xs-6">
                       <div v-if="day.selected" class="row">
                            <div class="col-xs-7">

                                <vf-num :min="0" :max="maxNum" :disabled="true" :value.sync="day.perDay"></vf-num>
                            </div>
                            <div class="col-xs-5"> perDay </div>
                            <div class="col-xs-12 small-text" v-if="showError">According your plan you cannot pass values greater than {{maxNum}}</div>

                        </div>
                   </div>
                </div>
                `,
                props: {
                    day: {
                        type: Object
                    },

                    maxNum: {
                        type: [Boolean, Number],
                        default: 100
                    }
                },

                computed: {
                    dayName () {
                        return this.day.weekday.format('dddd')
                    },

                    name () {
                        return `onoff_${this.day.weekday.format('d')}`
                    },

                    showError () {
                        return +this.day.perDay === +this.maxNum
                    }
                },

                data () {
                    return {}
                }
            }
        },

        props: {
            maxNum: {
                type: Number,
                default: 0
            }
        },

        data() {
            return {
                days: []
            }
        },

        computed: {
            _maxNum () {
                return this.maxNum !== 0 && this.maxNum;
            }
        },

        events: {
            /**
             * При изпращане на формата добавяме данните
             */
            'vue-formular.sending' () {
                this.$refs.weekslots.options.additionalPayload = {'days': this.days};
            }
        },

        created () {
            for(let i=0; i<=6;i++) {
                this.days.push({
                    localeDay: `${i}`,
                    selected: false,
                    weekday: moment(i, 'd'),
                    perDay: 0
                });
            }
        }
    }
</script>

<style lang="sass" scoped>
</style>