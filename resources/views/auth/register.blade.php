@extends('layouts.register')
@section('title','REGISTER')

@section('content')

    <header class="container-fluid shadowed">
        <h2 class="pull-left vcenter">Logo</h2>
        <h2 class="pull-right vcenter">Help</h2>
    </header>

    {{--<div class="seller-signup-leftnav">--}}
        {{--<div class="signup-leftnav-wrap">--}}
            {{--<div class="signup-leftnav-main">--}}
                {{--<div class="signup-leftnav-heading">--}}
                    {{--<h2>Create up your partner profile</h2>--}}
                    {{--<br>--}}
                    {{--<p>Enter your company details to register with {{ $site_name  }} and to start receiving work!</p>--}}
                    {{--<p>Having difficulty? Call {{ $phone_num }}.</p>--}}
                {{--</div>--}}
                {{--<div class="signup-leftnav-steps" :class="[valid, currentRef]">--}}

                    {{--<div class="signup-leftnav-step step1">--}}
                        {{--<p>Choose your package</p>--}}
                    {{--</div>--}}
                    {{--<div class="signup-leftnav-step step2">--}}
                        {{--<p>About your company</p>--}}
                    {{--</div>--}}
                    {{--<div class="signup-leftnav-step step3">--}}
                        {{--<p>Additional information</p>--}}
                    {{--</div>--}}
                    {{--<div class="signup-leftnav-step step4">--}}
                        {{--<p>Set up your payment</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}


    {{--<div class="seller-signup-rightnav">--}}

        <div class="main-content">


            <wizard
                    :one-at-atime="true"
                    :has-final="true"
                    {{--:current-step="currentStep"--}}
            >

                {{-- ====== STEP 1 - PRICING TABLE ========= --}}
                <wizard-hidden-step
                        title="Choose your package"
                        v-ref:head1
                        :valid="valid.step1"
                        :locked="isLocked"
                        :loading="isSending"
                >
                    @include('auth.steps.step1')

                </wizard-hidden-step>

                {{-- ====== STEP 2 - REGISTRATION INFO ========= --}}
                <wizard-hidden-step
                        title="About your company"
                        v-ref:head2
                        :valid="valid.step2"
                        :locked="isLocked"
                        :loading="isSending"
                >
                    @include('auth.steps.step2', ['action' => "/company/signup/step2"])

                </wizard-hidden-step>

                {{-- ====== STEP 3 - ADDITIONAL INFO ========= --}}
                <wizard-hidden-step
                        title="Additional information"
                        description="Add company additional info"
                        v-ref:head3
                        :valid="valid.step3"
                        :locked="isLocked"
                        :loading="isSending"
                >
                    @include('auth.steps.step3', ['action' => '/company/signup/step3'])

                </wizard-hidden-step>

                {{-- ====== STEP 4 - STRIPE DETAILS ========= --}}
                <wizard-hidden-step
                        title=" Set up your payment"
                        v-ref:head4
                        :valid="valid.step4"
                        :locked="isLocked"
                        :loading="isSending"
                >
                    @include('auth.steps.step4', ['action' => '/company/signup/step4'])

                </wizard-hidden-step>

                {{-- ====== STEP 5 - FINAL STEP ========= --}}
                <wizard-hidden-step
                        title="Registration complete"
                        v-ref:head5
                        :valid="valid.step5"
                        :locked.sync="isLocked"
                >
                    @include('auth.steps.step5')

                </wizard-hidden-step>

            </wizard>
        </div>
    {{--</div>--}}

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-4">
                        Terms of use
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        Privacy
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        Cookie Preferences
                    </div>
                </div>
            </div>

        </footer>


@endsection
