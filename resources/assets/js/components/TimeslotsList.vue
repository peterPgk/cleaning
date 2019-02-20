<template>
    <div class="timeslots-list">
        <div class="row">
            <div class="col-xs-12">
                <h4>Service Date</h4>
                <div class="row">
                    <div class="col-xs-12">{{ date }}</div>
                </div>
                <div class="row">
                    <div class="col-xs-12"> Arriving between {{ from }} - {{ to }}</div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            /**
             * TODO: Да го направя да приема масив от стринг слотове във формат 'start_slot|end_slot'
             *
             * Да се екстрактват датите и да се листват слотовете
             */
            slot: ''
        },

        computed: {
            slots () {
                return this.slot.split('|')
            },

            date () {
                return moment(_.head(this.slots), moment.ISO_8601).format('dddd, MMMM Do YYYY')
            },

            from () {
                return moment(_.head(this.slots), moment.ISO_8601).format('hh:mm a')
            },

            to () {
                return moment(_.last(this.slots), moment.ISO_8601).format('hh:mm a')
            }
        },

        data () {
            return {}
        }
    }
</script>

<style lang="scss" scoped>

</style>