<template>
    <div v-show="show" class="row" transition="slide">

        <div class="col-xs-12 m-b-30">
            <h4>{{ _categoryName }}</h4>
            <p>{{ _categorySubhead }}</p>
            <div v-if="_infoList">
                <ul class="remember">
                    <li v-for="list in _infoList">{{ list }}</li>
                </ul>
            </div>
        </div>

        <div v-for="subcategory of categories" class="col-xs-{{ colClass }}">

            <div v-if="!isList" class="row th">
                <div class="{{ _isExtra ? 'col-xs-6' : 'col-xs-7' }}">{{ subcategory.name }}</div>
                <div class="{{ _isExtra ? 'col-xs-3' : 'col-xs-5' }}">Price</div>
                <div v-if="_isExtra" class="{{ isList ? 'col-xs-5' : 'col-xs-3' }}">Don't offer</div>
            </div>

            <div v-if="isList" class="row th">
                <div v-if="$index == 0" class="col-xs-7">Size</div>
                <div class="col-xs-5">Price {{ subcategory.name }}</div>
                <div v-if="$index == 1" class="col-xs-7">Don't offer</div>
            </div>

            <step-service
                v-for="service of subcategory.data"
                :service="service"
                :is-extra="_isExtra"
            ></step-service>

        </div>

        <button-container>
            <button v-if="showNext" @click.prevent="next()">Next</button>
            <vf-submit v-if="!showNext" text="Next"></vf-submit>
        </button-container>


    </div>
</template>

<script type="text/babel">
    /**
     * Подкатегория на оснвона категория, като от своя страна
     * се разделя на тип основна или екстра -
     */

    import StepService from './StepService'
    import ButtonContainer from '../accordion_wizard/ButtonContainer'

    export default {
        components: {
            StepService,
            ButtonContainer
        },

        props: {
            categories: {
                type: Array,
                default: () => {}
            },

            categoryName: {
                type: String,
                default: ''
            },

            step: {
                type: Number,
                default: 1
            },

            iteration: {
                type: Number,
                default: 0
            },

            currStep: {
                type: Number
            },

            showNext: {
                type: Boolean
            },
            animationDuration: {
                type: Number,
                default: 500
            },
            isExtra: {
                type: String
            },
            /**
             * TODO: За сега, после ще се взема по някакъв начин от
             * категориите
             */
            info: null

        },

        data() {
            return {

            }
        },

        computed: {
            isList () {

                /**
                 * Някой услуги могат да имат по няколко цени в зависимост от
                 * избраната от потребителя бройка (за отстъпка)
                 * По дизайн категориите в които има такива услуги трябва да се
                 * покажат една до друга - затова са тези изчисления
                 *
                 * TODO:
                 */
                return _.some(this.categories, (el) => {
                    return _.some(el.data, (s) => {
                        return s.prices_number != 1;
                    })
                });
            },

            colClass () {
                return this.isList ? 12 / _.size(this.categories) : 12;
            },

            /**
             * Номерираме стъпките
             * Това е вътрешния номер на конкретната стъпка
             *
             * @returns {*}
             * @private
             */
            _step () {
                return this.iteration + this.step + 1
            },

            /**
             * Дали да я показваме
             *
             * @returns {boolean}
             */
            show () {
                return this._step == this.currStep;
            },

            /**
             * Определяме дали се връщаме на предна стъпка или продължаваме
             * За анимацията
             *
             * @returns {boolean}
             */
            isReturn () {
                return this._step > this.currStep
            },

//            showName () {
//                return _.isNil(this.$children.idx) || this.$children.idx === 1
//            },
//
//            showCheck () {
//                return _.isNil(this.$children.idx) || this.$children.idx === 2
//            },

            /**
             * Дали е екстра услуга или основна
             *
             * @returns {boolean}
             * @private
             */
            _isExtra () {
                return this.isExtra === "true";
            },

            _categoryName () {
                return this._isExtra ? 'Additional services' : `${this.categoryName} prices`
            },

            _categorySubhead () {
                return _.has(this.info, `${this._step}`) ? this.info[this._step].subhead : '';
            },

            _infoList () {
                return _.has(this.info, `${this._step}`) ? this.info[this._step].list : false;
            }


        },

        methods: {
            next () {
                this.$dispatch('go-next')
            }
        },

        events: {
            /**
             * това е глупост за да може да се чекне целия ред при услуги
             * които се извъртат в loop
             * @param value
             */
            checks (value) {
                this.$broadcast('some_is_checked', value)
            }
        },

        transitions: {
            slide: {
                enter: function (el, done) {
                    // element is already inserted into the DOM
                    // call done when animation finishes.
                    $(el).animate({ opacity: 1, marginLeft: '0' }, this.animationDuration, done)
                },

                leave: function (el, done) {

                    $(el).animate({ opacity: 0, marginLeft: ( this.isReturn ) ? '200px' : '-200px' }, this.animationDuration, done)
                },
            }
        },

        ready () {

            _.filter(
                this.$children, function (el) {
                return el.$options.name === 'step-service';
            })
                .forEach(function (el, index) {
                    if (el.$options.name === 'step-service') {
                        let cnt = _.filter(el.$children, function(item) {
                            return item.$options.name === 'step-service'
                        }).length;

                        if ( cnt > 0) {
                            el.idx = index+1;
                        }
                    }
                });
        }

    }
</script>

<style lang="scss" scoped>
    .slide-enter {
        margin-left: 200px;
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
</style>