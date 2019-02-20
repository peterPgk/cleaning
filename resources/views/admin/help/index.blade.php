@extends('layouts.admin.admin')

@section('content')

    <section class="content-header">
        <h1>
            HELP
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Help</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <div class="row">
                <div class="col-xs-12">

                    Help text<br />
                    <a href="http://google.com" target="_blank">Visit our helpdesk</a>
                    <hr />
                    @if($planData['show_help'] == 1)
                        Help number for PRO Subscribers: 0000 0000 0000
                    @endif
                </div>

            </div>
        </div>

    </section>

@endsection