<template>
  <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button
      id="sidebarToggleTop"
      @click="sideBar()"
      class="btn btn-link rounded-circle mr-3"
    >
      <i class="fa fa-bars text-danger"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
      <!-- Nav Item - Search Dropdown (Visible Only XS) -->
      <li class="nav-item dropdown no-arrow d-sm-none">
        <a
          class="nav-link dropdown-toggle"
          href="#"
          id="searchDropdown"
          role="button"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="fas fa-search fa-fw"></i>
        </a>
        <!-- Dropdown - Messages -->
        <div
          class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
          aria-labelledby="searchDropdown"
        >
          <form class="form-inline mr-auto w-100 navbar-search">
            <div class="input-group">
              <input
                type="text"
                class="form-control bg-light border-0 small"
                placeholder="Search for..."
                aria-label="Search"
                aria-describedby="basic-addon2"
              />
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Nav Item - Alerts -->
      <li class="nav-item dropdown no-arrow mx-1 d-none">
        <a
          class="nav-link dropdown-toggle"
          href="#"
          id="alertsDropdown"
          role="button"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="fas fa-bell fa-fw"></i>
          <!-- Counter - Alerts -->
          <span class="badge badge-danger badge-counter">3+</span>
        </a>
        <!-- Dropdown - Alerts -->
        <div
          class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
          aria-labelledby="alertsDropdown"
        >
          <h6 class="dropdown-header">Alerts Center</h6>
          <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="mr-3">
              <div class="icon-circle bg-primary">
                <i class="fas fa-file-alt text-white"></i>
              </div>
            </div>
            <div>
              <div class="small text-gray-500">December 12, 2019</div>
              <span class="font-weight-bold">
                A new monthly report is ready to download!
              </span>
            </div>
          </a>
          <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="mr-3">
              <div class="icon-circle bg-success">
                <i class="fas fa-donate text-white"></i>
              </div>
            </div>
            <div>
              <div class="small text-gray-500">December 7, 2019</div>
              $290.29 has been deposited into your account!
            </div>
          </a>
          <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="mr-3">
              <div class="icon-circle bg-warning">
                <i class="fas fa-exclamation-triangle text-white"></i>
              </div>
            </div>
            <div>
              <div class="small text-gray-500">December 2, 2019</div>
              Spending Alert: We've noticed unusually high spending for your account.
            </div>
          </a>
          <a class="dropdown-item text-center small text-gray-500" href="#">
            Show All Alerts
          </a>
        </div>
      </li>
      <div class="topbar-divider d-none d-sm-block"></div>

      <!-- Nav Item - User Information -->

      <li class="nav-item dropdown no-arrow" v-show="user">
        <a
          class="nav-link dropdown-toggle"
          href="#"
          id="userDropdown"
          role="button"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">
            {{ user.user_name != null && user.user_name != "" ? user.user_name : "User" }}
          </span>
          <img
            class="img-profile rounded-circle border-danger border"
            :src="getAvatarUrl(user.user_avatar)"
          />
          <div v-if="center != null">
            <span v-if="(center != null ? center.shop_activity_status : '') == 'offline'">
              <div class="offlineUser"></div>
            </span>
            <span v-else>
              <div class="onlineUser"></div>
            </span>
          </div>
        </a>
        <!-- Dropdown - User Information -->
        <div
          class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
          aria-labelledby="userDropdown"
        >
          <div v-for="(item, index) in items" :key="index">
            <router-link class="dropdown-item" :to="item.to">
              <i class="" :class="item.icon"></i>
              {{ item.name }}
            </router-link>
          </div>
          <div v-if="center != null">
            <div class="dropdown-divider"></div>
            <div style="cursor: pointer">
              <a class="dropdown-item" @click.prevent="activityStatus(center)">
                <i
                  :class="
                    (center != null ? center.shop_activity_status : '') == 'offline'
                      ? 'fa fa-toggle-off text-secondary  fa-lg '
                      : 'fa fa-toggle-on text-success fa-lg '
                  "
                ></i>
                Activity
              </a>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <a
            class="dropdown-item"
            href=""
            @click.prevent="logout()"
            data-toggle="modal"
            data-target="#logoutModal"
          >
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            Logout
          </a>
          <form
            ref="userLogout"
            id="logout-form"
            action="/logout"
            method="POST"
            style="display: none"
          >
            <input type="hidden" name="_token" :value="csrf" />
          </form>
        </div>
      </li>
    </ul>
  </nav>
</template>
<script>
var toast;
export default {
  data: () => ({
    adminList: [
      {
        name: "Profile",
        to: "/admin/profile",
        icon: "fas fa-user fa-sm fa-fw mr-2 text-gray-400",
      },
      {
        name: "App Setting",
        to: "/admin/app/setting",
        icon: "fas fa-cog fa-sm fa-fw mr-2 text-gray-400",
      },
    ],
    centerList: [
      {
        name: "Profile",
        to: "",
        icon: "fas fa-user fa-sm fa-fw mr-2 text-gray-400",
      },
    ],

    user: {},
    center: {},
    items: [],
    csrf: document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
  }),
  created() {
    this.showData();
    if (this.$route.path.includes("/admin/")) {
      this.items = this.adminList;
    } else {
      this.items = this.centerList;
    }
  },
  methods: {
    async showData() {
      const res = await this.callApi("get", "/api/admin/main", null);
      this.log("data", res);
      if (res.status == 200) {
        if (res.data.status == "success") {
          this.user = res.data.user;
          this.center = res.data.center;
          if (res.data.center != null) {
            this.centerList[0].to = `/service-center/profile/${this.center.shop_id}`;
          }
        } else {
          toast.fire({
            icon: "warning",
            title: res.data.message,
            timer: 2500,
          });
        }
      } else {
        toast.fire({
          icon: "warning",
          title: res.statusText,
          timer: 2500,
        });
      }
    },
    logout() {
      this.$refs.userLogout.submit();
    },
    sideBar() {
      $("#accordionSidebar").toggleClass("toggled");
    },
    activityStatus(center) {
      SwalCustomBtn.fire({
        title: `${
          center.shop_activity_status == "offline"
            ? "Do you want to do Service Center online"
            : "Do you want to do Service Center offline"
        }?`,
        showCancelButton: true,
        confirmButtonText: `Yes`,
        cancelButtonText: "No",
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          this.changeActivityStatus(center);
        }
      });
    },
    async changeActivityStatus(center) {
      const res = await this.callApi(
        "get",
        "/api/admin/service-centers/activity-status/" + center.shop_id,
        null
      );
      if (res.status == 200) {
        var data = res.data;
        if ((data.status = "success")) {
          this.center.shop_activity_status =
            center.shop_activity_status == "offline" ? "online" : "offline";
          toast = Toast.fire({
            icon: "success",
            title: "Status updated successfully",
            timer: 2500,
          });
        } else {
          toast = Toast.fire({
            icon: "warning",
            title: "something want wrong",
            timer: 2500,
          });
        }
      } else {
        toast = Toast.fire({
          icon: "warning",
          title: "something want wrong",
          timer: 2500,
        });
      }
    },
  },
  sideBar() {
    $("#accordionSidebar").toggleClass("toggled");
  },
};
</script>
<style>
.offlineUser {
  display: block;
  width: 11px;
  height: 11px;
  background-color: #da3f30;
  border-radius: 50%;
  margin-bottom: 24px;
  margin-left: -8px;
}
.onlineUser {
  display: block;
  width: 11px;
  height: 11px;
  background-color: #0fcc45;
  border-radius: 50%;
  margin-bottom: 24px;
  margin-left: -8px;
}
</style>
