{{-- ====== STEP 4 - EXTRA SPECIFIC ========= --}}

{{--<vf-form client--}}
<vf-form ajax
         action="/customers/step4"
         {{--action=""--}}
         method="POST"
         v-ref:step4
>
    <div class="row">
        <div class="col-xs-12">
            <div class="">

            <services-form
                    :services="shared.ssrv"
                    :selected="_selectedServices"
                    :is-extra="true"
            ></services-form>

            </div>
        </div>
    </div>

    <button-container-compare>
        <vf-submit text="CONTINUE" style="background-color:#EF5BA2"  onclick="ga('send', 'event', 'Comparison', 'Step', 'Step 3');"></vf-submit>
    </button-container-compare>

</vf-form>
