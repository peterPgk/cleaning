<template>
    <div class="questions">
        <div v-for="question in data">
            <question :question="question"></question>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {

        components: {

            'question': {
                template: `
                    <div class="row form">
                        <div class="col-xs-6">
                            {{question.question}}
                        </div>

                        <div v-if="!hasAnswer" class="col-xs-6">
                            <vf-text
                                :name="_name"
                                v-ref:_name
                            ></vf-text>
                        </div>


                        <div v-if="hasAnswer" class="col-xs-6">
                            <vf-select
                                :name="_name"
                                v-ref:_name
                                :items="_answers"
                            ></vf-select>
                        </div>

                    </div>
                   `,

                props: {
                    question: {
                        type: [Object],
                        default: {
                            id: 0,
                            question: 'First question',
                            answer: ''
                        }
                    }
                },

                data() {
                    return {}
                },

                computed: {
                    /**
                     * Ако има отговори, които да предварително зададени
                     * дисплейваме като селект
                     *
                     * @returns {boolean}
                     */
                    hasAnswer () {
                        return !_.isEmpty(this.question.answer)
                    },

                    /**
                     * Екстрактваме отговорите
                     *
                     * @returns {Array}
                     * @private
                     */
                    _answers () {
                        return _.map(this.question.answer, (ans, idx) => {
                            return {id: idx, text: ans}
                        })
                    },

                    /**
                     * @returns {string}
                     * @private
                     */
                    _name () {
                        return `q_${this.question.id}`
                    },

                }
            }
        },

        props: {
            data: {
                type: [Array],
                default: () => []
            }
        },

        data() {

            return {}

        },

    }
</script>

<style lang="sass" scoped>
</style>