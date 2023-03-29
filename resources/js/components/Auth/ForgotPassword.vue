<template>
  <v-app id="inspire">
    <v-main class="p-0">
      <v-container fluid>
        <div class="text-center mt-10 form-logo">
          <img width="100" src="/images/logo/logo.png" alt="logo">
          <p>100% Legal And Safe Platform</p>
          <h6>Forgot Password</h6>
        </div>
         <p class="mt-2 text-center">Already have an account?   <a href="/login">Login</a></p>
        <v-form ref="form">
          <v-text-field label="Mobile Number" type="number" v-model.trim="phone" prefix="+91" prepend-icon="mdi-cellphone-cog" counter="10" :rules="phoneRules" required solo>></v-text-field>
          <v-row>
            <v-col cols="7">
              <v-text-field label="Verification Code" ref="otp" v-model="otp" prepend-icon="mdi-android-messages" :rules="verifyRules" solo></v-text-field>                
            </v-col>
            <v-col cols="5">
              <v-btn v-if="otp_timer == 0" large class="get-otp" block @click="getOtp"><span>Get Otp</span></v-btn>
              <v-btn v-else large class="get-otp" block><span>WAIT FOR ({{otp_timer}})</span></v-btn>
            </v-col>
          </v-row>
          <v-text-field label="New Password" prepend-icon="mdi-key" v-model.trim="changePassword.password" :rules="passwordRules" solo></v-text-field>
          <div class="text-center">
            <v-btn large color="success" v-if="is_verify_loading"  class="white--text">
              <span>PLEASE WAIT...</span>
            </v-btn>
            <v-btn large color="success" v-else @click="verifyOtp"  class="white--text">
              <span>Reset Password</span>
            </v-btn>
          </div>
        </v-form>      
      </v-container>
    </v-main>
   </v-app>
</template>

<script>
  export default {
    data() {
      return {
        password: '',    
        user_id_not_valid: false,
        is_verify_loading: false,
        is_otp_send: false,
        is_otp_verify: false,
        phone: "",
        otp: '',
        otp_timer: 0,
        otp_timer_interval: null,
        changePassword: {
          password: '',
        },
        phoneRules: [
          v => !!v || 'Please enter your phone number',
          v => (v && v.length == 10) || 'Please enter a valid number',
        ],
        verifyRules: [
          v => !!v || 'Verification Code is required',
          v => (v && v.length == 6) || 'Verification Code only valid for 6 digit',
        ],
        passwordRules: [
          v => !!v || 'Please enter your password',
          v => (v && v.length >= 6) || 'Please enter a valid number',
        ]
      }
    },
    mounted() {
      console.log('Component mounted.')
    },
    methods: {
      hasHistory () { return window.history.length > 2 },
      validate () {
        return this.$refs.form.validate();
      },
      getOtp() {
        if (this.phone == '') {
          this.$toasted.error('Please Enter your Phone number', {icon: 'dangerous'});
        } else {
            axios.get('/check-phone-number/' + this.phone).then(res => {
               console.log(res.data);
              if (res.data.success != null) {
                this.sendOtp();
              } else if (res.data.error != null) {
                this.$toasted.error(res.data.error.toString(), {icon: 'dangerous'});
              }
            }).catch(e => {
              console.log(e.response);
            });          
        }
      },
      sendOtp() {
        axios.post('/send-otp', { phone: this.phone.toString() }).then(res => {
          this.$toasted.show('Verification code sent successfully to your phone number', {icon: 'check'});
          this.is_otp_send = true;
        }).catch(e => {
          var message = e.response.data.message.slice(0,10).toString();
          if (message == "[HTTP 400]") {
            this.$toasted.error('Please enter valid phone number !', {icon: 'dangerous'});
          } else {
            this.$toasted.error('Please try again !', {icon: 'dangerous'});
          }
        });
      },
      verifyOtp() {
        if (this.validate()) {
          this.is_verify_loading = true;
          axios.get('/verify-otp/' + this.phone + '/' + this.otp).then(res => {
            console.log(res.data);
            this.is_verify_loading = false;
            if (res.data.is_verified) {
              this.updatePassword();
            } else {
              this.$toasted.error('Verification code is invalid !', {icon: 'dangerous'});
            }
          }).catch(e => {
            this.is_verify_loading = false;
            this.$toasted.error('Please try again', {icon: 'dangerous'});
            console.log(e.response);
          });
        }
      },
      updatePassword() {
        axios.post('/change-password-after-top', {password: this.changePassword, phone: this.phone, otp: this.otp}).then(res => {
          if (res.data.error != null) {
            this.$toasted.error(res.data.error.toString(), {icon: 'dangerous'});
          } else  {
            this.is_verify_loading = false;
            this.$toasted.show('Password changed successfully', {icon: 'check'});
            window.location.href = window.location.origin;
          }
        }).catch(e => {
          this.is_verify_loading = false;
          this.$toasted.error('Please try again', {icon: 'dangerous'});
          console.log(e.response);
        });
      },      
    }
  }
</script>
<style scoped>
  .v-application a {
    color: #4caf50 !important;
  }
</style>
