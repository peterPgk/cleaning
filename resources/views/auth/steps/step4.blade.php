{{-- ====== STEP 5 - STRIPE DETAILS ========= --}}
<div class="col-xs-12">

    <stripe-form
            :additional-payload="shared"
            :must-confirm="true"
            {{--stripe-key="pk_test_htf6wr2J9QQv5ZLsbWJUWpes"--}}
            stripe-key="{{env('STRIPE_PUBLIC')}}"
            url="{{ $action }}"
            :hide-btn-on-submit="isSending"
            btn-text="Complete Payment"
    >
        <p slot="conditions" class="small-text">
            By clicking the "Complete Payment" button below, you agree to our Terms of Use and Privacy Statement. {{ env('SITE_NAME') }} will automatically
            continue your subscription package and charge the subscription package fee (currently £@{{ _currentPrice }}) to your payment method on a monthly
            basis until you cancel.
        </p>
    </stripe-form>

</div>