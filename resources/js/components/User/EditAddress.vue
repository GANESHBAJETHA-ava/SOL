<template>
  <v-app id="EditAddress"> 	
  	<v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Address</v-toolbar-title>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">	
    	<v-container fluid>
        <v-row class="mb-15 justify-center">
          <v-form ref="form">
            <v-col cols="12"  @keyup.enter="updateAddress">
              <v-text-field label="Full Name" v-model="edit_address.full_name" :counter="20" required :rules="fullNameErrors"></v-text-field>
              <v-text-field label="Mobile Number" v-model="edit_address.mobile_number" :counter="10" required :rules="mobileNumberErrors"></v-text-field>
              <v-text-field label="Pincode" v-model="edit_address.pincode" required :rules="pincodeErrors"></v-text-field>
              <v-text-field label="State" v-model="edit_address.state" required :rules="stateErrors"></v-text-field>
              <v-text-field label="Town/City" v-model="edit_address.city" required :rules="cityErrors"></v-text-field>
              <v-text-field label="Detail Address" v-model="edit_address.address_detail" required :rules="addressDetailErrors"></v-text-field>
            </v-col>
              <v-row class="justify-center">
                <v-col cols="12">
                 <v-btn large block color="primary" @click="updateAddress" class="white--text">
                  <span>Update</span>
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
    props: ['id'],
    data() {
      return {
        is_loading: false,
        editAddress: {
          full_name: '',
          mobile_number: '',
          pincode: '',
          state: '',
          city: '',
          address_detail: '',
        },
        edit_address: {},

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
    created() {
      this.is_loading = false;
      this.getAddress();
    },
    methods: {
      hasHistory () {
        return window.history.length > 2 
      },
      validate () {
        return this.$refs.form.validate();
      },
      getAddress() {
        this.is_loading = true;
        axios.get('/address/'+this.$props.id+ '/edit/').then(res => {
          console.log(res.data);
          this.is_loading = false;
          console.log(res.data.edit_address);
          this.edit_address = res.data.edit_address;
          this.editAddress.id = this.edit_address.id;
          this.editAddress.full_name = this.edit_address.full_name;
          this.editAddress.mobile_number = this.edit_address.mobile_number;
          this.editAddress.state = this.edit_address.state;
          this.editAddress.city = this.edit_address.city;
          this.editAddress.pincode = this.edit_address.pincode;
          this.editAddress.address_detail = this.edit_address.address_detail;
          this.is_loading = false;     
        }).catch(e => {
          this.is_loading = false;
          console.log(e.response);
          this.getAddress();
        });
      },
      updateAddress () {
        if (this.validate()) {
          axios.post('/address/'+this.$props.id, this.edit_address).then(res => {
            if (res.data.error != null) {
              this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
            } else {
              this.$toasted.show('Address Update successfully', {icon: 'power_settings_new'});
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
