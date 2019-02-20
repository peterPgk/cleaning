
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */


import Rating from 'vue-bulma-rating'

const app = new Vue({
    el: 'body',

    components: {
        Rating
    },

    data() {
        return {
            msg: 'rating system',
            /**
             * Предавам я глобално в блейда, да видя как да работя направо там
             */
            uuid: uuid,

            sent: false,
            error: false,
            success: false,

            value: 0,
            items: [
                {
                    title: '5 Stars',
                    label: '5 label',
                    value: 5
                },
                {
                    title: '4 Stars',
                    label: '4 label',
                    value: 4
                },
                {
                    title: '3 Stars',
                    label: '3 label',
                    value: 3
                },
                {
                    title: '2 Stars',
                    value: 2
                },
                {
                    title: '1 Star',
                    label: '1 label',
                    value: 1
                }
            ]
        }
    },

    computed: {
    },

    methods: {
        update (e) {
            this.value = e.target.value >>> 0
        }
    },

    events: {
        'vue-formular.sending' () {
            let currentForm = this.$refs.ratingform;

            currentForm.options.additionalPayload = {'rating': this.value, 'uuid': uuid}
        },

        'vue-formular.sent': function ({status}) {
            this.sent = true;
            if( status == 200 ) {
                this.success = true
            }
        },

        'vue-formular.invalid.server': function ({status}) {
            this.error = true;
            if (status != 200) {
                this.error = true;
            }
        },
    },


});
