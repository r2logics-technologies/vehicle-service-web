<template>
<div>
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Add Category</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-10 col-sm-12">
            <label>Name</label>
            <input
              type="text"
              class="form-control"
              placeholder="Enter Category Name ..."
              v-model="editItem.category_name"
              :class="errorItem.category_name != '' ? 'is-invalid' : ''"
            />
            <span
              class="error-label"
              v-if="errorItem.category_name != ''"
              v-text="errorItem.category_name"
            ></span>
          </div>
          <div class="col-md-2 col-sm-12 py-4">
            <button type="submit" class="btn mt-2 btn-danger w-100" @click="submitData()">
              SUBMIT
            </button>
          </div>
        </div>
      </div>
    </div>
</div>
</template>
<script>
export default {
  data: () => ({
    editItem: {},
    isValid: true,
    edited: -1,
    errorItem: {
      category_name: "",
    },
  }),
  mounted() {
    window.scrollTo(0, 0);
  },
  methods: {
    validationForm() {
      if (this.editItem.category_name == null || this.editItem.category_name == "") {
        this.errorItem.category_name = "Category is required";
        this.isValid = false;
      } else {
        this.isValid = true;
      }
      return this.isValid;
    },
    async submitData() {
      var isValid = await this.validationForm();
      if (!isValid) return;

      var data = new FormData();
      data.append("name", this.editItem.category_name);
      data.append("edited", this.edited);
      const res = await this.callApi("post", "/api/admin/categories/save/update", data);
      if (res.status == 200) {
        var data = res.data;
        console.log(data);
        if (data.status == "success") {
          this.$parent.updateData(data.category);
          this.resetFormData();
          Toast.fire({
            icon: "success",
            title: data.message,
            timer: 2500,
          });
        } else {
          Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          });
        }
      } else {
        Toast.fire({
          icon: res.data.status,
          title: res.data.message,
          timer: 2500,
        });
      }
    },
    resetFormData() {
      this.editItem = {};
      this.errorItem = {
        category_name: "",
      };
      this.edited = -1;
    },
    edit(data) {
      this.editItem = data;
      this.edited = data.category_id;
    },
  },
};
</script>
