<template>
    <div class="container-fluid">
        <div class="container-xxl flex-grow-1 container-p-y">
            <Breadcrumb pageTitle="Scanner" :breadcrumbList="breadcrumbList" />
        </div>
        <div class="card shadow mb-4">
            <div
                class="d-flex justify-content-between align-items-center card-header py-3"
            >
                <h6 class="m-0 font-weight-bold text-danger">Scanner</h6>
                <div class="text-center">
                    <button
                        type="submit"
                        :class="[
                            'btn',
                            'mt-2',
                            'btn-info',
                            { 'd-none': isEdit },
                        ]"
                        @click="editScanner()"
                    >
                        Edit
                    </button>

                    <button
                        type="submit"
                        :class="[
                            'btn',
                            'mt-2',
                            'btn-primary',
                            { 'd-none': !isEdit },
                        ]"
                        @click="submitData()"
                    >
                        Save
                    </button>
                    <button
                        type="submit"
                        :class="[
                            'btn',
                            'mt-2',
                            'btn-danger',
                            { 'd-none': !isEdit },
                        ]"
                        @click="editScanner()"
                    >
                        Cancel
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div
                        class="col-md-6 m-auto col-sm-12"
                        :style="!isEdit ? { 'pointer-events': 'none' } : null"
                    >
                        <div
                            class="border rounded p-2 text-center position-relative"
                        >
                            <img
                                :src="getImageUrl"
                                style="height: 300px; max-width: 100%"
                            />
                            <i
                                :class="[
                                    'fa',
                                    'fa-edit',
                                    'text-info',
                                    { 'd-none': !isEdit },
                                ]"
                                style="
                                    float: right;
                                    top: 10px;
                                    position: absolute;
                                    right: 10px;
                                    font-size: 30px;
                                    opacity: 1.5;
                                "
                            ></i>
                            <input
                                type="file"
                                class="image-input"
                                :disabled="!isEdit"
                                accept="image/jpeg, image/jpg, image/png, application/pdf"
                                @change="selectImage($event)"
                            />

                            <div>
                                <label class="mt-2">Scanner</label>
                            </div>
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
    data: () => ({
        breadcrumbList: [
            {
                to: { name: "admin.dashboard" },
                name: "Home",
            },
            {
                name: "Scanner",
            },
        ],
        srcImageFile: null,
        imageFilePath: null,
        isEdit: false,
    }),
    mounted() {
        window.scrollTo(0, 0);
    },
    created() {
        toast = Toast.fire({
            icon: "warning",
            title: "Loading Data...",
            timer: 0,
        });
        this.showData();
    },
    computed: {
        getImageUrl: function () {
            return this.srcImageFile != null && this.srcImageFile != ""
                ? this.srcImageFile
                : "/assets/img/scanner.png";
        },
    },
    methods: {
        editScanner() {
            this.isEdit = !this.isEdit;
        },
        async showData() {
            const res = await this.callApi("get", "/api/admin/scanner", null);
            if (res.status == 200) {
                var data = res.data;
                if (data.status == "success") {
                    this.srcImageFile = data.scanner.scanner_image;
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
        selectImage(e) {
            const file = e.target.files[0];
            if (file) {
                this.srcImageFile = URL.createObjectURL(file);
                URL.revokeObjectURL(file);
                this.imageFilePath = file;
            } else {
                this.srcImageFile = null;
                this.imageFilePath = null;
            }
        },
        async submitData() {
            Toast.fire({
                icon: "warning",
                title: "Uploading...",
                timer: 0,
            });
            var formData = new FormData();
            formData.append(
                "image",
                this.imageFilePath != null && this.imageFilePath != ""
                    ? this.imageFilePath
                    : null
            );
            const res = await this.callApi(
                "post",
                "/api/admin/scanner/save/update",
                formData,
                "multipart"
            );
            if (res.status == 200) {
                Toast.close();
                var data = res.data;
                if (data.status == "success") {
                    Toast.fire({
                        icon: "success",
                        title: data.message,
                        timer: 2500,
                    });
                    setTimeout(() => {
                        this.srcImageFile = data.scanner.scanner_image;
                        this.isEdit = false;
                    }, 2500);
                } else {
                    Toast.fire({
                        icon: "warning",
                        title: data.message,
                        timer: 2500,
                    });
                }
            } else {
                Toast.fire({
                    icon: "warning",
                    title: "opps something went wrong..",
                    timer: 2500,
                });
            }
        },
    },
};
</script>
