<template>
  <div class="container-fluid">
    <div class="container-xxl flex-grow-1 container-p-y">
      <Breadcrumb pageTitle="Pending Services" :breadcrumbList="breadcrumbList" />
    </div>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Pending Services</h6>
      </div>
      <div class="card-body">
        <div v-show="!noData" class="table table-responsive">
          <Table
            ref="table"
            tableId="customer_table"
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
        to: { name: "service-center.dashboard" },
        name: "Home",
      },
      {
        name: "Pending Services",
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
        item7: { label: "Pickup Date & Time" },
        item8: { label: "Payment Detail" },
        item9: { label: "Action" },
        item10: { label: "" },
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
        "/api/service-center/service_center_bookings/pending_bookings",
        null
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.bookings = data.pending_bookings;
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
              element.booking_user.user_email == null
                ? ""
                : `<br /><span class="ms-1 text-lowercase"><span class="fw-bold">@</span> - ${element.booking_user.user_email}</span>`
            }</div><span><i class="fa fa-address-card"></i> ${
              element.booking_address.address_line1
            }</span><span> ${
              element.booking_address.address_line2 == null
                ? ""
                : ", " + element.booking_address.address_line2
            }</span><br /><span> ${element.booking_address.city}</span><span>, ${
              element.booking_address.state
            }</span><span>, ${element.booking_address.pincode}</span>`,
            item4: `<span><i class="fa fa-${
              element.booking_veh_type == "four_wheeller" ? "car" : "motorcycle"
            }"></i> ${element.booking_veh_name}</span> <br /><span> ${
              element.booking_veh_num
            }</span>`,
            item5: `<span><i class="fa fa-list-alt"></i>  ${element.booking_package.package_name}</span><br/><span>₹${element.booking_package.package_price}, ${element.booking_package.package_duration} months</span>`,
            item6: Vue.filter("formatDateTime")(element.booking_picked_at),
            item7: Vue.filter("formatDateTime")(element.booking_date),
            item8: `<span>status - ${element.booking_payment_status}</span>${
              element.booking_payment_status == "paid"
                ? `<br/><span>method - ${element.booking_payment_method}</span>`
                : ""
            }`,
            item9: {
              type: "action",
              options: {
                method: this.acceptOrder,
                style: "background:none;border:none",
                icon: "<i class='fa fa-check text-success'></i>",
              },
            },
            item10: {
              type: "action",
              options: {
                method: this.changeStatus,
                style: "background:none;border:none",
                icon: "<i class='fa fa-times text-danger'></i>",
              },
            },
          },
          action: false,
        });
      });
      this.$refs.table.loadTable();
    },
    acceptOrder(item, index) {
      var status = "accepted";
      SwalCustomBtn.fire({
        text: "do you want to accept this service!",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "No",
        confirmButtonText: "Yes",
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          this.preparation = result.value;
          this.statusUpdate(item, status, index);
        }
      });
    },
    changeStatus(item, index) {
      var status = "rejected";
    //   const { value: text } = await Swal.fire({
    //     input: "textarea",
    //     inputLabel: "Message",
    //     inputPlaceholder: "Type your message here...",
    //     inputAttributes: {
    //       "aria-label": "Type your message here",
    //     },
    //     showCancelButton: true,
    //   });

    SwalCustomBtn.fire({
        input: "textarea",
        inputLabel: "Rejection Notes ",
        inputPlaceholder: "Type your message here...",
        inputAttributes: {
          "aria-label": "Type your message here",
        },
        text: "do you want to reject this service!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
            const text = result.value
          this.statusUpdate(item, status, index,text);
        }
      });
    },
    async statusUpdate(item, status, index,text) {
      var data = new FormData();
      data.append("status", status);
      data.append("rejection_notes", text);
      const res = await this.callApi(
        "post",
        `/api/service-center/customers/booking/status/${item.booking_id}`,
        data
      );
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.bookings[index] = data.booking;
          this.bookings.splice(index, 1);
          this.tableData.splice(index, 1);
          if (this.bookings.length <= 0) {
            this.noData = true;
          } else {
            this.initTable();
          }
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          });
        } else {
          toast = Toast.fire({
            icon: "error",
            title: "something want wrong",
            timer: 2500,
          });
        }
      } else {
        toast = Toast.fire({
          icon: "error",
          title: "something want wrong",
          timer: 2500,
        });
      }
    },
  },
};
</script>
