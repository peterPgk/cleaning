<template>
    <span>
      <span v-el:trigger>
        <slot></slot>
      </span>
        <div v-el:popover v-if="show" style="display:block;"
             :class="['tooltip',placement]"
             :transition="effect"
        >
            <div class="tooltip-arrow"></div>
            <div class="tooltip-inner">
                <slot name="content">{{{content}}}</slot>
            </div>
        </div>
    </span>
</template>

<script>

    export default {
        props: {
            title: {
                type: String
            },
            content: {
                type: String
            },
            header: {
                type: Boolean,
                default: true
            },
            placement: {
                type: String,
                default: 'top'
            },
            trigger: {
                type: String,
                default: 'hover'
            },
            effect: {
                type: String,
                default: 'scale'
            }
        },
        data () {
            return {
                position: {
                    top: 0,
                    left: 0
                },
                show: false
            }
        },
        methods: {
            toggle (e) {
                if (e && this.trigger === 'contextmenu') e.preventDefault()
                if (!(this.show = !this.show)) {
                    return
                }
                try {
                    setTimeout(() => {
                        const popover = this.$els.popover
                        const trigger = this.$els.trigger.children[0]
                        switch (this.placement) {
                            case 'top' :
                                this.position.left = trigger.offsetLeft - popover.offsetWidth / 2 + trigger.offsetWidth / 2
                                this.position.top = trigger.offsetTop - popover.offsetHeight
                                break
                            case 'left':
                                this.position.left = trigger.offsetLeft - popover.offsetWidth
                                this.position.top = trigger.offsetTop + trigger.offsetHeight / 2 - popover.offsetHeight / 2
                                break
                            case 'right':
                                this.position.left = trigger.offsetLeft + trigger.offsetWidth
                                this.position.top = trigger.offsetTop + trigger.offsetHeight / 2 - popover.offsetHeight / 2
                                break
                            case 'bottom':
                                this.position.left = trigger.offsetLeft - popover.offsetWidth / 2 + trigger.offsetWidth / 2
                                this.position.top = trigger.offsetTop + trigger.offsetHeight
                                break
                            default:
                                console.warn('Wrong placement prop')
                        }
                        popover.style.top = this.position.top + 'px';
                        popover.style.left = this.position.left + 'px'
                    }, 0)
                }
                catch (err) {
                    console.log( err, 'tooltip toogle error' );
                }

            }
        },
        ready () {
            let trigger = this.$els.trigger;
            if (!trigger) return console.error('Could not find trigger v-el in your component that uses popoverMixin.')

            if (this.trigger === 'focus' && !~trigger.tabIndex) {
                trigger = $('a,input,select,textarea,button', trigger)
                if (!trigger.length) {
                    trigger = null
                }
            }
            if (trigger) {
                let events = {contextmenu: 'contextmenu', hover: 'mouseleave mouseenter', focus: 'blur focus'}
                $(trigger).on(events[this.trigger] || 'click', this.toggle)
                this._trigger = trigger
            }
        },
        beforeDestroy () {
            if (this._trigger) $(this._trigger).off()
        }
    }
</script>

<style>
    .tooltip.top,
    .tooltip.left,
    .tooltip.right,
    .tooltip.bottom {
        opacity: .9
    }

    .fadein-enter {
        animation: fadein-in 0.3s ease-in;
    }

    .fadein-leave {
        animation: fadein-out 0.3s ease-out;
    }

    @keyframes fadein-in {
        0% {
            opacity: 0;
        }
        100% {
            opacity: .9;
        }
    }

    @keyframes fadein-out {
        0% {
            opacity: .9;
        }
        100% {
            opacity: 0;
        }
    }
</style>
