@extends('layouts.admin.admin')

@section('content')

    <section class="content-header">
        <h1>
            Edit postcodes
            <small>You can add and edit price for postcodes</small>
            <small>If you want to edit some postcode, just enter it again</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Postcodes</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <notification :show="alertData.show" placement="top" duration="3000" :msg="alertData.msg" :type="alertData.type" width="400px" dismissable>
            </notification>

            <div class="row">
                <div class="col-xs-12">

                    <vf-form ajax
                             action="/admin/postcodes/new"
                             method="POST"
                             v-ref:postcodes
                    >

                        <div class="form-inline">
                            <div class="form-group">
                                <label for="">Add postcode</label>
                                <vf-text
                                        {{--label="Add postcode"--}}
                                        name="code"
                                        v-ref:code
                                        placeholder="e.g. EZ"
                                        required
                                        :rules="{code:true}"
                                ></vf-text>

                                <label for="">Put your price</label>
                                <vf-number
                                        {{--label="Put your price"--}}
                                        name="price"
                                        v-ref:price
                                        placeholder="e.g. 20.20"
                                        required
                                        :rules="{price:true}"
                                ></vf-number>

                                <vf-submit></vf-submit>
                            </div>
                        </div>

                    </vf-form>

                    <div class="spinner" v-if="loading">
                        <loader :loading="loading" :color="color" :size="loaderSize" class="text-center"></loader>
                    </div>

                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-12">
                    <h4>Postcodes</h4>
                    <div class="table">
                        <v-client-table :data="_codes" :columns="headers" :options="options" transition="fadeLeft"></v-client-table>
                    </div>
                </div>
            </div>
        </div>



    </section>

@endsection

@section('js')
    <script>

        let app = new Vue({

            el: '#app',

            data: {
                //TODO Да изчистя формата
                loading: false,
                headers: ['code', 'price'],
                codes: <?= json_encode( $codes ) ?>,
                options: {
                    headings: {
                        code: 'Postcode',
                        price: 'Your price'
                    },
                },

                alertData: {
                    type: 'success',
                    show: false,
                    msg: 'Message'
                }
            },

            computed: {
                _codes: function () {

                    if( _.isString(this.codes) ) {
                        try {
                            return JSON.parse(this.codes)
                        }
                        catch(e) {
                            return this.codes
                        }
                    }

                    return this.codes;
                }
            },

            methods: {
                resetForm: function () {
                    this.$refs.postcodes.childrenOf('postcodes').forEach(function(field) {
                        field.reset();
                    })
                },

                showMessage: function ( type, msg ) {
                    this.alertData.type = type;
                    this.alertData.msg = msg;
                    this.alertData.show = true;
                }
            },

            events: {

                'vue-formular.sending': function () {
                    this.loading = true;
                },

                'vue-formular.sent': function ({data}) {

                    this.resetForm();

                    this.codes = data;
                    this.loading = false;
                    this.showMessage('success', 'Your postcode has been added successfully');
                },

                'vue-formular.invalid.server': function (response) {
                    //TODO: Error message
                    this.loading = false;
                    this.showMessage('danger', 'Error');
                },
            },

        });

    </script>
@endsection