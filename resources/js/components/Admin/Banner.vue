<template>
  <v-app id="upi" class="admin-index-pages">
    <v-main class="mb-15">
      <v-container fluid>
        <v-card>
          <v-card-title>Banners</v-card-title>
          <v-card-text class="mt-4">
            <v-form ref="form" id="bannerHomeForm">
              <v-row class="">
                <div class="col-sm-12 col-lg-4">
                  <h5>Home</h5>
                  <input type="file" class="form-control mb-2" @change="upload_home">
                  <v-img max-height="150" max-width="250" :src="get_home()" v-bind:style="UpdateBanner.banner"></v-img>
                    <v-btn class="mt-5" color="success" @click="updateHomeBannerData" style="padding: 10px 30px;">
                      <span>Submit</span>
                    </v-btn>
                  <!-- <v-card-actions class="justify-center">
                  </v-card-actions> -->
                </div> 
                <div class="col-sm-12 col-lg-4">
                  <h5>Promotion</h5>
                  <input type="file" class="form-control mb-2" @change="upload_permotion">
                  <v-img max-height="150" max-width="250" :src="get_permotion()" v-bind:style="UpdateBanner.banner"></v-img>
                  <v-card-actions class="justify-center">
                    <v-btn color="success" @click="updatePromotionBannerData" style="padding: 10px 30px;">
                      <span>Submit</span>
                    </v-btn>
                  </v-card-actions>
                </div>      
                <div class="col-sm-12 col-lg-4">
                  <h5>Withdrawal</h5>
                  <input type="file" class="form-control mb-2" @change="upload_dw">
                  <v-img max-height="150" max-width="250" :src="get_dw()" v-bind:style="UpdateBanner.banner"></v-img>
                  <v-card-actions class="justify-center">
                    <v-btn color="success" @click="updateDwBannerData" style="padding: 10px 30px;">
                      <span>Submit</span>
                    </v-btn>
                  </v-card-actions>
                </div>      
              </v-row>
            </v-form>
            <v-form ref="form" id="bannerPromotionForm">
              <v-row class="justify-center">
                           
              </v-row>
            </v-form>
            <v-form ref="form" id="bannerDwForm">
              <v-row class="justify-center">
                           
              </v-row>
            </v-form>
          </v-card-text>
          <v-divider></v-divider>          
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
        UpdateBanner: {
          home: '',
          permotion: '',
          dw: '',
          banner: {
             width: '100px',
             height: '100px'
          }
        },
        BannerData: [],
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
    created() {
      this.getBanner();
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
            console.log(res.data);
            if (res.data.error != null) {
              this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'});
            } else {
              this.$toasted.show('UPI ID Add successfully', {icon: 'power_settings_new'});
              this.$router.go(-1);
            }
          }).catch(e => {
            this.$toasted.error('Please try again', {icon: 'not_interested'});
            console.log(e.response);
          });
        }
      },
      getBanner() {
        axios.get('/get-banner').then(res => {
          console.log(res.data);
            this.BannerData = res.data.banner;
            this.UpdateBanner.home = this.BannerData.home;
            this.UpdateBanner.permotion = this.BannerData.permotion;
            this.UpdateBanner.dw = this.BannerData.dw;
            console.log(this.UpdateBanner);
          }).catch(e => {
            this.getBanner();
            console.log(e.response);
          });
      },
      updateHomeBannerData() {
        axios.post('/update-home-banner', this.UpdateBanner).then((res) => {
          console.log(res.data);
            if (res.data.error != null) {
              this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'})
            } else if (res.data.success != null) {
              this.$toasted.show(res.data.success.toString(), {icon: 'check_circle'})
            }
          })
          .catch((e) => {
            this.$toasted.error('Please try again', {icon: 'not_interested'});
            console.log(e.response);
          });
      },
      updatePromotionBannerData() {
        axios.post('/update-promotion-banner', this.UpdateBanner).then((res) => {
          console.log(res.data);
            if (res.data.error != null) {
              this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'})
            } else if (res.data.success != null) {
              this.$toasted.show(res.data.success.toString(), {icon: 'check_circle'})
            }
          })
          .catch((e) => {
            this.$toasted.error('Please try again', {icon: 'not_interested'});
            console.log(e.response);
          });
      },
      updateDwBannerData() {
        axios.post('/update-dw-banner', this.UpdateBanner).then((res) => {
          console.log(res.data);
            if (res.data.error != null) {
              this.$toasted.error(res.data.error.toString(), {icon: 'not_interested'})
            } else if (res.data.success != null) {
              this.$toasted.show(res.data.success.toString(), {icon: 'check_circle'})
            }
          })
          .catch((e) => {
            this.$toasted.error('Please try again', {icon: 'not_interested'});
            console.log(e.response);
          });
      },
      upload_home(e){
        let file = e.target.files[0];
        let reader = new FileReader();
        if(file['size'] < 2111775)
        {
          reader.onloadend = (file) => {
            this.UpdateBanner.home = reader.result;
          }
            reader.readAsDataURL(file);
        }else{
          alert('File size can not be bigger than 2 MB')
        }
      },
      get_home(){
         let photo = (this.UpdateBanner.home.length > 100) ? this.UpdateBanner.home :"/images/poster/"+this.UpdateBanner.home;
         return photo;
      }, 
      upload_permotion(e){
        let file = e.target.files[0];
        let reader = new FileReader();
        if(file['size'] < 2111775)
        {
          reader.onloadend = (file) => {
            this.UpdateBanner.permotion = reader.result;
          }
            reader.readAsDataURL(file);
        }else{
          alert('File size can not be bigger than 2 MB')
        }
      },
      get_permotion(){
         let photo = (this.UpdateBanner.permotion.length > 100) ? this.UpdateBanner.permotion :"/images/poster/"+this.UpdateBanner.permotion;
         return photo;
      },
      upload_dw(e){
        let file = e.target.files[0];
        let reader = new FileReader();
        if(file['size'] < 2111775)
        {
          reader.onloadend = (file) => {
            this.UpdateBanner.dw = reader.result;
          }
            reader.readAsDataURL(file);
        }else{
          alert('File size can not be bigger than 2 MB')
        }
      },
      get_dw(){
         let photo = (this.UpdateBanner.dw.length > 100) ? this.UpdateBanner.dw :"/images/poster/"+this.UpdateBanner.dw;
         return photo;
      },  
    }
  }
</script>
