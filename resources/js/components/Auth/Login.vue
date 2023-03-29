<template>
  <v-app>
    <v-main>
      <v-container fluid>
        <div class="text-center mt-8 form-logo">
          <img width="100" src="/images/logo/logo.png" alt="logo">
          <p>100% Legal And Safe Platform</p>
          <h6>Login</h6>
        </div>
        <v-row  class="justify-center">
          <v-col>
            <v-alert color="red" v-if="user_login_error" dark>
              {{ user_login_error_msg }}
            </v-alert>
          </v-col>
          <v-col cols="12">
            <v-text-field label="Mobile Number" type="number" v-model="phone" prepend-icon="mdi-cellphone-cog"  solo></v-text-field>
            <input class="form-control" type="hidden" v-model="view" />
            <v-text-field label="Password" type="password" v-model="password" prepend-icon="mdi-key" @keyup.enter="login" solo></v-text-field>
          </v-col>
          <v-col md="12" lg="3" class="d-flex">
            <v-btn large color="success" @click="login" class="white--text" style="width: 70%; margin-right: 10px;">
              <span>Login</span>
            </v-btn>
            <v-btn large color="primary" href="/download-apk">
              <v-icon>mdi-download</v-icon>
              <span>Apk</span>
            </v-btn>
          </v-col>
        </v-row>       
        <v-row class="justify-center">
          <v-col class="d-flex justify-space-between" md="12" lg="3">
            <a href="/register" class="text-decoration-none"><v-btn>
                <span>Register</span>
              </v-btn>
            </a>
            <a href="/forgot-password" class="text-decoration-none">
              <v-btn><span>Forgot Password ?</span></v-btn>
            </a>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
    <v-footer padless class="d-block">
      <div class="d-flex justify-content-center">
        <p class="mb-0 p-1">© Copyright 2022,Starofluck. All rights are reserved.</p>
      </div>
    </v-footer>
    <!-- <bottom-nav-layout></bottom-nav-layout> -->
   </v-app>
</template>
<script>
import { mapState } from "vuex";
  export default {
     data() {
      return {
        view: 'web',
        password_error: false,
        user_login_error_msg: "",
        phone: "",
        password: "",
      };
    },
    computed: {
      ...mapState([
        "user_login_error",
      ]),
    },
    mounted() {
      console.log('Component mounted.')
    },
    created() {
      // this.getUser();
    },
    methods: {
      hasHistory () {
       return window.history.length > 2
      },
      login() {
        axios.post("/user-login", {phone: this.phone, password: this.password, view: this.view})
        .then((res) => {
          if (res.data.error != null) {
            this.$store.commit("changeUserLoginError", true);
            this.user_login_error_msg = res.data.error.toString();
          }
          if (res.data.success != null) {
            this.password = "";
            this.$store.commit("changeUserLoginError", false);
            this.$toasted.success(res.data.success, {icon: "power_settings_new",});
            window.location.href = window.location.origin;
          }
        })
        .catch((e) => {
          this.$toasted.error("Please try again !", {icon: "not_interested",});
          console.log(e.response);
        });
      },
      getUser() {
        axios.get("/get-user").then((res) => {
          if (res.data.user.is_auth) {
            window.location.href = window.location.origin;
          }
        })
        .catch((e) => {
          this.getUser();
          console.log(e.response);
        });
      },
    }
  }
</script>

