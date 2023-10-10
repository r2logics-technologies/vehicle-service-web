<template>
    <div>
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            </div> 
            <!-- Content Row -->
            <div class="row">
               <div class="col-md-auto  col-sm-12 mb-4 ms-2">
                    <div class="card border-left-info shadow  py-2" >
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-3">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1 ">
                                      Total Bookings
                                    </div>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                      {{ state.all_bookings }}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Serivce Centers Card  -->
                <div class="col-md-auto col-sm-12 mb-4">
                    <div class="card border-left-info shadow  py-2" >
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-3">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Today's Bookings
                                    </div>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{state.today_booking}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Man Card -->
                <div class="col-md-auto col-sm-12 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2" >
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-3">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Pending Bookings
                                    </div>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ state.pend_bookings
                                         }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customers Card -->
                <div class="col-md-auto col-sm-12 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2" >
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-3">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        In Proccess Bookings
                                    </div>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ state.pros_bookings }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings Card -->
                <div class="col-md-auto col-sm-12 mb-4">
                    <div class="card border-left-success shadow h-100 py-2" >
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-3">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Completed Booking
                                    </div>
                                </div>
                                <div class="col-auto mb-0 font-weight-bold text-gray-800 h5">
                                    {{ state.comp_bookings
                                         }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->
            <div class="row">
                <div class="col-sm-12 mb-4">
                    <div class="card shadow mb-4 bg-white">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-danger">Top 10 Customers</h6>
                        </div>
                        <div class="card-body">
                            <canvas ref="topCustomerChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-sm-12 mb-4">
                    <div class="card shadow mb-4 bg-white">
                        <div class="card-body">
                            <canvas ref="chartBar"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
import Chart from "chart.js/auto";

export default {
    data: () => ({
        state: {},
        bookings:[],
        serviceCenters: [],
        barChartData: {
            labels: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ],
            datasets: [
                {
                    label: "Services",
                    backgroundColor: [
                        "#ff6384",
                        "#36a2eb",
                        "#ffce56",
                        "#4bc0c0",
                        "#9966ff",
                        "#ff9900",
                    ],
                    data: [40, 70, 30, 10, 50, 80, 40, 20, 60, 30, 80, 50],
                },
            ],
        },
        topCustomers: [],
    }),
    created() {
        this.showData();
    },
    mounted() {
        this.renderChart();
        window.scrollTo(0, 0);
    },
    methods: {
        renderChart() {
            const bar_chart = this.$refs.chartBar.getContext("2d");
            new Chart(bar_chart, {
                type: "bar",
                data: this.barChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                },
            });

            setTimeout(() => {
                const top10Customers = this.topCustomers.map(customer => customer.name);
                const top10CustomersBookings = this.topCustomers.map(customer => customer.bookings_count);
                const topCustomerCtx = this.$refs.topCustomerChart.getContext('2d');

                new Chart(topCustomerCtx, {
                    type: 'bar',
                    data: {
                        labels: top10Customers,
                        datasets: [{
                            label: 'Total Orders',
                            data: top10CustomersBookings,
                            backgroundColor: this.randomColor,
                            borderColor: this.randomColor,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }, 2500);
        },

        async showData() {
          const res = await this.callApi("get", "/api/service-center/service_center_bookings/dashboard", null);
          if (res.status == 200) {
            var data = res.data;
            if (data.status == "success") {
              this.state = data.state;
              this.topCustomers = data.topCustomers;
            this.bookings = data.bookings;
            } else {
              Toast.fire({
                icon: "warning",
                title: "something went wrong...",
                timer: 2500,
              });
            }
          }
        },
    },
};
</script>

