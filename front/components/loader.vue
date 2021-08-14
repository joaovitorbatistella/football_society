<template>
    <v-dialog
      v-model="dialog"
      hide-overlay
      persistent
      width="300"
    >
      <v-card
        color="blue-grey darken-4"
        dark
      >
        <v-card-text>
          Carregando
          <v-progress-linear
            indeterminate
            color="white"
            class="mb-0"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
</template>

<script>
export default {
  name: 'loader',
  props: {
    dialog: {
      type: Boolean,
      default: false
    },
  }
}
</script>
