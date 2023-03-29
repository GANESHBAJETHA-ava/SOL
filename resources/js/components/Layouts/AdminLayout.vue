<template>
  <v-app class="admin-layout">
    <v-navigation-drawer app class="grey darken-2" dark permanent v-if="nav" :temporary="mobile" v-click-outside="clickOutSide">
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="text-h6 text-center">
            <img src="/images/logo/logo.png" alt="" width="100px">
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider></v-divider>
      <v-list style="padding-top: 0 !important;">
        <v-list-item link to="/admin/dashboard">
          <v-list-item-icon>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link to="/admin/users">
          <v-list-item-icon>
            <v-icon>mdi-account-group</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Users</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link to="/admin/game-mode">
          <v-list-item-icon>
            <v-icon>mdi-gamepad-variant</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Game Mode</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link to="/admin/banner">
          <v-list-item-icon>
            <v-icon>mdi-folder-image</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Banners</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-group prepend-icon="mdi-bank-transfer" no-action>
          <template v-slot:activator>
            <v-list-item-content>
              <v-list-item-title>Transactions</v-list-item-title>
            </v-list-item-content>
          </template>
          <v-list-item link to="/admin/deposite/request">
            <v-list-item-content>
              <v-list-item-title>Deposits</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link to="/admin/withdrawal/request">
            <v-list-item-content>
              <v-list-item-title>Withdrawals</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link to="/admin/reward/withdrawal">
            <v-list-item-content>
              <v-list-item-title>Reward Withdrawals</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>
        <v-list-group prepend-icon="mdi-content-duplicate" no-action>
          <template v-slot:activator>
            <v-list-item-content>
              <v-list-item-title>Dummies</v-list-item-title>
            </v-list-item-content>
          </template>
          <v-list-item link to="/admin/dummy/wins">
            <v-list-item-content>
              <v-list-item-title>Dummy Wins</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>
        <v-list-item link to="/admin/upi">
          <v-list-item-icon>
            <v-icon>mdi-bank</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Bank UPIs</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link to="/admin/complains">
          <v-list-item-icon>
            <v-icon>mdi-cog</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Complains</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link to="/admin/settings">
          <v-list-item-icon>
            <v-icon>mdi-cog</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Settings</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link to="/admin/sports">
          <v-list-item-icon>
            <v-icon>mdi-cog</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Sports</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link to="/admin/matchs">
          <v-list-item-icon>
            <v-icon>mdi-cog</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Matchs</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link to="/admin/markets">
          <v-list-item-icon>
            <v-icon>mdi-cog</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Markets</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <template v-slot:append>
        <div class="pa-2">
          <v-btn block @click="logout">
            Logout
          </v-btn>
        </div>
      </template>
    </v-navigation-drawer>
    <v-app-bar elevate-on-scroll app>
      <v-btn elevation="0" style="padding: 0 !important; min-width: 40px !important;">
        <v-icon style="font-size: 30px;" @click="nav = !nav">mdi-menu</v-icon>
      </v-btn>
    </v-app-bar>
    <!-- Sizes your content based upon application components -->
    <v-main>
      <router-view :key="$route.fullPath"></router-view>
    </v-main>
  </v-app>
</template>
 <script>
  export default {
    props: ["auth"],
    data() {
      return {
        nav: true,
        mobile: screen.width <= 760,
      }
    },
    created() {
      if (this.mobile) {
        this.nav = false;
      }
      this.$store.commit("updateAuth", this.$props.auth);
    },
    methods: {
      clickOutSide() {
        if (this.nav && this.mobile) {
          this.nav = false;
        }
      },
      logout() {
        axios.post('/user-logout').then(res => {
          if (res.data.success != null) {
            this.$toasted.success(res.data.success, {icon: "power_settings_new",});
            setTimeout(function() {
              window.location.href = window.location.origin + '/login';
            }, 600);
          }
        }).catch(e => {
          console.log(e.response);
          window.location.href = window.location.origin;
        });
      }
    }
  }
</script>
<style>
  .v-application a {
    color: #fff !important;
  }
  
</style>