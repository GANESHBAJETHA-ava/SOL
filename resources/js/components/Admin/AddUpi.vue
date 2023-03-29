<template>
  <v-app>
    <v-main class="mb-15">  
      <v-container fluid>
        <v-card>
          <v-card-title>
            <v-btn icon @click="hasHistory() ? $router.go(-1) : $router.push('/')">
              <span class="material-icons">arrow_back_ios</span>
            </v-btn>
            <span class="ml-2">Add Bank UPI</span>
          </v-card-title>
          <v-card-text>
            <v-form ref="form">
              <v-text-field label="UPI ID" v-model.trim="upi.upi_id" :rules="upiErrors"></v-text-field>
              <v-text-field label="Name" v-model.trim="upi.name" :rules="nameErrors"></v-text-field>
              <v-text-field label="Type" v-model.trim="upi.type" :rules="typeErrors"></v-text-field>
            </v-form>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions class="justify-center">
            <v-btn color="success" @click="submitUpi" style="padding: 10px 30px;">
              <span>Submit</span>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-container>
    </v-main> 
   </v-app>
</template>
<script>
  import { mapState } from "vuex";
  export default {
  	data () {
      return {
        upi: {
          upi_id: '',
          name: '',
          type: '',
        },
        upiErrors: [
          v => !!v || 'Upi id name is required',
        ],
        nameErrors: [
          v => !!v || 'Name is required',
        ],
        typeErrors: [
          v => !!v || 'Type is required',
        ],
      }
    },
    computed: {
      ...mapState([
        "auth",
      ]),
    },
    methods: {
      hasHistory () { 
      	return window.history.length > 2 
      },
      validate () {
        return this.$refs.form.validate();
      },
      submitUpi () {
        if (this.validate()) {
          axios.post('/upi', this.upi).then(res => {
            if (res.data.error != null) {
              this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
            } else {
              this.$toasted.show('UPI ID Add successfully', {icon: 'check_circle'});
              this.$router.go(-1);
            }
          }).catch(e => {
            this.$toasted.error('Please try again', {icon: 'not_interested'});
            console.log(e.response);
          });
        }
      },     
    }
  }
</script>
