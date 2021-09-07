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
          :headers="gamesHeaders"
          :items="gamesList"
          sort-by="calories"
          class="elevation-1"
        >
          <template v-slot:top>
            <v-toolbar
              flat
            >
              <v-spacer></v-spacer>
              <v-dialog
                 color= "lime accent-3"
                 v-model="dialog"
                max-width="500px"
              >
                <template v-slot:activator="{ on, attrs }">
                   <v-btn dark
                    class="mb-2"
                    v-bind="attrs"
                    v-on="on"
                  >
                    CADASTRAR 
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="text-h5">{{ formTitle }}</span>
                  </v-card-title>
      
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col
                          cols="12"
                          sm="6"
                          md="4"
                        >
                          <v-text-field
                             color= "lime accent-3"
                             v-model="editedItem[0].data_hora"
                            label="Data e Hora"
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="4"
                        >
                          <v-text-field
                             color= "lime accent-3"
                             v-model="editedItem[0].valor"
                            label="Valor  (R$)"
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="4"
                        >
                          <v-text-field
                             color= "lime accent-3"
                             v-model="editedItem[0].desconto"
                            label="Desconto (R$)"
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="4"
                        >
                          <v-text-field
                             color= "lime accent-3"
                             v-model="editedItem[0].descricao"
                            label="Descrição"
                          ></v-text-field>
                        </v-col>                        
                        <v-col
                          cols="12"
                          sm="6"
                          md="4"
                        >
                          <v-text-field
                             color= "lime accent-3"
                             v-model="editedItem[0].cod_atendimento"
                            label="Atendimento"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
      
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                      color="lime accent-3"
                      text
                      @click="close"
                    >
                      Cancel
                    </v-btn>
                    <v-btn
                      color="lime accent-3"
                      text
                      @click="save"
                    >
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
              <v-dialog  color= "lime accent-3"
              v-model="dialogDelete" max-width="500px">
                <v-card>
                  <v-card-title class="text-h5">Você deseja excluir este jogo?</v-card-title>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="lime accent-3" text @click="closeDelete">Cancel</v-btn>
                    <v-btn color="lime accent-3" text @click="deleteItemConfirm(toDelte)">OK</v-btn>
                    <v-spacer></v-spacer>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-toolbar>
          </template>
          <template v-slot:item.data_hora="{ item }">
            <span>{{ formattedDate(item.data_hora) }}</span>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-icon
              small
              class="mr-2"
              @click="editItem(item.data_hora)"
            >
              mdi-pencil
            </v-icon>
            <v-icon
              small
              @click="deleteItem(item.data_hora)"
            >
              mdi-delete
            </v-icon>
          </template>
        </v-data-table>
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
import Card from '../../components/card.vue'
import moment from 'moment'
moment.locale('pt-br')

