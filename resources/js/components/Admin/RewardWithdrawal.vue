<template>
  <v-app>
    <v-main class="admin-index-pages">
      <v-container fluid>
        <v-card>
          <v-card-title class="justify-content-between">
            <span>Reward Withdrawals <v-chip>{{ rewardwithdrawals.length }}</v-chip> </span>
            <v-btn-toggle mandatory class="mt-4 mt-md-0" color="primary">
              <v-btn @click="getRewardWithdrawalData('pending')">
                Pending
              </v-btn>
              <v-btn @click="getRewardWithdrawalData('success')">
                Success
              </v-btn>
              <v-btn @click="getRewardWithdrawalData('rejected')">
                Rejected
              </v-btn>
            </v-btn-toggle>
          </v-card-title>
          <v-simple-table>
            <template v-slot:default>
              <thead class="grey lighten-3">
                <tr>
                  <th class="text-left">Phone</th>
                  <th class="text-left">Amount</th>
                  <th class="text-left">Actual Name</th>
                  <th class="text-left">IFSC</th>
                  <th class="text-left">Bank Name</th>                
                  <th class="text-left">Account Number</th>
                  <th class="text-left">Mobile Number</th>
                  <th class="text-left">Balance</th>
                  <th class="text-left">Created</th>
                  <th class="text-left">Status</th>
                  <th class="text-left" v-if="selectedType == 'pending'">Reject</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(rewardwithdrawal, index) in rewardwithdrawals">
                  <td>{{ rewardwithdrawal.user.phone }}</td>
                  <td><v-chip small color="success"><b>{{ rewardwithdrawal.amount }}</b></v-chip></td>
                  <td>{{ rewardwithdrawal.bank.actual_name }}</td>
                  <td>{{ rewardwithdrawal.bank.IFSC }}</td>
                  <td>{{ rewardwithdrawal.bank.bank_name }}</td>
                  <td>{{ rewardwithdrawal.bank.bank_account }}</td>
                  <td>{{ rewardwithdrawal.bank.mobile_number }}</td>
                  <td>{{ rewardwithdrawal.user.balance }}</td>
                  <td>{{ moment(rewardwithdrawal.created_at).format("YYYY-MM-DD hh:mm:ss") }}</td>
                  <td>
                    <v-chip small color="success" v-if="rewardwithdrawal.status == 1"><b>Success</b></v-chip>
                    <v-chip small color="error" v-else-if="rewardwithdrawal.is_rejected == 1"><b>Rejected</b></v-chip>
                    <v-chip small rounded v-else color="warning" @click="changeRewardWithdrawalStatus(rewardwithdrawal.id)" dark>Pending</v-chip>
                  </td>
                  <td v-if="rewardwithdrawal.status == 0 && rewardwithdrawal.is_rejected == 0">
                    <v-btn icon color="red accent-2" @click="RewardWithdrawalRejected(rewardwithdrawal.id)">
                      <v-icon dark size="30">mdi-close-box</v-icon>
                    </v-btn>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
          <div class="pagination-main-block pt-5 pb-5 d-flex flex-row justify-center align-items-center flex-wrap">
            <v-pagination
              v-model="page"
              :length="paginationLength"
              :total-visible="5"
              :loading="pageLoading"
              class="mb-3 mb-md-0"
            ></v-pagination>
            <v-select
              :items="perPageOptions"
              style="max-width: 60px; margin: 0; padding: 0; margin-left: 10px;"
              v-model="itemsPerPage"
            ></v-select>
          </div>
        </v-card>
      </v-container>
    </v-main> 
   </v-app>
</template>
<script>
  import { mapState } from "vuex";
  import moment from "moment";

  export default {
    data () {
      return {
        moment: moment,
        selectedType: 'pending',
        rewardwithdrawals: [],
        perPageOptions: ['10', '50', '100', '500'],
        itemsPerPage: '10',
        page: 1,
        paginationLength: 15,
        pageLoading: false  
      }
    },
    computed: {
      ...mapState([
        "auth",
      ]),
    },
    created() {
      this.getRewardWithdrawalData('pending');
    },
    methods: {
      hasHistory () { 
        return window.history.length > 2 
      },
      getRewardWithdrawalData(type = this.selectedType) {
        this.pageLoading = true;
        this.selectedType = type;        
        axios.post('/get-reward-withdrawal-data?page=' + this.page, { type: type,  itemsPerPage: this.itemsPerPage}).then(res => {          
          this.pageLoading = false;
          this.rewardwithdrawals = res.data.all_reward_withdrawal_requests.data;
          this.paginationLength = res.data.all_reward_withdrawal_requests.last_page;
          this.page = res.data.all_reward_withdrawal_requests.current_page;
        }).catch(e => {
          this.getRewardWithdrawalData();
          console.log(e.response);
        });
      },
      changeRewardWithdrawalStatus(id) {
        this.$swal({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Confirm'
        }).then((isConfirm) => {
          if (isConfirm.value == true) {
            axios.post('/chanage-reward-withdrawal-status', {id : id}).then(res => {
            this.rewardwithdrawals = this.rewardwithdrawals.filter(rewardwithdrawal => rewardwithdrawal.id !== id)
              this.$swal(
                'Status change!',
                'Your Reward Withdrawal status  change successfully!',
                'success'
              )
            });
          }
        });
      },
      RewardWithdrawalRejected(id) {
        this.$swal({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#0f9b0f',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Confirm'
        }).then((isConfirm) => {
          if (isConfirm.value == true) {
            axios.post('/reward-withdrawal-rejected', {id : id}).then(res => {
            this.rewardwithdrawals = this.rewardwithdrawals.filter(rewardwithdrawal => rewardwithdrawal.id !== id)
              this.$swal(
                'Reward Withdrawal Rejected!',
                'Your Reward Withdrawal Request Rejected  successfully!',
                'success'
              )
            });
          }
        });
      },
    }
  }
</script>
