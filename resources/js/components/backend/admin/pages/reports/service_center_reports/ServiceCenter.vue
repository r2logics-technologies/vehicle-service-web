<template>
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="container-xxl flex-grow-1 container-p-y mx-1">
              <Breadcrumb pageTitle="Reports" :breadcrumbList="breadcrumbList" />
    </div>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Service Centers List</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- All Card  -->
          <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-info text-capitalize mb-1">
                      Total Service Centers
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-info">
                      {{ states.allCenters }}
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fa fa-store fa-2x text-info"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Active Card -->
          <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div
                      class="text-xs font-weight-bold text-success text-capitalize mb-1"
                    >
                      Active Service Centers
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-success">
                      {{ states.actCenters }}
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-store fa-2x text-success"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Inactive Card -->
          <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-gray text-capitalize mb-1">
                      Inactive Service Centers
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-secondary">
                      {{ states.inActCenters }}
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-store fa-2x text-secondary"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

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
        to: { name: "admin.dashboard" },
        name: "Home",
      },
      {
        name: "Service Center Report",
      },
    ],
    service_centers: [],
    states: [],
    noData: true,
    isDetail:false,
    tableHead: {
      items: {
        item1: { label: "Sr.No." },
        item2: { label: "image" },
        item3: { label: "Owner Detail" },
        item4: { label: "Address" },
        item5: { label: "Registration Date" },
        item6: { label: "Status" },
      },
      //   actions: false,
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
      const res = await this.callApi("get", "/api/admin/reports/service_centers", null);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.service_centers = data.centers;
          this.states = data.states;
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
      this.log(this.service_centers);
      this.service_centers.forEach((element) => {
        this.tableData.push({
          item: element,
          data: {
            item1: element.shop_id,
            item2: {
              type: "file",
              path: element.image,
              height: "70px",
            },
            item3: `<div><span title="${element.shop_id}"><i class="fa fa-store"></i> - ${element.shop_name}</span><br /><span><i class="fa fa-user"></i> - ${element.shop_owner}</span><br /><span style="font-size:13px; class="ms-2"><i class='fa fa-phone'></i> - ${element.shop_mobile}</span><br /><span class="ms-1 text-lowercase"><span class="fw-bold">@ </span > - ${element.shop_email}</span></div>`,
            item4: `<span><i class="fa fa-address-card"></i> ${element.shop_address_line1}</span><span> ${element.shop_address_line2 == null ? '' : ', '+element.shop_address_line2 }</span><br /><span> ${element.shop_city}</span><span>, ${element.shop_state}</span><span>, ${element.shop_postcode}</span>`,
            item5: Vue.filter('formatDate')(element.shop_create_at),
            item6: element.shop_status,
          },
          action: true,
        });
      });
      this.$refs.table.loadTable();
    },
    viewDetailPage(data, index) {
            this.$router.push(`/admin/reports/service-center_details/${data.shop_id}`);
    },
  },
};
</script>
