import { createApp } from "vue";
// import App from "./App.vue";
// import router from "./router";

// createApp(App).use(router).mount("#app");
// Vue.config.productionTip = false;
// Vue.use(VueRouter);
// new Vue({
//   render: (h) => h(App),
//   router,
// }).mount("#app");
import App from "./App.vue";
import router from "./router";
import Vuex from "vuex";

export const store = new Vuex.Store({
  // state, mutations, actions, etc.
  state: {
    count: 0,
  },
  actions: {
    increment(context, payload) {
      context.commit("increment", payload);
    },
  },
});

const app = createApp(App);
app.use(router);
app.use(store);
app.mount("#app");
