<template>
  <v-app id="AddRedenvelope"> 	
  	<v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Add Red Envelope</v-toolbar-title>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">	
    	<v-container fluid>
        <v-row  class="justify-center">
          <v-col cols="11">
            <v-text-field label="Fixed Mony"></v-text-field>
            <v-text-field label="Enter Your Login Password"></v-text-field>
          </v-col>
          <v-col cols="8">
            <v-btn large block color="success" class="white--text">
              <span>Launch</span>
            </v-btn>
          </v-col>
        </v-row>
    	</v-container>
    </v-main>	
   </v-app>
</template>
<script>
  export default {
    mounted() {
      console.log('Component mounted.')
    },
    methods: {
      hasHistory () { return window.history.length > 2 }
    }
  }
</script>
