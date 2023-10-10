<template>
  <div class="container-fluid">
    <div class="container-xxl flex-grow-1 container-p-y">
            <Breadcrumb
                pageTitle = "Banners"
                :breadcrumbList="breadcrumbList"
            />
    </div>
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Add Banner</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 m-auto col-sm-12">
            <div class="border rounded p-2 text-center position-relative">
              <img :src="getImageUrl" style="height: 100px; max-width: 100%" />
              <input
                type="file"
                class="image-input"
                accept="image/jpeg, image/jpg, image/png, application/pdf"
                @change="selectImage($event)"
              />
              <div>
                <label class="mt-2">Banner Image</label>
              </div>
            </div>
            <span
              class="text-center text-danger fa-1"
              v-if="errorItem.image != ''"
              v-text="errorItem.image"
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
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Banner List</h6>
      </div>
      <div class="card-body">
        <div v-show="!noData" class="table table-responsive">
          <Table
            ref="table"
            tableId="banner_table"
            :tableHead="tableHead"
            :tableData="tableData"
          />
        </div>
        <div v-show="noData">
          <NoData />
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
                name: "Banner",
            },
        ],
    srcImageFile: null,
    imageFilePath: null,
    banners: [],
    noData: true,
    tableHead: {
      items: {
        item1: { label: "Sr.No." },
        item2: { label: "Banner" },
        item3: { label: "Status" },
      },
      actions: true,
    },
    tableData: [],
    errorItem: {
      image: "",
    },
  }),
  mounted() {
    window.scrollTo(0, 0);
  },
  created() {
    toast = Toast.fire({
      icon: "warning",
      title: "Loading Data...",
      timer: 0,
    });
    this.showData();
  },
  computed: {
    getImageUrl: function () {
      return this.srcImageFile != null && this.srcImageFile != ""
        ? this.srcImageFile
        : "/assets/img/upload.png";
    },
  },
  methods: {
    validationForm() {
      if (this.srcImageFile == null || this.srcImageFile == "") {
        this.errorItem.image = "Please select image";
        this.isValid = false;
      } else {
        this.isValid = true;
      }
      return this.isValid;
    },
    async showData() {
      const res = await this.callApi("get", "/api/admin/banners", null);
      // this.log(res);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.banners = data.banners;
          this.initTable();
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
      this.$refs.table.loadTable();
      toast.close();
    },
    initTable() {
      this.noData = false;
      this.tableData = [];
      this.banners.forEach((element) => {
        this.tableData.push({
          item: element,
          data: {
            item1: element.banner_id,
            item2: {
              type: "file",
              path: element.banner_image,
              height: "70px",
            },
            item3: element.banner_status,
          },
          action: {
            edit: false,
            delete: true,
            status: element.banner_status,
          },
        });
      });
      // this.log("Table ", this.tableData);
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
    async submitData() {
      var isValid = await this.validationForm();
      if (!isValid) return;

      Toast.fire({
        icon: "warning",
        title: "Uploading...",
        timer: 0,
      });
      var formData = new FormData();
      formData.append(
        "image",
        this.imageFilePath != null && this.imageFilePath != "" ? this.imageFilePath : null
      );
      const res = await this.callApi(
        "post",
        "/api/admin/banners/save/update",
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
            this.clearForm();
            this.banners.push(data);
            this.noData = false;
            this.tableData.push({
              item: data.banner,
              data: {
                item1: data.banner.banner_id,
                item2: {
                  type: "file",
                  path: data.banner.banner_image,
                  height: "70px",
                },
                item3: data.banner.banner_status,
              },
              action: {
                edit: false,
                delete: true,
                status: data.banner.banner_status,
              },
            });
            this.log(this.tableData);
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
      this.srcImageFile = null;
      this.imageFilePath = null;
    },
    async deleteData(item, index, status) {
      var data = new FormData();
      data.append("status", status);
      const res = await this.callApi(
        "post",
        `/api/admin/banners/change/status/${item.banner_id}`,
        data
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          if (status == "deleted") {
            this.banners.splice(index, 1);
            this.tableData.splice(index, 1);
            if (!this.banners.length) {
              this.noData = true;
            }
          } else {
            this.banners[index] = data.banner;
            this.tableData[index].data.item3 = data.banner.banner_status;
            this.tableData[index].action.status = data.banner.banner_status;
          }
          SwalCustomBtn.fire(
            status == "deleted" ? "Deleted!" : "Status Change",
            data.message,
            "success"
          );
        }
      }
    },
  },
};
</script>
