<template>
  <ul
    class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion"
    id="accordionSidebar"
  >
    <!-- Sidebar - Brand -->
    <li class="bg-white m-0">
      <router-link
        class="sidebar-brand align-items-center justify-content-center py-0 my-0"
        to="/admin/dashboard"
      >
        <div class="sidebar-brand-icon">
          <img src="/assets/img/lunaaz-logo.png" class="img-fluidf" height="90" alt="" />
          <div class="sidebar-brand-text text-capitalize text-danger text-center">
            lunazMoto
          </div>
        </div>
      </router-link>
    </li>

    <li class="nav-item" v-for="(menu, index) in menus" :key="index">
      <div v-if="menu.subMenuList.length > 0">
        <div
          class="nav-link collapsed"
          data-toggle="collapse"
          :data-target="`#collapseTwo_${index}`"
          aria-expanded="true"
          :aria-controls="`collapseTwo_${index}`"
        >
          <i :class="menu.icon"></i>
          <span>{{ menu.name }}</span>
        </div>
        <div
          :id="`collapseTwo_${index}`"
          class="collapse"
          aria-labelledby="headingTwo"
          data-parent="#accordionSidebar"
        >
          <div class="bg-white py-2 collapse-inner rounded">
            <!-- <h6 class="collapse-header">Custom Components:</h6> -->
            <router-link
              class="collapse-item text-danger"
              v-for="(sMenu, i) in menu.subMenuList"
              :key="i"
              :to="sMenu.to"
            >
              <div @click="sideBarClose()">
                <i :class="sMenu.icon" class="mr-2"></i>
                <span>{{ sMenu.name }}</span>
              </div>
            </router-link>
          </div>
        </div>
      </div>
      <div v-else>
        <div @click="sideBarClose()">
          <router-link class="nav-link" :to="menu.to">
            <i :class="menu.icon"></i>
            <span>{{ menu.name }}</span>
          </router-link>
        </div>
      </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block" />
  </ul>
</template>
<script>
export default {
  data: () => ({
    menuList: [
      {
        name: "Dashboard",
        to: "/admin/dashboard",
        icon: "fas fa-fw fa-tachometer-alt",
        subMenuList: [],
      },
      {
        name: "Banner",
        to: { name: "admin.banner" },
        icon: "fa fa-images",
        subMenuList: [],
      },
      {
        name: "Scanner",
        to: { name: "admin.scanner" },
        icon: "fa fa-qrcode",
        subMenuList: [],
      },
      {
        name: "Categories",
        to: { name: "admin.categories" },
        icon: "fa fa-list",
        subMenuList: [],
      },
      // {
      //   name: "Services",
      //   to: { name: "" },
      //   icon: "fa fa-hammer",
      //   subMenuList: [
      //     {
      //       name: "Add",
      //       to: { name: "admin.services.create" },
      //       icon: "fa fa-plus-circle",
      //     },
      //     {
      //       name: "List",
      //       to: { name: "admin.services" },
      //       icon: "fa fa-list",
      //     },
      //   ],
      // },
      {
        name: "Service Centers",
        to: { name: "" },
        icon: "fa fa-wrench",
        subMenuList: [
          {
            name: "Add",
            to: { name: "admin.service-centers.create" },
            icon: "fa fa-plus-circle",
          },
          {
            name: "List",
            to: { name: "admin.service-centers" },
            icon: "fa fa-list",
          },
        ],
      },
      {
        name: "Delivery Man",
        to: { name: "" },
        icon: "fa fa-shipping-fast",
        subMenuList: [
          {
            name: "Add",
            to: { name: "admin.drivers.create" },
            icon: "fa fa-plus-circle",
          },
          {
            name: "List",
            to: { name: "admin.drivers" },
            icon: "fa fa-list",
          },
        ],
      },

      {
        name: "Packages/Plains",
        to: { name: "" },
        icon: "fa fa-cubes",
        subMenuList: [
          {
            name: "Add",
            to: { name: "admin.packages.create" },
            icon: "fa fa-plus-circle",
          },
          {
            name: "List",
            to: { name: "admin.packages" },
            icon: "fa fa-list",
          },
        ],
      },
      {
        name: "Service Instruction",
        to: { name: "admin.instruction" },
        icon: "fa fa-list-alt",
        subMenuList: [],
      },
      {
        name: "Privacy And Policy",
        to: { name: "admin.privacy-and-policy" },
        icon: "fa fa-list-alt",
        subMenuList: [],
      },
      {
        name: "Term And Condition",
        to: { name: "admin.term-and-condition" },
        icon: "fa fa-list-alt",
        subMenuList: [],
      },
      {
        name: "Help Desk",
        to: { name: "admin.help-desk" },
        icon: "fa fa-info-circle",
        subMenuList: [],
      },
      {
        name: "Notification",
        to: { name: "admin.notification" },
        icon: "fa fa-bell",
        subMenuList: [],
      },
      {
        name: "Reports",
        to: { name: "" },
        icon: "fa fa-flag",
        subMenuList: [
          {
            name: "Customer",
            to: { name: "admin.reports.customers" },
            icon: "fa fa-users",
          },
          {
            name: "Service Center Report",
            to: { name: "admin.reports.service-centers" },
            icon: "fa fa-wrench",
          },
          {
            name: "Booking Report",
            to: { name: "admin.reports.booking" },
            icon: "fa fa-file",
          },
        ],
      },
    ],
    serviceMenuList: [
      {
        name: "Dashboard",
        to: { name: "service-center.dashboard" },
        icon: "fas fa-fw fa-tachometer-alt",
        subMenuList: [],
      },
      {
        name: "Customers",
        to: { name: "service-center.customers" },
        icon: "fa fa-users",
        subMenuList: [],
      },
      {
        name: "Pending Services",
        to: { name: "service-center.pending_bookings" },
        icon: "fa fa-clock",
        subMenuList: [],
      },
      {
        name: "Inprocess Services",
        to: { name: "service-center.in_process_bookings" },
        icon: "fa fa-spinner ",
        subMenuList: [],
      },
      {
        name: "Services History",
        to: { name: "service-center.completed_bookings" },
        icon: "fa fa-book",
        subMenuList: [],
      },
      {
        name: "Notification",
        to: { name: "service-center.notification" },
        icon: "fa fa-bell",
        subMenuList: [],
      },
    ],
    menus: [],
  }),
  created() {
    if (this.$route.path.includes("/admin/")) {
      this.menus = this.menuList;
    } else {
      this.menus = this.serviceMenuList;
    }
  },

  methods: {
    sideBarClose() {
      $("#accordionSidebar").toggleClass(
        "toggled",
        $(window).width() < 768 && !$("#accordionSidebar").hasClass("toggled")
      );
    },
  },
};
</script>
