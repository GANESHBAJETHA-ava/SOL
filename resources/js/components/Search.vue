<template>
    <v-app id="inspire">
      <v-app-bar app elevation="0" color="grey lighten-3">
        <v-autocomplete
          hide-no-data
          hide-details
          solo
        ></v-autocomplete>
      </v-app-bar>
      <v-card class="mb-12">
      <v-container fluid>
        <v-row >
          <v-col md="6">
            <v-card>
              <v-img src="/images/01.jpg" height="200px"></v-img>
              <v-card-title class="justify-center"> 
                <h6>LIZZIE MANDLER</h6>
              </v-card-title>
              <v-btn color="orange" text>₹ 26024.00</v-btn>
            </v-card>
          </v-col>
          <v-col md="6">
            <v-card>
              <v-img src="/images/02.jpg" height="200px"></v-img>
              <v-card-title class="justify-center"> 
                <h6>ELI HALILI</h6>
              </v-card-title>
              <v-btn color="orange" text>₹ 26024.00</v-btn>
            </v-card>
          </v-col>
          <v-col md="6">
            <v-card>
              <v-img src="/images/03.jpg" height="200px"></v-img>
              <v-card-title class="justify-center"> 
                <h6>GUCCI</h6>
              </v-card-title>
              <v-btn color="orange" text>₹ 26024.00</v-btn>
            </v-card>
          </v-col>
          <v-col md="6">
            <v-card>
              <v-img src="/images/04.jpg" height="200px"></v-img>
              <v-card-title class="justify-center"> 
                <h6>LIZZIE MANDLER</h6>
              </v-card-title>
              <v-btn color="orange" text>₹ 26024.00</v-btn>
            </v-card>
          </v-col>
          <v-col md="6">
            <v-card>
              <v-img src="/images/05.jpg" height="200px"></v-img>
              <v-card-title class="justify-center"> 
                <h6>HARWELL GODFREY</h6>
              </v-card-title>
              <v-btn color="orange" text>₹ 26024.00</v-btn>
            </v-card>
          </v-col>
          <v-col md="6">
            <v-card>
              <v-img src="/images/06.jpg" height="200px"></v-img>
              <v-card-title class="justify-center"> 
                <h6>BOTTEGA VENETA</h6>
              </v-card-title>
              <v-btn color="orange" text>₹ 26024.00</v-btn>
            </v-card>
          </v-col>
        </v-row>
      </v-container>    
    </v-card>
  </v-app>
</template>
 <script>
  export default {
    data() {
      return {
      }
    },
    methods: {
      
    }
  }
</script>