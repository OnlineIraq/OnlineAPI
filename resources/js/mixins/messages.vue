<script>
export default {
    methods :{
        successMessage() {
            this.$swal.fire({
                icon: 'success',
                type: 'success',
                title:this.$t('success'),
                showConfirmButton: false,
                timer: 1500
            });
        },
        errorMessage() {
            this.$swal.fire({
                icon: 'error',
                type: 'error',
                title:this.$t('notAvailable'),
                showConfirmButton: false,
                timer: 1500
            });
        },
        confirm() {
            return new Promise((resolve,reject) => {
                this.$swal.fire({
                    title: this.$t('confirm'),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: this.$t('cancel'),
                    confirmButtonText: this.$t('yes'),
                }).then((result) => {
                    resolve(result.value);
                });
            });
        },
        makeToast(variant,error) {
            this.$bvToast.toast(error, {
                title: '',
                variant:variant ,
                solid: true
            })
        },
    }
}
</script>
