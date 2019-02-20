@extends('layouts.firstlogin')
@section('title','Admin index')
@section('content')

<div class="row">

    <wizard
            :one-at-atime="true"
            {{--:current-step="currentStep"--}}
            {{--:show-counter=""--}}
            {{--:has-final="true"--}}
            top-class="collect"
    >

        <div class="col-xs-12 col-md-8 col-md-push-2">
            <wizard-slider-step
                    title="Get started with {{ env('SITE_NAME') }}"
                    v-ref:head1
                    :valid="valid.step1"
                    :locked="isLocked"
                    :loading="isSending"
            >
                @include('admin.login-steps.step1')

            </wizard-slider-step>
        </div>

        <div class="col-xs-12 col-md-8 col-md-push-2">
            <wizard-slider-step
                    title=""
                    v-ref:head2
                    :valid="valid.step2"
                    :locked="isLocked"
                    :loading="isSending"
            >
                @include('admin.login-steps.step2')

            </wizard-slider-step>
        </div>

        <div class="col-xs-12 col-md-8 col-md-push-2">
            <wizard-slider-step
                    title="Get started with {{ env('SITE_NAME') }}"
                    v-ref:head3
                    :valid="valid.step3"
                    :locked="isLocked"
                    :loading="isSending"
            >
                @include('admin.login-steps.step3')

            </wizard-slider-step>
        </div>

        <div class="col-xs-12 col-md-8 col-md-push-2">
            <wizard-slider-step
                    title="Get started with {{ env('SITE_NAME') }}"
                    v-ref:head4
                    :valid="valid.step4"
                    :locked="isLocked"
                    :loading="isSending"
            >
                @include('admin.login-steps.step4')

            </wizard-slider-step>
        </div>

        <div class="col-xs-12 col-md-8 col-md-push-2">
            <wizard-slider-step
                    title="Get started with {{ env('SITE_NAME') }}"
                    v-ref:head5
                    :valid="valid.step5"
                    :locked="isLocked"
                    :loading="isSending"
            >
                @include('admin.login-steps.step5')

            </wizard-slider-step>
        </div>

        {{-- ====== STEP 1 - POSTCODES ========= --}}
        {{--<div class="col-xs-12 col-md-8 col-md-push-2">--}}
            {{--<wizard-slider-step--}}
                    {{--title="Postcodes"--}}
                    {{--v-ref:head1--}}
                    {{--:valid="valid.step1"--}}
                    {{--:locked="isLocked"--}}
                    {{--:loading="isSending"--}}
            {{-->--}}
                {{--<postcodes--}}
                    {{--:codes="getPostcodes"--}}
                    {{--action="/collect/step1"--}}
                    {{--step="1"--}}
                {{-->--}}
                {{--</postcodes>--}}

            {{--</wizard-slider-step>--}}
        {{--</div>--}}

        {{-- STEP 2 - SERVICES --}}
        {{--<div class="col-xs-12 col-md-8 col-md-push-2">--}}
            {{--<wizard-slider-step--}}
                    {{--title="Services"--}}
                    {{--v-ref:head1--}}
                    {{--:valid="valid.step1"--}}
                    {{--:locked="isLocked"--}}
                    {{--:loading="isSending"--}}
            {{-->--}}
                {{--<step-services-board--}}
                    {{--:categories="getServices"--}}
                    {{--:valid="valid"--}}
                    {{--action="/collect/step2"--}}
                {{-->--}}
                {{--</step-services-board>--}}

            {{--</wizard-slider-step>--}}
        {{--</div>--}}

        {{-- STEP 3 - SERVICES --}}
        {{--<div class="col-xs-12 col-md-8 col-md-push-2">--}}
            {{--<wizard-slider-step--}}
                    {{--title="Availability"--}}
                    {{--v-ref:head2--}}
                    {{--:valid="valid.step2"--}}
                    {{--:locked="isLocked"--}}
                    {{--:loading="isSending"--}}
            {{-->--}}

                    {{--<week-slots--}}
                        {{--:max-num="getMaxBooking"--}}
                    {{-->--}}
                        {{--<days-picker--}}
                                {{--action="/collect/days-off"--}}
                                {{--:days="getDates"--}}
                        {{--></days-picker>--}}
                    {{--</week-slots>--}}

                    {{--<hr>--}}

            {{--</wizard-slider-step>--}}
        {{--</div>--}}

    {{--<wizard-slider-step--}}
            {{--title="Services"--}}
            {{--description="Enter your price for selected services"--}}
            {{--v-ref:head1--}}
            {{--:valid="valid.step1"--}}
            {{--:locked="isLocked"--}}
            {{--:loading="isSending"--}}
    {{-->--}}
        {{--<div class="row">--}}
            {{--<div class="col-xs-12">--}}
                {{--<vf-form ajax--}}
                         {{--action="/collect/step2"--}}
                         {{--method="POST"--}}
                         {{--v-ref:step2--}}
                {{-->--}}

                    {{--<services-form--}}
                            {{--type="client"--}}
                            {{--:services="stored.services"--}}
                            {{--:additional-taxes="mapData.additionalTaxes"--}}
                    {{--></services-form>--}}

                    {{--<div class="row">--}}
                        {{--<div class="col-xs-12">--}}
                            {{--<vf-submit></vf-submit>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                {{--</vf-form>--}}
            {{--</div>--}}
        {{--</div>--}}

    {{--</wizard-slider-step>--}}

        {{-- ====== STEP 3 - FINAL STEP ========= --}}
        {{--<wizard-slider-step--}}
                {{--title="Final"--}}
                {{--v-ref:head4--}}
                {{--:valid="valid.step4"--}}
                {{--:locked.sync="isLocked"--}}
                {{--:loading="isSending"--}}
        {{-->--}}
            {{--<div class="row text-center">--}}
                {{--<div class="col-xs-12">--}}
                    {{--<h5>Your registrations has finished successfully</h5>--}}
                {{--</div>--}}
                {{--<button-container>--}}
                    {{--<a class="btn" href="/admin">Go to Admin</a>--}}
                {{--</button-container>--}}
            {{--</div>--}}
        {{--</wizard-slider-step>--}}

    </wizard>
</div>

@endsection
