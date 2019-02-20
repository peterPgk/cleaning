@extends('layouts.admin.admin')

@section('sidebar')
    @include('layouts.admin.sidebar_master')
@endsection

@section( 'content' )

    <section class="content-header">
        <h1>
            Master Admin Page - {{$title}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Master</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
 
    <!-- Main content -->
    <section class="content">

        <div id="app">

            <chart :type="'bar'" :data="chartData" :options="chartOptions"></chart>

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
                    chartData: {
                        labels: <?= json_encode( $stats['labels'] )?>,
                        datasets: [{
                            label: 'Percentage',
                            data: <?= json_encode( $stats['values'] )?>,
                            backgroundColor: [
                                '#1fc8db',
                                '#fce473',
                                '#42afe3',
                                '#ed6c63',
                                '#97cd76'
                            ]
                        }]
                    },
                   

                    chartOpts: {
                        segmentShowStroke: false
                    }
                }
            }
        })
    </script>

@endsection