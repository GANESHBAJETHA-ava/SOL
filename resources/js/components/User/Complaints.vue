<template>
  <v-app id="Complaints">  
    <v-card>
      <v-toolbar color="success" dark flat>
        <v-btn  icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Contact us</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn large :ripple="true" to="/add/complaints" icon color="#FFFFFF">          
          <v-icon>mdi-plus</v-icon>
        </v-btn>
        <template v-slot:extension>
          <v-tabs fixed-tabs>
            <v-tab @click="changetTab">Wait</v-tab>
            <v-tab @click="changetTab">COMPLETED</v-tab>
          </v-tabs>
        </template>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">
      <v-container fluid>
        <v-row class="align-center mt-2">
          <div v-for="complain in complains" v-if="wait == false && complain.reply == null">
            <div class="d-flex justify-space-between align-center">
              <div class="d-flex justify-center">
                <div>
                  <h6 class="font-weight-medium"> ₹ {{ complain.type }}</h6>
                  <p class="font-weight-regular mb-0">{{ complain.whatsapp }}</p>
                  <p class="font-weight-regular mb-0">{{ complain.des  }}</p>
                </div>
              </div>
              <div class="d-flex justify-center">
                <div>
                  <h6 class="font-weight-regular mb-1">{{ moment(complain.created_at).format("YYYY-MM-DD hh:mm:ss")  }}</h6>
                </div>
              </div>
            </div>
            <v-divider></v-divider>
          </div>
          <div v-for="complain in complains" v-if="wait == true && complain.reply != null">
            <div class="d-flex justify-space-between align-center">
              <div class="d-flex justify-center">
                <div>
                  <h6 class="font-weight-medium"> ₹ {{ complain.type }}</h6>
                  <p class="font-weight-regular mb-0">{{ complain.whatsapp }}</p>
                  <p class="font-weight-regular mb-0">{{ complain.des  }}</p>
                </div>
              </div>
              <div class="d-flex justify-center">
                <div>
                  <h6 class="font-weight-regular mb-1">{{ moment(complain.created_at).format("YYYY-MM-DD hh:mm:ss")  }}</h6>
                  <p>{{ complain.reply }}</p>
                </div>
              </div>
            </div>
            <v-divider></v-divider>
          </div>
        </v-row>
      </v-container>
    </v-main> 
   </v-app>
</template>
<script>
import moment from "moment";
  export default {
    data () {
      return {
        moment: moment,        
        ripple: true,
        wait: false,
        tab: null,
        ripple: true,
        complains: [],
      }
    },
    mounted() {
      console.log('Component mounted.')
    },
    created() {
      this.getComplains();
    },
    methods: {
      hasHistory () { return window.history.length > 2 },
      getComplains() {
        axios.get('/complains').then(res => {
          console.log(res.data);
          this.complains = res.data.complains;
        }).catch(e => {
          this.getComplains();
          console.log(e.response);
        });
      },
      changetTab() {
        if (this.wait == false) {
          this.wait = true
        } else if(this.wait == true) {
          this.wait = false
        }
      }
    }
  }
</script>
