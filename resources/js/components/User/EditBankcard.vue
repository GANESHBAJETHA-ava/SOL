<template>
  <v-app id="EditBank"> 	
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
            <v-col cols="12"  @keyup.enter="updateBankCard">
              <v-text-field label="Actual Name" v-model="edit_bank.actual_name" :counter="20" required :rules="actualNameErrors"></v-text-field>
              <v-text-field label="IFSC Code" v-model="edit_bank.IFSC" required :rules="ifscErrors"></v-text-field>
              <v-text-field label="Bank Name" v-model="edit_bank.bank_name" required :rules="bankNameErrors"></v-text-field>
              <v-text-field label="Bank Account" v-model="edit_bank.bank_account" required :rules="bankAccountErrors"></v-text-field>
              <v-text-field label="State/Territory" v-model="edit_bank.state" required :rules="stateErrors"></v-text-field>
              <v-text-field label="City" v-model="edit_bank.city" required :rules="cityErrors"></v-text-field>
              <v-text-field label="Address" v-model="edit_bank.address" required :rules="addressErrors"></v-text-field>
              <v-text-field label="Mobile Number" v-model="edit_bank.mobile_number" required counter="10" prefix="91" :rules="mobileNumberErrors"></v-text-field>
              <!-- <v-text-field label="Email" v-model="edit_bank.email" required :rules="emailErrors"></v-text-field> -->
              <v-text-field label="Account phone number" v-model="auth.phone" prefix="91" disabled></v-text-field>
              <v-text-field label="Code" placeholder="Verification Code">
                <v-btn slot="append" large>OTP</v-btn>
              </v-text-field>      
            </v-col>
              <v-row class="justify-center">
                <v-col cols="12">
                 <v-btn large block color="primary" @click="updateBankCard" class="white--text">
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
        editBank: {
          actual_name: '',
          IFSC: '',
          bank_name: '',
          state: '',
          city: '',
          address: '',
          email: '',
          mobile_number: '',
        },
        edit_bank: {},

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
      this.getBankcard();
    },
    methods: {
      hasHistory () {
        return window.history.length > 2 
      },
      validate () {
        return this.$refs.form.validate();
      },
      getBankcard() {
        this.is_loading = true;
        axios.get('/bank/'+this.$props.id+ '/edit/').then(res => {
          this.is_loading = false;
          this.edit_bank = res.data.edit_bank;
          this.editBank.id = this.edit_bank.id;
          this.editBank.actual_name = this.edit_bank.actual_name;
          this.editBank.IFSC = this.edit_bank.IFSC;
          this.editBank.bank_name = this.edit_bank.bank_name;
          this.editBank.state = this.edit_bank.state;
          this.editBank.city = this.edit_bank.city;
          this.editBank.address = this.edit_bank.address;
          this.editBank.email = this.edit_bank.email;
          this.editBank.mobile_number = this.edit_bank.mobile_number;
          this.is_loading = false;     
        }).catch(e => {
          this.is_loading = false;
          console.log(e.response);
          this.getBankcard();
        });
      },
      updateBankCard () {
        if (this.validate()) {
          axios.post('/bank/'+this.$props.id, this.edit_bank).then(res => {
            if (res.data.error != null) {
              this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
            } else {
              this.$toasted.show('Bank Account Update successfully', {icon: 'power_settings_new'});
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
