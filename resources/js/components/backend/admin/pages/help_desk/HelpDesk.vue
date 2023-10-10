<template>
  <div>
    <div class="container-fluid">
      <!-- Page Heading -->
      <div class="container-xxl container-p-y">
        <Breadcrumb pageTitle="Help Desk" :breadcrumbList="breadcrumbList" />
      </div>
      <!-- /Breadcrumb -->
      <div class="card shadow mb-4">
        <div class="card-header">
          <h6 class="m-0 font-weight-bold text-danger">Add Contact</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 col-sm-12">
              <label for="exampleFormControlSelect1" class="form-label">
                Contact Type
                <span class="text-danger">*</span>
              </label>
              <select
                name=""
                id=""
                class="form-control"
                v-model="editItem.help_desk_type"
                :class="errorItem.help_desk_type != '' ? 'is-invalid' : ''"
              >
                <option selected disabled value="">--SELECT CONTACT TYPE--</option>
                <option value="email">EMAIL</option>
                <option value="mobile">MOBILE</option>
              </select>
              <span
                class="error-label"
                v-if="errorItem.help_desk_type != ''"
                v-text="errorItem.help_desk_type"
              ></span>
            </div>
            <div class="col-md-6 col-sm-12">
              <label for="" class="form-label">
                Contect
                <span class="text-danger">*</span>
              </label>
              <input
                type="text"
                class="form-control"
                :class="errorItem.help_desk_contect != '' ? 'is-invalid' : ''"
                placeholder="ENTER CONTECT HERE"
                v-model="editItem.help_desk_contect"
              />
              <span
                class="error-label"
                v-if="errorItem.help_desk_contect != ''"
                v-text="errorItem.help_desk_contect"
              ></span>
            </div>
            <div class="col-12 py-3 pt-4">
              <button class="btn btn-danger w-100" @click="submitData()">SUBMIT</button>
            </div>
          </div>
        </div>
      </div>
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-danger">Contact List</h6>
        </div>
        <div class="card-body">
          <div v-show="!noData" class="table table-responsive">
            <Table
              ref="table"
              tableId="help_desk_table"
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
  </div>
</template>

<script>
var toast;
export default {
  data() {
    return {
      helpDesks: [],
      editItem: {
        help_desk_type: "",
      },
      isFormValid: false,
      errorItem: {
        help_desk_type: "",
        help_desk_contect: "",
      },
      editedIndex: -1,
      noData: true,
      tableHead: {
        items: {
          item1: { label: "Sr.No." },
          item2: { label: "Content Type" },
          item3: { label: "Content" },
          item4: { label: "Status" },
        },
        actions: true,
      },
      tableData: [],
      breadcrumbList: [
        {
          to: { name: "admin.dashboard" },
          name: "Home",
        },
        {
          name: "Help Desk",
        },
      ],
    };
  },
  mounted() {
    window.scrollTo(0, 0);
    toast = Toast.fire({
      icon: "warning",
      title: "Loading Data..",
      timer: 0,
    });
    this.showData();
  },

  methods: {
    edit(item, index) {
      this.editItem = item;
      this.editedIndex = item.help_desk_id;
      this.editIndex = index;
    },
    async showData() {
      const res = await this.callApi("get", "/api/admin/help_desk", null);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.helpDesks = data.help_desks;
          this.log(this.helpDesks);
          this.initTable();
          toast = Toast.fire({
            icon: "success",
            title: "Data Founded",
            timer: 5000,
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
      toast.close();
    },
    initTable() {
      this.noData = false;

      this.tableData = [];
      this.helpDesks.forEach((element) => {
        this.tableData.push({
          item: element,
          data: {
            item1: element.help_desk_id,
            item2: element.help_desk_type,
            item3: element.help_desk_contect,
            item4: element.help_desk_status,
          },
          action: {
            edit: true,
            delete: true,
            status: element.help_desk_status,
          },
        });
      });
      this.$refs.table.loadTable();
    },
    formValidation() {
      if (this.editItem.help_desk_type == null || this.editItem.help_desk_type == "") {
        this.errorItem.help_desk_type = "Contect type is required";
        this.isFormValid = false;
      } else {
        this.isFormValid = true;
      }

      if (
        this.editItem.help_desk_contect == null ||
        this.editItem.help_desk_contect == ""
      ) {
        this.errorItem.help_desk_contect = "Contect type is required";
        this.isFormValid = false;
      } else {
        this.isFormValid = true;
      } 

      return this.isFormValid;
    },
    async submitData() {
      var isFormValid = this.formValidation();
      if (!isFormValid) {
        return;
      }

      Toast.fire({
        icon: "warning",
        title: "Uploading...",
        timer: 0,
      });

      var formData = new FormData();
      formData.append("edited", this.editedIndex);
      formData.append("type", this.editItem.help_desk_type);
      formData.append("contect", this.editItem.help_desk_contect);

      const res = await this.callApi(
        "post",
        "/api/admin/help_desk/save/update",
        formData
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
          this.updateData(data.help_desk);
          this.clearForm();
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
      this.editItem = {
        help_desk_type: "",
        help_desk_contect: "",
      };
        this.isFormValid = true;
      this.editedIndex = -1;
    },
    updateData(data) {
      if (this.editedIndex == -1) {
        this.log(data);
        this.helpDesks.push(data);
        this.noData = false;
        this.tableData.push({
          item: data,
          data: {
            item1: data.help_desk_id,
            item2: data.help_desk_type,
            item3: data.help_desk_contect,
            item4: data.help_desk_status,
          },
          action: {
            edit: true,
            delete: true,
            status: data.help_desk_status,
          },
        });
      } else {
        this.helpDesks[this.editIndex] = data;
        this.tableData[this.editIndex].data.item1 = data.help_desk_id;
        this.tableData[this.editIndex].data.item2 = data.help_desk_type;
        this.tableData[this.editIndex].data.item3 = data.help_desk_contect;
        this.tableData[this.editIndex].data.item4 = data.help_desk_status;
      }
      this.editIndex = -1;
      this.editedIndex = -1;
    },
    async deleteData(item, index, status) {
      var data = new FormData();
      data.append("status", status);
      const res = await this.callApi(
        "post",
        `/api/admin/help_desk/change/status/${item.help_desk_id}`,
        data
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          if (status == "deleted") {
            this.helpDesks.splice(index, 1);
            this.tableData.splice(index, 1);
            if (!this.helpDesks.length) {
              this.noData = true;
            }
          } else {
            this.helpDesks[index] = data.helpDesk;
            this.tableData[index].data.item4 = data.helpDesk.help_desk_status;
            this.tableData[index].action.status = data.helpDesk.help_desk_status;
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
