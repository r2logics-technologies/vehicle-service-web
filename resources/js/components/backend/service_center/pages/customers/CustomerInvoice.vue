<template>
    <div>
        <div class="container flex-grow-1 container-p-y">
            </div>
             <div class="container">
                <div class="text-center"><h4>Detail Page</h4></div>
                <div class="card text-color" v-if="service" style="border: 1px solid #a7a7af;">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-9">
                                <div>
                                    <span class="fw-bolder"><b>Booking No :</b></span>
                                    {{ service.booking_number }}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="fs-3 fw-bold "><b>Service Center</b></div>
                                <div class="fs-11" v-if="service.booking_center">
                                    {{ service.booking_center.shop_name }} {{ service.booking_center.shop_address_line1 }}
                                    {{ service.booking_center.shop_address_line2 }} {{ service.booking_center.shop_city }}
                                    {{ service.booking_center.shop_country }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row bservice-top mt-3">
                            <div class="col-md-4">
                                <span class="fw-bolder"><b>Booking Date and Time :</b></span>
                                <span class="text-center">{{ service.booking_date }}</span><br>
                                <span class="fw-bolder"><b>Service Complete Time :</b></span>
                                <span class="text-center">{{ service.booking_completed_at}}</span><br>
                                <span class="fw-bolder"><b>Service duration Time :</b></span>
                                <span class="text-center">{{ service.service_duration_time }} Hours</span><br>
                            </div>
                            <div class="col-md-4">
                                <div class="ml-5 pl-5">
                                        <span class="fw-bolder"><b>Package Features :</b></span> <br>
                                        <span v-html="service.booking_package.package_features_name"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="float-right">
                                        <span class="fw-bolder" v-if="service.booking_user"><b>Name :</b>
                                            <span class="fw-bold  ">{{ service.booking_user.user_name }}</span><br>
                                            <span class="fw-bold  "><b>Mobile:</b> {{ service.booking_user.user_mobile }}</span>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 table-responsive-sm">
                            <table class="table table-striped ">
                                    <thead>
                                        <tr class="tr-bservice">
                                            <th>Package Name</th>
                                            <th>Package Type</th>
                                            <th>Price</th>
                                            <th>Package Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bservice-0 border-1" v-if="service.booking_package">
                                            <td> {{service.booking_package.package_name}}
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
                                        </tr>
                                    </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <span>
                                    <strong>Payment Method :</strong>
                                    {{ service.booking_payment_method
                                    }} </span><br />
                                <span>
                                    <strong>Payment Status :</strong>
                                    {{ service.booking_payment_status
                                    }} </span><br/>    
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
                            <div class="col-md-6 ">
                                <span class="float-right" v-if="service.booking_driver && service.booking_driver != null">
                                    <h6 class="fw-bolder"><b>Driver Details :</b></h6>
                                    <strong class="" v-if="service.booking_driver.driver_name"><i class="fa fa-user"></i> {{ service.booking_driver.driver_name }}
                                    </strong><br />
                                    <span class="ms-5" v-if="service.booking_driver.driver_mobile"><i class="fa fa-phone"></i>
                                        {{ service.booking_driver.driver_mobile }}<br />
                                    </span>
                                    <span v-if="service.booking_driver.driver_email"><i class="fa fa-envelope pr-2"></i>{{ service.booking_driver.driver_email
                                    }}</span><br />
                                    <span v-if="service.booking_picked_at">
                                        <span class=""><b> Picked Time : </b></span>
                                       <span>{{ service.booking_picked_at}}</span>  
                                    </span><br />
                                    <span v-if="service.booking_dropped">
                                        <span class=""><b> Dropped Time : </b></span>
                                       <span>{{ service.booking_dropped}}</span>  
                                    </span><br />
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
            bookings: [],
            states: [],
            service: {},
            noData: true,
            selectedDate:"",
            tableData: [],
            
        };
    },
    mounted() {
        toast = Toast.fire({
            icon: "warning",
            title: "Loading Data..",
            timer: 0,
        });
        if (this.$route.params.booking_id) {
            this.showData();
        } else {
            this.$router.go(-1);
        }
    },
    methods: {
        async showData() {
            const res = await this.callApi(
                "get",
                `/api/service-center/customers/service-invoice/${this.$route.params.booking_id}`,
                null
            );
            if (res.status == 200) {
                var data = res.data;
                if (data.status == "success") {
                    this.noData = false;
                    this.service = data.service;       
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
</style>
