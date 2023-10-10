<template>
  <div>
    <div class="d-flex justify-content-between mb-4 mx-4">
      <!-- <h1 class="h3 mb-2 text-gray-800">Centers</h1> -->
      <div class="flex-grow-1">
        <Breadcrumb pageTitle="Packages" :breadcrumbList="breadcrumbList" />
      </div>
      <div class="float-end mt-2">
        <button class="btn btn-danger rounded-circle py-2" title="Add More"
          @click="$router.push('/admin/packages/create')">
          <i class="fa fa-plus"></i>
        </button>
      </div>
    </div>
    <div class="container-fluid">
      <!-- Page Heading -->

      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-danger">Packages List</h6>
        </div>
        <div class="card-body">
          <div v-show="!noData" class="table-responsive">
            <Table ref="table" tableId="package_table" :tableHead="tableHead" :tableData="tableData" />
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
  data: () => ({
    breadcrumbList: [
      {
        to: { name: "admin.dashboard" },
        name: "Home",
      },
      {
        name: "Packages",
      },
    ],
    packages: [],
    noData: true,
    tableHead: {
      items: {
        item1: { label: "Sr.No." },
        item2: { label: "Package Name" },
        item3: { label: "Package type" },
        item4: { label: "Drurations" },
        item5: { label: "Prices" },
        item6: { label: "Benefits/Limit" },
        item7: { label: "Status" },
      },
      actions: true,
    },
    tableData: [],
  }),
  mounted() {
    window.scrollTo(0, 0);
  },
  created() {
    toast = Toast.fire({
      icon: "warning",
      title: "Loadding Data...",
      timer: 0,
    });
    this.showData();
  },
  computed: {},
  methods: {
    async showData() {
      const res = await this.callApi("get", "/api/admin/packages", null);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.packages = data.packages;
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
      this.packages.forEach((element) => {
        this.tableData.push({
          item: element,
          data: {
            item1: element.package_id,
            item2: element.package_name,
            item3: element.package_type,
            item4: element.package_duration + " " + (element.package_duration == 1 ? "Month" : "Months"),
            item5: "₹" + element.package_price,
            item6: element.package_features_name,
            item7: element.package_status,
          },
          action: {
            edit: true,
            delete: true,
            status: element.package_status,
          },
        });
      });

      this.$refs.table.loadTable();
    },
    edit(item) {
      this.$router.push(`/admin/packages/edit/${item.package_id}/form`);
    },
    async deleteData(item, index, status) {
      var data = new FormData();
      data.append("status", status);
      const res = await this.callApi(
        "post",
        `/api/admin/packages/change/status/${item.package_id}`,
        data
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          if (status == "deleted") {
            this.packages.splice(index, 1);
            this.tableData.splice(index, 1);
            if (!this.packages.length) {
              this.noData = true;
            }
          } else {
            this.packages[index] = data.package;
            this.tableData[index].data.item7 = data.package.package_status;
            this.tableData[index].action.status = data.package.package_status;
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
