require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

// Vue.component('vf-services', require('./components/form_fields/services')());
// Vue.partial('services', require('./components/form_fields/services.html'));

let VueTables = require('vue-tables');
Vue.use(VueTables.client, {
    compileTemplates: true,
    filterByColumn: true,
    texts: {
        filter: "Search:"
    },
    datepickerOptions: {
        showDropdowns: true
    },

});

import store  from './vuex/store'
import { nextStep, setStep, sending, share, storeIn, getStored, getShared } from './vuex/actions'
// import { isSending, shared, stored } from './vuex/getters'
import { isSending } from './vuex/getters'

// import WizardStep from './components/accordion_wizard/WizardStep'
// var Velocity = require("velocity-animate")

import Wizard from './components/accordion_wizard/Wizard'
import WizardSliderStep from './components/accordion_wizard/WizardSliderStep'
import WizardFinalStep from './components/accordion_wizard/WizardFinalStep'
import ButtonContainer from './components/accordion_wizard/ButtonContainer'
import ServicesForm from './components/services/ServicesForm'
import StepServicesBoard from './components/services_steps/StepServicesBoard'

// import Postcodes from './components/Postcodes'
import WeekSlots from './components/WeekSlots'
import DaysPicker from './components/DaysPicker'

import { events } from './mixins/events'
import { methods } from './mixins/methods'
import { computed } from './mixins/computed'
import { data } from './mixins/data'

import { daterangepicker } from 'bootstrap-daterangepicker'
import { select2 } from 'select2'
import { cleanJson } from './utils/utils'

const app = new Vue({
    el: 'body',

    store,

    mixins: [events, methods, computed, data],

    components: {
        Wizard,
        // WizardStep,
        WizardSliderStep,
        WizardFinalStep,
        ServicesForm,
        StepServicesBoard,
        // Postcodes,
        WeekSlots,
        DaysPicker,
        ButtonContainer
    },

    vuex: {
        getters: {
            currentStep: state => state.currentStep,
            isSending: isSending,
            // shared,
            // stored,
        },
        actions: {
            nextStep,
            setStep,
            sending,
            share,
            storeIn,
            getStored,
            getShared,
        }
    },

    data() {
        return {
            formCustomRules: {},

            /**
             * TODO: Да го изтрия, вече не го използвам ?
             */
            firmData: {},

            // mapData: {
            //     /**
            //      * Избраните услуги - ако услугата не  е
            //      * избрана при регистрация, няма да я има в този обект
            //      * Проверявам дали я има
            //      */
            //     // services: {
            //     // }
            // },

            valid: {
                step1: null,
                step2: null,
                step3: null,
                step4: null,
                step5: null,
            },

            validation: {
                rules: {
                    regions: { regions: true, required: true },
                    weekdays: { weekdays: true, required: true },
                    jobs_number: { jobs_number: true, required: true },
                    holidays: { holidays: true, required: true },
                }
            },
        }
    },

    computed: {

        mapData () {
            return  _.isEmpty(this.getStored('mapData')) ? {} : this.getStored('mapData');
        },

        /**
         * Използвам комютед пропърти, защото ако задам директно в компонента mapData.regions,
         * и не сме на стъпката за продължаване на регистрацията, това се приема като подадена
         * стойност и предизвиква появяване на съобщение за грешка, въпреки, че сме на първото
         * въвеждане на данни и реално не сме въвели все още такива
         *
         * @returns {*}
         * @private
         */
        _mapRegions () {
            return _.isEmpty(this.mapData.regions) ? '' : this.mapData.regions;
        },

        _mapWeekdays () {
            return _.isEmpty(this.mapData.weekdays) ? '' : this.mapData.weekdays;
        },

        _mapHolidays () {
            return _.isEmpty(this.mapData.holidays) ? '' : this.mapData.holidays;
        },

        /**
         * Всички функции е за да форматирам върнатите от vuex стора данни.
         * Там проверявам за ключ и ако няма се връща празен обект, а в тези компоненти
         * се очаква масив.
         *
         * TODO: Downside на това е, че когато го кастнем към масив се връща масив с един елемент,
         * който се изобразява, преди да се върнат основните данни (в postcodes се появява един празен
         * чекбокс)
         * @returns {Array}
         */
        getDates () {
            return _.isEmpty(this.getStored('dates')) ? [] : _.castArray(this.getStored('dates'));
        },

        getServices () {
            return  _.castArray(this.getStored('services'))
        },

        getPostcodes () {
            return  _.isEmpty(this.getStored('postcodes')) ? [] : _.castArray(this.getStored('postcodes'))
        },

        getMaxBooking () {
            return _.isEmpty(this.getStored('firmData')) || !_.has(this.getStored('firmData'), 'max_booking') ? 0 : this.getStored('firmData').max_booking;
        },

        getPlanName () {
            return _.isEmpty(this.getStored('firmData')) || !_.has(this.getStored('firmData'), 'plan_name') ? '' : this.getStored('firmData').plan_name;
        },

        getHolidays () {
            return  _.isEmpty(this.getStored('holidays')) ? [] : _.castArray(this.getStored('holidays'))
        },

        /**
         * TODO: За сега, после ще се взема по някакъв начин от
         * категориите
         */
        getInfo () {
            return _.isEmpty(this.getStored('firmData')) || !_.has(this.getStored('firmData'), 'info_text') ? '' : this.getStored('firmData').info_text;
        },

        /**
         * Генерираме дните от седмицата
         *
         * @returns {Array}
         * @private
         */
        _weekdays () {
            /**
             * Имаме ново изискване дните да започват от понеделник, не от неделя
             * Има такъв формат ISO-8601, но за да не се налага и промяна на сървъра
             * правя тази врътка
             */
            let d = [];
            for(let i=1; i<=6; i++) {
                let day = moment(i, 'd');
                d.push({
                    id: `${i}`,
                    text: day.format('dddd'),
                });
            }

            d.push({id:'0', text: moment(0, 'd').format('dddd')});

            return d;
        },

        /**
         * Генерираме брой работи за ден
         * @private
         */
        _daysNum () {
            let n = [];

            for(let i = 1, max = this.getMaxBooking === 0 ? 10 : this.getMaxBooking; i <= max; i++) {
                n.push({
                    id: `${i}`,
                    text: `${i} jobs`,
                });
            }

            return n;
        }
    },

    methods: {},

    events: {},

    watch: {
        _mapWeekdays () {
            /**
             * bug: Fucking weekdays select
             * Kris working proposal is to preselect select2 component
             */
            $(this.$refs.weekdays.$el).find('select').select2()

        }
    },

    ready () {

        window.onpopstate = function (event) {

            if (_.has(event.state, 'inner')) {
                /**
                 * намираме се в компонента за въвеждане на
                 * цени на услуги
                 */
                this.$broadcast('inner-prev-step')
            }
            else {
                this.prevStep()
            }

        }.bind(this);

    }

});
