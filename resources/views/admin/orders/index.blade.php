@extends('layouts.admin.admin')

@section('content')

    <section class="content-header">
        <h1>
            Your orders
            <small>View your orders here</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Orders</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <div class="row">
                <div class="col-xs-12">

                    <div class="table">
                        <v-client-table :data="_orders" :columns="headers" :options="options"></v-client-table>
                    </div>

                    <div class="spinner" v-if="loading">
                        <!--<div class="spinner" v-if="isSending">-->
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
                headers: ['_from', 'email', 'service_date', 'service_time', '_services'],
                orders: <?= json_encode( $orders ) ?>,
                options: {
                    dateColumns: ['service_date'],
                    headings: {
                        _services: 'Services',
                        _from: 'Client name',
                    },
                    filterable: ['email'],
                    orderBy: { 'column': 'date_created','ascending': false},
                    templates: {
                        _services: function (row) {
                            //return (_.map(row.services, (s) => `${s.name} : ${s.count} cnt`)).join('<br>')
                            return (_.map(row.services, function (s) { return "" + s.name + ":" + s.count +  "cnt"})).join('<br>');
                        },
                        _from: function (row) {
//                            return (_.map(row.services, 'name')).join('<br>')
                            return '<a href="/admin/orders/' + row.id + '">' + row.from + '</a>';
                        }
                    }
                }
            },

            computed: {
                _orders: function () {
                    if( _.isString(this.orders) ) {
                        try {
                            return JSON.parse(this.orders)
                        }
                        catch(e) {
                            return this.orders
                        }
                    }

                    return this.orders;
                }
            }

        });

    </script>
@endsection