<template>
  <div>
    <div class="d-flex justify-content-between mb-4 mx-4">
      <div class="flex-grow-1">
        <Breadcrumb pageTitle="Push Notifications" :breadcrumbList="breadcrumbList" />
      </div>
      <div class="float-end mt-2">
        <button
          class="btn btn-danger py-2"
          title="Push Notification"
          @click="$router.push('/admin/notification/create')"
        >
          Push Notification
          <i class="fa fa-plus"></i>
        </button>
      </div>
    </div>
    <div class="container-fluid">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-danger">Push Notification</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap" v-show="!noData">
            <Table
              tableId="notification_table"
              ref="table"
              :tableHead="tableHead"
              :tableData="tableData"
            />
          </div>
          <NoData v-show="noData" />
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
          name: "Push Notifications",
        },
      ],
      tableHead: {
        items: {
          item1: { label: "Sr.No." },
          item2: { label: "Service Detail" },
          item3: { label: "Description" },
          item4: { label: "Date" },
        },
        actions: false,
      },
      tableData: [],
      notifications: [],
      noData: true,
      interValFuction: "",
    };
  },
  mounted() {
    toast = Toast.fire({
      icon: "warning",
      title: "Loading Data..",
      timer: 0,
    });
    this.showData();
    window.scrollTo(0, 0);
  },
  methods: {
    async showData() {
      const res = await this.callApi("get", "/api/admin/notifications", null);
      this.log(res);
      if (res.status == 200) {
        var data = res.data;
        if (data.status == "success") {
          this.notifications = data.notifications;
          this.initTable();
        } else {
          toast = Toast.fire({
            icon: "warning",
            title: data.message,
            timer: 2500,
          });
        }
      } else {
        toast = Toast.fire({
          icon: "error",
          title: "something want wrong.please try again..",
          timer: 2500,
        });
      }
      this.$refs.table.loadTable();
      toast.close();
    },
    initTable() {
      this.noData = false;
      this.tableData = [];
      this.notifications.forEach((element) => {
        this.tableData.push({
          item: element,
          data: {
            item1: element.notification_id,
            item2: element.notification_title,
            item3: element.notfication_description,
            item4: element.notification_created_at,
          },
          action: false,
        });
      });
    },
  },
};
</script>
