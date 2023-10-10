<template>
  <div>
    <div class="container-fluid">
      <div class="container-xxl flex-grow-1 container-p-y">
        <Breadcrumb pageTitle="Profile" :breadcrumbList="breadcrumbList" />
      </div>
      <div class="card">
        <div class="card-header">
          <p class="btn btn-primary py-1 float-right editBtn" @click="editProfile()">
            Edit Profile <i class="fa fa-pen"></i>
          </p>
          <p
            class="btn btn-danger mx-2 py-1 float-right d-none saveBtn"
            @click="cancelEdit()"
          >
            Cancel ❌
          </p>
          <p
            class="btn btn-primary py-1 float-right d-none saveBtn"
            @click="saveProfile()"
          >
            Save Changes <i class="fa fa-check"></i>
          </p>
        </div>
        <div class="card-body row">
          <div class="col-md-12 mb-3 border-bottom py-5 mb-5">
            <div class="d-flex flex-column align-items-center text-center">
              <div class="col-md-3 col-sm-12">
                <div class="p-2 text-center position-relative">
                  <img
                    :src="getUserImage"
                    alt="Admin"
                    class="rounded-circle"
                    width="150"
                  />
                  <input
                    type="file"
                    class="image-input"
                    disabled
                    accept="image/jpeg, image/jpg, image/png, application/pdf"
                    @change="selectImage($event)"
                  />
                  <div>
                    <p class="btn py-1 d-none saveBtn">
                      Change Image
                      <i class="fa fa-pen"></i>
                    </p>
                  </div>
                </div>
              </div>
              <div class="mt-3">
                <h4>{{ user.user_name }}</h4>
                <p class="text-secondary mb-1">User's Name</p>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row m-auto">
              <div class="col-3">
                <h6 class="mb-0">Full Name</h6>
              </div>
              <div class="col-6">
                <input
                  class="text-secondary border-0 readOnly"
                  disabled
                  :class="errorUser.user_name != '' ? 'is-invalid' : ''"
                  v-model="user.user_name"
                />
                <span
                  class="error-label"
                  v-if="errorUser.user_name != ''"
                  v-text="errorUser.user_name"
                ></span>
              </div>
            </div>
            <hr />
            <div class="row m-auto">
              <div class="col-3">
                <h6 class="mb-0">Email</h6>
              </div>
              <div class="col-6">
                <input
                  class="text-secondary border-0 readOnly"
                  v-model="user.user_email"
                  disabled
                  :class="errorUser.user_email != '' ? 'is-invalid' : ''"
                />
                <span
                  class="error-label"
                  v-if="errorUser.user_email != ''"
                  v-text="errorUser.user_email"
                ></span>
              </div>
            </div>
            <hr />
            <div class="row m-auto">
              <div class="col-3">
                <h6 class="mb-0">Mobile</h6>
              </div>
              <div class="col-6">
                <input
                  class="text-secondary border-0 readOnly"
                  disabled
                  v-model="user.user_mobile"
                  @keydown="enterValue"
                />
                <span
                  class="error-label"
                  v-if="errorUser.user_mobile != ''"
                  v-text="errorUser.user_mobile"
                ></span>
              </div>
            </div>
            <hr />

            <h6
              @click="changePassword()"
              class="btn row btn-secondary py-1 passwordChangeBtn d-none"
            >
              Change Password
            </h6>

            <div class="collapse" id="password_change">
              <div class="row m-auto">
                <div class="col-3">
                  <h6 class="mb-0">Current Password</h6>
                </div>
                <div class="col-6">
                  <input
                    :type="showPassword.current ? 'text' : 'password'"
                    class="form-control"
                    v-model="user.user_current_password"
                  />
                </div>
                <i
                  class="fa position-relative col-1 p-0 m-0"
                  :class="!showPassword.current ? 'fa-eye' : 'fa-eye-slash'"
                  style="top: 10px; left: -3rem"
                  @click="showPassword.current = !showPassword.current"
                ></i>
              </div>
              <hr />
              <div class="row m-auto">
                <div class="col-3">
                  <h6 class="mb-0">New Password</h6>
                </div>
                <div class="col-6">
                  <input
                    :type="showPassword.new ? 'text' : 'password'"
                    class="form-control"
                    v-model="user.user_new_password"
                  />
                  <span
                    class="error-label"
                    v-if="errorUser.user_new_password != ''"
                    v-text="errorUser.user_new_password"
                  ></span>
                </div>
                <i
                  class="fa position-relative col-1 p-0 m-0"
                  :class="!showPassword.new ? 'fa-eye' : 'fa-eye-slash'"
                  @click="showPassword.new = !showPassword.new"
                  style="top: 10px; left: -3rem"
                ></i>
              </div>
              <hr />
              <div class="row m-auto">
                <div class="col-3">
                  <h6 class="mb-0">Confirm New Password</h6>
                </div>
                <div class="col-6">
                  <input
                    :type="showPassword.confirm ? 'text' : 'password'"
                    class="form-control"
                    v-model="user.user_confirm_password"
                    :class="errorUser.user_confirm_password != '' ? 'is-invalid' : ''"
                  />
                  <span
                    class="error-label"
                    v-if="errorUser.user_confirm_password != ''"
                    v-text="errorUser.user_confirm_password"
                  ></span>
                </div>
                <i
                  class="fa position-relative col-1 p-0 m-0"
                  :class="!showPassword.confirm ? 'fa-eye' : 'fa-eye-slash'"
                  @click="showPassword.confirm = !showPassword.confirm"
                  style="top: 10px; left: -3rem"
                ></i>
              </div>
              <hr />
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
                name: "Profile",
            },
        ],
    user: {},
    errorUser: {
      user_name: "",
      user_email: "",
      user_mobile: "",
      user_confirm_password: "",
    },
    isValidForm: false,
    passChange: 0,
    mobileRegex: /^[5-9][0-9]{9}$/,
    srcImageFile: null,
    imageFilePath: null,
    showPassword: {
      current: false,
      new: false,
      confirm: false,
    },
  }),
  mounted() {
    window.scrollTo(0, 0);
   
    this.showData();
  },
  computed: {
    getUserImage: function () {
      return this.srcImageFile != null && this.srcImageFile != ""
        ? this.srcImageFile
        : "/assets/img/avatar.png";
    },
  },
  watch: {
    "user.user_mobile": function (val) {
      if (val != null && val != "") {
        if (val && !this.mobileRegex.test(val)) {
          this.errorUser.user_mobile = "Enter Valid Mobile Number";
        } else {
          this.errorUser.user_mobile = "";
        }
      }
    },

    "user.user_confirm_password": function (val) {
      if (val != null && val != "") {
        if (this.user.user_new_password != val) {
          this.errorUser.user_confirm_password = "Enter Same Password ";
        } else {
          this.errorUser.user_confirm_password = "";
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
      const res = await this.callApi("get", "/api/admin/profile/", null);
      this.log("data", res);
      if (res.status == 200) {
        if (res.data.status == "success") {
          this.user = res.data.user;
          this.srcImageFile = this.user.user_avatar;
          this.log(this.srcImageFile);
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
    editProfile() {
      $(".image-input").attr("disabled", false);
      $(".readOnly").attr("disabled", false);
      $(".readOnly").toggleClass("form-control border-0");
      $(".passwordChangeBtn").toggleClass("d-none");
      $(".editBtn").toggleClass("d-none");
      $(".saveBtn").toggleClass("d-none");
    },
    changePassword() {
      $("#password_change").toggleClass("collapse");
      $(".passwordChangeBtn").toggleClass("d-none");
      this.passChange = 1;
    },
    formValidation() {
      if (this.user.user_name == null || this.user.user_name == "") {
        this.errorUser.user_name = "Name is required";
        this.isValidForm = false;
      } else {
        this.errorUser.user_name = "";
        this.isValidForm = true;
      }
      if (this.user.user_email == null || this.user.user_email == "") {
        this.errorUser.user_email = "Email is required";
        this.isValidForm = false;
      } else {
        this.errorUser.user_email = "";
        this.isValidForm = true;
      }
      if (this.user.user_mobile == null || this.user.user_mobile == "") {
        this.errorUser.user_mobile = "Mobile Number is required";
        this.isValidForm = false;
      } else {
        this.errorUser.user_mobile = "";
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
      $("#password_change").addClass("collapse");
      $(".passwordChangeBtn").addClass("d-none");
      this.passChange = 0;
      this.showData();
    },
    async saveProfile() {
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
        "user_avatar",
        this.imageFilePath != null && this.imageFilePath != ""
          ? this.imageFilePath
          : this.srcImageFile
      );
      formData.append("pass_change", this.passChange);
      formData.append("id", this.user.id);
      formData.append("name", this.user.user_name);
      formData.append("email", this.user.user_email);
      formData.append("mobile", this.user.user_mobile);
      formData.append("current_password", this.user.user_current_password);
      formData.append("new_password", this.user.user_new_password);

      const res = await this.callApi(
        "post",
        "/api/admin/profile/change_profile",
        formData,
        "multipart"
      );

      if (res.status == 200) {
        Toast.close();
        var data = res.data;
        if (data.status == "success") {
          this.user = res.data.userUpdate;
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
      $("#password_change").addClass("collapse");
      $(".passwordChangeBtn").addClass("d-none");
    },
  },
};
</script>
