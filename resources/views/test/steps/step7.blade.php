{{-- ====== STEP 7 - STRIPE DETAILS ========= --}}

<style>
    .subscribe.btn {
        display: none;
    }
</style>

<div class="row">
    {{-- LEFT COLUMN --}}
    <div class="col-xs-12 col-md-7">

        <div class="row">
            <div class="col-xs-12">
                @{{ getClient.firstname }}, we just need a few more details to complete your booking
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h3 class="payment-slogan">Text goes here</h3>
            </div>
        </div>

        <vf-form client
                 v-ref:clientAdditional
                 :options="client_form_options"
        >

            <div class="row">

                <div class="col-xs-12 col-md-6">
                    {{--Flat --}}
                    <vf-text label="Flat (optional)"
                             name="flat"
                             :value="mapData.flat"
                             v-ref:flat
                             type="text"
                             placeholder="Flat"
                    >
                    </vf-text>
                </div>

                <div class="col-xs-12 col-md-6">

                    {{--Address --}}
                    <vf-text label="Address"
                             name="address"
                             :value="mapData.address"
                             v-ref:address
                             type="text"
                             placeholder="Address"
                             required
                    >
                    </vf-text>

                </div>

            </div>

            <div class="row">

                <div class="col-xs-12 col-md-6">

                    {{--Address 2 --}}
                    <vf-text label="Address 2"
                             name="address_2"
                             :value="mapData.address_2"
                             v-ref:address_2
                             type="text"
                             placeholder="Address 2"
                             required
                    >
                    </vf-text>

                </div>

                <div class="col-xs-12 col-md-6">
                    {{--town --}}
                    <vf-text label="Town"
                             name="town"
                             :value="mapData.town"
                             v-ref:town
                             type="text"
                             placeholder="Town"
                             required
                    >
                    </vf-text>
                </div>
            </div>

            <div class="row">

                <div class="col-xs-12 col-md-6">

                    {{--County --}}
                    <vf-text label="County"
                             name="county"
                             :value="mapData.county"
                             v-ref:county
                             type="text"
                             placeholder="County"
                             required
                    >
                    </vf-text>

                </div>

                <div class="col-xs-12 col-md-6">

                    {{-- Postcode --}}
                    <label for="postcode">Post code</label>
                    <input type="text"
                           class="form-control"
                           id="postcode"
                           placeholder="Post code"
                           disabled
                           {{--TODO: Да се оправя с mapData --}}
                           v-model="shared.postcode"
                    />
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12">
                    <stripe-form
                            {{--:additional-payload="selectedData"--}}
                            :additional-payload="shared"
                            {{--stripe-key="pk_test_htf6wr2J9QQv5ZLsbWJUWpes"--}}
                            stripe-key="{{ env('STRIPE_PUBLIC') }}"
                            url="/customers/step7"
                            btn-text="Pay Now Securely"
                            :hide-btn-on-submit="isSending"
                    >
                    </stripe-form>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-inline">
                    <input type="checkbox" id="agree" v-model="valid.agree"/> <label for="agree ">I agree to xxx</label>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-inline">
                    <input type="checkbox" id="agree2" v-model="valid.agree2"/> <label for="agree2">I agree to compare.ofertiko.com terms and conditions</label>
                </div>
            </div>


            <button-container-compare>
                <vf-submit text="Pay Now Securely" :disabled="!canPay" style="background-color:#EF5BA2"></vf-submit>
            </button-container-compare>


        </vf-form>

        <div class="row">
            <div class="col-xs-2">
                Image
            </div>
            <div class="col-xs-10">
                Static text
            </div>
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-xs-12 col-md-5">
        <show-services
                :company="_chosenCompany"
                :date="_serviceDate"
                :additional-data="_firmData"
        ></show-services>
    </div>

</div>
