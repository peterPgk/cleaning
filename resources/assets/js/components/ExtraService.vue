<template>
    <div>
        <vf-checkbox
                :name="name"
                :checked="isSelected"
                v-ref:collection
        ></vf-checkbox> <label>{{ service.name }}</label>
    </div>
</template>

<script type="text/babel">
    export default {
        data() {
            return {}
        },

        props: {
            service: {
                type: [Object, Array]
            },

            selected: {
                type: Object,
                'default': () => {}
            }
        },

        computed: {
            name () {
                return `services_${this.service.id}`
            },

            isSelected () {
                return _.has(this.selected, this.name)
            }
        }
    }
</script>

<style lang="sass" scoped>
</style>