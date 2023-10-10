<template>
    <div>
        <div class="container">
            <div class="container-xxl flex-grow-1 container-p-y">
                <Breadcrumb pageTitle="Reports" :breadcrumbList="breadcrumbList" />
            </div>
            <!-- <div class="text-center">
                <h4>Detail Page</h4>
            </div> -->
            <div class="card text-color" v-if="service" style="border: 1px solid #a7a7af;">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9 col-sm-12">
                            <div>
                                <span class="fw-bolder"><b>Booking No :</b></span><br />
                                {{ service.booking_number }}
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="fs-3 fw-bold"><b>Service Center</b></div>
                            <div class="fs-11" v-if="service.booking_center">
                                {{ service.booking_center.shop_name }} {{ service.booking_center.shop_address_line1 }}
                                {{ service.booking_center.shop_address_line2 }} {{ service.booking_center.shop_city }}
                                {{ service.booking_center.shop_country }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row bservice-top py-3">
                        <div class="col-md-6 col-sm-12">
                            <span class="fw-bolder me-2"><b>Booking Date and Time :</b></span>
                            <span class="text-center">{{ service.booking_date }}</span>
                        </div>
                        <div class="col-md-4 col-sm-12 ">
                            <span class="fw-bolder mr-0 " v-if="service.booking_user"><b>Inovice To :</b>
                                <span class="fw-bold">{{ service.booking_user.user_name }}</span><br>
                                <span class="fw-bold"><b>Mobile:</b> {{ service.booking_user.user_mobile }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <table class="table table-responsive bordered ">
                            <tr class="tr-bservice">
                                <th>Sr.No.</th>
                                <th>Package Name</th>
                                <th>Package Type</th>
                                <th>Price</th>
                                <th>Package Duration</th>
                                <th>Package Features Name</th>
                            </tr>
                            <tr class="bservice-0 border-1" v-if="service.booking_package">
                                <td v-if="service.booking_package.package_id">{{ service.booking_package.package_id }}</td>
                                <td>
                                    {{
                                        service.booking_package
                                            .package_name
                                    }}
                                </td>
                                <td v-if="service.booking_package.package_type">
                                    {{ service.booking_package.package_type }}
                                </td>
                                <td v-if="service.booking_package.package_price">
                                    {{ service.booking_package.package_price }}
                                </td>
                                <td v-if="service.booking_package.package_duration">
                                    {{ service.booking_package.package_duration }}
                                </td>
                                <td v-if="service.booking_package.package_features_name">
                                    <span v-html="service.booking_package.package_features_name"></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6 col-sm-12">
                            <span>
                                <strong>Payment Method :</strong>
                                {{ service.booking_payment_method
                                }} </span><br />
                            <span>
                                <strong>Payment Status :</strong>
                                {{ service.booking_payment_status
                                }} </span><br />
                            <span>
                                <strong>Address : </strong>
                                <span v-if="service.booking_address
                                " class="justify-content-center">
                                    <span v-if="
                                        service.booking_address.address_line1 &&
                                        service.booking_address.address_line1 !=
                                        null
                                    ">
                                        {{ service.booking_address.address_line1 }}
                                    </span>
                                    <span v-if="
                                        service.booking_address.address_line2 &&
                                        service.booking_address.address_line2 !=
                                        null
                                    ">
                                        {{ service.booking_address.address_line2 }}
                                    </span>
                                    <span v-if="
                                        service.booking_address
                                            .locality &&
                                        service.booking_address
                                            .locality != null
                                    ">
                                        {{
                                            service.booking_address.locality
                                        }}
                                    </span>
                                    <span v-if="
                                        service.booking_address
                                            .landmark &&
                                        service.booking_address
                                            .landmark != null
                                    ">
                                        {{
                                            service.booking_address.landmark
                                        }}
                                    </span>
                                    <span v-if="
                                        service.booking_address.city &&
                                        service.booking_address.city !=
                                        null
                                    ">
                                        ,{{ service.booking_address.city }}
                                    </span>
                                    <span v-if="
                                        service.booking_address
                                            .pincode &&
                                        service.booking_address
                                            .pincode != null
                                    ">
                                        {{ service.booking_address.pincode }}
                                    </span>
                                    <span v-if="
                                        service.booking_address.state &&
                                        service.booking_address.state !=
                                        null
                                    ">
                                        ,{{ service.booking_address.state }}
                                    </span>
                                    <span v-if="
                                        service.booking_address
                                            .country &&
                                        service.booking_address
                                            .country != null
                                    ">
                                        ,{{

                                        }}
                                    </span>
                                </span>
                            </span>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <span class="float-end" v-if="service.booking_driver && service.booking_driver != null">
                                <h6 class="fw-bolder"><b>Driver Details :</b></h6>
                                <strong class="" v-if="service.booking_driver.driver_name"><i class="fa fa-user"></i> {{
                                    service.booking_driver.driver_name }}
                                </strong><br />
                                <span class="ms-5" v-if="service.booking_driver.driver_mobile"><i class="fa fa-phone"></i>
                                    {{ service.booking_driver.driver_mobile }}<br />
                                </span>
                                <span v-if="service.booking_driver.driver_email"><i class="fa fa-envelope pr-1"></i>{{
                                    service.booking_driver.driver_email
                                }}</span><br />
                                <!-- <span class="ms-3"><i class="fa fa-address-card"></i>
                                    {{ service.booking_driver.driver_address_line1 }} {{
                                        service.booking_driver.driver_address_line2 }}
                                    {{ service.booking_driver.driver_landmark }} {{ service.booking_driver.driver_city }} {{
                                        service.booking_driver.driver_state }} {{ service.booking_driver.driver_country }}
                                </span> -->
                            </span>
                        </div>
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
                    to: { name: "admin.reports.booking" },
                    name: "Booking Report",
                },
                {
                    name: "Booking Invoice",                          
                },
            ],
            bookings: [],
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
            selectedDate: "",
            tableData: [],
            service: {},
            noData: true,

        };
    },
    mounted() {
        toast = Toast.fire({
            icon: "warning",
            title: "Loading Data..",
            timer: 0,
        });
        if (this.$route.params.bookings_id) {
            this.showData();
        } else {
            this.$router.go(-1);
        }
    },
    methods: {
        async showData() {
            const res = await this.callApi(
                "get",
                `/api/admin/reports/customers/service-invoice/${this.$route.params.bookings_id}`,
                null
            );
            console.log('RESSSS>>>>>>', res);
            if (res.status == 200) {
                var data = res.data;
                if (data.status == "success") {
                    this.noData = false;
                    this.service = data.service; 
                    // this.breadcrumbList[1].to = `/admin/reports/service-center_details/${this.service.booking_center.shop_id}`
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
    },
};
</script>
<style scoped>
.fs-11 {
    font-size: 11px !important;
}

th {
    font-weight: bold !important;
    margin-right: 20px !important;
}

/* .tr-bservice {
    bservice-bottom: 2px solid;
}

strong {
    margin-right: 20px;
}

.bservice-solid {
    bservice-top: 1px solid;
    bservice-bottom: 1px solid;
} */
</style>
