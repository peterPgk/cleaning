
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import { daterangepicker } from 'bootstrap-daterangepicker'
import { select2 } from 'select2'

import ScaleLoader from 'vue-spinner/src/ScaleLoader.vue'
import VisibleStepServicesBoard from './components/services_steps_visible/VisibleStepServicesBoard'
import Notification from './components/Notification'
import Chart from 'vue-bulma-chartjs'
import Tooltip from './components/Tooltip'
import ButtonContainer from './components/accordion_wizard/ButtonContainer'
import PricingTable from './components/PricingTable'

Vue.component('loader', ScaleLoader);
Vue.component('VisibleStepServicesBoard', VisibleStepServicesBoard);
Vue.component('Chart', Chart);
Vue.component('Notification', Notification);
Vue.component('Tooltip', Tooltip);
Vue.component('ButtonContainer', ButtonContainer);
Vue.component('PricingTable', PricingTable);

Vue.mixin({
    data () {
        return {
            /**
             * Индикатора за зареждане
             */
            loading: false,

            alertData: {
                type: 'success',
                show: false,
                msg: 'Message'
            }
        }
    },
    methods: {
        showMessage: function ( type, msg ) {
            this.alertData.type = type;
            this.alertData.msg = msg;
            this.alertData.show = true;
        },

        /**
         * Изчистваме мапнатите полета и формата
         * TODO: Да видя метода с който се изчистват полетата, маркирани с грешка/ок (при валидациата)
         */
        resetForm (ref) {
            this.$refs[ref].childrenOf(`${ref}`).forEach(function(field) {
                field.reset();
            })
        },

        cleanJson: function (resource) {
            if (typeof resource === 'string') {
                try {
                    return JSON.parse(resource)
                }
                catch (e) {
                    return resource
                }
            }

            return resource
        },

    },

    events: {

        /**
         * Eвентите при изпращането на форма.
         * TODO: Да видя как да аправя vue-formular.sent динамично, да се мерджва с такова от конкретната инстанция
         * Vue documentation -> Mixins -> Custom Option Merge Strategies
         */

        'vue-formular.sending': function () {
            this.loading = true;
        },

        'vue-formular.invalid.server': function (response) {
            this.loading = false;
            this.showMessage('danger', 'Error');

            console.log( response, 'formular sent ERROR' );

        },
    }
});

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

require('admin-lte/dist/js/app.js');
window.events = require('./mixins/events');

/**
 * ТОДО
 * Да напрява накой методи глобални
 *
 * resetForm()
 * showMessage()
 */
