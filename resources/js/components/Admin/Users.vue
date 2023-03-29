<template>
  <v-app>
    <v-main class="admin-index-pages">
      <v-container fluid>
        <v-card>
          <v-card-title>Users</v-card-title>
          <div class="d-flex user-action flex-wrap justify-space-between p-3">
            <v-btn-toggle mandatory color="primary">
              <v-btn @click="getUsers('active')">
                Active
              </v-btn>
              <v-btn @click="getUsers('inactive')">
                InActive
              </v-btn>
              <v-btn @click="getUsers('blocked')">
                Blocked
              </v-btn>
            </v-btn-toggle>
            <v-text-field label="Search" v-model="searchUser" class="mt-5 mt-md-0"></v-text-field>
          </div>
          <v-simple-table>
            <template v-slot:default>
              <thead class="grey lighten-3">
                <tr>
                  <th class="text-left">Name</th>                  
                  <th class="text-left">Phone</th>
                  <th class="text-left">Balance</th>
                  <th class="text-left">Secondary Balance</th>
                  <th class="text-left">Credit</th>                  
                  <th class="text-left">Password</th>
                  <th class="text-left">Registered</th>
                  <th class="text-left">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user, index) in users" v-if="user.is_admin == 0">
                  <td v-if="user.name != null">{{ user.name }}</td>
                  <td v-else>-</td>
                  <td>{{ user.phone }}</td>
                  <td>{{ user.balance }}</td>
                  <td>{{ user.loss_reward_balance }}</td>
                  <td>
                    <v-btn-toggle>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn fab icon x-small @click="deposit(user.id)" v-bind="attrs" v-on="on" color="green accent-3">D</v-btn>
                        </template>
                        <span>Deposit</span>
                      </v-tooltip>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn fab icon x-small  @click="withdrawal(user.id)" v-bind="attrs" v-on="on" color="red darken-2">W</v-btn>
                        </template>
                        <span>Withdrawal</span>
                      </v-tooltip>
                    </v-btn-toggle>
                  </td>
                  <td>{{ user.password_text }}</td>
                  <td>{{ moment(user.created_at).format("YYYY-MM-DD hh:mm:ss")  }}</td>
                  <td>
                    <v-btn-toggle>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn fab icon x-small :to="'/admin/user-details/' + user.id" v-bind="attrs" v-on="on">
                            <v-icon color="secondary">mdi-account-group</v-icon>           
                          </v-btn>
                        </template>
                        <span>Show level details</span>
                      </v-tooltip>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn fab icon x-small  @click="user_info(user)" v-bind="attrs" v-on="on">
                            <v-icon color="secondary">mdi-information</v-icon>           
                          </v-btn>
                        </template>
                        <span>Show info</span>
                      </v-tooltip>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn fab icon x-small  @click="openPassChangeModal(user)" v-bind="attrs" v-on="on">
                            <v-icon color="info">mdi-account-key</v-icon>
                          </v-btn>
                        </template>
                        <span>Change password</span>
                      </v-tooltip>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn fab icon x-small  @click="userBlock(user.id, index)" v-bind="attrs" v-on="on">
                            <v-icon color="green" v-if="usersTypeSelected == 'blocked'">mdi-lock-open-variant</v-icon>
                            <v-icon color="red" v-else>mdi-lock</v-icon>
                          </v-btn>
                        </template>
                        <span>Block/Unblock user</span>
                      </v-tooltip>
                    </v-btn-toggle>
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
    <!-- User detail modal -->
    <modal name="user-detail-modal" transition="nice-modal-fade" :clickToClose="false" :scrollable="true" :adaptive="true" width="100%" height="auto" :max-width="900" :max-height="1000">
      <div v-if="user_detail != null">
        <div class="modal-header flex-row justify-content-between">
          <h4 class="modal-title"><span class="text-green">User Details</span></h4>
          <a href="javascript:void(0)" class="default-close-btn" title="Close" @click="$modal.hide('user-detail-modal')">
            <i class="material-icons" style="color: #030303;">close</i>
          </a>
        </div>
        <div class="modal-body">
          <v-card class="mb-5">
            <v-card-title>Banks</v-card-title>
            <table class="table table-hover">
              <thead class="grey lighten-3">
                <tr>
                  <th class="text-left">Name</th>
                  <th class="text-left">Account</th>
                  <th class="text-left">Bank Name</th>
                  <th class="text-left">IFSC</th>
                  <th class="text-left">Phone</th>
                  <th class="text-left">State</th>
                  <th class="text-left">City</th>
                  <th class="text-left">Address</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="bank_detail in user_detail.banks">
                  <td>{{ bank_detail.actual_name }}</td>
                  <td>{{ bank_detail.bank_account }}</td>
                  <td>{{ bank_detail.IFSC }}</td>
                  <td>{{ bank_detail.bank_name }}</td>
                  <td>{{ bank_detail.mobile_number }}</td>
                  <td>{{ bank_detail.state }}</td>
                  <td>{{ bank_detail.city }}</td>
                  <td>{{ bank_detail.address }}</td>
                </tr>
              </tbody>
            </table>
            </v-simple-table>
          </v-card>
          <v-card>
            <v-card-title>Address</v-card-title>
            <table class="table table-hover">
              <thead class="grey lighten-3">
                <tr>
                  <th class="text-left">full name</th>
                  <th class="text-left">Phone</th>
                  <th class="text-left">Pincode</th>
                  <th class="text-left">State</th>
                  <th class="text-left">City</th>
                  <th class="text-left">Address Detail</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="address_detail in user_detail.addresses" v-if="address_detail != null">
                  <td>{{ address_detail.full_name }}</td>
                  <td>{{ address_detail.mobile_number }}</td>
                  <td>{{ address_detail.pincode }}</td>
                  <td>{{ address_detail.state }}</td>
                  <td>{{ address_detail.city }}</td>
                  <td>{{ address_detail.address_detail }}</td>
                </tr>
              </tbody>
            </table>
          </v-card>
        </div>
      </div>
    </modal>
    <!-- End user detail modal -->
    <!-- Deposit modal -->
    <modal name="deposit-modal" transition="nice-modal-fade" :clickToClose="false" :scrollable="true" :adaptive="true" width="100%" height="auto" :max-width="400" :max-height="1000">
      <div v-if="deposit_user_id != null">
        <div class="modal-header flex-row justify-content-between">
          <h4 class="modal-title"><span class="text-green">Deposit</span></h4>
          <a href="javascript:void(0)" class="default-close-btn" title="Close" @click="$modal.hide('deposit-modal')">
            <i class="material-icons" style="color: #030303;">close</i>
          </a>
        </div>
        <div class="modal-body">
          <v-form ref="form">
            <v-text-field label="Deposit Amount" type="number" v-model.trim="deposit_amount" :rules="userDeposit"></v-text-field>
          </v-form>
        </div>
        <div class="modal-footer">
          <v-btn small color="success" @click="depositSubmit" class="white--text">
            <span>Submit</span>
          </v-btn>
        </div>
      </div>
    </modal>
    <!-- End Depsoit modal -->
    <!-- Deposit modal -->
    <modal name="withdrawal-modal" transition="nice-modal-fade" :clickToClose="false" :scrollable="true" :adaptive="true" width="100%" height="auto" :max-width="400" :max-height="1000">
      <div v-if="withdrawal_user_id != null">
        <div class="modal-header flex-row justify-content-between">
          <h4 class="modal-title"><span class="text-green">Withdrawal</span></h4>
          <a href="javascript:void(0)" class="default-close-btn" title="Close" @click="$modal.hide('withdrawal-modal')">
            <i class="material-icons" style="color: #030303;">close</i>
          </a>
        </div>
        <div class="modal-body">
          <v-select
            :items="items"
            label="Withdrawal type"
            v-model="withdrawal_type"
            dense
          ></v-select>
          <v-form ref="form">
            <v-text-field label="Withdrawal Amount" type="number" v-model.trim="withdrawal_amount" :rules="userWithdrawal"></v-text-field>
          </v-form>
        </div>
        <div class="modal-footer">
          <v-btn small color="success" @click="withdrawalSubmit" class="white--text">
            <span>Submit</span>
          </v-btn>
        </div>
      </div>
    </modal>
    <!-- End Depsoit modal -->
    <!-- change Password modal -->
    <modal name="change-pass-modal" transition="nice-modal-fade" :clickToClose="false" :scrollable="true" :adaptive="true" width="100%" height="auto" :max-width="500" :max-height="1000">
      <div v-if="currentChooseUser != null">
        <div class="modal-header flex-row justify-content-between">
          <h4 class="modal-title">
            Change Password
          </h4>
          <a href="javascript:void(0)" class="default-close-btn" title="Close" @click="$modal.hide('change-pass-modal')">
            <i class="material-icons" style="color: #030303;">close</i>
          </a>
        </div>
        <div class="modal-body">
          <v-form ref="form">
            <v-text-field label="New Password" type="password" v-model.trim="passwordModalData.password" :rules="userPasswordErrors"></v-text-field>
          </v-form>
        </div>
        <div class="modal-footer">
          <v-btn small color="success" @click="changePassword" class="white--text">
            <span>Submit</span>
          </v-btn>
        </div>
      </div>
    </modal>
    <!-- End Password modal -->
   </v-app>
