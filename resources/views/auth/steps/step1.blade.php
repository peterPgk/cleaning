{{-- ====== STEP 1 - PRICING TABLE ========= --}}
<div class="authstep1">
    <vf-form ajax
             action="/company/signup/step1"
             method="POST"
             v-ref:step1
    >

        <pricing-table
                :price-data="pricingData"
                :selected="mapData.plan"
        ></pricing-table>

        <button-container>
            <vf-submit text="Next"></vf-submit>
        </button-container>

    </vf-form>
</div>