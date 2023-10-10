<template>
    <div class="container-fluid">
        <!-- <div class="d-flex justify-content-between mb-4">
            <h1 class="h3 mb-2 text-gray-800">Service Center</h1>
        </div> -->
        <div class="container-xxl container-p-y mx-1">
                  <Breadcrumb pageTitle="Reports" :breadcrumbList="breadcrumbList" />
        </div>
        <div class="card shadow">
            <div
                class="card-header py-3"
                v-if="service_center && service_center != null"
            >
                <h6 class="m-0 font-weight-bold text-danger">
                    {{ service_center.shop_name }}
                </h6>
            </div>
            <div class="row m-1">
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col text-center">
                                    <div
                                        class="h5 mb-0 font-weight-bold text-gray-800"
                                    >
                                        {{ state.all_bookings }}
                                    </div>
                                    <div
                                        class="text-xs font-weight-bold text-info text-uppercase mb-1"
                                    >
                                        Total Bookings
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col text-center">
                                    <div
                                        class="h5 mb-0 font-weight-bold text-gray-800"
                                    >
                                        {{ state.today_booking }}
                                    </div>
                                    <div
                                        class="text-xs font-weight-bold text-info text-uppercase mb-1"
                                    >
                                        Today's Bookings
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col text-center">
                                    <div
                                        class="h5 mb-0 font-weight-bold text-gray-800"
                                    >
                                        {{ state.pend_bookings }}
                                    </div>
                                    <div
                                        class="text-xs font-weight-bold text-danger text-uppercase mb-1"
                                    >
                                        Pending Bookings
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="row m-1">
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col text-center">
                                    <div
                                        class="h5 mb-0 font-weight-bold text-gray-800"
                                    >
                                        {{ state.pros_bookings }}
                                    </div>
                                    <div
                                        class="text-xs font-weight-bold text-warning text-uppercase mb-1"
                                    >
                                        In Proccess Bookings
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col text-center">
                                    <div
                                        class="col-auto mb-0 font-weight-bold text-gray-800 h5"
                                    >
                                        {{ state.comp_bookings }}
                                    </div>
                                    <div
                                        class="text-xs font-weight-bold text-success text-uppercase mb-1"
                                    >
                                        Completed Booking
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col text-center">
                                    <div
                                        class="col-auto mb-0 font-weight-bold text-gray-800 h5"
                                    >
                                        {{ state.customers }}
                                    </div>
                                    <div
                                        class="text-xs font-weight-bold text-success text-uppercase mb-1"
                                    >
                                        Customers
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="dataTables_filter">
                    <label>
                        Show by Date:
                        <input
                            type="date"
                            class="form-control"
                            v-model="selectedDate"
                        />
                    </label>
                </div>
                <div v-show="!noData" class="table table-responsive">
                    <Table
                        ref="table"
                        tableId="customer_table"
                        :tableHead="tableHead"
                        :tableData="filterData"
                        :isDetail = true
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
    data() {
        return {
            breadcrumbList: [
                {
                    to: { name: "admin.dashboard" },
                    name: "Home",
                },
                {
                    to: { name: "admin.reports.service-centers"},
                    name: "Service Center Report",
                },
                {
                    name: "Services",
                },
            ],
            bookings: [],
            state: {},
            noData: true,
            service_center: {},
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
                    item9: { label: "Status" },
                },
                //   actions: false,
            },
            selectedDate: "",
            tableData: [],
        };
    },
    computed: {
        filterData: function () {
            if (this.selectedDate != "") {
                return this.tableData.filter((customer) => {
                    var date = Vue.filter("formatDate")(this.selectedDate);
                    if (date == customer.data.item5) {
                        return customer;
                    }
                });
            } else {
                return this.tableData;
            }
        },
    },
    mounted() {
        if (this.$route.params.shop_id) {
            this.shop_id = this.$route.params.shop_id;
            console.log("iishop_iddddd", this.shop_id);
        }
        toast = Toast.fire({
            icon: "warning",
            title: "Loadding Data...",
            timer: 0,
        });
        this.showData();
    },
    methods: {
        async showData() {
            const res = await this.callApi(
                "get",
                `/api/admin/reports/service_centers/service_details/${this.shop_id}`,
                null
            );
            console.log("Bookings", res);
            if (res.status == 200) {
                var data = res.data;
                if (data.status == "success") {
                    this.service_center = data.this_center;
                    this.state = data.state;
                    this.topCustomers = data.topCustomers;
                    this.all_bookings = data.all_bookings;
                    this.initTable();
                } else {
                    Toast.fire({
                        icon: "warning",
                        title: "something went wrong...",
                        timer: 2500,
                    });
                }
                toast.close();
            }
        },
        initTable() {
            this.toast = false;
            this.noData = false;
            this.tableData = [];
            this.all_bookings.forEach((element) => {
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
                        }</div>`,
                        item4: `<span><i class="fa fa-${
                            element.booking_veh_type == "four_wheeller"
                                ? "car"
                                : "motorcycle"
                        }"></i> ${
                            element.booking_veh_name
                        }</span> <br /><span> ${
                            element.booking_veh_num
                        }</span>`,
                        item5: `<span><i class="fa fa-list-alt"></i>  ${element.booking_package.package_name}</span><br/><span>₹${element.booking_package.package_price}, ${element.booking_package.package_duration} months</span>`,
                        item6: Vue.filter("formatDateTime")(
                            element.booking_picked_at
                        ),
                        item7: Vue.filter("formatDateTime")(
                            element.booking_date
                        ),
                        item8: `<span>status - ${
                            element.booking_payment_status
                        }</span>${
                            element.booking_payment_status == "paid"
                                ? `<br/><span>method - ${element.booking_payment_method}</span>`
                                : ""
                        }`,
                        item9: element.booking_status,
                    },
                    action: false,
                });
            });
            this.$refs.table.loadTable();
        },
        viewDetailPage(item, index) {
            this.$router.push(
                `/admin/reports/service-booking_details/${item.booking_id}`
            );
        },
    },
};
</script>
