<template>
    <template v-if="isSent">
      <feedback />
    </template>
    <template v-else>

      <validation-errors 
        :validationErrors="validationErrors"  
        v-if="validationErrors.length > 0">
      </validation-errors>

      <template v-if="isFetched">
        <form v-if="hasEvents">
          <!-- <div class="mb-20">Der <a href="https://thomai.ch/angebot/verkehrskundeunterricht/" class="text-ocean" target="_blank">VKU</a> und <a href="https://thomai.ch/angebot/nothilfekurs/" class="text-ocean" target="_blank">Nothelferkurs</a> findet bei der Fahrschule <a href="https://thomai.ch/" class="text-ocean" target="_blank">Thomai Lüthi</a> in Bauma statt.</div> -->

          <form-grid>
            <form-group class="col-span-6">
              <select v-model="form.event" class="w-full border-0 px-10 lg:py-15 w-full font-black bg-[center_left_.4rem] bg-[url('/img/chevron-down.svg')] bg-[length:20px_10px] pl-35 ring-0 focus:ring-0 outline-none">
                <option v-for="event in events" :key="event.id" :value="event.title">
                  {{ event.title }}
                </option>
              </select>
            </form-group>
          </form-grid>
          <form-grid>
            <form-group :error="errors.firstname" class="mb-15 md:mb-0">
              <form-input 
                type="text" 
                v-model="form.firstname" 
                placeholder="Vorname*"
                :error="errors.firstname"
                @blur="validateField('firstname')"
                @focus="removeError('firstname')">
              </form-input>
            </form-group>
            <form-group :error="errors.name">
              <form-input 
                type="text" 
                v-model="form.name" 
                placeholder="Name*"
                :error="errors.name"
                @blur="validateField('name')"
                @focus="removeError('name')">
              </form-input>
            </form-group>
          </form-grid>
          <form-grid>
            <form-group :error="errors.address" class="mb-15 md:mb-0">
              <form-input 
                type="text" 
                v-model="form.address" 
                placeholder="Adresse*"
                :error="errors.address"
                @blur="validateField('address')"
                @focus="removeError('address')">
              </form-input>
            </form-group>
            <form-group :error="errors.zip_city">
              <form-input 
                type="text" 
                v-model="form.zip_city" 
                placeholder="PLZ/Ort*"
                :error="errors.zip_city"
                @blur="validateField('zip_city')"
                @focus="removeError('zip_city')">
              </form-input>
            </form-group>
          </form-grid>
          <form-grid>
            <form-group :error="errors.phone" class="mb-15 md:mb-0">
              <form-input 
                type="text" 
                v-model="form.phone" 
                placeholder="Telefon*"
                :error="errors.phone"
                @blur="validateField('phone')"
                @focus="removeError('phone')">
              </form-input>
            </form-group>
            <form-group :error="errors.email">
              <form-input 
                type="email" 
                v-model="form.email" 
                placeholder="E-Mail*"
                :error="errors.email"
                @blur="validateEmail()"
                @focus="removeError('email')">
              </form-input>
            </form-group>
          </form-grid>
          <form-grid>
            <form-group class="col-span-12">
              <form-textarea 
                v-model="form.message" 
                placeholder="Mitteilung"
                :error="errors.message"
                @blur="validateField()"
                @focus="removeError('message')">
              </form-textarea>
            </form-group>
          </form-grid>
          <form-group>
            <button 
              :class="[isValid && !isLoading ? 'bg-ocean text-white hover:bg-black transition-colors' : 'opacity-50 pointer-events-none select-none', 'bg-ocean font-black text-white uppercase py-15 px-20 leading-none inline-flex items-center w-auto text-left']"
              type="button"
              @click.prevent="submit()">
              Absenden
            </button>
          </form-group>
        </form>
        <div v-else>
          <p>Zur Zeit finden keine Kurse statt.</p>
        </div>
      </template>

    </template>
</template>
<script>
import NProgress from 'nprogress';
import FormGrid from '@/form/components/form/Grid.vue';
import FormGroup from '@/form/components/form/Group.vue';
import FormLabel from '@/form/components/form/Label.vue';
import FormInput from '@/form/components/form/Input.vue';
import FormCheckbox from '@/form/components/form/Checkbox.vue';
import FormTextarea from '@/form/components/form/Textarea.vue';
import ValidationErrors from '@/form/components/form/ValidationErrors.vue';
import Feedback from '@/form/components/form/Feedback.vue';

export default {

  components: {
    FormGrid,
    FormGroup,
    FormLabel,
    FormInput,
    FormTextarea,
    FormCheckbox,
    ValidationErrors,
    Feedback,
  },

  data() {

    return {

      form: {
        event: null,
        firstname: null,
        name: null,
        address: null,
        zip_city: null,
        phone: null,
        email: null,
        message: null,
      },

      events: [],

      errors: {
        firstname: null,
        name: null,
        address: null,
        zip_city: null,
        phone: null,
        email: null,
        message: null,
      },

      validationErrors: [],

      routes: {
        store: '/api/form/register'
      },

      isSent: false,
      isLoading: false,
      isFetched: false,
      hasEvents: false,
    }
  },

  mounted() {
    this.getEvents();
  },

  methods: {

    getEvents() {
      this.axios.get('/api/events').then(response => {
        this.events = response.data;
        if (this.events.length > 0) {
          this.form.event = this.events[0].title;
          this.hasEvents = true;
        }
        this.isFetched = true;
      });
    },

    submit() {
      this.isSent = false;
      this.isLoading = true;
      this.validationErrors = [];
      NProgress.start();
      this.axios.post(this.routes.store, this.form).then(response => {
        NProgress.done();
        this.reset();
        this.isSent = true;
        this.isLoading = false;
      })
      .catch(error => {
        NProgress.done();
        this.isLoading = false;
        this.handleValidationErrors(error.response.data);
      });
    },

    validateField(field) {
      if (this.form[field] === null || this.form[field] === '') {
        this.errors[field] = true;
      } 
      else {
        this.errors[field] = false;
      }
    },

    validateEmail() {
      if (this.validEmail()) {
        this.errors.email = false;
        return;
      }
      this.errors.email = true;
    },

    validEmail() {
      if (this.form.email === null || this.form.email === '') {
        return false;
      }
      const rgx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!rgx.test(this.form.email)) {
        return false;
      }
      return true;
    },

    handleValidationErrors(data) {
      let errors = [];
      for (let key in data.errors) {
        this.validationErrors.push(
          data.errors[key][0]
        );
        this.errors[key] = true;
      }
    },

    removeError(field) {
      this.errors[field] = null;
    },

    reset() {
      this.form = {
        firstname: null,
        name: null,
        address: null,
        zip_city: null,
        phone: null,
        email: null,
        message: null,
      };
      this.errors = {};
    },
  },

  computed: {

    isValid() { 
      if (
        this.form.firstname &&
        this.form.name &&
        this.form.address &&
        this.form.zip_city &&
        this.form.phone &&
        this.validEmail()
      )
      {
        return true;
      }
      return false;
    },
  },
}
</script>