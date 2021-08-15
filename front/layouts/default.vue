<template>
  <v-app dark>
    <v-layout justify-space-between>
      <v-bottom-navigation
        color="blue-grey"
        grow
      >
        <v-btn href="/">
          <span>Início</span>
          <v-icon>mdi-home</v-icon>
        </v-btn>
      </v-bottom-navigation>
      <buttons-nav/>
      <v-bottom-navigation
        color="blue-grey"
        grow
      >
        <v-btn @click="logout">
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
    <loader
      :message="loaderMessage"
      :dialog="loading"
    />
  </v-app>
</template>

<script>
import Cookies from 'js-cookie'
import Loader from '../components/loader.vue'
import buttonsNav from '../components/buttonsNav.vue'

export default {
  components: {
    Loader,
    buttonsNav
  },
  data () {
    return {
      loading: false,
      fixed: false,
      loaderMessage: 'Saindo',
    }
  },
  methods: {
    logout() {
      let token = Cookies.get('jwt-token')   
      const config = {
          headers: {
            Authorization: 'Bearer '+ token
            }
      };
      this.loading = true
      this.$axios
        .get(`users/logout/`, config)
        .then(({ data }) => {
          if( data.type == 'success') {
            Cookies.remove('jwt-token')
            this.loading = false
            this.$router.push('/')
          } else {
            this.loading = false
            alert('Erro ao tentar sair')
          }
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
    }
  }
}
</script>
