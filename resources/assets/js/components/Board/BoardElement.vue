<template>
    <div class="board-element panel panel-default {{_class}}">

        <div class="panel-heading accordion-toggle">

            <div class="row text-center">

                <div class="col-xs-2"><img class="img-responsive" :src="_img"></div>
                <!--<h5 class="col-xs-3">{{ element.name }}</h5>-->
                <div class="col-xs-2 text-center point"><span @click="modal">{{{ _rating }}}</span></div>
                <!--<div class="col-xs-1 text-center"><i class="fa {{ _liability }}" aria-hidden="true"></i></div>-->
                <div class="col-xs-2 text-center">{{{ _cover }}}</div>
                <div class="col-xs-1">{{ element.year }}</div>
                <div class="col-xs-2">{{{ _quarantee }}}</div>
                <div class="col-xs-1 text-center">£{{ _price }}</div>
                <div class="col-xs-2">
                    <!--<button class="btn btn-success" @click.prevent.stop="showInfo()">{{_btnName}}</button>-->
                    <button class="btn btn-success" @click.prevent="onChoose()">Reserve Now</button>
                </div>

            </div>

        </div>

        <!--<div class="panel-collapse" transition="collapse" v-show="show">-->
        <!--<div class="panel-collapse" transition="expand" v-show="show">-->
        <!--<div class="panel-body" transition="bounce" v-show="show">-->
        <div class="panel-body" v-show="show">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12">
                        <h5><a href="/company/{{element.id}}" target="_blank">{{element.name}}</a></h5>
                    </div>

                    <div class="col-xs-12">
                        <!--<services-list :services="element.services" :show-price="true"></services-list>-->
                    </div>

                    <div class="col-xs-12" v-if="element.regionPrice != 0">
                        <h4>Additional</h4>
                        <div class="row">
                            <div class="col-xs-3">Go to region</div>
                            <div class="col-xs-1">1</div>
                            <div class="col-xs-1">£{{element.regionPrice}}</div>
                            <div class="col-xs-2">£{{element.regionPrice}}</div>
                        </div>
                    </div>

                    <hr>
                    <div class="col-xs-12">
                        <div class="row">
                            <h3 class="col-xs-5 text-right">Total</h3>
                            <h3 class="col-xs-2">£{{element.price}}</h3>
                        </div>
                    </div>
                    <hr>
                    <div class="col-xs-12">

                        <p><a href="/company/{{element.id}}" target="_blank">SHOW PAGE</a></p>

                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto asperiores illo iusto minus
                        odit, officia repudiandae rerum saepe sequi tenetur, totam vel veritatis voluptatum. Autem excepturi
                        libero molestias ratione veniam.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto asperiores illo iusto minus
                        odit, officia repudiandae rerum saepe sequi tenetur, totam vel veritatis voluptatum. Autem excepturi
                        libero molestias ratione veniam.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto asperiores illo iusto minus
                        odit, officia repudiandae rerum saepe sequi tenetur, totam vel veritatis voluptatum. Autem excepturi
                        libero molestias ratione veniam.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto asperiores illo iusto minus
                        odit, officia repudiandae rerum saepe sequi tenetur, totam vel veritatis voluptatum. Autem excepturi
                        libero molestias ratione veniam.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto asperiores illo iusto minus
                        odit, officia repudiandae rerum saepe sequi tenetur, totam vel veritatis voluptatum. Autem excepturi
                        libero molestias ratione veniam.

                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto asperiores illo iusto minus
                        odit, officia repudiandae rerum saepe sequi tenetur, totam vel veritatis voluptatum. Autem excepturi
                        libero molestias ratione veniam.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto asperiores illo iusto minus
                        odit, officia repudiandae rerum saepe sequi tenetur, totam vel veritatis voluptatum. Autem excepturi
                        libero molestias ratione veniam.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto asperiores illo iusto minus
                        odit, officia repudiandae rerum saepe sequi tenetur, totam vel veritatis voluptatum. Autem excepturi
                        libero molestias ratione veniam.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto asperiores illo iusto minus
                        odit, officia repudiandae rerum saepe sequi tenetur, totam vel veritatis voluptatum. Autem excepturi
                        libero molestias ratione veniam.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto asperiores illo iusto minus
                        odit, officia repudiandae rerum saepe sequi tenetur, totam vel veritatis voluptatum. Autem excepturi
                        libero molestias ratione veniam.

                    </div>
                </div>
            </div>
        </div>


        <modal
                :large="true"
                :forse="true"
                cancel-class="hidden"
                title="element.name"
                :show.sync="showModal"
                @ok="modal"
        >
            <div slot="header"><h3>{{ element.name }} reviews</h3></div>
            <rating
                :company="element"
            ></rating>
        </modal>


    </div>


