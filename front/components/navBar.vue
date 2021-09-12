<template>
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
      <v-btn href="/ref">
        <span>Valor Ref.</span>
        <v-icon>mdi-gift-open-outline</v-icon>
      </v-btn>
      <v-btn v-if="token != undefined" @click="logout">
        <span>Sair</span>
        <v-icon>mdi-logout</v-icon>
      </v-btn>
      
    </v-bottom-navigation>
    <loader
      :message="loaderMessage"
      :dialog="loading"
    />
  </v-layout>
</template>

<script>
import Cookies from 'js-cookie'
import Loader from './loader.vue'
import buttonsNav from './buttonsNav.vue'

export default {
  name: 'navBar',
  middleware: ['authenticated'],
  components: {
    Loader,
    buttonsNav
  },
  data () {
    return {
      loading: false,
      fixed: false,
      loaderMessage: 'Saindo',
      token: ''
    }
  },
  beforeMount() {
    this.token = Cookies.get('jwt-token')
  },
  updated() {
    this.token = Cookies.get('jwt-token')
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