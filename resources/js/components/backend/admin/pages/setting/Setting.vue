<template>
  <div>
    <div class="container-fluid">
      <div class="container-xxl flex-grow-1 container-p-y">
        <Breadcrumb pageTitle="App Settings" :breadcrumbList="breadcrumbList" />
      </div>

      <div class="card">
        <div class="card-header">
          <p class="btn btn-primary py-1 float-right editBtn" @click="editSetting()">
            Edit Setting <i class="fa fa-pen"></i>
          </p>
          <p
            class="btn btn-danger mx-2 py-1 float-right d-none saveBtn"
            @click="cancelEdit()"
          >
            Cancel ❌
          </p>
          <p
            class="btn btn-primary py-1 float-right d-none saveBtn"
            @click="saveSetting()"
          >
            Save Changes <i class="fa fa-check"></i>
          </p>
        </div>
        <div class="card-body row">
          <div class="col-md-4 col-sm-12 py-2 border rounded">
            <div class="rounded-1 py-2 text-center position-relative">
              <img :src="getLogo" style="height: 100px; max-width: 100%" />
              <input
                type="file"
                disabled
                class="image-input"
                accept="image/jpeg, image/jpg, image/png, application/pdf"
                @change="selectImage($event)"
              />
              <div>
                <label class="mt-2">Application's Logo</label>
              </div>
            </div>
            <div class="mt-4 text-center">
              <label class="col-12">Application's Name </label>
              <input
                type="text"
                :class="errorSetting.app_name != '' ? 'is-invalid' : ''"
                v-model="setting.app_name"
                disabled
                class="text-secondary text-center border-0 readOnly"
              />
              <span
                v-if="errorSetting.app_name != ''"
                v-text="errorSetting.app_name"
                class="error-label"
              ></span>
            </div>
          </div>
          <div class="col-md-8 col-sm-12 py-2">
            <div class="row m-auto">
              <div class="col-3">
                <h6 class="mb-0">Owner Name</h6>
              </div>
              <div class="col-9">
                <input
                  class="text-secondary border-0 readOnly"
                  disabled
                  :class="errorSetting.owner_name != '' ? 'is-invalid' : ''"
                  v-model="setting.owner_name"
                />
                <span
                  class="error-label"
                  v-if="errorSetting.owner_name != ''"
                  v-text="errorSetting.owner_name"
                ></span>
              </div>
            </div>
            <hr />
            <div class="row m-auto">
              <div class="col-3">
                <h6 class="mb-0">Owner Email</h6>
              </div>
              <div class="col-9">
                <input
                  class="text-secondary border-0 readOnly"
                  v-model="setting.owner_email"
                  disabled
                  :class="errorSetting.owner_email != '' ? 'is-invalid' : ''"
                />
                <span
                  class="error-label"
                  v-if="errorSetting.owner_email != ''"
                  v-text="errorSetting.owner_email"
                ></span>
              </div>
            </div>
            <hr />
            <div class="row m-auto">
              <div class="col-3">
                <h6 class="mb-0">Owner Mobile</h6>
              </div>
              <div class="col-9">
                <input
                  class="text-secondary border-0 readOnly"
                  disabled
                  v-model="setting.owner_mobile"
                  @keydown="enterValue"
                />
                <span
                  class="error-label"
                  v-if="errorSetting.owner_mobile != ''"
                  v-text="errorSetting.owner_mobile"
                ></span>
              </div>
            </div>
            <hr />
            <div class="row m-auto">
              <div class="col-3">
                <h6 class="mb-0">Current Version</h6>
              </div>
              <div class="col-9">
                <input
                  class="text-secondary border-0 readOnly"
                  disabled
                  v-model="setting.current_version"
                  :class="errorSetting.current_version != '' ? 'is-invalid' : ''"
                />
                <span
                  class="error-label"
                  v-if="errorSetting.current_version != ''"
                  v-text="errorSetting.current_version"
                ></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