export default {
  middleware: ['authenticated'],
  components: {
    Card
  },
  data: () => ({
    loading: false,
    loaderMessage: 'Carregando',
    dialog: false,
    dialogDelete: false,
    gamesList: [],
    gamesHeaders: [
      { text: 'Horário', value: 'data_hora' },
      { text: 'Valor', value: 'valor' },
      { text: 'Atend. Cliente', value: 'nome_cliente' },
      { text: 'Actions', value: 'actions', sortable: false },
    ],
    editedIndex: -1,
    editedItem: [
      {
        data_hora: '',
        valor: '',
        descricao: '',
        desconto: '',
        cod_atendimento: ''
      }
    ],
  }),
  async beforeMount() {
    try {
      let token = Cookies.get('jwt-token')   
      const config = {
          headers: {
            Authorization: 'Bearer '+ token
            }
      };
      this.loading = true
      await this.$axios
        .get(`game/list/`, config)
        .then(({ data }) => {
          this.gamesList = data.response
          this.loading = false
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
    } catch(e) {
      console.log("erro: ", e)
    }
  },
 computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Novo Jogo' : 'Editar Jogo'
    },
  },

  watch: {
    dialog (val) {
      val || this.close()
    },
    dialogDelete (val) {
      val || this.closeDelete()
    },
  },
  methods: {
    formattedDate (data) {
      return moment(data).format('llll');
    },
    editItem (item) {
      try {
        this.editedIndex = 1
        let token = Cookies.get('jwt-token')   
        const config = {
            headers: {
              Authorization: 'Bearer '+ token
              },
              params: {
                dateTime: item
              }
        };
        this.loading = true
        this.$axios
          .get(`game/list/`, config)
          .then(({ data }) => {
            this.editedItem[0] = {
              data_hora: data.response[0].data_hora,
              old_data_hora: data.response[0].data_hora,
              valor: data.response[0].valor,
              descricao: data.response[0].descricao,
              desconto: data.response[0].desconto,
              cod_atendimento: data.response[0].cod_atendimento
            }
            this.dialog = true
            this.loading = false
          })
          .catch(err => {
            console.log('error on GET: ', err)
          })
      } catch(e) {
        console.log(e)
      }
    },

    deleteItem (key) {
      this.dialogDelete = true
      this.toDelte = key
    },

    async deleteItemConfirm (key) {
      let token = Cookies.get('jwt-token')   
      let headers= {
            'Authorization': 'Bearer '+ token
            }
      const data = {
          dateAndTime: key,
        }
      this.loading = true
      console.log(data, headers)

      await this.$axios
        .delete('game/delete', { data: data, headers })
        .then(({data}) => {
          console.log(data)
          if(data.type == 'success') {
            this.updateTable()
            this.closeDelete()
          }
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
    },

    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem[0] = {
          data_hora: '',
          valor: '',
          descricao: '',
          cod_atendimento: ''
        } 
        this.editedIndex = -1
      })
    },

    closeDelete () {
      this.dialogDelete = false
    },

    save () {
      if (this.editedIndex > -1) {
        let token = Cookies.get('jwt-token')   
        let headers= {
              'Authorization': 'Bearer '+ token
              }
        const data = {
            oldDateAndTime: this.editedItem[0].old_data_hora,
            newDateAndTime: this.editedItem[0].data_hora,
            price: this.editedItem[0].valor,
            description: this.editedItem[0].descricao,
            discount: this.editedItem[0].desconto,
            attendanceId: this.editedItem[0].cod_atendimento
          }
        this.loading = true
        this.$axios
          .put(`game/update/`, data, {headers})
          .then(({ data }) => {
            console.log(data)
            this.updateTable()
          })
          .catch(err => {
            console.log('error on GET: ', err)
          })
      } else {
         let token = Cookies.get('jwt-token')   
          let headers= {
                'Authorization': 'Bearer '+ token
                }
          const data = {
              dateAndTime: this.editedItem[0].data_hora,
              price: this.editedItem[0].valor,
              description: this.editedItem[0].descricao,
              discount: this.editedItem[0].desconto == '' ? 0.00 :  this.editedItem[0].desconto,
              attendanceId: this.editedItem[0].cod_atendimento
            }
          console.log(data)
          this.loading = true
          this.$axios
            .post(`game/store/`, data, {headers})
            .then(({ data }) => {
              console.log(data)
              this.updateTable()
            })
            .catch(err => {
              console.log('error on GET: ', err)
            })
      }
      this.close()
    },
    async updateTable() {
      try {
        this.gamesList = []
        let token = Cookies.get('jwt-token')   
        const config = {
            headers: {
              Authorization: 'Bearer '+ token
              }
        };
        await this.$axios
          .get(`game/list/`, config)
          .then(({ data }) => {
            this.gamesList = data.response
            this.editedItem[0] = {
              data_hora: '',
              valor: '',
              descricao: '',
              desconto: '',
              cod_atendimento: ''
            }
            this.loading = false
          })
          .catch(err => {
            console.log('error on GET: ', err)
            this.loading = false
          })
      } catch(e) {
        console.log("erro: ", e)
      }
    }
  },
}
</script>

