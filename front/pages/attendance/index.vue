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
                        <v-col v-if="editedIndex > -1" :style="{
                          borderRight: '1px solid #a5d6a7',
                          borderBottom: '1px solid #a5d6a7',
                        }">
                          <v-switch
                            v-model="newGame"
                            color="green lighten-3"
                            label="Alterar jogo"
                          ></v-switch>
                          <v-row v-if="newGameCtrl == true">
                            <v-col justify="center" align="center" cols="12" lg="4" md="4">
                              <v-dialog
                                ref="gameDateDialog"
                                v-model="gameDateModal"
                                :return-value.sync="gameEditedItem[0].date"
                                persistent
                                width="290px"
                              >
                                <template v-slot:activator="{ on, attrs }">
                                  <v-text-field
                                    v-model="gameEditedItem[0].date"
                                    label="Picker in dialog"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                  ></v-text-field>
                                </template>
                                <v-date-picker
                                  v-model="gameEditedItem[0].date"
                                  scrollable
                                >
                                  <v-spacer></v-spacer>
                                  <v-btn
                                    text
                                    color="primary"
                                    @click="gameDateModal = false"
                                  >
                                    Cancel
                                  </v-btn>
                                  <v-btn
                                    text
                                    color="primary"
                                    @click="$refs.gameDateDialog.save(gameEditedItem[0].date); gameTimeModal = true"
                                  >
                                    OK
                                  </v-btn>
                                </v-date-picker>
                              </v-dialog>


                              <v-dialog
                                ref="gameTimeDialog"
                                v-model="gameTimeModal"
                                :return-value.sync="gameEditedItem[0].hour"
                                persistent
                                width="290px"
                              >
                                <template v-slot:activator="{ on, attrs }">
                                  <v-text-field
                                    v-model="gameEditedItem[0].hour"
                                    label="Picker in dialog"
                                    prepend-icon="mdi-clock-time-four-outline"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                  ></v-text-field>
                                </template>
                                <v-time-picker
                                  v-if="gameTimeModal"
                                  v-model="gameEditedItem[0].hour"
                                  full-width
                                >
                                  <v-spacer></v-spacer>
                                  <v-btn
                                    text
                                    color="primary"
                                    @click="gameTimeModal = false"
                                  >
                                    Cancel
                                  </v-btn>
                                  <v-btn
                                    text
                                    color="primary"
                                    @click="$refs.gameTimeDialog.save(gameEditedItem[0].hour)"
                                  >
                                    OK
                                  </v-btn>
                                </v-time-picker>
                              </v-dialog>
                            </v-col>
                            <v-col justify="center" align="center" cols="12" lg="8" md="8">
                              <v-textarea
                                v-model="gameEditedItem[0].description"
                                filled
                                label="Descrição..."
                                auto-grow
                              ></v-textarea>
                            </v-col>
                          </v-row>
                          <v-row v-row v-if="newGameCtrl == true">
                            <v-col justify="center" align="center" cols="12" lg="6" md="6">
                              <v-text-field
                                v-model="gameEditedItem[0].price"
                                color="green lighten-3"
                                label="Valor do tempo"
                                type="number"
                                required
                              ></v-text-field>
                            </v-col>
                            <v-col justify="center" align="center" cols="12" lg="6" md="6">
                              <v-text-field
                                v-model="gameEditedItem[0].discount"
                                color="green lighten-3"
                                label="Desconto do valor do tempo"
                                type="number"
                                required
                              ></v-text-field>
                            </v-col>
                          </v-row>
                        </v-col>
                        
                        <v-col v-if="editedIndex <= -1" :style="{
                          borderRight: '1px solid #a5d6a7',
                          borderBottom: '1px solid #a5d6a7',
                        }">
                          <v-switch
                            v-model="newGame"
                            color="green lighten-3"
                            label="Novo jogo"
                          ></v-switch>
                          <v-row v-if="newGameCtrl == true">
                            <v-col justify="center" align="center" cols="12" lg="4" md="4">
                              <v-dialog
                                ref="gameDateDialog"
                                v-model="gameDateModal"
                                :return-value.sync="gameEditedItem[0].date"
                                persistent
                                width="290px"
                              >
                                <template v-slot:activator="{ on, attrs }">
                                  <v-text-field
                                    v-model="gameEditedItem[0].date"
                                    label="Picker in dialog"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                  ></v-text-field>
                                </template>
                                <v-date-picker
                                  v-model="gameEditedItem[0].date"
                                  scrollable
                                >
                                  <v-spacer></v-spacer>
                                  <v-btn
                                    text
                                    color="primary"
                                    @click="gameDateModal = false"
                                  >
                                    Cancel
                                  </v-btn>
                                  <v-btn
                                    text
                                    color="primary"
                                    @click="$refs.gameDateDialog.save(gameEditedItem[0].date); gameTimeModal = true"
                                  >
                                    OK
                                  </v-btn>
                                </v-date-picker>
                              </v-dialog>


                              <v-dialog
                                ref="gameTimeDialog"
                                v-model="gameTimeModal"
                                :return-value.sync="gameEditedItem[0].hour"
                                persistent
                                width="290px"
                              >
                                <template v-slot:activator="{ on, attrs }">
                                  <v-text-field
                                    v-model="gameEditedItem[0].hour"
                                    label="Picker in dialog"
                                    prepend-icon="mdi-clock-time-four-outline"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                  ></v-text-field>
                                </template>
                                <v-time-picker
                                  v-if="gameTimeModal"
                                  v-model="gameEditedItem[0].hour"
                                  full-width
                                >
                                  <v-spacer></v-spacer>
                                  <v-btn
                                    text
                                    color="primary"
                                    @click="gameTimeModal = false"
                                  >
                                    Cancel
                                  </v-btn>
                                  <v-btn
                                    text
                                    color="primary"
                                    @click="$refs.gameTimeDialog.save(gameEditedItem[0].hour)"
                                  >
                                    OK
                                  </v-btn>
                                </v-time-picker>
                              </v-dialog>
                            </v-col>
                            <v-col justify="center" align="center" cols="12" lg="8" md="8">
                              <v-textarea
                                v-model="gameEditedItem[0].description"
                                filled
                                label="Descrição..."
                                auto-grow
                              ></v-textarea>
                            </v-col>
                          </v-row>
                          <v-row v-row v-if="newGameCtrl == true">
                            <v-col justify="center" align="center" cols="12" lg="6" md="6">
                              <v-text-field
                                v-model="gameEditedItem[0].price"
                                color="green lighten-3"
                                label="Valor do tempo"
                                type="number"
                                required
                              ></v-text-field>
                            </v-col>
                            <v-col justify="center" align="center" cols="12" lg="6" md="6">
                              <v-text-field
                                v-model="gameEditedItem[0].discount"
                                color="green lighten-3"
                                label="Desconto do valor do tempo"
                                type="number"
                                required
                              ></v-text-field>
                            </v-col>
                          </v-row>
                        </v-col>
                        <v-col :style="{
                          borderLeft: '1px solid #a5d6a7',
                          borderBottom: '1px solid #a5d6a7',
                        }">
                        <v-row v-if="buyComplete == true" :style="{
                          height: '100%',
                          width: '100%',
                          backgroundColor: '#00000055'
                        }">
                          <v-col justify="center" align="center" cols="12" lg="1" md="1">
                            
                          </v-col>
                          <v-col justify="center" align="center" cols="12" lg="10" md="10">
                            <v-text>PRODUTOS INSERIDOS</v-text>
                          </v-col>
                          <v-col justify="center" align="center" cols="12" lg="1" md="1">
                            
                          </v-col>
                        </v-row>
                        <v-row v-if="buyComplete ==false">
                          <v-col justify="center" align="center" cols="12" lg="10" md="10">
                              <v-data-table
                                v-model="selected"
                                checkbox-color="a5d6a7"
                                :headers="productsHeaders"
                                :items="productsList"
                                item-key="codigo"
                                show-select
                                color="green lighten-3"
                                class="elevation-1"
                                :single-select="false"
                              >
                              </v-data-table>
                          </v-col>
                          <v-col justify="center" align="center" cols="12" lg="2" md="2">
                            <v-btn
                              color="blue darken-1"
                              text
                              @click="getSelectedsProducts"
                            >
                              <v-icon
                                medium
                                color="blue darken-2"
                              >
                                mdi-step-forward-2
                              </v-icon>
                            </v-btn>
                            <v-btn
                              color="green darken-1"
                              text
                              @click="confirmProducts"
                            >
                              <v-icon
                                medium
                                color="green darken-2"
                              >
                                mdi-check-all
                              </v-icon>
                            </v-btn>
                          </v-col>
                        </v-row>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col :style="{
                          borderRight: '1px solid #a5d6a7',
                        }">
                          
                        </v-col>
                        <v-col :style="{
                          borderLeft: '1px solid #a5d6a7',
                        }">
                          <v-row>
                            <v-col justify="center" align="center" cols="12" lg="12" md="12">
                              <v-combobox
                                v-model="editedItem[0].customer"
                                :items="customersList"
                                hide-selected
                                item-text="nome"
                                color="green lighten-3"
                                item-value="codigo"
                                return-object
                                label="Selecione um cliente "
                                small-chips
                                dense
                                dark
                                clearable
                              />
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col justify="center" align="center" cols="12" lg="8" md="8">
                              <v-textarea
                                v-model="editedItem[0].description"
                                filled
                                label="Descrição..."
                                auto-grow
                              ></v-textarea>
                            </v-col>
                            <v-col justify="center" align="center" cols="12" lg="1" md="1">
                              <v-checkbox
                                v-model="editedItem[0].payed"
                                label="Pago?"
                              ></v-checkbox>
                            </v-col>
                          </v-row>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>
      
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                      color="green lighten-3"
                      text
                      @click="close"
                    >
                      Cancel
                    </v-btn>
                    <v-btn
                      color="green lighten-3"
                      text
                      @click="save"
                    >
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
              <v-dialog
                v-model="productsDialog"
                width="800px"
                height="100%"
              >
                <!-- PRODUCTS -->
                <v-card
                  width="800px"
                  height="100%"
                >
                  <v-card-title>
                    <span class="text-h5"> CARRINHO DE COMPRAS </span>
                  </v-card-title>

                  <v-card-text>
                    <v-container>
                      <v-layout v-for="(product, index) in selected" :key="product.codigo">
                        <v-row>
                          <v-col justify="center" align="center" cols="12" lg="8" md="8">
                            <v-text-field
                              :value="product.nome"
                              :id="index"
                              color="green lighten-3"
                              label="Nome do produto"
                              required
                            ></v-text-field>
                          </v-col>
                          <v-col justify="center" align="center" cols="12" lg="2" md="2">
                            <v-text-field
                              :id="index"
                              :value="product.preco"
                              color="green lighten-3"
                              label="Preço"
                              required
                            ></v-text-field>
                          </v-col>
                          <!-- <v-col v-if="editedIndex == -1" justify="center" align="center" cols="12" lg="2" md="2">
                            <v-text-field
                              v-model="quantity[index]"
                              :value="0"
                              :id="index"
                              type="number"
                              color="green lighten-3"
                              label="Quantidade"
                              required
                            ></v-text-field>
                          </v-col> -->
                          <v-col justify="center" align="center" cols="12" lg="2" md="2">
                            <v-text-field
                              v-model="product.quantity"
                              :value="0"
                              :id="index"
                              type="number"
                              color="green lighten-3"
                              label="Quantidade"
                              required
                            ></v-text-field>
                          </v-col>
                        </v-row>
                      </v-layout>
                    </v-container>
                  </v-card-text>
          
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                      color="green lighten-3"
                      text
                      @click="closeProducts"
                    >
                      Cancel
                    </v-btn>
                    <v-btn
                      color="green lighten-3"
                      text
                      @click="buyProducts"
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
                    <v-btn color="green lighten-3" text @click="closeDelete">Cancel</v-btn>
                    <v-btn color="green lighten-3" text @click="deleteItemConfirm(toDelte)">OK</v-btn>
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
              @click="editItem(item.codigo)"
            >
              mdi-pencil
            </v-icon>
            <v-icon
              small
              @click="deleteItem(item.codigo)"
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
import VCalendar from 'v-calendar';
moment.locale('pt-br')

