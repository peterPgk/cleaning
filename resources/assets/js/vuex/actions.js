export const changeValid = ({dispatch }, ...args) => dispatch('IS_VALID', ...args);

export const nextStep = ({ dispatch, state }, ...args) => {

    // console.log( _.inRange(state.currentStep, 1, 7), 'PUSH NET STEP' );

    history.pushState( {
        // step: state.currentStep,
    }, state.currentStep.toString());

    dispatch('increment', ...args);
};
export const prevStep = ({ dispatch }, ...args) => dispatch('decrement', ...args);
export const setStep = ({dispatch}, ...args) => dispatch('setStep', ...args);
export const sending = ({ dispatch }, ...args) => dispatch('toggleSending', ...args);
export const selectItem = ({dispatch}, ...args) => dispatch('select', ...args);

/**
 * Put items into 'sharedState' object
 * @param dispatch
 * @param args
 */
export const share = ({dispatch}, ...args) => dispatch('share', ...args);
/**
 * Get item from 'sharedState' object
 * @param state
 * @param args
 */
export const getShared = ({state}, ...args) => (_.size(args) > 0 && _.has(state.sharedState, `${args[0]}`)) ? state.sharedState[`${args[0]}`] : {};
/**
 * Put item into 'storage' object
 * @param dispatch
 * @param args
 */
export const storeIn = ({dispatch}, ...args) => dispatch('store_in', ...args);
/**
 * Get item from 'storage' object
 * @param state
 * @param args
 */
export const getStored = ({state}, ...args) => (_.size(args) > 0 && _.has(state.storage, `${args[0]}`)) ? state.storage[`${args[0]}`] : {};

// export const incrementIfOdd = ({ commit, state }) => {
//     if ((state.currentStep + 1) % 2 === 0) {
//         commit('increment')
//     }
// };
//
// export const incrementAsync =  ({ commit }) => {
//     return new Promise((resolve, reject) => {
//         setTimeout(() => {
//             commit('increment')
//             resolve()
//         }, 1000)
//     })
// };