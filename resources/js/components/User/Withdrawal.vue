<template>
  <v-app id="Withdrawal"> 	
  	<v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Withdrawal</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn large :ripple="true" to="/withdrawal/record" icon color="#FFFFFF">          
          <v-icon>mdi-menu</v-icon>
        </v-btn>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">	
    	<v-container fluid>
        <v-form ref="form">
          <v-row class="justify-center mt-3 mb-4 balance-type-block">
            <v-col cols="6" class="d-flex justify-center">
              <h4 :class="balanceType == 'Main Balance' ? 'active text-center' : 'text-center'">Balance <br><span><strong>₹ {{ auth.balance | addCommas }}</strong></span></h4>
            </v-col> 
            <v-col cols="6" class="d-flex justify-center">
              <h4 :class="balanceType == 'Reward Balance' ? 'active text-center' : 'text-center'">Secondary Balance <br><span><strong>₹ {{ auth.loss_reward_balance | addCommas }}</strong></span>
                <h3 v-if="auth.loss_reward_balance >= 5000"><v-btn color="primary" small @click="$modal.show('read-rule');" class="ml-2">Read Rule</v-btn></h3>
              </h4>
            </v-col>
          </v-row>
          <v-select solo v-model="balanceType" :items="balanceTypeOptions" v-if="auth.loss_reward_balance >= rewardThreshold"></v-select>
          <v-text-field label="Enter withdrawal amount" v-model.trim="amount" :rules="amountErrors" prepend-inner-icon="mdi-cash" solo></v-text-field>
          <p>Fee: {{ fee }},to account {{ accountAmount }}</p>
          <div class="d-flex flex-row justify-content-between align-items-center">
            <v-select v-model="bank"
              :items="banks"
              item-text="account_with_name"
              label="Select Bank Card"
              :rules="bankErrors"
              return-object
              prepend-inner-icon="mdi-bank" solo>
            </v-select>
            <v-btn color="secondary" small to="/add/bank/card" class="ml-2">Add Bank</v-btn>
          </div>
          <v-text-field label="Enter your login password" v-model.trim="password" type="password" prepend-inner-icon="mdi-key" :rules="passwordErrors" solo></v-text-field>
          <div class="text-center">
            <v-btn large color="success" @click="Withdrawal">
              <span>Withdrawal</span>
            </v-btn>
          </div>
        </v-form>
    	</v-container>
    </v-main>
    <!-- banner modal -->
    <modal name="banner-modal" transition="nice-modal-fade" :clickToClose="false" :scrollable="true" :adaptive="true" width="100%" height="auto" :max-width="400" :max-height="2000">
      <div>
        <div class="modal-body p-0">
          <div style="position: absolute; right: 14px; top: 10px; background-color: #FFF; border-radius: 100%; width: 35px; height: 35px; text-align: center; line-height: 3.3; box-shadow: 0 0 10px rgb(0 0 0 / 30%);">
            <a href="javascript:void(0)" class="default-close-btn" title="Close" @click="$modal.hide('banner-modal')">
              <i class="material-icons" style="color: #030303;">close</i>
            </a>
          </div>
          <img :src="'/images/poster/'+BannerData.dw" width="100%" alt="">
        </div>
      </div>
    </modal>
    <!-- End banner modal -->
    <!-- banner modal -->
    <modal name="read-rule" transition="nice-modal-fade" :clickToClose="false" :scrollable="true" :adaptive="true" width="100%" height="auto" :max-width="400" :max-height="2000">
      <div>
        <div class="modal-body p-0">
          <div style="position: absolute; right: 14px; top: 10px; background-color: #FFF; border-radius: 100%; width: 35px; height: 35px; text-align: center; line-height: 3.3; box-shadow: 0 0 10px rgb(0 0 0 / 30%);">
            <a href="javascript:void(0)" class="default-close-btn" title="Close" @click="$modal.hide('read-rule')">
              <i class="material-icons" style="color: #030303;">close</i>
            </a>
          </div>
          
        </div>
        <div class="p-2 " style="overflow-y: scroll;">
            <div class="mt-15">
              <p>1. आपके पास कम से कम 100 यूज़र्स का होना अनिवार्य है और उसमे से 80% यूज़र्स ऐक्टिव होने चाहीये। तथा  जिन जिन लोगो को आपने जोड़ा है उन सभी का पहला रिचार्ज 500/- रुपये से कम नहीं होना चाहीये। </p>
              <p>2. उदाहरण के लिए यदि आपने 100 व्यक्तियों को जोड़ा है और उसमे से 80 व्यक्ति पूरी  तरह से ऐक्टिव है तो इस स्थिति  में आपको आपका संपूर्ण पैसा मिल जाएगा जिसका सिर्फ़ 5 प्रतिशत भुगतान काटा जाएगा।   </p>
              <p> 3. यदि आपके पास पूरे 5000/- का कोटा भर जाता है उसके बाद ही आपको आपका भुगतान किया जाएगा उससे पहले किसी तरह का भुगतान नहीं किया जाएगा।  </p>
              <p>4. यदि आप इन नियमों का पालन नहीं करने उपरांत भी हमसे भुगतान चाहते है तो उस स्थिति में कंपनी आपकी प्रोफाइल का निरीक्षण करेगी तथा इस स्तिथि में सभी तरह से निर्णय कंपनी का रहेगा।  
