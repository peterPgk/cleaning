<template>
    <div>

        <div v-if="mustLoop" v-for="num in cnt" class="looping">

            <step-service :service="service" :old-price="getOldPrice(num + 1)" :num="num + 1" :in-loop="true"></step-service>

        </div>

        <div v-if="!mustLoop">

            <div class="row form-group" :class="{'is_disabled': checked}">
                <div v-if="showName" class="{{ showCheck ? 'col-xs-6' : 'col-xs-7' }}">
                    <label>{{ _printName }}</label>
                </div>
                <div class="{{ inLoop ? 'col-xs-5' : 'col-xs-3' }}">

                <!--<div class="col-xs-3">-->
                    <!--<input v-model="price" type="number" :name="_elementName" class="form-control" :disabled="checked"/>-->
                    <div class="price-group clearfix">
                    <vf-text
                            :value.sync="price"
                            :name="_elementName"
                            :disabled="checked"
                            :min="0"
                            :rules="{price:true}"
                    >
                        <!--<span class="input-group-addon" slot="beforeField">{{ price_sign }}</span>-->
                        <span class="price-sign" slot="beforeField">{{ price_sign }}</span>
                    </vf-text>
                    </div>
                </div>
                <!--<div v-if="showCheck" class="col-xs-4">-->
                <div v-if="showCheck" class="{{ showName ? 'col-xs-3' : 'col-xs-7' }}">
                <!--<div class="col-xs-4 ">-->
                    <input type="checkbox" v-model="checked"> don't offer
                </div>
            </div>

        </div>

    </div>
</template>

<script type="text/babel">

    export default {

        /**
         * Използваме рекурсивно извикване
         */
        name: 'step-service',

        props: {
            service: {
                type: Object,
                'default': () => {}
            },

            /**
             * Когато трябва да покажем даден елемент
             * n-пъти това е номера на поредната итерация
             */
            num: {
                type: Number,
                'default': 0
            },

            /**
             * Дали сме извикали рекурсивно елемента.
             * Може да използваме и типа на $parent елемента,
             * за да определим това
             */
            inLoop: {
                type: Boolean,
                'default': false
            },

            isExtra: {
                type: Boolean,
                'default': false
            },

            oldPrice: {
                type: [String, Number],
                'default': null
            },

            price_sign: {
                type: String,
                'default': '£'
            }
        },

        data() {
            return {
                checked: false,
                price: '',
                idx: null,
                human_names: {
                    1: 'One',
                    2: 'Two',
                    3: 'Three',
                    4: 'Four',
                    5: 'Five',
                    6: 'Six',
                    7: 'Seven'
                }
            }
        },

        computed: {
            mustLoop () {
                return !this.inLoop && this.cnt > 1;
            },

            cnt () {
                return this.service.prices_number;
            },

            /**
             *
             * @returns {string}
             * @private
             */
            _printName () {
                return this.num <= 1
                    ? this.inLoop
                        ? `${this.human_names[this.num]} ${this.service.name}`
                        : this.service.name
                    : `${this.human_names[this.num]} ${this.service.name}s`
            },

            _elementName () {
                return this.num <= 1 ? `${this.service.id}` : `${this.service.id}_${this.num}`
            },

            showName () {
                return _.isNil(this.$parent.idx) || this.$parent.idx === 1
            },

            showCheck () {
                /**
                 * Нямаше чек на картинката при едните главни услуги
                 * Когато фирмата прекъсне попълването на формата и се върне на по-късен етап,
                 * трябва да се чекнат услугите, които нямат цени (т.е. да се маркират като don't offer)
                 * Ако няма чек, става проблем с това, че не можеш да си ънчекнеш услугата за да
                 * въведеш цена, затова го показвам навсякъде
                 */
//                return (_.isNil(this.$parent.idx) && this.isExtra ) || this.$parent.idx === 2
                return _.isNil(this.$parent.idx) || this.$parent.idx === 2
            },

            _prices () {
                return !_.isNil(this.service.my_price) && !_.isNil(this.service.my_price.prices) ? JSON.parse(this.service.my_price.prices) : {};
            }

        },

        methods: {
            getOldPrice (num) {

                return (_.has(this._prices, num))
                    ? this._prices[num]
                    : num == 1 && !_.isNil(this.service.my_price)
                        ? this.service.my_price.price
                        : null;
            }
        },

        events: {
            /**
             * Правим тези глупости за да може да маркираме същия елемент от
             * другия тип услуга
             *
             * @param value
             * @returns {boolean}
             */
            'some_is_checked' (value) {

                if ( this.mustLoop ) {
                    /**
                     * Ако е главен елемент (елемент, който рекурсивно създава step-service-s
                     * това не ни интересува и пропускаме нататък
                     */
                    return true
                }
                else {
                    /**
                     * Не съм аз елемента, който предизвиква евента и
                     * имам номер, като този, който го предизвиква
                     */
                    if ( !(this === value.elem) && (value.elem.num == this.num)) {
                        this.checked = value.is_checked;
                    }
                }
            }
        },

        watch: {
            checked (val) {
                if ( this.inLoop ) {
                    this.$dispatch('checks', {elem: this, is_checked: val})
                }
                if (val) {
                    this.price = '';
                }
            }
        },

        ready () {
            /**
             * Най-тъпия начин
             */
//            if ( this.inLoop && !_.isNil(this.oldPrice) ) {
            if ( this.inLoop ) {
                //тряба да сме на елемент, който има няколко цени
                this.price = this.oldPrice || '';
                this.checked = _.isNil(this.oldPrice);
            }
            else {

                let nil = _.isNil(this.service.my_price);

                this.price = !nil ? this.service.my_price.price : '';
                this.checked = nil;
            }

        }
    }

</script>

<style lang="sass" scoped>
</style>