var toast;
export default {
  data: () => ({
    breadcrumbList: [
      {
        to: { name: "admin.dashboard" },
        name: "Home",
      },
      {
        name: "App Settings",
      },
    ],
    setting: {},
    errorSetting: {
      app_name: "",
      owner_name: "",
      owner_email: "",
      owner_mobile: "",
      current_version: "",
    },
    isValidForm: false,
    mobileRegex: /^[5-9][0-9]{9}$/,
    srcImageFile: null,
    imageFilePath: null,
  }),
  mounted() {
    window.scrollTo(0, 0);
    this.showData();
  },
  computed: {
    getLogo: function () {
      return this.srcImageFile != null && this.srcImageFile != ""
        ? this.srcImageFile
        : "/assets/img/upload.png";
    },
  },
  watch: {
    "setting.owner_mobile": function (val) {
      if (val != null && val != "") {
        if (val && !this.mobileRegex.test(val)) {
          this.errorSetting.owner_mobile = "Enter Valid Mobile Number";
        } else {
          this.errorSetting.owner_mobile = "";
        }
      }
    },
  },
  methods: {
    enterValue(e) {
      if (
        e.which != 8 &&
        e.which != 9 &&
        e.which != 13 &&
        e.which != 46 &&
        (e.which < 37 || e.which > 40)
      ) {
        if (e.target.value.length >= 10) {
          e.preventDefault();
          return;
        }
      }

      if (!this.isNumberOnly(e)) {
        e.preventDefault();
        return;
      }
    },
    async showData() {
      const res = await this.callApi("get", "/api/admin/setting/", null);
      this.log("data", res);
      var data = res.data;
      if (res.status == 200) {
        if (res.data.status == "success") {
          this.setting = data.setting;
          this.srcImageFile = this.setting.logo;
        } else {
          toast.fire({
            icon: "warning",
            title: res.data.message,
            timer: 2500,
          });
        }
      } else {
        toast.fire({
          icon: "warning",
          title: res.statusText,
          timer: 2500,
        });
      }
      toast.close();
    },
    selectImage(e) {
      const file = e.target.files[0];
      if (file) {
        if (!file.type.includes("image")) {
          Toast.fire({
            icon: "error",
            title:
              "Wrong file format please select image file (like .JPG, .PNG, .SVG, etc.)",
            timer: 2500,
          });
          e.target.value = "";
          this.srcImageFile = null;
          this.imageFilePath = null;
        } else {
          this.srcImageFile = URL.createObjectURL(file);
          URL.revokeObjectURL(file);
        }
        this.imageFilePath = file;
      } else {
        this.srcImageFile = null;
        this.imageFilePath = null;
      }
    },
    editSetting() {
      $(".image-input").attr("disabled", false);
      $(".readOnly").attr("disabled", false);
      $(".readOnly").toggleClass("form-control border-0");
      $(".editBtn").toggleClass("d-none");
      $(".saveBtn").toggleClass("d-none");
    },
    formValidation() {
      if (this.setting.app_name == null || this.setting.app_name == "") {
        this.errorSetting.app_name = "Name is required!";
        this.isValidForm = false;
      } else {
        this.errorSetting.app_name = "";
        this.isValidForm = true;
      }
      if (this.setting.owner_name == null || this.setting.owner_name == "") {
        this.errorSetting.owner_name = "Name is required!";
        this.isValidForm = false;
      } else {
        this.errorSetting.owner_name = "";
        this.isValidForm = true;
      }
      if (this.setting.owner_email == null || this.setting.owner_email == "") {
        this.errorSetting.owner_email = "Email is required!";
        this.isValidForm = false;
      } else {
        this.errorSetting.owner_email = "";
        this.isValidForm = true;
      }
      if (this.setting.owner_mobile == null || this.setting.owner_mobile == "") {
        this.errorSetting.owner_mobile = "Mobile Number is required!";
        this.isValidForm = false;
      } else {
        this.errorSetting.owner_mobile = "";
        this.isValidForm = true;
      }
      if (this.setting.current_version == null || this.setting.current_version == "") {
        this.errorSetting.current_version = "Version is required!";
        this.isValidForm = false;
      } else {
        this.errorSetting.current_version = "";
        this.isValidForm = true;
      }

      return this.isValidForm;
    },
    cancelEdit() {
      $(".readOnly").attr("disabled", true);
      $(".image-input").attr("disabled", true);
      $(".readOnly").toggleClass("form-control border-0");
      $(".editBtn").toggleClass("d-none");
      $(".saveBtn").toggleClass("d-none");
      this.showData();
    },
    async saveSetting() {
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
      formData.append(
        "logo",
        this.imageFilePath != null && this.imageFilePath != ""
          ? this.imageFilePath
          : this.srcImageFile
      );
      formData.append("app_name", this.setting.app_name);
      formData.append("name", this.setting.owner_name);
      formData.append("email", this.setting.owner_email);
      formData.append("mobile", this.setting.owner_mobile);
      formData.append("version", this.setting.current_version);

      const res = await this.callApi(
        "post",
        "/api/admin/setting/change_setting",
        formData,
        "multipart"
      );

      if (res.status == 200) {
        Toast.close();
        var data = res.data;
        if (data.status == "success") {
          this.setting = data.setting;
          Toast.fire({
            icon: "success",
            title: data.message,
            timer: 2500,
          });
        } else {
          Toast.fire({
            icon: "warning",
            title: data.message,
            timer: 2500,
          });
        }
      } else {
        Toast.close();
        Toast.fire({
          icon: "warning",
          title: "opps something went wrong..",
          timer: 2500,
        });
      }

      if (res.status == 500) {
        Toast.close();
        Toast.fire({
          icon: "warning",
          title: "opps something went wrong..",
          timer: 2500,
        });
      }

      $(".image-input").attr("disabled", true);
      $(".readOnly").attr("disabled", true);
      $(".readOnly").toggleClass("form-control border-0");
      $(".editBtn").toggleClass("d-none");
      $(".saveBtn").toggleClass("d-none");
    },
  },
};
</script>
