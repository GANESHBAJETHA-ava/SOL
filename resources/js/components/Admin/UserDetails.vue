<template>
  <v-app>
    <v-main class="admin-index-pages" v-if="user != null">
      <v-container fluid>
        <v-card>
          <v-card-title>
            <v-btn icon @click="hasHistory() ? $router.go(-1) : $router.push('/')">
              <span class="material-icons">arrow_back_ios</span>
            </v-btn>
            <span class="ml-2">{{ user.phone }} Details</span>
          </v-card-title>
          <v-row class="justify-center mb-4 mt-0">
            <v-col cols="12" class="d-flex justify-center mt-5">
              <h4>Bonus: ₹ <span>{{ user.commission == null ? 0 : user.commission }}</span></h4>
            </v-col>
            <v-col cols="6" class="d-flex justify-center">
              <h5 class="text-center">Total People <br><span>{{ total_people }}</span></h5>
            </v-col> 
            <v-col cols="6" class="d-flex justify-center">
              <h5 class="text-center">Contribution <br><span>₹ {{ contribution }}</span></h5>
            </v-col>
          </v-row>
          <v-card>
            <v-tabs fixed-tabs background-color="grey lighten-3">
              <v-tabs-slider color="green accent-4"></v-tabs-slider>
              <v-tab @click="selectLevel(1)" color="green">Level 1 ({{ level_one_users.length }})</v-tab>
              <v-tab @click="selectLevel(2)" color="green">Level 2 ({{ level_two_users.length }})</v-tab>
              <v-tab @click="selectLevel(3)" color="green">Level 3 ({{ level_three_users.length }})</v-tab>
            </v-tabs>
            <v-card-title>
              <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details class="m-0 p-0"></v-text-field>
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="allUsers"
              :search="search"
              :disableFiltering="disableFiltering"
              :disableSort="disableSort"
              mobile-breakpoint="0"
            >No Data Available</v-data-table>
          </v-card>
        </v-card>
      </v-container>
    </v-main>
   </v-app>
</template>
<script>
  import { mapState } from "vuex";
  export default {
    props: ["id"],
    data () {
      return {
        search: '',
        isDataLoading: false,
        bonus: 0,
        total_people: 0,
        contribution: 0,
        disableSort: true,
        disableFiltering: false,
        headers: [
          { text: 'Id', align: 'center', filterable: false, value: 'id' },
          { text: 'Phone', align: 'center', value: 'phone', filterable: true },
          { text: 'Water reward', align: 'center', value: 'water_reward', filterable: false, },
          { text: 'First reward', align: 'center', value: 'first_reward', filterable: false, },
        ],
        allUsers: [],
        level_one_users: [],
        level_two_users: [],
        level_three_users: [],
        user: null
      }
    },
    computed: {
      ...mapState([
        "auth",
      ]),
    },
    created() {
      this.getUser();
      this.getData();
    },
    methods: {
      hasHistory () { return window.history.length > 2 },
      getUser() {
        axios.get('/get-user/' + this.$props.id).then(res => {
          this.user = res.data.user;
        }).catch(e => {
          console.log(e.response);
        });
      },
      getData() {
        axios.get('/get-user-levels/' + this.$props.id).then(res => {
          this.level_one_users = res.data.one_levels;
          this.level_two_users = res.data.two_levels;
          this.level_three_users = res.data.three_levels;
          this.total_people = res.data.total_people;
          this.contribution = res.data.contribution;
          this.bonus = res.data.bonus;
          this.selectLevel(1);
        }).catch(e => {
          console.log(e.response);
        });
      },
      selectLevel($level = 1) {
        if ($level == 1) {
          this.allUsers = this.level_one_users;
        } else if ($level == 2) {
          this.allUsers = this.level_two_users;
        } else if ($level == 3) {
          this.allUsers = this.level_three_users;
        } else {
          this.allUsers = this.level_one_users;
        }
      }
    }
  }
</script>
<style scoped>
  .v-tab--active {
    color: #4CAF50 !important;
  }
</style>
