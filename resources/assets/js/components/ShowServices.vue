<template>
    <div class="row">
        <div class="col-xs-12">
            <h4>Property Details</h4>
            <h5 class="bb-1">{{ additionalData.chosenCategory }}</h5>
            <p v-for="service in services">{{ service.count }} {{ service.name }}</p>
        </div>

        <div class="col-xs-12">
            <h4>{{ _serviceDate }}</h4>
            <p>Arriving between {{ _time }}</p>
        </div>

        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-6">Total: </div>
                <div class="col-xs-6">{{ _revenueSum }}</div>
            </div>

            <div class="row">
                <div class="col-xs-6">Pay now: </div>
                <div class="col-xs-6">{{ _taxSum }}</div>
            </div>

            <div class="row">
                <div class="col-xs-6">TOTAL: </div>
                <div class="col-xs-6">{{ _servicesSum }}</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            company: {
                type: Object,
                required: true
            },

            /**
             * Ще използвам таймслот за да извлека датата
             */
            date: {
                type: String,
                'default': ''
            },

            additionalData: {
                type: Object,
                'default': () => {}
            },

            currencySymbol: {
                type: String,
                'default': '£'
            }
        },

        computed: {
            /**
             *
             */
            services () {
                return _.has(this.company, 'services') ? this.company.services : {}
            },

            /**
             * All services sum price
             */
            servicesSum () {
//                return +_.sumBy(_.toArray(this.services), 'sum_price');
                return +_.sumBy(_.toArray(this.services), function (service) {
                    return +service.sum_price;
                });
            },

            /**
             * String representation of the sum
             * @returns {string}
             * @private
             */
            _servicesSum () {
                return `${this.currencySymbol} ${this.servicesSum.toFixed(2)}`
            },

            /**
             * All services tax sum price
             */
            taxSum () {
                return this.servicesSum * this._tax / 100;
            },

            /**
             * String representation of the tax sum
             * @returns {string}
             * @private
             */
            _taxSum () {
                return `${this.currencySymbol} ${this.taxSum.toFixed(2)}`;
            },

            /**
             * String representation of the total sum
             * @returns {string}
             * @private
             */
            _revenueSum () {
                return `${this.currencySymbol} ${(this.servicesSum - this.taxSum).toFixed(2)}`;
            },

            /**
             *
             * @returns {Array}
             * @private
             */
            _slotsArray () {
                return _.split(this.date, '|')
            },

            /**
             * Date of the service
             * @private
             */
            _serviceDate () {
                return moment(_.head(this._slotsArray)).format('DD MMMM YYYY');
            },

            /**
             * Prints timeslots
             * @returns {Array}
             * @private
             */
            _time () {
                return _.map(this._slotsArray, function (slot) {
                    return moment(slot).format('HH:00')
                }).join(' - ');
            },

            /**
             * Extract tax number - how much we must pay to the site owner
             * @returns {number}
             * @private
             */
            _tax () {
                return _.has(this.additionalData, 'booking_fee') ? +this.additionalData['booking_fee']['value'] : 1;
            }
        },

        data() {
            return {}
        }
    }
</script>

<style scoped>
</style>