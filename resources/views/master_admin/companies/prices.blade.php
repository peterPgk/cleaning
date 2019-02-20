@extends('layouts.admin.admin')

@section('sidebar')
    @include('layouts.admin.sidebar_master')
@endsection

@section( 'content' )

    <section class="content-header">
        <h1>
            {{ $mapData['name'] }} Prices
            <small>Edit company prices</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Company</a></li>
            <li class="active">Prices</li>
        </ol>
    </section>

  <!-- Main content -->
    <section class="content">

        <div id="app">

            <notification :show="alertData.show" placement="top" duration="3000" :msg="alertData.msg" :type="alertData.type" width="400px" dismissable>
            </notification>

            <div class="row">
                <div class="col-xs-12">


                        <visible-step-services-board
                                :categories="services"
                                :valid="valid"
                                :info="info"
                                action="/master/company/'. $mapData['company_id'] . '/update-prices"
                        >
                        </visible-step-services-board>



                    {{--</vf-form>--}}

                    <div class="spinner" v-if="loading">
                        <loader :loading="loading" :color="color" :size="loaderSize" class="text-center"></loader>
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
                services: <?php echo json_encode($services); ?>,
            },

            events: {

                'vue-formular.sent': function () {
                    this.loading = false;
                    this.showMessage('success', 'Your services has been edited successfully');
                },
            },

        });

    </script>
@endsection