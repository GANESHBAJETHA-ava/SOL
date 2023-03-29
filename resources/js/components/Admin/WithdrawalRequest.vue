<template>
  <v-app>
    <v-main class="admin-index-pages">
      <v-container fluid>
        <v-card>
          <v-card-title class="justify-content-between">
            <span>Withdrawals <v-chip>{{ withdrawals.length }}</v-chip> </span>
            <v-btn-toggle mandatory class="mt-4 mt-md-0" color="primary">
              <v-btn @click="getWithdrawalData('pending')">
                Pending
              </v-btn>
              <v-btn @click="getWithdrawalData('success')">
                Success
              </v-btn>
              <v-btn @click="getWithdrawalData('rejected')">
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
                <tr v-for="(withdrawal, index) in withdrawals">
                  <td>{{ withdrawal.user.phone }}</td>
                  <td><v-chip small color="success"><b>{{ withdrawal.amount }}</b></v-chip></td>
                  <td>{{ withdrawal.bank.actual_name }}</td>
                  <td>{{ withdrawal.bank.IFSC }}</td>
                  <td>{{ withdrawal.bank.bank_name }}</td>
                  <td>{{ withdrawal.bank.bank_account }}</td>
                  <td>{{ withdrawal.bank.mobile_number }}</td>
                  <td>{{ withdrawal.user.balance }}</td>
                  <td>{{ moment(withdrawal.created_at).format("YYYY-MM-DD hh:mm:ss") }}</td>
                  <td>
                    <v-chip small color="success" v-if="withdrawal.status == 1"><b>Success</b></v-chip>
                    <v-chip small color="error" v-else-if="withdrawal.is_rejected == 1"><b>Rejected</b></v-chip>
                    <v-chip small rounded v-else color="warning" @click="changeWithdrawalStatus(withdrawal.id)" dark>Pending</v-chip>
                  </td>
                  <td v-if="withdrawal.status == 0 && withdrawal.is_rejected == 0">
                    <v-btn icon color="red accent-2" @click="WithdrawalRejected(withdrawal.id)">
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
        withdrawals: [],
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
    watch: {
      page(val) {
        this.getWithdrawalData();
      },
      itemsPerPage(val) {
        this.getWithdrawalData();
      },
    },
    created() {
      this.getWithdrawalData('pending');
    },
    methods: {
      hasHistory () { 
      	return window.history.length > 2 
      },
      getWithdrawalData(type = this.selectedType) {
        this.pageLoading = true;
        this.selectedType = type;
        axios.post('/get-withdrawal-data?page=' + this.page, { type: type,  itemsPerPage: this.itemsPerPage}).then(res => {
          this.pageLoading = false;
          console.log(res.data);
          this.withdrawals = res.data.all_withdrawal_requests.data;
          this.paginationLength = res.data.all_withdrawal_requests.last_page;
          this.page = res.data.all_withdrawal_requests.current_page;
        }).catch(e => {
          this.getWithdrawalData();
          console.log(e.response);
        });
      },
      changeWithdrawalStatus(id) {
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
            axios.post('/chanage-withdrawal-status', {id : id}).then(res => {
            this.withdrawals = this.withdrawals.filter(withdrawal => withdrawal.id !== id)
              this.$swal(
                'Status change!',
                'Your Withdrawal status  change successfully!',
                'success'
              )
            });
          }
        });
      },
      WithdrawalRejected(id) {
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
            axios.post('/withdrawal-rejected', {id : id}).then(res => {
            this.withdrawals = this.withdrawals.filter(withdrawal => withdrawal.id !== id)
              this.$swal(
                'Withdrawal Request Rejected',
                'Your Withdrawal Request Rejected  successfully!',
                'success'
              )
            });
          }
        });
      },
    }
  }
</script>
