<template>
<div>

    <div class="container-fluid">
      <!-- Page Heading -->
      <div class="d-flex justify-content-between mb-4 mx-1">
      <!-- <h1 class="h3 mb-2 text-gray-800">Centers</h1> -->
      <div class="flex-grow-1">
        <Breadcrumb pageTitle = "Drivers" :breadcrumbList = "breadcrumbList"/>
      </div>
        <div class="float-end mt-2">
          <button
            class="btn btn-danger rounded-circle py-2"
            title="ADD MORE"
            @click="$router.push('/admin/drivers/create')"
          >
            <i class="fa fa-plus"></i>
          </button>
        </div>
      </div>
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-danger">Drivers List</h6>
        </div>
        <div class="card-body">
          <div v-show="!noData" class="table table-responsive">
            <Table
              ref="table"
              tableId="driver_table"
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
    drivers: [],
    noData: true,
    tableHead: {
      items: {
        item1: { label: "Sr.No." },
        item2: { label: "image" },
        item3: { label: "Driver Detail" },
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
                name: "Delivery Mans",
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
      const res = await this.callApi("get", "/api/admin/drivers", null);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.drivers = data.drivers;
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
      this.log(this.drivers);
      this.drivers.forEach((element) => {
        this.tableData.push({
          item: element,
          data: {
            item1: element.driver_id,
            item2: {
              type: "file",
              path: element.driver_avatar,
              height: "70px",
            },
            item3: `<div><span title="${element.driver_id}"><i class="fa fa-user"></i> - ${element.driver_name}</span><br /><span style="font-size:13px; class="ms-2"><i class='fa fa-phone'></i> - ${element.driver_mobile}</span><br /><span class="ms-1 text-lowercase"><span class="fw-bold">@ </span > - ${element.driver_email}</span></div>`,
            item4: element.driver_status,
          },
          action: {
            edit: true,
            delete: true,
            status: element.driver_status,
          },
        });
      });
      // this.log("Table ", this.tableData);
      this.$refs.table.loadTable();
    },
    edit(item) {
      this.$router.push(`/admin/drivers/edit/${item.driver_id}/form`);
    },
    async deleteData(item, index, status) {
      var data = new FormData();
      data.append("status", status);
      const res = await this.callApi(
        "post",
        `/api/admin/drivers/change/status/${item.driver_id}`,
        data
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          if (status == "deleted") {
            this.drivers.splice(index, 1);
            this.tableData.splice(index, 1);
            if (!this.drivers.length) {
              this.noData = true;
            }
          } else {
            this.drivers[index] = data.driver;
            this.tableData[index].data.item4 = data.driver.driver_status;
            this.tableData[index].action.status = data.driver.driver_status;
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
