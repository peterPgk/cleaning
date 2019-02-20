@extends('layouts.admin.admin')

@section('sidebar')
    @include('layouts.admin.sidebar_master')
@endsection

@section( 'content' )

    <section class="content-header">
        <h1>
            Master Admin Page
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Master</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app" v-cloak>

            <div class="modal" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Details view</h4>
                        </div>
                        <div class="modal-body">
                            <p>Revervation per services</p>
                            <ul>
                                <li v-for="service in modalstats">
                                    @{{ $key }} : @{{ service }}
                                </li>
                            </ul>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->


            <div class="row">
                <div class="col-xs-6">
                    <vf-form
                            action="/master"
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
                <div class="col-xs-12">
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4"><h4>Total</h4></div>
                <div class="col-xs-4"><h4>Total Bookings</h4></div>
                <div class="col-xs-4"><h4>New subscribers</h4></div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    {{ env('SITE_CURRENCY') }} @{{ total_revenue }}
                </div>
                <div class="col-xs-4">
                    @{{ total_bookings }}
                </div>
                <div class="col-xs-4">
                    @{{ new_subscribers }}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <chart :type="'pie'" :data="totalChart" :options="chartOptions"></chart>
                </div>
                <div class="col-xs-6"></div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <v-client-table :data="_topCompanies" :columns="top_headers" :options="top_options"></v-client-table>
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
                    chartOptions: {
                        segmentShowStroke: false
                    },
                    colors: [
                        '#1fc8db',
                        '#fce473',
                        '#42afe3',
                        '#ed6c63',
                        '#97cd76'
                    ],
                    totals: <?= json_encode( $total_stats )?>,

                    sortKey: 'bookings',
                    top_headers: ['logo', 'name', 'bookings'],
                    top_options: {
                        filterable: ['name', 'bookings'],
                            orderBy: {'column': 'bookings', 'ascending': false},
                        headings: {
                            logo: 'Company logo',
                                name: 'Company name',
                                bookings: 'Reservations'
                        },
                        templates: {
                            logo (row) {
                                return '<img height="50px" src="<?=url( '/' ) . "/img/logos/";?>' + row.logo + '" />';
                            },
                            bookings (row) {
                                return `<a href='javascript:void(0);' @click='$parent.detailedView(${row.id})'>${row.bookings}</a>`
                            },
                        }
                    },
                    top_companies: <?= json_encode( $companies ) ?>,
                    top_stats: <?= json_encode( $top_stats ) ?>,
                    modalstats: {},

                    total_revenue: <?= json_encode( $revenue ) ?>,
                    total_bookings: <?= json_encode( $bookings_cnt ) ?>,
                    new_subscribers: <?= json_encode( $new_subscribers ) ?>,
                }
            },

            computed: {
                totalChart () {

                    return {
                        labels: this._total_labels,
                        datasets: [{
                            data: this._total_values,
                            backgroundColor: this.colors
                        }]
                    };

                },

                _total_labels () {
                    return _.keys(this.totals)
                },

                _total_values () {
                    return _.values(this.totals)
                },

                _topCompanies () {
                    return _.toArray(this.top_companies);
                }

            },

            methods: {
                detailedView: function (row) {
                    this.modalstats = this.top_stats[row];

                    $("#myModal").modal('show');
                }
            },
        })
    </script>

@endsection