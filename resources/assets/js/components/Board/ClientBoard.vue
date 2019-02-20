<template>
    <div class="grid">
        <div v-if="!_emptyResult">

            <!--<div id="change">-->
            <!--<button @click="add">Add</button>-->
            <!--<button @click="replace">Replace</button>-->
            <!--</div>-->
            <!--<hr>-->

            <div class="row companies-filter">
                <div class="col-xs-2">Sort By</div>
                <div class="col-xs-4">
                    <select class="form-control" @change="sort(sortBy)" v-model="sortBy">
                        <option>price</option>
                        <option>rating</option>
                    </select>
                </div>


                <!--<button :class="[sortOption==='id' ? 'is-checked' : '']" @click="sort('price')">Sort by price</button>-->
                <!--<button @click="shuttle">Shuttle</button>-->
            </div>

            <form method="post" action="{{path}}" @submit.prevent="onSubmit()" v-el:clientForm>
                <!-- pgk:start Това бяха подуслуги, няма да се използва -->
                <!--<div class="form-inline">-->
                <!--<div v-for="add_service in _additional" class="form-group">-->
                <!--<div class="checkbox">-->
                <!--<label for="{{add_service.id}}">-->
                <!--<input type="checkbox" name="{{add_service.id}}" value="{{add_service.id}}" id="{{add_service.id}}" v-model="additional" @click="refresh('price')"/>-->
                <!--{{add_service.name}}-->
                <!--</label>-->
                <!--</div>-->
                <!--</div>-->
                <!--</div>-->
                <!-- pgk:end -->

                <!--<div id="filter">-->
                <!--<div>-->
                <!--<input type="text" v-model="filterText" placeholder="no filter">-->
                <!--<button :class="[filterOption==='filterByText' ? 'is-checked' : '']" @click="filter('filterByText')">-->
                <!--Filter-->
                <!--</button>-->
                <!--</div>-->
                <!--<button :class="[filterOption==='isEven' ? 'is-checked' : '']" @click="filter('isEven')">Filter Even-->
                <!--</button>-->
                <!--<button :class="[filterOption==='isOdd' ? 'is-checked' : '']" @click="filter('isOdd')">Filter Odd</button>-->
                <!--<button @click="filter()">Unfilter</button>-->
                <!--</div>-->
                <!--<hr>-->

                <!--<hr>-->
                <!--<div>-->
                <!--<div v-if="selected">-->
                <!--<input type="text" name="" v-model="selected.name">-->
                <!--<br>-->
                <!--<input type="text" name="" v-model="selected.id">-->
                <!--</div>-->
                <!--</div>-->

                <!--<hr>-->

                <div class="row text-center companies-header">
                    <div class="col-xs-2">Company</div>
                    <div class="col-xs-2">Reviews</div>
                    <div class="col-xs-2">Liability insurance</div>
                    <div class="col-xs-1">Date</div>
                    <div class="col-xs-2">Cleaning guarantee</div>
                    <div class="col-xs-1">Price</div>
                    <div class="col-xs-2"></div>
                </div>

                <div class="row">
                    <!--<div v-isotope-for="company in _companies" :options='isotopOptions' @click="select(company)">-->
                    <div v-isotope-for="company in _companies" :options='isotopOptions'>
                        <board-element
                                :element="company"
                                :price.sync="company.price"
                                :selected="selected && selected.id==company.id"
                        ></board-element>
                    </div>
                </div>

                <hr>

                <button-container-compare>
                    <button class="btn btn-primary btn-default pull-right" type="submit" style="background-color:#EF5BA2">Next</button>
                </button-container-compare>

                <!--<div class="row trans">-->
                    <!--<div class="col-xs-12">-->
                        <!--<button class="btn btn-primary btn-default pull-right" type="submit">Next</button>-->
                    <!--</div>-->
                <!--</div>-->

            </form>

        </div>

        <div v-if="_emptyResult" class="text-center">
            <h3>Sorry, we cannot find companies, based on provided criteria</h3>
            <h5>Go back several steps and change some </h5>
        </div>

    </div>

