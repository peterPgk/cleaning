@extends('layouts.admin.admin')

@section('content')

    <section class="content-header">
        <h1>
            View order
            <small>Detailed view for your order</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Orders</a></li>
            <li class="active">View order</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">
            <div class="row">
                <div class="col-xs-12">
                    <h4>Client information:</h4>
                    <span class="text-bold">Firstname</span>: {{ $order['client']['firstname'] or $order['firstname'] }}
                    <br/>
                    <span class="text-bold">Lastname:</span> {{ $order['client']['lastname'] or $order['lastname'] }}
                    <br/>
                    <span class="text-bold">Email:</span> {{ $order['client']['email'] or $order['email'] }}<br/>
                    <span class="text-bold">Postcode:</span> {{ $order['postcode'] or $order['postcode'] }}<br/>
                    <span class="text-bold">Address:</span> {{ $order['client']['address'] or '' }}<br/>
                    <span class="text-bold">Address 2:</span> {{ $order['client']['address_2'] or '' }}<br/>
                    <span class="text-bold">Town:</span> {{ $order['client']['town'] or '' }}<br/>
                    <span class="text-bold">County:</span> {{ $order['client']['county'] or '' }}<br/>

                    <hr/>

                    <h4>Time and date:</h4>
                    <span class="text-bold">Service date:</span> {{ \Carbon\Carbon::parse($order['service_date'])->format('d.m.Y') }}
                    <br/>
                    <span class="text-bold">Timeslot:</span> <?php
					$exp = explode( '|', $order['timeslots'] );
					echo date( 'H:i', strtotime( $exp[0] ) ) . ' - ' . date( 'H:i', strtotime( $exp[1] ) );?>

                    <hr/>
                    <h4>Services</h4>

                    <span class="text-bold">{{ $order['category_name']  }}</span>

                    <br /><br />

                    @foreach($order['company']['services'] as $service)
                        <p> <b>{{ $service['category'] }} </b> - {{ $service['count'].' '.$service['name'] }}</p>
                    @endforeach

                    <br/>

                </div>
            </div>
        <!--<div class="row">
             <hr />
            <vf-form ajax action="{{ $url }}" 
                     method="POST"
                     >
                <vf-submit text="Resend booking email" class="pull-left"></vf-submit>
            </vf-form>
         </div>-->
            <div class="spinner" v-if="loading">
                <loader :loading="loading" :color="color" :size="loaderSize" class="text-center"></loader>
            </div>
        </div>


    </section>

@endsection

@section('js')
    <script>

        let app = new Vue({

            el: '#app',
            data: {
                loading: false,
                formurl: 'testaddr',
                order: <?= json_encode( $order ) ?>,
            },
            mixins: [
                events
            ],
            events: {
                'vue-formular.sending': function () {
                    this.loading = true;
                },
                'vue-formular.sent': function () {
                    //TODO: Success message
                    this.loading = false;
                },
                'vue-formular.invalid.server': function (response) {
                    console.log(response);
                    this.$emit('sent.error', response)
                },
            }


        });

    </script>
@endsection