<template>
  <div class="container-fluid">
    <!-- Page Heading -->
    <!-- <div class="d-flex justify-content-between">
      <h1 class="h3 mb-2 text-gray-800">Terms And Conditions</h1>
    </div> -->
    <div class="container-xxl container-p-y">
        <Breadcrumb pageTitle = "Terms and Conditions"
            :breadcrumbList="breadcrumbList"
        />
    </div>
    <div class="card shadow">
      <div class="card-header">
        <h6 class="m-0 font-weight-bold text-danger">Terms and Conditions</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-sm-12">
            <label for="">
              Description
              <span class="text-danger">*</span>
            </label>
            <VueTrix
              class="form-control"
              :class="errorItem.terms_detail != '' ? 'is-invalid' : ''"
              placeholder="Enter Terms And Conditions Here..."
              v-model="editItem.terms_detail"
            />
            <span
              class="error-label"
              v-if="errorItem.terms_detail != ''"
              v-text="errorItem.terms_detail"
            ></span>
          </div>
          <div class="col-sm-12 py-4">
            <button
              type="submit"
              class="btn mt-2 btn-danger w-100"
              @click="submitData()"
            >
              SUBMIT
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
var toast
export default {
  data: () => ({
    breadcrumbList: [
        {
            to: { name: "admin.dashboard"},
            name: "Home",
        },
        {
            name: "Create"
        },
    ],
    editItem: {},
    isFormValid: false,
    errorItem: {
      terms_detail: '',
    },
  }),
  created() {
    window.scrollTo(0, 0)
  },
  mounted() {
    window.scrollTo(0, 0);
    toast = Toast.fire({
      icon: 'warning',
      title: 'Data Loading..',
      timer: 0,
    })
    this.showData()
  },
  methods: {
    validationForm() {
      if (
        this.editItem.terms_detail == null ||
        this.editItem.terms_detail == ''
      ) {
        this.errorItem.terms_detail = 'Fill Terms And Conditions'
        this.isValid = false
      } else {
        this.isValid = true
      }
      return this.isValid
    },
    async showData() {
      const res = await this.callApi('get', `/api/admin/terms/`, null)
      if (res.status == 200) {
        var data = res.data
        if (data.status == 'success') {
          this.editItem = data.terms
        } else {
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          })
        }
        toast.close()
      }
    },
    async submitData() {
      var isValid = await this.validationForm()
      if (!isValid) return

      Toast.fire({
        icon: 'warning',
        title: 'Uploading...',
        timer: 0,
      })

      var formData = new FormData()
      formData.append('description', this.editItem.terms_detail)
      const res = await this.callApi(
        'post',
        '/api/admin/terms/save/update',
        formData,
      )
      if (res.status == 200) {
        var data = res.data
        Toast.fire({
          icon: 'success',
          title: data.message,
          timer: 2500,
        })
      } else {
        Toast.fire({
          icon: 'warning',
          title: 'opps something went wrong..',
          timer: 2500,
        })
      }
    },
  },
}
</script>
