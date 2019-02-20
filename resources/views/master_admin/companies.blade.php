@extends('layouts.admin.admin')

@section('sidebar')
    @include('layouts.admin.sidebar_master')
@endsection

@section( 'content' )

    <section class="content-header">
        <h1>
            Companies edit
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-cogs"></i> Master</a></li>
            <li class="active">Companies</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <div class="row">
                <div class="col-xs-12 form-inline">
                <label for="filter">Filter: </label>
                <select class="form-control" name="filter" id="filter" v-model="companies_filter" @change="filterCompanies">
                    <option value="0">Unapproved</option>
                    <option value="1">Approved</option>
                    <option value="">All</option>
                </select>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">

                    <div class="table">
                        <v-client-table :data="_companies" :columns="table_headers" :options="table_options"></v-client-table>
                    </div>

                    <div class="spinner" v-if="loading">
                        <loader :loading="loading" :color="color" :size="loaderSize" class="text-center"></loader>
                    </div>

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

            data: {
                companies_filter: "",

                loading: false,

                companies: <?= json_encode( $companies )?>,

                table_headers: ['name', 'email', 'created_at'],
                table_options: {
                    dateColumns: ['created_at'],
                    headings: {
                        created_at: 'Created',
                        name: 'Company name',
                        change: 'Is approved',
                        edit: 'Edit Data',
                        additional: 'Edit Additional',
                        prices: 'Edit Prices'
                    },
                    texts: {
                        filterBy: 'Filter'
                    },
                    templates: {
                        change: function (row) {
                            {{--return `<a href='javascript:void(0);' @click='$parent.changeApproved(${row.id})'><i class='glyphicon glyphicon-erase'></i></a>`--}}

                            return `
                                <div class="onoffswitch">
                                    <input type="checkbox" class="onoffswitch-checkbox"
                                        id="${row.id}"
                                        name="${row.id}"
                                        @change="$parent.changeApproved(${row.id})"
                                        ${row.approved ? 'checked' : ''}
                                    >
                                    <label class="onoffswitch-label" for="${row.id}">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            `
                        },

                        edit: function (row) {
                            return `<a href="/master/company/${row.id}/general"><i class="fa fa-edit"></i></a>`
                        },

                        additional: function (row) {
                            return `<a href="/master/company/${row.id}/additional"><i class="fa fa-edit"></i></a>`
                        },
                                
                        prices: function (row) {
                            return `<a href="/master/company/${row.id}/prices"><i class="fa fa-edit"></i></a>`
                        }
                    },
                    customFilters: [
                        {
                            name:'company',
                            callback: function(row, query) {
                                return row.approved == query;
                            }
                        }
                    ]
                }
            },

            computed: {
                _companies () {
                    if( _.isString(this.companies) ) {
                        try {
                            return JSON.parse(this.companies)
                        }
                        catch(e) {
                            return this.companies
                        }
                    }

                    return this.companies;
                }
            },

            methods: {
                changeApproved: function (id) {
                    this.loading = true;

                    this.$http.put(`/master/companies/${id}/update`, [])
                        .then(
                            function () {
                                this.loading = false;
//                                this.scheduled = this.cleanJson(data);
//                                this.showMessage('success', 'Your timeslot has been deleted successfully');

                            }.bind(this),

                            function (error) {
                                this.loading = false;
//                                this.showMessage('danger', 'Error!');
                                console.log(error, 'ERR');

                            }.bind(this)
                        );
                },

                /**
                 *
                 */
                filterCompanies() {
                    this.$broadcast('vue-tables.filter::company', this.companies_filter);
                }
            },

        });

    </script>
@endsection