</template>
<script>
  import { mapState } from "vuex";
  import moment from "moment";
  export default {
  	data () {
      return {
        users: [],
        deposit_user_id: '',
        withdrawal_type: 'primary',
        deposit_amount: '',
        withdrawal_user_id: '',
        withdrawal_amount: '',
        items: ['primary', 'secondary'],
        usersTypeSelected: 'active',
        searchUser: '',
        moment: moment,
        user_detail: null,
        currentChooseUser: null,
        passwordModalData: {
          password: '',
        },
        userPasswordErrors: [
          v => !!v || 'Password is required',
        ],
        userDeposit: [
          v => !!v || 'Deposit Amount is required',          
        ],
        userWithdrawal: [
          v => !!v || 'Withdrawal Amount is required', 
        ],
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
        this.getUsers(this.usersTypeSelected, false);
      },
      itemsPerPage(val) {
        this.getUsers(this.usersTypeSelected, false);
      },
      searchUser(val) {
        if (val.length >= 3) {
          this.getUsers(this.usersTypeSelected, true);
        } else {
          this.getUsers(this.usersTypeSelected, false);
        }
      }
    },
    created() {
      this.getUsers('active');
    },
    methods: {
      hasHistory () { 
      	return window.history.length > 2 
      },
      getUsers(type = this.usersTypeSelected, isSearch = false) {
        this.pageLoading = true;
        this.usersTypeSelected = type;
        axios.post('/users?page=' + this.page, { type: type, isSearch: isSearch, searchUser: this.searchUser, itemsPerPage: this.itemsPerPage}).then(res => {
          console.log(res.data);
          this.pageLoading = false;
          this.users = res.data.users.data;
          this.paginationLength = res.data.users.last_page;
          this.page = res.data.users.current_page;
        }).catch(e => {
          this.getUsers();
          // console.log(e.response);
        });
      },
      userBlock(id, index) {
        axios.post('/user/block', {id : id}).then(res => {
          this.loading = false;
          if (res.data.success != null) {
            this.$toasted.success(res.data.success.toString(), {icon: 'check_circle'});
            this.users.splice(index, 1);
          } else if (res.data.error != null) {
            this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
          }
        }).catch(e => {
          this.$toasted.error('Please try again', {icon: 'not_interested'});
          console.log(e.response);
        });
      },
      user_info(user) {
        this.user_detail = user;
        this.$modal.show('user-detail-modal');
      },
      deposit(id) {
        this.deposit_user_id = id;
        this.$modal.show('deposit-modal');
      },
      depositSubmit() {
        axios.post('/add-deposit-amount', {id: this.deposit_user_id, amount: this.deposit_amount}).then(res => {
          console.log(res.data);
          if (res.data.error != null) {
            this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'})
          } else if (res.data.success != null) {
            this.$toasted.show('Deposit amount add Successfully', {icon: 'update'})
            this.$modal.hide('deposit-modal');
          }
        }).catch((e) => {
          this.$toasted.error('Please try again', {icon: 'not_interested'});
          console.log(e.response);
        });
      },
      withdrawal(id) {
        this.withdrawal_user_id = id;
        this.$modal.show('withdrawal-modal');
      },
       withdrawalSubmit() {
        axios.post('/withdrawal-amount', {id: this.withdrawal_user_id, amount: this.withdrawal_amount, type: this.withdrawal_type}).then(res => {
          console.log(res.data);
          if (res.data.error != null) {
            this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'})
          } else if (res.data.success != null) {
            this.$toasted.show('Amount Withdrawal Successfully', {icon: 'update'})
            this.$modal.hide('withdrawal-modal');
          }
        }).catch((e) => {
          this.$toasted.error('Please try again', {icon: 'not_interested'});
          console.log(e.response);
        });
      },
      openPassChangeModal(user) {
        this.currentChooseUser = user;
        this.$modal.show('change-pass-modal');
      },
      changePassword() {
        axios.post('/change-user-password', {id: this.currentChooseUser.id, password: this.passwordModalData.password}).then(res => {
          if (res.data.error != null) {
            this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'})
          } else if (res.data.success != null) {
            this.$modal.hide('change-pass-modal');
            this.$toasted.show('Password has been changed successfully', {icon: 'update'})
            this.passwordModalData.password = '';
          }
        }).catch((e) => {
          this.$toasted.error('Please try again', {icon: 'not_interested'});
          console.log(e.response);
        });
      },
    }
  }
</script>
<style scoped>
  .user-action .v-input {
    max-width: 300px !important;
  }
  @media (max-width:  600px) {
    .user-action .v-input {
      max-width: 100% !important;
    }
  }
</style>
