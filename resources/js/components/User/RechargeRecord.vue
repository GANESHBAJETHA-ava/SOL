<template>
  <v-app id="RechargeRecord">
    <v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Recharge Records</v-toolbar-title>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">
      <v-container fluid>
        <v-row class="align-center mt-2" >
          <div v-for="recharge in recharges">
            <div class="d-flex justify-space-between align-center">
              <div class="d-flex justify-center">
                <div>
                  <h6 class="font-weight-medium"> ₹ {{ recharge.amount }}</h6>
                  <p class="font-weight-regular mb-0">{{ recharge.txn_id }}</p>
                  <p class="font-weight-regular mb-0">{{ moment(recharge.created_at).format("YYYY-MM-DD hh:mm:ss")  }}</p>                  
                </div>
              </div>
              <div class="d-flex justify-center">
                <div>
                  <p class="font-weight-regular mb-1" v-if="recharge.is_cleared == 1" style="color: #4caf50">Success</p>
                  <p class="font-weight-regular mb-1" v-if="recharge.is_cleared == 0" style="color: #FF8F00">Unpaid</p>                  
                  <p class="font-weight-regular mb-0">{{ recharge.type }}</p>                  
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
        recharges: [],
      }
    },
    created() {
      this.getRecharges();
    },
    mounted() {
      console.log('Component mounted.')
    },
    methods: {
      hasHistory () { return window.history.length > 2 },
      getRecharges() {
        axios.get('/recharges').then(res => {
          this.recharges = res.data.recharges;
        }).catch(e => {
          this.getRecharges();
          console.log(e.response);
        });
      },
    }
  }
</script>
<style>
  .v-application .font-weight-medium {
    font-size: 16px;
  }
  .v-application .grey.lighten-1 .material-icons {
    color: #fff;
  }
  .v-application a {
    text-decoration: none;
  }
</style>