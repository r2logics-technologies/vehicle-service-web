<template>
    <div>
        <div class="d-flex justify-content-between">
            <h3 class="h3 mb-2 text-gray-800">{{ pageTitle }}</h3>
        </div>
        <nav aria-label="breadcrumb" v-if="breadcrumbList">
            <ol class="breadcrumb bg-light">
                <li class="breadcrumb-item" v-for="(item, index) in breadcrumbList" :key="index">
                    <router-link class="text-danger" :to="item.to" v-if="item.to">{{
                        item.name
                    }}</router-link>
                    <span v-else>{{ item.name }}</span>
                </li>
            </ol>
        </nav>
    </div>
</template>
<script>
export default {
    name: "Breadcrumb",
    props: ["pageTitle", "breadcrumbList"],
    methods: {
        goBack() {
            this.$router.go(-1)
        }
    }
};

</script>
<style scoped>
@media only screen and (max-width: 600px) {
    .slot {
        margin-top: 0 !important;
    }

    .btn {
        font-size: 16px !important;
    }
}

.breadcrumb-item+.breadcrumb-item::before {
    display: inline-block;
    padding-right: 0.5rem;
    color: #6c757d;
    content: " / ";
}

</style>
