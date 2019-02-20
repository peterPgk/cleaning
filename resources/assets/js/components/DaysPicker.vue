<template>

    <div class="days-off">

        <vf-form
                ajax
                :action="action"
                method="POST"
                v-ref:daysPicker
                class="form-inline"
        >
            <vf-date
                    label="Select closed date"
                    name="date_off"
                    :value="today"
                    v-ref:date_off
                    no-input
                    :options="{showDropdowns: true, minDate:today}"
            >
            </vf-date>
            <vf-submit text="Add"></vf-submit>
        </vf-form>

        <hr>

        <div class="table">
            <v-client-table :data="_days" :columns="headers" :options="options"></v-client-table>
        </div>
    </div>

</template>

<script type="text/babel">

    export default {

        props: {
            action: {
                type: String,
                required: true
            },

            days: {
                type: Array
            }
        },

        data() {
            return {
                today: moment(),

                headers: ['day'],

                options: {
//                    filterable: [{day: false}],
                    dateColumns: ['day'],
                    dateFormat: 'dddd, MMMM Do YYYY',
                    headings: {
                        day: 'Day Off',
                        del: 'Remove'
                    },
                    templates: {
                        del: function (row) {
                            return `<a href='javascript:void(0);' @click='$parent.deleteSchedule(${row.id})'><i class='glyphicon glyphicon-erase'></i></a>`
                        }
                    }
                },

            }
        },

        computed: {
            _days () {
                return _.map(this.days, (el) => {
                    return {
                        day: moment(el.day, 'YYYY-MM-DD')
                    }
                })
            }
        },

        methods: {
            deleteSchedule: function (id) {
//                this.loading = true;

                this.$http.delete(`/admin/schedule/${id}/delete`, [])
                    .then(
                        function ({data}) {
//                            this.loading = false;
//                            this.scheduled = this.cleanJson(data);
//                            this.showMessage('success', 'Your timeslot has been deleted successfully');

                        }.bind(this),

                        function (error) {
//                            this.loading = false;
//                            this.showMessage('danger', 'Error!');
                            console.log(error, 'ERR');

                        }.bind(this)
                    );
            },
        },

        created () {

        }
    }
</script>

<style lang="sass" scoped>
</style>