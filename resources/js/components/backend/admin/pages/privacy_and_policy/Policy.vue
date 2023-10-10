<template>
  <div class="container-fluid">
    <!-- Page Heading -->
    <!-- <div class="d-flex justify-content-between">
      <h1 class="h3 mb-2 text-gray-800">Privacy And Policy</h1>
    </div> -->
    <div class="container-xxl container-p-y">
        <Breadcrumb pageTitle = "Privacy and Policy"
            :breadcrumbList="breadcrumbList"
        />
    </div>
    <div class="card shadow">
      <div class="card-header">
        <h6 class="m-0 font-weight-bold text-danger">Add</h6>
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
              :class="errorItem.policy_detail != '' ? 'is-invalid' : ''"
              placeholder="Enter Privacy and Policy"
              v-model="editItem.policy_detail"
            />
            <span
              class="error-label"
              v-if="errorItem.policy_detail != ''"
              v-text="errorItem.policy_detail"
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
                to: { name: "admin.dashboard" },
                name: "Home",
            },
            {
                name: "Privacy and Policy",
            },
        ],
    editItem: {},
    isFormValid: false,
    errorItem: {
      policy_detail: '',
    },
  }),
  mounted() {
    window.scrollTo(0, 0);
  },
  created() {
    window.scrollTo(0, 0)
  },
  mounted() {
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
        this.editItem.policy_detail == null ||
        this.editItem.policy_detail == ''
      ) {
        this.errorItem.policy_detail = 'Fill Privacy And Policy'
        this.isValid = false
      } else {
        this.isValid = true
      }
      return this.isValid
    },
    async showData() {
      const res = await this.callApi('get', `/api/admin/policy/`, null)
      this.log(res)
      if (res.status == 200) {
        var data = res.data
        if (data.status == 'success') {
          this.editItem = data.policy
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
      formData.append('description', this.editItem.policy_detail)
      const res = await this.callApi(
        'post',
        '/api/admin/policy/save/update',
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
<style>
trix-editor {
  min-height: 250px !important;
  max-height: 500px !important;
  overflow-y: auto !important;
}
</style>
