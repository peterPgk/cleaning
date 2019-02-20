<script>
    import WizardStep from './WizardStep'

    export default {

        extends: WizardStep,

        methods: {
            toggle () {

                if( this.locked )
                    return;

                if( this.currentStep == this.index || this.valid !== null ) {

                    this.isOpen = !this.isOpen
                    this.$dispatch('isOpenEvent', this)

                    this.locked = true
                }
            }
        },

    }
</script>
