<template>
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <Breadcrumb pageTitle="Customers" :breadcrumbList="breadcrumbList" />
    </div>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Customers List</h6>
      </div>
      <div class="card-body">
        <div v-show="!noData" class="table table-responsive">
          <Table
            ref="table"
            tableId="customer_table"
            :tableHead="tableHead"
            :tableData="tableData"
            :isDetail="true"
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
                to: { name: "service-center.dashboard" },
                name: "Home",
            },
            {
                name: "Customers",
            },
        ],
    customers: [],
    noData: true,
    tableHead: {
      items: {
        item1: { label: "Sr.No." },
        item2: { label: "Customer Detail" },
        item3: { label: "Services" },
        item4: { label: "Registration Date" },
        item5: { label: "Status" },
      },
    },
    tableData: [],
    selectedDate: "",
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
  methods: {
    async showData() {
      const res = await this.callApi("get", "/api/service-center/customers", null);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.customers = data.customers;
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
      this.customers.forEach((element) => {
        this.tableData.push({
          item: element,
          data: {
            item1: element.user_id,

            item2: `<div><span title="${element.id}"><i class="fa fa-user"></i> - ${element.user_name}</span><br /><span style="font-size:13px; class="ms-2"><i class='fa fa-phone'></i> - ${element.user_mobile}</span><br /><span class="ms-1 text-lowercase"><span class="fw-bold">@ </span > - ${element.user_email}</span></div>`,
            item3: element.center_booking_count != null ? element.center_booking_count : "",
            item4: Vue.filter("formatDate")(element.user_created_at),
            item5: element.user_status,
          },
          action: false,
        });
      });
      this.$refs.table.loadTable();
    },
    viewDetailPage(data, index) {
      if (data.center_booking_count==0) {
        SwalCustombtn.fire(
          "No Service",
          "Customer don't hame any service!",
          "warning"
        );
      }else {
        this.$router.push(`/service-center/reports/customers/${data.id}`);
      }
    },
  },
};
</script>
