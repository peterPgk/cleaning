
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */
import store  from './vuex/store'
import { nextStep, prevStep, setStep, sending, share, storeIn, getStored, getShared } from './vuex/actions'
import { isSending, shared, stored } from './vuex/getters'

import Vueisotope from 'vueisotope'

import Wizard from './components/accordion_wizard/Wizard'
import WizardSliderStep from './components/accordion_wizard/WizardSliderStep'
import WizardFinalStep from './components/accordion_wizard/WizardFinalStep'
import ServicesForm from './components/services/ServicesForm'
import ServicesList from './components/services/ServicesList'
import StripeForm from './components/StripeForm'
import ClientBoard from './components/Board/ClientBoard'
import TimeslotsList from './components/TimeslotsList'
import Questions from './components/Questions'
import ButtonContainerCompare from './components/accordion_wizard/ButtonContainerCompare'
import ShowServices from './components/ShowServices'
import ExtraService from './components/ExtraService'

import { daterangepicker } from 'bootstrap-daterangepicker'
import { slider } from 'bootstrap-slider'

import { cleanJson } from './utils/utils'

Vue.use(Vueisotope);

import { events } from './mixins/events'
import { methods } from './mixins/methods'
import { computed } from './mixins/computed'
import { data } from './mixins/data'

// import ButtonContainer from './components/accordion_wizard/ButtonContainer'
// import Vueisotope from './vendor/vue_isotope'
// import WizardStep from './components/accordion_wizard/WizardStep'
// import Timeslots from './components/Timeslots'



Vue.transition('bounce', {
    // enterClass: 'bounceInLeft',
    // leaveClass: 'bounceOutRight'
    enterClass: 'slideInDown',
    leaveClass: 'slideOutUp'
});

