<template>
  <v-app id="AddAddress"> 	
  	<v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Add Address</v-toolbar-title>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">	
    	<v-container fluid>
        <v-row class="mb-15 justify-center">
          <v-col cols="12">
            <v-form ref="form">
              <v-text-field label="Full Name" v-model.trim="address.full_name" :counter="20" required :rules="fullNameErrors" solo></v-text-field>
              <v-text-field label="Mobile Number" v-model.trim="address.mobile_number" :counter="10" required :rules="mobileNumberErrors" solo></v-text-field>
              <v-text-field label="Pincode" v-model.trim="address.pincode" required :rules="pincodeErrors" solo></v-text-field>
              <v-text-field label="State" v-model.trim="address.state" required :rules="stateErrors" solo></v-text-field>
              <v-text-field label="Town/City" v-model.trim="address.city" required :rules="cityErrors" solo></v-text-field>
              <v-text-field label="Detail Address" v-model.trim="address.address_detail" required :rules="addressDetailErrors" solo></v-text-field>
              <v-row class="justify-center">
                <v-col md="12" lg="8">
                 <v-btn large block color="success" @click="submitAddress" class="white--text">
                  <span>Cantinue</span>
                </v-btn>
              </v-col>
              <v-col md="12" lg="4">
                 <v-btn large block color="red" @click="resetForm" class="white--text">
                  <span>Reset</span>
                </v-btn>
              </v-col>
              </v-row>
            </v-form>
          </v-col>
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
        address: {
          full_name: '',
          mobile_number: '',
          pincode: '',
          state: '',
          city: '',
          address_detail: '',
        },
        fullNameErrors: [
          v => !!v || 'Full name is required',
          v => (v && v.length <= 20) || 'Full name must be less than 20 characters',
        ],
        pincodeErrors: [
          v => !!v || 'Pincode is required',
        ],
        stateErrors: [
          v => !!v || 'State name is required',
        ],
        cityErrors: [
          v => !!v || 'City name is required',
        ],
        addressDetailErrors: [
          v => !!v || 'Address is required',
        ],
        mobileNumberErrors: [
          v => !!v || 'Please enter your mobile number',
          v => (v && v.length == 10) || 'Please enter a valid number',
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
      submitAddress () {
        if (this.validate()) {
          axios.post('/address', this.address).then(res => {
            if (res.data.error != null) {
              this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
            } else {
              this.$toasted.show('Address Add successfully', {icon: 'power_settings_new'});
              setTimeout(function() {
                window.location.href = window.location.origin;
              }, 800);
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
