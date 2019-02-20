@extends('layouts.admin.admin')

@section( 'content' )

    <style type="text/css">
        td {
            border: 1px solid #aaaaaa;
            padding: 5px;
        }
    </style>

    <section class="content-header">
        <h1>
            Company dashboard
            <small>Company summary</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="app" v-cloak>

            <div class="row">

                <div class="col-xs-12 form-inline">
                    <vf-form
                            action="/admin"
                            method="POST"
                            v-ref:statistics
                    >
                        {{ csrf_field() }}

                        <vf-date
                                name="services_date"
                                v-ref:services_date
                                :rules="{services_date:true}"
                                :options="{maxDate:today, locale:{firstDay:1}}"
                                class="pull-left"
                                required
                        ></vf-date>

                        <vf-submit text="Show" class="pull-left"></vf-submit>

                    </vf-form>
                </div>

                <div class="row"><div class="col-xs-12">&nbsp;</div></div>
                <div class="row"><div class="col-xs-12">&nbsp;</div></div>

                <div class="col-xs-12">
                    <h4>Summary for @{{ stats.date }}</h4>

                    <table>
                        <tr>
                            <td>Total bookings today: </td>
                            <td>@{{ stats.today_cnt  }}</td>
                        </tr>
                        <tr v-for="count in stats.grouped">
                            <td>@{{ $key }}: </td>
                            <td>@{{ count }}</td>
                        </tr>
                        <tr>
                            <td>Total revenue: </td>
                            <td>£@{{ stats.total_revenue }}</td>
                        </tr>
                    </table>


                    <h4>Monthly</h4>
                    <p>Package: @{{ plan.name }}</p>
                    <p>@{{ stats.month_cnt }} / @{{ plan.jobs }}</p>
                </div>


            </div>



        </div>

        <!-- Your Page Content Here -->

    </section>
    <!-- /.content -->

@endsection

@section('js')
    <script>
        let app = new Vue({
            el: '#app',

            data: function () {
                return {
                    today: moment(),
                    stats: <?= json_encode($data) ?>,
                    company: <?=json_encode($company) ?>,
                    plan: <?= json_encode($plan) ?>
                }
            },

        })
    </script>

@endsection