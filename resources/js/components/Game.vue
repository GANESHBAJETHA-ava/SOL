<template>
  <v-app id="game">
  	<div v-if="game != null && period != null">
  		<!-- Order Dialog -->
	    <v-dialog v-model="orderDialog" width="600">
	      <v-card>
      		<v-card-title small v-if="order.selection == 'king'" class="green d-flex justify-space-between" style="color: #fff;">
      			<span>Join King</span>
      			<v-btn style="font-size: 22px !important;" icon dark @click="orderDialog = false">
	            <v-icon>mdi-close</v-icon>
	          </v-btn>
	        </v-card-title>
	        <v-card-title small v-else-if="order.selection == 'joker'" class="indigo d-flex justify-space-between" style="color: #fff;">
	        	<span>Join Joker</span>
      			<v-btn style="font-size: 22px !important;" icon dark @click="orderDialog = false">
	            <v-icon>mdi-close</v-icon>
	          </v-btn>
	        </v-card-title>
	        <v-card-title small v-else-if="order.selection == 'jackpot'" class="black d-flex justify-space-between" style="color: #fff;">
	        	<span>Join Jackpot</span>
      			<v-btn style="font-size: 22px !important;" icon dark @click="orderDialog = false">
	            <v-icon>mdi-close</v-icon>
	          </v-btn>
	        </v-card-title>
	        <v-card-title small v-else-if="order.selection == 'queen'" class="pink d-flex justify-space-between" style="color: #fff;">
	        	<span>Join Queen</span>
      			<v-btn style="font-size: 22px !important;" icon dark @click="orderDialog = false">
	            <v-icon>mdi-close</v-icon>
	          </v-btn>
	        </v-card-title>
	        <v-card-title small v-else-if="order.selection != null" class="blue d-flex justify-space-between" style="color: #fff;">
	        	<span>Select {{ order.selection }}</span>
      			<v-btn style="font-size: 22px !important;" icon dark @click="orderDialog = false">
	            <v-icon>mdi-close</v-icon>
	          </v-btn>
	        </v-card-title>
	        <v-card-text class="mt-3" style="padding-bottom: 0 !important;">
	        	<p>Contract Money</p>
	        	<v-btn-toggle v-model="toggle_exclusive">
			        <v-btn @click="order.contract_money = 10">10</v-btn>
			        <v-btn @click="order.contract_money = 100">100</v-btn>
			        <v-btn @click="order.contract_money = 1000">1000</v-btn>
			        <v-btn @click="order.contract_money = 10000">10000</v-btn>
			      </v-btn-toggle>
				    <p class="mt-3" style="margin-bottom: 5px;">Number</p>
				    <div class="d-flex justify-space-between">
				    	<v-btn @click="minusContractCount"><span class="material-icons">remove</span></v-btn>
				    	<v-text-field solo v-model="order.contract_count" class="contract-count-class"></v-text-field>
				    	<v-btn @click="order.contract_count++;"><span class="material-icons">add</span></v-btn>								    	
				    </div>
				    <p class="mt-3">Total contract money is <span>{{ total_contract_money }}</span></p>
				    <div class="d-flex align-items-center">
				    	<v-checkbox v-model="orderCheckbox" style="margin-top: 0;">
				    		<template v-slot:label>
					        <div>
					          I Agree 
					          <a href="javascript:void(0)" @click="presaleDialog = true" style="text-decoration: none;">Presale Rule</a>
					        </div>
					      </template>
				    	</v-checkbox>
				    </div>
	        </v-card-text>
	        <v-divider style="margin: 0;"></v-divider>
	        <v-card-actions>
	          <v-spacer></v-spacer>
	          <v-btn large plain color="defult" @click="orderDialog = false">Cancel</v-btn>
	          <v-btn large plain color="success" @click="submitOrder">Confirm</v-btn>					          
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	    <!-- Presale Rule Dialog -->
	    <v-dialog v-model="presaleDialog" width="1200" height="700">
	      <v-card>
	        <v-card-title class="text-h5 grey lighten-2">Rule of Guess</v-card-title>
	        <v-card-text class="mt-3">
	          <v-row>
		          <v-col>
		            <p>This Privacy Policy describes Our policies and procedures on the collection, use and disclosure of Your information when You use the Service and tells You about Your privacy rights and how the law protects You.</p>
		            <h4>Interpretation and Definitions</h4>
		            <h6>Interpretation</h6>
		            <p>The words of which the initial letter is capitalized have meanings defined under the following conditions.</p>
		            <p>The following definitions shall have the same meaning regardless of whether they appear in singular or in plural.</p>
		            <h4>Definitions</h4>
		            <p>For the purposes of this Privacy Policy:</p>
		            <ul>
		              <li><strong>You</strong>means the individual accessing or using the Service, or the company, or other legal entity on behalf of which such individual is accessing or using the Service, as applicable.</li>
		              <li><strong>Company</strong>(referred to as either "the Company", "We", "Us" or "Our" in this Agreement) refers to Coem Shop.</li>
		              <li><strong>Affiliate</strong>means an entity that controls, is controlled by or is under common control with a party, where "control" means ownership of 50% or more of the shares, equity interest or other securities entitled to vote for election of directors or other managing authority.</li>
		              <li><strong>Account</strong>means a unique account created for You to access our Service or parts of our Service.</li>
		              <li><strong>Website</strong>refers to Coem Shop, accessible from https://coem.in</li>
		              <li><strong>Service</strong>refers to the Website.</li>
		              <li><strong>Country</strong>refers to: Uttar Pradesh, India</li>
		              <li><strong>Service Provider</strong> means any natural or legal person who processes the data on behalf of the Company. It refers to third-party companies or individuals employed by the Company to facilitate the Service, to provide the Service on behalf of the Company, to perform services related to the Service or to assist the Company in analyzing how the Service is used.</li>
		              <li><strong>Third-party Social Media Service</strong>refers to any website or any social network website through which a User can log in or create an account to use the Service. Personal Data is any information that relates to an identified or identifiable individual.</li>
		              <li><strong>Personal Data</strong>is any information that relates to an identified or identifiable individual.</li>
		              <li><strong>Cookies </strong>are small files that are placed on Your computer, mobile device or any other device by a website, containing the details of Your browsing history on that website among its many uses.</li>
		              <li><strong>Device</strong>means any device that can access the Service such as a computer, a cellphone or a digital tablet.</li>
		              <li><strong>Usage Data</strong>refers to data collected automatically, either generated by the use of the Service or from the Service infrastructure itself (for example, the duration of a page visit).</li>
		              <h4>Collecting and Using Your Personal Data</h4>
		              <h6>Types of Data Collected</h6>
		              <p><strong>Personal Data</strong></p>
		              <li>While using Our Service, We may ask You to provide Us with certain personally identifiable information that can be used to contact or identify You. Personally identifiable information may include, but is not limited to:</li>
		              <ul>
		                <li>Email address</li>
		                <li>First name and last name</li>
		                <li>Phone number</li>
		                <li>Address, State, Province, ZIP/Postal code, City</li>
		                <li>Usage Data</li>
		              </ul>
		            </ul>
		            <h5>Usage Data</h5>
		            <p>Usage Data is collected automatically when using the Service.</p>
		            <p>Usage Data may include information such as Your Device's Internet Protocol address (e.g. IP address), browser type, browser version, the pages of our Service that You visit, the time and date of Your visit, the time spent on those pages, unique device identifiers and other diagnostic data.</p>
		            <p>When You access the Service by or through a mobile device, We may collect certain information automatically, including, but not limited to, the type of mobile device You use, Your mobile device unique ID, the IP address of Your mobile device, Your mobile operating system, the type of mobile Internet browser You use, unique device identifiers and other diagnostic data.</p>
		            <p>We may also collect information that Your browser sends whenever You visit our Service or when You access the Service by or through a mobile device.</p>
		            <h6>Tracking Technologies and Cookies</h6>
		            <p>We use Cookies and similar tracking technologies to track the activity on Our Service and store certain information. Tracking technologies used are beacons, tags, and scripts to collect and track information and to improve and analyze Our Service.</p>
		            <p>You can instruct Your browser to refuse all Cookies or to indicate when a Cookie is being sent. However, if You do not accept Cookies, You may not be able to use some parts of our Service.</p>
		            <p>Cookies can be "Persistent" or "Session" Cookies. Persistent Cookies remain on your personal computer or mobile device when You go offline, while Session Cookies are deleted as soon as You close your web browser.</p>
		            <p>We use both session and persistent Cookies for the purposes set out below:</p>
		            <h6>Necessary / Essential Cookies</h6>
		            <p>Type: Session Cookies</p>
		            <p>Administered by: Us</p>
		            <p>Purpose: These Cookies are essential to provide You with services available through the Website and to enable You to use some of its features. They help to authenticate users and prevent fraudulent use of user accounts. Without these Cookies, the services that You have asked for cannot be provided, and We only use these Cookies to provide You with those services.</p>
		            <h6>Cookies Policy / Notice Acceptance Cookies</h6>
		            <p>Type: Persistent Cookies</p>
		            <p>Administered by: Us</p>
		            <p>Purpose: These Cookies identify if users have accepted the use of cookies on the Website.</p>
		            <h6>Functionality Cookies</h6>
		            <p>Type: Persistent Cookies</p>
		            <p>Administered by: Us</p>
		            <p>Purpose: These Cookies allow us to remember choices You make when You use the Website, such as remembering your login details or language preference. The purpose of these Cookies is to provide You with a more personal experience and to avoid You having to re-enter your preferences every time You use the Website.</p>
		            <p>For more information about the cookies we use and your choices regarding cookies, please visit our Cookies Policy.</p>
		            <h4>Use of Your Personal Data</h4>
		            <p>The Company may use Personal Data for the following purposes:</p>
		            <ul>
		              <li><strong>To provide and maintain our Service,</strong>including to monitor the usage of our Service.</li>
		              <li><strong>To manage Your Account:</strong> to manage Your registration as a user of the Service. The Personal Data You provide can give You access to different functionalities of the Service that are available to You as a registered user.</li>
		              <li><strong>For the performance of a contract:</strong>the development, compliance and undertaking of the purchase contract for the products, items or services You have purchased or of any other contract with Us through the Service.</li>
		              <li><strong>To contact You:</strong>To contact You by email, telephone calls, SMS, or other equivalent forms of electronic communication, such as a mobile application's push notifications regarding updates or informative communications related to the functionalities, products or contracted services, including the security updates, when necessary or reasonable for their implementation.</li>
		              <li><strong>To provide You</strong>with news, special offers and general information about other goods, services and events which we offer that are similar to those that you have already purchased or enquired about unless You have opted not to receive such information.</li>
		              <li><strong>To manage Your requests</strong>To attend and manage Your requests to Us.We may share your personal information in the following situations:</li>
		              <li><strong>With Service Providers:</strong>With Service Providers</li>
		              <li><strong>For Business transfers:</strong>We may share or transfer Your personal information in connection with, or during negotiations of, any merger, sale of Company assets, financing, or acquisition of all or a portion of our business to another company.</li>
		              <li><strong>With Affiliates:</strong> We may share Your information with Our affiliates, in which case we will require those affiliates to honor this Privacy Policy. Affiliates include Our parent company and any other subsidiaries, joint venture partners or other companies that We control or that are under common control with Us.</li>
		              <li><strong>With Business partners</strong> We may share Your information with Our business partners to offer You certain products, services or promotions.</li>
		              <li><strong>With other users:</strong> when You share personal information or otherwise interact in the public areas with other users, such information may be viewed by all users and may be publicly distributed outside. If You interact with other users or register through a Third-Party Social Media Service, Your contacts on the Third-Party Social Media Service may see You name, profile, pictures and description of Your activity. Similarly, other users will be able to view descriptions of Your activity, communicate with You and view Your profile.</li>
		            </ul>
		            <h4>Retention of Your Personal Data</h4>
		            <p>The Company will retain Your Personal Data only for as long as is necessary for the purposes set out in this Privacy Policy. We will retain and use Your Personal Data to the extent necessary to comply with our legal obligations (for example, if we are required to retain your data to comply with applicable laws), resolve disputes, and enforce our legal agreements and policies.</p>
		            <p>The Company will also retain Usage Data for internal analysis purposes. Usage Data is generally retained for a shorter period of time, except when this data is used to strengthen the security or to improve the functionality of Our Service, or We are legally obligated to retain this data for longer time periods.</p>
		            <h4>Transfer of Your Personal Data</h4>
		            <p>Your information, including Personal Data, is processed at the Company's operating offices and in any other places where the parties involved in the processing are located. It means that this information may be transferred to — and maintained on — computers located outside of Your state, province, country or other governmental jurisdiction where the data protection laws may differ than those from Your jurisdiction.</p>
		            <p>Your consent to this Privacy Policy followed by Your submission of such information represents Your agreement to that transfer.</p>
		            <p>The Company will take all steps reasonably necessary to ensure that Your data is treated securely and in accordance with this Privacy Policy and no transfer of Your Personal Data will take place to an organization or a country unless there are adequate controls in place including the security of Your data and other personal information.</p>
		            <h4>Disclosure of Your Personal Data</h4>
		            <h6>Business Transactions</h6>
		            <p>If the Company is involved in a merger, acquisition or asset sale, Your Personal Data may be transferred. We will provide notice before Your Personal Data is transferred and becomes subject to a different Privacy Policy.</p>
		            <h6>Law enforcement</h6>
		            <p>Under certain circumstances, the Company may be required to disclose Your Personal Data if required to do so by law or in response to valid requests by public authorities (e.g. a court or a government agency).</p>
		            <h6>Other legal requirements</h6>
		            <p>The Company may disclose Your Personal Data in the good faith belief that such action is necessary to:</p>
		            <ul>
		              <li>Comply with a legal obligation</li>
		              <li>Protect and defend the rights or property of the Company</li>
		              <li>Prevent or investigate possible wrongdoing in connection with the Service</li>
		              <li>Protect the personal safety of Users of the Service or the public</li>
		              <li>Protect against legal liability</li>
		            </ul>
		            <h4>Security of Your Personal Data</h4>
		            <p>The security of Your Personal Data is important to Us, but remember that no method of transmission over the Internet, or method of electronic storage is 100% secure. While We strive to use commercially acceptable means to protect Your Personal Data, We cannot guarantee its absolute security.</p>
		            <h5>Children's Privacy</h5>
		            <p>Our Service does not address anyone under the age of 13. We do not knowingly collect personally identifiable information from anyone under the age of 13. If You are a parent or guardian and You are aware that Your child has provided Us with Personal Data, please contact Us. If We become aware that We have collected Personal Data from anyone under the age of 13 without verification of parental consent, We take steps to remove that information from Our servers.</p>
		            <p>If We need to rely on consent as a legal basis for processing Your information and Your country requires consent from a parent, We may require Your parent's consent before We collect and use that information.</p>
		            <h4>Links to Other Websites</h4>
		            <p>Our Service may contain links to other websites that are not operated by Us. If You click on a third party link, You will be directed to that third party's site. We strongly advise You to review the Privacy Policy of every site You visit.</p>
		            <p>We have no control over and assume no responsibility for the content, privacy policies or practices of any third party sites or services.</p>
		            <h4>Changes to this Privacy Policy</h4>
		            <p>We may update our Privacy Policy from time to time. We will notify You of any changes by posting the new Privacy Policy on this page.</p>
		            <p>We will let You know via email and/or a prominent notice on Our Service, prior to the change becoming effective and update the "Last updated" date at the top of this Privacy Policy.</p>
		            <p>You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>
		            <h4>Contact Us</h4>
		            <p>If you have any questions about this Privacy Policy, You can contact us:</p>
		            <p>By visiting this page on our website: https://coem.in/#/suggestion</p>
		          </v-col>
		        </v-row>
	        </v-card-text>
	        <v-divider style="margin: 0;"></v-divider>
	        <v-card-actions>
	          <v-spacer></v-spacer>
	          <v-btn color="primary" text @click="presaleDialog = false">Close</v-btn>
	        </v-card-actions>
	      </v-card>
	    </v-dialog>
	    <v-card dark  color="dark" class="my-card-block m-2">
		    <v-card-text class="pb-0">
			    <div class="d-flex justify-content-between mb-2">
			    	<div class="balance">
			    		<h5>Available balance</h5>
			    		<h5>₹ {{ (auth.balance != null ? auth.balance : 0 ) | addCommas }}</h5>
			    	</div>
			    	<div class="reward text-right">
			    		<h5>Secondary Balance</h5>
			    		<h5>₹ {{ auth.loss_reward_balance | addCommas }}</h5>
			    	</div>
			    </div>
		    </v-card-text>
		    <div class="d-flex justify-content-between align-items-center pb-5" style="padding: 0 15px;">
		    	<v-btn to="/recharge" color="success" depressed>Recharge</v-btn>
		    	<v-dialog v-model="dialog" width="1200">
			      <template v-slot:activator="{ on, attrs }">
	      			<v-btn class="ml-3" v-bind="attrs" color="light-blue darken-1" v-on="on">Read Rules</v-btn>
			      </template>
			      <v-card>
			        <v-card-title class="text-h5 grey lighten-2">Rule of Guess</v-card-title>
			        <v-card-text class="mt-3">
			          <p>2 minutes 30 seconds 1 issue, 2 minutes to order, 30 seconds to show the lottery result. It opens all day. The total number of trade is 2880 issues</p>
			          <p>If you spend 100 to trade, after deducting 2 service fee, your contract amount is 98:</p>
			          <p>1. JOIN KING: if the result shows 1,3,7,9, you will get (98*2) 196</p>
			          <p>If the result shows 5, you will get (98*1.5) 147</p>
			          <p>2. JOIN QUEEN: if the result shows 2,4,6,8, you will get (98*2) 196; If the result shows 0, you will get (98*1.5) 147</p>
			          <p>3. JOIN JOKER: if the result shows 0 or 5, you will get (98*4.5) 441</p>
			          <p>4. JOIN JACKPOT: if the result shows JACKPOT you will get (98*100) 9800</p>
			          <p>5. SELECT NUMBER: if the result is the same as the number you selected, you will get (98*9) 882</p>
			        </v-card-text>
			        <v-divider></v-divider>
			        <v-card-actions>
			          <v-spacer></v-spacer>
			          <v-btn color="primary" text @click="dialog = false">Close</v-btn>
			        </v-card-actions>
			      </v-card>
			    </v-dialog>
		    </div>
		  </v-card>
	    <v-main class="mb-15 admin-index-pages">
	  		<div class="game-title">Predict & {{ game.title }}</div>
	      <v-container fluid>
	        <v-row>
	        	<v-col>
	        		<div class="d-flex justify-space-between mt-1">
		            <div class="d-flex align-center">
		            	<span class="material-icons" style="color: #777;">emoji_events</span>
		            	<span style="color: #777;">Period</span>
		            </div>
		            <div class="d-flex align-center">
		            	<v-icon style="font-size: 20px; margin-right: 2px;">mdi-av-timer</v-icon>
			            <div style="color: #777;">Count Down</div>
		            </div>
		          </div>
		          <div class="d-flex justify-space-between mt-1 mb-4 align-items-center">
		            <h4 style="margin: 0;">{{period.period}}</h4>
		            <Countdown :end="period_countdown.toString()" v-if="period_countdown != null"></Countdown>
		          </div>
	        	</v-col>
	        </v-row>
	        <div class="odds-block">
	        	<div class="bet-lock-overlay" v-if="is_bet_lock"><v-icon>mdi-lock-outline</v-icon></div>
	        	<div class="d-flex justify-space-between mb-4">
	            <v-btn color="green" dark @click="selectOrder('king')">
	            	<img src="/images/king.png" width="25px"> <span style="position: absolute;
    right: -12px;
    top: -3px;
    font-size: 12px;
    font-weight: bold;">K</span>
	            </v-btn>
	            <v-btn color="indigo" dark @click="selectOrder('joker')">
	            	<img src="/images/joker_two.png" width="25px"> <span style="position: absolute;
    right: -12px;
    top: -3px;
    font-size: 12px;
    font-weight: bold;">J</span>
	            </v-btn>
	            <v-btn color="black" dark @click="selectOrder('jackpot')" style="overflow: hidden;">
	            	<img src="/images/jackpot.png" width="30px">
	            <span style="position: absolute;
    right: -16px;
    top: -1px;
    font-size: 8px;
    font-weight: bold;
    text-align: center;
    width: 65px;
    background-color: #FFEE58;
    letter-spacing: 1px;
    color: #000;
    text-transform: uppercase;">Jackpot</span></v-btn>
	            </v-btn>
	            <v-btn color="pink" dark @click="selectOrder('queen')">
	            	<img src="/images/queen.png" width="28px"> <span style="position: absolute;
    right: -12px;
    top: -3px;
    font-size: 12px;
    font-weight: bold;">Q</span>
	            </v-btn>
	          </div>
	          <v-row class="justify-center">
		        	<v-col>
		        		<v-btn dark block style="background: linear-gradient(to right bottom, #3f51b5 50%, #E91E63 50%);" @click="selectOrder('0')">0</v-btn>
		        	</v-col>
		        	<v-col>
		        		<v-btn block color="success" @click="selectOrder('1')">1</v-btn>
		        	</v-col>
		        	<v-col>
		        		<v-btn block dark color="pink" @click="selectOrder('2')">2</v-btn>
		        	</v-col>
		        	<v-col>
		        		<v-btn block color="success" @click="selectOrder('3')">3</v-btn>
		        	</v-col>
		        	<v-col>
		        		<v-btn block dark color="pink" @click="selectOrder('4')">4</v-btn>
		        	</v-col>
	        	</v-row>
	        	<v-row class="justify-center">
		        	<v-col>
		        		<v-btn block dark style="background: linear-gradient(to right bottom, #3f51b5 50%, rgb(76, 175, 80) 50%);" @click="selectOrder('5')">5</v-btn>
		        	</v-col>
		        	<v-col>
		        		<v-btn block dark color="pink" @click="selectOrder('6')">6</v-btn>
		        	</v-col>
		        	<v-col>
		        		<v-btn block color="success" @click="selectOrder('7')">7</v-btn>
		        	</v-col>
		        	<v-col>
		        		<v-btn block dark color="pink" @click="selectOrder('8')">8</v-btn>
		        	</v-col>
		        	<v-col>
		        		<v-btn block color="success" @click="selectOrder('9')">9</v-btn>
		        	</v-col>
	        	</v-row>
	        </div>
	      </v-container>
	      <v-container v-if="users_win_records_data.length > 2">
	      	<div class="users-main-records-block text-center">
				    <div class="carousel">
							<div class="change_outer">
								<div class="change_inner user-records-data">
									<h5 class="element font-weight-regular d-flex align-items-center justify-center" v-for="(data, index) in users_win_records_data">
				      			<v-icon>mdi-account-circle</v-icon>
				      			<span class="ml-1">{{ data.number }} {{ data.text }}</span>
				      		</h5>
								</div>
							</div>
						</div>
	      	</div>
	      </v-container>
	      <div class="details-block mt-5">
	      	<v-card class="mb-10">
	      		<v-card-title>
	      			<div class="text-center w-100">
			          <span class="material-icons">emoji_events</span>
			          <h5>Win Records</h5>
			      	</div>
	      		</v-card-title>
	          <v-data-table
	          	:disableSort="disableSort"
	            :headers="headers"
	            :items="allPeriods"
	            :search="search"
	            mobile-breakpoint="0"
	          >
	          	<template v-slot:item.result="{ item }">
	          		<div v-if="item.result == 'JACKPOT'" class="border" style="border-color: #FFC107 !important; border-radius: 5px !important;">
	          			<v-icon color="amber">mdi-star</v-icon>
	          			<v-icon color="amber">mdi-star</v-icon>
	          			<v-icon color="amber">mdi-star</v-icon>
	          		</div>
	          		<div v-else>
	          			<v-icon color="indigo" v-if="item.result == 0 || item.result == 5">mdi-star</v-icon>
						      <v-icon color="success" v-if="item.result == 1 || item.result == 3 || item.result == 5 || item.result == 7 || item.result == 9">mdi-star</v-icon>
						      <v-icon color="pink" v-else>mdi-star</v-icon>
	          		</div>
					    </template>
	          </v-data-table>
	        </v-card>
	        <v-card class="mb-10">
	      		<v-card-title class="mb-3">
	      			<div class="text-center w-100">
			          <span class="material-icons">receipt_long</span>
			          <h5>My Records</h5>
			      	</div>
	      		</v-card-title>
	      		<v-row justify="center">
					    <v-expansion-panels accordion>
					      <v-expansion-panel v-for="(order,i) in myOrders" :key="i">
					        <v-expansion-panel-header v-if="order.is_cleared == 0">{{ order.period.period }} &nbsp; &nbsp; <span class="text-warning">Wait</span></v-expansion-panel-header>
					        <v-expansion-panel-header v-else-if="order.pl > 0">{{ order.period.period }} &nbsp; &nbsp; <span class="text-success">Success &nbsp; &nbsp; +{{ order.pl | addCommas }}</span></v-expansion-panel-header>
					        <v-expansion-panel-header v-else-if="order.pl < 0">{{ order.period.period }} &nbsp; &nbsp; <span class="text-danger">Fail &nbsp; &nbsp; {{ order.pl | addCommas }}</span></v-expansion-panel-header>
					        <v-expansion-panel-content>
					          <h6 class="text-primary">Period Detail</h6>
					          <table class="table">
					          	<tbody>
					          		<tr>
					          			<td>Period</td>
					          			<td>{{ order.period.period }}</td>
					          		</tr>
					          		<tr>
					          			<td>Contact Money</td>
					          			<td>{{ order.contract_money }}</td>
					          		</tr>
					          		<tr>
					          			<td>Contract Count</td>
					          			<td>{{ order.contract_count }}</td>
					          		</tr>
					          		<tr>
					          			<td>Delivery</td>
					          			<td class="text-warning">{{ order.delivery | addCommas }}</td>
					          		</tr>
					          		<tr>
					          			<td>Fee</td>
					          			<td>{{ order.fee | addCommas }}</td>
					          		</tr>
					          		<tr>
					          			<td>Open Price</td>
					          			<td v-if="order.is_cleared == 0">Wait</td>
					          			<td v-else>{{ order.period.price_money }}</td>
					          		</tr>
					          		<tr>
					          			<td>Result</td>
					          			<td v-if="order.is_cleared == 0">Wait</td>
					          			<td v-else>{{ order.period.result }}</td>
					          		</tr>
					          		<tr>
					          			<td>Select</td>
					          			<td class="text-primary">{{ order.selection }}</td>
					          		</tr>
					          		<tr>
					          			<td>Status</td>
					          			<td v-if="order.is_cleared == 0" class="text-warning">Wait</td>
					          			<td v-else-if="order.pl > 0" class="text-success">Success</td>
					          			<td v-else-if="order.pl < 0" class="text-danger">Fail</td>
					          		</tr>
					          		<tr>
					          			<td>Amount</td>
					          			<td v-if="order.is_cleared == 0">Wait</td>
					          			<td v-else-if="order.pl > 0" class="text-success">+{{ order.pl | addCommas }}</td>
					          			<td v-else-if="order.pl < 0" class="text-danger">{{ order.pl | addCommas }}</td>
					          		</tr>
					          		<tr>
					          			<td>Create Time</td>
					          			<td>{{ order.created }}</td>
					          		</tr>
					          	</tbody>
					          </table>
					        </v-expansion-panel-content>
					      </v-expansion-panel>
					      <div class="pagination-main-block pt-5 pb-5 d-flex flex-row justify-center align-items-center flex-wrap">
			            <v-pagination
			              v-model="page"
			              :length="paginationLength"
			              :total-visible="5"
			              :loading="pageLoading"
			              class="mb-3 mb-md-0"
			            ></v-pagination>
			            <v-select
			              :items="perPageOptions"
			              style="max-width: 60px; margin: 0; padding: 0; margin-left: 10px;"
			              v-model="itemsPerPage"
			            ></v-select>
			          </div>
					    </v-expansion-panels>
					  </v-row>
	        </v-card>
	      </div>
	    </v-main>
  	</div>
  </v-app>
