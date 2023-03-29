<template>
  <v-app class="admin-index-pages">
    <v-main>
      <v-container fluid>
        <v-card v-if="settings != null">
          <v-card-title>Settings</v-card-title>
          <v-card-text>
            <v-card class="mt-4">
              <v-card-title><h5 class="m-0">Promotion %</h5></v-card-title>
              <v-card-text>
                <v-row class="mt-0">
                  <v-col cols="12" md="3">
                    <v-text-field label="First Reward" v-model="settings.first_reward"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="3">
                    <v-text-field label="Level 1 Reward" v-model="settings.level_1_reward"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="3">
                    <v-text-field label="Level 2 Reward" v-model="settings.level_2_reward"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="3">
                    <v-text-field label="Level 3 Reward" v-model="settings.level_3_reward"></v-text-field>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
            <v-card class="mt-4">
              <v-card-title><h5 class="m-0">Fee %</h5></v-card-title>
              <v-card-text>
                <v-row class="mt-0">
                  <v-col cols="12" md="6">
                    <v-text-field label="Order Fee" v-model="settings.order_fee"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field label="Withdrawal Fee" v-model="settings.withdrawal_fee"></v-text-field>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
            <v-card class="mt-4">
              <v-card-title><h5 class="m-0">Jackpot &amp; Reward</h5></v-card-title>
              <v-card-text>
                <v-row class="mt-0">
                  <v-col cols="12" md="6">
                    <v-text-field label="Jackpot Odds (x)" v-model="settings.jackpot_odds"></v-text-field>
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field label="Lose Reward Threshold" v-model="settings.reward_threshold"></v-text-field>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
            <v-divider></v-divider>
            <div class="text-center">
              <v-btn color="success" @click="saveSettings">Save Settings</v-btn>              
            </div>
          </v-card-text>
        </v-card>
      </v-container>
    </v-main>
   </v-app>
</template>
<script>
  export default {
  	data () {
      return {
        settings: null
      }
    },
    created() {
      this.getSettings();
    },
    methods: {
      getSettings() {
        axios.get('/get-settings').then(res => {
          this.settings = res.data.settings;
        }).catch(e => {
          console.log(e.response);
        });
      },
      saveSettings() {
        axios.post('/save-settings', { settings: this.settings }).then(res => {
          this.$toasted.success('Settings saved successfully', {icon: 'check_circle'});
        }).catch(e => {
          console.log(e.response);
        });
      }
    }
  }
</script>