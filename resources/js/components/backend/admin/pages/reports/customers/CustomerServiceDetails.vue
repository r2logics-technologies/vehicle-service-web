<template>
<div>
        <div class="container-xxl container-p-y mx-4">
            <Breadcrumb
                pageTitle="Customer Services"
                :breadcrumbList="breadcrumbList"
            />
        </div>
     <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">{{customer.user_name}} Services</h6>
                </div>
                <div class="card-body">
                    <div class="dataTables_filter">
              <label v-if="this.bookings == null ? isShowDate : selectedDate">
                Show by Date:
                <input type="date" class="form-control" v-model="selectedDate" />
              </label>
            </div>
                    <div v-show="!noData" class="table table-responsive">
                        <Table ref="table" tableId="customer_table"
                         :tableHead="tableHead" :tableData="filterData" :isDetail = true />
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
           bookings: [],
           customer:{},
            states: [],
            noData: true,
            isShowDate: false,
            tableHead: {
                items: {
                    item1: { label: "Sr.No." },
                    item2: { label: "Booking .No." },
                    item3: { label: "Vehicle detail" },
                    item4: { label: "Package Detail" },
                    item5: { label: "Booking Date & Time" },
                    item6: { label: "Pickup Date & Time" },
                    item7: { label: "Payment Detail" },
                    item8: { label: "Status" },
                },
                //   actions: false,
            },
            selectedDate:"",
            tableData: [],
            breadcrumbList: [
                {
                    to: { name: "admin.dashboard" },
                    name: "Home",
                },
                {
                    to: { name: "admin.reports.customers" },
                    name: "Customer Report",
                },
                {
                    name: "Customer Services"
                },
            ],
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
        if (this.$route.params.id) {
            this.id = this.$route.params.id;
            console.log('iiiddddd', this.id);
        }
         toast = Toast.fire({
            icon: "warning",
            title: "Loadding Data...",
            timer: 0,
        });
        this.showData();
    },
    methods:{
         async showData() {
            const res = await this.callApi("get", `/api/admin/reports/customers/service-details/${this.id}`, null);
            this.log('services', res);
            if (res.status == 200) {
                var data = res.data;
                if (data.status == "success") {
                    this.customer = data.customer;
                    this.bookings = data.services;
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
            this.log(this.bookings);
            this.bookings.forEach((element) => {
                this.tableData.push({
                    item: element,
                    data: {
                        item1: element.booking_id,
                        item2: element.booking_number,
                        item3: `<span><i class="fa fa-${element.booking_veh_type == "four_wheeller" ? "car" : "motorcycle"
                            }"></i> ${element.booking_veh_name}</span> <br /><span> ${element.booking_veh_num
                            }</span>`,
                        item4: `<span><i class="fa fa-list-alt"></i>  ${element.booking_package.package_name}</span><br/><span>₹${element.booking_package.package_price}, ${element.booking_package.package_duration} months</span>`,
                        item5: Vue.filter("formatDateTime")(element.booking_picked_at),
                        item6: Vue.filter("formatDateTime")(element.booking_date),
                        item7: `<span>status - ${element.booking_payment_status}</span>${element.booking_payment_status == 'paid' ? `<br/><span>method - ${element.booking_payment_method}</span>` : ''}`,
                        item8: element.booking_status,
                    },
                    action: false,
                });
            });
            this.$refs.table.loadTable();
        },
        viewDetailPage(item, index) {
            this.$router.push(`/admin/reports/customer_invoice/${item.booking_id}`);
        },
    },
}
</script>
