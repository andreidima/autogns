/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');
import './bootstrap';

import '../sass/app.scss'
import '../css/andrei.css'


import { createApp } from 'vue/dist/vue.esm-bundler.js'

// import App from './App.vue'

// createApp(App).mount("#app")

// window.Vue = require('vue').default;
// window.Vue = import ('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import VueDatepickerNext from './components/DatePicker.vue';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// if (document.querySelector('#app')) {
//     const app = new Vue({
//         el: '#app',
//     });
// }


const programari = createApp({
    data() {
        return {
            // message: 'Hello root Component 1'
        };
    },
    components: {
        'vue-datepicker-next': VueDatepickerNext,
        // 'example-component-2': VueDatepickerNext,
    },
});

if (document.getElementById('programari') != null) {
    programari.mount('#programari');
}



const programariForm = createApp({
    data() {
        return {
            programari: programariVechi,

            programari_lista_autocomplete: [],

            client: clientVechi,
            telefon: telefonVechi,
            email: emailVechi,
            masina: masinaVechi,
            nr_auto: nr_autoVechi,


            // message: 'Hello root Component 1'
        };
    },
    components: {
        'vue-datepicker-next': VueDatepickerNext,
        // 'example-component-2': VueDatepickerNext,
    },
    methods: {
        // autocomplete($value) {
        autocomplete() {
            // console.log(this.nr_auto);
            this.programari_lista_autocomplete = [];
            // nr_auto = this.nr_auto;
            // var nume_camp = this.nume_camp;
            // var valoare_camp = this.valoare_camp.split(/[\s,]+/).pop(); // se imparte stringul dupa virgule, si se ia ultimul element
            // var camp = $value;
            // var camp = '';
            // console.log(nume_camp);
            // if (autor_autocomplete.length > 2) {
            for (var i = 0; i < this.programari.length; i++) {
                // console.log(this.carti[i][nume_camp]);
                if (this.programari[i].nr_auto) {
                    if (this.nr_auto) {
                        if (this.programari[i].nr_auto.toLowerCase().includes(this.nr_auto.toLowerCase())) {
                            this.programari_lista_autocomplete.push(this.programari[i]);
                        }
                    }
                }
            }
            // }
        },
    }
});

if (document.getElementById('programariForm') != null) {
    programariForm.mount('#programariForm');
}
