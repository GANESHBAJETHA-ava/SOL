<template>
  <v-app id="Payment">
    <v-main class="mb-15">
      <v-container fluid>
        <v-row class="justify-center">
          <v-col cols="6">
            <v-form ref="form">
              <!-- <v-img max-height="150" max-width="250" :src="'/images/payment/'+ order.order_id +'.svg'"></v-img> -->
              <div class="d-flex">
                <h5>{{ upi }}</h5>
                <v-btn elevation="2" @click="copy(upi, $event)">COPY</v-btn>
              </div>
            </v-form> 
          </v-col>
        </v-row>
      </v-container>
    </v-main> 
   </v-app>
</template>
<script>
  export default {
    data () {
      return {

      }
    },
    mounted() {
      console.log('Component mounted.')
    },
    methods: {
      hasHistory () { return window.history.length > 2 },
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