<template>
  <div>
    <div class="d-flex justify-content-between mb-4 mx-4">
      <div class="flex-grow-1">
        <Breadcrumb pageTitle="Push Notifications" :breadcrumbList="breadcrumbList" />
      </div>
      <div class="float-end mt-2">
        <button
          class="btn btn-danger py-2"
          title="Push Notification"
          @click="$router.push('/admin/notification')"
        >
          Push Notification
          <i class="fa fa-eye"></i>
        </button>
      </div>
    </div>
    <div class="container-fluid">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <div class="row">
            <div class="col-md-10 col-sm-6">
              <h6 class="text-danger m-0 font-weight-bold">Notification</h6>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div>
            <div class="row">
              <div class="col-12 mb-2">
                <label for="Message">Message For</label>
                <div class="d-flex">
                  <div class="mx-2">
                    <input
                      type="radio"
                      v-model="editItem.sendFor"
                      value="all"
                      @click="clearDataMessage"
                    />
                    <label for="">All</label>
                  </div>
                  <div class="mx-5">
                    <input
                      type="radio"
                      v-model="editItem.sendFor"
                      value="centers"
                      @click="clearDataMessage"
                    />
                    <label for="">Service Center</label>
                  </div>
                  <div class="mx-5">
                    <input
                      type="radio"
                      v-model="editItem.sendFor"
                      value="drivers"
                      @click="clearDataMessage"
                    />
                    <label for="">Drivers</label>
                  </div>
                  <div class="mx-5">
                    <input
                      type="radio"
                      v-model="editItem.sendFor"
                      value="customers"
                      id=""
                      @click="clearDataMessage"
                    />
                    <label for="">Customers</label>
                  </div>
                </div>
              </div>
              <div
                class="col-12 mb-2"
                v-if="editItem.sendFor == 'centers' || editItem.sendFor == 'drivers'"
              >
                <div>
                  <input
                    type="text"
                    class="form-control"
                    ref="search"
                    placeholder="ENTER Search"
                    v-model="editItem.searchItem"
                  />
                </div>
                <div class="row mx-1 mt-3">
                  <div class="col-12">
                    <input
                      type="checkbox"
                      v-model="editItem.selectAll"
                      id="selectAll"
                      @change="selectedAllData()"
                    />
                    <label for="" @click="selectedAllData()">Select All</label>
                  </div>
                  <div
                    class="col-md-3 col-sm-1"
                    v-for="(data, index) in filterData"
                    :key="index"
                  >
                    <input
                      type="checkbox"
                      name=""
                      :id="`data${index}`"
                      @change="selectedData(data.user_id, index)"
                    />
                    <label
                      class="fw-bold"
                      @click="selectedData(data.user_id, index)"
                      >{{ data.name }}</label
                    >
                  </div>
                </div>
              </div>
              <div class="col-12 mb-2">
                <label class="form-label">Title<span class="text-danger">*</span></label>
                <input
                  type="text"
                  ref="notification_name"
                  class="form-control"
                  placeholder="ENTER TITLE"
                  v-model="editItem.notification_title"
                  :class="errorItem.notification_title != '' ? 'error-field' : ''"
                />
                <span
                  class="error-label"
                  v-if="errorItem.notification_title != ''"
                  v-text="errorItem.notification_title"
                ></span>
              </div>
              <div class="col-12 mb-2">
                <label class="form-label">Description</label>
                <textarea
                  class="form-control"
                  :class="errorItem.notification_description != '' ? 'is-invalid' : ''"
                  label=""
                  placeholder="Enter description here"
                  v-model="editItem.notification_description"
                ></textarea>
                <span
                  class="error-label"
                  v-if="errorItem.notification_description != ''"
                  v-text="errorItem.notification_description"
                ></span>
              </div>
            </div>
          </div>
          <div class="mt-2">
            <button type="submit" class="btn btn-danger w-100" @click="submitData">
              SEND
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
var toast;
export default {
  data() {
    return {
      breadcrumbList: [
        {
          to: { name: "admin.dashboard" },
          name: "Home",
        },
        {
          to: { name: "admin.notification" },
          name: "Push Notifications",
        },
        {
          name: "Create",
        },
      ],
      editItem: {},
      errorItem: {
        notification_title: "",
        notification_description: "",
      },
      isFormValid: false,
      messageForData: [],
      messageIds: [],
    };
  },
  computed: {
    filterData() {
      if (this.editItem.searchItem == null || this.editItem.searchItem == "") {
        return this.messageForData;
      } else {
        return this.messageForData.filter((ele) => {
          if (ele.name.includes(this.editItem.searchItem)) {
            return ele;
          }
        });
      }
    },
  },
  watch: {
    "editItem.sendFor": function (val) {
      if (val == "centers") {
        this.getCentres();
      } else if (val == "drivers") {
        this.getDrivers();
      }
    },
  },
  created() {
    setTimeout(() => {
      this.firstTime = false;
    }, 2500);
    if (this.$route.params.discount_id) {
      this.editedIndex = this.$route.params.discount_id;
      this.getEditData();
    }
  },
  mounted() {
    window.scrollTo(0, 0);
  },
  methods: {
    clearDataMessage() {
      this.messageForData = [];
      this.messageIds = [];
    },
    selectedAllData() {
      if (this.editItem.selectAll === true) {
        this.messageForData.forEach((element, index) => {
          this.selectedData(element.user_id, index);
        });
      }
    },
    selectedData(id, index) {
      if (this.messageIds.includes(id)) {
        this.editItem.selectAll = false;
        $("#selectAll").prop("checked", false);
        this.messageIds.splice(this.messageIds.indexOf(id), 1);
        $("#data" + index).prop("checked", false);
      } else {
        $("#data" + index).prop("checked", true);
        this.messageIds.push(id);
      }
      if (this.messageForData.length == this.messageIds.length) {
        this.editItem.selectAll = true;
        $("#selectAll").prop("checked", true);
      }
    },
    async getCentres() {
      const res = await this.callApi("get", "/api/admin/service-centers", null);

      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.messageForData = data.centers;
          this.editItem.selectAll = true;
          setTimeout(() => {
            this.messageForData.forEach((element, index) => {
              this.selectedData(element.user_id, index);
            });
          }, 1000);
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
    async getDrivers() {
      const res = await this.callApi("get", "/api/admin/drivers", null);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.messageForData = data.drivers;
          this.editItem.selectAll = true;

          setTimeout(() => {
            this.messageForData.forEach((element, index) => {
              this.selectedData(element.user_id, index);
            });
          }, 1000);
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
    validationForm() {
      if (
        this.editItem.notification_title == "" ||
        this.editItem.notification_title == null
      ) {
        this.errorItem.notification_title = "Title is required";
        this.isFormValid = false;
      } else {
        this.errorItem.notification_title = "";
        this.isFormValid = true;
      }
      if (
        this.editItem.notification_description == "" ||
        this.editItem.notification_description == null
      ) {
        this.errorItem.notification_description = "Description is required";
        this.isFormValid = false;
      } else {
        this.errorItem.notification_description = "";
        this.isFormValid = true;
      }
      return this.isFormValid;
    },
    async submitData() {
      var isValid = await this.validationForm();
      if (!isValid) {
        return;
      }
      let formData = new FormData();
      formData.append("title", this.editItem.notification_title);
      formData.append("description", this.editItem.notification_description);
      if (this.editItem.sendFor == "centers") {
        if (this.editItem.selectAll) {
          formData.append("message", "center");
        } else {
          formData.append("message", this.messageIds);
        }
      } else if (this.editItem.sendFor == "drivers") {
        if (this.editItem.selectAll) {
          formData.append("message", "driver");
        } else {
          formData.append("message", this.messageIds);
        }
      } else {
        formData.append("message", this.editItem.sendFor);
      }

      const res = await this.callApi("post", `/api/admin/send/notification`, formData);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          });
          setTimeout(() => {
            this.$router.push("/admin/notification");
          }, 3000);
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
  },
};
</script>
