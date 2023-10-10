<template>
  <div>
    <div class="container-fluid">
      <div class="d-flex justify-content-between mb-4 mx-1">
        <div class="flex-grow-1">
          <Breadcrumb pageTitle="Services" :breadcrumbList="breadcrumbList" />
        </div>
        <div class="float-end mt-2">
          <button
            class="btn btn-danger rounded-circle py-2"
            title="ADD MORE"
            @click="$router.push('/admin/services/create')"
          >
            <i class="fa fa-plus"></i>
          </button>
        </div>
      </div>
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-danger">Services List</h6>
        </div>
        <div class="card-body">
          <div v-show="!noData" class="table table-responsive">
            <Table
              ref="table"
              tableId="services_table"
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
  data: () => ({
    services: [],
    noData: true,
    tableHead: {
      items: {
        item1: { label: "Sr.No." },
        item2: { label: "image" },
        item3: { label: "Service Name" },
        item4: { label: "Service Price" },
        item5: { label: "Category" },
        item6: { label: "Status" },
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
        name: "Services",
      },
    ],
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
      const res = await this.callApi("get", "/api/admin/services", null);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.services = data.services;
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
      this.log(this.services);
      this.services.forEach((element) => {
        this.tableData.push({
          item: element,
          data: {
            item1: element.service_id,
            item2: {
              type: "file",
              path: element.service_icon,
              height: "50px",
            },
            item3: element.service_name,
            item4: `₹${element.service_price}`,
            item5: element.service_category_name,
            item6: element.service_status,
          },
          action: {
            edit: true,
            delete: true,       
            status: element.service_status,
          },
        });
      });
      this.$refs.table.loadTable();
    },
    edit(item) {
      this.$router.push(`/admin/services/edit/${item.service_id}/form`);
    },
    async deleteData(item, index, status) {
      var data = new FormData();
      data.append("status", status);
      const res = await this.callApi(
        "post",
        `/api/admin/services/change/status/${item.service_id}`,
        data
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          if (status == "deleted") {
            this.service.splice(index, 1);
            this.tableData.splice(index, 1);
            if (!this.service.length) {
              this.noData = true;
            }
          } else {
            this.services[index] = data.service;
            this.tableData[index].data.item4 = data.service.service_status;
            this.tableData[index].action.status = data.service.service_status;
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
