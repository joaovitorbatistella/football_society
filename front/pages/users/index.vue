<template>
  <v-container align-center justify-center :style="{
        backgroundColor: '#323232',
        width: '65%',
        height: '50vh',
        marginTop: '3vh',
        borderRadius: '10px'
      }"
    >
    <v-row justify="center" align="center">
      <v-col justify="center" align="center" cols="12" lg="12" md="12" :style="{ 
        }"
      >
        <h1>USUÁRIOS</h1>
      </v-col>
    </v-row>

    <v-row >
      <v-col justify="center" align="center" cols="12" lg="12" md="12" :style="{
          padding: '50px'
        }"
      >
        <v-data-table
          :headers="usersHeaders"
          :items="userList"
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
                             color= "lime accent-3"
                             v-model="editedItem[0].nome"
                            label="Nome do usuário"
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <v-text-field
                             color= "lime accent-3"
                             v-model="editedItem[0].nome_de_usuario"
                            label="Nome de Usuário"
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <v-text-field
                             color= "lime accent-3"
                             v-model="editedItem[0].senha"
                            label="Senha para o usuário"
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
                  <v-card-title class="text-h5">Você deseja excluir este usuario?</v-card-title>
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
          <template v-slot:item.actions="{ item }">
            <v-icon
              small
              class="mr-2"
              @click="editItem(item.nome_de_usuario)"
            >
              mdi-pencil
            </v-icon>
            <v-icon
              small
              @click="deleteItem(item.nome_de_usuario)"
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
    dataCity: null,
    editingUser: null,
    usersList: [],
    usersHeaders: [
      { text: 'Código', value: 'codigo' },
      { text: 'Nome', value: 'nome' },
      { text: 'Nome de Usuário', value: 'nome_de_usuario' },
      { text: 'Ações', value: 'actions', sortable: false },
    ],
    editedIndex: -1,
    editedItem: [
      {
        nome: '',
        nome_de_usuario: '',
        senha: '',
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
      return this.editedIndex === -1 ? 'Novo Usuário' : 'Editar Usuário'
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
        this.editingUser = key
        this.editedIndex = 1
        let token = Cookies.get('jwt-token')   
        const configUser = {
          headers: {
            Authorization: 'Bearer '+ token
            },
            params: {
              username: parseInt(key)
            }
        }
        
        this.loading = true
        await this.$axios
          .get(`users/list`, configUser)
          .then(({ data }) => {
            console.log("data", data)
            this.editedItem[0] = {
              nome: data.response[0].nome,
              nome_de_usuario: data.response[0].telefone,
            }
          })
          .catch(err => {
            console.log('error on GET: ', err)
          })
  
        this.dialog = true
        this.loading = false
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
        .delete(`users/delete/${key}`, {headers})
        .then(({data}) => {
          console.log(data)
          if(data.type == 'success') {
            this.updateTable()
            this.closeDelete()
          } else {
            alert('Este usuário não pode ser deletado, tente novamente mais tarde!')
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
      this.$nextTick(() => {
        this.editedItem[0] = {
          nome: '',
          nome_de_usuario: '',
          senha: '',
        } 
        this.editedIndex = -1
        this.editingUser = null
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
          nome_de_usuario: this.editedItem[0].nome_de_usuario,
          senha: this.editedItem[0].cidade.senha,
        }
        this.loading = true
        this.$axios
          .put(`users/update/${this.editingUser}`, data, {headers})
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
            nome_de_usuario: this.editedItem[0].nome_de_usuario,
            senha: this.editedItem[0].cidade.senha,
          }
          console.log(data)
          this.loading = true
          this.$axios
            .post(`users/store/`, data, {headers})
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
        this.userList = []
        let token = Cookies.get('jwt-token')   
        const config = {
            headers: {
              Authorization: 'Bearer '+ token
              }
        };
        this.loading = true
        await this.$axios
          .get(`users/list`, config)
          .then(({ data }) => {
            this.userList = data.response
            this.loading = false
          })
          .catch(err => {
            console.log('error on GET: ', err)
          })
        
        this.close()
      } catch(e) {
        console.log("erro: ", e)
      }
    }
  },
}
</script>

