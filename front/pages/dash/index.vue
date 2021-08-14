<template>
  <v-container >
    <v-row justify="center" align="center" >
      <v-col justify="center" align="center" cols="12" sm="11" md="11" :style="{
          backgroundColor: '#323232',
          borderRadius: '8px',
          height: '85vh',
          marginTop: '3vh'
        }"
      >
        <a :style="{ textDecoration: 'none', color: 'white' }" href=""><h1>DASHBOARD</h1></a>

        <v-row justify="center" align="center" >
          <v-col justify="center" align="center" cols="12" sm="4" md="4">
            <v-layout align-top justify-top column :style="{
                backgroundColor: '#212121',
                borderRadius: '8px',
                height: '30vh',
                margin: '23px'
              }"
            >
              <a :style="{ textDecoration: 'none', color: 'white' }" href=""><h1>JOGOS</h1></a>
              <v-divider></v-divider>
              <v-data-table
                height="100%"
                :headers="gamesHeaders"
                :items="gamesList"
                :items-per-page="5"
                class="elevation-1"
              ></v-data-table>
              
            </v-layout>
          </v-col>
          <v-col justify="center" align="center" cols="12" sm="4" md="4">
            <v-layout align-top justify-top column :style="{
                backgroundColor: '#212121',
                borderRadius: '8px',
                height: '30vh',
                margin: '23px'
              }"
            >
              <a :style="{ textDecoration: 'none', color: 'white' }" href="/attendance"><h1>ATENDIMENTOS</h1></a>
              <v-divider></v-divider>
              <v-data-table
                height="100%"
                :headers="attendancesHeaders"
                :items="attendancesList"
                :items-per-page="5"
                class="elevation-1"
              ></v-data-table>
              
            </v-layout>
          </v-col>
          <v-col justify="center" align="center" cols="12" sm="4" md="4">
            <v-layout align-top justify-top column :style="{
                backgroundColor: '#212121',
                borderRadius: '8px',
                height: '30vh',
                margin: '23px'
              }"
            >
              <a :style="{ textDecoration: 'none', color: 'white' }" href=""><h1>CLIENTES</h1></a>
              <v-divider></v-divider>
              <v-data-table
                height="100%"
                :headers="customersHeaders"
                :items="customersList"
                :items-per-page="5"
                class="elevation-1"
              ></v-data-table>
              
            </v-layout>
          </v-col>
        </v-row>




        <v-row justify="center" align="center" >
          <v-col justify="center" align="center" cols="12" sm="4" md="4">
            <v-layout align-top justify-top column :style="{
                backgroundColor: '#212121',
                borderRadius: '8px',
                height: '30vh',
                margin: '23px'
              }"
            >
              <a :style="{ textDecoration: 'none', color: 'white' }" href=""><h1>PRODUTOS</h1></a>
              <v-divider></v-divider>
              <v-data-table
                height="100%"
                :headers="productsHeaders"
                :items="productsList"
                :items-per-page="5"
                class="elevation-1"
              ></v-data-table>
              
            </v-layout>
          </v-col>
          <v-col justify="center" align="center" cols="12" sm="4" md="4">
            <v-layout align-top justify-top column :style="{
                backgroundColor: '#212121',
                borderRadius: '8px',
                height: '30vh',
                margin: '23px'
              }"
            >
              <a :style="{ textDecoration: 'none', color: 'white' }" href=""><h1>FORNECEDORES</h1></a>
              <v-divider></v-divider>
              <v-data-table
                height="100%"
                :headers="providersHeaders"
                :items="providersList"
                :items-per-page="5"
                class="elevation-1"
              ></v-data-table>
              
            </v-layout>
          </v-col>
          <v-col justify="center" align="center" cols="12" sm="4" md="4">
            <v-layout align-top justify-top column :style="{
                backgroundColor: '#212121',
                borderRadius: '8px',
                height: '30vh',
                margin: '23px'
              }"
            >
              <a :style="{ textDecoration: 'none', color: 'white' }" href=""><h1>USUÁRIOS</h1></a>
              <v-divider></v-divider>
              <v-data-table
                height="100%"
                :headers="usersHeaders"
                :items="usersList"
                :items-per-page="2"
                class="elevation-1"
              ></v-data-table>
              
            </v-layout>
          </v-col>
        </v-row>
            
      </v-col>
    </v-row>
    <loader
      :dialog="loading"
    />
  </v-container>
</template>


<script>

import Cookies from 'js-cookie'
import Loader from '../../components/loader.vue'

export default {
  middleware: ['authenticated'],
  componets: {
    Loader
  },
  data () {
    return {
      loading: false,
      gamesList: [],
      gamesHeaders: [
        { text: 'Horário', value: 'data_hora' },
        { text: 'Valor', value: 'valor' },
        { text: 'Atendimento', value: 'cod_atendimento' },
      ],
      attendancesList: [],
      attendancesHeaders: [
        { text: 'Código', value: 'codigo' },
        { text: 'Desc.', value: 'descricao' },
        { text: 'Data/Hora', value: 'data_hora' },
        { text: 'Status', value: 'pago' },
      ],
      customersList: [],
      customersHeaders: [
        { text: 'Código', value: 'codigo' },
        { text: 'Nome', value: 'nome' },
        { text: 'Telefone', value: 'telefone' },
      ],
      productsList: [],
      productsHeaders: [
        { text: 'Código', value: 'codigo' },
        { text: 'Nome', value: 'nome' },
        { text: 'Preço', value: 'preco' },
        { text: 'Estoque', value: 'estoque' },
      ],
      providersList: [],
      providersHeaders: [
        { text: 'Código', value: 'codigo' },
        { text: 'Nome', value: 'nome' },
        { text: 'Telefone', value: 'telefone' },
      ],
      usersList: [],
      usersHeaders: [
        { text: 'Código', value: 'codigo' },
        { text: 'Nome', value: 'nome' },
        { text: 'Nome de Usuário', value: 'nome_de_usuario' },
      ],
    }
  },
    beforeMount() {
      let token = Cookies.get('jwt-token')   
      const config = {
          headers: {
            Authorization: 'Bearer '+ token
            }
      };
      this.loading = true
      this.$axios
        .get(`users/list/`, config)
        .then(({ data }) => {
          this.usersList = data.response
          console.log(this.usersList)
          this.loading = false
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
  },
}
</script>
