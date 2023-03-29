<template>
  <v-app>
    <!-- ADD DIALOG -->
    <v-dialog v-model="addDialog" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="text-h5">Add Markets</span>
        </v-card-title>
        <v-card-text class="mt-4">
          <v-select
              :items="all_sports"
              label="Select Sport"
              v-model="selected_sport"
              solo
            ></v-select>
            <v-select
              :items="all_matches"
              label="Select Match"
              v-model="addData.match_slug"
              solo
            ></v-select>
          <v-text-field label="Name *" v-model="addData.name" required></v-text-field>
          <v-text-field label="Odds *" v-model="addData.odds" required></v-text-field>
          <div class="d-flex align-items-center">
            <v-switch inset color="primary" label="Block" v-model="addData.is_bet_block" type="checkbox" class="m-0">
            </v-switch>
          </div>
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
          <span class="text-h5">Edit Markets</span>
        </v-card-title>
        <v-card-text class="mt-4">
          <v-card-text class="mt-4">
            <v-select
              :items="all_matches"
              v-model="editData.match"
              solo
            ></v-select>
            <v-text-field label="Name *" v-model="editData.name" required></v-text-field>
            <v-text-field label="Odds *" v-model="editData.odds" required></v-text-field>
            <div class="d-flex align-items-center">
              <v-switch inset color="primary" label="Block" v-model="editData.is_bet_block" type="checkbox" class="m-0">
              </v-switch>
            </div>
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
            <span>All Markets</span>
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
                    Sport
                  </th>
                  <th class="text-left">
                    Match
                  </th>
                  <th class="text-left">
                    Name
                  </th>
                  <th class="text-left">
                    Result
                  </th>
                  <th class="text-left">
                    Odds
                  </th>
                  <th class="text-left">
                    Block
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
                  <td>{{ data&& data.match&& data.match.sport&& data.match.sport.name }}</td>
                  <td>{{ data&& data.match && data.match.name }}</td>
                  <td>{{ data.name }}</td>
                  <td>{{ data.result }}</td>
                  <td>{{ data.odds }}</td>
                  <!-- <td>{{ index }}</td>
                  <td>{{ index }}</td> -->
                  <!-- <td>{{ index }}</td>
                  <td>{{ index }}</td>
                  <td>{{ index}}</td> -->
                  <td class="text-right">
                    <v-switch inset color="primary" v-model="data.is_bet_block" @change="changeStatus(data.id, 'bet block')" type="checkbox" id="is_bet_block"></v-switch>
                  </td>
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
        all_sports: [],
        selected_sport: "",
        all_matches: [],
        addDialog: false,
        addData: {
          match_slug: "",
          name: "",
          odds: "",
          is_bet_block: true,
          is_active: true
        },
        editDialog: false,
        editData: {
          match: "",
          name: "",
          odds: "",
          is_bet_block: "",
          is_active: ""
        }
      }
    },
    watch: {
      selected_sport(sport_slug) {
        this.matchesData(sport_slug);
      }
    },
    created() {
      this.getData();
    },
    methods: {
      getData() {
        axios.get('/get-markets-data').then(res => {
          this.allData = res.data.all_data;
          this.all_sports = res.data.all_sports;
          console.log(this.allData[2].match.sport.name ,'alldata');
          console.log(this.allData[0].match.sport.name ,'alldata');
          console.log(this.allData[1].match.sport.name ,'alldata');
          console.log(this.allData[3].match.sport.name ,'alldata');
        }).catch(e => {
          console.log(e.response);
        });
      },
      matchesData(sport_slug) {
        axios.get('/get-sport-matches-data/' + sport_slug).then(res => {
          this.all_matches = res.data.all_matches;
        }).catch(e => {
          console.log(e.response);
        });
      },
      edit(id) {
        this.editData = this.allData.find(data => data.id === id);
        this.editDialog = true;
      },
      changeStatus(id, type) {
          axios.post('/update-market-status', { id: id, type: type }).then(res => {
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
        axios.post('/delete-market', {id: id}).then(res => {
          this.$toasted.info(res.data.success, {icon: 'check_circle'});
          this.allData = this.allData.filter(data => data.id !== id);
        }).catch(e => {
          console.log(e.response);
        });
      },
      submit() {
        axios.post('/add-market', { data: this.addData }).then(res => {
          if (res.data.success != null) {
            this.$toasted.success(res.data.success, {icon: 'check_circle'});
            this.addData = {
              match_slug: "",
              name: "",
              odds: "",
              is_bet_block: "",
              is_active: ""
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
        axios.post('/update-market-data', { id: this.editData.id, data: this.editData }).then(res => {
          if (res.data.success != null) {
            this.$toasted.info(res.data.success, {icon: 'check_circle'});
            this.editData = {
              match: "",
              name: "",
              odds: "",
              is_bet_block: "",
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
