<template>
<div>
    <div class="container-xxl flex-grow-1 container-p-y mx-4">
            <Breadcrumb
              pageTitle="Categories"
                :breadcrumbList="breadcrumbList"
            />
    </div>
    <div class="container-fluid">
      <Create ref="create_component" />
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-danger">Category List</h6>
        </div>
        <div class="card-body">
          <div v-show="!noData" class="table table-responsive">
            <Table
              ref="table"
              tableId="category_table"
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
import Create from "./Create.vue";
export default {
  components: { Create },
  data: () => ({
    categories: [],
    noData: true,
    tableHead: {
      items: {
        item1: { label: "Sr.No." },
        item2: { label: "Name" },
        item3: { label: "Status" },
      },
      actions: true,
    },
    tableData: [],
    editIndex: -1,
    breadcrumbList: [
            {
                to: { name: "admin.dashboard" },
                name: "Home",
            },
            {
                name: " Categories",
            },
        ],
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
    centerFilter: function () {
      if (this.searchItem && this.searchItem != null && this.searchItem != "") {
        return this.tableData.filter((center) => {
          return (
            center.data.item2.toLowerCase().indexOf(this.searchItem.toLowerCase()) !==
              -1 ||
            center.data.item3.toLowerCase().indexOf(this.searchItem.toLowerCase()) !== -1
          );
        });
      } else {
        return this.tableData;
      }
    },
  },
  methods: {
    async showData() {
      const res = await this.callApi("get", "/api/admin/categories", null);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.categories = data.categories;
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
      this.categories.forEach((element) => {
        this.tableData.push({
          item: element,
          data: {
            item1: element.category_id,
            item2: element.category_name,
            item3: element.category_status,
          },
          action: {
            edit: true,
            delete: true,
            status: element.category_status,
          },
        });
      });
    },
    edit(item, index) {
      this.$refs.create_component.edit(item);
      this.editIndex = index;
    },
    updateData(data) {
      if (this.editIndex == -1) {
        this.categories.push(data);
      this.noData = false;
        this.tableData.push({
          item: data,
          data: {
            item1: data.category_id,
            item2: data.category_name,
            item3: data.category_status,
          },
          action: {
            edit: true,
            delete: true,
            status: data.category_status,
          },
        });
      } else {
        this.categories[this.editIndex] = data;
        this.tableData[this.editIndex].data.item1 = data.category_id;
        this.tableData[this.editIndex].data.item2 = data.category_name;
        this.tableData[this.editIndex].data.item3 = data.category_status;
      }
      this.editIndex = -1;
    },
    async deleteData(item, index, status) {
      var data = new FormData();
      data.append("status", status);
      const res = await this.callApi(
        "post",
        `/api/admin/categories/change/status/${item.category_id}`,
        data
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          if (status == "deleted") {
            this.categories.splice(index, 1);
            this.tableData.splice(index, 1);
            if (!this.categories.length) {
              this.noData = true;
            }
          } else {
            this.categories[index] = data.category;
            this.tableData[index].data.item3 = data.category.category_status;
            this.tableData[index].action.status = data.category.category_status;
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
