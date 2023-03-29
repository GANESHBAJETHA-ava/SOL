<template>
  <v-app id="RechargeRecord">
    <v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Withdrawal Records</v-toolbar-title>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">
      <v-container fluid>
        <v-select solo v-model="withdrawalType" :items="withdrawalTypeOptions"></v-select>
        <div v-for="request in requests">
          <div class="d-flex justify-space-between align-center">
            <div class="d-flex justify-center">
              <div>
                <h6 class="font-weight-medium"> ₹ {{ request.amount }}</h6>
                <p class="font-weight-regular mb-0">{{ request.txn_id }}</p>
                <p class="font-weight-regular mb-0">{{ moment(request.created_at).format("YYYY-MM-DD hh:mm:ss")  }}</p>
              </div>
            </div>
            <div class="d-flex justify-center">
              <div> Status
                <p class="font-weight-regular mb-1" v-if="request.status == 1" style="color: #4caf50">Success</p>
                <p class="font-weight-regular mb-1" v-if="request.status == 0 && request.is_rejected  == 0" style="color: #FF8F00">In Process</p>
                <p class="font-weight-regular mb-1" v-if="request.is_rejected == 1 && request.status == 0" style="color: #FF4B2B">Rejected</p>
                <p class="font-weight-regular mb-1" v-if="request.is_rejected == 1" style="color: red">Rejected</p>
                <p class="font-weight-regular mb-1" v-else-if="request.status == 1" style="color: #4caf50">Success</p>
                <p class="font-weight-regular mb-1" v-else-if="request.status == 0" style="color: #FF8F00">In Process</p>
              </div>
            </div>
          </div>
          <v-divider></v-divider>
        </div>
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
        requests: [],
        withdrawalType: "Main Withdrawals",
        withdrawalTypeOptions: ["Main Withdrawals", "Reward Withdrawals"]
      }
    },
    created() {
      this.getWithdrawalRequests();
    },
    watch: {
      withdrawalType(val) {
        this.getWithdrawalRequests();
      }
    },
    methods: {
      hasHistory () { return window.history.length > 2 },
      getWithdrawalRequests() {
        axios.get('/get-withdrawal-requests/' + this.withdrawalType).then(res => {
          console.log(res.data);
          this.requests = res.data.requests;
        }).catch(e => {
          console.log(e.response);
        });
      },
    }
  }
</script>