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
        <a :style="{ textDecoration: 'none', color: 'white' }"><h1>CLIENTES</h1></a>


      </v-col>
    </v-row>
    <loader
      :message="loaderMessage"
      :dialog="loading"
    />
  </v-container>
</template>
<script>
export default {
  middleware: ['authenticated'],
  
  data: () => ({

  })
}
</script>

