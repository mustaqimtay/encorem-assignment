import { createWebHistory, createRouter } from "vue-router";
import Login from "./components/Login.vue";
import Appointment from "./components/Appointment.vue";




const routes = [
  {
    path: "/appointment",
    name: "appointment",
    component: Appointment,
  },
 
  {
    path: "/login",
    component: Login,
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
const publicPages = ['/login'];
const authRequired = !publicPages.includes(to.path);
const loggedIn = localStorage.getItem('user');

// trying to access a restricted page + not logged in
// redirect to login page
if (authRequired && !loggedIn) {
    next('/login');
} else {
    next();
}
});

export default router;