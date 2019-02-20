@extends('layouts.admin.admin')

@section('sidebar')
    @include('layouts.admin.sidebar_master')
@endsection

@section( 'content' )

    <section class="content-header">
        <h1>
            Master Admin Page - {{ $title }}
            <small>{{ $labels }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Master</a></li>
            <li class="active">{{ $title }}</li>
        </ol>
    </section>
 
    <!-- Main content -->
    <section class="content">

        <div id="app">

            <div class="row">
                {{--<div class="col-xs-12 col-sm-8 col-sm-push-2 col-md-6 col-md-push-3 col-lg-4 col-lg-push-4">--}}
                <div class="col-xs-6">
                    <vf-form
                            action="/master/total"
                            method="POST"
                            v-ref:total
                            class="form-inline"
                    >
                        {{ csrf_field() }}

                        <vf-date
                                name="services_date"
                                v-ref:services_date
                                :range="true"
                                :rules="{services_date:true}"
                                :options="{maxDate:today, locale:{firstDay:1}}"
                                class="pull-left"
                                required
                        ></vf-date>

                        <vf-submit text="Show" class="pull-left"></vf-submit>

                    </vf-form>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">&nbsp;</div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <chart :type="'pie'" :data="chartData" :options="chartOptions"></chart>
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

            data () {
                return {
                    chart: <?= json_encode( $total_stats )?>,

                    chartOpts: {
                        segmentShowStroke: false
                    }
                }
            },

            computed: {
                chartData () {

                    return {
                        labels: this._labels,
                        datasets: [{
                            data: this._values,
                            backgroundColor: [
                                '#1fc8db',
                                '#fce473',
                                '#42afe3',
                                '#ed6c63',
                                '#97cd76'
                            ]
                        }]
                    };

                },

                _labels () {
                    return _.keys(this.chart)
                },

                _values () {
                    return _.values(this.chart)
                }

            }
        })
    </script>

@endsection