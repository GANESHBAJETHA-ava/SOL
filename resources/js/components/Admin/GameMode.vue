<template>
  <v-app id="dashboard">
    <v-main class="admin-index-pages game-mode">
      <v-container fluid>
        <v-card>
          <v-card-title class="justify-content-between align-items-center">
            <span>Game Mode</span>
            <Countdown :end="period_countdown.toString()" v-if="period_countdown != null"></Countdown>
          </v-card-title>
          <v-card-text class="mt-4">
            <v-select
              :items="allGames"
              item-text="title"
              item-value="slug"
              label="select game"
              v-model="selectedGameSlug"
              class="mb-3"
              solo
            ></v-select>
            <v-card v-if="currentPeriod != null">
              <v-card-title class="justify-content-between align-items-center">
                <h5 class="m-0">Period: {{ currentPeriod.period }}</h5>
                <v-switch v-model="periodModeManual" inset :label="'' + modeText" class="m-0 mt-2" color="error" @click="updatePeriodMode"></v-switch>
              </v-card-title>
              <table class="table table-hover" v-if="selectedGameSlug== 'win'">
                <tbody>
                  <tr>
                    <th class="pl-4" width="120px">0</th>
                    <th class="border pl-4"><span :class="currentPeriodBook[0] < 0 ? 'red--text' : (currentPeriodBook[0] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[0] > 0">+</span>{{ currentPeriodBook[0] | addCommas }}</span></th>
                  </tr>
                  <tr>
                    <th class="pl-4" width="120px">1</th>
                    <th class="border pl-4"><span :class="currentPeriodBook[1] < 0 ? 'red--text' : (currentPeriodBook[1] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[1] > 0">+</span>{{ currentPeriodBook[1] | addCommas }}</span></th>
                  </tr>
                  <tr>
                    <th class="pl-4" width="120px">2</th>
                    <th class="border pl-4"><span :class="currentPeriodBook[2] < 0 ? 'red--text' : (currentPeriodBook[2] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[2] > 0">+</span>{{ currentPeriodBook[2] | addCommas }}</span></th>
                  </tr>
                  <tr>
                    <th class="pl-4" width="120px">3</th>
                    <th class="border pl-4"><span :class="currentPeriodBook[3] < 0 ? 'red--text' : (currentPeriodBook[3] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[3] > 0">+</span>{{ currentPeriodBook[3] | addCommas }}</span></th>
                  </tr>
                  <tr>
                    <th class="pl-4" width="120px">4</th>
                    <th class="border pl-4"><span :class="currentPeriodBook[4] < 0 ? 'red--text' : (currentPeriodBook[4] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[4] > 0">+</span>{{ currentPeriodBook[4] | addCommas }}</span></th>
                  </tr>
                  <tr>
                    <th class="pl-4" width="120px">5</th>
                    <th class="border pl-4"><span :class="currentPeriodBook[5] < 0 ? 'red--text' : (currentPeriodBook[5] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[5] > 0">+</span>{{ currentPeriodBook[5] | addCommas }}</span></th>
                  </tr>
                  <tr>
                    <th class="pl-4" width="120px">6</th>
                    <th class="border pl-4"><span :class="currentPeriodBook[6] < 0 ? 'red--text' : (currentPeriodBook[6] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[6] > 0">+</span>{{ currentPeriodBook[6] | addCommas }}</span></th>
                  </tr>
                  <tr>
                    <th class="pl-4" width="120px">7</th>
                    <th class="border pl-4"><span :class="currentPeriodBook[7] < 0 ? 'red--text' : (currentPeriodBook[7] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[7] > 0">+</span>{{ currentPeriodBook[7] | addCommas }}</span></th>
                  </tr>
                  <tr>
                    <th class="pl-4" width="120px">8</th>
                    <th class="border pl-4"><span :class="currentPeriodBook[8] < 0 ? 'red--text' : (currentPeriodBook[8] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[8] > 0">+</span>{{ currentPeriodBook[8] | addCommas }}</span></th>
                  </tr>
                  <tr>
                    <th class="pl-4" width="120px">9</th>
                    <th class="border pl-4"><span :class="currentPeriodBook[9] < 0 ? 'red--text' : (currentPeriodBook[9] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[9] > 0">+</span>{{ currentPeriodBook[9] | addCommas }}</span></th>
                  </tr>
                  <tr>
                    <th class="pl-4" width="120px">Jackpot</th>
                    <th class="border pl-4"><span :class="currentPeriodBook[10] < 0 ? 'red--text' : (currentPeriodBook[10] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[10] > 0">+</span>{{ currentPeriodBook[10] | addCommas }}</span></th>
                  </tr>
                </tbody>
              </table>
              <table class="table table-hover" v-if="selectedGameSlug== 'coin-flip'">
                <tbody>
                  <tr>
                    <th class="pl-4" width="120px">Head</th>
                    <th class="border pl-4">
                      <span :class="currentPeriodBook[0] < 0 ? 'red--text' : (currentPeriodBook[0] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[0] > 0"></span>{{ currentPeriodBook[0] | addCommas }}</span></th>
                  </tr>
                  <tr>
                    <th class="pl-4" width="120px">Tail</th>
                    <th class="border pl-4"><span :class="currentPeriodBook[1] < 0 ? 'red--text' : (currentPeriodBook[1] > 0 ? 'green--text' : '')"><span v-if="currentPeriodBook[1] > 0">+</span>{{ currentPeriodBook[1] | addCommas }}</span></th>
                  </tr>
                </tbody>
              </table>
            </v-card>
            <v-card>
              <v-card-title class="justify-content-between align-items-center" v-if="selectedGameSlug== 'win'">
                <h5 class="m-0 mb-3 mb-md-0 mr-4">Result</h5>
                <v-select
                  :items="resultOptions"
                  label="Select Result"
                  v-model="selectedResult"
                  class="mb-3 mb-md-0 mr-4"
                  solo
                  dense
                ></v-select>
                <v-btn color="success" @click="submitResult">Submit</v-btn>
              </v-card-title>
              <v-card-title class="justify-content-between align-items-center" v-if="selectedGameSlug== 'coin-flip'">
                <h5 class="m-0 mb-3 mb-md-0 mr-4">Result</h5>
                <v-select
                  :items="coinresultOptions"
                  label="Select Result"
                  v-model="selectedResult"
                  class="mb-3 mb-md-0 mr-4"
                  solo
                  dense
                ></v-select>
                <v-btn color="success" @click="submitResult">Submit</v-btn>
              </v-card-title>
            </v-card>
            <v-divider class="mt-8 mb-8"></v-divider>
            <v-card>
              <v-card-title class="justify-content-between align-items-center">
                <h5 class="m-0">Old Periods</h5>
              </v-card-title>
              <v-data-table
                :disableSort="disableSort"
                :headers="headers"
                :items="oldPeriods"
                :search="search"
                mobile-breakpoint="0"
                :loading="oldPeriodsLoading"
              >
                <template v-slot:item.result="{ item }">
                  {{ item.result }}
                </template>
                <template v-slot:item.is_manual="{ item }">
                  {{ item.is_manual == 1 ? "Manual" : "Auto" }}
                </template>
              </v-data-table>
            </v-card>
          </v-card-text>
        </v-card>
      </v-container>
    </v-main>
    <!-- SNACKBARS -->
    <v-snackbar v-model="generalError" timeout="1500" elevation="24">
      Please try again
      <template v-slot:action="{ attrs }">
        <v-btn color="pink" text v-bind="attrs" @click="generalError = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </v-app>
