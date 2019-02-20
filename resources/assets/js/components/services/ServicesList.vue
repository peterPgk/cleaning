<template>

    <div class="services-list row">
        <div class="col-xs-12">
            <h4>Services</h4>
            <div class="row">
                <div class="col-xs-3">Service</div>
                <div class="col-xs-1">Number</div>
                <div class="col-xs-1" v-if="showPrice">Single price</div>
                <div class="col-xs-2" v-if="showPrice">Price</div>
            </div>
            <div class="row" v-for="service in services">
                <div class="col-xs-3">{{service.name}}</div>
                <div class="col-xs-1">{{service.count}}</div>
                <div class="col-xs-1" v-if="showPrice">£{{service.price}}</div>
                <div class="col-xs-2" v-if="showPrice">£{{service.sum_price}}</div>
            </div>

        </div>
    </div>

</template>

<script>
    export default {

        props: {
            services: {
                type: [Object, Array],
                default: () => ([])
            },

            showPrice: false,
        },

        data () {
            return {}
        },
    }
</script>

<style lang="scss" scoped>

</style>