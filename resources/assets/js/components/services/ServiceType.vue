<template>
    <div>
        <!-- Когато компонента се използва за клиентски интерфейс -->
        <!--<div class="row"  v-if="showOnClient" style="margin-top: 5px;">-->
            <!--<div class="col-xs-5">-->
                <!--<div class="checkbox">-->
                    <!--<label>-->
                        <!--<input type="checkbox" v-model="checked">-->
                        <!--{{serviceData.name}}-->
                    <!--</label>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="inner-client col-xs-7">-->
                <!--<div class="form-group">-->
                    <!--<vf-num-placeholder-->
                            <!--:name="_name"-->
                            <!--:label="serviceData.name"-->
                            <!--:min="0"-->
                            <!--:max="10"-->
                            <!--:debounce="debounce"-->
                            <!--:value.sync="price"-->
                            <!--:hide-label="true"-->
                            <!--:rules="{required:checked,_name:true}"-->
                    <!--&gt;</vf-num-placeholder>-->

                    <!--&lt;!&ndash;<input type="number" v-model="price">&ndash;&gt;-->
                    <!--&lt;!&ndash;<label>{{clientPrice}}</label>&ndash;&gt;-->

                    <!--<hr>-->

                    <!--&lt;!&ndash;TODO: Validation and messages &ndash;&gt;-->
                            <!--&lt;!&ndash;:additional-taxes="additionalTaxes"&ndash;&gt;-->
                    <!--<service-type-->
                            <!--v-for="subService in subServices"-->
                            <!--type="client"-->
                            <!--:service-data="subService"-->
                            <!--:selected-data="getSelected(subService)"-->
                    <!--&gt;</service-type>-->

                <!--</div>-->

            <!--</div>-->
        <!--</div>-->

        <!-- Слайдер, показва се, когато компонента се използва за потребителски интерфейс -->
        <div class="row">
            <!--<div class="col-xs-12" v-if="showOnUser">-->
            <div class="col-xs-12">
                <!--<vf-slider-->
                <vf-num-placeholder
                        :label="serviceData.name"
                        :name="_nameClient"
                        :min="0"
                        :max="serviceData.limit"
                        :value="price"
                        :hide-label="true"
                        :tooltip="false"
                ></vf-num-placeholder>
                <!-- Kris -->
                <p class="extra_info" v-if="serviceData.description != ''">{{{ _description }}}</p>

                <!--<vf-select-->
                        <!--name="related"-->
                        <!--v-if="showRelated"-->
                        <!--:items="makeRelated"-->
                <!--&gt;</vf-select>-->

                <div v-show="false">
                    <vf-text
                            v-show=false
                            v-if="showRelated"
                            :name="relatedService"
                            :value=1
                    ></vf-text>
                </div>
                <select v-model="relatedService" name="test" class="form-control" v-if="showRelated">
                    <option selected>Select an option</option>
                    <option v-for="related in makeRelated" value="{{ related.id }}">{{ related.text }}</option>
                </select>

                <!--<span>{{serviceData.limit}}</span>-->
                <!--<span class="selected">( {{chosen}} )</span>-->
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {

        name: 'service-type',

        props: {
            /**
             * Service to show
             */
            serviceData: {
                required: true,
                type: [Object, Array]
            },
            /**
             * I this service is selected
             */
            selectedData: {
                type: Number,
                'default': 0,
                required: false
            },
            isExtra: {
                type: Boolean,
                default: true
            }
        },

        data () {
            return {
                debounce: 0,
//                checked: false,
                price: 0,
//                chosen: 0,
                /**
                 * Избрания тип зависим сървис.
                 * За да се
                 */
                relatedService: ''
            }
        },

        computed: {
            /**
             * We are using service id as its name,
             * sometimes must cast to string
             */
            _name () {
                return `${this.serviceData.id}`
            },

            _nameClient () {
                return `services_${this.serviceData.id}`
            },


//            clientPrice () {
//                let price = +this.price;
//                if(!_.isEmpty(this.additionalTaxes)) {
//                    _.each(this.additionalTaxes, function (value, key) {
//                        price = price + (price * value)
//                    })
//                }
//                //Vue 2.0 - Cannot use currency filter
//                return `£${price.toFixed(2)}`;
//            },

//            showOnClient () {
//                return this.type === 'client' && this.checked;
//            },
//
//            showOnUser () {
//                /**
//                 * Нова промяна, вече трябва всички да се показват когато се
//                 * при потребител (във booking страницата )
//                 */
////                return this.type === 'user' && this.checked;
//                return this.type === 'user';
//            },

            /**
             * В старата логика на компонента всяка услуга (serviceType) имаше подуслуги (отново от
             * тип serviceType) които служеха за допълнителна филтрация.
             * Те се показваха само в админската част на фирмата за да се попълнят цени.
             *
             * @returns {boolean|*|number}
             */
            subServices () {
                return this.serviceData.sub
            },

            /**
             * В новата логика на компонента дадена услуга (serviceType) може да има свързани услуги
             * от които да се избира.
             * В потребителската част (type === user) трябва да се показват като select, за да може
             * потребителя да избере един от тях, а в админската част трябва да се показват като останалите
             * услуги, за да може да им се постави цена
             *
             * @returns {*|boolean}
             */
            showRelated() {
//                return _.size(this.serviceData.related) && this.checked
                /**
                 * това са свързаните услуги, за да работи, трябва да сложа prise.sync в vf-number компонента.
                 * за сега го скривам, не знам дали ще трябва
                 */
//                return _.size(this.serviceData.related) && this.price != 0

                return false;
            },

            /**
             * Прави related услугите за дисплейване
             */
            makeRelated() {
                return _.map(this.serviceData.related, (el) => {return {id: `related_${el.id}`, text: el.name}});
            },

            _description () {
                return `<i class="fa fa-question-circle"></i> ${this.serviceData.description}` || ''
            }

        },

//        methods: {
//            getSelected (service) {
//
////                Array of objects
////                return _.find(this.selectedData.sub, ['id', `${service.id}`]) || {}
//                return _.find(this.selectedData.sub, {'id': `${service.id}`}) || {}
//            }
//        },

        watch: {
//            checked (newValue) {
//                if( !newValue )
//                    this.price = 0;
//
//            }
        },

        events: {
//            'slide-change' (value) {
//                this.chosen = value;
//            }
        },

        ready () {
            this.price = (!_.isNil(this.selectedData) && _.isFinite(this.selectedData)) ? this.selectedData : 0;
        }

    }
</script>

<style scoped>
    .selected {
        font-weight: bold;
        padding: 0 5px;
        color: #0a75df;
    }
</style>