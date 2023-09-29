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


const datepicker = createApp({
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
if (document.getElementById('datepicker') != null) {
    datepicker.mount('#datepicker');
}

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
            programariListaAutocompleteClient: [],

            client: clientVechi,
            telefon: telefonVechi,
            email: emailVechi,
            masina: masinaVechi,
            nr_auto: nr_autoVechi,
            vin: vinVechi,


            // message: 'Hello root Component 1'
        };
    },
    components: {
        'vue-datepicker-next': VueDatepickerNext,
        // 'example-component-2': VueDatepickerNext,
    },
    methods: {
        autocomplete() {
            this.programari_lista_autocomplete = [];
            for (var i = 0; i < this.programari.length; i++) {
                if (this.programari[i].nr_auto) {
                    if (this.nr_auto) {
                        if (this.programari[i].nr_auto.toLowerCase().includes(this.nr_auto.toLowerCase())) {
                            this.programari_lista_autocomplete.push(this.programari[i]);
                        }
                    }
                }
            }
        },
        autocompleteClient() {
            this.programariListaAutocompleteClient = [];
            for (var i = 0; i < this.programari.length; i++) {
                if (this.programari[i].client) {
                    if (this.client) {
                        if (this.programari[i].client.toLowerCase().includes(this.client.toLowerCase())) {
                            this.programariListaAutocompleteClient.push(this.programari[i]);
                        }
                    }
                }
            }
        },
    }
});

if (document.getElementById('programariForm') != null) {
    programariForm.mount('#programariForm');
}

// Formular adaugare manopere
const manopereFormularProgramare = createApp({
    el: '#manopereFormularProgramare',
    data() {
        return {
            // recoltariSangeProduse: recoltariSangeProduse,
            // recoltariSangeGrupe: recoltariSangeGrupe,
            // nrPungi: nrPungi,
            // nrManopere: nrManopere,
            // manopere: pungi,
            mecanici: mecanici,
            manopere: manopere,
        }
    },
    watch: {
    },
    created: function () {
        // this.adaugaManoperaGoalaInArray();
    },
    methods: {
        adaugaManoperaGoalaInArray() {
            this.manopere.push({ id: '', mecanic_id: '', denumire: '', pret: '', bonus_mecanic: '', observatii: '', constatare_atelier: '', mecanic_consumabile: '', mecanic_observatii: '' });
        },
        stergeManopera(index) {
            this.manopere.splice(index, 1);
        }
    }
});
manopereFormularProgramare.component('vue-datepicker-next', VueDatepickerNext);
if (document.getElementById('manopereFormularProgramare') != null) {
    manopereFormularProgramare.mount('#manopereFormularProgramare');
}

// Formular chestionar
const chestionar = createApp({
    el: '#chestionar',
    data() {
        return {
            manopere: manopere,
        }
    },
    watch: {
    },
    created: function () {
        this.manopere.forEach(manopera => {
            if (manopera.comentariu){
                manopera.comentariuAfisare = "da";
            }
        });
    },
    methods: {
    }
});
if (document.getElementById('chestionar') != null) {
    chestionar.mount('#chestionar');
}
