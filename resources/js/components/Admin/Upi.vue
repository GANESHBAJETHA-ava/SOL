<template>
  <v-app class="admin-index-pages">
    <v-main>
      <v-container fluid>
        <v-card>
          <v-card-title class="justify-content-between">
            <span>Bank UPIs</span>
            <v-btn to="add/upi" color="primary">
              <v-icon left dark>
                mdi-folder-multiple-plus
              </v-icon>
              Add
            </v-btn>
          </v-card-title>
          <v-simple-table>
            <template v-slot:default>
              <thead class="grey lighten-3">
                <tr>
                  <th class="text-left">
                    Name
                  </th>
                  <th class="text-left">
                    Upi id
                  </th>
                  <th class="text-left">
                    Type
                  </th>
                  <th class="text-left">
                    Status
                  </th>
                  <th class="text-right">
                    Delete
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(upi, index) in upis">
                  <td>{{ upi.name }}</td>
                  <td>{{ upi.upi_id }}</td>
                  <td>{{ upi.type }}</td>
                  <td>
                    <v-switch inset color="primary" v-model="upi.is_active" @change="changeStatus(upi.id, index)" type="checkbox" id="is_active"></v-switch>
                  </td>
                  <td class="text-right">
                    <v-btn fab icon x-small color="red" class="border" @click="deleteUpi(upi.id)">
                      <v-icon>
                        mdi-delete
                      </v-icon>
                    </v-btn>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
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
        switch1: true,
        upis: [],
        
      }
    },
    computed: {
      ...mapState([
        "auth",
      ]),
    },
    created() {
      this.getUpis();
    },
    methods: {
      hasHistory () { 
      	return window.history.length > 2 
      },
      getUpis() {
        axios.get('/upis').then(res => {
          this.upis = res.data.upis;
        }).catch(e => {
          this.getUpis();
          console.log(e.response);
        });
      },
      changeStatus(id, index) {
        axios.post('/change-upi-status', {id : id}).then(res => {
          this.loading = false;
          if (res.data.show != null) {
            this.$toasted.show('Status Change successfully', {icon: 'power_settings_new'});
          } else if (res.data.error != null) {
            this.upis[index].is_active = !this.upis[index].is_active;
            this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
          }
        }).catch(e => {
          this.$toasted.error('Please try again', {icon: 'not_interested'});
          console.log(e.response);
        });
      },
      deleteUpi(id) {
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
            axios.get('/delete/upi/'+id).then(res => {
            this.upis = this.upis.filter(upi => upi.id !== id)
              this.$swal(
                'Deleted!',
                'Your Upi id has been deleted!',
                'success'
              )
            });
          }
        });
      },
    }
  }
</script>