आपके कार्य ( जीतने भी व्यक्ति आपने जोड़े है ) का भुगतान कंपनी उसी अनुसार करेगी जिस तरह से आपने व्यक्ति जोड़े है अर्थात् यदि आप 20% व्यक्ति जोड़ते है तो उसी अनुसार आपको भुगतान किया जाएगा।  </p>
              <p>5. इन पैसों के भुगतान में 24 घंटे से अधिक का समय भी लग सकता है।   </p>
              <p>6. अगर आप अभी भुगतान नहीं करवाना चाहते है तो निश्चिंत रहे आपका पैसा हमारे साथ सुरक्षित है जिस दिन आपके व्यक्ति जुड़ जाये उस दिन अपना भुगतान ले सकते है </p>
              <p>7. अगर आपने एक भी व्यक्ति को नहीं जोड़ा है तो इस स्थिति में आपका संपूर्ण भुगतान रोका जा सकता है  </p>
              <p>8. आप फिर भी भुगतान लेना चाहते है तो आगे की टैब में अपनी जानकारी भरें तथा कंपनी द्वारा वेरीफाय होने तक इंतज़ार करें।</p>
            </div>
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
        ripple: true,
        amount: null,
        password: null,
        banks: [],
        BannerData: [],
        bank: null,
        balanceType: "Main Balance",
        balanceTypeOptions: ["Main Balance", "Reward Balance"],
        withdrawal: {
          amount: null,
          fee: null,
        },
        type: 'Bankcard',
        amountErrors: [
          v => !!v || 'Withdrawal amount is required',
          v => v > 299 || 'Min withdrawal amount is 300',
        ],
        passwordErrors: [
          v => !!v || 'Password is required',
        ],
        bankErrors: [
          v => !!v || 'Bank is required',
        ],
      }
    },
    watch: {
      amount(val) {
        if (parseInt(this.amount) > parseInt(this.auth.balance)) {
          setTimeout(function() {
            if (this.balanceType == 'Main Balance') {
              this.amount = this.auth.balance;
            } else if (this.balanceType == 'Reward Balance') {
              this.amount = this.auth.loss_reward_balance;
            }
          }.bind(this), 100);
        }
      }
    },
    created() {
      this.banner();
      this.getBanks();
      this.getBanner();
      Echo.connect();
      Echo.channel(`User.Update.${this.auth.id}`).listen('UserUpdate', (data) => {
        this.$store.commit('updateAuthBalance', (data.balance));
        this.$store.commit('updateAuthRewardBalance', (data.loss_reward_balance));
      });
    },
    beforeDestroy() {
      Echo.disconnect();
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
    computed: {
      fee() {
        return parseFloat(this.withdrawal.fee = this.amount * (this.wfee / 100)).toFixed(2);
      },
      accountAmount() {
        return parseFloat(this.withdrawal.amount = this.amount - this.withdrawal.fee).toFixed(2);
      },
      ...mapState([
        'auth', 'wfee', 'rewardThreshold'
      ]),
    },
    methods: {
      banner() {
        setTimeout(function(){
          this.$modal.show('banner-modal');
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
      validate () {
        return this.$refs.form.validate();
      },
      hasHistory () { return window.history.length > 2 },
      Withdrawal () {
        if (this.validate()) {
          if (this.amount < 300 ) {
            this.$toasted.error('You can not withdrawal less than  300 !', {icon: 'not_interested'});
          } else {
            axios.post('/withdrawal', {amount: this.withdrawal.amount, fee: this.withdrawal.fee, type: this.type, bank_id: this.bank.id, password: this.password, balanceType: this.balanceType}).then(res => {
              if (res.data.error != null) {
                this.$toasted.error(res.data.error, {icon: 'not_interested'});
              } else {
                this.$refs.form.reset();
                this.$toasted.show(res.data.success, {icon: 'power_settings_new'});
                this.amount = "";
                this.withdrawal = {
                  amount: null,
                  fee: null,
                };
                this.bank = null;
                this.password = "";
              }
            }).catch(e => {
              this.$toasted.error('Please try again', {icon: 'not_interested'});
              console.log(e.response);
            });
          }
        }
      },
      getBanks() {
        axios.get('/banks').then(res => {
          this.banks = res.data.banks;
        }).catch(e => {
          this.getBanks();
          console.log(e.response);
        });
      },
    }
  }
</script>
