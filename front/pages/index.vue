<template>
  <v-row justify="center" align="center">
      <v-img
        max-width="500"
        :src="require('~/assets/img/logo_society.png')"
      ></v-img>
  </v-row>
</template>

<script>
export default {
  middleware: ['authenticated'],
  
  data: () => ({

  })
}
</script>

