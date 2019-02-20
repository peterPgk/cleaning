@extends('layouts.admin.admin')

@section('sidebar')
    @include('layouts.admin.sidebar_master')
@endsection

@section( 'content' )

    <section class="content-header">
        <h1>
            {{ $mapData['name'] }} Details
            <small>Optional description</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <notification :show="alertData.show" placement="top" duration="3000" :msg="alertData.msg" :type="alertData.type" width="400px" dismissable>
            </notification>

            @include('auth.steps.step3', ['action' => '/master/company/'. $mapData['company_id'] .'/update-info', 'liability_path' => '/master/company/'. $mapData['company_id'] .'/additional/liability'])

            <div class="spinner" v-if="loading">
                <loader :loading="loading" :color="color" :size="loaderSize" class="text-center"></loader>
            </div>
        </div>

    </section>
    <!-- /.content -->

@endsection

@section('js')
    <script>

        /**
         * TODO: Да сложа валидациите на стъпките във самите полета на формата,
         * за да може да се валидира автоматично на двете места
         *
         * @type {Vue}
         */
        let app = new Vue({
            el: 'body',

            data: {
                data: <?php echo json_encode($data) ?>,
                map_data: <?php echo json_encode($mapData) ?>,
                validation: {},
                stored: {
                    service_categories: [],
                },
                shared: {
                    /**
                     * TODO: Да оправя ъплоуда на сертификата
                     */
                    uuid: 'img'
                },
            },

            computed: {
                getQuarantee () {
                    // return _.isEmpty(this.getStored('cleaning_quarantee')) ? [] : _.castArray(this.getStored('cleaning_quarantee'));
                    return !_.has(this.data, 'cleaning_quarantee') ? [] : this.data.cleaning_quarantee;
                },

                getMembers () {
                    return !_.has(this.data, 'members_of') ? [] : this.data.members_of;
                },

                getAmount () {
                    return !_.has(this.data, 'liability_amount') ? [] : this.data.liability_amount;
                },

                getServiceCategories () {
                    return !_.has(this.data, 'service_categories') ? [] : this.data.service_categories;
                },

                mapData () {
                    let ret = this.map_data;

                    ret.members_of = JSON.parse(ret.members_of);
                    ret.liability = !!ret.liability;

                    return ret;

                }
            },

            events: {

                'vue-formular.sent': function () {
                    //TODO: Да изчистя полетата за паролата след успешня промяна
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