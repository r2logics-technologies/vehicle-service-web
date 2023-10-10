import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

import Dashboard from './components/backend/admin/pages/dashboard/Dashboard.vue'
import AdminProfile from './components/backend/admin/pages/profile/Profile.vue'
import AdminServiceCenter from './components/backend/admin/pages/service_center/Centers.vue'
import AdminServiceCenterCreate from './components/backend/admin/pages/service_center/Create.vue'
import AdminDriver from './components/backend/admin/pages/delivery_mans/DeliveryMans.vue'
import AdminDriverCreate from './components/backend/admin/pages/delivery_mans/Create.vue'
import AdminCategory from './components/backend/admin/pages/categories/Categories.vue'
import AdminCategoryCreate from './components/backend/admin/pages/categories/Create.vue'
import AdminPackage from './components/backend/admin/pages/packages/Packages.vue'
import AdminPackageCreate from './components/backend/admin/pages/packages/Create.vue'
import AdminReportCustomer from './components/backend/admin/pages/reports/customers/Customers.vue'
import AdminReportCustomerInvoice from './components/backend/admin/pages/reports/customers/CustomerInvoice.vue'
import AdminReportCustomerDetails from './components/backend/admin/pages/reports/customers/CustomerServiceDetails.vue'
import AdminReportCenter from './components/backend/admin/pages/reports/service_center_reports/ServiceCenter.vue'
import AdminReportCenterDetails from './components/backend/admin/pages/reports/service_center_reports/ServiceCenterDetails.vue'
import AdminReportBookingDetails from "./components/backend/admin/pages/reports/service_center_reports/ServiceBookingDetailPage.vue";
import AdminReportBooking from './components/backend/admin/pages/reports/booking_reports/Bookings.vue'
import AdminReportBookingInvoice from './components/backend/admin/pages/reports/booking_reports/BookingInvoice.vue'
import AdminBanner from './components/backend/admin/pages/banner/Banner.vue'
import AdminPolicy from './components/backend/admin/pages/privacy_and_policy/Policy.vue'
import AdminTerms from './components/backend/admin/pages/term_and_condition/Terms.vue'
import AdminHelpDesk from './components/backend/admin/pages/help_desk/HelpDesk.vue'
import AdminNotification from './components/backend/admin/pages/notification/Notification.vue'
import AdminNotificationCreate from './components/backend/admin/pages/notification/Create.vue'
import AdminSetting from './components/backend/admin/pages/setting/Setting.vue'
import AdminServices from './components/backend/admin/pages/services/Services.vue'
import AdminServicesCreate from './components/backend/admin/pages/services/Create.vue'
import AdminInstruction from './components/backend/admin/pages/instruction/Instruction.vue'

// SERVICE CENTER COMPONENT
import ServiceDashboard from './components/backend/service_center/pages/dashboard/Dashboard.vue'
import ServicePendingBookings from './components/backend/service_center/pages/pending_bookings/PendingBookings.vue'
import ServiceCompletedBookings from './components/backend/service_center/pages/completed_bookings/CompletedBookings.vue'
import ServiceInProcessBookings from './components/backend/service_center/pages/inprocess_bookings/ServiceInProcess.vue'
import ServiceCustomer from './components/backend/service_center/pages/customers/Customer.vue'
import ServiceCustomerDetails from './components/backend/service_center/pages/customers/CustomerServiceDetails.vue'
import ServiceCustomerInvoice from './components/backend/service_center/pages/customers/CustomerInvoice.vue'
import centerNotification from './components/backend/service_center/pages/notification/Notification.vue'

