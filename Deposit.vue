<template>
	<div class="main-account-statement-block main-withdrawal-block">
      <div class="card">
      <div class="card-body p-2">
        <div class="row">
          <div class="col-lg-6 col-md-12">
            <div class="accordion" id="depositMethod">
              <div class="card">
                <div class="card-header" id="upi" style="background-color: #f5af19;">
                  <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#DirectWeekPl" aria-expanded="true" aria-controls="DirectWeekPl">
                      <span>UPI</span>
                    </button>
                  </h2>
                </div>
                <div id="DirectWeekPl" class="collapse" aria-labelledby="upi" data-parent="#depositMethod">
                  <div class="card-body">
                   <h3>Pay via UPI: Min 500 - Max 99000</h3>
                    <input class="form-control" type="number" placeholder="amount" v-model="online_deposit_amount" required />                      
                    <button class="btn btn-success mt-3" type="button" @click="onlineDeposit">
                      <span class="animate-btn">Deposit</span>
                    </button>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="upi" style="background-color: #fc4a1a;">
                  <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#cardAndNetbanking" aria-expanded="true" aria-controls="cardAndNetbanking">
                      <span>Cards / Net Banking</span>
                    </button>
                  </h2>
                </div>
                <div id="cardAndNetbanking" class="collapse" aria-labelledby="upi" data-parent="#depositMethod">
                  <div class="card-body">
                   <h3>Pay via Cards / Net Banking: Min 500 - Max 1000000</h3>
                    <form id="deposit_cc" action="https://epicinfo.tech/paytmint/TxnTest.php" method="POST">
                      <input class="form-control" type="number" name="amount" placeholder="amount" v-model="online_deposit_amount_cc" required />
                      <input class="form-control" type="hidden" name="customerid" :value="auth.id" required />
                      <input class="form-control" type="hidden" name="refrenceid" v-model="online_deposit_amount_cc_orderid" required />
                    </form>
                    <button class="btn btn-success mt-3" type="button" @click="onlineDepositCC">
                      <span class="animate-btn">DEPOSIT</span>
                    </button>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="upi" style="background-color: #0f9b0f;">
                  <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#COD" aria-expanded="true" aria-controls="COD">
                      <span>COD</span>
                    </button>
                  </h2>
                </div>
                <div id="COD" class="collapse" aria-labelledby="upi" data-parent="#depositMethod">
                  <div class="card-body">
                    <h3>Cash on Delivery</h3>
                    <div class="d-flex">
                      <h6>MIN Deposit: 10LAKH</h6>
                      <a href="https://wa.me/+919971567026" class="ml-3"><img src="/images/icons/whatsapp.png" class="mr-2" width="20px" alt="cod"><span>+91 997 156 7026</span></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header" id="upi" style="background-color: #00C9FF;">
                  <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#BankTransfer" aria-expanded="true" aria-controls="BankTransfer">
                      <span>Bank Transfer</span>
                    </button>
                  </h2>
                </div>
                <div id="BankTransfer" class="collapse" aria-labelledby="upi" data-parent="#depositMethod">
                  <div class="card-body">
                    <h3>Pay via Bank Transfer :</h3>
                    <div class="row mt-1 mb-1 d-block p-3">
                      <h6 style="color: #1CB5E0;">YESBANK : --</h6>
                      <div class="d-flex align-items-center justify-content-between">
                        <h6> Account Number</h6>
                        <input type="text" value="012763400001427" id="AccountNumber" style="border: none; width: 126px;">
                        <button type="button" class="btn btn-primary btn-sm btn-clipboard" data-clipboard-action="copy" data-clipboard-target="#AccountNumber">
                          <span class="material-icons">content_copy</span>
                        </button>
                      </div>
                       <div class="d-flex align-items-center justify-content-between">
                        <h6 >IFSC CODE</h6>
                        <input type="text" value="YESB0000127" id="CopyIfsc" style="border: none; width: 126px;">
                        <button type="button" class="btn btn-primary btn-sm btn-clipboard" data-clipboard-action="copy" data-clipboard-target="#CopyIfsc">
                          <span class="material-icons">content_copy</span>
                        </button>
                      </div>
                       <div class="d-flex align-items-center justify-content-between">
                        <label class="form-label">Amount Holder Name: </label>
                        <h6 class="mt-2">Aspire Software Solutions</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>




        
      </div>
    </div>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  import { required, sameAs, minLength, maxLength, between } from 'vuelidate/lib/validators';
  export default {
    props: [ 'payment_gateway_link', 'is_direct'],    
    data() {
      return {
        online_deposit_amount: '',
        online_deposit_amount_cc: "",
        online_deposit_amount_cc_orderid: "",
      }
    },
    computed: {
      ...mapState([
        'loggedIn', 'auth', 'origin_path'
      ])
    },
    watch: {
      account: function(val) {
        this.selectAccount();
      }
    },
    mounted() {
      // Show the first tab by default
        $('.tabs-stage div').hide();
        $('.tabs-stage div:first').show();
        $('.tabs-nav li:first').addClass('tab-active');

        // Change tab class and display content
        $('.tabs-nav a').on('click', function(event){
          event.preventDefault();
          $('.tabs-nav li').removeClass('tab-active');
          $(this).parent().addClass('tab-active');
          $('.tabs-stage .active-tab').hide();
          $($(this).attr('href')).show();
        });
    },
    methods: {      
      onlineDeposit() {
        if (this.online_deposit_amount >= 500 && this.online_deposit_amount <= 50000) {
          window.open(this.$props.payment_gateway_link + '?userid=' + this.auth.userid + '&phone=' + this.auth.phone + '&amount=' + this.online_deposit_amount, '_blank');
        } else {
          this.$toasted.error('Deposit amount should be min 500 and max 50000', {icon: 'dangerous'});
          // this.$toasted.error('Payment are down, please try again later !', {icon: 'not_interested'});
        }
      },
      onlineDepositCC() {
        if (this.online_deposit_amount_cc % 100 == 0) {
          if (this.online_deposit_amount_cc >= 500 && this.online_deposit_amount_cc <= 1000000) {
            axios.post('/get-deposit-orderid', { user_id: this.auth.id, amount: this.online_deposit_amount_cc }).then(res => {
              this.online_deposit_amount_cc_orderid = res.data.orderid;
              setTimeout(function () {
                $('#deposit_cc').submit();
              }, 500);
            }).catch(e => {
              console.log(e.response);
            });
          } else {
            this.$toasted.error('Deposit amount should be min 500 and max 50000', {icon: 'not_interested'});
          }
        } else {
          this.$toasted.error('Deposit amount should be divisible by 100', {icon: 'not_interested'});
        }
      },
    }
  };
</script>
<style scoped>
  .accordion .card {
    margin-bottom: 15px;
    border-radius: 50px;
    background-color: #333;
  }
 
  .accordion .card .card-header button {
    color: #fff;
  }
  .accordion .card .card-body h3 {
    color: #92FE9D;
    font-size: 15px;
    margin-bottom: 15px;
  }

</style>