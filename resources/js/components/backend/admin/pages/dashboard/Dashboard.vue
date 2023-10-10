<template>
  <div class="container-fluid">
    <!-- Page Heading -->
    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div> -->
    <div class="container-xxl container-p-y">
        <Breadcrumb pageTitle="Dashboard" />
    </div>
    
    <!-- Content Row -->
    <div class="row">
      <!-- Serivce Centers Card  -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  Serivce Centers
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ state.centers }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fa fa-store fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Delivery Man Card -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Delivery Man
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ state.drivers }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-id-card fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Customers Card -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  Customers
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ state.customers }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-users fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bookings Card -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  Packages
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ state.packages }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-list-alt fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Row -->
    <div class="row">
      <!-- Serivce Centers Card  -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-80 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  Today's Bookings
                </div>
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                {{ state.today_booking }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Delivery Man Card -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                  Pending Bookings
                </div>
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                {{ state.pend_bookings }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Customers Card -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
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
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Completed Booking
                </div>
              </div>
              <div class="col-auto mb-0 font-weight-bold text-gray-800">
                {{ state.comp_bookings }}
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
    <!-- Content Row -->
    <div class="row">
      <!-- Content Column -->
      <div class="col-md-8 col-sm-12 mb-4">
        <!-- Project Card Example -->
        <div class="card shadow mb-4 bg-white">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Top 3 Service Centers</h6>
          </div>
          <div class="card-body">
            <div v-if="serviceCenters.length > 0">
              <div class="py-2" v-for="(item, index) in serviceCenters" :key="index">
                <h4 class="small font-weight-bold">
                  {{ item.name }}
                  <span class="float-right">Top {{ index + 1 }} </span>
                </h4>
                <div class="progress mb-4">
                  <div
                    class="progress-bar progress-bar-striped bg-danger"
                    role="progressbar"
                    style="width: 100%"
                    aria-valuenow="100"
                    aria-valuemin="0"
                    aria-valuemax="100"
                  ></div>
                </div>
              </div>
            </div>
            <div v-else>
              <img
                src="/assets/img/no-data.png"
                class="img-fluid my-0 py-0 mx-md-5"
                alt=""
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Content Column -->
      <div class="col-md-4 col-sm-12 mb-4">
        <!-- Project Card Example -->
        <div class="card shadow mb-4 bg-white">
          <div class="card-body">
            <canvas ref="chartPie"></canvas>
          </div>
        </div>
      </div>
    </div>


    <!-- Content Row -->
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
</template>
<script>
import Chart from "chart.js/auto";

export default {
  data: () => ({
    
    state: {},
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
    pieChartData: {
      labels: ["BOOKINGS", "CUSTOMERS", "DELIVERY MAN", "SERIVCE CENTERS", "PACKAGES"],
      datasets: [
        {
          data: [50, 40, 30, 40, 50],
          backgroundColor: ["#4E73DF", "#1CC88A", "#F7C649", "#1CC88A", "#4BC0C0"],
          hoverBackgroundColor: [
            "#ff6384",
            "#36a2eb",
            "#ffce56",
            "#4bc0c0",
            "#9966ff",
            "#ff9900",
          ],
        },
      ],
    },
    topCustomers: [],
  }),
  created(){
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
      const pie_chart = this.$refs.chartPie.getContext("2d");
      new Chart(pie_chart, {
        type: "polarArea",
        data: this.pieChartData,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: "right",
          },
        },
      });

      setTimeout(() => {
        const top10Customers = this.topCustomers.map(customer => customer.name);
        const top10CustomersBookings = this.topCustomers.map(customer => customer.bookings_count);
        this.log(top10Customers);
        this.log(top10CustomersBookings);
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
      const res = await this.callApi("get", "/api/admin/dashboard", null);
      // this.log("data->state", res);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.state = data.state;
          this.topCustomers = data.topCustomers;
          this.serviceCenters = data.service_centers;
          this.log(this.topCustomers);
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
