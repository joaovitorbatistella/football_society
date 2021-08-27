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
        <h1>ATENDIMENTOS</h1>
      </v-col>
    </v-row>

    <v-row >
      <v-col justify="center" align="center" :style="{
          padding: '50px'
        }"
      >
        <v-data-table
          :headers="attendanceHeaders"
          :items="attendanceList"
          sort-by="calories"
          class="elevation-1"
        >
          <template v-slot:top>
            <v-toolbar
              flat
            >
              <v-spacer></v-spacer>
              <v-dialog
                v-model="dialog"
                width="100%"
                height="100%"
              >
                <template v-slot:activator="{ on, attrs }">
                   <v-btn dark
                    class="mb-2"
                    v-bind="attrs"
                    v-on="on"
                    @click="onStore"
                  >
                    CADASTRAR 
                  </v-btn>
                </template>
                <v-card
                  width="100%"
                  height="100%"
                >
                  <v-card-title>
                    <span class="text-h5">{{ formTitle }}</span>
                  </v-card-title>
      
                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col :style="{
                          borderRight: '1px solid #616159',
                          borderBottom: '1px solid #616159',
                        }">
                          <v-combobox
                            :items="gamesList"
                            hide-selected
                            item-text="data_hora"
                            color="orange darken-1"
                            item-value="data_hora"
                            label="Selecione um jogo"
                            small-chips
                            dense
                            dark
                            clearable
                          />
                        </v-col>
                        <v-col :style="{
                          borderLeft: '1px solid #616159',
                          borderBottom: '1px solid #616159',
                        }">
                        <v-row v-for="i in input" :key="i" :id='"item"+i'>
                          <v-col justify="center" align="center" cols="12" lg="8" md="8">
                            <v-combobox
                              :items="productsList"
                              :id='"productsList"+i'
                              hide-selected
                              item-text="nome"
                              color="orange darken-1"
                              item-value="codigo"
                              label="Selecione um produto "
                              small-chips
                              dense
                              dark
                              clearable
                            />
                          </v-col>
                          <v-col justify="center" align="center" cols="12" lg="2" md="2">
                            <v-text-field
                              :id='"quantity"+i'
                              :model="quantity"
                              color="orange darken-1"
                              type="number"
                              label="Quantidade"
                            ></v-text-field>
                          </v-col>
                          <v-col justify="center" align="center" cols="12" lg="1" md="1">
                            <v-btn
                              color="green darken-1"
                              text
                              @click="input++"
                            >
                              <v-icon
                                medium
                                color="green darken-1"
                              >
                                mdi-plus-circle-outline
                              </v-icon>
                            </v-btn>
                          </v-col>
                          <v-col justify="center" align="center" cols="12" lg="1" md="1">
                            <v-btn
                              v-if="input > 1"
                              color="red darken-1"
                              text
                              @click="input--"
                            >
                              <v-icon
                                medium
                                color="red darken-1"
                              >
                                mdi-minus-circle-outline
                              </v-icon>
                            </v-btn>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col justify="center" align="center" cols="12" lg="5" md="5">
                            <v-text-field
                              color="orange darken-1"
                              type="number"
                              label="Valor Total (R$)"
                            ></v-text-field>
                          </v-col>
                          <v-col justify="center" align="center" cols="12" lg="1" md="1">
                            <v-btn
                              color="yellow darken-1"
                              text
                              @click="input--"
                            >
                              <v-icon
                                medium
                                color="yellow darken-1"
                              >
                                mdi-reload
                              </v-icon>
                            </v-btn>
                          </v-col>
                        </v-row>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col :style="{
                          borderRight: '1px solid #616159',
                        }">
                          <v-combobox
                            :items="customersList"
                            hide-selected
                            item-text="nome"
                            color="orange darken-1"
                            item-value="codigo"
                            label="Selecione um cliente "
                            small-chips
                            dense
                            dark
                            clearable
                          />
                        </v-col>
                        <v-col :style="{
                          borderLeft: '1px solid #616159',
                        }">
                          CAMPOS
                        </v-col>
                      </v-row>
                      <!-- <v-row>
                        <v-col
                          cols="12"
                          sm="6"
                          md="4"
                        >
                          <v-text-field
                            color="orange darken-1"
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
                            color="orange darken-1"
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
                            color="orange darken-1"
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
                            color="orange darken-1"
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
                            color="orange darken-1"
                            v-model="editedItem[0].cod_atendimento"
                            label="Atendimento"
                          ></v-text-field>
                        </v-col>
                      </v-row> -->
                    </v-container>
                  </v-card-text>
      
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                      color="orange darken-1"
                      text
                      @click="close"
                    >
                      Cancel
                    </v-btn>
                    <v-btn
                      color="orange darken-1"
                      text
                      @click="save"
                    >
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
              <v-dialog v-model="dialogDelete" max-width="500px">
                <v-card>
                  <v-card-title class="text-h5">Você deseja excluir este jogo?</v-card-title>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="orange darken-1" text @click="closeDelete">Cancel</v-btn>
                    <v-btn color="orange darken-1" text @click="deleteItemConfirm(toDelte)">OK</v-btn>
                    <v-spacer></v-spacer>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-toolbar>
          </template>
          <template v-slot:item.pago="{ item }">
            <span v-if="item.pago == 'Y'">PAGO</span>
            <span v-if="item.pago == 'N'">- - - - -</span>
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
    input: 1,
    quantity: 0,
    loaderMessage: 'Carregando',
    dialog: false,
    dialogDelete: false,
    attendanceList: [],
    attendanceHeaders: [
      { text: 'Código', value: 'codigo' },
      { text: 'Descrição', value: 'descricao' },
      { text: 'Horário', value: 'data_hora' },
      { text: 'Status', value: 'pago' },
      { text: 'Cliente', value: 'nome_cliente' },
      { text: 'Actions', value: 'actions', sortable: false },
    ],
    customersList: [],
    productsList: [],
    gamesList: [],
    editedIndex: -1,
    editedItem: [
      {
        descricao: '',
        data_hora: '',
        pago: '',
        cod_cliente: ''
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
        .get(`attendance/list/`, config)
        .then(({ data }) => {
          this.attendanceList = data.response
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
      return this.editedIndex === -1 ? 'Novo Atendimento' : 'Editar Atendimento'
    },
  },

  watch: {
    quantity(){
      console.log('fui chamado')
    },
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
    async onStore(){
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
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
      await this.$axios
        .get(`product/list/`, config)
        .then(({ data }) => {
          this.productsList = data.response
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
      await this.$axios
        .get(`customer/list/`, config)
        .then(({ data }) => {
          this.customersList = data.response
          this.loading = false
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
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
          .get(`attendance/list/`, config)
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
      this.loading = true
      await this.$axios
        .delete(`game/delete/${key}`, { headers })
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
          .put(`attendance/update/`, data, {headers})
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
            .post(`attendance/store/`, data, {headers})
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
        this.attendanceList = []
        let token = Cookies.get('jwt-token')   
        const config = {
            headers: {
              Authorization: 'Bearer '+ token
              }
        };
        await this.$axios
          .get(`attendance/list/`, config)
          .then(({ data }) => {
            this.attendanceList = data.response
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

