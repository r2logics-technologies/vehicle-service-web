<template>
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between mb-4">
      <!-- <h1 class="h3 mb-2 text-gray-800">Driver Form</h1> -->
      <div class="flex-grow-1">
          <Breadcrumb pageTitle = "Drivers" :breadcrumbList = "breadcrumbList"/>
        </div>
      <div class="mr-3">
        <button
          class="btn btn-danger rounded-circle"
          title="View Service Centers"
          @click="$router.push('/admin/drivers')"
        >
          <i class="fa fa-eye"></i>
        </button>
      </div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Driver Form</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 col-sm-12">
            <div
              class="rounded-1 p-2 text-center position-relative"
              style="border: solid 1px #00000012"
            >
              <img :src="getImageUrlAvatar" style="height: 150px; max-width: 100%" />
              <input
                type="file"
                class="image-input"
                accept="image/jpeg, image/jpg, image/png, application/pdf"
                @change="getImageDoc($event, 'avatar')"
              />
              <div>
                <label class="mt-2">Driver Photo</label>
              </div>
            </div>
          </div>
          <div class="col-md-9 col-sm-12">
            <div class="row">
              <div class="col-sm-12">
                <label for="" class="form-label"
                  >Driver Name
                  <span class="text-danger">*</span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  :class="errorItem.driver_name != '' ? 'is-invalid' : ''"
                  placeholder="Enter Driver Name"
                  v-model="editItem.driver_name"
                />
                <span
                  class="error-label"
                  v-if="errorItem.driver_name != ''"
                  v-text="errorItem.driver_name"
                ></span>
              </div>
              <div class="col-md-6 col-sm-12">
                <label for="" class="form-label"
                  >Driver Email
                  <span class="text-danger">*</span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  :class="errorItem.driver_email != '' ? 'is-invalid' : ''"
                  v-model="editItem.driver_email"
                  placeholder="Enter Driver Email"
                />
                <span
                  class="error-label"
                  v-if="errorItem.driver_email != ''"
                  v-text="errorItem.driver_email"
                ></span>
              </div>
              <div class="col-md-6 col-sm-12">
                <label for="" class="form-label"
                  >Mobile Number
                  <span class="text-danger">*</span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  :class="errorItem.driver_mobile != '' ? 'is-invalid' : ''"
                  v-model="editItem.driver_mobile"
                  @keydown="enterValue"
                  placeholder="Enter Mobile Number"
                />
                <span
                  class="error-label"
                  v-if="errorItem.driver_mobile != ''"
                  v-text="errorItem.driver_mobile"
                ></span>
              </div>
              <div class="col-md-6 col-sm-12" v-if="editedIndex == -1">
                <label for="" class="form-label"
                  >Password
                  <span class="text-danger">*</span>
                </label>
                <input
                  type="password"
                  class="form-control"
                  :class="errorItem.driver_password != '' ? 'is-invalid' : ''"
                  v-model="editItem.driver_password"
                  placeholder="Enter Password"
                />
                <span
                  class="error-label"
                  v-if="errorItem.driver_password != ''"
                  v-text="errorItem.driver_password"
                ></span>
              </div>
              <div class="col-md-6 col-sm-12" v-if="editedIndex == -1">
                <label for="" class="form-label"
                  >Confirm Password
                  <span class="text-danger">*</span>
                </label>
                <input
                  type="password"
                  class="form-control"
                  :class="errorItem.driver_confirm_password != '' ? 'is-invalid' : ''"
                  v-model="editItem.driver_confirm_password"
                  placeholder="Enter Password"
                />
                <span
                  class="error-label"
                  v-if="errorItem.driver_confirm_password != ''"
                  v-text="errorItem.driver_confirm_password"
                ></span>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-md-6 col-sm-12">
            <label for="">Address Line 1 <span class="text-danger">*</span> </label>
            <textarea
              class="form-control"
              v-model="editItem.driver_address_line1"
              :class="errorItem.driver_address_line1 != '' ? 'is-invalid' : ''"
            ></textarea>
            <span
              v-if="errorItem.driver_address_line1 != ''"
              v-text="errorItem.driver_address_line1"
            >
            </span>
          </div>
          <div class="col-md-6 col-sm-12">
            <label for="">Address Line 2</label>
            <textarea
              class="form-control"
              v-model="editItem.driver_address_line2"
            ></textarea>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">Locality</label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.driver_location"
              placeholder="Enter location"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">Landmark</label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.driver_landmark"
              placeholder="Enter landmark"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">Pin Code <span class="text-danger">*</span></label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.driver_postcode"
              placeholder="Enter post code"
              @keydown="enterPin"
              :class="errorItem.driver_postcode != '' ? 'is-invalid' : ''"
            />
            <span
              class="error-label"
              v-if="errorItem.driver_postcode != ''"
              v-text="errorItem.driver_postcode"
            ></span>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">City <span class="text-danger">*</span></label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.driver_city"
              :class="errorItem.driver_city != '' ? 'is-invalid' : ''"
              placeholder="Enter City"
            />
            <span
              class="error-label"
              v-if="errorItem.driver_city != ''"
              v-text="errorItem.driver_city"
            ></span>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">State <span class="text-danger">*</span> </label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.driver_state"
              :class="errorItem.driver_state != '' ? 'is-invalid' : ''"
              placeholder="Enter State"
            />
            <span
              class="error-label"
              v-if="errorItem.driver_state != ''"
              v-text="errorItem.driver_state"
            ></span>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">Country <span class="text-danger">*</span> </label>
            <input
              type="text"
              class="form-control"
              placeholder="Enter Country"
              v-model="editItem.driver_country"
              :class="`${errorItem.driver_country != '' ? 'is-invalid' : ''}`"
            />
          </div>
        </div>

        <div class="row mt-5">
          <div class="col-12">
            <h3>Bank Details</h3>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label"
              >Account Holder Name
            </label>
            <input
              type="text"
              class="form-control"
              placeholder="Account Holder Name"
              v-model="editItem.driver_account_holder"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label"
              >Account Number
            </label>
            <input
              type="text"
              class="form-control"
              placeholder="Enter Account Number"
              v-model="editItem.driver_account_number"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label"
              >Confirm Account Number
            </label>
            <input
              type="text"
              class="form-control"
              :class="errorItem.driver_confirm_account_number != '' ? 'is-invalid' : ''"
              v-model="editItem.driver_confirm_account_number"
              placeholder="Enter Account Number"
            />
            <span
              class="error-label"
              v-if="errorItem.driver_confirm_account_number != ''"
              v-text="errorItem.driver_confirm_account_number"
            ></span>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label">Bank Name </label>
            <input
              type="text"
              class="form-control"
              placeholder="Enter Bank Name"
              v-model="editItem.driver_bank_name"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label">Bank Branch Name </label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.driver_bank_branch_name"
              placeholder="Enter Bank Branch Name"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label"
              >IFSC
            </label>
            <input
              type="text"
              class="form-control"
              placeholder="Enter IFSC"
              v-model="editItem.driver_ifsc"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label">UPI </label>
            <input
              type="text"
              class="form-control"
              placeholder="Enter UPI"
              v-model="editItem.driver_upi"
            />
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-12">
            <h3>
              Driver Identity Cards<span class="text-danger">*</span
              ><sup> (must add license)</sup>
            </h3>
          </div>
          <div class="col-md-6 row col-sm-12 mt-2">
            <div class="col-12 text-center">
              <h5>Driving License <span class="text-danger">*</span></h5>
            </div>
            <div class="my-1 col-md-4 mx-4 col-sm-12">
              <div
                class="rounded-1 p-2 text-center position-relative"
                style="border: solid 1px #00000012"
              >
                <img
                  :src="getImageUrlLicenseFront"
                  style="height: 100px; max-width: 100%"
                />
                <input
                  type="file"
                  class="image-input"
                  accept="image/jpeg, image/jpg, image/png, application/pdf"
                  ref="lf"
                  @change="getImageDoc($event, 'lf')"
                />
                <div>
                  <label class="mt-2">License Front</label>
                </div>
              </div>
              <span
                class="error-label"
                v-if="errorItem.driver_license_front != ''"
                v-text="errorItem.driver_license_front"
              ></span>
            </div>
            <div class="my-1 col-md-4 mx-4 col-sm-12">
              <div
                class="rounded-1 p-2 text-center position-relative"
                style="border: solid 1px #00000012"
              >
                <img
                  :src="getImageUrlLicenseBack"
                  style="height: 100px; max-width: 100%"
                />
                <input
                  type="file"
                  class="image-input"
                  accept="image/jpeg, image/jpg, image/png, application/pdf"
                  ref="lb"
                  @change="getImageDoc($event, 'lb')"
                />
                <div><label class="mt-2">License Back</label> <br /></div>
              </div>
              <span
                class="error-label"
                v-if="errorItem.driver_license_back != ''"
                v-text="errorItem.driver_license_back"
              ></span>
            </div>
          </div>
          <div class="col-md-6 row col-sm-12 mt-2">
            <div class="col-12 text-center">
              <h5>Aadhar Card</h5>
            </div>
            <div class="my-1 col-md-4 mx-4 col-sm-12">
              <div
                class="rounded-1 p-2 text-center position-relative"
                style="border: solid 1px #00000012"
              >
                <img
                  :src="getImageUrlAadharFront"
                  style="height: 100px; max-width: 100%"
                />
                <input
                  type="file"
                  class="image-input"
                  accept="image/jpeg, image/jpg, image/png, application/pdf"
                  @change="getImageDoc($event, 'af')"
                />
                <div>
                  <label class="mt-2">Aadhar Front</label>
                </div>
              </div>
            </div>
            <div class="my-1 col-md-4 mx-4 col-sm-12">
              <div
                class="rounded-1 p-2 text-center position-relative"
                style="border: solid 1px #00000012"
              >
                <img
                  :src="getImageUrlAadharBack"
                  style="height: 100px; max-width: 100%"
                />
                <input
                  type="file"
                  class="image-input"
                  accept="image/jpeg, image/jpg, image/png, application/pdf"
                  @change="getImageDoc($event, 'ab')"
                />
                <div>
                  <label class="mt-2">Aadhar Back</label>
                </div>
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
        name: "Delivery Mans",
      },
    ],
    editItem: {},
    errorItem: {
      driver_name: "",
      driver_email: "",
      driver_mobile: "",
      driver_password: "",
      driver_confirm_password: "",
      driver_confirm_account_number: "",
      driver_license_front: "",
      driver_license_back: "",
      driver_address_line1: "",
      driver_postcode: "",
      driver_city: "",
      driver_state: "",
      driver_country: "",
    },

    emailRegex: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/,
    mobileRegex: /^[5-9][0-9]{9}$/,

    isValidForm: false,

    srcImageFileAV: null,
    imageFilePathAV: null,
    srcImageFileLF: null,
    imageFilePathLF: null,
    srcImageFileLB: null,
    imageFilePathLB: null,
    srcImageFileAF: null,
    imageFilePathAF: null,
    srcImageFileAB: null,
    imageFilePathAB: null,
    editedIndex: -1,
  }),
  mounted() {
    window.scrollTo(0, 0);
  },
  created() {
    setTimeout(() => {
      if (this.$route.params.driver_id) {
        this.editedIndex = this.$route.params.driver_id;
        this.getEditData();
      }
    }, 500);
  },
  watch: {
    "editItem.driver_mobile": function (val) {
      if (val != null && val != "") {
        if (val && !this.mobileRegex.test(val)) {
          this.errorItem.driver_mobile = "Enter Valid Mobile Number";
        } else {
          this.errorItem.driver_mobile = "";
        }
      }
    },
    "editItem.driver_confirm_password": function (val) {
      if (val != null && val != "") {
        if (this.editItem.driver_password != val) {
          this.errorItem.driver_confirm_password = "Enter Same Password ";
        } else {
          this.errorItem.driver_confirm_password = "";
        }
      }
    },
    "editItem.driver_confirm_account_number": function (val) {
      if (val != null && val != "") {
        if (this.editItem.driver_account_number != val) {
          this.errorItem.driver_confirm_account_number = "Enter Same Account Number";
        } else {
          this.errorItem.driver_confirm_account_number = "";
        }
      }
    },
  },
  computed: {
    getImageUrlAvatar: function () {
      return this.srcImageFileAV != null && this.srcImageFileAV != ""
        ? this.srcImageFileAV
        : "/assets/img/upload.png";
    },
    getImageUrlLicenseFront: function () {
      return this.srcImageFileLF != null && this.srcImageFileLF != ""
        ? this.srcImageFileLF
        : "/assets/img/upload.png";
    },
    getImageUrlLicenseBack: function () {
      return this.srcImageFileLB != null && this.srcImageFileLB != ""
        ? this.srcImageFileLB
        : "/assets/img/upload.png";
    },
    getImageUrlAadharFront: function () {
      return this.srcImageFileAF != null && this.srcImageFileAF != ""
        ? this.srcImageFileAF
        : "/assets/img/upload.png";
    },
    getImageUrlAadharBack: function () {
      return this.srcImageFileAB != null && this.srcImageFileAB != ""
        ? this.srcImageFileAB
        : "/assets/img/upload.png";
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
    enterPin(e) {
      if (
        e.which != 8 &&
        e.which != 9 &&
        e.which != 13 &&
        e.which != 46 &&
        (e.which < 37 || e.which > 40)
      ) {
        if (e.target.value.length >= 6) {
          e.preventDefault();
          return;
        }
      }

      if (!this.isNumberOnly(e)) {
        e.preventDefault();
        return;
      }
    },
    getImageDoc(e, docName) {
      const file = e.target.files[0];
      if (!file.type.includes("image")) {
        Toast.fire({
          icon: "error",
          title: `Wrong file format please select image file (like .JPG, .PNG, .SVG, etc.)`,
          timer: 2000,
        });
        e.target.value = "";
      } else if (file) {
        if (docName == "avatar") {
          this.srcImageFileAV = URL.createObjectURL(file);
          URL.revokeObjectURL(file);
          this.imageFilePathAV = file;
        }

        if (docName == "lf") {
          this.srcImageFileLF = URL.createObjectURL(file);
          URL.revokeObjectURL(file);
          this.imageFilePathLF = file;
        }

        if (docName == "lb") {
          this.srcImageFileLB = URL.createObjectURL(file);
          URL.revokeObjectURL(file);
          this.imageFilePathLB = file;
        }

        if (docName == "af") {
          this.srcImageFileAF = URL.createObjectURL(file);
          URL.revokeObjectURL(file);
          this.imageFilePathAF = file;
        }

        if (docName == "ab") {
          this.srcImageFileAB = URL.createObjectURL(file);
          URL.revokeObjectURL(file);
          this.imageFilePathAB = file;
        }
      } else {
        Toast.fire({
          icon: "warning",
          title: "something went wrong!plz try again...",
          timer: 2500,
        });
      }
    },
    formValidation() {
      if (this.editItem.driver_name == null || this.editItem.driver_name == "") {
        this.errorItem.driver_name = "Driver Name is required";
        this.isValidForm = false;
      } else {
        this.errorItem.driver_name = "";
        this.isValidForm = true;
      }

      if (
        this.editItem.driver_email == null ||
        this.editItem.driver_email == "" ||
        !this.emailRegex.test(this.editItem.driver_email)
      ) {
        this.errorItem.driver_email = "Driver Email is required";
        this.isValidForm = false;
      } else {
        this.errorItem.driver_email = "";
        this.isValidForm = true;
      }

      if (
        this.editItem.driver_mobile == null ||
        this.editItem.driver_mobile == "" ||
        !this.mobileRegex.test(this.editItem.driver_mobile)
      ) {
        this.errorItem.driver_mobile = "Driver Mobile Number is required";
        this.isValidForm = false;
      } else {
        this.errorItem.driver_mobile = "";
        this.isValidForm = true;
      }

      if (this.editItem.driver_password == null || this.editItem.driver_password == "") {
        this.errorItem.driver_password = "Driver Password is required";
        this.isValidForm = false;
      } else {
        this.errorItem.driver_password = "";
        this.isValidForm = true;
      }

      if (this.editItem.driver_confirm_password != this.editItem.driver_password) {
        this.errorItem.driver_confirm_password = "Password do not match";
        this.isValidForm = false;
      } else {
        this.errorItem.driver_confirm_password = "";
        this.isValidForm = true;
      }

      if (
        this.editItem.driver_account_number != null ||
        this.editItem.driver_account_number != ""
      ) {
        if (
          this.editItem.driver_account_number !=
          this.editItem.driver_confirm_account_number
        ) {
          this.errorItem.driver_confirm_account_number = "Account Number do not match";
          this.isValidForm = false;
        } else {
          this.errorItem.driver_confirm_account_number = "";
          this.isValidForm = true;
        }
      } else {
        this.errorItem.driver_confirm_account_number = "";
        this.isValidForm = true;
      }

      if (this.srcImageFileLF == null || this.srcImageFileLF == "") {
        this.errorItem.driver_license_front = "Please select license.";
        this.isValidForm = false;
      } else {
        this.errorItem.driver_license_front = "";
        this.isValidForm = true;
      }

      if (
        this.editItem.driver_address_line1 == null ||
        this.editItem.driver_address_line1 == ""
      ) {
        this.errorItem.driver_address_line1 = "Shop Address Line 1 is required";
        this.isValidForm = false;
      } else {
        this.errorItem.driver_address_line1 = "";
        this.isValidForm = true;
      }
      if (this.editItem.driver_postcode == null || this.editItem.driver_postcode == "") {
        this.errorItem.driver_postcode = "postcode is required";
        this.isValidForm = false;
      } else {
        this.errorItem.driver_postcode = "";
        this.isValidForm = true;
      }
      if (this.editItem.driver_city == null || this.editItem.driver_city == "") {
        this.errorItem.driver_city = "city is required";
        this.isValidForm = false;
      } else {
        this.errorItem.driver_city = "";
        this.isValidForm = true;
      }
      
      if (this.editItem.driver_state == null || this.editItem.driver_state == "") {
        this.errorItem.driver_state = "State is required";
        this.isValidForm = false;
      } else {
        this.errorItem.driver_state = "";
        this.isValidForm = true;
      }
      if (this.editItem.driver_country == null || this.editItem.driver_country == "") {
        this.errorItem.driver_country = "Country is required";
        this.isValidForm = false;
      } else {
        this.errorItem.driver_country = "";
        this.isValidForm = true;
      }

      if (this.srcImageFileLB == null || this.srcImageFileLB == "") {
        this.errorItem.driver_license_back = "Please select license.";
        this.isValidForm = false;
      } else {
        this.errorItem.driver_license_front = "";
        this.isValidForm = true;
      }


      return this.isValidForm;
    },

    async getEditData() {
      const res = await this.callApi(
        "get",
        "/api/admin/drivers/edit/" + this.editedIndex,
        null
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.editItem = data.driver;
          this.srcImageFileAV = data.driver.driver_avatar;
          this.srcImageFileLF = data.driver.driver_license_front;
          this.srcImageFileLB = data.driver.driver_license_back;
          this.srcImageFileAF = data.driver.driver_aadhar_front;
          this.srcImageFileAB = data.driver.driver_aadhar_back;
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

      formData.append(
        "avatar",
        this.imageFilePathAV != null && this.imageFilePathAV != ""
          ? this.imageFilePathAV
          : null
      );

      formData.append(
        "license_front",
        this.imageFilePathLF != null && this.imageFilePathLF != ""
          ? this.imageFilePathLF
          : null
      );

      formData.append(
        "license_back",
        this.imageFilePathLB != null && this.imageFilePathLB != ""
          ? this.imageFilePathLB
          : null
      );
      formData.append(
        "aadhar_front",
        this.imageFilePathAF != null && this.imageFilePathAF != ""
          ? this.imageFilePathAF
          : null
      );
      formData.append(
        "aadhar_back",
        this.imageFilePathAB != null && this.imageFilePathAB != ""
          ? this.imageFilePathAB
          : null
      );

      formData.append("name", this.editItem.driver_name);
      formData.append("email", this.editItem.driver_email);
      formData.append("mobile", this.editItem.driver_mobile);
      formData.append("password", this.editItem.driver_password);
      formData.append("confirm_password", this.editItem.driver_confirm_password);
      formData.append("holder_name", this.editItem.driver_account_holder);
      formData.append("account_number", this.editItem.driver_account_number);
      formData.append("confirm_account_number",this.editItem.driver_confirm_account_number);
      formData.append("address_line1", this.editItem.driver_address_line1);
      formData.append("address_line2", this.editItem.driver_address_line2);
      formData.append("location", this.editItem.driver_location);
      formData.append("landmark", this.editItem.driver_landmark);
      formData.append("postcode", this.editItem.driver_postcode);
      formData.append("city", this.editItem.driver_city);
      formData.append("state", this.editItem.driver_state);
      formData.append("country", this.editItem.driver_country);
      formData.append("bank_name", this.editItem.driver_bank_name);
      formData.append("bank_branch_name", this.editItem.driver_bank_branch_name);
      formData.append("upi", this.editItem.driver_upi);
      formData.append("ifsc", this.editItem.driver_ifsc);

      const res = await this.callApi(
        "post",
        "/api/admin/drivers/save/update",
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
            this.$router.push("/admin/drivers");
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
      this.srcImageFileAV = null;
      this.srcImageFileLF = null;
      this.srcImageFileLB = null;
      this.srcImageFileAF = null;
      this.srcImageFileAB = null;
      this.editedIndex = -1;
    },

    edit(item) {
      this.editItem = item;
      this.editedIndex = item.id;
      this.srcImageFileAV = item.driver_avatar;
      this.srcImageFileLF = item.driver_license_front;
      this.srcImageFileLB = item.driver_license_back;
      this.srcImageFileAF = item.driver_aadhar_front;
      this.srcImageFileAB = item.driver_aadhar_back;
    },
  },
};
</script>
