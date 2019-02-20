<template>
    <div>
        <alert :show.sync="show" placement="top" duration="3000" :type="type" width="400px" dismissable>
            {{ msg }}
        </alert>
    </div>
</template>

<script type="text/babel">

    import Alert from './Alert.vue'

    export default {
        components: {
            Alert
        },

        data() {
            return {}
        },

        props: {

            type: {
                type: String,
                default: 'danger'
            },

            show: {
                type: Boolean,
                default: false
            },

            msg: {
                type: String,
                default: 'Message'
            }
        }
    }
</script>

<style lang="sass" scoped>
</style>