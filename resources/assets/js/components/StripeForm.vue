<template>
    <div class="panel panel-default credit-card-box">
        <div class="panel-heading display-table">
            <div class="row display-tr">
                <h3 class="panel-title display-td">Payment Details</h3>
                <div class="display-td">
                    <img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png">
                </div>
            </div>
        </div>
        <div class="panel-body">

            <div class="row">
                <div class="col-xs-12">
                    <div v-if="!general_error.valid" class="alert alert-danger" role="alert">{{general_error.text}}</div>
                </div>
            </div>



            <form role="form" id="payment-form" method="POST" :submit.stop.prevent >
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group has-feedback {{classes.number}}">
                            <label class="control-label">Card Number *</label>
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size: 22px"><i class="fa fa-credit-{{card}}"></i></span>
                                <input
                                        type="text"
                                        class="form-control"
                                        data-stripe="number"
                                        placeholder="Valid Card Number"
                                        @input="validateCard()"
                                        @change="getCardType()"
                                        required autofocus
                                        v-model="number.val"
                                />

                                <span v-if="this.number.valid !== null" aria-hidden="true" class="glyphicon icon-{{classes.number}} form-control-feedback"></span>
                            </div>
                            <span v-if="this.number.valid === false" class="help-block">Your card number is incorrect.</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-7 col-md-7">
                        <div class="form-group {{classes.date}}">
                            <label class="control-label"><span class="hidden-xs">Expiration</span><span class="visible-xs-inline">EXP</span>
                                Date</label>
                            <!--<div class="">-->
                                <div class="row">
                                    <div class="col-xs-6">
                                        <input
                                                type="text"
                                                class="form-control"
                                                data-stripe="exp_month"
                                                placeholder="MM"
                                                v-model="exp_month.val"
                                                @change="validateExpiry()"
                                                required
                                        />
                                    </div>
                                    <div class="col-xs-6">
                                        <input
                                                type="text"
                                                class="form-control"
                                                data-stripe="exp_month"
                                                placeholder="YY"
                                                @change="validateExpiry()"
                                                v-model="exp_year.val"
                                                required
                                        />
                                    </div>
                                </div>


                                <span v-if="this.exp_month.valid === false" class="help-block">Please, enter valid Month / valid Year</span>
                            <!--</div>-->
                        </div>

                    </div>
                    <div class="col-xs-5 col-md-5 pull-right">
                        <div class="form-group has-feedback {{classes.cvc}}">
                            <label class="control-label">CV CODE</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    v-model="cvc.val"
                                    @input="validateCVC()"
                                    placeholder="CVC"
                                    required
                            />
                            <span v-if="this.cvc.valid !== null" aria-hidden="true" class="glyphicon icon-{{classes.cvc}} form-control-feedback"></span>
                            <span v-if="this.cvc.valid === false" class="help-block">Please, enter valid CVC code</span>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="mustConfirm">
                    <div class="col-xs-12">
                        <input  type="checkbox" v-model="confirmed"/>
                        <slot name="conditions">
                            Confirm this
                        </slot>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <button
                                v-if="!hideBtnOnSubmit"
                                @click.prevent="processForm()"
                                :disabled="btnDisabled"
                                class="subscribe btn btn-success btn-lg btn-block"
                                type="button"
                        >{{btnText}}</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</template>

