<template>
  <v-app>
    <!-- ADD DIALOG -->
    <v-dialog v-model="addDialog" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Add Dummy Win</span>
        </v-card-title>
        <v-card-text class="mt-4">
          <div class="one-block">
            <span class="text-h5">Numbers</span>
            <v-row>
              <v-col cols="12" sm="6">
                <v-text-field label="From *" v-model="addData.from_number" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field label="To *" v-model="addData.to_number" required></v-text-field>
              </v-col>
            </v-row>
          </div>
          <div class="one-block">
            <span class="text-h5">Amount</span>
            <v-row>
              <v-col cols="12" sm="6">
                <v-text-field label="From *" v-model="addData.from_amount" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field label="To *" v-model="addData.to_amount" required></v-text-field>
              </v-col>
            </v-row>
          </div>
          <div class="one-block">
            <span class="text-h5">Win Text</span>
            <v-text-field label="Text *" v-model="addData.text" required></v-text-field>
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
          <span class="text-h5">Edit Dummy Win</span>
        </v-card-title>
        <v-card-text class="mt-4">
          <div class="one-block">
            <span class="text-h5">Numbers</span>
            <v-row>
              <v-col cols="12" sm="6">
                <v-text-field label="From *" v-model="editData.from_number" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field label="To *" v-model="editData.to_number" required></v-text-field>
              </v-col>
            </v-row>
          </div>
          <div class="one-block">
            <span class="text-h5">Amount</span>
            <v-row>
              <v-col cols="12" sm="6">
                <v-text-field label="From *" v-model="editData.from_amount" required></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field label="To *" v-model="editData.to_amount" required></v-text-field>
              </v-col>
            </v-row>
          </div>
          <div class="one-block">
            <span class="text-h5">Win Text</span>
            <v-text-field label="Text *" v-model="editData.text" required></v-text-field>
          </div>
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
            <span>Dummy Wins</span>
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
                    From Number
                  </th>
                  <th class="text-left">
                    To Number
                  </th>
                  <th class="text-left">
                    From Amount
                  </th>
                  <th class="text-left">
                    To Amount
                  </th>
                  <th class="text-left">
                    Text
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
                  <td>{{ data.from_number }}</td>
                  <td>{{ data.to_number }}</td>
                  <td>{{ data.from_amount }}</td>
                  <td>{{ data.to_amount }}</td>
                  <td>{{ data.text }}</td>
                  <td class="text-right">
                    <v-switch inset color="primary" v-model="data.is_active" @change="changeStatus(data.id, index)" type="checkbox" id="is_active"></v-switch>
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
          from_number: "",
          to_number: "",
          from_amount: "",
          to_amount: "",
          text: ""
        },
        editDialog: false,
        editData: {
          id: "",
          from_number: "",
          to_number: "",
          from_amount: "",
          to_amount: "",
          text: ""
        }
      }
    },
    created() {
      this.getData();
    },
    methods: {
      getData() {
        axios.get('/get-dummy-wins-data').then(res => {
          this.allData = res.data;
        }).catch(e => {
          console.log(e.response);
        });
      },
      edit(id) {
        this.editData = this.allData.find(data => data.id === id);
        this.editDialog = true;
      },
      changeStatus(id) {
        axios.post('/update-dummy-wins-status', { id: id }).then(res => {
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
        axios.post('/delete-dummy-wins', {id: id}).then(res => {
          this.$toasted.info(res.data.success, {icon: 'check_circle'});
          this.allData = this.allData.filter(data => data.id !== id);
        }).catch(e => {
          console.log(e.response);
        });
      },
      submit() {
        axios.post('/add-dummy-wins', { data: this.addData }).then(res => {
          if (res.data.success != null) {
            this.$toasted.success(res.data.success, {icon: 'check_circle'});
            this.addData = {
              from_number: "",
              to_number: "",
              from_amount: "",
              to_amount: "",
              text: ""
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
        axios.post('/update-dummy-wins-data', { id: this.editData.id, data: this.editData }).then(res => {
          if (res.data.success != null) {
            this.$toasted.info(res.data.success, {icon: 'check_circle'});
            this.editData = {
              from_number: "",
              to_number: "",
              from_amount: "",
              to_amount: "",
              text: ""
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
