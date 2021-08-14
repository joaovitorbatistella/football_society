<template>
  <v-row justify="center" align="center">
    <v-col cols="6" sm="6" md="6">
      <v-row justify="center" align="center">
        <form>
          <!-- <v-text-field
            v-model="email"
            :error-messages="emailErrors"
            label="E-mail"
            required
            @input="$v.email.$touch()"
            @blur="$v.email.$touch()"
          ></v-text-field> -->
          <v-text-field
            v-model="username"
            label="Usuário"
            required
          ></v-text-field>
          <v-text-field
            v-model="password"
            type="password"
            label="Senha"
            required
          ></v-text-field>
          <v-btn
            class="mr-4"
            @click="submit"
          >
            submit
          </v-btn>
          <v-btn @click="clear">
            clear
          </v-btn>
        </form>
      </v-row >
    </v-col>
  </v-row>
</template>

<script>
import Cookies from 'js-cookie'

  export default {
    middleware: ['notAuthenticated'],
    data: () => ({
      password: '',
      username: '',
    }),


    methods: {
      submit () {
        let request = {
          "username": this.username,
          "password": this.password
        }
        this.$axios
        .post(`users/login/`, request)
        .then(({ data }) => {
          if(data.type == 'success'){
            let token = data.response.token
            this.$router.push('/dash')
            Cookies.set('jwt-token', token, { expires: 1 })
          }

        })
        .catch(err => {
          console.log('error on GET: ', err)

        })
      },
      clear () {
        this.name = ''
        this.username = ''
      },
    },
  }
</script>