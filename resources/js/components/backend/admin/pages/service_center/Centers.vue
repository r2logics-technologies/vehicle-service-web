<template>
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between mb-4">
      <Breadcrumb pageTitle="Service Center" :breadcrumbList="breadcrumbList" />
      <div class="float-end mt-2">
        <button class="btn btn-danger rounded-circle py-2" title="ADD MORE"
          @click="$router.push('/admin/service-centers/create')">
          <i class="fa fa-plus"></i>
        </button>
      </div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Service Center List</h6>
      </div>
      <div class="card-body">
        <div v-show="!noData" class="table table-responsive">
          <Table ref="table" tableId="center_table" :tableHead="tableHead" :tableData="centerFilter" />
        </div>
        <div v-show="noData">
          <NoData />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
var toast
export default {
  data: () => ({
    breadcrumbList: [
      {
        to: { name: "admin.dashboard" },
        name: "Home",
      },
      {
        name: "Service Center List",
      },
    ],
    centers: [],
    noData: true,
    tableHead: {
      items: {
        item1: { label: 'Sr.No.' },
        item2: { label: 'image' },
        item3: { label: 'Center/Owner' },
        item4: { label: 'Recommend' },
        item5: { label: 'Spotlight' },
        item6: { label: 'Popular' },
        item7: { label: 'Featured' },
        item8: { label: 'Offline/Online' },
        item9: { label: 'Status' },
      },
      actions: true,
    },
    tableData: [],
  }),
  mounted() {
    window.scrollTo(0, 0)
  },
  created() {
    toast = Toast.fire({
      icon: 'warning',
      title: 'Loadding Data...',
      timer: 0,
    })
    this.showData()
  },
  computed: {
    centerFilter: function () {
      if (this.searchItem && this.searchItem != null && this.searchItem != '') {
        return this.tableData.filter((center) => {
          return (
            center.data.item3
              .toLowerCase()
              .indexOf(this.searchItem.toLowerCase()) !== -1 ||
            center.data.item4
              .toLowerCase()
              .indexOf(this.searchItem.toLowerCase()) !== -1
          )
        })
      } else {
        return this.tableData
      }
    },
  },
  methods: {
    async showData() {
      const res = await this.callApi('get', '/api/admin/service-centers', null)
      if (res.status == 200) {
        var data = res.data
        if (data.status == 'success') {
          this.centers = data.centers
          this.initTable()
        } else {
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          })
        }
      } else {
        toast = Toast.fire({
          icon: 'error',
          title: 'Oops!! Something went wrong. Try again..',
          timer: 2500,
        })
      }

      toast.close()
    },

    initTable() {
      this.noData = false
      this.tableData = []
      this.centers.forEach((element) => {
        this.tableData.push({
          item: element,
          data: {
            item1: element.shop_id,
            item2: {
              type: 'file',
              path: element.image,
              height: '70px',
            },
            item3: `<div><span title="${element.shop_id}"><i class="fa fa-store"></i> - ${element.shop_name}</span><br /><span><i class="fa fa-user"></i> - ${element.shop_owner}</span><br /><span style="font-size:13px; class="ms-2"><i class='fa fa-phone'></i> - ${element.shop_mobile}</span><br /><span class="ms-1 text-lowercase"><span class="fw-bold">@ </span > - ${element.shop_email}</span></div>`,

            item4: {
              type: 'action',
              options: {
                style: 'background: none;border:none;',
                class:
                  element.shop_recommend == 0
                    ? 'fa fa-toggle-on text-success fa-lg my-5'
                    : 'fa fa-toggle-off text-secondary  fa-lg my-5',
                method: this.recommendStatus,
              },
            },
            item5: {
              type: 'action',
              options: {
                style: 'background: none;border:none;',
                class:
                  element.shop_spotlight == 0
                    ? 'fa fa-toggle-on text-success fa-lg my-5'
                    : 'fa fa-toggle-off text-secondary  fa-lg my-5',
                method: this.spotlightStatus,
              },
            },
            item6: {
              type: 'action',
              options: {
                style: 'background: none;border:none;',
                class:
                  element.shop_popular == 0
                    ? 'fa fa-toggle-on text-success fa-lg my-5'
                    : 'fa fa-toggle-off text-secondary  fa-lg my-5',
                method: this.popularStatus,
              },
            },
            item7: {
              type: 'action',
              options: {
                style: 'background: none;border:none;',
                class:
                  element.shop_featured == 0
                    ? 'fa fa-toggle-on text-success fa-lg my-5'
                    : 'fa fa-toggle-off text-secondary  fa-lg my-5',
                method: this.featuredStatus,
              },
            },

            item8: {
              type: 'action',
              options: {
                method: this.activityStatus,
                class:
                  element.shop_activity_status == 'offline'
                    ? 'fa fa-toggle-off text-secondary  fa-lg my-5'
                    : 'fa fa-toggle-on text-success fa-lg my-5',
                style: 'border:none;background:none;',
              },
            },
            item9: element.shop_status,
          },
          action: {
            edit: true,
            delete: true,
            status: element.shop_status,
          },
        })
      })
      this.$refs.table.loadTable()
    },
    edit(item) {
      this.$router.push(`/admin/service-centers/edit/${item.shop_id}/form`)
    },
    async deleteData(item, index, status) {
      var data = new FormData()
      data.append('status', status)
      const res = await this.callApi(
        'post',
        `/api/admin/service-centers/change/status/${item.shop_id}`,
        data,
      )
      if (res.status == 200) {
        var data = res.data
        if (data.status == 'success') {
          if (status == 'deleted') {
            this.centers.splice(index, 1)
            this.tableData.splice(index, 1)
            if (!this.centers.length) {
              this.noData = true
            }
          } else {
            this.centers[index] = data.center
            this.tableData[index].data.item9 = data.center.shop_status
            this.tableData[index].action.status = data.center.shop_status
          }
          SwalCustomBtn.fire(
            status == 'deleted' ? 'Deleted!' : 'Status Change',
            data.message,
            'success',
          )
        }
      }
    },

    activityStatus(item, index) {
      SwalCustomBtn.fire({
        title: `${item.shop_activity_status == 'offline'
          ? 'Do you want to do Service Center online'
          : 'Do you want to do Service Center offline'
          }?`,
        showCancelButton: true,
        confirmButtonText: `Yes`,
        cancelButtonText: 'No',
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          this.changeActivityStatus(item, index)
        }
      })
    },
    async changeActivityStatus(item, index) {
      const res = await this.callApi(
        'get',
        '/api/admin/service-centers/activity-status/' + item.shop_id,
        null,
      )
      if (res.status == 200) {
        var data = res.data
        if ((data.status = 'success')) {
          this.centers[index].shop_activity_status =
            item.shop_activity_status == 'offline' ? 'online' : 'offline'
          this.initTable()
          toast = Toast.fire({
            icon: 'success',
            title: 'Status updated successfully',
            timer: 2500,
          })
        } else {
          toast = Toast.fire({
            icon: 'warning',
            title: 'something want wrong',
            timer: 2500,
          })
        }
      } else {
        toast = Toast.fire({
          icon: 'warning',
          title: 'something want wrong',
          timer: 2500,
        })
      }
    },

    recommendStatus(item, index) {
      SwalCustomBtn.fire({
        title: `${item.shop_recommend == 1
          ? 'Mark as Recommend'
          : 'Remove from Recommend'
          }?`,
        showCancelButton: true,
        confirmButtonText: `Yes`,
        cancelButtonText: 'No',
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          this.recommendChangeStatus(item, index)
        }
      })
    },
    async recommendChangeStatus(item, index) {
      var data = new FormData()
      var status = ''
      if (item.shop_recommend == 1) {
        status = 0
      } else {
        status = 1
      }
      data.append('recommend', status)
      const res = await this.callApi(
        'post',
        `/api/admin/service-centers/recommend/${item.shop_id}`,
        data,
      )
      if (res.status == 200) {
        var data = res.data
        if (data.status == 'success') {
          this.centers[index] = data.center
          this.initTable()
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          })
        } else {
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          })
        }
      } else {
        toast = Toast.fire({
          icon: 'error',
          title: 'Something Went Wrong, Not able to recommend',
          timer: 0,
        })
      }
    },
    spotlightStatus(item, index) {
      SwalCustomBtn.fire({
        title: `${item.shop_spotlight == 1
          ? 'Mark as Spotlight'
          : 'Remove from Spotlight'
          }?`,
        showCancelButton: true,
        confirmButtonText: `Yes`,
        cancelButtonText: 'No',
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          this.spotlightChangeStatus(item, index)
        }
      })
    },
    async spotlightChangeStatus(item, index) {
      var data = new FormData()
      var status = ''
      if (item.shop_spotlight == 0) {
        status = 1
      } else {
        status = 0
      }
      data.append('spotlight', status)
      const res = await this.callApi(
        'post',
        `/api/admin/service-centers/spotlight/${item.shop_id}`,
        data,
      )
      if (res.status == 200) {
        var data = res.data
        if (data.status == 'success') {
          this.centers[index] = data.center
          this.initTable()
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          })
        } else {
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          })
        }
      } else {
        toast = Toast.fire({
          icon: 'error',
          title: 'Something Went Wrong, Not able to spotlight',
          timer: 0,
        })
      }
    },
    popularStatus(item, index) {
      SwalCustomBtn.fire({
        title: `${item.shop_popular == 1 ? 'Mark as Popular' : 'Remove from Popular'
          }?`,
        showCancelButton: true,
        confirmButtonText: `Yes`,
        cancelButtonText: 'No',
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          this.popularChangeStatus(item, index)
        }
      })
    },
    async popularChangeStatus(item, index) {
      var data = new FormData()
      var status = ''
      if (item.shop_popular == 1) {
        status = 0
      } else {
        status = 1
      }
      data.append('popular', status)
      const res = await this.callApi(
        'post',
        `/api/admin/service-centers/popular/${item.shop_id}`,
        data,
      )
      if (res.status == 200) {
        var data = res.data
        if (data.status == 'success') {
          this.centers[index] = data.center
          this.initTable()
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          })
        } else {
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          })
        }
      } else {
        toast = Toast.fire({
          icon: 'error',
          title: 'Something Went Wrong, Not able to popular',
          timer: 0,
        })
      }
    },
    featuredStatus(item, index) {
      SwalCustomBtn.fire({
        title: `${item.shop_featured == 1 ? 'Mark as Featured' : 'Remove from Featured'
          }?`,
        showCancelButton: true,
        confirmButtonText: `Yes`,
        cancelButtonText: 'No',
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) {
          this.featuredChangeStatus(item, index)
        }
      })
    },
    async featuredChangeStatus(item, index) {
      var data = new FormData()
      var status = ''
      if (item.shop_featured == 1) {
        status = 0
      } else {
        status = 1
      }
      data.append('featured', status)
      const res = await this.callApi(
        'post',
        `/api/admin/service-centers/featured/${item.shop_id}`,
        data,
      )
      if (res.status == 200) {
        var data = res.data
        if (data.status == 'success') {
          this.centers[index] = data.center
          this.initTable()
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          })
        } else {
          toast = Toast.fire({
            icon: data.status,
            title: data.message,
            timer: 2500,
          })
        }
      } else {
        toast = Toast.fire({
          icon: 'error',
          title: 'Something Went Wrong, Not able to featured',
          timer: 0,
        })
      }
    },
  },
}
</script>
