<template>
  <footer class="sticky-footer bg-white">
    <div class="container my-auto">
      <div class="copyright my-auto">
        <span>Copyright © {{ currentYear }}</span>
        <span class="float-right">
          <a class="text-muted" target="_blank" href="https://r2logics.com/">{{
            yourCompanyName
          }}</a>
          . All rights reserved.
        </span>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  data() {
    return {
      currentYear: new Date().getFullYear(),
      yourCompanyName: "R2Logics Technologies Pvt Ltd",
    };
  },
};
</script>
