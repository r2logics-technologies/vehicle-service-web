<template>
  <div class="container-fluid">
    <div class="d-flex justify-content-between">
      <Breadcrumb :pageTitle="pageTitle" :breadcrumbList="breadcrumbList" />
      <div class="float-end mt-2">
        <button
          class="btn btn-danger rounded-circle py-2"
          title="View Service Centers"
          @click="$router.push('/admin/service-centers')"
          v-if="!isCenter"
        >
          <i class="fa fa-eye"></i>
        </button>
      </div>
    </div>
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Service Center Form</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 col-sm-12">
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
                <label class="mt-2">Service Center Image</label>
              </div>
            </div>
          </div>
          <div class="col-md-9 col-sm-12">
            <div class="row">
              <div class="col-md-6 col-sm-12">
                <label for="" class="form-label"
                  >Shop Name
                  <span class="text-danger">*</span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  :class="errorItem.shop_name != '' ? 'is-invalid' : ''"
                  v-model="editItem.shop_name"
                  placeholder="Enter Shop Name"
                />
                <span
                  v-if="errorItem.shop_name != ''"
                  v-text="errorItem.shop_name"
                  class="error-label"
                ></span>
              </div>
              <div class="col-md-6 col-sm-12">
                <label for="" class="form-label"
                  >Shop Owner Name
                  <span class="text-danger">*</span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  v-model="editItem.shop_owner"
                  :class="errorItem.shop_owner != '' ? 'is-invalid' : ''"
                  placeholder="Enter Shop Owner Name"
                />
                <span
                  class="error-label"
                  v-if="errorItem.shop_owner != ''"
                  v-text="errorItem.shop_owner"
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
                  v-model="editItem.shop_mobile"
                  placeholder="Enter  Mobile Number"
                  @keydown="enterValue"
                  :class="`${errorItem.shop_mobile != '' ? 'is-invalid' : ''}`"
                />
                <span
                  class="error-label"
                  v-if="errorItem.shop_mobile != ''"
                  v-text="errorItem.shop_mobile"
                ></span>
              </div>
              <div class="col-md-6 col-sm-12">
                <label for="" class="form-label"
                  >Shop Email <span class="text-danger">*</span></label
                >
                <input
                  type="text"
                  class="form-control"
                  v-model="editItem.shop_email"
                  placeholder="Enter Shop Email Address"
                  :class="errorItem.shop_email != '' ? 'is-invalid' : ''"
                />
                <span
                  class="error-label"
                  v-if="errorItem.shop_email != ''"
                  v-text="errorItem.shop_email"
                ></span>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">Alternet Mobile Number</label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.shop_alter_mobile"
              @keydown="enterValue"
              placeholder="Enter alternate set of mobile numbers"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">Alternet Email </label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.shop_alter_email"
              placeholder="Enter alternate set of email address"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">Delivery Type <span class="text-danger">*</span></label>
            <select
              v-model="editItem.shop_delivery_type"
              class="form-control"
              :class="errorItem.shop_delivery_type != '' ? 'is-invalid' : ''"
            >
              <option value="" selected disabled>--SELECT TYPE--</option>
              <option value="delivery">Delivery</option>
              <option value="take-way">Take-away</option>
              <option value="both">Both</option>
            </select>
          </div>
          <div class="col-md-4 col-sm-12" v-if="editedIndex == -1">
            <label for="">Password</label>
            <input
              type="password"
              class="form-control"
              placeholder="password..."
              v-model="editItem.shop_password"
            />
          </div>
          <div class="col-md-4 col-sm-12" v-if="editedIndex == -1">
            <label for="">Confirm Password</label>
            <input
              type="password"
              class="form-control"
              placeholder="password..."
              v-model="editItem.shop_confirm_password"
              :class="errorItem.shop_confirm_password != '' ? 'is-invalid' : ''"
            />
            <span
              class="error-label"
              v-if="errorItem.shop_confirm_password != ''"
              v-text="errorItem.shop_confirm_password"
            ></span>
          </div>
        </div>
        <div class="row mt-4">
        <div class="row col-12">
          <div class="col-md-3 col-sm-12">
            <h4>Categories :</h4>
          </div>
          <div v-for="(cat, index) in categories" :key="index" class="col-md-3 col-sm-6">
            <input
              type="checkbox"
              class="checkbox-cat"
              :id="`cat${index}`"
              @change="selectCategory(cat.category_id, index)"
            />
            <label
              for="exampleFormControlSelect1"
              class="fs-5"
              @click="selectCategory(cat.category_id, index)"
              >{{ cat.category_name }}
            </label>
          </div>
        </div>
          <!-- <div class="row col-12">
          <div class="col-md-3 col-sm-12">
            <h4>Services :</h4>
          </div>
          <div v-for="(service, index) in services" :key="index" class="col-md-3 col-sm-6">
            <input
              type="checkbox"
              class="checkbox-cat"
              :id="`service${index}`"
              @change="selectService(service.service_id, index)"
            />
            <label
              for="exampleFormControlSelect1"
              class="fs-5"
              @click="selectService(service.service_id, index)"
              >{{ service.service_name }}
            </label>
          </div>
          </div> -->
          <div class="col-12">
            <label for="">Description</label>
            <textarea
              v-model="editItem.shop_description"
              placeholder="Enter Description hare...."
              class="form-control"
            ></textarea>
          </div>
          <div class="col-md-6 col-sm-12">
            <label for="">Address Line 1 <span class="text-danger">*</span> </label>
            <textarea
              class="form-control"
              v-model="editItem.shop_address_line1"
              :class="errorItem.shop_address_line1 != '' ? 'is-invalid' : ''"
            ></textarea>
            <span
              v-if="errorItem.shop_address_line1 != ''"
              v-text="errorItem.shop_address_line1"
            >
            </span>
          </div>
          <div class="col-md-6 col-sm-12">
            <label for="">Address Line 2</label>
            <textarea
              class="form-control"
              v-model="editItem.shop_address_line2"
            ></textarea>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">Locality</label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.shop_location"
              placeholder="Enter location"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">Landmark</label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.shop_landmark"
              placeholder="Enter landmark"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">Pin Code <span class="text-danger">*</span></label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.shop_postcode"
              placeholder="Enter post code"
              @keydown="enterPin"
              :class="errorItem.shop_postcode != '' ? 'is-invalid' : ''"
            />
            <span
              class="error-label"
              v-if="errorItem.shop_postcode != ''"
              v-text="errorItem.shop_postcode"
            ></span>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">City <span class="text-danger">*</span></label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.shop_city"
              :class="errorItem.shop_city != '' ? 'is-invalid' : ''"
              placeholder="Enter City"
            />
            <span
              class="error-label"
              v-if="errorItem.shop_city != ''"
              v-text="errorItem.shop_city"
            ></span>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">State <span class="text-danger">*</span> </label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.shop_state"
              :class="errorItem.shop_state != '' ? 'is-invalid' : ''"
              placeholder="Enter State"
            />
            <span
              class="error-label"
              v-if="errorItem.shop_state != ''"
              v-text="errorItem.shop_state"
            ></span>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="">Country <span class="text-danger">*</span> </label>
            <input
              type="text"
              class="form-control"
              placeholder="Enter Country"
              v-model="editItem.shop_country"
              :class="`${errorItem.shop_country != '' ? 'is-invalid' : ''}`"
            />
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-12">
            <h3>Bank Details</h3>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label">Account Holder Name </label>
            <input
              type="text"
              class="form-control"
              placeholder="Account Holder Name"
              v-model="editItem.shop_account_holder"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label">Account Number </label>
            <input
              type="text"
              class="form-control"
              placeholder="Enter Account Number"
              v-model="editItem.shop_account_number"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label">Confirm Account Number </label>
            <input
              type="text"
              class="form-control"
              placeholder="Enter Account Number"
              v-model="editItem.shop_confirm_account_number"
              :class="`${
                errorItem.shop_confirm_account_number != '' ? 'is-invalid' : ''
              }`"
            />
            <span
              class="error-label"
              v-if="errorItem.shop_confirm_account_number != ''"
              v-text="errorItem.shop_confirm_account_number"
            ></span>
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label">Bank Name </label>
            <input
              type="text"
              class="form-control"
              placeholder="Enter Bank Name"
              v-model="editItem.shop_bank_name"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label">Bank Branch Name </label>
            <input
              type="text"
              class="form-control"
              v-model="editItem.shop_bank_branch_name"
              placeholder="Enter Bank Branch Name"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label">IFSC </label>
            <input
              type="text"
              class="form-control"
              placeholder="Enter IFSC"
              v-model="editItem.shop_ifsc"
            />
          </div>
          <div class="col-md-4 col-sm-12">
            <label for="" class="form-label">UPI </label>
            <input
              type="text"
              class="form-control"
              placeholder="Enter UPI"
              v-model="editItem.shop_upi"
            />
          </div>
        </div>

        <div class="col-12 mt-4">
          <h2>Documents</h2>
        </div>
        <div class="row" v-if="editedIndex != -1">
          <div
            class="col-md-3 col-sm-12"
            v-for="(doc, index) in oldDocuments"
            :key="index"
          >
            <div class="my-2">
              <div>
                <label for="">Document Name</label>
                <i
                  class="fa fa-trash text-danger float-right"
                  @click="deleteDocumentBtn(doc.id, index)"
                ></i>
              </div>
              <input
                type="text"
                v-model="doc.name"
                class="form-control"
                placeholder="Enter Document Name ..."
              />
            </div>
            <div
              class="rounded-1 p-2 text-center position-relative"
              style="border: solid 1px #00000012"
            >
              <img :src="doc.srcImageFile" style="height: 100px; max-width: 100%" />
              <input
                type="file"
                class="image-input"
                accept="image/jpeg, image/jpg, image/png, application/pdf"
                @change="selectOldDocumentImage($event, index)"
              />
              <div>
                <label class="mt-2">Service Center Document</label>
              </div>
            </div>
            <div class="text-center my-2">
              <i class="btn btn-success" @click="uploadDocument(doc.id, index)">
                Upload Document <i class="fa fa-upload"></i>
              </i>
            </div>
          </div>
        </div>

        <div class="my-4">
          <span
            type="submit"
            class="btn rounded-circle float-right btn-danger"
            @click="
              documents.push({
                name: '',
                imageFilePath: null,
                srcImageFile: '/assets/img/upload.png',
              })
            "
          >
            <i class="fa fa-plus"></i>
          </span>
        </div>
        <div class="row">
          <div class="col-md-3 col-sm-12" v-for="(doc, index) in documents" :key="index">
            <div>
              <i
                class="fa fa-times text-danger float-right"
                v-if="index != 0"
                @click="removeDocument(index)"
              ></i>
            </div>
            <div class="my-2">
              <label for="">Document Name</label>
              <input
                type="text"
                v-model="doc.name"
                class="form-control"
                placeholder="Enter Document Name ..."
              />
            </div>
            <div
              class="rounded-1 p-2 text-center position-relative"
              style="border: solid 1px #00000012"
            >
              <img :src="doc.srcImageFile" style="height: 100px; max-width: 100%" />
              <input
                type="file"
                class="image-input"
                accept="image/jpeg, image/jpg, image/png, application/pdf"
                @change="selectDocumentImage($event, index)"
              />
              <div>
                <label class="mt-2">Service Center Document</label>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-2">
          <button class="btn btn-danger w-100" @click="submitData()">
            {{ isCenter ? "SAVE" : "SUBMIT" }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data: () => ({
    pageTitle: "Service Center",
    breadcrumbList: [
      {
        to: { name: "admin.dashboard" },
        name: "Home",
      },
      {
        name: "Add Service Center",
      },
    ],
    editItem: {
      shop_delivery_type: "",
    },
    errorItem: {
      shop_name: "",
      shop_email: "",
      shop_mobile: "",
      shop_owner: "",
      shop_delivery_type: "",
      shop_address_line1: "",
      shop_address_line2: "",
      shop_postcode: "",
      shop_city: "",
      shop_state: "",
      shop_country: "",
      shop_confirm_password: "",
      shop_confirm_account_number: "",
    },
    srcImageFile: null,
    imageFilePath: null,
    emailRegex: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/,
    mobileRegex: /^[5-9][0-9]{9}$/,

    isValidForm: false,

    editedIndex: -1,
    // services: [],
    // serviceIds: [],
    categories: [],
    catIds: [],
    documents: [
      {
        name: "",
        imageFilePath: null,
        srcImageFile: "/assets/img/upload.png",
      },
    ],
    oldDocuments: [],
    isCenter: false,
  }),
  mounted() {
    window.scrollTo(0, 0);
  },
  created() {
    if (this.$route.path.includes("/service-center/profile/")) {
      this.isCenter = true;
      this.pageTitle = "Profile";
      this.breadcrumbList[0].to = { name: "service-center.dashboard" };
      this.breadcrumbList[1].name = "Profile";
    }
    this.getCategories();
    setTimeout(() => {
      if (this.$route.params.service_center_id) {
        this.editedIndex = this.$route.params.service_center_id;
        this.getEditData();
      }
    }, 500);
    // this.getServices();
    // setTimeout(() => {
    //   if (this.$route.params.service_center_id) {
    //     this.editedIndex = this.$route.params.service_center_id;
    //     this.getServices();
    //   }
    // }, 500);
  },
  watch: {
    "editItem.shop_mobile": function (val) {
      if (val != null && val != "") {
        if (val && !this.mobileRegex.test(val)) {
          this.errorItem.shop_mobile = "Enter Valid Mobile Number";
        } else {
          this.errorItem.shop_mobile = "";
        }
      }
    },
    "editItem.shop_confirm_password": function (val) {
      if (val != null && val != "") {
        if (this.editItem.shop_password != val) {
          this.errorItem.shop_confirm_password = "Enter Same Password ";
        } else {
          this.errorItem.shop_confirm_password = "";
        }
      }
    },
    "editItem.shop_confirm_account_number": function (val) {
      if (val != null && val != "") {
        if (this.editItem.shop_account_number != val) {
          this.errorItem.shop_confirm_account_number = "Enter Same Account Number";
        } else {
          this.errorItem.shop_confirm_account_number = "";
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
    async getCategories() {
      const res = await this.callApi("get", "/api/admin/categories", null);
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
          timer: 2500,
        });
      }
    },
    // async getServices() {
    //   const res = await this.callApi("get", "/api/admin/services", null);
    //   if (res.status == 200) {
    //     var data = res.data;
    //     if (data.status == "success") {
    //       this.services = data.services;
    //     } else {
    //       toast = Toast.fire({
    //         icon: data.status,
    //         title: data.message,
    //         timer: 2500,
    //       });
    //     }
    //   } else {
    //     toast = Toast.fire({
    //       icon: "error",
    //       title: "Oops!! Something went wrong. Try again..",
    //       timer: 2500,
    //     });
    //   }
    // },
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
    selectCategory(id, index) {
      if (this.catIds.includes(id)) {
        this.catIds.splice(this.catIds.indexOf(id), 1);
        $("#cat" + index).prop("checked", false);
      } else {
        $("#cat" + index).prop("checked", true);
        this.catIds.push(id);
      }
    },
    // selectService(id, index) {
    //   if (this.serviceIds.includes(id)) {
    //     this.serviceIds.splice(this.serviceIds.indexOf(id), 1);
    //     $("#service" + index).prop("checked", false);
    //   } else {
    //     $("#service" + index).prop("checked", true);
    //     this.serviceIds.push(id);
    //   }
    // },
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
    selectDocumentImage(e, i) {
      const file = e.target.files[0];
      if (file) {
        this.documents[i].srcImageFile = URL.createObjectURL(file);
        URL.revokeObjectURL(file);
        this.documents[i].imageFilePath = file;
      } else {
        this.documents[i].srcImageFile = null;
        this.document[i].imageFilePath = null;
      }
    },
    deleteDocumentBtn(id, index) {
      SwalCustomBtn.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "No",
        confirmButtonText: `Yes`,
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteOldDocument(id, index);
        }
      });
    },
    async deleteOldDocument(id, index) {
      const res = await this.callApi(
        "post",
        "/api/admin/service-centers/delete_document/" + id,
        null
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          SwalCustomBtn.fire("Deleted!", "Your document deleted.", "success");
          this.oldDocuments.splice(index, 1);
        }
      }
    },
    selectOldDocumentImage(e, i) {
      const file = e.target.files[0];
      if (file) {
        this.oldDocuments[i].srcImageFile = URL.createObjectURL(file);
        URL.revokeObjectURL(file);
        this.oldDocuments[i].imageFilePath = file;
      } else {
        this.oldDocuments[i].srcImageFile = null;
        this.oldDocuments[i].imageFilePath = null;
      }
    },
    async uploadDocument(id, i) {
      Toast.fire({
        icon: "warning",
        title: "Uploading...",
        timer: 0,
      });
      var oldDocumentData = new FormData();
      oldDocumentData.append("document_file", this.oldDocuments[i].imageFilePath);
      oldDocumentData.append("document_name", this.oldDocuments[i].name);
      oldDocumentData.append("id", id);

      const res = await this.callApi(
        "post",
        "/api/admin/service-centers/upload_document",
        oldDocumentData,
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
    removeDocument(index) {
      this.documents.splice(index, 1);
    },
    async getEditData() {
      const res = await this.callApi(
        "get",
        "/api/admin/service-centers/edit/" + this.editedIndex,
        null
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.editItem = data.center;
          this.srcImageFile = data.center.image;
          if (
            data.center.shop_documents != null &&
            data.center.shop_documents.length > 0
          ) {
            data.center.shop_documents.forEach((ele) => {
              this.oldDocuments.push({
                id: ele.id,
                name: ele.name,
                srcImageFile: ele.file,
                imageFilePath: null,
              });
            });
          }

          if (this.editItem.shop_categories.length) {
            this.editItem.shop_categories.forEach((element, index) => {
              this.categories.forEach((ele, i) => {
                if (element.category_id == ele.category_id) {
                  this.selectCategory(ele.category_id, i);
                }
              });
            });
          }
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
      if (this.editItem.shop_name == null || this.editItem.shop_name == "") {
        this.errorItem.shop_name = "Shop Name is required";
        this.isValidForm = false;
      } else {
        this.errorItem.shop_name = "";
        this.isValidForm = true;
      }

      if (
        this.editItem.shop_email == null ||
        this.editItem.shop_email == "" ||
        !this.emailRegex.test(this.editItem.shop_email)
      ) {
        this.errorItem.shop_email = "Shop Email is required";
        this.isValidForm = false;
      } else {
        this.errorItem.shop_email = "";
        this.isValidForm = true;
      }

      if (
        this.editItem.shop_mobile == null ||
        this.editItem.shop_mobile == "" ||
        !this.mobileRegex.test(this.editItem.shop_mobile)
      ) {
        this.errorItem.shop_mobile = "Shop Mobile is required";
        this.isValidForm = false;
      } else {
        this.errorItem.shop_mobile = "";
        this.isValidForm = true;
      }
      if (this.editItem.shop_delivery_type == "") {
        this.errorItem.shop_delivery_type = "Shop Delivery Type is required";
        this.isValidForm = false;
      } else {
        this.errorItem.shop_delivery_type = "";
        this.isValidForm = true;
      }
      if (
        this.editItem.shop_address_line1 == null ||
        this.editItem.shop_address_line1 == ""
      ) {
        this.errorItem.shop_address_line1 = "Shop Address Line 1 is required";
        this.isValidForm = false;
      } else {
        this.errorItem.shop_address_line1 = "";
        this.isValidForm = true;
      }
      if (this.editItem.shop_postcode == null || this.editItem.shop_postcode == "") {
        this.errorItem.shop_postcode = "postcode is required";
        this.isValidForm = false;
      } else {
        this.errorItem.shop_postcode = "";
        this.isValidForm = true;
      }
      if (this.editItem.shop_city == null || this.editItem.shop_city == "") {
        this.errorItem.shop_city = "city is required";
        this.isValidForm = false;
      } else {
        this.errorItem.shop_city = "";
        this.isValidForm = true;
      }
      if (this.editItem.shop_state == null || this.editItem.shop_state == "") {
        this.errorItem.shop_state = "State is required";
        this.isValidForm = false;
      } else {
        this.errorItem.shop_state = "";
        this.isValidForm = true;
      }
      if (this.editItem.shop_country == null || this.editItem.shop_country == "") {
        this.errorItem.shop_country = "Country is required";
        this.isValidForm = false;
      } else {
        this.errorItem.shop_country = "";
        this.isValidForm = true;
      }

      // if (this.editedIndex == -1) {
      //   if (this.editItem.shop_password == null || this.editItem.shop_password == "") {
      //     this.errorItem.shop_password = "Password is required";
      //     this.isValidForm = false;
      //   } else {
      //     this.errorItem.shop_password = "";
      //     this.isValidForm = true;
      //   }
      //   if (this.editItem.shop_confirm_password != this.editItem.shop_password) {
      //     this.errorItem.shop_confirm_password = "Passwords do not match";
      //     this.isValidForm = false;
      //   } else {
      //     this.errorItem.shop_confirm_password = "";
      //     this.isValidForm = true;
      //   }
      // }

      // if (
      //   this.editItem.shop_account_number != null ||
      //   this.editItem.shop_account_number != ""
      // ) {
      //   if (
      //     this.editItem.shop_account_number != this.editItem.shop_confirm_account_number
      //   ) {
      //     this.errorItem.shop_confirm_account_number = "Account Number do not match";
      //     this.isValidForm = false;
      //   } else {
      //     this.errorItem.shop_confirm_account_number = "";
      //     this.isValidForm = true;
      //   }
      // } else {
      //   this.errorItem.shop_confirm_account_number = "";
      //   this.isValidForm = true;
      // }

      return this.isValidForm;
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
        "logo",
        this.imageFilePath != null && this.imageFilePath != "" ? this.imageFilePath : null
      );
      formData.append("name", this.editItem.shop_name);
      formData.append("owner", this.editItem.shop_owner);
      formData.append("email", this.editItem.shop_email);
      formData.append("mobile", this.editItem.shop_mobile);
      formData.append("delivery_type", this.editItem.shop_delivery_type);
      formData.append("alter_email", this.editItem.shop_alter_email);
      formData.append("alter_mobile", this.editItem.shop_alter_mobile);
      formData.append("description", this.editItem.shop_description);
      formData.append("address_line1", this.editItem.shop_address_line1);
      formData.append("address_line2", this.editItem.shop_address_line2);
      formData.append("location", this.editItem.shop_location);
      formData.append("landmark", this.editItem.shop_landmark);
      formData.append("postcode", this.editItem.shop_postcode);
      formData.append("city", this.editItem.shop_city);
      formData.append("state", this.editItem.shop_state);
      formData.append("country", this.editItem.shop_country);
      formData.append("cat_ids", this.catIds);
      formData.append("holder_name", this.editItem.shop_account_holder);
      formData.append("account_number", this.editItem.shop_account_number);
      formData.append(
        "confirm_account_number",
        this.editItem.shop_confirm_account_number
      );
      formData.append("bank_name", this.editItem.shop_bank_name);
      formData.append("bank_branch_name", this.editItem.shop_bank_branch_name);
      formData.append("upi", this.editItem.shop_upi);
      formData.append("ifsc", this.editItem.shop_ifsc);
      this.documents.forEach((ele) => {
        formData.append("document_file[]", ele.imageFilePath);
        formData.append("document_name[]", ele.name);
      });
      const res = await this.callApi(
        "post",
        "/api/admin/service-centers/save/update",
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
          if (this.isCenter == true) {
            setTimeout(() => {
              window.scrollTo(0, 0);
            }, 2500);
          } else {
            setTimeout(() => {
              this.$router.push("/admin/service-centers");
              this.clearForm();
            }, 2500);
          }
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
      this.editItem.shop_delivery_type = "";
      this.editedIndex = -1;
      this.srcImageFile = null;
      this.imageFilePath = null;
      this.cat_ids = [];
      $(".checkbox-cat").prop("checked", false);
    },
    edit(item) {
      this.editItem = item;
      this.editedIndex = item.id;
      this.srcImageFile = item.image;
    },
  },
};
</script>