<script>

    import { debounce } from 'lodash'

    export default {

        props: {
            /**
             * Additional data to send with the form
             */
            additionalPayload: {
                type: [Object]
            },
            stripeKey: {
                required: true
            },
            url: {
                required: true,
                type: String
            },
            hideBtnOnSubmit: {
                type: Boolean,
                default: false
            },
            btnText: {
                type: String,
                default: 'Start Subscription'
            },
            mustConfirm: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                number: {
                    val: "",
                    valid: null
                },
                exp_month: {
                    val: "",
                    valid: null
                },
                exp_year: {
                    val: "",
                    valid: null
                },
                cvc: {
                    val: "",
                    valid: null
                },
                general_error: {
                    valid: true,
                    text: ''
                },
                card: 'unknown',
                confirmed: false,
            }
        },

        computed: {
            formData: function () {
                return {
                    number: this.number.val,
                    exp_month: this.exp_month.val,
                    exp_year: this.exp_year.val,
                    cvc: this.cvc.val,
//                    address_zip: this.address_zip.val
                };
            },
            classes: function () {
                return {
                    'number': this.number.valid === null ? "" : this.number.valid ? 'has-success' : 'has-error',
                    'date': this.exp_month.valid === null ? "" : this.exp_month.valid ? 'has-success' : 'has-error',
                    'cvc': this.cvc.valid === null ? "" : this.cvc.valid ? 'has-success' : 'has-error'
                }
            },
            btnDisabled () {
                return this.mustConfirm ? !this.confirmed : false
            }
        },

        methods: {
            /**
             * Create Stripe token
             */
            processForm () {
                Stripe.setPublishableKey(this.stripeKey);
                Stripe.card.createToken(this.formData, this.stripeResponseHandler.bind(this));
            },

            validateCard: debounce( function () {
                this.number.valid = Stripe.card.validateCardNumber(this.number.val)
            }, 500),

            validateExpiry: debounce( function () {
                if ( this.exp_month.val !== "" && this.exp_year.val !== "" ) {
                    return this.exp_month.valid = Stripe.card.validateExpiry(this.exp_month.val, this.exp_year.val)
                }
                this.exp_month.valid = null;
            }, 500),

            validateCVC: debounce(function () {
                this.cvc.valid = Stripe.card.validateCVC(this.cvc.val)
            }, 500),

            getCardType: debounce(function () {
                this.card = Stripe.card.cardType(this.number.val).toLowerCase()
            }, 500),

            stripeErrorHandler (error) {

                if( error.type && error.type === "card_error" ) {

                    if (_.has(this, error.param)) {
                        this[error.param].valid = false;
                    }
                }
                else {
                    this.general_error.text = (error.message && error.message != "") ? error.message.toString()  : 'General error !!!';
                    this.general_error.valid = false;
                }

            },

            stripeResponseHandler (status, response) {

                this.$dispatch('stripe-form.sending')
                if ( response.error ) {
                    this.stripeErrorHandler(response.error);
                    this.$dispatch('stripe-form.error', {url: this.url})

                    /**
                     * code : "incorrect_number"
                     message : "Your card number is incorrect."
                     param : "number"
                     type : "card_error"
                     */

                } else {

                    this.$http.post( this.url, [ JSON.stringify(_.assign(response, this.additionalPayload)) ])
                        .then(
                            function (response) {
                                //OK
                                this.general_error.text = '';
                                this.general_error.valid = true;

                                this.$dispatch("stripe-form.sent", response)
                            },

                            function (error) {
                                //ERROR
                                this.stripeErrorHandler( error.data );
                                this.$dispatch('stripe-form.error', error)
                            }
                        );
                }

            }
        }
    }
</script>

<style scoped>
    .icon-has-error:before {
        content: "\e014";
    }
    .icon-has-success:before {
        content: "\e013";
    }
    .fa-credit-unknown:before,
    .fa-credit-undefined:before {
        content: "\f09d"
    }
    .fa-credit-visa:before {
        content: "\f1f0"
    }
    .fa-credit-american:before {
        content: "\f1f3"
    }
    .fa-credit-mastercard:before {
        content: "\f1f1"
    }
    .fa-credit-discover:before {
        content: "\f1f2"
    }
    .fa-credit-diners:before {
        content: "\f24c"
    }
    .fa-credit-jcb:before {
        content: "\f24b"
    }
    .credit-card-box .panel-title {
        display: inline;
        font-weight: bold;
    }
    .credit-card-box .form-control.error {
        border-color: red;
        outline: 0;
        box-shadow: inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px rgba(255,0,0,0.6);
    }
    .credit-card-box label.error {
        font-weight: bold;
        color: red;
        padding: 2px 8px;
        margin-top: 2px;
    }
    .credit-card-box .payment-errors {
        font-weight: bold;
        color: red;
        padding: 2px 8px;
        margin-top: 2px;
    }
    .credit-card-box label {
        display: block;
    }
    /* The old "center div vertically" hack */
    .credit-card-box .display-table {
        display: table;
    }
    .credit-card-box .display-tr {
        display: table-row;
    }
    .credit-card-box .display-td {
        display: table-cell;
        vertical-align: middle;
        width: 50%;
    }
    /* Just looks nicer */
    .credit-card-box .panel-heading img {
        min-width: 180px;
    }
</style>