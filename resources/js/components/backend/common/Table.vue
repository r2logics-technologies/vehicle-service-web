<template>
  <div>
    <table
      :id="tableId"
      class="table display text-nowrap"
      v-show="!loading"
      style="width: 100% !important;"
    >
      <thead>
        <tr>
          <th
            v-for="(item, index) in tableHead.items"
            :key="index"
            :style="thStyle"
          >
            <i :class="item.icon" v-if="item.icon"></i>
            <span>{{ item.label }}</span>
          </th>
          <th v-show="tableHead.actions">
            <span>Actions</span>
          </th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th
            v-for="(item, index) in tableHead.items"
            :key="index"
            :style="thStyle"
          >
            <i :class="item.icon" v-if="item.icon"></i>
            <span>{{ item.label }}</span>
          </th>
          <th v-show="tableHead.actions">
            <span>Actions</span>
          </th>
        </tr>
      </tfoot>
      <tbody class="table-border-bottom-0">
        <tr
          v-for="(tableItem, index) in tableData"
          :key="index"
          :style="
            tableItem.action && tableItem.action.status == 'deactivated'
              ? 'background-color: rgb(255, 225, 225);'
              : 'background-color: rgb(39 108 196 / 9%);'
          "
          :class="isDetail ? 'trContainer' : ''"
          @click="isDetail ? $parent.viewDetailPage(tableItem.item, index) : ''"
        >
          <td
            v-for="(tableData, tdKey) in tableItem.data"
            :key="tdKey"
            :width="tdKey == 'item1' ? '12%' : 'auto'"
            class="text-capitalize"
          >
            <span v-if="tdKey == 'item1'">{{ index + 1 }}</span>
            <span v-else>
              <div v-if="typeof tableData == 'object'">
                <div class="text-center" v-if="tableData.type === 'file'">
                  <img
                    v-if="
                      tableData.path &&
                      (tableData.path.includes('.jpg') ||
                        tableData.path.includes('.jpeg') ||
                        tableData.path.includes('.svg') ||
                        tableData.path.includes('.png'))
                    "
                    :src="tableData.path"
                    :style="
                      tableData.height
                        ? `height: ${tableData.height} !important`
                        : `height: 80px !important`
                    "
                    @click.stop="showImage(tableData.path)"
                  />
                  <div v-else>IMAGE NOT ADDED</div>
                </div>
                <div v-else-if="tableData.type === 'label'">
                  <div
                    :class="tableData.options.class"
                    :style="tableData.options.style"
                  >
                    <span v-html="tableData.options.icon"></span>
                    {{ tableData.options.label }}
                  </div>
                </div>
                <div v-else-if="tableData.type === 'action'">
                  <button
                    @click.stop="
                      tableData.options.method(tableItem.item, index)
                    "
                    :class="tableData.options.class"
                    :style="tableData.options.style"
                  >
                    <span v-html="tableData.options.icon"></span>
                    {{ tableData.options.label }}
                  </button>
                </div>
                <div v-else-if="tableData.type === 'filter'">
                  <span v-if="tableData.options.filter == 'formatTime'">
                    {{ tableData.value | formatTime }}
                  </span>
                  <span v-if="tableData.options.filter == 'formatDate'">
                    {{ tableData.value | formatDate }}
                  </span>
                  <span v-if="tableData.options.filter == 'formatDateMDY'">
                    {{ tableData.value | formatDateMDY }}
                  </span>
                </div>
                <div v-else></div>
              </div>
              <div v-else>
                <span v-html="tableData"></span>
              </div>
            </span>
          </td>

          <td
            v-show="tableItem.action"
            :width="Object.keys(tableItem.action).length * 5 + '%'"
            style="min-width: 20%;"
          >
            <div class="d-flex justify-content-between">
              <i
                v-if="tableItem.action.edit"
                class="fa fa-edit bx-sm text-primary p-1"
                @click.stop="editItem(tableItem.item, index)"
                title="Edit"
              ></i>
              <i
                v-if="tableItem.action.status"
                :class="
                  tableItem.action.status == 'deactivated'
                    ? 'fa fa-toggle-off text-secondary'
                    : 'fa fa-toggle-on text-success '
                "
                class="p-1 bx-sm"
                @click.stop="
                  deleteItem(tableItem.item, index, tableItem.action.status)
                "
                title="Status"
              ></i>
              <i
                v-if="tableItem.action.delete"
                class="fa fa-trash-alt bx-sm text-danger p-1"
                @click.stop="deleteItem(tableItem.item, index, 'deleted')"
                title="Delete"
              ></i>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <LottieAnimation
      v-show="loading"
      :width="120"
      :height="120"
      path="assets/lottie/loader.json"
    />

    <!-- Start Offcanvansa  -->
    <div
      class="modal fade"
      :class="isImage ? 'show' : ''"
      :style="isImage ? 'display:table-column-group' : ''"
      style="margin-top: 5% !important;"
      tabindex="-1"
    >
      <!-- data-bs-backdrop="static" -->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <div
              type="button"
              class="btn-close"
              style="background-color: none;"
              data-bs-dismiss="modal"
              aria-label="Close"
              @click="
                () => {
                  isImage = false
                }
              "
            >
              x
            </div>
          </div>
          <div class="modal-body">
            <img :src="imgPath" style="height: 300px; width: 100%;" />
          </div>
        </div>
      </div>
    </div>
    <!-- End Offcanvas  -->
  </div>
</template>
<script>
import 'jquery/dist/jquery.min.js'
// import "bootstrap/dist/css/bootstrap.min.css";
import 'datatables.net-dt/js/dataTables.dataTables'
import 'datatables.net-dt/css/jquery.dataTables.min.css'
import $ from 'jquery'

export default {
  props: ['tableData', 'tableHead', 'isDetail', 'thStyle', 'tableId'],
  data() {
    return {
      loading: true,
      dataStatus: '',
      tableNewId: '',
      isImage: false,
      imgPath: '',
    }
  },
  mounted() {},
  created() {
    this.tableNewId = this.tableId
  },
  methods: {
    loadTable() {
      this.loading = true
      setTimeout(() => {
        this.loading = false
        if (!$.fn.dataTable.isDataTable('#' + this.tableNewId)) {
          $('#' + this.tableNewId).DataTable({
            columnDefs: [{ targets: 'no-sort', orderable: false }],
          })
        }
      }, 2000)
    },
    updateTableId(id) {
      this.tableNewId = id
    },
    editItem(item, index) {
      this.$parent.edit(item, index)
    },
    updateTable(list) {
      this.tableData = list
    },
    deleteItem(item, index, status) {
      if (status == 'deactivated') {
        this.dataStatus = 'activated'
      } else if (status == 'activated') {
        this.dataStatus = 'deactivated'
      } else {
        this.dataStatus = status
      }
      SwalCustomBtn.fire({
        title: status == 'deleted' ? 'Are you sure?' : '',
        text:
          status == 'deleted'
            ? "You won't be able to revert this!"
            : `You won't ${this.dataStatus} this!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `Yes, ${this.dataStatus}  it!`,
        cancelButtonText: 'No, cancel!',
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          this.$parent.deleteData(item, index, this.dataStatus)
        }
      })
    },
    showImage(imgPath) {
      this.isImage = true
      this.imgPath = imgPath
    },
  },
}
</script>
<style scoped>
.trContainer:hover {
  background-color: rgba(105, 108, 255, 0.16) !important;
  cursor: pointer;
}
</style>
<style>
.bg-success {
  background-color: lightgreen;
}
.bg-dangar {
  background-color: lightcoral;
}
</style>
