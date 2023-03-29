<template>
  <v-app id="AddComplaints"> 	
  	<v-card>
      <v-toolbar color="success">
        <v-btn icon class="white--text" @click="hasHistory() ? $router.go(-1) : $router.push('/')">
          <span class="material-icons">arrow_back_ios</span>
        </v-btn>
        <v-toolbar-title class="white--text">Add Contact us</v-toolbar-title>
      </v-toolbar>
    </v-card>
    <v-main class="mb-15">
    	<v-container fluid>
        <v-row class="mb-15 justify-center">
          <v-col cols="12">
            <v-select :items="items" v-model.trim="complains.type" label="Type" solo></v-select>
            <v-text-field label="WhatsApp" v-model.trim="complains.whatsapp" solo></v-text-field>
            <v-textarea label="Description" v-model.trim="complains.des" solo></v-textarea>
          </v-col>
          <v-col cols="8">
            <p class="text-center">Service: 10:00~17:00, Mon~Fri about 1~5 business days</p>
             <v-btn large block color="success" class="white--text" @click="submitComplains">
              <span>Cantinue</span>
            </v-btn>
          </v-col>
        </v-row>
    	</v-container>
    </v-main>	
   </v-app>
</template>
<script>
  export default {
    data: () => ({
      items: ['Suggestion', 'Consult', 'Recharge Problem', 'Withdrawal Problem', 'Game Problem', 'Referral Problem', 'Other'],
      complains: {
          type: '',
          whatsapp: '',
          des: '',
        },
    }),
    mounted() {
      console.log('Component mounted.')
    },
    methods: {
      hasHistory () { return window.history.length > 2 },
      submitComplains () {
        axios.post('/complain', this.complains).then(res => {
          if (res.data.error != null) {
            this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
          } else {
            this.$toasted.show('Complains Add successfully', {icon: 'power_settings_new'});
            setTimeout(function() {
              window.location.href = window.location.origin;                
            }, 800);
          }
        }).catch(e => {
          this.$toasted.error('Please try again', {icon: 'not_interested'});
          console.log(e.response);
        });        
      },
    }
  }
</script>
