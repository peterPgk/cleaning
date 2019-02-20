@extends('layouts.admin.admin')

@section('sidebar')
    @include('layouts.admin.sidebar_master')
@endsection

@section( 'content' )

    <section class="content-header">
        <h1>
            {{ $mapData['name'] }} Data
            <small>Edit company data</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Company</a></li>
            <li class="active">General</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <notification :show="alertData.show" placement="top" duration="3000" :msg="alertData.msg" :type="alertData.type" width="400px" dismissable>
            </notification>

            <!-- Main content -->
            <section class="content">

                @include('auth.steps.step2', ['action' => '/master/company/'. $mapData['company_id'] . '/update', 'logo_path' => '/master/company/'. $mapData['company_id'] . '/general/logo'])

            </section>
            <!-- /.content -->

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
            data () {
                return {
                    mapData: <?php echo json_encode($mapData) ?>,
                    validation: {},
                    shared: {
                        uuid: 'logo'
                    },
                }
            },

            events: {

                'vue-formular.sent': function () {
                    this.loading = false;
                    this.showMessage('success', 'Your data has been updated successfully');
                },

            },

            ready () {
                $('.VF-Submit__button').text('Save');

            }
        });

    </script>
@endsection