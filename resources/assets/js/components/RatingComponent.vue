<template>
    <form>
        <rating :items="items" legend="Default star rating:" :value="value" @change="ratingUpdate"></rating>
    </form>
    {{ value }}
</template>

<script type="text/babel">

    import Rating from 'vue-bulma-rating'

    export default {
        components: {
            Rating
        },

        data() {
            return {
                /* Rating component */
                value: 2,
                items: [
                    {
                        title: '5 Stars',
                        value: 5
                    },
                    {
                        title: '4 Stars',
                        value: 4
                    },
                    {
                        title: '3 Stars',
                        value: 3
                    },
                    {
                        title: '2 Stars',
                        value: 2
                    },
                    {
                        title: '1 Star',
                        value: 1
                    }
                ]
            }
        },

        methods: {
            ratingUpdate (val) {
                this.value = val
            }
        }
    }
</script>

<style lang="sass" scoped>
</style>