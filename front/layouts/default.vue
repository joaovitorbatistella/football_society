<template>
  <v-app dark>
    <nav-bar/>
    <v-main>
      <v-container>
        <Nuxt />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import navBar from '../components/navBar.vue'

export default {
  components: {
    navBar
  },
  data () {
    return {

    }
  },
}
</script>
