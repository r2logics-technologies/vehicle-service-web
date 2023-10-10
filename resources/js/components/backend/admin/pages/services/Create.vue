<template>
  <div class="container-fluid">
    <div class="d-flex justify-content-between">
      <Breadcrumb pageTitle="Services" :breadcrumbList="breadcrumbList" />
      <div class="float-end mt-2">
        <button
          class="btn btn-danger rounded-circle py-2"
          title="View Service Centers"
          @click="$router.push('/admin/services')"
        >
          <i class="fa fa-eye"></i>
        </button>
      </div>
    </div>
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Form</h6>
      </div>
      <div class="card-body">
        <div class="row mb-5">
          <div class="col-md-3 mt-2 col-sm-12">
            <div
              class="rounded-1 p-2 text-center position-relative"
              style="border: solid 1px #00000012"
            >
              <img :src="getImageUrl" style="height: 100px; max-width: 100%" />
              <input
                type="file"
                class="image-input"
                accept="image/jpeg, image/jpg, image/png, application/pdf"
                @change="selectImage($event)"
              />
              <div>
                <label class="mt-2">Service Icon</label>
              </div>
            </div>
          </div>
          <div class="col-md-9 col-sm-12">
            <div class="row">
              <div class="col-12 my-2">
                <label for="service-category-select"
                  >Service Category <span class="text-danger">*</span></label
                >
                <div>
                  <select
                    v-model="editItem.service_category_id"
                    class="form-control"
                    id="service-category-select"
                  >
                    <option value="" disabled>Select Category</option>
                    <option
                      v-for="(cat, index) in categories"
                      :key="index"
                      :value="cat.category_id"
                    >
                      {{ cat.category_name }}
                    </option>
                  </select>
                </div>
                <span
                  v-if="errorItem.service_category_id != ''"
                  v-text="errorItem.service_category_id"
                  class="error-label"
                ></span>
              </div>
              <div class="row col-12 my-2">
                <div class="col-md-6">
                  <label for="" class="form-label"
                    >Service Name
                    <span class="text-danger">*</span>
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="errorItem.service_name != '' ? 'is-invalid' : ''"
                    v-model="editItem.service_name"
                    placeholder="Enter Service Name"
                  />
                  <span
                    v-if="errorItem.service_name != ''"
                    v-text="errorItem.service_name"
                    class="error-label"
                  ></span>
                </div>
                <div class="col-md-6">
                  <label for="" class="form-label"
                    >Service Price
                    <span class="text-danger">* </span><sup>  In rupees ₹ only</sup>
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    @keydown="enterPrice"
                    :class="errorItem.service_price != '' ? 'is-invalid' : ''"
                    v-model="editItem.service_price"
                    placeholder="Enter Service Price (ex. 1000.00)"
                  />
                  <span
                    v-if="errorItem.service_price != ''"
                    v-text="errorItem.service_price"
                    class="error-label"
                  ></span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-2">
          <button class="btn btn-danger w-100" @click="submitData()">SUBMIT</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data: () => ({
    breadcrumbList: [
      {
        to: { name: "admin.dashboard" },
        name: "Home",
      },
      {
        name: "Add Services",
      },
    ],
    
    editItem: {
      service_category_id: "",
    },
    errorItem: {
      service_name: "",
      service_price: "",
    },
    srcImageFile: null,
    imageFilePath: null,
    isValidForm: false,
    editedIndex: -1,
    categories: [],
    priceRegex : /^\$?[0-9]{1,10}(?:\.[0-9]{1,2})?$/,
  }),
  mounted() {
    window.scrollTo(0, 0);
  },
  created() {
    this.getCategories();
    setTimeout(() => {
      if (this.$route.params.service_id) {
        this.editedIndex = this.$route.params.service_id;
        this.getEditData();
      }
    }, 500);
  },
  watch: {
    "editItem.service_price": function (val) {
      console.log(val);
      if (val != null && val != "") {
        if (val && !this.priceRegex.test(val)) {
          this.errorItem.service_price = "Enter Valid Price";
        } else {
          this.errorItem.service_price = "";
        }
      }
    },
  },
  computed: {
    
    getImageUrl: function () {
      return this.srcImageFile != null && this.srcImageFile != ""
        ? this.srcImageFile
        : "/assets/img/upload.png";
    },
  },
  methods: {
    enterPrice(){
      const charCode = e.which ? e.which : e.keyCode;
      if (
        charCode == 46 || charCode == 110 && this.editItem.package_price.indexOf('.') == -1 
      ) {
        return true;
      }
      if (
        charCode > 31 && (charCode < 48 || charCode > 57) &&
        (charCode < 96 || charCode > 105)
      ) {
        e.preventDefault();
        return false;
      }
      return true;
    },
    async getCategories() {
      const res = await this.callApi("get", "/api/admin/categories", null);
      this.log(res);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.categories = data.categories;
        } else {
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          });
        }
      } else {
        toast = Toast.fire({
          icon: "error",
          title: "Oops!! Something went wrong. Try again..",
          timer: 2500,
        });
      }
    },

    selectImage(e) {
      const file = e.target.files[0];
      if (file) {
        this.srcImageFile = URL.createObjectURL(file);
        URL.revokeObjectURL(file);
        this.imageFilePath = file;
      } else {
        this.srcImageFile = null;
        this.imageFilePath = null;
      }
    },
    async getEditData() {
      const res = await this.callApi(
        "get",
        "/api/admin/services/edit/" + this.editedIndex,
        null
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.editItem = data.service;
          this.srcImageFile = data.service.service_icon;
        } else {
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          });
        }
      } else {
        toast = Toast.fire({
          icon: "error",
          title: "Oops!! Something went wrong. Try again..",
          timer: 2500,
        });
      }
    },
    formValidation() {
      if (this.editItem.service_name == null || this.editItem.service_name == "") {
        this.errorItem.service_name = "Service name is required";
        this.isValidForm = false;
      } else {
        this.errorItem.service_name = "";
        this.isValidForm = true;
      }

      if (
        this.editItem.service_category_id == null ||
        this.editItem.service_category_id == ""
      ) {
        this.errorItem.service_category_id = "Service category is required";
        this.isValidForm = false;
      } else {
        this.errorItem.service_category_id = "";
        this.isValidForm = true;
      }

      if (
        this.editItem.service_price == null ||
        this.editItem.service_price == ""
      ) {
        this.errorItem.service_price = "Service price is required";
        this.isValidForm = false;
      } else {
        this.errorItem.service_price = "";
        this.isValidForm = true;
      }

      return this.isValidForm;
    },
    async submitData() {
      var isValid = this.formValidation();
      if (!isValid) {
        return;
      }

      this.log(this.editItem);
      Toast.fire({
        icon: "warning",
        title: "Uploading...",
        timer: 0,
      });
      var formData = new FormData();
      formData.append("edited", this.editedIndex);
      formData.append(
        "icon",
        this.imageFilePath != null && this.imageFilePath != "" ? this.imageFilePath : null
      );
      formData.append("name", this.editItem.service_name);
      formData.append("price", this.editItem.service_price);
      formData.append("cat_id", this.editItem.service_category_id);
      this.log("res", formData);
      const res = await this.callApi(
        "post",
        "/api/admin/services/save/update",
        formData,
        "multipart"
      );
      if (res.status == 200) {
        Toast.close();
        var data = res.data;
        if (data.status == "success") {
          Toast.fire({
            icon: "success",
            title: data.message,
            timer: 2500,
          });
          setTimeout(() => {
            this.$router.push("/admin/services");
            this.clearForm();
          }, 2500);
        } else {
          Toast.fire({
            icon: "warning",
            title: data.message,
            timer: 2500,
          });
        }
      } else {
        Toast.fire({
          icon: "warning",
          title: "opps something went wrong..",
          timer: 2500,
        });
      }
    },
    clearForm() {
      this.editItem = {};
      this.editedIndex = -1;
      this.srcImageFile = null;
      this.imageFilePath = null;
    },
    edit(item) {
      this.editItem = item;
      this.editedIndex = item.id;
      this.srcImageFile = item.image;
    },
  },
};
</script>
