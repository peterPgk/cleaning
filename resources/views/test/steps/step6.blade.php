{{-- ====== STEP 6 - USER ADDITIONAL INFO ========= --}}
<div class="row">
    <div class="col-xs-12">

        <client-board
                :companies="_companies"
                :selected-services="_selectedSubServices"
                path="/customers/step6"
        ></client-board>

        {{--<div class="row buttons-group">--}}
            {{--<div class="col-xs-12">--}}
                {{--<button class="btn btn-default" @click.prevent="prevStep()">Back</button>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<button-container-compare>--}}
            {{--<vf-submit text="" style="background-color:#EF5BA2"></vf-submit>--}}
        {{--</button-container-compare>--}}

    </div>
</div>