const app = new Vue({
    el: 'body',

    store,

    vuex: {
        getters: {
            currentStep: state => state.currentStep,
            isSending: isSending,
            shared,
            stored
        },
        actions: {
            nextStep,
            prevStep,
            setStep,
            sending,
            share,
            storeIn,
            getStored,
            getShared
        }
    },

    mixins:[events, methods, computed, data],

    components: {
        Wizard,
        WizardSliderStep,
        WizardFinalStep,
        ButtonContainerCompare,
        ServicesForm,
        ServicesList,
        ClientBoard,
        StripeForm,
        TimeslotsList,
        Questions,
        ShowServices,
        ExtraService
        // WizardStep,
        // ButtonContainer,
        // Timeslots,
    },

    data () {
        return {
            timeFormat: 'DD/MM/YYYY',

            /**
             * pgk NEW; must test
             */
            /**
             * TODO: Да пробвам да мапна това направо към store.shared
             */
            mapData: {},


            /**
             * Validation rules
             */
            validation: {
                rules: {
                    service_date: { service_date: true, required: true },
                    timeslots: { timeslots: true, required: true },
                    firstname: { firstname: true, required: true },
                    lastname: { lastname: true, required: true },
                    email: { email: true, required: true },
                    survey: {survey: true, required: true },
                    postcode: {postcode: true, required: true, signspace: true, max: 9},
                    testGroup: {required: true}
                }
            },

            services_options: {
                beforeSubmit: (theForm) => {

                     return new Promise(function (resolve, reject) {
                        if ( _.some( theForm.formData(), (el, name) => _.startsWith(name, 'services_') && el != 0 ) )
                            resolve();
                        else {
                            alert( 'You must select at least one service' );
                            reject('rejected')
                        }
                    })
                }
            },

            client_form_options: {
                beforeSubmit: (theForm) => {

                    /**
                     * Запазваме данните за да отидав в php
                     */
                    this.share(_.merge(this.getShared('client'), theForm.formData()));

                    $('.btn.subscribe').trigger('click');

                    return new Promise(function (resolve, reject) {
                        reject('this was rejected');
                    })
                }
            },

            valid: {
                step1: null,
                step2: null,
                step3: null,
                step4: null,
                step5: null,
                step6: null,
                step8: null,
                step7: null,

                agree: false,
                agree2: false
            },

            q: [
                {
                    id: 1,
                    question: 'Is the property empty of furniture?',
                    answer: {
                        '1': 'yes',
                        '2': 'no'
                    }
                },
                {
                    id: 2,
                    question: 'Is the Carpet natural or synthetic?',
                    answer: {
                        '1': 'yes',
                        '2': 'no',
                        '3': 'dont know'
                    }
                },
                {
                    id: 3,
                    question: 'Are there stains?',
                    answer: ''
                }
            ]
        }
    },

    computed: {

        // mapData () { return _.has(this.shared, 'restoreData') ? this.shared['restoreData'] : {} },

        /**
         * Попълваме календара на утрешна дата
         */
        tomorrow () {
            return this.today.add(1, 'd');
        },

        canPay () {
            return this.valid.agree && this.valid.agree2;
        },

        /**
         * Ако има избрана фирма, връщаме свободните и таймслотове
         * Използва се за Timeslots компонента
         *
         * @returns {Array}
         * @private
         */
        // _timeslots () {
        //     return _.has(this.shared, 'company') ? this.shared.company.timeslots : []
        // },

        /**
         * За попълване на календара за избор на дата за услугата
         * Може да се подаде от сървъра или днешна дата
         *
         * P.S. - Понеже в тази стъпка се използва timerange-select елемент за избор на таймслот
         * а този елемент слуша календара за промяна на датата, за да изпрати необходимите слотове
         * променяме логиката като когато не е избрана дата предварително (не сме на стъпка restore)
         * не подаваме предварително днешната дата, за да форснем потребителя да избере дата и да
         * предизвикаме event който да задейства timerange-select-a
         *
         * Попълва <vf-date name='service_date'> на step2
         * @private
         */
        _chosenDate() {

            // return moment("2017-02-26T00:00:00+02:00");

            // console.log( _.has(this.mapData, 'service_date'), '_chosenDate' );
            // console.log( _.isEmpty(this.getShared('service_date')), '_chosenDate' );

            return _.has(this.mapData, 'service_date') ? moment(this.mapData['service_date']) : '';
            // return moment(this.mapData.service_date);
            // return _.isEmpty(this.mapData['service_date']) ? '' : moment(this.mapData['service_date']);
            // return _.isEmpty(this.getShared('service_date')) ? '' : moment(this.getShared('service_date'))
        },

        _chosenServiceCategory () {
            return _.has(this.shared, 'ssrv') ? this.shared.ssrv[0].text : ''
        },

        /**
         * това го използвам за да принтна датата накрая на поръчката, но реално е същото като горното
         * TODO:
         */
        _serviceDate () {
            // return _.isEmpty(this.getShared('service_date')) ? '' : this.getShared('service_date')
            return _.isEmpty(this.getShared('timeslots')) ? '' : this.getShared('timeslots')
        },

        /**
         * Това са избраните вече услуги от клиента.
         * Ако сме във фаза на върнати от сървъра данни за продължаване на регистрацията,
         * намираме избраните сервиси, иначе празен обект
         *
         * Изпозва се за <services-form> на step3
         *
         * Избраните сървиси трябва да са във формат
         *
         * 'service_id': {
                'subcategory_name': [
                    {
                        id: 'service_id',
                        price: 'selected_service number'
                    },
                    ....
                ]
                ....
            }
            ....
         *
         * 'price' параметъра е подвеждащ, това реално е избраната от потребителя бройка
         * но в момента се използва това име и се прави филтрация по него във <service> компонента
         *
         * TODO:  Евентуално да се промени името на параметъра
         *
         // * @returns {{}}
         * @private
         */
        _selectedServices () {
            /**
             *
             */
            // return _.pickBy( this.shared, function (ser, key) {
            return _.pickBy( this.mapData, function (ser, key) {
                return _.startsWith(key, 'services_')
            });
        },

        /**
         * Форматираме услугите (след като потребителя избере основна категория)
         * за по-лесен достъп
         *
         * @returns {*}
         * @private
         */
        _formattedSelectedServices () {

            return _.has(this.shared, 'ssrv') ? _.flatten(_.map(_.head(this.shared.ssrv).additional, 'data')) : [];
        },

        /**
         * Фирмите, които отговарят на подадените от потребителя критерии
         * Може да се вземат от vuex store, ако са в резултат на реално търсене
         * в момента, или от подадени от сървъра данни, в случай на продължаване на
         * прекъсната поръчка
         *
         * Попълва <client-board> компонента на step6
         *
         * @returns {[]}
         * @private
         */
        _companies () {
            return this.stored.companies;

            // return _.isEmpty(this.mapData)
            //     ? this.stored.companies
            //     : _.has(this.mapData, 'company')
            //         ? _.castArray(this.mapData.company)
            //         : [];
        },

        /**
         * За попълване на избраните допълнителни услуги от клиент
         *
         * Попълва <client-board> на step4
         *
         * @returns {Array}
         * @private
         */
        _selectedSubServices () {
            return _.isEmpty(this.mapData) ? [] : _.map(this.mapData.additional, 'name')
        },

        getClient () {
            return _.isEmpty(this.getShared('client')) ? {} : this.getShared('client');
        },

        _chosenCompany () {
            return _.isEmpty(this.getShared('company')) ? {} : this.getShared('company');
        },

        /***
         * Данни за фирмата + extra things if needed
         * @returns {{}}
         * @private
         */
        _firmData () {
            return _.isEmpty(this.getStored('firmData')) ? {} : _.merge({}, this.getStored('firmData'), {'chosenCategory': this._chosenServiceCategory});
        },

        _extraServices () {
            return _.isEmpty(this.shared.ssrv) ? [] : this.shared.ssrv[0].extra_services;
        }
    },

    events: {
        'stripe-form.sending': function () {
            this.$emit('sending')
        },

        'stripe-form.sent': function (response) {
            this.$emit('sent.ok', response)
        },

        'stripe-form.error': function (response) {
            this.$emit('sent.error', response)
        },

        /**
         * PGK: При новата логика не е необходимо
         * При промяна на избраната дата (service_date) изчистваме следващите
         * стъпки и ги заключваме, като принуждаваме потребителя да мине
         * отново през следващите стъпки, за да сме сигурни, че ще съберем
         * цялата необходима информация
         */
        // 'vue-formular.change::service_date' () {
        //     this.setStep(2);
        //
        //     this.changeValidation(this.valid, this.currentStep);
        // },

        // 'vue-formular.sending' () {
        //
        // },

        'hasRestoreData' (restoreData) {

            if (_.has(restoreData, 'services')) {
                /**
                 * автоматично минаваме на стъпка 2
                 */
                this.$emit('vue-formular.change::services');
            }
        },

        /**
         * Иска се на първата стъпка да се премахне бутона NEXT
         * и да се преминава автоматично на следваща стъпка
         *
         * @param field_data
         */
        'vue-formular.change::services' (field_data) {
            if( _.has(this.$refs, `step${this.currentStep}`) ) {
                // this.$nextTick(() => this.$refs[`step${this.currentStep}`].submit(new Event('temp')))
                this.$nextTick(() => this.$refs[`step${this.currentStep}`].submit(this.customEvent()))
            }
        },

        /**
         * Евент при изпращане на форма от тип client.
         * В случая слушаме за
         *      изпращането на формата с избраната дата
         *      изпращане на формата с основните услуги
         *
         * @param data
         */
        'vue-formular.client' (data) {

            this.$emit('sent.ok', {url: `step${this.currentStep}`, status: 200, data: {share: data}})
        },

        'clientBoard::sending' () {
            this.$emit('sending')
        },
        'clientBoard::sent' (data) {
            this.$emit('sent.ok', {url: `step${this.currentStep}`, status: 200, data: {share:data}})
        },
        'clientBoard::error' (data) {
            this.$emit('sent.error', {url: `step${this.currentStep}`, status: 404, data: {share:data}})
        },

        /**
         * При клик в/у стъпка, която е по-напред, трябва да ресетнем следващите стъпки,
         * за да минем отново по веригата
         *
         * @param index
         */
        'wizard::toggle' ( {index} ) {
            this.setStep(index);
            this.changeValidation(this.valid, `${index}`)
        },
    },

    created () {
        /**
         * Getting categories
         */
        this.$http.get('/company/signup/step5/get-service-categories')
            .then(
                (response) => {

                    this.$emit('sent.ok', response)
                },
                (error) => {
                    console.log( error, 'ERROR getting categories' );
                }
        );
    },

    ready () {

        window.onpopstate = function (event) {

            if ( _.inRange( this.currentStep, 1, 8) ) {
                this.prevStep()
            }
            else {
                location.reload();
                return false;
            }
        }.bind(this);

        /**
         * Предаваме обратно данните
         */
        if( !_.isNil(window.restoreData) && !_.isEmpty(restoreData)) {

            this.$emit('hasRestoreData', restoreData);
            // this.share(restoreData);

            this.mapData = _.assign({}, this.mapData, restoreData);
            this.share({'uuid': _.get(restoreData, 'uuid')});
            this.share({'services': _.get(restoreData, 'services')});
        }
    }
});
