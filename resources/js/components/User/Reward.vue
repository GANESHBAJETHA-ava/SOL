<template>
  <v-app id="Reward"> 	
  	<v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Reward</v-toolbar-title>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">	
    	<v-container fluid>
        <v-row>
          <v-card class="mb-15">
            <v-card-title>
              <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="desserts"
              :search="search"
            ></v-data-table>
          </v-card>
        </v-row>  
    	</v-container>
    </v-main>	
   </v-app>
</template>
<script>
  export default {
    data () {
      return {
        search: '',
        headers: [
          {
            text: 'Id',
            align: 'start',
            filterable: false,
            value: 'Id',
          },
          { text: 'Phone', value: 'Phone' },
          { text: 'Water Reward', value: 'Wreward' },
          { text: 'Reward', value: 'reward' },
        ],
        desserts: [
          {
            Id: 29,
            Phone: 965023652,
            Wreward: 6.0,
            reward: 24,
          },
          {
            Id: 29,
            Phone: 965023652,
            Wreward: 6.0,
            reward: 24,
          },
          {
            Id: 29,
            Phone: 965023652,
            Wreward: 6.0,
            reward: 'ini',
          },
        ],
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
