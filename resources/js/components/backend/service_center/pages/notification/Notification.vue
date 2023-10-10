<template>
    <div class="container-fluid">
        <div class="container-xxl flex-grow-1 container-p-y">
            <Breadcrumb
                pageTitle="Notification"
                :breadcrumbList="breadcrumbList"
            />
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">
                    Notifications List
                </h6>
            </div>
            <div class="card-body">
                <div v-show="!noData" class="table table-responsive">
                    <Table
                        ref="table"
                        tableId="notification_table"
                        :tableHead="tableHead"
                        :tableData="tableData"
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
                    to: { name: "service-center.dashboard" },
                    name: "Home",
                },
                {
                    name: "Notifications",
                },
            ],
            notifications: [],
            states: [],
            noData: true,
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
            const res = await this.callApi(
                "get",
                "/api/service-center/service_center_bookings/notifications",
                null
            );
            this.log('notification',res);
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
