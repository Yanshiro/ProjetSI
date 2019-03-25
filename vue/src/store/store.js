import Vue from 'vue';
import Vuex from 'vuex';


Vue.use(Vuex)

export const state = {
    lodding: false,
    data: []
}

export const mutations = {
    changeLodding(state, value) {
        state.lodding = value;
    },
    addData(state, value) {
        state.data.push(value);
    },
    setData(state, value) {
        state.data = value;
    },
    removeData(state, value) {
        state.data.splice(state.data.indexOf(value), 1);
    }
}

export const getters = {
    lodding: (state) => state.lodding,
    data: (state) => state.data
}

export default new Vuex.Store({
    state,
    mutations,
    getters,
    strict: true
})