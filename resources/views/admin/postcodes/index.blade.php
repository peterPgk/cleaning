@extends('layouts.admin.admin')

@section('content')

    <section class="content-header">
        <h1>
            Edit postcodes
            <small>You can add and edit price for postcodes</small>
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

            <vf-form ajax
                     action="/admin/postcodes/new"
                     method="POST"
                     v-ref:postcodes
            >
                <h3>Select postal</h3>
                <label>Select postal districts you cover?</label>

                <div class="form-inline">
                    <div class="row">
                        <div class="col-xs-10">
                            <vf-select
                                    multiple
                                    select2
                                    err-msg="postal districts"
                                    name="regions"
                                    :value="my_codes"
                                    v-ref:regions
                                    :options="{width: '100%'}"
                                    :items="codes"
                            >
                            </vf-select>
                        </div>
                        <div class="col-xs-2">
                            <vf-submit></vf-submit>
                        </div>
                    </div>
                </div>
            </vf-form>

            <div class="spinner" v-if="loading">
                <loader :loading="loading" :color="color" :size="loaderSize" class="text-center"></loader>
            </div>

        </div>

    </section>

@endsection

@section('js')
    <script>

        let app = new Vue({

            el: '#app',

            data: {
                codes: <?= json_encode($all_codes)  ?>,
                my_codes: <?= $my_codes ?>,
            },

            events: {

                'vue-formular.sent': function () {
                    this.loading = false;
                    this.showMessage('success', 'Your postcode has been added successfully');
                },
            },

        });

    </script>
@endsection