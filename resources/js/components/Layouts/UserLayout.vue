<template>
  <v-app id="success-app">
    <router-view :key="$route.fullPath"></router-view>
    <bottom-nav-layout v-if="$props.auth != null"></bottom-nav-layout>
  </v-app>
</template>
 <script>
  export default {
    props: ["auth", "wfee", "rewardthreshold"],
    data() {
      return {
        value: 1,
        items: [
          {
            src: '/images/slider/slider01.jpg',
          },
          {
            src: '/images/slider/slider02.jpg',
          },
          {
            src: '/images/slider/slider03.jpg',
          },
          {
            src: '/images/slider/slider04.jpg',
          },
        ],
      }
    },
    created() {
      this.$store.commit("updateAuth", this.$props.auth);
      this.$store.commit("wfeeUpdate", this.$props.wfee);
      this.$store.commit("rewardThresholdUpdate", this.$props.rewardthreshold);
      if ((this.$route.path == '/' || this.$route.path == '/home' || this.$route.path == '/dashboard') && this.$props.auth == null) {
        this.$router.push('/login');
      } else if (this.$route.path == '/') {
        this.$router.push('/dashboard'); 
      }
    }
  }
</script>