</template>
<script>
  import { mapState } from "vuex";
  import Countdown from 'vuejs-countdown';
  export default {
    components: { Countdown },
  	data () {
      return {
        allGames: [],
        selectedGameSlug: null,
        period_countdown: null,
        currentPeriod: null,
        periodModeManual: false,
        generalError: false,
        currentPeriodBook: [],
        resultOptions: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "jackpot"],
        coinresultOptions: ["head", "tail"],        
        selectedResult: "0",
        oldPeriods: [],
        disableSort: true,
        headers: [
          { text: 'Period', align: 'center', value: 'period' },
          { text: 'Price', align: 'center', value: 'price_money' },
          { text: 'Number', align: 'center', value: 'number' },
          { text: 'Result', align: 'center', value: 'result' },
          { text: 'Mode', align: 'center', value: 'is_manual' },
        ],
        search: '',
        oldPeriodsLoading: true,
        init: true
      }
    },
    computed: {
      modeText() {
        if (this.periodModeManual) {
          return "Manual";
        } else {
          return "Auto";
        }
      },
      selectedGame() {
        return this.allGames.find(game => game.slug == this.selectedGameSlug);
      }
    },
    watch: {
      selectedGameSlug(val) {
        this.getCurrentPeriod();
        this.getOldPeriods();
      }
    },
    filters: {
    	addCommas(val) {
	      var val = parseFloat(val).toFixed(2);
	      val += '';
	      var x = val.split('.');
	      var x1 = x[0];
	      var x2 = x.length > 1 ? '.' + x[1] : '';
	      var rgx = /(\d+)(\d{3})/;
	      while (rgx.test(x1)) {
	          x1 = x1.replace(rgx, '$1' + ',' + '$2');
	      }
	      var total = x1 + x2;
	      return total;
	    }
    },
    created() {
      this.getAllGames();
    },
    methods: {
      getAllGames() {
        axios.get('/get-all-games').then(res => {
          console.log(res.data);
          this.allGames = res.data.all_games;
          this.selectedGameSlug = this.allGames[0].slug;
          this.getCurrentPeriod();
          this.getOldPeriods();
        }).catch(e => {
          console.log(e.response);
        });
      },
      echoSetup() {
        Echo.disconnect();
        Echo.connect();
        Echo.channel(`Game.${this.selectedGame.id}`).listen('GameBroadcast', (data) => {
          this.period_countdown = null;
          setTimeout(function () {
            this.currentPeriod = data.data;
            this.periodModeManual = this.currentPeriod.is_manual;
            var period_time = new Date(this.currentPeriod.created_at);
            this.period_countdown = new Date(period_time.setSeconds(period_time.getSeconds() + this.selectedGame.time));
            this.getOldPeriods();
          }.bind(this), 100);
        });
      },
      getCurrentPeriod() {
        this.period_countdown = null;
        axios.get('/get-game-current-period/' + this.selectedGameSlug).then(res => {
          this.currentPeriod = res.data.currentPeriod;
          this.periodModeManual = this.currentPeriod.is_manual;
          var period_time = new Date(this.currentPeriod.created_at);
          this.period_countdown = new Date(period_time.setSeconds(period_time.getSeconds() + this.selectedGame.time));
          this.echoSetup();
          if (this.init) {
            this.getCurrentPeriodBook();
            this.init = false;
          }
        }).catch(e => {
          console.log(e.response);
        });
      },
      updatePeriodMode() {
        setTimeout(function () {
          axios.post('/update-period-mode', { period_id: this.currentPeriod.id, periodModeManual: this.periodModeManual }).then(res => {
            if (res.data.error != null) {
              this.generalError = true;
              this.periodModeManual = !this.periodModeManual;
            }
          }).catch(e => {
            this.generalError = true;
            this.periodModeManual = !this.periodModeManual;
            console.log(e.response);
          });
        }.bind(this), 500);
      },
      submitResult() {
        if (this.periodModeManual == true) {
          axios.post('/submit-game-result', { period_id: this.currentPeriod.id, result: this.selectedResult }).then(res => {
            if (res.data.success != null) {
              this.$toasted.success(res.data.success.toString(), {icon: 'check_circle'});
              this.selectedResult = "0";
            } else if (res.data.error != null) {
              this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
            } else {
              this.generalError = true;
            }
          }).catch(e => {
            this.generalError = true;
            console.log(e.response);
          });
        } else {
          this.$toasted.error('Please switch mode to manual first', {icon: 'not_interested'});
        }
      },
      getOldPeriods() {
        this.oldPeriodsLoading = true;
        axios.get('/get-old-periods/' + this.selectedGameSlug).then(res => {
          this.oldPeriodsLoading = false;
          this.oldPeriods = res.data.periods;
        }).catch(e => {
          console.log(e.response);
        });
      },
      getCurrentPeriodBook() {
        axios.post('/get-current-period-book', { game_id: this.selectedGame.id, period_id: this.currentPeriod.id }).then(res => {
          console.log(res.data);
          this.currentPeriodBook = res.data.book;
          setTimeout(function () {
            this.getCurrentPeriodBook();
          }.bind(this), 3000);
        }).catch(e => {
          console.log(e.response);
        });
      },
    }
  }
</script>