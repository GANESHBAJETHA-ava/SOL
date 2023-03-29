<template>
  <v-app id="Address">
    <v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Addresses</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn large :ripple="true" to="add/address" icon color="#FFFFFF">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">
      <v-container fluid>
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">Full Name</th>
                <th class="text-left">Mobile Number</th>
                <th class="text-left">Pincode</th>
                <th class="text-left">State</th>
                <th class="text-left">City</th>
                <th class="text-left">Address Detail</th>
                <th class="text-left">Action</th>                
              </tr>
            </thead>
            <tbody>
              <tr v-for="address in addresses">
                <td>{{ address.full_name }}</td>
                <td>{{ address.mobile_number }}</td>
                <td>{{ address.pincode }}</td>
                <td>{{ address.state }}</td>
                <td>{{ address.city }}</td>
                <td>{{ address.address_detail }}</td>
                <td>
                  <div class="d-flex">
                    <router-link :to="'/edit/address/card/'+ address.id +/edit/">
                      <v-icon size="28" class="mr-2">mdi-pencil</v-icon>
                    </router-link>
                    <v-icon size="28" @click="deleteAddress(address.id)">mdi-delete</v-icon>
                  </div>
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-container>
    </v-main> 
   </v-app>
</template>
<script>
  export default {
    data () {
      return {
        ripple: true,
        addresses: [],
      }
    },
    created() {
      this.getAddresses();
    },
    mounted() {
      console.log('Component mounted.')
    },
    methods: {
      hasHistory () { return window.history.length > 2 },
      getAddresses() {
        axios.get('/addresses').then(res => {
          console.log(res.data);
          this.addresses = res.data.addresses;
        }).catch(e => {
          this.getAddresses();
          console.log(e.response);
        });
      },
      deleteAddress(id) {
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
            axios.get('/delete/address/'+id).then(res => {
            this.addresses = this.addresses.filter(address => address.id !== id)
              this.$swal(
                'Deleted!',
                'Your Address has been deleted!',
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