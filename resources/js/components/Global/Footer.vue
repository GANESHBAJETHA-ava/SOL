<template> 
  <v-footer>
    <div class="d-flex justify-content-center">
      <p>© Copyright 2022,site name. All rights are reserved.</p>      
    </div>   
  </v-footer>
</template>
 <script>
  import { mapState } from "vuex";
  export default {
    data() {
      return {
        
      }
    },
    computed: {
      ...mapState([
        "auth",
      ]),
    },
    methods: {
      
    }
  }
</script>
<style scoped>
  .v-footer  {
    margin-bottom: 60px;
  }
</style>