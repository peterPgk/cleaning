@extends('layouts.register')
@section('title','REGISTER')

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="panel text-center">
                <div class="panel-heading">
                    <h2>Landing page</h2>
                </div>
                <div class="panel-body">
                    {{--<form action="signup/index" method="post">--}}
                    {{--</form>--}}
                    <button-container>
                        <a href="signup/index" class="btn">SIGN UP NOW</a>
                    </button-container>
                </div>

            </div>
        </div>
    </div>

@endsection
