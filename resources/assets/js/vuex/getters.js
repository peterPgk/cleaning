// export function isSending (state) {return state.sending;}
export const isSending = state => state.sending;

export function getSelected (state, item) {
    if( state.selected.hasOwnProperty(item) ) {
        return state.selected[item]
    }
    return null;
}

export const shared = state => state.sharedState;
export const stored = state => state.storage;
export const getPath = state => state.pathname;
