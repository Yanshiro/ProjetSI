import Vue from 'vue';
import Vuex from 'vuex';


Vue.use(Vuex)

export const state = {
    lodding: false
}

export const mutations = {
    changeLodding(state, value) {
        state.lodding = value;
    },
}

export const getters = {
    lodding: (state) => state.lodding
}

export default new Vuex.Store({
    state,
    mutations,
    getters,
    strict: true
})