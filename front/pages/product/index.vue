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
        <h1>PRODUTOS</h1>
      </v-col>
    </v-row>

    <v-row >
      <v-col justify="center" align="center" cols="12" lg="12" md="12" :style="{
          padding: '50px'
        }"
      >
        <v-data-table
          :headers="productsHeaders"
          :items="productsList"
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
                          sm="6"
                          md="6"
                        >
                          <v-text-field
                            v-model="editedItem[0].nome"
                            label="Nome do produto"
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <v-textarea
                            v-model="editedItem[0].descricao"
                            filled
                            label="Descrição do produto"
                            auto-grow
                          ></v-textarea>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <v-text-field
                            v-model="editedItem[0].preco"
                            label="Valor de venda do poduto"
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <v-text-field
                            v-model="editedItem[0].estoque"
                            label="Estoque do produto"
                            type="number"
                          ></v-text-field>
                        </v-col>                        
                      </v-row>
                    </v-container>
                  </v-card-text>
      
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                      color="blue darken-1"
                      text
                      @click="close"
                    >
                      Cancel
                    </v-btn>
                    <v-btn
                      color="blue darken-1"
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
                  <v-card-title class="text-h5">Você deseja excluir este produto?</v-card-title>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                    <v-btn color="blue darken-1" text @click="deleteItemConfirm(toDelte)">OK</v-btn>
                    <v-spacer></v-spacer>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-toolbar>
          </template>
          <template v-slot:item.preco="{ item }">
            <span>R$ {{ item.preco }}</span>
          </template>
          <template v-slot:item.estoque="{ item }">
            <span>{{ item.estoque }} un</span>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-icon
              small
              class="mr-2"
              @click="editItem(item.cod_produto)"
            >
              mdi-pencil
            </v-icon>
            <v-icon
              small
              @click="deleteItem(item.cod_produto)"
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
    editingProduct: null,
    productsList: [],
    productsHeaders: [
      { text: 'Código', value: 'cod_produto' },
      { text: 'Nome', value: 'nome' },
      { text: 'Descrição', value: 'descricao' },
      { text: 'Preço', value: 'preco' },
      { text: 'Estoque', value: 'estoque' },
      { text: 'Actions', value: 'actions', sortable: false },
    ],
    editedIndex: -1,
    editedItem: [
      {
        nome: '',
        descricao: '',
        preco: '',
        estoque: ''
      }
    ],
    cityList: []
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
      return this.editedIndex === -1 ? 'Novo Produto' : 'Editar Produto'
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
        this.editingProduct = key
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
        
        this.loading = true
        await this.$axios
          .get(`product/list`, configCustomer)
          .then(({ data }) => {
            this.editedItem[0] = {
              nome: data.response[0].nome,
              descricao: data.response[0].descricao,
              preco: data.response[0].preco,
              estoque: data.response[0].estoque,
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
        .delete(`product/delete/${key}`, {headers})
        .then(({data}) => {
          console.log(data)
          if(data.type == 'success') {
            this.updateTable()
            this.closeDelete()
          } else {
            alert('Este produto está associado à atendimentos, corrija isto e tente novamente!')
            this.closeDelete()
            return;
          }
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
    },

    close () {
      this.dialog = false
      this.loading = false
      this.$nextTick(() => {
        this.editedItem[0] = {
          nome: '',
          dt_nascimento: '',
          telefone: '',
          email: '',
          logradouro: '',
          sexo: '',
          cpf: '',
          cidade: null
        } 
        this.editedIndex = -1
        this.editingProduct = null
      })
    },

    closeDelete () {
      this.dialogDelete = false
      this.loading = false
    },

    save () {
      if (this.editedIndex > -1) {
        let token = Cookies.get('jwt-token')   
        let headers= {
              'Authorization': 'Bearer '+ token
              }
        const data = {
            name: this.editedItem[0].nome,
            description: this.editedItem[0].descricao,
            price: this.editedItem[0].preco,
            inventory: this.editedItem[0].estoque
          }
        this.loading = true
        this.$axios
          .put(`product/update/${this.editingProduct}`, data, {headers})
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
            name: this.editedItem[0].nome,
            description: this.editedItem[0].descricao,
            price: this.editedItem[0].preco,
            inventory: this.editedItem[0].estoque
          }
          console.log(data)
          this.loading = true
          this.$axios
            .post(`product/store/`, data, {headers})
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
        this.productList = []
        let token = Cookies.get('jwt-token')   
        const config = {
            headers: {
              Authorization: 'Bearer '+ token
              }
        };
        this.loading = true
        await this.$axios
          .get(`product/list`, config)
          .then(({ data }) => {
            this.productsList = data.response
          })
          .catch(err => {
            console.log('error on GET: ', err)
          })
        
        const configWithoutId = {
            headers: {
              Authorization: 'Bearer '+ token
              }
          };
        this.close()
      } catch(e) {
        console.log("erro: ", e)
      }
    }
  },
}
</script>

