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
          id="productsTable"
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
                 color= "lime accent-3"
                 v-model="dialog"
                max-width="500px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-btn dark
                    class="mb-2"
                    @click="generatePDF"
                  >
                    Gerar PDF 
                  </v-btn>
                   <v-btn dark
                    class="mb-2"
                    v-bind="attrs"
                    v-on="on"
                  >
                    CADASTRAR 
                  </v-btn>
                  <form>
                    <v-layout :style="{
                        marginTop: '20px'
                      }"
                    >
                      <v-select
                        color="lime accent-3"
                        v-model="searchOptionSelected"
                        :items="searchOptions"
                        placeholder="Selecione o filtro"
                        return-object
                        item-text="value"
                        item-value="id"
                        :style="{
                          maxWidth: '120px',
                          marginRight: '8px'
                        }"
                        dense
                        solo
                      ></v-select>

                      <v-text-field
                        color="lime accent-3"
                        v-model="searchId"
                        v-if="searchOptionSelected.id == 1"
                        :style="{
                          marginRight: '8px',
                          maxWidth: '180px'
                        }"
                        label="Informe um Código"
                        solo
                        dense
                      ></v-text-field>

                      <v-text-field
                        color="lime accent-3"
                        v-model="searchName"
                        v-if="searchOptionSelected.id == 2"
                        :style="{
                          marginRight: '8px',
                          maxWidth: '180px'
                        }"
                        label="Informe um nome, ou parte dele"
                        solo
                        dense
                      ></v-text-field>                      
                      <v-btn
                        class="mr-4"
                        @click="search"
                      >
                        Procurar
                      </v-btn>
                    </v-layout>
                  </form>
                  <v-btn
                    :style="{
                      marginRight: '18px',
                    }"
                    small
                    icon
                    outlined
                    class="mx-1" color="red darken-1" @click.stop="updateTable"
                  >
                    <v-icon>mdi-filter-remove-outline</v-icon>
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
                            label="Nome do produto"
                          ></v-text-field>
                        </v-col>
                        <v-col
                          cols="12"
                          sm="6"
                          md="6"
                        >
                          <v-textarea
                             color= "lime accent-3"
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
                             color= "lime accent-3"
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
                             color= "lime accent-3"
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
                  <v-card-title class="text-h5">Você deseja excluir este produto?</v-card-title>
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
import jsPDF from "jspdf"
import {autoTable} from 'jspdf-autotable'

moment.locale('pt-br')

export default {
  middleware: ['authenticated'],
  components: {
    Card
  },
  data: () => ({
    loading: false,
    loaderMessage: 'Carregando',
    searchOptions: [
      { id: 1, value: 'Cod.' },
      { id: 2, value: 'Nome' },
    ],
    searchOptionSelected: [],
    searchName: '',
    searchId: null,
    dialog: false,
    dialogDelete: false,
    editingProduct: null,
    productsList: [],
    heading: 'Relatorio de Produtos',
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
      this.loading = true
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
    async search() {
      let token = Cookies.get('jwt-token')   
      this.loading = true
      switch (this.searchOptionSelected.id) {
        case 1:
          const configIdproduct = {
            headers: {
              Authorization: 'Bearer '+ token
              },
              params: {
                id: this.searchId
              }
            };
            console.log(configIdproduct)
            await this.$axios
              .get(`product/list/`, configIdproduct)
              .then(({ data }) => {
                this.productsList = []
                this.productsList = data.response   
              })
              .catch(err => {
                console.log('error on GET: ', err)
              })
            this.loading = false
          break;

        case 2:
          const configNameproduct = {
            headers: {
              Authorization: 'Bearer '+ token
              },
              params: {
                name: this.searchName
              }
            };
            console.log(configNameproduct)
            await this.$axios
              .get(`product/list/`, configNameproduct)
              .then(({ data }) => {
                this.productsList = []
                this.productsList = data.response   
              })
              .catch(err => {
                console.log('error on GET: ', err)
              })
            this.loading = false
          break;
      
        default:
          break;
      }
    },
    generatePDF() {
      const columns = [
        { title: "Codigo", dataKey: "cod_produto" },
        { title: "Nome", dataKey: "nome" },
        { title: "Descricao", dataKey: "descricao" },
        { title: "Preco", dataKey: "preco" },
        { title: "Estoque", dataKey: "estoque" },
      ];

      console.log(this.productsList)

      const doc = new jsPDF({
        orientation: "landscape",
        unit: "in",
        format: "a4"
      });
       var imgData = "data:image/jpeg;base64,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"
      doc.addImage(imgData, 'JPEG', 1.06, 1.06, 1.06, 1.06)

      // text is placed using x, y coordinates
      doc.setFontSize(16).text(this.heading, 0.5, 1.0);
      // create a line under heading 
      doc.setLineWidth(0.01).line(0.5, 1.1, 8.0, 1.1);
      // Using autoTable plugin

     
      doc.autoTable({
        columns,
        body: this.productsList,
        margin: { left: 0.5, top: 2 },
        theme: 'striped'
      });
      // Using array of sentences
      // doc
      //   .setFont("helvetica")
      //   .setFontSize(12)
      //   .text(this.moreText, 0.5, 3.5, { align: "left", maxWidth: "7.5" });
      
      // // Creating footer and saving file
      doc
        // .setFont("times")
        // .setFontSize(11)
        // .setFontStyle("italic")
        .setTextColor(0, 0, 0)
        .text(
          "Relatório gerado em: " + this.getNow(),
          0.5,
          doc.internal.pageSize.height - 0.5
        )
        .save(`${this.heading}.pdf`);
    },
    getNow() {
        const today = new Date();
        const date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        const dateTime = moment(date).format('L') +' às '+ time
        return dateTime;
    },
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
      this.loading = true
      let token = Cookies.get('jwt-token')   
      let headers= {
            'Authorization': 'Bearer '+ token
            }
      

      await this.$axios
        .delete(`product/delete/${key}`, {headers})
        .then(({data}) => {
          console.log(data)
          if(data.type != 'success') {
            alert('Este produto está associado à atendimentos, corrija isto e tente novamente!')
            this.closeDelete()
            return;
          }
        })
        .catch(err => {
          console.log('error on GET: ', err)
        })
      this.updateTable()
      this.closeDelete()
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
        this.loading = true
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
        
        this.$axios
          .put(`product/update/${this.editingProduct}`, data, {headers})
          .then(({ data }) => {
            console.log(data)
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
          this.$axios
            .post(`product/store/`, data, {headers})
            .then(({ data }) => {
              console.log(data)
              
            })
            .catch(err => {
              console.log('error on GET: ', err)
            })
      }
      this.updateTable()
      this.close()
    },
    async updateTable() {
        this.searchOptionSelected = []
        this.loading = true
        this.productList = []
        let token = Cookies.get('jwt-token')   
        const config = {
            headers: {
              Authorization: 'Bearer '+ token
              }
        };
        await this.$axios
          .get(`product/list`, config)
          .then(({ data }) => {
            this.productsList = data.response
          })
          .catch(err => {
            console.log('error on GET: ', err)
          })
        this.close()

    }
  },
}
</script>

