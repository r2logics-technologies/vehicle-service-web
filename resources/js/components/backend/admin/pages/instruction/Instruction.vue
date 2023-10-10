<template>
  <div>
    <div class="container-fluid">
      <!-- Page Heading -->
      <div class="container-xxl container-p-y">
        <Breadcrumb pageTitle="Service Instruction" :breadcrumbList="breadcrumbList" />
      </div>
      <!-- /Breadcrumb -->
      <div class="card shadow mb-4">
        <div class="card-header">
          <h6 class="m-0 font-weight-bold text-danger">Add Instruction</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-12">
              <label for="" class="form-label">
                Instruction Description
                <span class="text-danger">*</span>
              </label>
              <input
                type="text"
                class="form-control"
                :class="errorItem.instruction_description != '' ? 'is-invalid' : ''"
                placeholder="Enter instruction here"
                v-model="editItem.instruction_description"
              />
              <span
                class="error-label"
                v-if="errorItem.instruction_description != ''"
                v-text="errorItem.instruction_description"
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
          <h6 class="m-0 font-weight-bold text-danger">Instruction List</h6>
        </div>
        <div class="card-body">
          <div v-show="!noData" class="table table-responsive">
            <Table
              ref="table"
              tableId="instruction_table"
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
      instructions: [],
      isFormValid: false,
      editItem: {},
      errorItem: {
        instruction_description: "",
      },
      editedIndex: -1,
      noData: true,
      tableHead: {
        items: {
          item1: { label: "Sr.No." },
          item2: { label: "Instruction Description" },
          item3: { label: "Status" },
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
          name: "Service Instruction",
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
      this.editedIndex = item.instruction_id;
      this.editIndex = index;
    },
    async showData() {
      const res = await this.callApi("get", "/api/admin/instruction", null);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.instructions = data.instructions;
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
      this.instructions.forEach((element) => {
        this.tableData.push({
          item: element,
          data: {
            item1: element.instruction_id,
            item2: element.instruction_description,
            item3: element.instruction_status,
          },
          action: {
            edit: true,
            delete: true,
            status: element.instruction_status,
          },
        });
      });
      this.$refs.table.loadTable();
    },
    formValidation() {
      if (
        this.editItem.instruction_description == null ||
        this.editItem.instruction_description == ""
      ) {
        this.errorItem.instruction_description = "Instruction required!";
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
      formData.append("description", this.editItem.instruction_description);

      const res = await this.callApi(
        "post",
        "/api/admin/instruction/save/update",
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
          this.updateData(data.instruction);
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
        instruction_description: "",
      };
      this.isFormValid = true;
      this.editedIndex = -1;
    },
    updateData(data) {
      if (this.editedIndex == -1) {
        this.noData = false;
        this.instructions.push(data);        
        this.tableData.push({
          item: data,
          data: {
            item1: data.instruction_id,
            item2: data.instruction_description,
            item3: data.instruction_status,
          },
          action: {
            edit: true,
            delete: true,
            status: data.instruction_status,
          },
        });
      } else {
        this.instructions[this.editIndex] = data;
        this.tableData[this.editIndex].data.item1 = data.instruction_id;
        this.tableData[this.editIndex].data.item2 = data.instruction_description;
        this.tableData[this.editIndex].data.item3 = data.instruction_status;
      }
      this.editIndex = -1;
      this.editedIndex = -1;
    },
    async deleteData(item, index, status) {
      var data = new FormData();
      data.append("status", status);
      const res = await this.callApi(
        "post",
        `/api/admin/instruction/change/status/${item.instruction_id}`,
        data
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          if (status == "deleted") {
            this.instructions.splice(index, 1);
            this.tableData.splice(index, 1);
            if (!this.instructions.length) {
              this.noData = true;
            }
          } else {
            this.instructions[index] = data.instruction;
            this.tableData[index].data.item3 = data.instruction.instruction_status;
            this.tableData[index].action.status = data.instruction.instruction_status;
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
