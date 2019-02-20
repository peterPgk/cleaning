
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
import { nextStep, setStep, sending, share, storeIn, getStored } from './vuex/actions'
import { isSending, shared, stored, getPath } from './vuex/getters'

import Wizard from './components/accordion_wizard/Wizard'
import WizardHiddenStep from './components/accordion_wizard/WizardHiddenStep'
import WizardFinalStep from './components/accordion_wizard/WizardFinalStep'
import ButtonContainer from './components/accordion_wizard/ButtonContainer'
import PricingTable from './components/PricingTable'
import StripeForm from './components/StripeForm'
import Tooltip from './components/Tooltip'

import { daterangepicker } from 'bootstrap-daterangepicker'
import { select2 } from 'select2'

import { cleanJson } from './utils/utils'
import { events } from './mixins/events'
import { methods } from './mixins/methods'
import { computed } from './mixins/computed'
import { data } from './mixins/data'

// var Velocity = require("velocity-animate")

const app = new Vue({
    el: 'body',

    mixins:[events, methods, computed, data],

    store,

    components: {
        Wizard,
        WizardHiddenStep,
        WizardFinalStep,
        ButtonContainer,
        PricingTable,
        StripeForm,
        Tooltip
    },

    vuex: {
        getters: {
            currentStep: state => state.currentStep,
            isSending: isSending,
            shared,
            stored,
            getPath
        },
        actions: {
            nextStep,
            setStep,
            sending,
            share,
            storeIn,
            getStored
        }
    },

    data() {
        return {

            /**
             * Понякога данните се чупят и идват като стринг вместо json
             * При event 'sent.ok' попълвам tempData с респонса и след това
             * с computedProperty се генерира formattedData
             */
            // tempData: '',

            // today: moment(),

            // memberPlans: ['silver', 'gold'],

            pricingData: {
                type: [Array, String, Object],
                default: []
            },

            liabilityOpts: [
                { value: false , text:'No' },
                { value: true, text:'Yes'},
            ],

            valid: {
                step1: null,
                step2: null,
                step3: null,
                step4: null,
                step5: null,
                // step6: null,
                // step7: null
            },

            mapData: {
                liability: true,
            },

            validation: {
                rules: {
                    name: { min: 4, name: true, required: true},
                    email: { email: true, required: true },
                    client_name: { min: 4, client_name: true, required: true, letters: true },
                    trading_name: { min: 4, trading_name: true },
                    company_number: { company_number: true, required: true, number: true },
                    vat: { vat: true },
                    website: { url: true, website: true, required: true },
                    // phone: { phone: true, required: true, number: true, maxLen: 14 },
                    phone: { phone: true, required: true, numspace: true, maxLen: 14 },
                    phone_2: { phone_2: true, number: true, maxLen: 12 },
                    address: { min: 4, address: true, required: true },
                    address_2: { min: 4, address_2: true, required: true },
                    address_3: { min: 4, address_3: true },
                    city: { min: 4, city: true, required: true, letters: true },
                    postcode: { postcode: true, required: true, letters: true },
                    logo: { logo: true },
                    services: { services: true, required: true },
                    members_of: { members_of: true },
                    date_established: { date_established: true, required: true, number: true, maxLen: 4 },
                    complaints: { complaints: true, required: true },
                    liability: { liability: true },
                    liability_amount: {
                        requiredAndShownIf: 'liability',
                        liability_amount: true
                    },
                    liability_expires: {
                        requiredAndShownIf: 'liability',
                        liability_expires: true
                    },
                    liability_certificate: {
                        liability_certificate: true
                    },
                }
            },

        }
    },

    computed: {

        _pricingData () {
            return cleanJson(this.pricingData);
        },

        /**
         * Динамично вадим данни за избрания план
         * Използва се само в последната стъпка да се покаже цената на плана
         *
         * @returns {{}}
         * @private
         */
        _selectedPlan () {

            return (_.has(_.head(this._pricingData), 'plans' )) ? _.find(_.head(this._pricingData).plans, {stripe_plan: `${this.shared.plan}`})  : {};
            // return _.find(_.head(this._pricingData).plans, {stripe_plan: `${this.shared.plan}`}) || {};

        },

        _currentPrice () {
            return _.has(this._selectedPlan, 'price') ? this._selectedPlan.price : 0;
        },

        getQuarantee () {
            // return _.isEmpty(this.getStored('cleaning_quarantee')) ? [] : _.castArray(this.getStored('cleaning_quarantee'));
            return _.isEmpty(this.getStored('firmData')) || !_.has(this.getStored('firmData'), 'cleaning_quarantee') ? [] : this.getStored('firmData').cleaning_quarantee;
        },

        getMembers () {
            return _.isEmpty(this.getStored('firmData')) || !_.has(this.getStored('firmData'), 'members_of') ? [] : this.getStored('firmData').members_of;
        },

        getAmount () {
            return _.isEmpty(this.getStored('firmData')) || !_.has(this.getStored('firmData'), 'liability_amount') ? [] : this.getStored('firmData').liability_amount;
        },

        getServiceCategories () {
            return _.isEmpty(this.getStored('firmData')) || !_.has(this.getStored('firmData'), 'service_categories') ? [] : this.getStored('firmData').service_categories;
        },

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

        'pricingTable::selected': function (response) {
            /**
             * Saving selected plan
             */
            this.share({plan: response});
        }

    },

    ready () {

        this.$emit('sending');

        window.onpopstate = function (event) {
            if ( this.currentStep > 1 ) {
                this.prevStep()
            }
        }.bind(this);

        /**
         * Getting plans
         */
        this.$http.get('/company/signup/get-plans')
            .then(
                ({data}) => {
                    this.pricingData = data;
                    setTimeout(() => this.$broadcast('register::getPriceTable'), 0);
                    this.sending(false);
                },
                (error) => console.log( error, 'error' )
            );
        /**
         * Getting categories
         */
        this.$http.get('/company/signup/step5/get-service-categories')
            .then((response) => {
                this.$emit('sent.ok', response)
            }
        );
    }

});
