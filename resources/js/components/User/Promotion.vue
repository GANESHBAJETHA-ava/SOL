<template>
  <v-app id="Promotion"> 	
    <!-- accessories -->
    <v-dialog v-model="infoDialog" width="1200">
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">Notice</v-card-title>
        <v-card-text class="mt-3">
          <p>When your friends trade, you will also receive a 30% commission. Therefore, the more friends you invite, the higher your commission. There is a fixed income every day, the commission is permanent, but the reward is only onceWhen they make money, they will invite their friends to join them, and then you can get a 20% commission. In this way, your team can spread quickly. Therefore, I hope everyone can use our platform to make money, make money, and make money!When they make money, they will invite their friends to join them, and then you can get a 20% commission. In this way, your team can spread quickly. Therefore, I hope everyone can use our platform to make money, make money, and make money!Level 1 commission: Friends who join through your own link belong to your level, when they trade, you will get 30% commission.Tier 2 commission: Friends who join through your friend link belong to your secondary commission. When they trade, you can get 20% commission.Level 3 commission: Friends who join through friends of friends belong to your level 3. When they trade, you get 10% commission.Promotional rewards: 10% bonus amount for the first recharge after the first-level lower level joins. If your friend joins through your invitation and recharges 1000 for the first time, you will get 200</p>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="infoDialog = false">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar v-model="copy_snackbar" timeout="1000">
      Text Copied
      <template v-slot:action="{ attrs }">
        <v-btn color="pink" text v-bind="attrs" @click="copy_snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
    <!-- end accessories -->
  	<v-card>
      <v-toolbar color="success" dark flat>
        <v-btn  icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Promotion</v-toolbar-title>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">
      <loading v-if="is_loading"></loading>
    	<v-container fluid v-else>
        <v-row class="justify-center">
          <v-col cols="12" class="d-flex justify-center mt-5">
            <h4>Bonus: ₹ <span>{{ auth.commission == null ? 0 : auth.commission }}</span></h4>
          </v-col>
          <v-col cols="6" class="d-flex justify-center">
            <h5 class="text-center">Total People <br><span>{{ total_people }}</span></h5>
          </v-col> 
          <v-col cols="6" class="d-flex justify-center">
            <h5 class="text-center">Contribution <br><span>₹ {{ contribution }}</span></h5>
          </v-col>
        </v-row>
        <v-text-field label="My Promotion Code" disabled v-model="auth.r_code" ></v-text-field>   
        <v-text-field label="My Promotion Link" :value="r_url" id="referalLink"></v-text-field>
        <div class="text-center mb-5">
          <v-btn class="btn btn-primary btn-sm btn-clipboard" data-clipboard-action="copy" data-clipboard-target="#referalLink" depressed color='secondary'>
            <span>Copy Link</span>
          </v-btn>
        </div>
    	</v-container>
      <v-card class="mb-15">
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
        >
          <template v-slot:item.id="{ item }">
            {{ 1011 + parseInt(item.id) }}
          </template>
          No Data Available
        </v-data-table>
      </v-card>
    </v-main>
      <!-- banner modal -->
    <modal name="promotion-banner-modal" transition="nice-modal-fade" :clickToClose="false" :scrollable="true" :adaptive="true" width="100%" height="auto" :max-width="400" :max-height="2000">
      <div>
        <div class="modal-body p-0">
         <div style="position: absolute; right: 14px; top: 10px; background-color: #FFF; border-radius: 100%; width: 35px; height: 35px; text-align: center; line-height: 3.3; box-shadow: 0 0 10px rgb(0 0 0 / 30%);">
            <a href="javascript:void(0)" class="default-close-btn" title="Close" @click="$modal.hide('promotion-banner-modal')">
              <i class="material-icons" style="color: #030303;">close</i>
            </a>
         </div>
          <img :src="'/images/poster/'+BannerData.permotion" width="100%" alt="">
        </div>
      </div>
    </modal>
    <!-- End banner modal -->
   </v-app>
</template>
<script>
  import { mapState } from "vuex";
  export default {
    data () {
      return {
        search: '',
        infoDialog: false,
        copy_snackbar: false,
        is_loading: false,
        BannerData: [],
        bonus: 0,
        total_people: 0,
        contribution: 0,
        r_url: "",
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
        level_three_users: []
      }
    },
    computed: {
      ...mapState([
        "auth",
      ]),
    },
    created() {
      this.banner();
      this.r_url = window.location.origin + '/register?code=' + this.auth.r_code;
      this.getData();
      this.getBanner();
    },
    methods: {
      banner() {
        setTimeout(function(){
          this.$modal.show('promotion-banner-modal');
        }.bind(this), 500);
      },
      getBanner() {
        axios.get('/get-banner').then(res => {
          console.log(res.data);
            this.BannerData = res.data.banner;
          }).catch(e => {
            this.getBanner();
            console.log(e.response);
          });
      },
      referralLinkCopy(link) {
        this.copyToClipboard(link).then(() => this.copy_snackbar = true).catch(() => console.log('error'));
      },
      copyToClipboard(textToCopy) {
        if (navigator.clipboard && window.isSecureContext) {
          return navigator.clipboard.writeText(textToCopy);
        } else {
          let textArea = document.createElement("textarea");
          textArea.value = textToCopy;
          textArea.style.position = "fixed";
          textArea.style.left = "-999999px";
          textArea.style.top = "-999999px";
          document.body.appendChild(textArea);
          textArea.focus();
          textArea.select();
          return new Promise((res, rej) => {
            document.execCommand('copy') ? res() : rej();
            textArea.remove();
          });
        }
      },
      hasHistory () { return window.history.length > 2 },
      getData() {
        axios.get('/get-my-levels').then(res => {
          this.level_one_users = res.data.one_levels;
          this.level_two_users = res.data.two_levels;
          this.level_three_users = res.data.three_levels;
          this.total_people = res.data.total_people;
          this.contribution = res.data.contribution;
          this.bonus = res.data.bonus;
          this.selectLevel(1);
        }).catch(e => {
          console.log(e.response);
        })
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
