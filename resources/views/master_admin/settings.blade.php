@extends('layouts.admin.admin')

@section('sidebar')
    @include('layouts.admin.sidebar_master')
@endsection

@section( 'content' )

    <section class="content-header">
        <h1>
            General Settings
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-cogs"></i> Master</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <div class="row">
                <div class="col-xs-12">
                    <vf-form ajax
                             action="/master/settings/edit"
                             method="POST"
                             v-ref:settings
                    >

                        <vf-number
                            label="VAT"
                            placeholder="VAT in percents"
                            name="vat"
                            v-ref:vat
                            :value="settings.vat.value"
                            :rules="{vat:true}"
                            required
                        ></vf-number>

                        <vf-number
                                label="Booking percent"
                                placeholder="Percent for the site owner"
                                name="booking"
                                v-ref:booking
                                :value="settings.booking.value"
                                :rules="{booking:true}"
                                required
                        ></vf-number>

                        <div class="row">
                            <div class="col-xs-12">
                                <vf-submit></vf-submit>
                            </div>
                        </div>

                    </vf-form>

                    <div class="spinner" v-if="loading">
                        <loader :loading="loading" :color="color" :size="loaderSize" class="text-center"></loader>
                    </div>

                </div>

            </div>
        </div>

    </section>
    <!-- /.content -->

@endsection

@section('js')
    <script>

        let app = new Vue({

            el: '#app',

            data: {
                loading: false,

                settings: <?= json_encode( $settings )?>,
            },

            events: {

                'vue-formular.sending': function () {
                    this.loading = true;
                },

                'vue-formular.sent': function ({data}) {
                    //TODO: Success message
                    this.loading = false;
                },

                'vue-formular.invalid.server': function (response) {
                    //TODO: Error message
                    console.log( response, 'Services form sending ERROR' );
                    this.loading = false;
                },
            },

        });

    </script>
@endsection