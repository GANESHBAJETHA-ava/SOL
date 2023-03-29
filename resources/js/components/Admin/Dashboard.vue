<template>
  <v-app>
    <v-main class="admin-index-pages">
      <v-container fluid>
        <v-card>
          <v-card-title>Dashboard</v-card-title>
          <v-card-text class="mt-4">
            <transition enter-active-class="animate__animated animate__flash">
              <v-alert type="info" dense outlined v-if="depositCount">
                <v-row align="center">
                  <v-col class="grow">
                    There are <strong>{{ depositCount }}</strong> deposit request(s) are pending
                  </v-col>
                  <v-col class="shrink">
                    <v-btn x-small color="info" to="/admin/deposite/request">Take action</v-btn>
                  </v-col>
                </v-row>
              </v-alert>
            </transition>
            <transition enter-active-class="animate__animated animate__flash">
              <v-alert type="error" dense outlined v-if="withdrawalCount">
                <v-row align="center">
                  <v-col class="grow">
                    There are <strong>{{ withdrawalCount }}</strong> withdrawal request(s) are pending
                  </v-col>
                  <v-col class="shrink">
                    <v-btn x-small color="error" to="/admin/withdrawal/request">Take action</v-btn>
                  </v-col>
                </v-row>
              </v-alert>
            </transition>
            <v-row v-if="dashboardData != null">
              <v-col cols="4">
                <v-card color="deep-purple lighten-5" elevation="0">
                  <v-card-title style="border: none;"><h5 class="m-0" style="font-weight: 400;">Total Members</h5></v-card-title>
                  <v-card-text><h5 class="m-0" style="font-weight: 600;">{{ dashboardData.total_members }}</h5></v-card-text>
                </v-card>
              </v-col>
              <v-col cols="4">
                <v-card color="blue lighten-5" elevation="0">
                  <v-card-title style="border: none;"><h5 class="m-0" style="font-weight: 400;">Active Members</h5></v-card-title>
                  <v-card-text><h5 class="m-0" style="font-weight: 600;">{{ dashboardData.active_members }}</h5></v-card-text>
                </v-card>
              </v-col>
              <v-col cols="4">
                <v-card color="amber lighten-5" elevation="0">
                  <v-card-title style="border: none;"><h5 class="m-0" style="font-weight: 400;">Inactive Members</h5></v-card-title>
                  <v-card-text><h5 class="m-0" style="font-weight: 600;">{{ dashboardData.inactive_members }}</h5></v-card-text>
                </v-card>
              </v-col>
              <v-col cols="4">
                <v-card color="grey lighten-4" elevation="0">
                  <v-card-title style="border: none;"><h5 class="m-0" style="font-weight: 400;">Total Orders</h5></v-card-title>
                  <v-card-text><h5 class="m-0" style="font-weight: 600;">{{ dashboardData.total_orders }}</h5></v-card-text>
                </v-card>
              </v-col>
              <v-col cols="4">
                <v-card color="teal lighten-5" elevation="0">
                  <v-card-title style="border: none;"><h5 class="m-0" style="font-weight: 400;">Total Deposits</h5></v-card-title>
                  <v-card-text><h5 class="m-0" style="font-weight: 600;">{{ dashboardData.total_deposits | addCommas }}</h5></v-card-text>
                </v-card>
              </v-col>
              <v-col cols="4">
                <v-card color="red lighten-5" elevation="0">
                  <v-card-title style="border: none;"><h5 class="m-0" style="font-weight: 400;">Total Withdrawals</h5></v-card-title>
                  <v-card-text><h5 class="m-0" style="font-weight: 600;">{{ dashboardData.total_withdrawals | addCommas }}</h5></v-card-text>
                </v-card>
              </v-col>
              <v-col cols="4">
                <v-card color="light-green lighten-5" elevation="0">
                  <v-card-title style="border: none;"><h5 class="m-0" style="font-weight: 400;">Today P/L</h5></v-card-title>
                  <v-card-text><h5 :class="dashboardData.today_pl > 0 ? 'm-0 green--text' : (dashboardData.today_pl < 0 ? 'red--text m-0' : 'm-0')" style="font-weight: 600;"><span v-if="dashboardData.today_pl > 0">+</span>{{ dashboardData.today_pl | addCommas }}</h5></v-card-text>
                </v-card>
              </v-col>
              <v-col cols="4">
                <v-card color="blue-grey lighten-5" elevation="0">
                  <v-card-title style="border: none;"><h5 class="m-0" style="font-weight: 400;">Total P/L</h5></v-card-title>
                  <v-card-text><h5 :class="dashboardData.total_pl > 0 ? 'm-0 green--text' : (dashboardData.total_pl < 0 ? 'red--text m-0' : 'm-0')" style="font-weight: 600;"><span v-if="dashboardData.total_pl > 0">+</span>{{ dashboardData.total_pl | addCommas }}</h5></v-card-text>
                </v-card>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>
<script>
  import { mapState } from "vuex";
  export default {
  	data () {
      return {
        depositCount: 0,
        withdrawalCount: 0,
        dashboardData: null
      }
    },
    filters: {
    	addCommas(val) {
	      var val = parseFloat(val).toFixed(2);
	      val += '';
	      var x = val.split('.');
	      var x1 = x[0];
	      var x2 = x.length > 1 ? '.' + x[1] : '';
	      var rgx = /(\d+)(\d{3})/;
	      while (rgx.test(x1)) {
	          x1 = x1.replace(rgx, '$1' + ',' + '$2');
	      }
	      var total = x1 + x2;
	      return total;
	    }
    },
    created() {
      this.getDashboardData();
    },
    methods: {
      getDashboardData() {
        axios.get('/get-dashboard-data').then(res => {
          this.dashboardData = res.data.data;
          this.depositCount = res.data.depositCount;
          this.withdrawalCount = res.data.withdrawalCount;
        }).catch(e => {
          console.log(e.response);
        });
      }
    }
  }
</script>
