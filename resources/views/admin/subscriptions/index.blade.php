@extends('layouts.admin.admin')

@section('content')

    <section class="content-header">
        <h1>
            Your subscription plan - <span class="text-bold">{{ $plan['name'] }}</span>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Subscription</a></li>
            <li class="active">Change</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <div class="row">
                <div class="col-xs-12">

                    <div class="table">
                        <pricing-table
                            :price-data="plans"
                            :selected="plan.plan"
                        ></pricing-table>
                    </div>

                    {{--<form action="@{{path}}" method="post">--}}
                        {{--<input type="hidden" name="plan" value="@{{ selected }}">--}}

                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-6">
                            <button type="submit" class="btn btn-primary" :disabled="disabled" @click="changePlan">Change Plan</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-danger" @click="unsubscribe">Unsubscribe</button>
                        </div>
                    </div>
                    {{--</form>--}}


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
                plan: <?=json_encode($plan)?>,
                plans: <?=json_encode($plans)?>,
                change_path: '/admin/subscription/change',
                uns_path: '/admin/subscription/unsubscribe',
                selected: null
            },

            computed: {
                disabled: function () {
                    return _.isNil(this.selected) || this.selected == this.plan.plan;
                }
            },

            methods: {
                changePlan: function () {
                    this.loading = true;
                    this.$http.post(this.change_path, this.selected)
                        .then(function (response) {
                                this.loading = false;
                                this.showMessage('success', 'Your plan has been upgraded successfully');
                            }.bind(this),
                            function (error) {
                                this.loading = false;
                                this.showMessage('danger', 'Error upgrading plan');
                            }.bind(this))
                },

                unsubscribe: function () {
                    this.loading = true;
                    this.$http.post(this.uns_path, [])
                        .then(function (response) {
                                this.loading = false;
                                this.showMessage('success', 'Your plan has been upgraded successfully');
                            }.bind(this),
                            function (error) {
                                this.loading = false;
                                this.showMessage('danger', 'Error upgrading plan');
                            }.bind(this))
                },
            },

            events: {
                'pricingTable::selected': function (response) {
                    /**
                     * Saving selected plan
                     */

                    this.selected = response;
                }
            },

        });

    </script>
@endsection