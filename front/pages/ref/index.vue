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
        <h1>Valores de Referência</h1>
      </v-col>
    </v-row>

    <v-row >
      <v-col justify="center" align="center" cols="12" lg="12" md="12" :style="{
          padding: '50px'
        }"
      >
        <v-data-table
          id="valorRefTable"
          :headers="valorRefHeaders"
          :items="valorRefList"
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
                        <v-col
                          cols="12"
                          sm="2"
                          md="2"
                        >
                          <v-text-field
                            color= "lime accent-3"
                            v-model="editedItem[0].valor"
                            type='number'
                            label="Valor"
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="5"
                          md="5"
                        >
                          <v-menu
                            ref="dateStartMenu"
                            color="lime accent-3"
                            v-model="menu"
                            :close-on-content-click="false"
                            :return-value.sync="editedItem[0].inicio"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                          >
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field
                                :style="{
                                  maxWidth: '140px',
                                  marginRight: '8px'
                                }"
                                color="lime accent-3"
                                v-model="editedItem[0].inicio"
                                label="Picker in menu"
                                prepend-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                dense
                                solo
                              ></v-text-field>
                            </template>
                            <v-date-picker
                              color="lime accent-3"
                              v-model="editedItem[0].inicio"
                              no-title
                              scrollable
                            >
                              <v-spacer></v-spacer>
                              <v-btn
                                text
                                color="primary"
                                @click="menu = false"
                              >
                                Cancel
                              </v-btn>
                              <v-btn
                                text
                                color="primary"
                                @click="$refs.dateStartMenu.save(editedItem[0].inicio)"
                              >
                                OK
                              </v-btn>
                            </v-date-picker>
                          </v-menu>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="5"
                          md="5"
                        >
                          <v-menu
                            ref="dateEndMenu"
                            color="lime accent-3"
                            v-model="menu2"
                            :close-on-content-click="false"
                            :return-value.sync="editedItem[0].fim"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                          >
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field
                                :style="{
                                  maxWidth: '140px',
                                  marginRight: '8px'
                                }"
                                color="lime accent-3"
                                v-model="editedItem[0].fim"
                                label="Picker in menu"
                                prepend-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                dense
                                solo
                              ></v-text-field>
                            </template>
                            <v-date-picker
                              color="lime accent-3"
                              v-model="editedItem[0].fim"
                              no-title
                              scrollable
                            >
                              <v-spacer></v-spacer>
                              <v-btn
                                text
                                color="primary"
                                @click="menu2 = false"
                              >
                                Cancel
                              </v-btn>
                              <v-btn
                                text
                                color="primary"
                                @click="$refs.dateEndMenu.save(editedItem[0].fim)"
                              >
                                OK
                              </v-btn>
                            </v-date-picker>
                          </v-menu>
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
            </v-toolbar>
          </template>
          <template v-slot:item.valor="{ item }">
            <span>R$ {{ item.valor }}</span>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-icon
              small
              class="mr-2"
              @click="editItem(item.codigo)"
            >
              mdi-pencil
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
    menu: false,
    menu2: false,
    dialog: false,
    dialogDelete: false,
    editingValorRef: null,
    valorRefList: [],
    valorRefHeaders: [
      { text: 'Código', value: 'codigo' },
      { text: 'Valor', value: 'valor' },
      { text: 'Início', value: 'inicio' },
      { text: 'Fim', value: 'fim' },
      { text: 'Ações', value: 'actions', sortable: false },
    ],
    editedIndex: -1,
    editedItem: [
      {
        valor: '',
        inicio: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        fim: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
      }
    ],
  }),
  async beforeMount() {
    try {
      this.updateTable()
    } catch(e) {
      console.log("erro: ", e)
    }
  },
 computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'Novo Valor de Referência' : 'Editar Valor de Referência'
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
      return moment(data).format('L');
    },
    async editItem (key) {
      try {
        this.loading = true
        this.editingValorRef = key
        this.editedIndex = 1
        let token = Cookies.get('jwt-token')   
        const configCustomer = {
          headers: {
            Authorization: 'Bearer '+ token
            },
            params: {
              id: parseInt(key)
            }
        }
        
        await this.$axios
          .get(`ref/list`, configCustomer)
          .then(({ data }) => {
            this.editedItem[0] = {
              valor: data.response[0].valor,
              inicio: data.response[0].inicio,
              fim: data.response[0].fim,
            }
          })
          .catch(err => {
            console.log('error on GET: ', err)
          })
        this.dialog = true
        this.loading = false
        console.log(this.editedItem[0])
      } catch(e) {
        console.log(e)
      }
    },

    close () {
      this.dialog = false
      this.loading = false
      this.$nextTick(() => {
        this.editedItem[0] = {
          valor: null,
          inicio: '',
          fim: '',
        } 
        this.editedIndex = -1
        this.editingValorRef = null
      })
    },

    async save () {
      if (this.editedIndex > -1) {
        this.loading = true
        let token = Cookies.get('jwt-token')   
        let headers= {
              'Authorization': 'Bearer '+ token
              }
        const data = {
          price: this.editedItem[0].valor,
          start: this.editedItem[0].inicio,
          end: this.editedItem[0].fim,
        }
        
        await this.$axios
          .put(`ref/update/${this.editingValorRef}`, data, {headers})
          .then()
          .catch(err => {
            console.log('error on GET: ', err)
          })
          this.updateTable()
      }
    },
    async updateTable() {
        this.loading = true
        this.valorRefList = []
        let token = Cookies.get('jwt-token')   
        const config = {
          headers: {
            Authorization: 'Bearer '+ token
            }
        };
        await this.$axios
          .get(`ref/list`, config)
          .then(({ data }) => {
            this.valorRefList = data.response
          })
          .catch(err => {
            console.log('error on GET: ', err)
          })
        this.close()

    }
  },
}
</script>

