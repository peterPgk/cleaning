@extends('test.layouts.master')
@section('title','FRONTEND')
@section('content')


    <div class="booking-content row">

        <wizard
                :one-at-atime="true"
                {{--:current-step="currentStep"--}}
                :show-counter="true"
                :has-final="true"
                :force-count-steps="4"
                top-class="booking"
        >
        {{-- ====== STEP 1 - GENERAL INFO ========= --}}
        <!--<div class="col-xs-12 col-md-8 col-md-push-2">-->
            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-push-3 comparestep1">
                    <wizard-slider-step
                            title="Pick a service to compare"
                            description="Compare up to 50 professional and vatted cleaning companies in under 60 seconds."
                            icon="users"
                            v-ref:head1
                            :valid="valid.step1"
                            :locked="isLocked"
                            :loading="isSending"
                            :force-hide-counter="true"
                    >
                        @include('test.steps.step1')

                    </wizard-slider-step>

                </div>
            </div>

            {{--====== STEP 2 - DATE AND POSITION ========= --}}
            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-push-3 comparestep2">

                    <wizard-slider-step
                            title="When would you like the service?"
                            description="Pick a date and arrival time so we can give you the most accurate results."
                            icon="money"
                            v-ref:head2
                            :valid="valid.step2"
                            :locked="isLocked"
                            :loading="isSending"
                    >
                        @include('test.steps.step2')

                    </wizard-slider-step>

                </div>
            </div>

            {{--====== STEP 3 - SERVICES SPECIFIC ========= --}}
            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-push-3 comparestep3">

                    <wizard-slider-step
                            title="About your property"
                            description="Tell us more about your property so we can provide the most accurate prices."
                            icon="users"
                            v-ref:head3
                            :valid="valid.step3"
                            :locked="isLocked"
                            :loading="isSending"
                    >
                        @include('test.steps.step3')

                    </wizard-slider-step>
                </div>
            </div>

            {{--====== STEP 4 - EXTRA SERVICES ========= --}}
            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-push-3 comparestep4">

                    <wizard-slider-step
                            title="Additional services"
                            description="Tell us about any extra services you require on the day."
                            icon="users"
                            v-ref:head3
                            :valid="valid.step3"
                            :locked="isLocked"
                            :loading="isSending"
                    >
                        @include('test.steps.step4')

                    </wizard-slider-step>

                </div>
            </div>

            {{--====== STEP 5 - Additional info ========= --}}
            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-push-3 comparestep5">

                    <wizard-slider-step
                            title="Additional info"
                            description="You're almost there. We just need some additional information before we can compare your quotes."
                            v-ref:head5
                            :valid="valid.step5"
                            :locked="isLocked"
                            :loading="isSending"
                    >
                        @include('test.steps.step5')

                    </wizard-slider-step>
                </div>
            </div>

            {{--====== STEP 6 - COMPANIES ========= --}}
            <div class="row">
                <div class="col-xs-12 comparestep6">

                    <wizard-slider-step
                            title="Companies"
                            description="Choose a company"
                            v-ref:head6
                            :valid="valid.step6"
                            :locked="isLocked"
                            :loading="isSending"
                    >
                        @include('test.steps.step6')

                    </wizard-slider-step>

                </div>
            </div>

            {{-- ====== STEP 7 - PAYMENT ========= --}}
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-push-2 comparestep7">

                    <wizard-slider-step
                            title="Complete your booking"
                            {{--description="Stripe payment"--}}
                            v-ref:head7
                            :valid="valid.step7"
                            :locked="isLocked"
                            :loading="isSending"
                    >
                        @include('test.steps.step7')

                    </wizard-slider-step>
                </div>
            </div>
            {{-- ====== STEP 8 - FINAL STEP ========= --}}
            <div class="col-xs-12 col-md-8 col-md-push-2">

                <wizard-slider-step
                        title="Final"
                        {{--description="Review"--}}
                        v-ref:head8
                        :valid="valid.step8"
                        :locked.sync="isLocked"
                >
                    <div class="row text-center">
                        <div class="col-xs-12">
                            <h2>Thank you for your order!</h2>
                            <p>An email with your order was sent to you and to the cleaning company. Wait for their
                                connection!</p>


                        </div>
                        <div class="col-xs-12">

                            <div class="fb-share-button" data-href="http://compare.ofertiko.com" data-layout="button_count" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fcompare.ofertiko.com%2F&amp;src=sdkpreparse">Share on facebook</a></div>

                        </div>
                        <div class="col-xs-12">
                            <a class="btn btn-success btn-lg" href="/">Go to Home</a>
                        </div>
                    </div>
                </wizard-slider-step>

            </div>
        </wizard>
    </div>


    <footer>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 footer-text text-left" >
                    Questions? Call {{ env('SITE_PHONE') }}
                </div>
            </div>
        </div>

    </footer>

@endsection
