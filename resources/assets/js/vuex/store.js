import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

const state = {

    /**
     * Информация, която трябва да бъде споделена
     * между стъпките и изпратена обратно към сървъра
     */
    sharedState: {},

    storage: {
        // companies: []
    },

    currentStep: 1,
    /**
     * URL на главната стъпка
     * Използва се за преминаване м/у стъпките
     * с използването на BACK бутона на браузъра
     */
    // pathname: window.location.pathname,
    sending: false,
}

const mutations = {

    IS_VALID (state, step, is_valid) {
        state.isValid[step] = is_valid
    },

    increment (state, step = 1) {
        state.currentStep = state.currentStep + step;
    },

    decrement (state, step = 1) {
        state.currentStep = state.currentStep - step;
    },

    setStep (state, step) {
        state.currentStep = step;
    },

    toggleSending(state, condition) {
        ( condition !== undefined ) ? state.sending = condition : state.sending = !state.sending;
    },

    // select (state, item, value) {
    //     if( state.selected.hasOwnProperty(item) )
    //         state.selected[item] = value;
    // },

    share (state, item) {
        if (typeof item === 'object') {
            state.sharedState = _.assign({}, state.sharedState, item)
        }
        else {
            state.sharedState = _.assign({}, state.sharedState, {[item]: item})
        }
    },

    store_in (state, item) {
        if (typeof item === 'object') {
            state.storage = _.assign({}, state.storage, item)
        }
        else {
            state.storage = _.assign({}, state.storage, {[item]: item})
        }
    }
}

// const getters = {
//     evenOrOdd: state => state.count % 2 === 0 ? 'even' : 'odd',
//
//     getCurrentStep: state => state.currentStep,
//
// }

export default new Vuex.Store({
    state,
    mutations,
    // getters
})