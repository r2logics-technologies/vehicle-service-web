<template>
    <div>
        <div class="container-xxl flex-grow-1 container-p-y mx-4">
            <Breadcrumb pageTitle = "Reports"
                :breadcrumbList="breadcrumbList"
            />
        </div>
        <div class="container-fluid">
          

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        Booking List
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!--Card  -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div
                                class="card border-left-primary shadow h-80 py-2"
                            >
                                <div class="card-body py-2">
                                    <div
                                        class="row no-gutters align-items-center"
                                    >
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-primary text-capitalize mb-1"
                                            >
                                                Total Booking
                                            </div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-primary"
                                            >
                                                {{ states.allBooking }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div
                                class="card border-left-warning shadow h-80 py-2"
                            >
                                <div class="card-body py-2">
                                    <div
                                        class="row no-gutters align-items-center"
                                    >
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-warning text-capitalize mb-1"
                                            >
                                                Pending Booking
                                            </div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-warning"
                                            >
                                                {{ states.pendBooking }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-80 py-2">
                                <div class="card-body py-2">
                                    <div
                                        class="row no-gutters align-items-center"
                                    >
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-info text-capitalize mb-1"
                                            >
                                                Accepted Booking
                                            </div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-info"
                                            >
                                                {{ states.acptBooking }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-dark shadow h-80 py-2">
                                <div class="card-body py-2">
                                    <div
                                        class="row no-gutters align-items-center"
                                    >
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-dark text-capitalize mb-1"
                                            >
                                                Picked Vehicle
                                            </div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-dark"
                                            >
                                                {{ states.pickBooking }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!--Card  -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div
                                class="card border-left-warning shadow h-80 py-2"
                            >
                                <div class="card-body py-2">
                                    <div
                                        class="row no-gutters align-items-center"
                                    >
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-warning text-capitalize mb-1"
                                            >
                                                In Progress
                                            </div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-warning"
                                            >
                                                {{ states.progBooking }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div
                                class="card border-left-danger shadow h-80 py-2"
                            >
                                <div class="card-body py-2">
                                    <div
                                        class="row no-gutters align-items-center"
                                    >
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-danger text-capitalize mb-1"
                                            >
                                                Cancelled Booking
                                            </div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-danger"
                                            >
                                                {{ states.cancBooking }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div
                                class="card border-left-success shadow h-80 py-2"
                            >
                                <div class="card-body py-2">
                                    <div
                                        class="row no-gutters align-items-center"
                                    >
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-success text-capitalize mb-1"
                                            >
                                                Completed Services
                                            </div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-success"
                                            >
                                                {{ states.compBooking }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div
                                class="card border-left-secondary shadow h-80 py-2"
                            >
                                <div class="card-body py-2">
                                    <div
                                        class="row no-gutters align-items-center"
                                    >
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-muted text-capitalize mb-1"
                                            >
                                                Rejected Services
                                            </div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-muted"
                                            >
                                                {{ states.rejcBooking }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div
                                class="card border-left-success shadow h-80 py-2"
                            >
                                <div class="card-body py-2">
                                    <div
                                        class="row no-gutters align-items-center"
                                    >
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-success text-capitalize mb-1"
                                            >
                                                Cash Payments
                                            </div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-success"
                                            >
                                                ₹{{ totalCashPrice }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-2x text-success"
                                                >₹</i
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div
                                class="card border-left-success shadow h-80 py-2"
                            >
                                <div class="card-body py-2">
                                    <div
                                        class="row no-gutters align-items-center"
                                    >
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-success text-capitalize mb-1"
                                            >
                                                Online Payments
                                            </div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-success"
                                            >
                                                ₹{{ totalOnlinePrice }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-2x text-success"
                                                >₹</i
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-80 py-2">
                                <div class="card-body py-2">
                                    <div
                                        class="row no-gutters align-items-center"
                                    >
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-info text-capitalize mb-1"
                                            >
                                                Two Wheeler Vehicle
                                            </div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-info"
                                            >
                                                {{ states.twoWBooking }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i
                                                class="fa fa-motorcycle fa-2x text-info"
                                            ></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-80 py-2">
                                <div class="card-body py-2">
                                    <div
                                        class="row no-gutters align-items-center"
                                    >
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-info text-capitalize mb-1"
                                            >
                                                Four Wheeler Vehicle
                                            </div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-info"
                                            >
                                                {{ states.fourWBooking }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i
                                                class="fa fa-car fa-2x text-info"
                                            ></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-show="!noData" class="table table-responsive">
                        <Table
                            ref="pendingTable"
                            tableId="customer_table"
                            :tableHead="tableHead"
                            :tableData="tableData"
                            :isDetail = true
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
        breadcrumbList: [
            {
                to: { name: "admin.dashboard" },
                name: "Home",
            },
            {
                name: "Booking Report",
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
                item9: { label: "Status" },
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
                "/api/admin/reports/bookings",
                null
            );
            this.log(res);
            if (res.status == 200) {
                var data = res.data;
                if (data.status == "success") {
                    this.bookings = data.bookings;
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
            this.$refs.pendingTable.loadTable();
        },
        viewDetailPage(item, index) {
            this.$router.push(
                `/admin/reports/booking_invoice/${item.booking_id}`
            );
        },
    },
};
</script>
