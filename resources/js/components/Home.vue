<template>
  <v-app id="inspire">
    <top-bar-layout></top-bar-layout>
    <v-container fluid>
      <div class="row g-0">
        <carousel animateOut="animate__animated animate__fadeOutLeft" animateIn="animate__animated animate__fadeInRight" :nav="false" :center="true" :margin="10" :items="1" :dots="false" :autoplay="true" :autoplayTimeout="5500" :smartSpeed="1000" :loop="true">
          <div class="slider-item"><img src="/images/mobile/01.jpg" alt="slider"></div>
          <div class="slider-item"><img src="/images/mobile/02.jpg" alt="slider"></div>
        </carousel>
      </div>    
      <v-row>
        <v-col cols="6">
          <v-btn class="text-center card_one" to="/game/win">
            <div>
              <div class="pt-3">
                <img src="/images/icons/king.png" alt="" width="60px">
              </div>
              <span class="chip mt-2 d-inline-block" style="font-size: 12px;">2.5 Min</span>
              <h6 class="game-name">JACKPOT GAME</h6>
            </div>
          </v-btn>
        </v-col>
        <v-col cols="6">
          <v-btn class="text-center card_two" to="/game/coin">
            <div>
              <div class="pt-3">
                <img src="/images/icons/coin.png" alt="" width="60px">
              </div>
              <span class="chip mt-2 d-inline-block" style="font-size: 12px;">Instant</span>
              <h6 class="game-name">COIN FLIP</h6>
            </div>
          </v-btn>
        </v-col>
        <v-col cols="6">
          <v-card class="text-center card_three" disabled>
            <div>
              <div class="pt-3">
                <img src="/images/icons/cricket.png" alt="" width="60px">
              </div>
              <span class="chip mt-2 d-inline-block" style="font-size: 12px;">SOON</span>
              <h6 class="game-name">IPL 2023</h6>
            </div>
          </v-card>
        </v-col>
        <v-col cols="6">
          <v-card class="text-center card_four" disabled>
            <div>
              <div class="pt-3">
                <img src="/images/icons/joystick.png" alt="" width="60px">
              </div>
              <span class="chip mt-2 d-inline-block" style="font-size: 12px;">More games</span>
              <h6 class="game-name">Comming Soon...</h6>
            </div>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    <!-- banner modal -->
    <modal name="home-banner-modal" transition="nice-modal-fade" :clickToClose="false" :scrollable="true" :adaptive="true" width="100%" height="auto" :max-width="400" :max-height="2000">
      <div>
        <div class="modal-body p-0">
          <div style="position: absolute; right: 14px; top: 10px; background-color: #FFF; border-radius: 100%; width: 35px; height: 35px; text-align: center; line-height: 3.3; box-shadow: 0 0 10px rgb(0 0 0 / 30%);">
            <a href="javascript:void(0)" class="default-close-btn" title="Close" @click="$modal.hide('home-banner-modal')">
              <i class="material-icons" style="color: #030303;">close</i>
            </a>
          </div>
          <img :src="'/images/poster/'+BannerData.home" width="100%" alt="">
        </div>
      </div>
    </modal>
    <!-- End banner modal -->
  </v-app>
</template>
<script>
  import carousel from 'vue-owl-carousel';
  import topBarLayout from "./Global/TopBarLayout.vue";
  export default {
    components: { carousel, topBarLayout },
    data() {
      return {
        value: 1,
        BannerData: [],        
        mobileDevice: ($(window).width() <= 768 ? true : false),
      }
    },
    created() {
      this.banner();
      this.getBanner();
    },
    methods: {
      getBanner() {
        axios.get('/get-banner').then(res => {
          console.log(res.data);
            this.BannerData = res.data.banner;
          }).catch(e => {
            this.getBanner();
            console.log(e.response);
          });
      },
      banner() {
        setTimeout(function(){
          this.$modal.show('home-banner-modal');
        }.bind(this), 500);
      },
    }
  }
</script>
<style scoped>
  .card_one,
  .card_two,
  .card_three,
  .card_four {
    height: 150px !important;
    width: 100% !important;
    border-radius: 10px;
  }
  .v-sheet.v-card {
    height: 150px !important;
    width: 100% !important;
    border-radius: 10px;
  }
  .chip {
    border: 1px solid #ddd;
    padding: 5px 10px;
    border-radius: 18px;
    background-color: #f6f6f6;
    margin-top: 5px;
    color: #111;
  }
  .game-name {
    padding: 10px 5px;
    font-size: 16px;
    color: #fff;
    font-weight: 700;
  }
  .card_one {
    background: #8E2DE2 !important;
  }
  .card_two {
    background-color: #FF0099 !important;
  }
  .card_three {
    background-color: #009fff !important;
  }
  .card_four {
    background-color: #0f9b0f !important;
  }
  .v-card--disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
</style>