window.Vue = require('vue').default;
import Vuex from 'vuex';
Vue.use(Vuex);

export const store = new Vuex.Store({
    strict: false,
    state: {
        user: null,
        origin_path: window.location.origin + '/',
        user_login_error: false,
        auth: null,
        wfee: 5,
        rewardThreshold: 5000
    },
    mutations: {
        changeUserLoginError(state, payload) {
            state.user_login_error = payload;
        },
        updateAuth(state, payload) {
            state.auth = payload;
        },
        updateAuthBalance(state, payload) {
            state.auth.balance = payload;
        },
        updateAuthRewardBalance(state, payload) {
            state.auth.loss_reward_balance = payload;
        },
        wfeeUpdate(state, payload) {
            state.wfee = payload;
        },
        rewardThresholdUpdate(state, payload) {
            state.rewardThreshold = payload;
        }
    },
    actions: {
       
    }
})