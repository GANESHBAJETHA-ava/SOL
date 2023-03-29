<template>
  <v-app id="Bankcard">
  	<v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Bank Card</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn large :ripple="true" to="add/bank/card" icon color="#FFFFFF">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">
    	<v-container>
        <div class="align-center mt-2">
          <div v-for="bank in banks">
            <v-row>
              <v-col cols="9">
                <div class="d-flex">
                  <v-avatar color="grey lighten-1 mr-5" size="50"><span class="material-icons">credit_card</span></v-avatar>
                  <div>
                    <h6 class="font-weight-medium">{{ bank.actual_name }}</h6>
                    <p class="font-weight-regular mb-0">{{ bank.bank_account }}</p>
                  </div>
                </div>
              </v-col>
              <v-col cols="3" class="d-flex justify-end align-items-center">
                <div class="d-flex justify-end">
                  <router-link :to="'/edit/bank/card/'+ bank.id +/edit/">
                    <v-icon size="28" class="mr-2">mdi-pencil</v-icon>
                  </router-link>
                  <v-icon size="28" @click="deleteBank(bank.id)">mdi-delete</v-icon>
                </div>
              </v-col>
            </v-row>
            <v-divider></v-divider>
          </div>
        </div>
    	</v-container>
    </v-main>	
   </v-app>
</template>
<script>
  export default {
    data () {
      return {
        ripple: true,
        banks: [],
      }
    },
    created() {
      this.getBanks();
    },
    mounted() {
      console.log('Component mounted.')
    },
    methods: {
      hasHistory () { return window.history.length > 2 },
      getBanks() {
        axios.get('/banks').then(res => {
          this.banks = res.data.banks;
        }).catch(e => {
          this.getBanks();
          console.log(e.response);
        });
      },
      deleteBank(id) {
        this.$swal({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((isConfirm) => {
          if (isConfirm.value == true) {
            axios.get('/delete/bank/'+id).then(res => {
            this.banks = this.banks.filter(bank => bank.id !== id)
              this.$swal(
                'Deleted!',
                'Your Bank Account has been deleted!',
                'success'
              )
            });
          }
        });
      }
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