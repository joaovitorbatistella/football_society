<template>
  <v-container align-center justify-center :style="{
        backgroundColor: '#323232',
        height: '85vh',
        marginTop: '3vh',
        borderRadius: '10px'
      }"
    >
    <v-row justify="center" align="center">
      <v-col justify="center" align="center" cols="12" lg="12" md="12" :style="{ 
        }"
      >
        <h1>JOGOS</h1>
      </v-col>
    </v-row>

    <v-row >
      <v-col justify="center" align="center" cols="12" lg="5" md="5" :style="{ 
        }"
      >
        <v-row :style="{ minHeight: '40vh', maxHeight: '40vh' }">
          <v-layout align-center justify-content-center column>
            <h2 :style="{
                marginBottom: '10px'
              }"
            >
              PRÓXIMO JOGO
            </h2>
            <card></card>
          </v-layout>
        </v-row>
        <v-row :style="{ minHeight: '40vh', maxHeight: '40vh' }">
          <v-col justify="center" align="center" cols="12" lg="12" md="12">
            <v-img
              max-height="204"
              max-width="353"
              src="https://picsum.photos/id/11/500/300"
            ></v-img>
          </v-col>
        </v-row>
      </v-col>

      <v-col justify="center" align="center" cols="12" lg="7" md="7" :style="{
          padding: '50px'
        }"
      >
        <v-data-table
          height="100%"
          :headers="gamesHeaders"
          :items="gamesList"
          :items-per-page="5"
          class="elevation-1"
        ></v-data-table>
      </v-col>
    </v-row>
    <loader
      :dialog="loading"
    />
  </v-container>
</template>

<script>
import Card from '../../components/card.vue'

export default {
  middleware: ['authenticated'],
  components: {
    Card
  },
  data: () => ({
    loading: false,
    gamesList: [],
    gamesHeaders: [
      { text: 'Horário', value: 'data_hora' },
      { text: 'Valor', value: 'valor' },
      { text: 'Atendimento', value: 'cod_atendimento' },
    ],

  }),
  // beforeMount() {
  //   let token = Cookies.get('jwt-token')   
  //     const config = {
  //         headers: {
  //           Authorization: 'Bearer '+ token
  //           }
  //     };
  //     this.loading = true
  //     this.$axios
  //       .get(`game/list/`, config)
  //       .then(({ data }) => {
  //         this.gamesList = data.response
  //         console.log(this.gamesList)
  //         this.loading = false
  //       })
  //       .catch(err => {
  //         console.log('error on GET: ', err)
  //       })
  // }
}
</script>