</template>

<script>

    import BoardElement from './BoardElement'
    import ButtonContainerCompare from '../accordion_wizard/ButtonContainerCompare'

    export default {
        components: {
            BoardElement,
            ButtonContainerCompare
        },

        props: {
            companies: {
                type: [Array],
                required: true,
                'default': () => []
            },

            path: {
                type: String,
                required: true,
                'default': '#'
            },

            /**
             * Предварително избраните от потребителя допълнителни услуги
             * Използва се при ресет на поръчка
             * (чекбоксовете)
             */
            selectedServices: {
                type: Array,
                'default': () => []
            },

            /**
             * Продължителност на анимациите
             * За сега го прилагам само на основния борд,
             * TODO: Да се напрви и за BoardElement, при показването на допълнителната информация
             */
            duration: {
                type: Number,
                'default': 300
            }
        },

        computed: {
            /**
             * making some changes over
             */
            _companies () {

                return _.map(_.assign({}, this.companies), function (company) {

//                    let rp = this.regionPrice(company.region);

                    return _.assign({}, company, {
                        //Запазваме цената за транспорт за да не я пресмятаме отново в елемента
//                        regionPrice: rp,
                        //За да може да се сортира правилно трябва да пресметнем цената тук :(
                        price: this.allServicesPrice(company.services)
                        /**
                         * pgk:start Това бяха подуслуги, които сега няма да се използват
                         *
                         additional: _.flatten(_.map(company.services, 'additional')),
                         */
                    })
                }.bind(this));
            },

            /**
             * pgk:start - Това бяха подуслуги, които сега няма да се използват
             * Вадим допълнителните услуги
             *
             * @returns {Array|*}
             * @private
             *
             *
             _additional () {

                    return _.uniqBy(_.flatten(_.map(this._companies, 'additional')), 'id')
                },
             */

            /**
             * Намерили ли сме фирми, на база на предоставените клритерии
             */
            _emptyResult () {
                return _.size(this._companies) === 0
            },

            interval () {
                return this.duration / 60
            }
        },

        data () {
            return {
                /**
                 * Пазим избраната фирма
                 */
                selected: null,
                /**
                 * Инпут за филтриране
                 */
                filterText: "",

                sortBy: '',

                /**
                 * pgk:start - Това бяха подуслуги, които сега няма да се използват
                 * Мапваме избраните допълнителни услуги

                 additional: [],
                 */

                /**
                 *
                 */
                isotopOptions: {
                    layoutMode: 'vertical',
                    getSortData: {
                        id: "id",
                        name: function (itemElem) {
                            return itemElem.name.toLowerCase();
                        },
                        price: function (itemElem) {
                            return itemElem.price;
                        },
                        rating: function (itemElem) {
                            //hack - да видя как да подам сорт ордер
                            return 6 - itemElem.rating;
                        }
                    },

                    getFilterData: {
                        filterByText: function (itemElem, vm) {
                            return itemElem.name.toLowerCase().includes(vm.filterText.toLowerCase());
                        },
                        /**
                         * pgk:start - Това бяха подуслуги, които сега няма да се използват


                         hasAdditionalService (itemElem) {
                            console.log( itemElem, 'hasAdditionalService ' );
                            let additional = _.map(itemElem.additional, _.property('id'));

                            return _.every(this.additional, (el) => {

                                return _.includes(additional, el)
                            })

                            // Another way

                                if( this.additional.length == 0 )
                                    return true;
                                return _.intersection(this.additional, additional).length > 0;

                        }

                         */


                    }
                }
            }
        },

        methods: {
            /**
             *
             * @param services
             * @returns {*}
             */
            allServicesPrice (services) {
                return _.reduce(services, (sum, el) => {

                    /**
                     * pgk:start - Това бяха подуслуги, които сега няма да се използват

                     return sum + _.toFinite(el.sum_price) + this.additionalPrice(el.additional, el);
                     */
                    return sum + _.toFinite(el.sum_price);
                }, 0)
            },
            /**
             * Допълнителна цена ако се налага пътуване до регион
             * По задание фирмите трябва да пишат регионите в които работят
             * дори и с цена 0
             *
             * @returns {*}
             * @private
             */
//            regionPrice (regions) {
//
//                return _.reduce(regions, function (sum, n) {
//                    return sum + parseFloat(n.price);
//                }, 0);
//            },

            /**
             * pgk:start - Това бяха подуслуги, които сега няма да се използват
             *
             * TODO: Това използва ли се вече ???
             * @param company_prices
             * @param el
             * @returns {*}
             *
             *
             additionalPrice (company_prices, el) {

                return _.reduce(company_prices, (sum, el) => {
                    return (_.includes(this.additional, el.id)) ? sum + _.toFinite(el.price) : sum + 0
                }, 0)
            },
             */


            /**
             *
             * @param company
             */
            select (company) {
                this.selected = _.eq(this.selected, company) ? null : company;
            },

            onSubmit () {
                if (_.isNil(this.selected)) {
                    alert('TODO: You must select a company');
                    return false;
                }

                this.$dispatch('clientBoard::sending');

                /**
                 * pgk:start - additifonal data вече не се използва
                 */
                let form = this.$els['clientform'];

                let data = {
//                    'additional': $(form).serializeArray(),
                    'company': this.selected
                };

                this.$http.post(this.path, [JSON.stringify(data)])
                    .then(function (response) {
                            this.$dispatch('clientBoard::sent', data)
                        },
                        function (error) {
                            this.$dispatch('clientBoard::error', error);
                            console.log(error, 'send client form ERROR');
                        })
            },

            add: function () {
                /**
                 * Add new element
                 */
                this.companies.push({
                    name: 'Juan',
                    id: '87'
                });
            },
            /**
             * Replace all elements
             */

            replace: function () {
                this.companies = [{
                    name: 'Edgard',
                    id: "88"
                }, {
                    name: 'James',
                    id: "90"
                }]
            },
            sort: function (key) {
                console.log(key, 'sort');
                this.isotopeSort(key);
            },
            filter: function (key) {
                this.isotopeFilter(key);
            },
            shuttle: function () {
                this.isotopeShuttle();
            },
            refresh: function () {
                /**
                 * Може да сме селектирали компания, но тя да бъде филтриране
                 * @type {null}
                 */
                this.selected = null;
//                this.isotopeFilter('hasAdditionalService')
                this.isotopeSort('price')
            }
        },

        events: {
            /**
             * При показване/скриване на допълниителната информация
             * се анимират останалите елементи
             */
            'boardElement::toggleInfo' () {
//                let temp = 0,
//
//                    i = setInterval(() => {
//                        this.isotopeLayout()
//
//                        if (temp++ === this.interval) {
//                            clearInterval(i);
//                        }
//
//                    }, 60);

                /**
                 * Понеже за сега не анимирам показването/скриването на
                 * допълнителната информация на фирмите, може със това,
                 * ако се направи анимация трябва да се използва Интервал
                 */
                setTimeout(() => {
                    this.isotopeLayout()
                }, 0);

            },

            'boardElement::choose' (company) {
                this.selected = _.eq(this.selected, company) ? null : company;
            }
        },

        ready () {
            /**
             *
             * pgk:start - Additional услуггите няма да се използват вече
             * Но това да не би да е нещо друго
             *
             */
//            if (!_.isEmpty(this.selectedServices)) {

//                console.log( this.selectedServices, 'ready' );
//                this.additional = this.selectedServices;
//            }
        }

    }
</script>

<style scoped>
    form, .trans {
        transition: all .3s ease;
    }

    .item {
        width: 100%;
        margin-bottom: 8px;
        box-sizing: border-box;
    }

</style>