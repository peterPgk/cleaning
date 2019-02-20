<template>
    <div class="parent-table">

        <div class="row">
            <div class="col-xs-12">
                <div class="row buttons">
                    <div class="hidden-xs hidden-sm col-md-5">&nbsp;</div>
                    <div v-for="plan in plans" class="col-xs-4 col-md-2 {{ plan.stripe_plan == selected ? 'selected' : '' }}">
                        <div class="plan-inner">
                            <button @click.prevent="select(plan.stripe_plan)" class="btn" href="#"><span>{{plan.name}}</span></button>
                        </div>
                    </div>
                </div>

                <div class="plans-body">

                    <div class="row">
                        <div class="col-xs-12 col-md-5 item-name">
                            Monthly price after free month ends on {{ payDate }}
                        </div>

                        <div v-for="plan in plans" class="col-xs-4 col-md-2 text-center">
                            <span class="{{ plan.stripe_plan == selected ? 'element-selected' : '' }}">£{{ plan.price }}</span>
                        </div>
                    </div>

                    <div v-for="item in _priceData" class="row">
                        <div class="col-xs-12 col-md-5 item-name">
                            {{ item.name }}

                            <tooltip effect="scale" :content="item.info" placement="top" trigger="hover" v-if="item.info !== ''">
                                <span class="tooltip-handler fa fa-question-circle" aria-hidden="true"></span>
                            </tooltip>
                        </div>

                        <div v-for="plan in item.plans" class="col-xs-4 col-md-2 text-center">
                            <price-value :selected="plan.stripe_plan == selected" :value-data="plan.pivot"></price-value>
                        </div>
                    </div>
                </div>

                <div class="additional-info">
                    <p>Don't worry, you can upgrade, downgrade or cancel your plan at any time.</p>
                </div>

            </div>
        </div>


    </div>
</template>

<script>
    import Tooltip from './../components/Tooltip'

    export default {

        components: {
            Tooltip,

            'priceValue': {
                template: `<span class="{{ cls }}">{{{ val }}}</span>`,
                props: {
                    valueData: {
                        type: [Object],
                        default: () => {{}}
                    },

                    selected: {
                        type: Boolean,
                        default: false
                    }
                },

                computed: {
                    val () {
                        return this.valueData.text != ""
                            ? `<span>${this.valueData.text}</span>`
                            : this.valueData.value == 1
                                ? `<span class="fa fa-check"><span>`
                                : `<span class="fa fa-times"><span>`
                    },

                    cls () {
                        return this.selected ? 'element-selected' : ''
                    }
                }
            }
        },

        props: {
            priceData: {
                type: [Array, Object, String],
//                coerce (data) {
//                    return (typeof data == 'string') ? JSON.parse(data) : data
//                }
            },

            selected: {
                'default': null
            }
        },

        data () {
            return {
                /**
                 * Общия брой планове
                 */
                items: 0
            }

        },

        computed: {
            _priceData () {
                return (typeof this.priceData == 'string') ? JSON.parse(this.priceData) : this.priceData
            },

            /**
             * In how many columns we must put plans
             * TODO: За сега не се занимавам да го изчислявам, приемам, че ще са три или 4
             *
             * @returns {string}
             */
            computedClass () {
                return "col-md-" + 12 / this.items
            },

            plans () {
                let t = _.head(this._priceData);
                return _.has(t, 'plans') ? t.plans : [];
            },

            defaultPlan () {

                let d = _.find(this.plans, 'selected');

                if ( ! d )
                    return _.find(this.plans, 'default') || null;

                return d;
            },

            defaultPlanId () {
                return ! _.isNil(this.defaultPlan) ? this.defaultPlan.stripe_plan : null;
            },

            payDate () {
                return moment().add(1, 'month').format('DD-MM-YYYY')
            }

        },

        methods: {
            select: function (planId) {
                this.selected = planId;
                this.$dispatch('pricingTable::selected', planId)
            },
        },

        events: {
            'register::getPriceTable' () {
//                this.items = this.priceData.length
//
//                //Try to find if some selected plan came from the server
//                let selected = _.find(this.plans, {'selected': true})
//
//                if (!selected) {
//                    selected = _.find(this.plans, 'default')
//                }
//
//                if (selected)
//                    this.selected = selected.stripe_id;
            }
        },

        watch: {
            /**
             * Определяме предварително селектиран план, ако
             * компонента се използва за показване на вече избран от
             * потребителя план или плана по подразбиране
             * Използва се oldValue, защото се определя само веднъж в началото
             *
             * @param oldValue
             * @param newValue
             */
            defaultPlanId: function (oldValue, newValue) {
//                this.selected = oldValue;
                this.select(oldValue);
            }
        }

    }
</script>

<style lang="scss" scoped>
    .parent-table {
        font-size: 16px;
        line-height: 28px;
        margin-top: 25px;
    }

    .buttons {
        margin-bottom: 20px;
    }

    .plan-inner {
        padding: 3px;
        border: 1px solid transparent;
        border-radius: 20px;

        button {
            width: 100%;
            height: 0;
            padding-bottom: 100%;
            position: relative;
            border: none;
            -webkit-border-radius: 0;
            -moz-border-radius: 0;
            border-radius: 20px;
            background-color: #ee0b00;
            opacity: .6;
            color: #ffffff;
            outline: none;

            span {
                position: absolute;
                left: 0;
                top: 50%;
                width: 100%;
                height: 1em;
                margin-top: -.5em;
                text-align: center;
            }
        }

    }

    .selected {
        .plan-inner {
            border-color: #ee0b00;

            button {
                opacity: 1;
            }
        }
    }

    .plans-body {
        .row {
            padding: 6px 0;
            border-bottom: 1px solid #eee;

            &:last-child {
                 border-bottom: 0;
             }
        }

        .tooltip-handler {
            cursor: pointer;
        }
    }

    .element-selected {
        color: #e50914;
    }

    .additional-info {
        font-size: 10px;
        line-height: 12px;
        margin-top: 20px;

        p {
            margin: 0;
        }

    }

    @media (max-width: 991px) {

        .plans-body {

            .row {
                border-bottom: 0;

                [class^="col-"]:not(.item-name) {
                    border-right: 1px solid #ccc;

                    &:last-child,
                    &.item-name {
                         border-right: 0;
                     }
                }

                .item-name {
                    text-align: center;
                }

            }

        }

        .selected {
            .plan-inner {
                &:after {
                     content: '';
                     position: absolute;
                     border: 10px solid transparent;
                     border-top-color: #e50914;
                     bottom: -20px;
                     left: 50%;
                     margin-left: -10px
                 }
            }
        }
    }
</style>