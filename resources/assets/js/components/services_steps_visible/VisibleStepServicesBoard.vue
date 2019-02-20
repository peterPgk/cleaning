<template>
    <div>
        <vf-form ajax
                 action="/admin/services/update"
                 method="POST"
                 v-ref:services
        >

            <visible-step-services-category
                    v-for="category in categories"

                    :category="category.additional"
                    :category-name="category.text"
                    :curr-step="currStep"
                    :show-next="showNext"
                    :iteration="$index"
                    :info="info"
            ></visible-step-services-category>

        </vf-form>

    </div>
</template>

<script type="text/babel">
    /**
     * Тук се подават основните категории услуги, като масив
     * Логиката за преминаването м/у стъпките се извършва при
     * евенти от children компонентите
     *
     */

    import VisibleStepServicesCategory from './VisibleStepServicesCategory.vue'

    export default {
        components: {
            VisibleStepServicesCategory
        },

        props:{
            categories: {
                type: Array,
                default: () => []
            },

            /**
             * Линка къде да субмитва
             */
            action: {
                type: String,
                default: ''
            },

            /**
             * TODO: За сега, после ще се взема по някакъв начин от
             * категориите
             */
            info: null
        },

        data() {
            return {
                currStep: 1
            }
        },

        computed: {
            /**
             * Колко ще са слайдовете
             * В StepServicesCategory разделяме всяка категория услуги на 2
             * в зависимост от това дали е от тип екстра 'is_extra' или е категория
             * от основни услуги - затова * 2
             *
             * @returns {number}
             */
            steps () {
                return _.size(this.categories) * 2
            },

            /**
             * Дали може да се слайдне към предишна стъпка
             * @returns {boolean}
             */
            showPrev () {
                return this.currStep > 1;
            },

            /**
             * Дали може да се слайдне към следваща стъпка
             * @returns {boolean}
             */
            showNext () {
                return this.currStep < this.steps
            }
        },

        methods: {
            /**
             * Преминаване на следваща стъпка
             */
            next () {


                if ( this.currStep === 1 ) {

                    /**
                     * Не знам защо, ако не поставим обект в историята когато
                     * компонента се появи за първи път, първата стъпка после
                     * липсва при back.
                     * Тъй като няма евент, който да се изпълни, когато компонента
                     * става визибъл, за сега го правя така
                     * TODO: Да го фиксна
                     */
                    window.history.pushState({
                        inner:this.currStep
                    }, null);
                }

                window.history.pushState({
                    inner:this.currStep
                }, null);

                this.currStep++

            },

            /**
             * Преминаване на предишна стъпка
             */
            prev () {
                this.currStep--;
            }
        },

        events: {
            /**
             * Слушаме за евент от чайлд компонент
             */
            'go-next' () {
                this.showNext && this.next();
            },

            'inner-prev-step' () {
                this.showPrev && this.prev();
            }
        }
    }
</script>

<style lang="sass" scoped>
</style>