const BAMultiComponents = {
  data: () => ({}),
  props: {
    degrade: { type: Boolean, default: true },
    confirmRemove: { type: Boolean, default: false },
    min: { type: Number, default: 1 },
    minRequired: { type: Number, default: 0 }
  },
  computed: {
    onAddField() {
      return () => {}
    }
  },
  methods: {
    getDegradeColor(i) {
      return !i ? '' : `multi-label ${this.degrade} lighten-${5 - i}`
    },
    setDisabled(i) {
      return i < this.minRequired
    },
    removeField(idx) {
      if (this.confirmRemove)
        this.$swal({
          title: 'Are you sure?',
          text: 'Confirm deletion of this address?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then(result => {
          if (result.value) this.$delete(this.list, idx)
        })
      else this.$delete(this.list, idx)
    }
  }
}

export { BAMultiComponents }
