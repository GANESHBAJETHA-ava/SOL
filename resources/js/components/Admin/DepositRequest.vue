<template>
  <v-app>
    <v-main class="admin-index-pages">
      <v-container fluid>
        <v-card>
          <v-card-title class="justify-content-between">
            <span>Deposits <v-chip>{{ deposites.length }}</v-chip> </span>
            <v-btn-toggle mandatory class="mt-4 mt-md-0" color="primary">
              <v-btn @click="getDepositData('pending')">
                Pending
              </v-btn>
              <v-btn @click="getDepositData('success')">
                Success
              </v-btn>
              <v-btn @click="getDepositData('rejected')">
                Rejected
              </v-btn>
            </v-btn-toggle>
          </v-card-title>
          <v-simple-table>
            <template v-slot:default>
              <thead class="grey lighten-3">
                <tr>
                  <th class="text-left">Phone</th>
                  <th class="text-left">Balance</th>
                  <th class="text-left">Amount</th>
                  <th class="text-left">Type</th>
                  <th class="text-left">To UPI</th>     
                  <th class="text-left">Utr</th>     
                  <th class="text-left">Created</th>
                  <th class="text-left">Status</th>       
                  <th class="text-left" v-if="selectedType == 'pending'">Reject</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(deposite, index) in deposites">
                  <td>{{ deposite.user.phone }}</td>
                  <td>{{ deposite.user.balance }}</td>          
                  <td><v-chip small color="success"><b>{{ deposite.amount }}</b></v-chip></td>
                  <td>{{ deposite.type }}</td>
                  <td>{{ deposite.to_upi }}</td>
                  <td>
                    <v-chip color="primary" style="letter-spacing: 1.5px;" v-if="deposite.utr" :small="selectedType != 'pending'"><b>{{ deposite.utr }}</b></v-chip>
                    <span v-else>-</span>
                  </td>
                  <td>{{ moment(deposite.created_at).format("YYYY-MM-DD hh:mm:ss") }}</td>
                  <td>
                    <v-chip small color="success" v-if="deposite.is_cleared == 1"><b>Success</b></v-chip>
                    <v-chip small color="error" v-else-if="deposite.is_rejected == 1"><b>Rejected</b></v-chip>
                    <v-chip small rounded v-else color="warning" @click="changeDepositStatus(deposite.id)" dark>Pending</v-chip>
                  </td>
                  <td v-if="deposite.is_cleared == 0 && deposite.is_rejected == 0">
                    <v-btn icon color="red accent-2" @click="depositeRejected(deposite.id)">
                      <v-icon dark size="30">
                        mdi-close-box
                      </v-icon>
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
        deposites: [],
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
        this.getDepositData(this.selectedType, false);
      },
      itemsPerPage(val) {
        this.getDepositData(this.selectedType, false);
      },
    },
    created() {
      this.getDepositData('pending');
    },
    methods: {
      hasHistory () { 
      	return window.history.length > 2 
      },
      getDepositData(type = this.selectedType) {
        this.pageLoading = true;
        this.selectedType = type;

        axios.post('/get-deposit-data?page=' + this.page, { type: type,  itemsPerPage: this.itemsPerPage}).then(res => {
          this.pageLoading = false;
          this.deposites = res.data.all_deposit_requests.data;
          this.paginationLength = res.data.all_deposit_requests.last_page;
          this.page = res.data.all_deposit_requests.current_page;
        }).catch(e => {
          this.getDepositData();
          console.log(e.response);
        });
      },
      changeDepositStatus(id) {
        var amount = this.deposites.find(deposit => deposit.id === id).amount;
        this.$swal({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Confirm',
          input: 'number',
          inputValue: amount
        }).then((val) => {
          if (val.isConfirmed == true) {
            axios.post('/chanage-deposit-status', {id : id, editAmount: val.value}).then(res => {
            this.deposites = this.deposites.filter(deposit => deposit.id !== id)
              this.$swal(
                'Status change!',
                'Your Deposit status  change successfully!',
                'success'
              )
            });
          }
        });
      },
      depositeRejected(id) {
        this.$swal({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#0f9b0f',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Confirm!'
        }).then((isConfirm) => {
          if (isConfirm.value == true) {
            axios.post('/deposit-rejected', {id : id}).then(res => {
            this.deposites = this.deposites.filter(deposit => deposit.id !== id)
              this.$swal(
                'Deposit Request Rejected',
                'Your Deposit Request rejected successfully!',
                'success'
              )
            });
          }
        });
      },
    }
  }
</script>

