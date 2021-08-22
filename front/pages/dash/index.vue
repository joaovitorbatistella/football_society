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
        <a :style="{ textDecoration: 'none', color: 'white' }"><h1>DASHBOARD</h1></a>

        <v-row justify="center" align="center" >
          <v-col justify="center" align="center" cols="12" sm="4" md="4">
            <v-layout align-top justify-top column :style="{
                backgroundColor: '#212121',
                borderRadius: '8px',
                minHeight: '30vh',
                maxHeight: '100%',
                margin: '23px'
              }"
            >
              <a :style="{ textDecoration: 'none', color: 'white' }" href="/game"><h1>JOGOS</h1></a>
              <v-divider></v-divider>
              <v-data-table
                height="100%"
                :headers="gamesHeaders"
                :items="gamesList"
                :items-per-page="3"
                class="elevation-1"
              ></v-data-table>
              
            </v-layout>
          </v-col>
          <v-col justify="center" align="center" cols="12" sm="4" md="4">
            <v-layout align-top justify-top column :style="{
                backgroundColor: '#212121',
                borderRadius: '8px',
                minHeight: '30vh',
                maxHeight: '100%',
                margin: '23px'
              }"
            >
              <a :style="{ textDecoration: 'none', color: 'white' }" href="/attendance"><h1>ATENDIMENTOS</h1></a>
              <v-divider></v-divider>
              <v-data-table
                height="100%"
                :headers="attendancesHeaders"
                :items="attendancesList"
                :items-per-page="3"
                class="elevation-1"
              ></v-data-table>
              
            </v-layout>
          </v-col>
          <v-col justify="center" align="center" cols="12" sm="4" md="4">
            <v-layout align-top justify-top column :style="{
                backgroundColor: '#212121',
                borderRadius: '8px',
                minHeight: '30vh',
                maxHeight: '100%',
                margin: '23px'
              }"
            >
              <a :style="{ textDecoration: 'none', color: 'white' }" href="/customer"><h1>CLIENTES</h1></a>
              <v-divider></v-divider>
              <v-data-table
                height="100%"
                :headers="customersHeaders"
                :items="customersList"
                :items-per-page="3"
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
                minHeight: '30vh',
                maxHeight: '100%',
                margin: '23px'
              }"
            >
              <a :style="{ textDecoration: 'none', color: 'white' }" href="/product"><h1>PRODUTOS</h1></a>
              <v-divider></v-divider>
              <v-data-table
                height="100%"
                :headers="productsHeaders"
                :items="productsList"
                :items-per-page="3"
                class="elevation-1"
              ></v-data-table>
              
            </v-layout>
          </v-col>
          <v-col justify="center" align="center" cols="12" sm="4" md="4">
            <v-layout align-top justify-top column :style="{
                backgroundColor: '#212121',
                borderRadius: '8px',
                minHeight: '30vh',
                maxHeight: '100%',
                margin: '23px'
              }"
            >
              <a :style="{ textDecoration: 'none', color: 'white' }" href="/provider"><h1>FORNECEDORES</h1></a>
              <v-divider></v-divider>
              <v-data-table
                height="100%"
                :headers="providersHeaders"
                :items="providersList"
                :items-per-page="3"
                class="elevation-1"
              ></v-data-table>
              
            </v-layout>
          </v-col>
          <v-col justify="center" align="center" cols="12" sm="4" md="4">
            <v-layout align-top justify-top column :style="{
                backgroundColor: '#212121',
                borderRadius: '8px',
                minHeight: '30vh',
                maxHeight: '100%',
                margin: '23px'
              }"
            >
              <a :style="{ textDecoration: 'none', color: 'white' }" href="/users"><h1>USUÁRIOS</h1></a>
              <v-divider></v-divider>
              <v-data-table
                height="85%"
                :headers="usersHeaders"
                :items="usersList"
                :items-per-page="3"
                class="elevation-1"
              ></v-data-table>
              
            </v-layout>
          </v-col>
        </v-row>
            
      </v-col>
    </v-row>
    <loader
      :message="loaderMessage"
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
      loaderMessage: 'Carregando',
      gamesList: [],
      gamesHeaders: [
        { text: 'Horário', value: 'data_hora' },
        { text: 'Valor', value: 'valor' },
        { text: 'Atendimento', value: 'nome_cliente' },
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
    async beforeMount() {
      try {
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
          if(data.type == 'success') {
            this.usersList = data.response
          } else {
            this.loading = false
            alert('Erro ao buscar dados')
          }
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
      
      this.$axios
        .get(`provider/list/`, config)
        .then(({ data }) => {
          if(data.type == 'success') {
            this.providersList = data.response
          } else {
            this.loading = false
            alert('Erro ao buscar dados')
          }
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
      
      this.$axios
        .get(`product/list/`, config)
        .then(({ data }) => {
          if(data.type == 'success') {
            this.productsList = data.response
          } else {
            this.loading = false
            alert('Erro ao buscar dados')
          }
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
      
      this.$axios
        .get(`customer/list/`, config)
        .then(({ data }) => {
          if(data.type == 'success') {
            this.customersList = data.response
          } else {
            this.loading = false
            alert('Erro ao buscar dados')
          }
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
      
      this.$axios
        .get(`attendance/list/`, config)
        .then(({ data }) => {
          if(data.type == 'success') {
            this.attendancesList = data.response
          } else {
            this.loading = false
            alert('Erro ao buscar dados')
          }
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
      
      await this.$axios
        .get(`game/list/`, config)
        .then(({ data }) => {
          if(data.type == 'success') {
            this.gamesList = data.response
          } else {
            this.loading = false
            alert('Erro ao buscar dados')
          }
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
      
      this.loading = false
      } catch(e) {
        console.error(e);
      }
      
  },
}
</script>
