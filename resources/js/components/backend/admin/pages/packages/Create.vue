<template>
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between mb-4">
      <div class="flex-grow-1">
          <Breadcrumb pageTitle = "Packages" :breadcrumbList="breadcrumbList" />
        </div>
      <div class="mr-3">
        <button
          class="btn btn-danger rounded-circle"
          title="View Service Centers"
          @click="$router.push('/admin/packages')"
        >
          <i class="fa fa-eye"></i>
        </button>
      </div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Package Form</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mt-2 col-sm-12">
            <label for="" class="form-label"
              >Package Name
              <span class="text-danger">*</span>
            </label>
            <input
              type="text"
              class="form-control"
              :class="errorItem.package_name != '' ? 'is-invalid' : ''"
              placeholder="Enter Package Name"
              v-model="editItem.package_name"
            />
            <span
              class="error-label"
              v-if="errorItem.package_name != ''"
              v-text="errorItem.package_name"
            ></span>
          </div>
          <div class="col-md-6 mt-2 col-sm-12">
            <label for="">Package Type <span class="text-danger">*</span></label>
            <select
              v-model="editItem.package_type"
              class="form-control"
              :class="errorItem.package_type != '' ? 'is-invalid' : ''"
            >
              <option value="" selected disabled>--SELECT TYPE--</option>
              <option value="bike">Bike</option>
              <option value="car">Car</option>
              <option value="both">Both</option>
            </select>
            <span
              class="error-label"
              v-if="errorItem.package_type != ''"
              v-text="errorItem.package_type"
            ></span>
          </div>
          <div class="col-md-6 mt-2 col-sm-12">
            <label for="" class="form-label"
              >Package Price
              <span class="text-danger">* </span><sup>  In rupees ₹ only</sup>
            </label>
            <input
              type="text"
              class="form-control"
              :class="errorItem.package_price != '' ? 'is-invalid' : ''"
              placeholder="Enter package price (ex. 1000.00)"
              @keydown="enterValue"
              v-model="editItem.package_price"
            />
            <span
              class="error-label"
              v-if="errorItem.package_price != ''"
              v-text="errorItem.package_price"
            ></span>
          </div>
          <div class="col-md-6 mt-2 col-sm-12">
            <label for="" class="form-label"
              >Package Duration
              <span class="text-danger">*</span>
            </label>
            <select
              v-model="editItem.package_duration"
              class="form-control"
              :class="errorItem.package_duration != '' ? 'is-invalid' : ''"
            >
              <option value="" selected disabled>--SELECT DURATION MONTH--</option>
              <option v-for="n in 12" :key="n" :value="n">{{ n }} Months</option>
            </select>
            <span
              class="error-label"
              v-if="errorItem.package_duration != ''"
              v-text="errorItem.package_duration"
            ></span>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-sm-12">
            <h3 class="col-10">Benefits</h3>
            <span
              type="submit"
              class="btn rounded-circle float-right btn-danger"
              @click="benefits.push({ benefit_name: '' })"
            >
            <i class="fa fa-plus"></i>
            </span>
          </div>
          <div
            class="col-md-6 col-sm-12"
            v-for="(benefit, index) in benefits"
            :key="index"
          >
            <div class="my-2">
              <i
                class="fa fa-times text-danger float-right"
                v-if="index != 0"
                @click="removeBenefit(index)"
              ></i>
            </div>
            <div class="my-2 row">
              <div class="col-md-6 col-sm-12">
                {{ index + 1 }}.
                <label for="">Benefit</label>
                <input
                  type="text"
                  v-model="benefit.name"
                  class="form-control"
                  placeholder="Enter benefit..."
                />
              </div>
              <div class="col-md-3 col-sm-12">
                <label for="">Limit </label>
                <input
                  type="text"
                  v-model="benefit.time"
                  class="form-control"
                  placeholder="Limit..."
                />
              </div>
            </div>
          </div>
        </div>
        <div class="mt-4">
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
        name: "Create Package",
      },
    ],
    editItem: {
      package_type: "",
      package_duration: "",
    },

    benefits: [{ name: "" }],

    errorItem: {
      package_name: "",
      package_type: "",
      package_price: "",
      package_duration: "",
    },

    isValidForm: false,

    editedIndex: -1,
  }),
  mounted() {
    window.scrollTo(0, 0);
  },
  created() {
    setTimeout(() => {
      if (this.$route.params.package_id) {
        this.editedIndex = this.$route.params.package_id;
        this.getEditData();
      }
    }, 500);
  },
  computed: {},
  methods: {
    enterValue(e)  {
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

    formValidation() {
      if (this.editItem.package_name == null || this.editItem.package_name == "") {
        this.errorItem.package_name = "Package name is required";
        this.isValidForm = false;
      } else {
        this.errorItem.package_name = "";
        this.isValidForm = true;
      }
      if (this.editItem.package_type == null || this.editItem.package_type == "") {
        this.errorItem.package_type = "Package type is required";
        this.isValidForm = false;
      } else {
        this.errorItem.package_type = "";
        this.isValidForm = true;
      }
      if (this.editItem.package_price == null || this.editItem.package_price == "") {
        this.errorItem.package_price = "Package price is required";
        this.isValidForm = false;
      } else {
        this.errorItem.package_price = "";
        this.isValidForm = true;
      }
      if (
        this.editItem.package_duration == null ||
        this.editItem.package_duration == ""
      ) {
        this.errorItem.package_duration = "Package duration is required";
        this.isValidForm = false;
      } else {
        this.errorItem.package_duration = "";
        this.isValidForm = true;
      }

      return this.isValidForm;
    },

    removeBenefit(i) {
      this.benefits.splice(i, 1);
    },

    async submitData() {
      var isValid = this.formValidation();
      if (!isValid) {
        return;
      }

      Toast.fire({
        icon: "warning",
        title: "Uploading...",
        timer: 0,
      });
      var formData = new FormData();
      formData.append("edited", this.editedIndex);
      formData.append("name", this.editItem.package_name);
      formData.append("package_type", this.editItem.package_type);
      formData.append("price", this.editItem.package_price);
      formData.append("duration", this.editItem.package_duration);
      this.benefits.forEach((ele) => {
        formData.append("benefit_name[]", ele.name);
        formData.append("benefit_time[]", ele.time);
      });

      const res = await this.callApi(
        "post",
        "/api/admin/packages/save/update",
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
            this.$router.push("/admin/packages");
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

    async getEditData() {
      const res = await this.callApi(
        "get",
        "/api/admin/packages/edit/" + this.editedIndex,
        null
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.editItem = data.package;

          if (
            data.package.package_benefits != null &&
            data.package.package_benefits.length > 0
          ) {
            this.benefits = [];
            data.package.package_benefits.forEach((ele) => {
              this.benefits.push({
                id: ele.id,
                name: ele.name,
                time: ele.time,
              });
            });
          }
          toast = Toast.fire({
            icon: "success",
            title: "success..",
            timer: 2500,
          });
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
    clearForm() {
      this.editItem = {};
      this.editedIndex = -1;
    },

    edit(item) {
      this.editItem = item;
      this.editedIndex = item.id;
      this.srcImageFile = item.image;
    },
  },
};
</script>
