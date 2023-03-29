<template>
  <v-app>
    <v-main>
      <v-container fluid>
        <div class="text-center mt-8 form-logo">
          <img width="100" src="/images/logo/logo.png" alt="logo">
            <p>100% Legal And Safe Platform</p>
          <h6>Register</h6>
        </div>
        <p class="mt-2 text-center">Already have an account?   <a href="/login">Login</a></p>
        <v-form ref="form">
          <v-text-field label="User name" type="text" v-model.trim="formData.name" prepend-icon="mdi-account" :rules="nameRules" solo>
          </v-text-field>
          <v-text-field label="Mobile Number" type="number" v-model.trim="formData.phone" prefix="+91" prepend-icon="mdi-cellphone-cog" counter="10" :rules="phoneRules" solo>
          </v-text-field>
          <v-row>
            <v-col cols="7">
              <v-text-field label="Verification Code" ref="otp" v-model="otp" prepend-icon="mdi-android-messages" :rules="verifyRules" solo></v-text-field>
            </v-col>
            <v-col cols="5">
              <v-btn v-if="otp_timer == 0" large block @click="getOtp" class="get-otp"><span>Get Otp</span></v-btn>
              <v-btn v-else large class="get-otp" block><span>WAIT FOR ({{otp_timer}})</span></v-btn>
            </v-col>
          </v-row>
          <v-text-field label="Password" :type="showPassword ? 'text' : 'password'" :rules="passwordRules" v-model.trim="formData.password" prepend-icon="mdi-key" solo :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" @click:append="showPassword = !showPassword"></v-text-field>
          <v-text-field label="Recommendation Code" v-model.trim="formData.r_code" prepend-icon="mdi-gift" solo>
          </v-text-field>
          <v-checkbox color="black" class="m-0">
            <template v-slot:label>
              <div>I agree
                <v-tooltip bottom>
                  <template v-slot:activator="{ on }">
                    <a target="_blank" href="#" class="text-decoration-none" style="color: #4caf50;">Privacy Policy</a>
                  </template>
                </v-tooltip>
              </div>
            </template>
          </v-checkbox>
          <div class="text-center">
            <v-btn large color="success" v-if="is_verify_loading"  class="white--text">
              <span>PLEASE WAIT...</span>
            </v-btn>
            <v-btn large color="success mb-4" v-else @click="verifyOtp"  class="white--text">
              <span>Register</span>
            </v-btn>
            <p class="mt-2">By signing up, you agree to our
            <span class="link">Terms & Conditions</span> and <span class="link">Privacy Policy</span></p>
          </div>
        </v-form>
      </v-container>
    </v-main>
    <v-footer padless class="d-block">
      <div class="text-center mt-3">
        <h5>Safety & Relieved </h5>
        <p class="mb-0 p-1"><strong>1</strong> CRORE+USERS <strong>18</strong> LAKH+ INVESTORS <strong>150</strong> CRORE+ USER INCOME </p>
        <p class="mb-0 p-1">© Copyright 2022, SOL. All rights are reserved. </p>
      </div>
    </v-footer>
   </v-app>
</template>
<script>
  export default {
    data() {
      return {
        showPassword: false,
        otp: '',
        phone_number_not_valid: false,
        user_id_not_valid: false,
        is_otp_send: false,
        is_submit_loading: false,
        otp_timer: 0,
        is_verify_loading: false,


        formData: {
          r_code: '',
          password: '',
          phone: '',
          name: '',
        },
        // RULES
        phoneRules: [
          v => !!v || 'Please enter your phone number',
          v => (v && v.length == 10) || 'Please enter a valid number',
        ],
        nameRules: [
          v => !!v || 'Please enter your name',
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
    created() {
      if (this.$route.query.code != null || this.$route.query.code != "") {
        this.formData.r_code = this.$route.query.code;
      }
    },
    methods: {
      hasHistory () {
        return window.history.length > 2;
      },
      validate () {
        return this.$refs.form.validate();
      },
      resetForm () {
        this.$refs.form.reset();
      },
      resetValidation () {
        this.$refs.form.resetValidation();
      },
      async getOtp() {
        await this.checkPhoneNumber();
        if (this.phone_number_not_valid == false) {
          this.is_submit_loading = true;
            this.sendOtp();
        }
      },
    verifyOtp() {
      if (this.validate()) {
        this.is_verify_loading = true;
        axios.get('/verify-otp/' + this.formData.phone + '/' + this.otp).then(res => {
          this.is_verify_loading = false;
          console.log(res.data.is_verified);
          if (res.data.is_verified) {
            this.signUp();
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
    async checkPhoneNumber() {
      this.phone_number_not_valid = false;
      await axios.post('/check-phone', {phone: this.formData.phone}).then(res => {
          console.log(res.data.valid);
          if (res.data.valid == false) {
            this.phone_number_not_valid = true;
            this.$toasted.error('This phone number already register', {icon: 'dangerous'});
          }
        })
        .catch(e => {
          this.checkPhoneNumber();
          console.log(e.response);
        });
    },
    signUp() {
      axios.post('/sign-up', {data: this.formData, otp: this.otp}).then(res => {
        this.is_submit_loading = false;
        if (res.data.error != null) {
          this.$toasted.error(res.data.error.toString(), {icon: 'dangerous'});
        } else  {
          this.is_verify_loading = false;
          this.$toasted.show('Thanks for signing up', {icon: 'check'});
          window.location.href = window.location.origin;
        }
      }).catch(e => {
        this.is_verify_loading = false;
        this.$toasted.error('Please try again', {icon: 'dangerous'});
        this.is_submit_loading = false;
        console.log(e.response);
      });
    },
    sendOtp() {
      if (this.otp_timer == 0) {
        this.otp_timer = 30;
        this.otp = '';
        axios.post('/send-otp', { phone: this.formData.phone.toString() }).then(res => {
          this.$toasted.show('Verification code has been sent successfully', {icon: 'check'});
          this.is_submit_loading = false;
          this.is_otp_send = true;
        }).catch(e => {
          console.log(e.response);
          this.is_submit_loading = false;
          var message = e.response.data.message.slice(0,10).toString();
          if (message == "[HTTP 400]") {
            this.$toasted.error('Phone number is invalid !', {icon: 'dangerous'});
          } else {
            this.$toasted.error('Please try again !', {icon: 'dangerous'});
          }
        });
        clearInterval(this.otp_timer_interval);
        this.otp_timer_interval = setInterval(function () {
          if (this.otp_timer != 0) {
            this.otp_timer = this.otp_timer - 1;
          }
        }.bind(this), 1000);
      }
    },
    }
  }
</script>
<style scoped>
  .link {
    color: #4caf50;
  }
  .v-footer h5 {
    color: #999;
  }
  .v-footer p {
    color: #999;
  }
  .v-application a {
    color: #4caf50 !important;
  }
</style>