export default {
  middleware: ['authenticated'],
  components: {
    Card,
    VCalendar
  },
  data: () => ({
    loading: false,
    input: 1,
    quantity: [],
    newGameCtrl: false,
    newGame: false,
    dialog: false,
    attendanceEditing: null,
    productToDeleteFromAttendance: null,
    insertedAttendanceId: null,
    newProductInAttendance: false,
    deleteProductInAttendance: false,
    gameHour: '',
    gameDateModal: false,
    gameTime: null,
    gameTimeModal: false,
    productDateModal: false,
    productTime: null,
    productTimeModal: false,
    loaderMessage: 'Carregando',
    buyComplete: false,
    productsDialog: false,
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
    productData: {
      name: '',
      description: '',
      price: null,
      inventory: null,
    },
    selected: [],
    productsHeaders: [
      { text: 'Código', value: 'codigo' },
      { text: 'Nome', value: 'nome' },
      { text: 'Preço', value: 'preco' },
      { text: 'Estoque', value: 'estoque' },
    ],
    editedIndex: -1,
    editedItem: [
      {
        description: '',
        payed: false,
        customer: null
      }
    ],
    gameEditedItem: [
      {
        date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        hour: null,
        description: '',
        price: null,
        discount: null,
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
          
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
      this.loading = false
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
    newGame(n) {
      if(n == true ){
        this.newGameCtrl = true
      } else if(n == false)
        this.newGameCtrl = false
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
        .get(`ref/list/`, config)
        .then(({ data }) => {
          this.gameEditedItem[0].price = data.response[0].valor
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
    getSelectedsProducts() {
      if(this.selected.length > 0) {
        this.productsDialog = true
      } else {
        alert('Selecione algum produto')
      }
    },
    confirmProducts() {
      this.buyComplete = true
    },
    async editItem (id) {
      this.loading = true
      this.buyComplete = false
      this.editedIndex = 1
      this.attendanceEditing = id
      let token = Cookies.get('jwt-token')

      const configCustomer = {
        headers: {
          Authorization: 'Bearer '+ token
          },
      }

      await this.$axios
        .get(`customer/list/`, configCustomer)
        .then(({ data }) => {
          this.customersList = data.response
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })

        const config = {
          headers: {
            Authorization: 'Bearer '+ token
            },
            params: {
              id: id
            }
        }

        await this.$axios
          .get(`attendance/list/`, config)
          .then(({ data }) => {
            console.log(data)
            this.editedItem[0] = {
              description: data.response[0].descricao,
              dateTime: data.response[0].data_hora,
              payed: data.response[0].pago == 'Y' ? true : false,
              customer: {
                codigo: data.response[0].cod_cliente,
                nome: data.response[0].nome
              }
            }
            console.log(this.editedItem[0])
            this.gameHour = data.response[0].horario_jogo
          })
          .catch(err => {
            console.log('error on GET: ', err)
          })
          console.log("GAME HOUR", this.gameHour)
        if(this.gameHour != null){
          const configGame = {
            headers: {
              Authorization: 'Bearer '+ token
              },
              params: {
                dateTime: this.gameHour
              }
          }
          await this.$axios
            .get(`game/list/`, configGame)
            .then(({ data }) => {
              let dateTime = data.response[0].data_hora.split(' ')
              this.gameEditedItem[0] = {
                date: dateTime[0],
                oldDate: dateTime[0],
                hour: dateTime[1],
                oldHour: dateTime[1],
                description: data.response[0].descricao,
                price: data.response[0].valor,
                discount: data.response[0].desconto,
              }
              this.newGameCtrl = true
            })
            .catch(err => {
              console.log('error on GET: ', err)
            })
        }

        const configProductAtt = {
          headers: {
            Authorization: 'Bearer '+ token
            },
            params: {
              attendanceId: id
            }
        };
        await this.$axios
            .get(`productattendance/list/`, configProductAtt)
            .then(({ data }) => {
              console.log("before", this.selected)
              if(data.type === "success"){
                 this.selected.length = 0
                 this.selected = []
                 console.log("antes do for", data.response)
                for(var i=0; i < data.response.length; i++) {
                  this.selected[i] = {
                    codigo: data.response[i].cod_produto,
                    nome: data.response[i].nome,
                    preco:  data.response[i].valor_unitario,
                    quantity: data.response[i].quantidade,
                    oldQuantity: data.response[i].quantidade,
                    estoque: data.response[i].estoque
                  }
                }
                console.log("after", this.selected)
              }
            })
            .catch(err => {
              console.log('error on GET: ', err)
            })

            console.log(this.selected)

        const configProduct = {
          headers: {
            Authorization: 'Bearer '+ token
            }
        }
        await this.$axios
          .get(`product/list/`, configProduct)
          .then(({ data }) => {
            this.productsList = data.response
          })
          .catch(err => {
            console.log('error on GET: ', err)
          })
        this.loading = false
        this.dialog = true
    },
    teste() {

    },
    buyProducts() {
      console.log(this.selected[0])
      for(var i=0; i < this.selected.length; i++) {
        if(this.selected[i].hasOwnProperty('oldQuantity') == false){
          this.selected[i].oldQuantity = 0
        }
        if(this.selected[i].quantity > 0) {     
          if(this.selected[i].quantity > this.selected[i].estoque) {
            alert('Estoque insuficiente, o estoque do produto: '+ this.selected[i].nome+' é: '+ this.selected[i].estoque)
            return;
          }
        } else {
          alert('Informe a quantidade')
        }
      } 
      this.closeProducts()
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
    closeProducts(){
      this.productsDialog = false
    },
    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem[0] = {
          description: '',
          dateTime: '',
          payed: '',
          customerId: null
        } 
        this.editedIndex = -1
      })
    },

    closeDelete () {
      this.dialogDelete = false
    },

    async save () {
      console.log(this.editedIndex)
      if (this.editedIndex > -1) {
        let token = Cookies.get('jwt-token')   
        let headers= {
          'Authorization': 'Bearer '+ token
        }

        const attendanceData = {
          description: this.editedItem[0].description,
          payed: this.editedItem[0].payed == true ? 'Y' : 'N',
          customerId: this.editedItem[0].customer.codigo
        }
        this.loading = true
        await this.$axios
          .put(`attendance/update/${this.attendanceEditing}`, attendanceData, {headers})
          .then()
          .catch(err => {
            console.log('error on GET: ', err)
          })

        if(this.newGameCtrl == true) {
          console.log('dentro do id do game')
            const gameData = {
              newDateAndTime: this.gameEditedItem[0].date + ' ' + this.gameEditedItem[0].hour+':00',
              oldDateAndTime: this.gameEditedItem[0].oldDate + ' ' + this.gameEditedItem[0].oldHour,
              price: this.gameEditedItem[0].price,
              description: this.gameEditedItem[0].description,
              discount: this.gameEditedItem[0].discount == '' ? 0.00 : parseFloat(this.gameEditedItem[0].discount),
              attendanceId: this.attendanceEditing
            }
            console.log('dentro do id do game', gameData)
            await this.$axios
              .put(`game/update`, gameData, {headers})
              .then()
              .catch(err => {
                console.log('error on GET: ', err)
              })
        }
       

        if(this.selected.length > 0){
          console.log("selected b", this.selected)
          for(var i=0; i < this.selected.length; i++){
            const cfgP = {
              headers: {
                Authorization: 'Bearer '+ token
                },
                params: {
                  attendanceId: this.attendanceEditing,
                }
            }
            await this.$axios
            .get(`productattendance/list`, cfgP)
            .then(({data}) => {
              console.log("productattendance/list", data)
              for(var m=0; m < data.response.length; m++){
                if(data.response[m].cod_produto != this.selected[i].codigo){
                  this.productToDeleteFromAttendance = data.response[m].cod_produto
                  this.deleteProductInAttendance = true
                }
              }
            })

            if(this.deleteProductInAttendance == true) {
              const cfgA = {
                headers: {
                  Authorization: 'Bearer '+ token
                  },
                  data: {
                    attendanceId: this.attendanceEditing,
                    productId: this.productToDeleteFromAttendance
                  }
              }
              console.log(cfgA)
              await this.$axios
                .delete(`productattendance/delete`, cfgA)
                .then(({data}) => {
                  console.log("delete pa", data)
                })
                .catch(err => {
                  console.log('error on GET: ', err)
                })
            }

             const cfg = {
              headers: {
                Authorization: 'Bearer '+ token
                },
                params: {
                  attendanceId: this.attendanceEditing,
                  productId: this.selected[i].codigo
                }
            }
            await this.$axios
            .get(`productattendance/list`, cfg)
            .then(({data}) => {
              if(data.type == 'error'){
                this.newProductInAttendance = true
              }
            })

            

            if(this.newProductInAttendance == true){
              const productAttndDataU = {
                attendanceId: this.attendanceEditing,
                productId: this.selected[i].codigo,
                quantity: this.selected[i].quantity,
                fullPrice: this.selected[i].preco * this.selected[i].quantity,
                unityPrice: this.selected[i].preco
              }
            
              await this.$axios
                .post(`productattendance/store/`, productAttndDataU, {headers})
                .then(pad => {
                  console.log("PAD", pad)
                  if(pad.data.type == 'success') {
                    console.log("-> prox. list/id", this.selected[i])
                  }
                })
                .catch(err => {
                  console.log('error on GET: ', err)
                })
            }

          const productAttndData = {
            attendanceId: this.attendanceEditing,
            productId: this.selected[i].codigo,
            quantity: parseInt(this.selected[i].quantity),
            fullPrice: parseFloat(this.selected[i].preco) * parseInt(this.selected[i].quantity),
            unityPrice: parseFloat(this.selected[i].preco)
          }
          console.log("productAttndData", productAttndData)
          await this.$axios
            .put(`productattendance/update`, productAttndData, {headers})
            .then(pad => {
              console.log("PAD", pad)
              if(pad.data.type == 'success') {
                console.log("-> prox. list/id", this.selected[i])
              }
            })
            .catch(err => {
              console.log('error on GET: ', err)
            })
          await this.$axios
            .get(`product/list/${this.selected[i].codigo}`, {headers})
            .then(pd => {
              console.log("PD",pd)
              this.productData = {
                name: pd.data.response.nome,
                description: pd.data.response.descricao,
                price: pd.data.response.preco,
                inventory: this.selected[i].oldQuantity < this.selected[i].quantity ? pd.data.response.estoque - (this.selected[i].quantity - this.selected[i].oldQuantity) : pd.data.response.estoque + (this.selected[i].oldQuantity - this.selected[i].quantity)
              }
              console.log("q1", this.selected[i])
            })
            .catch(err => {
              console.log('error on GET: ', err)
              this.loading = false
            })
            console.log("this.productData", this.productData)
          await this.$axios
            .put(`product/update/${this.selected[i].codigo}`, this.productData, {headers})
            .then()
            .catch(err => {
              console.log('error on GET: ', err)
              this.loading = false
            })
          }     
        }
        console.log("selected", this.selected)
        this.newProductInAttendance = false
        this.deleteProductInAttendance = false
        this.productToDeleteFromAttendance = null
        await this.updateTable()
        
      } else {
        let token = Cookies.get('jwt-token')   
        let headers= {
          'Authorization': 'Bearer '+ token
        }

        const attendanceData = {
            description: this.editedItem[0].description,
            payed: this.editedItem[0].payed == true ? 'Y' : 'N',
            customerId: this.editedItem[0].customer.codigo
          }
        this.loading = true
        await this.$axios
          .post(`attendance/store/`, attendanceData, {headers})
          .then(( {data} ) => {
            this.insertedAttendanceId = data.response.insertedId
          })
          .catch(err => {
            console.log('error on GET: ', err)
          })

        if(this.newGameCtrl == true) {
            const gameData = {
              dateAndTime: this.gameEditedItem[0].date + ' ' + this.gameEditedItem[0].hour+':00',
              price: this.gameEditedItem[0].price,
              description: this.gameEditedItem[0].description,
              discount: this.gameEditedItem[0].discount == '' ? 0.00 :  this.gameEditedItem[0].discount,
              attendanceId: this.insertedAttendanceId
            }
            await this.$axios
              .post(`game/store/`, gameData, {headers})
              .then(({ data }) => {
                console.log(data)
              })
              .catch(err => {
                console.log('error on GET: ', err)
              })
        }

        if(this.selected.length > 0){
          console.log("selected b", this.selected)
          for(var i=0; i < this.selected.length; i++){
            console.log("for")
            const productAttndData = {
              attendanceId: this.insertedAttendanceId,
              productId: this.selected[i].codigo,
              quantity: this.selected[i].quantity,
              fullPrice: this.selected[i].preco * this.selected[i].quantity,
              unityPrice: this.selected[i].preco
            }
          console.log("productAttndData", productAttndData)
          console.log("tem que estar vazio", this.productData)
        
          await this.$axios
            .post(`productattendance/store/`, productAttndData, {headers})
            .then(pad => {
              console.log("PAD", pad)
              if(pad.data.type == 'success') {
                console.log("-> prox. list/id", this.selected[i])
              }
            })
            .catch(err => {
              console.log('error on GET: ', err)
            })
          await this.$axios
            .get(`product/list/${this.selected[i].codigo}`, {headers})
            .then(pd => {
              console.log("PD",pd)
              this.productData = {
                name: pd.data.response.nome,
                description: pd.data.response.descricao,
                price: pd.data.response.preco,
                inventory: pd.data.response.estoque - this.selected[i].quantity,
              }
              console.log("product Data -> prox. update", this.productData)
            })
            .catch(err => {
              console.log('error on GET: ', err)
              this.loading = false
            })
          await this.$axios
            .put(`product/update/${this.selected[i].codigo}`, this.productData, {headers})
            .then()
            .catch(err => {
              console.log('error on GET: ', err)
              this.loading = false
            })
          }     
        }
        console.log("selected", this.selected)
        await this.updateTable()
      }
      this.close()
      this.buyComplete = false

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