const routes = [
    {
        name: "admin.dashboard",
        path: "/admin/dashboard",
        component: Dashboard,
        props: true,
    },
    {
        name: "admin.profile",
        path: "/admin/profile",
        component: AdminProfile,
        props: true,
    },
    {
        name: "admin.service-centers",
        path: "/admin/service-centers",
        component: AdminServiceCenter,
        props: true,
    },
    {
        name: "admin.service-centers.create",
        path: "/admin/service-centers/create",
        component: AdminServiceCenterCreate,
        props: true,
    },
    {
        name: "admin.service-centers.create.edit",
        path: "/admin/service-centers/edit/:service_center_id/form",
        component: AdminServiceCenterCreate,
        props: true,
    },
    {
        name: "admin.drivers",
        path: "/admin/drivers",
        component: AdminDriver,
        props: true,
    },
    {
        name: "admin.drivers.create",
        path: "/admin/drivers/create",
        component: AdminDriverCreate,
        props: true,
    },
    {
        name: "admin.drivers.create.edit",
        path: "/admin/drivers/edit/:driver_id/form",
        component: AdminDriverCreate,
        props: true,
    },
    {
        name: "admin.categories",
        path: "/admin/categories",
        component: AdminCategory,
        props: true,
    },
    {
        name: "admin.categories.create",
        path: "/admin/categories/create",
        component: AdminCategoryCreate,
        props: true,
    },
    {
        name: "admin.packages",
        path: "/admin/packages",
        component: AdminPackage,
        props: true,
    },
    {
        name: "admin.packages.create",
        path: "/admin/packages/create",
        component: AdminPackageCreate,
        props: true,
    },
    {
        name: "admin.packages.create.edit",
        path: "/admin/packages/edit/:package_id/form",
        component: AdminPackageCreate,
        props: true,
    },
    {
        name: "admin.reports.customers",
        path: "/admin/reports/customers",
        component: AdminReportCustomer,
        props: true,
    },
    {
        name: "admin.reports.service-centers",
        path: "/admin/reports/service-centers",
        component: AdminReportCenter,
        props: true,
    },
    {
        name: "admin.reports.service-center_details",
        path: "/admin/reports/service-center_details/:shop_id",
        component: AdminReportCenterDetails,
        props: true,
    },
    {
        name: "admin.reports.service-booking_details",
        path: "/admin/reports/service-booking_details/:booking_id",
        component: AdminReportBookingDetails,
        props: true,
    },
    {
        name: "admin.reports.booking",
        path: "/admin/reports/booking",
        component: AdminReportBooking,
        props: true,
    },
    {
        name: "admin.reports.booking_invoice",
        path: "/admin/reports/booking_invoice/:bookings_id",
        component: AdminReportBookingInvoice,
        props: true,
    },
    {
        name: "admin.reports.customer_service_details",
        path: "/admin/reports/customer_service_details/:id",
        component: AdminReportCustomerDetails,
        props: true,
    },
    {
        name: "admin.reports.customer_invoice",
        path: "/admin/reports/customer_invoice/:booking_id",
        component: AdminReportCustomerInvoice,
        props: true,
    },
    {
        name: "admin.banner",
        path: "/admin/banner",
        component: AdminBanner,
        props: true,
    },
    {
        name: "admin.privacy-and-policy",
        path: "/admin/privacy-policy",
        component: AdminPolicy,
        props: true,
    },
    {
        name: "admin.term-and-condition",
        path: "/admin/term-condition",
        component: AdminTerms,
        props: true,
    },
    {
        name: "admin.help-desk",
        path: "/admin/help-desk",
        component: AdminHelpDesk,
        props: true,
    },
    {
        name: "admin.notification",
        path: "/admin/notification",
        component: AdminNotification,
        props: true,
    },
    {
        name: "admin.notification.create",
        path: "/admin/notification/create",
        component: AdminNotificationCreate,
        props: true,
    },
    {
        name: "admin.setting",
        path: "/admin/app/setting",
        component: AdminSetting,
        props: true,
    },
    {
        name: "admin.services",
        path: "/admin/services",
        component: AdminServices,
        props: true,
    },
    {
        name: "admin.services.create",
        path: "/admin/services/create",
        component: AdminServicesCreate,
        props: true,
    },
    {
        name: "admin.services.create.edit",
        path: "/admin/services/edit/:service_id/form",
        component: AdminServicesCreate,
        props: true,
    },
    {
        name: "admin.instruction",
        path: "/admin/instruction",
        component: AdminInstruction,
        props: true,
    },

    // Service Center Route

    {
        name: "service-center.dashboard",
        path: "/service-center/dashboard",
        component: ServiceDashboard,
        props: true,
    },
    {
        name: "service-center.pending_bookings",
        path: "/service-center/pending_bookings",
        component: ServicePendingBookings,
        props: true,
    },
    {
        name: "service-center.completed_bookings",
        path: "/service-center/completed_bookings",
        component: ServiceCompletedBookings,
        props: true,
    },
    {
        name: "service-center.in_process_bookings",
        path: "/service-center/in_process_bookings",
        component: ServiceInProcessBookings,
        props: true,
    },
    {
        name: "service-center.customers",
        path: "/service-center/customers",
        component: ServiceCustomer,
        props: true,
    },
    {
        name: 'service-center.customer_service_details',
        path: '/service-center/reports/customers/:id',
        component: ServiceCustomerDetails,
        props: true,
    },
    {
        name: 'service-center.reports.customer_invoice',
        path: '/service-center/reports/customer_invoice/:booking_id',
        component: ServiceCustomerInvoice,
        props: true,
    },
    {
        name: 'service-center.profile',
        path: '/service-center/profile/:service_center_id',
        component: AdminServiceCenterCreate,
        props: true,
    },
    {
        name: 'service-center.notification',
        path: '/service-center/notification',
        component: centerNotification,
        props: true,
    }
];

export default new Router({
    mode: 'history',
    routes,
})
