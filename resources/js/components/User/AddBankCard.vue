<template>
  <v-app id="AddBankcard"> 	
  	<v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Add Bank Card</v-toolbar-title>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">	
    	<v-container fluid>
        <v-row class="mb-15 justify-center">
          <v-form ref="form">
            <v-col cols="12"  @keyup.enter="submitBank">
              <v-text-field label="Actual Name" v-model.trim="bank.actual_name" :counter="20" required :rules="actualNameErrors" solo></v-text-field>
              <v-text-field label="IFSC Code" v-model.trim="bank.IFSC" required :rules="ifscErrors" solo></v-text-field>
              <v-text-field label="Bank Name" v-model.trim="bank.bank_name" required :rules="bankNameErrors" solo></v-text-field>
              <v-text-field label="Bank Account" v-model.trim="bank.bank_account" required :rules="bankAccountErrors" solo></v-text-field>
              <v-text-field label="State/Territory" v-model.trim="bank.state" required :rules="stateErrors" solo></v-text-field>
              <v-text-field label="City" v-model.trim="bank.city" required :rules="cityErrors" solo></v-text-field>
              <v-text-field label="Address" v-model.trim="bank.address" required :rules="addressErrors" solo></v-text-field>
              <v-text-field label="Mobile Number" v-model.trim="bank.mobile_number" required counter="10" prefix="91" :rules="mobileNumberErrors" solo></v-text-field>
              <v-text-field label="Email" v-model.trim="bank.email" required :rules="emailErrors" solo></v-text-field>
              <v-text-field label="Account phone number" v-model.trim="auth.phone" prefix="91" disabled solo></v-text-field>
              <!-- <v-text-field label="Code" placeholder="Verification Code" solo>
                <v-btn slot="append" large>OTP</v-btn>
              </v-text-field>   -->    
            </v-col>
              <v-row class="justify-center">
                <v-col md="12" lg="8">
                 <v-btn large block color="success" @click="submitBank" class="white--text">
                  <span>Submit</span>
                </v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-row>
    	</v-container>
    </v-main>	
   </v-app>
</template>
<script>
  import { mapState } from "vuex";
  export default {    
    data() {
      return {
        is_loading: false,
        bank: {
          actual_name: '',
          IFSC: '',
          bank_name: '',
          state: '',
          city: '',
          address: '',
          email: '',
          mobile_number: '',
        },
        actualNameErrors: [
          v => !!v || 'Actual name is required',
          v => (v && v.length <= 20) || 'Actual name must be less than 20 characters',
        ],
        ifscErrors: [
          v => !!v || 'IFSC CODE is required',
        ],
        bankNameErrors: [
          v => !!v || 'Bank name is required',
        ],
        bankAccountErrors: [
          v => !!v || 'Bank account is required',
        ],
        stateErrors: [
          v => !!v || 'State name is required',
        ],
        cityErrors: [
          v => !!v || 'City name is required',
        ],
        addressErrors: [
          v => !!v || 'Address is required',
        ],
        mobileNumberErrors: [
          v => !!v || 'Please enter your phone number',
          v => (v && v.length == 10) || 'Please enter a valid number',
        ],
        emailErrors: [
          v => !!v || 'E-mail is required',
          v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        ],
      }
    },   
    computed: {
      ...mapState([
        "auth",
      ]),
    }, 
    mounted() {
      console.log('Component mounted.')
    },
    methods: {
      hasHistory () {
        return window.history.length > 2 
      },
      validate () {
        return this.$refs.form.validate();
      },
      resetForm () {
        this.$refs.form.reset();
      },
      submitBank () {
        if (this.validate()) {
          axios.post('/bank', this.bank).then(res => {
            if (res.data.error != null) {
              this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
            } else {
              this.$toasted.show('Bank Add successfully', {icon: 'power_settings_new'});
              this.$router.go(-1);
            }
          }).catch(e => {
            this.$toasted.error('Please try again', {icon: 'not_interested'});
            console.log(e.response);
          });
        }
      },
    }
  }
</script>
