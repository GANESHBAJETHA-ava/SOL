<template>
  <v-app>
    <!-- ADD DIALOG -->
    <v-dialog v-model="addDialog" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Add Sport</span>
        </v-card-title>
        <v-card-text class="mt-4">
          <v-text-field label="Name *" v-model="addData.name" required></v-text-field>
          <div class="d-flex align-items-center">
            <v-switch inset color="primary" label="Active" v-model="addData.is_active" type="checkbox" class="m-0">
            </v-switch>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error darken-1" text @click="addDialog = false">
            Close
          </v-btn>
          <v-btn color="blue darken-1" text @click="submit">
            Add
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- EDIT DIALOG -->
    <v-dialog v-model="editDialog" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Edit Sport</span>
        </v-card-title>
        <v-card-text class="mt-4">
          <v-card-text class="mt-4">
            <v-text-field label="Name *" v-model="editData.name" required></v-text-field>
            <div class="d-flex align-items-center">
              <v-switch inset color="primary" label="Active" v-model="editData.is_active" type="checkbox" class="m-0">
              </v-switch>
            </div>
          </v-card-text>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error darken-1" text @click="editDialog = false">
            Close
          </v-btn>
          <v-btn color="info darken-1" text @click="update">
            Update
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-main class="admin-index-pages">
      <v-container fluid>
        <v-card>
          <v-card-title class="justify-content-between align-items-center">
            <span>All Sports</span>
            <v-btn color="primary" @click="addDialog = true">
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
                    Active
                  </th>
                  <th class="text-right">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data, index) in allData">
                  <td>{{ data.name }}</td>
                  <td class="text-right">
                    <v-switch inset color="primary" v-model="data.is_active" @change="changeStatus(data.id, 'active')" type="checkbox" id="is_active"></v-switch>
                  </td>
                  <td class="text-right">
                    <v-btn fab icon x-small color="info" class="border" @click="edit(data.id)">
                      <v-icon>
                        mdi-square-edit-outline
                      </v-icon>
                    </v-btn>
                    <v-btn fab icon x-small color="red" class="border" @click="deleteData(data.id, index)">
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
        allData: [],
        addDialog: false,
        addData: {
          name: "",
          is_active: true
        },
        editDialog: false,
        editData: {
          name: "",
          is_active: ""
        }
      }
    },
    created() {
      this.getData();
    },
    methods: {
      getData() {
        axios.get('/get-sports-data').then(res => {
          console.log(res.data);
          this.allData = res.data.all_data;
        }).catch(e => {
          console.log(e.response);
        });
      },
      edit(id) {
        this.editData = this.allData.find(data => data.id === id);
        this.editDialog = true;
      },
      changeStatus(id) {
        axios.post('/update-sports-status', { id: id }).then(res => {
          if (res.data.success != null) {
            this.$toasted.info(res.data.success, {icon: 'check_circle'});
          } else if (res.data.error != null) {
            this.$toasted.error(res.data.error, {icon: 'not_interested'});
          }
        }).catch(e => {
          console.log(e.response);
        });
      },
      deleteData(id, index) {
        axios.post('/delete-sport', {id: id}).then(res => {
          this.$toasted.info(res.data.success, {icon: 'check_circle'});
          this.allData = this.allData.filter(data => data.id !== id);
        }).catch(e => {
          console.log(e.response);
        });
      },
      submit() {
        axios.post('/add-sport', { data: this.addData }).then(res => {
          if (res.data.success != null) {
            this.$toasted.success(res.data.success, {icon: 'check_circle'});
            this.addData = {
              name: "",
              is_active: true
            };
            this.addDialog = false;
            this.getData();
          } else {
            this.$toasted.error(res.data.error, {icon: 'not_interested'});
          }
        }).catch(e => {
          console.log(e.response);
        });
      },
      update() {
        axios.post('/update-sport-data', { id: this.editData.id, data: this.editData }).then(res => {
          if (res.data.success != null) {
            this.$toasted.info(res.data.success, {icon: 'check_circle'});
            this.editData = {
              name: "",
              is_active: ""
            };
            this.editDialog = false;
            this.getData();
          } else if (res.data.error != null) {
            this.$toasted.error(res.data.error, {icon: 'not_interested'});
          }
        }).catch(e => {
          console.log(e.response);
        });
      }
    }
  }
</script>
