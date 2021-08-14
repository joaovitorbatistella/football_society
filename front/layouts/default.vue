<template>
  <v-app dark>
    <v-layout justify-space-between>
      <v-bottom-navigation
        :value="value"
        color="teal"
        grow
      >
        <v-btn href="/dash">
          <span>Dashboard</span>
          <v-icon>mdi-view-dashboard</v-icon>
        </v-btn>
      </v-bottom-navigation>
      <buttons-nav/>
      <v-bottom-navigation
        :value="value"
        color="teal"
        grow
      >
        <v-btn href="/logout">
          <span>Sair</span>
          <v-icon>mdi-logout</v-icon>
        </v-btn>
      </v-bottom-navigation>
    </v-layout>
    <v-main>
      <v-container>
        <Nuxt />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import buttonsNav from '../components/buttonsNav.vue'
export default {
  components: {
    buttonsNav
  },
  data () {
    return {
      fixed: false,
    }
  }
}
</script>
