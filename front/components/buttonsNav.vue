<template>
  <v-bottom-navigation
      color="blue-grey"
      grow
    >
        <v-btn href="/game">
          <span>Jogos</span>
          <v-icon>mdi-soccer-field</v-icon>
        </v-btn>

        <v-btn href="/attendance">
          <span>Atendimento</span>
          <v-icon>mdi-face-agent</v-icon>
        </v-btn>
        
        <v-btn href="/customer">
          <span>Clientes</span>
          <v-icon>mdi-clipboard-account</v-icon>
        </v-btn>

        <v-btn href="/ref">
          <span>Valor Ref.</span>
          <v-icon>mdi-gift-open-outline</v-icon>
        </v-btn>

        <v-btn href="/dash">
          <span>Dashboard</span>
          <v-icon>mdi-view-dashboard</v-icon>
        </v-btn>

        <v-btn href="/product">
          <span>Produtos</span>
          <v-icon>mdi-package-variant</v-icon>
        </v-btn>

        <v-btn href="/purchase">
          <span>Compra</span>
          <v-icon>mdi-basket-outline</v-icon>
        </v-btn>

        <v-btn href="/provider">
          <span>Fornecedores</span>
          <v-icon>mdi-dolly</v-icon>
        </v-btn>

        <v-btn href="/users">
          <span>Usuários</span>
          <v-icon>mdi-account-check</v-icon>
        </v-btn>
    </v-bottom-navigation>
</template>