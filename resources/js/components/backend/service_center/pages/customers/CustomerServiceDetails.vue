<template>
     <div class="container-fluid">
        <div class="container-xxl flex-grow-1 container-p-y">
            <Breadcrumb pageTitle="Customers" :breadcrumbList="breadcrumbList" />
          </div>
          

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Customers Services</h6>
                </div>
                <div class="card-body">

                    <div v-show="!noData" class="table table-responsive">
                        <Table ref="pendingTable" tableId="customer_table" :tableHead="tableHead" :tableData="tableData" :isDetail="true" />
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
                to: { name: "service-center.dashboard" },
                name: "Home",
            },
            {
                to: {name: "service-center.customers"},
                name: "Customers",
            },
            {
                name: "Customers Services",
            },
        ],
           bookings: [],
            states: [],
            noData: true,
            tableHead: {
                items: {
                    item1: { label: "Sr.No." },
                    item2: { label: "Booking .No." },
                    item3: { label: "Vehicle detail" },
                    item4: { label: "Package Detail" },
                    item5: { label: "Booking Date & Time" },
                    item6: { label: "Pickup Date & Time" },
                    item7: { label: "Payment Detail" },
                    item8: { label: "Status" }
                },
                //   actions: false,
            },
            tableData: [],
        };
    },
    mounted() {
        if (this.$route.params.id) {
            this.id = this.$route.params.id;
            this.showData();
        }else{
            this.$router.go(-1);
        }
    },
    methods:{
         async showData() {
            const res = await this.callApi("get", `/api/service-center/customers/service-details/${this.id}`, null);
            if (res.status == 200) {
                var data = res.data;
                if (data.status == "success") {
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
            this.$refs.pendingTable.loadTable();
        },
        viewDetailPage (item, index) {
            this.$router.push(`/service-center/reports/customer_invoice/${item.booking_id}`);
        },
    },
}
</script>
