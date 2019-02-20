@extends('layouts.admin.admin')

@section('content')

    <section class="content-header">
        <h1>
            Edit your services
            <small>You can edit services and prices</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Services</a></li>
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


                        <visible-step-services-board
                                :categories="services"
                                :valid="valid"
                                :info="info"
                                action="/admin/services/update"
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