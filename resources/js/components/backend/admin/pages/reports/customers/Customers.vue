<template>
    <div>
        <div class="container-xxl flex-grow-1 container-p-y mx-4">
            <Breadcrumb pageTitle="Reports" :breadcrumbList="breadcrumbList" />
        </div>
        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        Customers List
                    </h6>
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
                                                All Customers
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-info">
                                                {{ states.allCustomers }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-users fa-2x text-info"></i>
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
                                            <div class="text-xs font-weight-bold text-success text-capitalize mb-1">
                                                Active Customers
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-success">
                                                {{ states.actCustomers }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-success"></i>
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
                                                Inactive Customers
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-secondary">
                                                {{ states.inActCustomers }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dataTables_filter">
                        <label>
                            Show by Date:
                            <input type="date" class="form-control" v-model="selectedDate" />
                        </label>
                    </div>
                    <div v-show="!noData" class="table">
                        <Table ref="table" tableId="customer_table" :tableHead="tableHead" :tableData="filterData"
                            :isDetail="true" />
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
    data() {
        return {
            breadcrumbList: [
                {
                    to: { name: "admin.dashboard" },
                    name: "Home",
                },
                {
                    name: "Customer Report",
                },
            ],
            customers: [],
            states: [],
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
        };
    },
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
        filterData: function () {
            if (this.selectedDate != "") {
                return this.tableData.filter((customer) => {
                    var date = Vue.filter("formatDate")(this.selectedDate);
                    if (date == customer.data.item4) {
                        return customer;
                    }
                });
            } else {
                return this.tableData;
            }
        },
    },
    methods: {
        async showData() {
            const res = await this.callApi(
                "get",
                "/api/admin/reports/customers",
                null
            );
            if (res.status == 200) {
                var data = res.data;
                if (data.status == "success") {
                    this.customers = data.customers;
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
            this.log(this.customers);
            this.customers.forEach((element) => {
                this.tableData.push({
                    item: element,
                    data: {
                        item1: element.user_id,
                        item2: `<div title="${element.id != null ? element.id : ""}">
                            ${element.user_name == null ? '' : `<span><i class="fa fa-user"></i> - ${element.user_name}</span><br />`}<span style="font-size:13px; class="ms-2"><i class='fa fa-phone'></i> -
                 ${element.user_mobile != null ? element.user_mobile : ""
                            }</span>${element.user_email == null
                                ? ' ' : `<br /><span class="ms-1 text-lowercase"><span class="fw-bold">@ </span > -  ${element.user_email}`}</span></div>`,
                        item3:
                            element.booking_count != null
                                ? element.booking_count
                                : "",
                        item4: Vue.filter("formatDate")(
                            element.user_created_at != null
                                ? element.user_created_at
                                : ""
                        ),
                        item5: element.user_status,
                    },
                    action: false,
                });
            });
            this.$refs.table.loadTable();
        },
        viewDetailPage(data, index) {
            if (data.booking_count == 0) {
                SwalCustomBtn.fire(
                    "No Service",
                    "Customer don't have any service!",
                    "warning"
                );
            } else {
                this.$router.push(
                    `/admin/reports/customer_service_details/${data.id}`
                );
            }
        },
    },
};
</script>
