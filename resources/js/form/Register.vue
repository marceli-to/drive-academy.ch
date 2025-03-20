<template>
  <template v-if="isSent">
    <feedback />
  </template>
  <template v-else>
    <validation-errors 
      :validationErrors="validationErrors"  
      v-if="validationErrors.length > 0">
    </validation-errors>
    <form v-if="isFetched">
      <form-grid>
        <form-group class="col-span-6">
          <select v-model="form.option" class="border-0 px-10 lg:py-15 w-full font-unineue-black bg-[center_left_.4rem] bg-[url('/img/chevron-down.svg')] bg-[length:20px_10px] pl-35 ring-0 focus:ring-0 outline-none">
            <option v-for="option in options" :key="option" :value="option">{{ option }}</option>
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
          :class="[isValid && !isLoading ? 'bg-ocean text-white hover:bg-black transition-colors' : 'opacity-50 pointer-events-none select-none', 'bg-ocean font-unineue-black text-white uppercase py-15 px-20 leading-none inline-flex items-center w-auto text-left']"
          type="button"
          @click.prevent="submit()">
          Absenden
        </button>
      </form-group>
    </form>
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
        option: 'Fahrstunden Neulenker',
        event: null,
        firstname: null,
        name: null,
        phone: null,
        email: null,
        message: null,
      },

      options: [
        'Fahrstunden Neulenker',
        'Kontrollfahrten',
        // 'VKU/Nothelferkurs',
      ],

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
          this.options = [...this.options, ...this.events.map(event => event.title)];
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
        option: 'Fahrstunden Neulenker',
        firstname: null,
        name: null,
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
        this.form.option &&
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