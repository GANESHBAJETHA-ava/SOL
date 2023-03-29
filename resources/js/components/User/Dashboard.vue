<template>
  <v-app id="dashboard">
  	<!-- <v-toolbar dark  color="success">
      <v-btn class="mx-2" fab dark small color="primary">
      	<span>{{ auth.phone.toString().slice(0,1) }}</span>
      </v-btn>
      <v-toolbar-title>
      	<h6>User: {{ auth.phone }}</h6>
      	<h6>ID：{{ auth.id }}</h6>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-dialog v-model="dialog" width="900">
	      <template v-slot:activator="{ on, attrs }">
	      	<v-btn class="mx-2" fab small v-bind="attrs" v-on="on"  color="#FAFAFA">
	      		<v-icon color="#757575">mdi-bell</v-icon>
	      	</v-btn>
	      </template>
	      <v-card>
	        <v-card-title class="text-h5 lighten-2">Notice</v-card-title>
	        <v-card-text class="mt-3">
	          <p>???Official notice: ??? The system is updated to allow new and old customers to have a better investment experience. Shopping Center will distinguish the investment projects of each promotion member, so that each superior can better distinguish whether the member belongs to your member. Thanks to the new and old members for their continued support. shopping center will become more and more perfect. I wish all members a happy investment. ❤️❤️❤️</p>
	        </v-card-text>
	        <v-card-actions>
	          <v-spacer></v-spacer>
	          <v-btn color="primary" text @click="dialog = false">Close</v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
    </v-toolbar> -->
    <v-container fluid>
    	<v-card dark  color="dark" class="my-card-block">
		    <v-card-text>
			    <div class="d-flex justify-content-between">
			    	<p class="m-0" v-if="auth.name != null">{{ auth.name }}</p>
			    	<p class="m-0" v-else>My Account info</p>
            <img width="40" src="/images/logo/logo.png" alt="logo" class="card-logo">
			    </div>
			    <div class="d-flex justify-content-between mt-1 mb-2">
			    	<h5>{{ auth.phone }} <span><img src="/images/icons/verify.png" width="15px" alt="" style="position: relative; top: -2px;"></span></h5>
			    </div>
			    <div class="d-flex justify-content-between">
			    	<div>
			    		<p class="mb-1">Available Balance</p>
			    		<div class="d-flex align-items-center">
			    			<h5 class="m-0">₹ {{ auth.balance | addCommas }}</h5>
			    		</div>	
			    	</div>
			    	<div style="position: absolute; right: 15px; top: 15px;">
			    		<p class="mb-1">Secondary Balance</p>
			    		<div class="d-flex align-items-center mb-2">
			    			<h5 class="m-0">₹ {{ auth.loss_reward_balance | addCommas }}</h5>
			    		</div>	
		    			<v-progress-linear tag="a" color="green" :value="secondaryBalanceValue"></v-progress-linear>
		    			<div class="social-btns text-center flex-row mt-5">
		    				<!-- <a href="#" class="mr-2">
		              <img src="/images/icons/facebook.png" width="30px" alt="telegram">		    					
		    				</a> -->
		           <!--  <v-btn class="mr-3" fab dark x-small color="primary" to="#">
		            </v-btn> -->
		            <a href="https://t.me/earnmoneystarofluck">
		              <img src="/images/icons/telegram.png" width="30px" alt="telegram">		            	
		            </a> <br>
		            <p>Join Telegram For <br> Daily Prediction</p>
		            <!-- <v-btn fab dark x-small color="primary" to="/https://t.me/earnmoneystarofluck">
		            </v-btn>
		            <a href="https://t.me/earnmoneystarofluck">asdfasd</a> -->
		    			</div>
			    	</div>
			    </div>
		    </v-card-text>
		    <v-divider class="mx-4"></v-divider>
		    <div class="d-flex justify-content-between align-items-center mt-5 pb-5" style="padding: 0 15px;">
		    	<v-btn to="/recharge" color="success" depressed>Recharge</v-btn>
		    	<v-btn to="/withdrawal" color="primary" depressed>Withdrawal</v-btn>
		    </div>
		  </v-card>
    </v-container>
		<!-- <v-banner color="success">
     <v-container>
     	<v-card>
     		
     	</v-card>
     	<v-row class="text-md-center">
     		<v-col md="4">
          <p class="text-white pa-0 mb-1">₹ {{ auth.balance | addCommas }}</p>
          <p class="text-white pa-0 mb-1">Balance</p>
          <v-btn rounded to="/recharge" color="primary">Recharge</v-btn>
        </v-col>
        <v-col md="4">
          <p class="text-white pa-0 mb-1">₹ {{ auth.commission | addCommas }}</p>
          <p class="text-white pa-0 mb-1">Commission</p>
          <v-btn rounded to="/promotion" color="primary">See</v-btn>
        </v-col>
        <v-col md="4">
          <p class="text-white pa-0 mb-1">₹ {{ auth.loss_reward_balance | addCommas }}</p>
          <p class="text-white pa-0 mb-1">Reward Balance</p>
        </v-col>
     	</v-row>
     </v-container>	      
    </v-banner> -->
    <v-main class="mb-15 dashboard-content-user">	
    	<v-container fluid>
    		<v-card raised elevation="0">
	     		<v-list>
        		<v-list-item link to="/promotion" class="tab_text">
          	 	<v-list-item-icon>
		            <v-icon>mdi-gift</v-icon>
		          </v-list-item-icon>
		          <v-list-item-content>
		            <v-list-item-title><span>Promotion</span></v-list-item-title>
		          </v-list-item-content>
        		</v-list-item>
			      <v-list-group no-action>
			        <template v-slot:activator>
			          <v-list-item-content>
			            <v-list-item-title>
			            	<v-icon class="mr-7">mdi-wallet</v-icon>
			            	<span>Wallet</span>
			            </v-list-item-title>
			          </v-list-item-content>
			        </template>
			        <v-list-item link to="/recharge" class="tab_text">
			          <v-list-item-content>
			            <v-list-item-title><span>Recharge</span></v-list-item-title>
			          </v-list-item-content>
			        </v-list-item>
			        <v-list-item link to="/withdrawal" class="tab_text">
			          <v-list-item-content>
			            <v-list-item-title><span>Withdrawal</span></v-list-item-title>
			          </v-list-item-content>
			        </v-list-item>
			        <v-list-item link to="/transactions" class="tab_text">
			          <v-list-item-content>
			            <v-list-item-title><span>Transactions</span></v-list-item-title>
			          </v-list-item-content>
			        </v-list-item>
			      </v-list-group>
			      <v-list-item link to="/bankcard" class="tab_text">
          	 	<v-list-item-icon>
		            <v-icon>mdi-credit-card</v-icon>
		          </v-list-item-icon>
		          <v-list-item-content>
		            <v-list-item-title>
			          	<span>Bank Card</span>
		            </v-list-item-title>
		          </v-list-item-content>
        		</v-list-item>
        		<v-list-item link to="/address" class="tab_text">
          	 	<v-list-item-icon>
		            <v-icon>mdi-domain</v-icon>
		          </v-list-item-icon>
		          <v-list-item-content>
		            <v-list-item-title>
			          	<span>Address</span>
		            </v-list-item-title>
		          </v-list-item-content>
        		</v-list-item>
			      <v-list-group no-action>
			        <template v-slot:activator>
			          <v-list-item-content>
			            <v-list-item-title>
			            	<v-icon class="mr-7">mdi-security</v-icon>
			            	<span>Account Security</span>
			            </v-list-item-title>
			          </v-list-item-content>
			        </template>
			        <v-list-item link to="/reset/password" class="tab_text">
			          <v-list-item-content>
			            <v-list-item-title><span>Reset Password</span></v-list-item-title>
			          </v-list-item-content>
			        </v-list-item>
			      </v-list-group>
			      <v-list-item link href="/download-apk" class="tab_text">
          	 	<v-list-item-icon>
		            <v-icon>mdi-download</v-icon>
		          </v-list-item-icon>
		          <v-list-item-content>
		            <v-list-item-title>
			          	<span>App Download</span>
		            </v-list-item-title>
		          </v-list-item-content>
        		</v-list-item>
        		<v-list-item link to="/complaints" class="tab_text">
          	 	<v-list-item-icon>
		            <v-icon>mdi-message</v-icon>
		          </v-list-item-icon>
		          <v-list-item-content>
		            <v-list-item-title>
			          	<span>Contact Us</span>
		            </v-list-item-title>
		          </v-list-item-content>
        		</v-list-item>
			      <v-list-group no-action>
			        <template v-slot:activator>
			          <v-list-item-content>
			            <v-list-item-title>
			            	<v-icon class="mr-7">mdi-information</v-icon>
			            	<span>About</span>
			            </v-list-item-title>
			          </v-list-item-content>
			        </template>
			        <v-list-item link to="/privacy/policy" class="tab_text">
			          <v-list-item-content>
			            <v-list-item-title><a class="tab_text" href="/privacy/policy"><span>Privacy Policy</span></a></v-list-item-title>
			          </v-list-item-content>
			        </v-list-item>
			        <v-list-item link to="/risk/agreement" class="tab_text">
			          <v-list-item-content>
			            <v-list-item-title><span>Risk Disclosure Agreement</span></v-list-item-title>
			          </v-list-item-content>
			        </v-list-item>
			      </v-list-group>
			    </v-list>			    
	     	</v-card>
	     	<div class="d-flex justify-center mt-4 pl-10 pr-10">
           <v-btn block @click="logout" color="error" style="font-weight: 500;">LOGOUT</v-btn>
	     	</div>
    	</v-container>
    </v-main>	
   </v-app>
</template>
<script>
  import { mapState } from "vuex";
  export default {
  	data () {
      return {
      	dialog: false,
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
    computed: {
    	secondaryBalanceValue() {
    		var val = (this.auth.loss_reward_balance / this.rewardThreshold) * 100;
    		if (val >= 100) {
    			val = 100;
    		}
    		return val;
    	},
      ...mapState([
        "auth", "rewardThreshold"
      ]),
    },
    methods: {
      hasHistory () { 
      	return window.history.length > 2 
      },
      logout() {
      	axios.post('/user-logout').then(res => {
          if (res.data.success != null) {
            this.$toasted.success(res.data.success, {icon: "power_settings_new",});
            setTimeout(function() {
	            window.location.href = window.location.origin + '/login';
            }, 600);
          }
      	}).catch(e => {
      		console.log(e.response);
      		window.location.href = window.location.origin;
      	});
      }
    }
  }
</script>
<style>
#dashboard {
	margin-bottom: 70px;
}
  #dashboard .v-btn--example {
    bottom: 0;
    position: absolute;
    margin: 0 0 16px 16px;
  }
  #dashboard .tab_text {
  	text-decoration: none;
  	color: #000000 !important;
  }
</style>
