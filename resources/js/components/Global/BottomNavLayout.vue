<template> 
  <v-bottom-navigation grow fixed>
    <v-btn to="/home">
        <span>Home</span>
        <v-icon>mdi-home</v-icon>
      </v-btn>
      <v-btn to="/game/win" max-width="80" v-if="auth != null">
        <span>Win</span>
        <span class="material-icons">emoji_events</span>
      </v-btn>
      <v-btn to="/promotion" v-if="auth != null">
        <span>Promotion</span>
        <v-icon>mdi-gift</v-icon>
      </v-btn>
      <!-- <v-btn to="/dashboard" plain max-width="80" v-if="auth != null">
        <span>My Account</span>
        <v-icon>mdi-gift</v-icon>        
      </v-btn> -->
      <v-btn to="/dashboard" v-if="auth != null">
        <span>Account</span>
        <v-icon>mdi-account</v-icon>
      </v-btn>
      <v-btn to="/login" v-else>
        <span>Login</span>
        <v-icon>mdi-login</v-icon>
      </v-btn>
  </v-bottom-navigation>
</template>
 <script>
  import { mapState } from "vuex";
  export default {
    data() {
      return {
        
      }
    },
    computed: {
      ...mapState([
        "auth",
      ]),
    },
    methods: {
      
    }
  }
</script>
<style scoped>
  .v-item-group.v-bottom-navigation .v-btn.v-btn--active {
    color: #4caf50 !important;
  }
  .v-btn>.v-btn__content .v-icon {
    margin-bottom: 5px;
  }
</style>