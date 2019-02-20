@extends('layouts.admin.admin')

@section( 'content' )

    <section class="content-header">
        <h1>
            Company Rating
            <small>Optional description</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Company</a></li>
            <li class="active">Company Rating</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <vf-textarea
                    label="User comment"
                    placeholder="You can answer of the user comment"
                    v-ref:fff
                    name="fff"
                    :disabled="true"
                    :value="edited_comment"
            >
            </vf-textarea>

            <vf-form ajax
                     action="/admin/company/rating/update"
                     method="POST"
                     v-ref:ratingForm
            >

                <vf-textarea
                        label="Your answer"
                        placeholder="You can answer of the user comment"
                        name="answer"
                        v-ref:answer
                        :value="edited_answer"
                        :disabled="edited_completed"
                        :rules="{answer:true, min:4}"
                >
                </vf-textarea>

                <vf-submit v-if="_showButton"></vf-submit>

            </vf-form>

            <hr>
            <hr>
            <hr>

            <div class="table">
                <v-client-table :data="_rating" :columns="headers" :options="options"></v-client-table>
            </div>

            <div class="spinner" v-if="loading">
                <loader :loading="loading" :color="color" :size="loaderSize" class="text-center"></loader>
            </div>

        </div>

    </section>
    <!-- /.content -->

@endsection

@section('js')
    <script>

        let app = new Vue({
            el: 'body',
            data: function () {
                return {

                    /**
                     * Мапваме тези пропъртита към формата
                     */
                    edited_answer: null,
                    edited_comment: null,
                    edited_completed: false,
                    edited_id: null,

                    rating: <?php echo json_encode( $rating ) ?>,
                    loading: false,
                    headers: ['email', 'date_created', '_rating', '_comment', '_answer', 'edit'],

                    options: {
                        dateColumns: ['date_created'],
                        filterable: ['email', 'date_created', '_comment', '_answer'],
                        orderBy:'date_created',
                        headings: {
                            email: 'User',
                            date_created: 'Created',
                            _rating: 'User rating',
                            edit: 'Edit',
                        },
                        templates: {
                            /**
                             * Ако реда вече е редактиран, попълваме двете полета, но
                             * скриваме бутона за изпращане
                             *
                             * @param row
                             * @returns {string}
                             */
                            edit: function(row) {
                                return row.completed
                                        //? `<a href='javascript:void(0);' @click='$parent.editMe(${row.id})'><i class="glyphicon glyphicon-eye-open"></i></a>`
                                        ? "<a href='javascript:void(0);' @click='$parent.editMe(" + row.id + ")'><i class='glyphicon glyphicon-eye-open'></i></a>"
                                       // : `<a href='javascript:void(0);' @click='$parent.editMe(${row.id})'><i class='glyphicon glyphicon-edit'></i></a>`
                                        : "<a href='javascript:void(0);' @click='$parent.editMe(" + row.id + ")'><i class='glyphicon glyphicon-edit'></i></a>"
                            },

                            _rating: function (row) {
//                                return `<span>${_.repeat('<i class="fa fa-star" aria-hidden="true"></i>', row.rating)}</span>`
                                return "<span>" + _.repeat('<i class="fa fa-star" aria-hidden="true"></i>', row.rating) + "</span>"
                            },

                            _comment: function (row) {
                                return _.truncate(row.comment)
                            },

                            _answer: function (row) {
                                return _.truncate(row.answer)
                            },
                        }
                    }

                }
            },

            computed: {
                _rating: function () {
                    if( _.isString(this.rating) ) {
                        try {
                            return JSON.parse(this.rating)
                        }
                        catch(e) {
                            return this.rating
                        }
                    }

                    return this.rating;
                },

                _showButton: function () {
                    return ! _.isNil(this.edited_id) && !this.edited_completed
                }

            },

            methods: {
                /**
                 *
                 */
                editMe: function (id) {

                    let record = _.find(this._rating, {id: id.toString()});

                    if ( ! _.isNil(record)) {
                        this.edited_answer = record.answer;
                        this.edited_comment = record.comment;
                        this.edited_completed = record.completed;
                        this.edited_id = id;
                    }
                },

                /**
                 * Изчистваме мапнатите полета и формата
                 */
                resetForm: function () {
                    this.edited_answer = this.edited_comment = this.edited_completed = this.edited_id = null;
                    this.$refs.ratingform.childrenOf('ratingform').forEach(function(field) {
                        field.reset();
                    })
                }
            },

            events: {
                'vue-formular.sending': function () {
                    this.loading = true;
                    let currentForm = this.$refs.ratingform;

                    currentForm.options.additionalPayload = {'id': this.edited_id}
                },

                'vue-formular.sent': function (response) {
                    this.loading = false;
                    this.rating = response.data;

                    this.resetForm();
                }
            }
        });

    </script>
@endsection