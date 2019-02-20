{{-- ====== STEP 3 - SERVICES SPECIFIC ========= --}}

{{--<vf-form client--}}
<vf-form ajax
         action="/customers/step3"
         method="POST"
         :validation="validation"
         v-ref:step3
         {{-- Добавяме опции за да може да използваме beforeSubmit() функция --}}
         :options="services_options"
>
    <div class="row">
        <div class="col-xs-12">
            <div class="">

            {{--Postcode--}}
            <vf-text label="Post code"
                     name="postcode"
                     {{--:value="shared.postcode"--}}
                     :value="mapData.postcode"
                     v-ref:postcode
                     {{--required--}}
                     type="text"
                     placeholder="Please enter your full postcode"
            >
            </vf-text>

        </div>
        </div>
    </div>

    <div class="row">

        <div class="col-xs-12">
            <div class="">
            <label class="control-label">Services</label>


                    <services-form
                            :services="shared.ssrv"
                            :selected="_selectedServices"
                            {{--:additional-taxes="mapData.additionalTaxes"--}}
                    ></services-form>

        </div>
        </div>
    </div>

    <button-container-compare >
        <vf-submit text="CONTINUE" style="background-color:#EF5BA2" onclick="ga('send', 'event', 'Comparison', 'Step', 'Step 2');"></vf-submit>
    </button-container-compare>

</vf-form>