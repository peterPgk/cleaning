@extends('test.layouts.master')
@section('title','FRONTEND')
@section('content')

<h1>{{$data['title']}}</h1>
{{$data['status']==1 ?'Success' : ''}}

<div class="col-xs-12 col-sm-5">
 <vf-form ajax
                     action="/emails/customer_feedback"
                     method="POST"
                     v-ref:general_edit
            >
   <vf-submit>Text</vf-submit>

 </vf-form>
</div>
@endsection