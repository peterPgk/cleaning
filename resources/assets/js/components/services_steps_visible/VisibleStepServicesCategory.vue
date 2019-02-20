<template>
    <div>
        <visible-step-services-category-type
                v-for="(key, subcat) in _grouped"
                :categories="subcat"
                :category-name="categoryName"
                :is-extra="key"
                :step="$index"
                :curr-step="currStep"
                :show-next="showNext"
                :iteration="_iteration"
                class="col-xs-12"
                :info="info"
        >
        </visible-step-services-category-type>
    </div>

</template>

<script type="text/babel">
    /**
     * Основните категории услуги
     *
     * Получаваме категория от услуги - обект, който се
     * състои от услуги, разделени в подкатегории.
     * Всяка подкатегория има маркер, дали е екстра или е основна
     *
     */

    import VisibleStepServicesCategoryType from './VisibleStepServicesCategoryType.vue'

    export default {

        components: {
            VisibleStepServicesCategoryType
        },

        props: {
            category: {
                type: Object,
                default: ()=> {}
            },

            categoryName: String,

            iteration: {
                type: Number,
                default: 0
            },

//            currentStep: {
            currStep: {
                type: Number
            },

            showNext: {
                type: Boolean
            },
            /**
             * TODO: За сега, после ще се взема по някакъв начин от
             * категориите
             */
            info: null
        },

        data () {
            return {}
        },

        computed: {
            _grouped () {
                return _.groupBy(this.category, 'is_extra');
            },

            _size () {
                return _.size(this._grouped)
            },

            _iteration () {
                return this.iteration * _.size(this._grouped)
            }
        }
    }
</script>

<style lang="sass" scoped>
</style>