</template>
<script>
	import Countdown from 'vuejs-countdown';
  import { mapState } from "vuex";
  export default {
  	components: { Countdown },
    data () {
      return {
      	game: null,
      	toggle_exclusive: 0,
      	dialog: false,      	
      	orderDialog: false,
      	presaleDialog: false,
        tab: null,
        orderCheckbox: true,
        search: '',
        period: null,
        period_countdown: null,
        order: {
        	contract_money: 10,
        	contract_count: 1,
        	selection: null
        },
        is_bet_lock: false,
        local_countdown: 0,
        disableSort: true,
        headers: [
          { text: 'Period', align: 'center', value: 'period' },
          { text: 'Price', align: 'center', value: 'price_money' },
          { text: 'Number', align: 'center', value: 'number' },
          { text: 'Result', align: 'center', value: 'result' },
        ],
        allPeriods: [],
        myOrders: [],
        users_win_records_data: [],

        perPageOptions: ['10', '50', '100', '500'],
        itemsPerPage: '10',
        page: 1,
        paginationLength: 15,
        pageLoading: false,
        focused: true
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
    watch: {
    	focused(val) {
    		if (val == true) {
    			window.location.reload();
    		}
    	},
      page(val) {
        this.getRecords();
      },
      itemsPerPage(val) {
        this.getRecords();
      },
    },
    created() {
    	window.onfocus = function() {
			  this.focused = true;
			}.bind(this);
			window.onblur = function() {
			  this.focused = false;
			}.bind(this);
    	this.getGame();
    },
    computed: {
    	total_contract_money() {
    		return this.order.contract_count * this.order.contract_money;
    	},
      ...mapState([
        "auth",
      ]),
    },
    methods: {
      hasHistory () { return window.history.length > 2 },
      getGame(init = true) {
      	this.period_countdown = null;
      	axios.get('/get-game/win').then(res => {
      		this.game = res.data.game;
      		this.period = res.data.period;
      		var period_time = new Date(this.period.created_at);
      		this.period_countdown = new Date(period_time.setSeconds(period_time.getSeconds() + this.game.time));
      		if (this.game != null) {
      			if (init == true) {
		      		this.echoSetup();
      			}
	      		var now = new Date();
	      		var total_time_diff = Math.abs(this.period_countdown.getTime() - now.getTime()) / 1000;
	      		if (total_time_diff <= 30) {
	      			this.is_bet_lock = true;
	      		} else {
	      			this.local_countdown = Math.round(total_time_diff);
	      			this.countDownTimer();
	      		}
	      		this.getRecords(init);
      		}
      		this.users_win_records_data = res.data.users_win_records_data;
      		if (this.users_win_records_data.length > 2) {
						setTimeout(function() {
							this.animateUserRecords();
						}.bind(this), 1000);
      		}
      	}).catch(e => {
      		console.log(e.response);
      	});
      },
      animateUserRecords(i = 1) {
  			var className = ".user-records-data";
  			var px_size = 34;
  			var px = (i * px_size);
  			setTimeout(function () {
  				const current_animation = document.querySelector(className.toString());
					current_animation.animate([
						{ transform: `translateY(-${px - (px_size / 2)}px)`, opacity: 0.5, offset: 0.5}, { transform: `translateY(-${px}px)`, opacity: 1}
					], 700);
	      	if ((i + 1) < this.users_win_records_data.length) {
	      		setTimeout(function () {
	      			$(className).css('transform', `translateY(-${px}px)`);
		      		this.animateUserRecords(i + 1);
		      	}.bind(this), 700);
	      	} else {
	      		setTimeout(function () {
	      			$(className).css('transform', `translateY(0)`);
		      		this.animateUserRecords(1);
		      	}.bind(this), 700);
	      	}
  			}.bind(this), 1000);
      },
      getRecords() {
      	axios.post('/get-game-records/' + this.game.slug + '?page=' + this.page, {itemsPerPage: this.itemsPerPage}).then(res => {
      		this.allPeriods = res.data.periods;
      		this.pageLoading = false;
          this.myOrders = res.data.my_orders.data;
          this.paginationLength = res.data.my_orders.last_page;
          this.page = res.data.my_orders.current_page;
      	}).catch(e => {
      		console.log(e.response);
      	});
      },
      getMyOrders() {
      	axios.get('/get-my-game-orders/' + this.game.slug).then(res => {
      		this.myOrders = res.data.my_orders;
      	}).catch(e => {
      		console.log(e.response);
      	});
      },
      countDownTimer() {
    		if(this.local_countdown > 30) {
          setTimeout(() => {
            this.local_countdown -= 1;
            this.countDownTimer();
          }, 1000)
        } else {
          this.is_bet_lock = true;
        }
      },
      echoSetup() {
    		Echo.channel(`Game.${this.game.id}`).listen('GameBroadcast', (data) => {
	      	this.period_countdown = null;
	      	setTimeout(function () {
	      		this.period = data.data;
	    			var period_time = new Date(this.period.created_at);
	      		this.period_countdown = new Date(period_time.setSeconds(period_time.getSeconds() + this.game.time));
	      		var now = new Date();
	      		var total_time_diff = Math.abs(this.period_countdown.getTime() - now.getTime()) / 1000;
	      		if (total_time_diff <= 30) {
	      			this.is_bet_lock = true;
	      		} else {
	      			this.is_bet_lock = false;
	      			this.local_countdown = Math.round(total_time_diff);
	      			this.countDownTimer();
	      		}
	      		this.getRecords(false);
	      	}.bind(this), 100);
	      });
	      Echo.channel(`User.Update.${this.auth.id}`).listen('UserUpdate', (data) => {
          this.$store.commit('updateAuthBalance', (data.balance));
          this.$store.commit('updateAuthRewardBalance', (data.loss_reward_balance));
	      });
      },
      minusContractCount() {
      	if (this.order.contract_count > 1) {
      		this.order.contract_count--;
      	} else {
      		this.order.contract_count = 1;
      	}
      },
      selectOrder(selection = null) {
      	if (selection != null) {
      		this.order.selection = selection;
      		this.orderDialog = true;
      	}
      },
      submitOrder() {
      	axios.post('/submit-new-order', { order: this.order, game_id: this.game.id, period_id: this.period.id }).then(res => {
      		if (res.data.success != null) {
            this.$toasted.success(res.data.success.toString(), {icon: 'check_circle'});
            this.orderDialog = false;
            this.order = {
            	contract_money: this.order.contract_money,
		        	contract_count: 1,
		        	selection: null
            };
            this.getMyOrders();
      		} else if (res.data.error != null) {
            this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
      		}
      	}).catch(e => {
      		console.log(e.response);
      	});
      }
    }
  }
</script>