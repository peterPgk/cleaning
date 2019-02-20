<template>
    <div>
        <vf-form ajax
                 :action="action"
                 method="POST"
                 v-ref:step{{step}}
        >
            <div class="row">

                <div class="col-xs-12 col-md-8 col-md-push-2">
                    <postcode v-for="code in codes" :postcode="code"></postcode>

                    <button-container>
                        <vf-submit></vf-submit>
                    </button-container>

                </div>

            </div>
        </vf-form>
    </div>
</template>

<script>

    import ButtonContainer from './accordion_wizard/ButtonContainer'

    export default {

        components: {
            ButtonContainer,
            postcode: {
                template: `
                    <div class="row">
                        <div class="col-xs-3">
                            <label>{{ postcode.name }}</label>
                        </div>
                        <div class="col-xs-1">
                            <input type="checkbox" name="chk_name" v-model="isSelected">
                        </div>

                        <div class="col-xs-8 form-inline" v-if="isSelected">
                           £ <vf-number
                                :name="name"
                                :value="val"
                            >

                            </vf-number>
                        </div>
                    </div>
                `,
                props: {
                    postcode: {
                        type: Object,
                        default: () => {},
                        required: true
                    }
                },

                data () {
                    return {
                        isSelected: false
                    }
                },

                computed: {
                    name () {
//                        return `${this.postcode.id}`
                        return `${this.postcode.text}`
                    },

                    chk_name () {
                        return `chk_${this.name}`;
                    },

                    val () {
                        return this.postcode.price || 0;
                    }
                },

                ready () {
                    this.isSelected = this.postcode.selected
                }
            }
        },

        props: {
            codes: {
                type: Array,
                default: () => []
            },

            action: {
                type: String,
                required: true,
            },

            step: {
                type: String,
                required: true,
                default: 1
            }
        },

        data () {
            return {}
        }
    }
</script>

<style lang="scss" scoped>

</style>