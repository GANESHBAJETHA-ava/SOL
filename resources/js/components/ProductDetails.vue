<template>
  <v-app id="inspire">
    <v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Product</v-toolbar-title>
      </v-toolbar>
    </v-card>
    <v-carousel height="80vh" cycle :interval="3000" :show-arrows="false">
      <v-carousel-item
      v-for="(item,i) in items"
      :key="i"
      :src="item.src">
      </v-carousel-item>
    </v-carousel>
    <v-card class="mb-12">
      <v-container fluid>
        <v-row>
          <v-col cols="12">
            <h5>LIZZIE MANDLER</h5>
            <v-btn color="orange" text>₹ 26024.00</v-btn> <br>
            <v-btn>Sold out</v-btn>
          </v-col>
          <v-col cols="12" class="mb-15">
            <v-card class="d-flex justify-start mb-6" flat tile>
               <v-card-subtitle  color="grey">Product Specificationss</v-card-subtitle >
            </v-card>
            <div>
              <v-card class="d-flex justify-space-around mb-2" flat tile>
                <v-card flat tile>Brand</v-card>
                <v-card flat tile>Ananth Jewels</v-card>
              </v-card>
              <v-divider></v-divider>
            </div>
            <div>
              <v-card class="d-flex justify-space-around mb-2" flat tile>
                <v-card flat tile>Brand</v-card>
                <v-card flat tile>Ananth Jewels</v-card>
              </v-card>
              <v-divider></v-divider>
            </div>
          </v-col>
        </v-row>
      </v-container>    
    </v-card>
  </v-app>
</template>
<script>
  import topBarLayout from "./Global/TopBarLayout.vue";
  export default {
    components: { topBarLayout },
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
    methods: {
      
    }
  }
</script>