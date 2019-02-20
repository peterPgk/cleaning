<template>
<div>
    <vf-buttons-list
            name="timeslot"
            v-ref:timeslot
            label="Select time slot for ?"
            :value="_selected"
            :items="_slots"
    >
    </vf-buttons-list>
</div>
</template>

<script type="text/babel">
    export default {
        props: {
            slots: {
                type: Array
            },

            selected: [String, Number]
        },

        computed: {
            _slots () {
                return _.map(this.slots, (slot) => {
                    return {
                        id: slot.id,
                        text: moment(slot.from).format('YYYY/MM/DD ( HH:mm') + ' - ' + moment(slot.to).format('HH:mm )')
                    }
                })
            },

            _selected () {
                return `${this.selected}`
            }
        },

        data() {
            return {

            }
        },
    }
</script>

<style lang="sass" scoped>
</style>