</template>

<script>

//    import ServicesList from './../services/ServicesList'
    import Modal from 'vue-bootstrap-modal'
    import Rating from './../RatingShow'

    export default {

        components: {
//            ServicesList,
            Modal,
            Rating
        },

        props: {
            element: {
                type: Object,
                required: true,
                default: () => ({})
            },
            selected: {
                type: Boolean,
                default: false
            },

        },

        data () {
            return {
                show: false,
                showModal: false,
                height: false,
            }
        },

        computed: {
            /**
             * Извличаме цената на услугите.
             * Това е общата цена на всички услуги и бройката + additional
             * @returns {*|number|string|isotopOptions.getSortData.price}
             * @private
             */
            _price () {
                return this.element.price.toFixed(2);
            },

            _class() {
                return this.selected ? 'selected' : ''
            },

            _btnName () {
                return this.show ? 'Hide' : 'Show'
            },

//            _liability () {
//                return (this.element.liability) ? 'fa-check' : 'fa-times';
//            },

//            _year () {
//                return moment(this.element.year).format('Y')
//            },

            _cover () {
//                return (this.element.cover != 0) ? `<span>£${this.element.cover}</span>` : `<i class="fa fa-times" aria-hidden="true"></i>`;
                return (this.element.cover != 0)
                    ? `<div><i class="fa fa-check" aria-hidden="true"></i></div><span>£${this.element.cover}</span>`
                    : `<i class="fa fa-times" aria-hidden="true"></i>`;
            },

            _quarantee () {

                return (! this.element.quarentee)
                    ? `<i class="fa fa-times" aria-hidden="true"></i>`
                    : `<div><i class="fa fa-check" aria-hidden="true"></i></div><span>${this.element.quarentee}</span>`;
            },

            _rating () {
                return `<div>${_.repeat('<i class="fa fa-star" aria-hidden="true"></i>', this.element.rating)}</div><div>from ${this.element.total_reviews} reviews.</div>`
            },

            _img () {
                return `/img/logos/${this.element.logo}`
            }

        },

        methods: {
            onChoose () {
                this.$dispatch('boardElement::choose', this.element);
            },

            modal () {
                this.showModal = !this.showModal;
            }

//            showInfo () {
//                this.show = !this.show;
//                this.$dispatch('boardElement::toggleInfo')
//            }
        },



        transitions: {
            collapse: {
                afterEnter (el) {
//                    el.style.maxHeight = '';
//                    el.style.overflow = '';
                },
                beforeLeave (el) {
                    el.style.maxHeight = el.offsetHeight + 'px';
                    el.style.overflow = 'hidden';
                    // Recalculate DOM before the class gets added.
                    return el.offsetHeight;
                }
            },
            bounce: {
//                enterClass: 'bounceInLeft',
//                leaveClass: 'bounceOutRight',
                enterClass: 'slideInLeft',
                leaveClass: 'slideOutRight',
                beforeEnter (el) {
                    console.log( el.offsetHeight, 'beforeEnter' );
                },
                afterEnter (el) {
                    console.log( el.offsetHeight, 'afterEnter' );
                    return el.offsetHeight;
                },
                beforeLeave (el) {
                    console.log( el.offsetHeight, 'beforeLeave' );
                    return el.offsetHeight;
                },
                afterLeave (el) {
                    console.log( el.offsetHeight, 'afterLeave' );
                }
            }
        }

    }
</script>

<style lang="scss" scoped>
    .accordion-toggle {
        /*cursor: pointer;*/
        display: table;
        width: 100%;
        text-aligh: left;

        & > div {
            display: table-cell;
            vertical-align: middle;
            padding: 0 2em 0 0;
        }
    }

    .selected {
        .panel-heading {
            background-color: #e8f5e9;
        }
    }

    .panel {

        .panel-body {
            position: relative;
            display: table;
            background-color: #eee;
        }
    }

    /* always present */
    .expand-transition {
        transition: all .3s ease;
        height: 464px;
        /*padding: 10px;*/
        background-color: #eee;
        overflow: hidden;
    }

    /* .expand-enter defines the starting state for entering */
    /* .expand-leave defines the ending state for leaving */
    .expand-enter, .expand-leave {
        height: 0;
        /*padding: 0 10px;*/
        opacity: 0;
    }

    .collapse-transition {
        transition: max-height .3s ease;
    }
    .collapse-leave, .collapse-enter {
        max-height: 0!important;
    }

</style>