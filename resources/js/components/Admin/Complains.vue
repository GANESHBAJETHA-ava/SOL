<template>
  <v-app>
    <v-main class="admin-index-pages">
      <v-container fluid>
        <v-card>
          <v-card-title class="justify-content-between">
            <span>Complains</span>
          </v-card-title>
          <v-row class="align-center mt-2">
            <div v-for="complain in complains">
              <div class="d-flex justify-space-between align-center p-2">
                <div class="d-flex justify-center">
                  <div>
                    <h6 class="font-weight-medium"> <strong>USERID : {{ complain.user.phone }}</strong></h6>
                    <h6 class="font-weight-medium"> ₹ {{ complain.type }}</h6>
                    <p class="font-weight-regular mb-0">{{ complain.whatsapp }}</p>
                    <p class="font-weight-regular mb-0">{{ complain.des  }}</p>
                  </div>
                </div>
                <div class="d-flex justify-center">
                  <div>
                    <h6 class="font-weight-regular mb-1">{{ moment(complain.created_at).format("YYYY-MM-DD hh:mm:ss")  }}</h6>
                  </div>
                </div>
              </div>
              <div class="row p-2">
                <div class="col-sm-9">
                  <div class="form-group">
                    <input type="text" v-model="reply" class="form-control" placeholder="Reply">
                  </div>
                </div>
                <div class="col-sm-3">
                  <button type="submit" @click="Reply(complain.id)" class="btn text-white w-100 btn-success">Reply</button>
                </div>
              </div>
              <v-divider></v-divider>
            </div>
          </v-row>
        </v-card>
      </v-container>
    </v-main> 
   </v-app>
</template>
<script>
import moment from "moment";
  export default {
    data () {
      return {
        moment: moment,
        ripple: true,
        complains: [],
        reply: null,
      }
    },
    mounted() {
      console.log('Component mounted.')
    },
    created() {
      this.getComplains();
    },
    methods: {
      hasHistory () { return window.history.length > 2 },
      Reply(id) {
         axios.post('/complains-reply', {id : id, reply: this.reply}).then(res => {
          console.log(res.data);
          if (res.data.error != null) {
            this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
          } else {
            this.$toasted.show('Complain reply successfully', {icon: 'power_settings_new'});
          }
        }).catch(e => {
          this.$toasted.error('Please try again', {icon: 'not_interested'});
          console.log(e.response);
        });
      },
      getComplains() {
        axios.get('/customer-complains').then(res => {
          console.log(res.data);
          this.complains = res.data.complains;
        }).catch(e => {
          this.getComplains();
          console.log(e.response);
        });
      },
    }
  }
</script>
