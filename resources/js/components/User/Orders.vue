<template>
  <v-app id="Order"> 	
  	<v-card>
      <v-toolbar color="success" dark flat>
        <v-btn  icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Orders</v-toolbar-title>
        <v-spacer></v-spacer>
        <template v-slot:extension>
          <v-tabs fixed-tabs><v-tab>All</v-tab>
            <v-tab>UNDELIVER</v-tab>
            <v-tab>UNDELIVER</v-tab>
            <v-tab>SUCCESS</v-tab>
          </v-tabs>
        </template>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">	
    	<v-container fluid>
    	</v-container>
    </v-main>	
   </v-app>
</template>
<script>
  export default {
    data () {
      return {
        tab: null,
        items: [
          'web', 'shopping', 'videos', 'images', 'news',
        ],
        text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
      }
    },
    mounted() {
      console.log('Component mounted.')
    },
    methods: {
      hasHistory () { return window.history.length > 2 }
    }
  }
</script>
