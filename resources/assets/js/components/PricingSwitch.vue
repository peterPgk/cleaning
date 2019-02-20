<template>
    <div class="parent-table">
        <div class="row">

            <div v-for="table in priceData" class="col-xs-12 col-sm-6 {{computedClass}}">
                <div class="price-table text-center"
                     :class="{'hover-effect' : table.active, 'selected-plan' :  table.id == selected}"
                >
                    <div v-if="table.active" class="popular">MOST POPULAR</div>
                    <span v-if="table.id == selected" class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    <div class="price-table-heading">
                        <h4 class="title">{{table.name}} </h4>
                    </div>
                    <div class="price-table-body">
                        <p class="value">${{table.price}}<small>/month</small></p>
                    </div>
                    <ul class="list-group">
                        <li v-for="item in table.items" class="list-group-item"><i class="icon-ok text-success"></i> {{item.name}}</li>
                    </ul>
                    <div class="price-table-footer"> <button type="submit" @click="select(table.id)" class="btn btn-lg btn-success" href="#">BUY NOW!</button> </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            priceData: {
                type: [Array, Object, String],
                coerce (data) {
                    return (typeof data == 'string') ? JSON.parse(data) : data
                }
            },

            selected: {
                default: null
            }
        },

        data () {
            return {
                items: 0
            }

        },

        computed: {
            /**
             * In how many columns we must put plans
             * TODO: За сега не се занимавам да го изчислявам, приемам, че ще са три или 4
             *
             * @returns {string}
             */
            computedClass: function () {
                return "col-md-" + 12 / this.items
            },

        },

        methods: {
            select: function ( planId ) {
                this.selected = planId;
                this.$dispatch('pricingTable::selected', planId)
            },
        },

        events: {
            'register::getPriceTable' () {
                this.items = this.priceData.length

                //Try to find if some selected plan came from the server
                let selected = _.find(this.priceData, {'selected': true})

                if(selected)
                    this.selected = selected.id
            }
        },

    }
</script>

<style scoped>
    .parent-table {
        /*font-family: 'Lato', sans-serif;*/
        font-size: 16px;
        line-height: 28px;
        margin-top: 25px;
    }
    .main-title {
        font-weight: 700;
        text-align: center;
        margin: 50px 0;
    }
    .margin-bottom-20 {
        margin-bottom: 20px;
    }
    .margin-bottom-50 {
        margin-bottom: 50px;
    }


    /* =================================
    Price Table
    ================================= */
    .price-table {
        background: none;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
        border: 7px solid #eeeeee;
        -moz-transition: all .3s ease;
        -o-transition: all .3s ease;
        -webkit-transition: all .3s ease;
    }
    .price-table.hover-effect {
        margin-top: -15px;
    }
    .price-table:hover,
    .price-table.hover-effect {
        background: rgba(0,0,0, .03);
        border-color: #0093DD;
    }
    .price-table.selected-plan {
        background: rgba(0,0,0, .03);
        border-color: #2ab27b;
    }

    .price-table.selected-plan .glyphicon-ok{
        position: absolute;
        right: 35px;
        font-size: 50px;
        top: 5px;
        color: #2ab27b;
    }

    .price-table .popular {
        background-color: #d9534f;
        padding: 5px 0;
        font-weight: 800;
        color: #ffffff;
    }
    .price-table .price-table-heading {
        color: #333333;
        background: #f9f9f9;
        padding: 10px 0;
        margin: 0;
    }
    .price-table .price-table-heading .title {
        color: #333333;
        font-weight: 900;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .price-table > .price-table-body {
        color: #FFFFFF;
        background: #0093DD;
        padding: 50px 0 30px;
        -moz-transition: all .3s ease;
        -o-transition: all .3s ease;
        -webkit-transition: all .3s ease;
    }
    .price-table > .price-table-body .value {
        font-size: 66px;
        font-weight: 300;
        color: #FFFFFF;
        padding: 0;
    }
    .price-table > .price-table-body .value small {
        font-size: 16px;
    }
    .price-table > .list-group {
        color: #333;
        font-weight: 400;
        margin-bottom: 0;
    }
    .price-table > .list-group .list-group-item {
        color: #333;
        font-weight: 400;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
    }
    .price-table .btn {
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        font-weight: 700;
    }
    .price-table-footer {
        background: #eeeeee;
        padding: 15px 0;
    }
</style>