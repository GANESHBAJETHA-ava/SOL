<template>
  <v-app id="Recharge"> 	
  	<v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Recharge</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn large :ripple="true" to="/recharge/record" icon color="#FFFFFF">          
          <v-icon>mdi-menu</v-icon>
        </v-btn>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">	
    	<v-container fluid v-if="payment_layout == false">
        <div class="text-center mt-5">
          <h4>Balance:₹ <span>{{ auth.balance | addCommas }}</span></h4>
        </div>
        <v-form ref="form">
          <v-row class="justify-center">
            <v-col cols="11">
              <v-text-field label="Enter or Select recharge amount" type="number" v-model.trim="recharge.amount" :rules="amountErrors" prepend-inner-icon="mdi-wallet" solo></v-text-field>
            </v-col>
            <v-col cols="4">
              <v-btn block @click="recharge.amount = 300">₹ 300</v-btn>
            </v-col>
            <v-col cols="4">
              <v-btn block @click="recharge.amount = 500">₹ 500</v-btn>
            </v-col>
            <v-col cols="4">
              <v-btn block @click="recharge.amount = 1000">₹ 1000</v-btn>
            </v-col>
            <v-col cols="4">
              <v-btn block @click="recharge.amount = 5000">₹ 5000</v-btn>
            </v-col>
            <v-col cols="4">
              <v-btn block @click="recharge.amount = 10000">₹ 10000</v-btn>
            </v-col>
            <v-col cols="4">
              <v-btn block @click="recharge.amount = 49999">₹ 49999</v-btn>
            </v-col>
          </v-row>
          <div class="mt-5">
            <h6>Payment</h6>
            <v-radio-group v-model.trim="type">
              <v-radio  color="primary" label="UPI Payment" value="UPI Payment"></v-radio>
              <v-radio  color="primary" label="QR Code Scanner" value="QR Code Scanner"></v-radio>
              <v-radio  color="primary" label="Smart Pay" value="Smart Pay"></v-radio>
            </v-radio-group>
          </div>
          <div class="text-center">
            <v-btn large  color="success" @click="Recharge" style="padding: 5px 90px !important;">
              <span>Recharge</span>
            </v-btn>
            <div class="images-block d-flex flex-row justify-center mt-4">
              <img src="/images/icons/security.png" style="width: 85px; height: 100%;" class="mr-4">
              <img src="/images/icons/fast.png" style="width: 57px; height: 100%;">
            </div>
          </div>
        </v-form>
    	</v-container>
      <v-container fluid v-if="payment_layout == true">
        <v-form ref="form">
          <div class="payment justify-content-center d-flex">
            <v-card>
              <h4 class="mb-3">Amount:  ₹ {{ data.amount }} </h4>
              <div class="d-flex justify-content-center">
                <img max-width="200" :src="'/images/payment/'+ data.txn_id +'.svg'" alt="qr code">
              </div>
              <div class="d-flex mt-3">
                <v-btn disabled class="btn btn-primary w-100">Take Screenshot and Pay</v-btn>
              </div>
              <div class="d-flex justify-content-between align-items-center mt-5">
                <h5>{{ account.upi_id }}</h5>
                <v-btn elevation="2" @click="copy(account.upi_id, $event)">COPY</v-btn>
              </div>
              <v-text-field label="UTR NO" v-model.trim="utr" required :rules="utrErrors"></v-text-field>
              <v-btn large block color="success" @click="submitUtr" class="white--text">
                <span>Submit</span>
              </v-btn>
              <div class="d-flex justify-content-between mt-2">
                <a href="javascript:void">
                  <img src="images/phonepay.png" width="100px" alt="payment" class="p-2"></a>
                <a href="javascript:void">
                  <img src="images/gpay.png" width="100px" alt="payment" class="p-2"></a>
                <a href="javascript:void">
                  <img src="images/paytm.png" width="100px" alt="payment" class="p-2"></a>
              </div>
              <video autoplay loop style="width: 100%;" class="mt-3">
                <source src="/images/video/rules.mp4" type="video/mp4">
              </video>   
            </v-card>
          </div>
        </v-form> 
      </v-container>
    </v-main>	
   </v-app>
</template>
<script>
  import { mapState } from "vuex";
  export default {
    data () {
      return {
        ripple: true,
        payment_layout: false,
        utr: '',
        recharge: {
          amount: null
        },
        data: null,
        account: null,
        type: 'UPI Payment',
        amountErrors: [
          v => !!v || 'Amount is required',
        ],
        utrErrors: [
          v => !!v || 'Utr is required',
        ],
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
    mounted() {
      console.log('Component mounted.')
    },
    computed: {
      ...mapState([
        "auth",
      ]),
    },
    methods: {
      validate () {
        return this.$refs.form.validate();
      },
      hasHistory () { return window.history.length > 2 },
      Recharge () {
        if (this.validate()) {
          if (this.recharge.amount > 299) {
            axios.post('/recharge', {amount: this.recharge.amount, type: this.type}).then(res => {
              this.account = res.data.account;
              this.data = res.data.data;
              this.payment_layout = true;
              if (res.data.error != null) {
                this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
              } else {
                // this.$toasted.show('Bank Add successfully', {icon: 'power_settings_new'});
                setTimeout(function() {
                  // window.location.href = window.location.origin;                
                }, 800);
              }
            }).catch(e => {
              this.$toasted.error('Please try again', {icon: 'not_interested'});
              console.log(e.response);
            });
          } else {
            this.$toasted.error('you can not recharge less than 300', {icon: 'not_interested'});
          }
        }
      },
      submitUtr () {
        if (this.validate()) {
          axios.post('utr', {txn_id : this.data.txn_id, utr: this.utr}).then(res => {
            if (res.data.error != null) {
              this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
            } else {
              this.hasHistory() ? this.$router.go(-1) : this.$router.push('/');
              this.$toasted.success(res.data.success.toString(), {icon: 'check_circle'});
              window.location.href = "/dashboard";
            }
          }).catch(e => {
            this.$toasted.error('Please try again', {icon: 'not_interested'});
            console.log(e.response);
          });
        }
      },
      copy(mytext, el) {
        this.copyToClipboard(mytext).then(() => {
          $(el.target).html("COPIED!");
          setTimeout(function() {
            $(el.target).html("COPY");
          }, 1000);
        }).catch(() => console.log('error'));
      },
      copyToClipboard(textToCopy) {
          // navigator clipboard api needs a secure context (https)
          if (navigator.clipboard && window.isSecureContext) {
              // navigator clipboard api method'
              return navigator.clipboard.writeText(textToCopy);
          } else {
              // text area method
              let textArea = document.createElement("textarea");
              textArea.value = textToCopy;
              // make the textarea out of viewport
              textArea.style.position = "fixed";
              textArea.style.left = "-999999px";
              textArea.style.top = "-999999px";
              document.body.appendChild(textArea);
              textArea.focus();
              textArea.select();
              return new Promise((res, rej) => {
                  // here the magic happens
                  document.execCommand('copy') ? res() : rej();
                  textArea.remove();
              });
          }
      },
    }
  }
</script>
<style scoped>
  .payment .v-sheet.v-card {
    padding: 50px;
  }
  @media (max-width:  600px) {
    .payment .v-sheet.v-card {
      padding: 10px;
    }
  }
</style>
