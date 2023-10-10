 <template>
    <div>
        <div id="wrapper">
			<!-- ================================ -->
			<!-- Sidebar -->
			<Sidebar />
			<!-- ================================ -->
			<!-- End of Sidebar -->

			<!-- Content Wrapper -->
			<div id="content-wrapper" class="d-flex flex-column">
				<!-- Main Content -->
				<div id="content">
					<!-- ====================== -->
					<!-- Header -->
					<Header />
					<!-- ====================== -->
					<!-- End Header  -->
					<!-- ===================== -->
					<!-- main content -->
					<router-view :key="$route.fullPath"></router-view>
					<!-- end content -->
				</div>

				<!-- footer -->
				<Footer />
				<!-- end footer -->
			</div>
		</div>
    </div>
</template>
<script>
export default{

}
</script>
