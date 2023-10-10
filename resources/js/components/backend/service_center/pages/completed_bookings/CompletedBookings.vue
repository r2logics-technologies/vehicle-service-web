<template>
  <div class="container-fluid">
    <div class="container-xxl flex-grow-1 container-p-y">
      <Breadcrumb pageTitle="Services History" :breadcrumbList="breadcrumbList" />
    </div>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Bookings List</h6>
      </div>
      <div class="card-body">
        <div v-show="!noData" class="table table-responsive">
          <Table
            ref="pendingTable"
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
                name: "Services History",
            },
        ],
    bookings: [],
    states: [],
    noData: true,
    tableHead: {
      items: {
        item1: { label: "Sr.No." },
        item2: { label: "Booking .No." },
        item3: { label: "Customer Detail" },
        item4: { label: "Vehicle detail" },
        item5: { label: "Package Detail" },
        item6: { label: "Booking Date & Time" },
        item7: { label: "Service Complet Time"},
        item8: { label: "Payment Detail" },
      },
      actions: false,
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
  computed: {
    totalCashPrice() {
      if (this.states.cashBooking != null) {
        return this.states.cashBooking.reduce((total, booking) => {
          return total + parseInt(booking.package.price);
        }, 0);
      } else {
        return 0;
      }
    },
    totalOnlinePrice() {
      if (this.states.onlineBooking != null) {
        return this.states.onlineBooking.reduce((total, booking) => {
          return total + parseInt(booking.package.price);
        }, 0);
      } else {
        return 0;
      }
    },
  },
  methods: {
    async showData() {
      toast.close();
      const res = await this.callApi(
        "get",
        "/api/service-center/service_center_bookings/completed_bookings",
        null
      );
      this.log(res);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.bookings = data.completed_bookings;
          this.states = data.states;
          this.log(this.states);
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
      this.log(this.bookings);
      this.bookings.forEach((element) => {
        this.tableData.push({
          item: element,
          data: {
            item1: element.booking_id,
            item2: element.booking_number,
            item3: `<div><span title="${
              element.booking_user.id
            }"><i class="fa fa-user"></i> ${
              element.booking_user.user_name
            }</span><br /><span style="font-size:13px;" class="ms-2"><i class="fa fa-phone"></i> - ${
              element.booking_user.user_mobile
            }</span>${
              element.booking_user.user_email == ''
                ? ""
                : `<br /><span class="ms-1 text-lowercase"><span class="fw-bold">@</span> - ${element.booking_user.user_email}</span>`
            }</div>`,
            item4: `<span><i class="fa fa-${
              element.booking_veh_type == "four_wheeller" ? "car" : "motorcycle"
            }"></i> ${element.booking_veh_name}</span> <br /><span> ${
              element.booking_veh_num
            }</span>`,
            item5: `<span><i class="fa fa-list-alt"></i>  ${element.booking_package.package_name}</span><br/><span>₹${element.booking_package.package_price}, ${element.booking_package.package_duration} months</span>`,
            item6: Vue.filter("formatDateTime")(element.booking_picked_at),

            item7: Vue.filter("formatDateTime")(element.booking_completed_at),
            item8: `<span>status - ${element.booking_payment_status}</span>${
              element.booking_payment_status == "paid"
                ? `<br/><span>method - ${element.booking_payment_method}</span>`
                : ""
            }`,
          },
          action: false,
        });
      });
      this.$refs.pendingTable.loadTable();
    },
    viewDetailPage(item, index) {
      this.$router.push(`/service-center/reports/customer_invoice/${item.booking_id}`);
    },
  },
};
</script>
