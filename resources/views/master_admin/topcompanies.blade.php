@extends('layouts.admin.admin')

@section('sidebar')
    @include('layouts.admin.sidebar_master')
@endsection

@section( 'content' )

    <section class="content-header">
        <h1>
            Top 5 companies
            <small>This menu display top companies.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
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

            <hr>

            <div class="table">
                <v-client-table :data="_companies" :columns="headers" :options="options"></v-client-table>
            </div>

        </div>

    </section>

@endsection

@section('js')
    <script>
        let app = new Vue({
            el: '#app',

            data: {
                sortKey: 'bookings',
                loading: false,
                headers: ['logo', 'name', 'bookings'],
                options: {
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
                companies: <?= json_encode( $companies ) ?>,
                stats: <?= json_encode( $top_stats ) ?>,
                modalstats: {},

            },
            methods: {
                detailedView: function (row) {
                    this.modalstats = this.stats[row];

                    $("#myModal").modal('show');
                }
            },
            computed: {
                _companies () {

                    if (_.isString(this.companies)) {
                        try {
                            return JSON.parse(this.companies)
                        }
                        catch (e) {
                            return this.companies
                        }
                    }

                    return _.toArray(this.companies);
                },

            },

        })


    </script>

@endsection